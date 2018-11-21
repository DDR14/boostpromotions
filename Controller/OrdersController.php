<?php

App::uses("AppController", "Controller");

class OrdersController extends AppController {

    /**
     * method isAuthorized
     * description controller level isAuthorized method
     */
    public function isAuthorized($customer = null) {
        // All registered users can add posts
        if ($this->action === 'add') {
            return true;
        }

        // The owner of a post can edit and delete it
        if (in_array($this->action, array('edit', 'delete', 'view', 'proof'))) {
            $orderId = (int) $this->request->params['pass'][0];
            if ($this->Order->isOwnedBy($orderId, $customer['customers_id'])) {
                return true;
            }

            return false;
        }

        return parent::isAuthorized($customer);
    }

    /**
     * method index
     * url /orders/
     */
    public function index() {
        $custId = $this->Auth->user("customers_id");

        $ordersData = $this->Order->find("all", array(
            "conditions" => array(
                "Order.customers_id" => $custId
            ),
            "order" => array(
                "date_purchased" => "desc"
            ),
            "contain" => array(
                "OrdersTotal",
                "OrdersProduct",
                "OrdersStatus"
            )
        ));

        $this->set("orders", $ordersData);
    }

    /**
     * method view
     * url /orders/view/id
     */
    public function view($id = null) {
        if (is_null($id)) {
            return $this->redirect($this->referer());
        }

        $orderData = $this->Order->find("first", array(
            "conditions" => array(
                "Order.orders_id" => $id
            ),
            "contain" => array(
                "OrdersTotal",
                "OrdersProduct" => array(
                    "Product" => array(
                        "ProductsDescription"
                    ),
                    "CustomCo" => [
                        "Proof" => ["conditions" => ['status' => 1]]]
                ),
                "OrdersStatusHistory" => ["OrdersStatus"],
                "OrdersStatus"
            )
        ));

        // Arrange the array order here to display it
        // properly in the view.
        $ordersTotal = [];
        foreach ($orderData['OrdersTotal'] as $total) {

            switch ($total['title']) {
                case 'Sub-Total:':
                    $ordersTotal[0] = $total;
                    break;
                case 'Tax':
                    $ordersTotal[2] = $total;
                    break;
                case 'Total':
                    $ordersTotal[3] = $total;
                    break;

                default:
                    $ordersTotal[1] = $total;
                    break;
            }
        }


        ksort($ordersTotal);
        $orderData['OrdersTotal'] = $ordersTotal;

        $this->set("orderData", $orderData);
    }

    /**
     * method proof
     * url /orders/proof/:id
     */
    public function proof($id) {
        // Get product data
        $order_products = $this->Order->OrdersProduct->find("all", [
            "conditions" => [
                "OrdersProduct.orders_id" => $id,
                "Product.require_artwork" => 1
            ],
            "order" => ["OrdersProduct.orders_products_id DESC"],
            "contain" => [
                "CustomCo" => ["Proof" => ['order' => ['status, date_modified DESC']]],
                "Product" => ['fields' => ['products_image', 'products_id', 'products_model', 'master_categories_id']]
            ]
        ]);

        $order = $this->Order->find('first', [
            'conditions' => ['orders_id' => $id],
            'fields' => ['orders_status']
        ]);
        $status = $order['Order']['orders_status'];

        $this->loadModel('Design');

        $hasProof = false;
        foreach ($order_products as $key => $value):
            if ($value['CustomCo']["Proof"]) {
                $hasProof = true;
            }

            //super hacky
            $productType = $this->Utility->checkTagType($value);
            $designs = [];
            if ($value['CustomCo']['title'] == '[[v2_proof:on]]' || in_array($productType, ['STOCK', 'MODIFIED'])):
                $designs_only = [];
                $designandqty = [];
                foreach (explode(",", $value['CustomCo']['customs']) as $design) {
                    if (strpos($design, '=') === false) {
                        continue;
                    }
                    list($k, $v) = explode("=", $design);
                    $s = '';
                    if (strpos($k, '>') !== false) {
                        list($k, $s) = explode(">", $k);
                    }

                    $designandqty[strtoupper(trim($k))] = ['qty' => $v, 'shp' => $s];
                    $designs_only[] = trim($k);
                }
                $customs = $this->Design->find('all', [
                    'conditions' => [
                        "products_model" => $designs_only
                    ],
                    "fields" => ["products_image", "products_model"]
                ]);
                foreach ($customs as $custom) {
                    $proofs = [];
                    foreach ($value['CustomCo']['Proof'] as $proof) {
                        if ($proof['design_model'] == $custom['Design']['products_model']) {
                            $proofs[] = $proof;
                        }
                    }
                    $designs[] = [
                        'products_image' => $custom['Design']['products_image'],
                        'products_model' => $custom['Design']['products_model'],
                        'quantity' => $designandqty[$custom['Design']['products_model']]['qty'],
                        'proofs' => $proofs
                    ];
                }
            endif;

            //restructure the array for easier viewing
            if (!$designs) {
                $designs[] = [
                    'quantity' => $value['OrdersProduct']['products_quantity'],
                    'proofs' => $value['CustomCo']['Proof']
                ];
            }
            $order_products[$key]['CustomCo']['Proof'] = [];
            $order_products[$key]["designs"] = $designs;
        endforeach;

        $this->set(compact("order_products", "hasProof", "designandqty", "status"));
    }

    /**
     * method appProveProof
     * url [post]/orders/approveProof
     */
    public function approveProof($id, $order_id) {
        if (!$this->request->is("post")) {
            return $this->redirect($this->referer());
        }

        // update the proof data
        $this->Order->Proof->id = $id;
        $this->Order->Proof->saveField("status", 1);

        $this->approveOrder($order_id);

        $this->Flash->success("Proof Approved", array("key" => "proofApproved"));

        // TODO: redirect to payment method
        return $this->redirect($this->referer());
    }

    /**
     * 
     * @param type $order_id
     */
    function approveOrder($order_id) {
        //check IF there are still pending proofs before updating artwork_approved
        $result = $this->Order->Proof->query("SELECT COUNT(*) AS RESULT  FROM (
    SELECT a.id FROM naz_custom_co a 
    INNER JOIN zen_orders_products d
    ON d.orders_products_id = a.orders_products_id
    INNER JOIN zen_products b
    ON d.products_id = b.products_id
    LEFT JOIN proofs c
    ON c.naz_custom_id = a.id 
    WHERE a.order_id = ?
    AND b.require_artwork = '1'
    GROUP BY a.orders_products_id, c.design_model HAVING MIN(c.status) IS NULL OR MIN(c.status) <> 1 )as a", [$order_id]);

        if ($result[0][0]['RESULT'] == 0) {

            $date = date("Ymd");

            $check = $this->Order->Proof->query("SELECT payment_made, ship_by, 
        (SELECT COUNT(x.orders_products_id)
        FROM zen_orders_products x
        WHERE x.products_id IN('990','853') 
        AND x.orders_id = ?) AS hasLanyard 
FROM zen_orders WHERE orders_id= ?", [$order_id, $order_id]);

            $payment_made = $check[0]['zen_orders']['payment_made'];
            $ship_by = $check[0]['zen_orders']['ship_by'];
            $hasLanyard = $check[0][0]['hasLanyard'] > 0;

            $ok = 6;
            if ($payment_made != '0') {
                $mod_date = $hasLanyard ? strtotime(date("Ymd") . "+ 3 weeks") : strtotime(date("Ymd") . "+ 7 weekdays");
                $ship_by = ($ship_by == "0") ? date("m/d/Y", $mod_date) : $ship_by;
                $ok = 8;
            }

            $this->Order->id = $order_id;
            $this->Order->save([
                'follow_up' => 0,
                'ship_by' => $ship_by,
                'artwork_approved' => $date,
                'orders_status' => $ok
            ]);

            $this->Order->OrdersStatusHistory->create();
            $this->Order->OrdersStatusHistory->save([
                'orders_id' => $order_id,
                'orders_status_id' => $ok,
                'date_added' => date('Y-m-d H:i:s'),
                'customer_notified' => 1,
                'comments' => 'Artwork approved by customer'
            ]);

            //Validate if he is already fully paid
            if ($payment_made == '0') {
                return $this->redirect(['controller' => 'Payments', $order_id]);
            } else {
                return $this->redirect("/orders/proof/" . $order_id);
            }
        }
    }

    /**
     * method chagenQty
     * url [post] /orders/changeQty
     */
    public function changeQty() {
        if (!$this->request->is("post")) {
            return $this->redirect($this->referer());
        }

        $data = $this->request->data;
        $this->Order->Proof->id = $data["Proof"]["id"];
        $this->Order->Proof->saveField("new_qty", $data["Proof"]["new_qty"]);


        $this->Order->Proof->query("INSERT INTO zen_orders_status_history(orders_id, orders_status_id, date_added, customer_notified, comments) 
VALUES (?,(SELECT orders_status FROM zen_orders WHERE orders_id = ?),NOW(),'1',
'Quantity update request for proof({$data["OrdersProduct"]["products_model"]}): from {$data['OrdersProduct']["quantity"]} to {$data["Proof"]["new_qty"]} pcs.')"
                , [
            $data["Proof"]["order_id"],
            $data["Proof"]["order_id"]
        ]);

        $this->Flash->success("Proof new quantitiy request sent", array("key" => "quantiyReqSent"));
        return $this->redirect($this->referer());
    }

    /**
     * method approve all proof
     * @param type $order_id
     */
    public function approveALL($order_id) {
        // update the proof data
        $this->Order->Proof->query("UPDATE proofs SET status='1' WHERE order_id=? AND status = 0", [$order_id]);

        $this->approveOrder($order_id);

        $this->Flash->success("All Artworks Approved", array("key" => "quantiyReqSent"));
        return $this->redirect("/orders/proof/" . $order_id);
    }

    /**
     * method rejectProof
     * url /orders/rejectProof
     */
    public function rejectProof($id) {
        if ($this->request->is("post")) {

            $data = $this->request->data;
            $this->Order->Proof->id = $id;
            $this->Order->Proof->saveField("reason", $data["Proof"]["reason"]);
            $this->Order->Proof->saveField("status", 3);

            // update order
            $this->Order->id = $data["Proof"]["order_id"];
            $this->Order->saveField("artwork_approved", 0);
            $this->Order->saveField("orders_status", 2);

            // create order status history
            $orderHistory = [];
            $orderHistory["OrdersStatusHistory"]["orders_id"] = $data["Proof"]["order_id"];
            $orderHistory["OrdersStatusHistory"]["orders_status_id"] = 2;
            $orderHistory["OrdersStatusHistory"]["date_added"] = date("Y-m-d");
            $orderHistory["OrdersStatusHistory"]["customer_notified"] = "Artwork rejected";
            $orderHistory["OrdersStatusHistory"]["comments"] = 'Artwork rejected: ' . $data["Proof"]["reason"];

            $this->Order->OrdersStatusHistory->save($orderHistory);
            $this->Flash->success("Artwork has been rejected", array("key" => "artworkRejected"));

            return $this->redirect("/orders/proof/" . $data["Proof"]["order_id"]);
        }
    }

    /**
     * method ajax_getOrderHistory
     * description get the order status history of the selected order
     *
     * @param orderId {{ int }} selected order id
     */
    public function ajax_getOrderHistory($orderId = null) {
        if (is_null($orderId)) {
            $response = [
                "status" => "unauthorized"
            ];
        } else {
            $ordersHistoryData = $this->Order->OrdersStatusHistory->find("all", [
                "conditions" => ["OrdersStatusHistory.orders_id" => $orderId],
                "contain" => [
                    "OrdersStatus"
                ],
                "order" => [
                    "OrdersStatusHistory.date_added" => "desc"
                ]
            ]);

            $response = [
                "status" => true,
                "data" => $ordersHistoryData
            ];
        }

        return $this->set([
                    "response" => $response,
                    "_serialize" => array("response")
        ]);
    }

    /**
     * method ajax_getCustomerPreviousOrders
     * description get the logged in customer previous order
     */
    public function ajax_getCustomerPreviousOrders() {

        $customerId = $this->Auth->user("customers_id");
        $prevOrders = $this->Order->getCustomerPrevOrderss($customerId);

        $response = [
            "status" => true,
            "data" => $prevOrders
        ];

        return $this->set([
                    "response" => $response,
                    "_serialize" => array("response")
        ]);
    }

}
