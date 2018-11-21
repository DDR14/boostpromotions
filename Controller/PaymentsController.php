<?php

App::uses("AppController", "Controller");
App::uses("ConvergeApi", "Lib");

class PaymentsController extends AppController {

    var $uses = array(
        "Order",
        "OrdersProduct",
        "Payment",
        "Transaction",
        "Converge",
        "CheckReceipt",
        "PoReceipt"
    );

    /**
     * method index
     * url /payments/index/:orderId
     */
    public function index($orderId = null) {
        if (is_null($orderId)):
            return $this->redirect(['controller' => 'orders', 'action' => 'index']);
        endif;

        //use in the future
        $transactions = $this->Transaction->find("all", array(
            "fields" => array(
                "SUM(amount) AS total"
            ),
            "conditions" => array(
                "txn_type" => "Credit",
                "customers_id" => $this->Auth->user("customers_id")
            )
        ));

        $order = $this->Order->find("first", array(
            "conditions" => array(
                "orders_id" => $orderId,
                "customers_id" => $this->Auth->user("customers_id")
            ),
            "fields" => array("Order.order_total")
        ));

        $amountPaid = $this->Order->Charge->getPaid($orderId);
        $orderTotal = $order["Order"]["order_total"];
        $balance = $orderTotal - $amountPaid;

        $this->set("orderId", $orderId);
        $this->set("balance", $balance);
    }

    /**
     * method creditCardPayment
     * url /payments/creditCartPayment/:orderId
     */
    public function creditCardPayment($orderId = null) {
        if (is_null($orderId)):
            return $this->redirect(['controller' => 'orders', 'action' => 'index']);
        endif;

        $orderData = $this->Order->find("first", [
            "conditions" => [
                "orders_id" => $orderId,
                "customers_id" => $this->Auth->user("customers_id")
            ],
            "contain" => [
                "OrdersTotal" => [
                    "order" => ["OrdersTotal.sort_order" => "asc"],
                    "fields" => ["OrdersTotal.title", "OrdersTotal.text"]
                ]
            ],
            "fields" => [
                "Order.artwork_approved",
                "Order.ship_by",
                "Order.customers_id",
                "Order.order_total", "Order.coupon_code",
                "NOT EXISTS(SELECT 1 FROM zen_orders_products a INNER JOIN zen_products b ON a.products_id = b.products_id WHERE b.require_artwork = 1 AND a.orders_id = {$orderId}) AS noProof"
            ]
        ]);

        $amountPaid = $this->Order->Charge->getPaid($orderId);
        $orderTotal = $orderData["Order"]["order_total"];
        $balance = $orderTotal - $amountPaid;

        $this->set("orderData", $orderData);
        $this->set("balance", $balance);
        $this->set("amountPaid", $amountPaid);
        
        $countries = $this->Utility->generateCountrySelectOptions(0);
        $this->set("countries2", $countries);

        if ($this->request->is("post")) {
            $requestData = $this->request->data;

            // convert year to 2 digit format
            $year = substr($requestData["Payment"]["cc_exp_date"]["year"], 2);
            $requestData["Payment"]["cc_exp_date"] = $requestData["Payment"]["cc_exp_date"]["month"] . $year;

            //validate fields first
            $this->Payment->set($requestData["Payment"]);
            if (!$this->Payment->validates()) {
                return $this->Flash->error('There are error(s) in validating your form. See below.', ['key' => 'paymentError']);
            }

            //Process the Payment here using convergeAPi
            $PaymentProcessor = new ConvergeApi(
                    '672235', 'boostweb', '384786', true
            );

            $ssl_company = $requestData["Payment"]["billing_company"];
            $ssl_first_name = $requestData["Payment"]["billing_firstname"];
            $ssl_last_name = $requestData["Payment"]["billing_lastname"];
            $ssl_avs_address = $requestData["Payment"]["billing_street_address"];
            $ssl_address2 = $requestData["Payment"]["billing_suburb"];
            $ssl_city = $requestData["Payment"]["billing_city"];
            $ssl_state = $requestData["Payment"]["billing_state"];
            $ssl_avs_zip = $requestData["Payment"]["billing_postcode"];
            $ssl_country = $requestData["Payment"]["billing_country"];
            $ssl_phone = "";
            $ssl_email = "";

            // submit payment
            $response = $PaymentProcessor->ccsale(
                    array(
                        'ssl_amount' => $requestData['Payment']['cc_amount'],
                        'ssl_card_number' => $requestData['Payment']['cc_number'],
                        'ssl_cvv2cvc2' => $requestData['Payment']['cc_cvc'],
                        'ssl_exp_date' => $requestData['Payment']['cc_exp_date'],
                        'ssl_description' => urlencode("Order ID: $orderId - BoostPromotions"),
                        'ssl_company' => $ssl_company,
                        'ssl_first_name' => $ssl_first_name,
                        'ssl_last_name' => $ssl_last_name,
                        'ssl_avs_address' => $ssl_avs_address,
                        'ssl_address2' => $ssl_address2,
                        'ssl_city' => $ssl_city,
                        'ssl_state' => $ssl_state,
                        'ssl_avs_zip' => $ssl_avs_zip,
                        'ssl_country' => $ssl_country,
                        'ssl_phone' => $ssl_phone,
                        'ssl_email' => $ssl_email
                    )
            );

            if (!isset($response['ssl_result']) || $response['ssl_result'] != '0') {
                if (isset($response['errorCode'])) {

                    $errorNameCode = $response["errorName"] . " " . $response["errorCode"] . ": ";
                    $errorMessage = $response["errorMessage"];
                    $this->Flash->error($errorNameCode . $errorMessage, array("key" => "paymentError"));
                } else {

                    $errorNameCode = $response["ssl_avs_response"] . ": ";
                    $errorMessage = $response["ssl_result_message"];
                    $this->Flash->error($errorNameCode . $errorMessage, array("key" => "paymentError"));
                }
            } else {
                // Display converge api response
                $txn_id = $this->readyShipPayment($orderData, [
                    'payment' => $response['ssl_amount'],
                    'method' => 'CreditCard-Online',
                    'memo' => "Order ID: $orderId - BoostPromotions",
                    'reference_no' => $response['ssl_txn_id'],
                    'noProof' => $orderData['0']['noProof'],
                    'amountPaid' => $amountPaid
                ]);

                $convergeData = array(
                    "Converge" => array(
                        "ssl_card_number" => $response["ssl_card_number"],
                        "ssl_exp_date" => $response["ssl_exp_date"],
                        "ssl_amount" => $response["ssl_amount"],
                        "ssl_departure_date" => $response["ssl_departure_date"],
                        "ssl_completion_date" => $response["ssl_completion_date"],
                        "ssl_result" => $response["ssl_result"],
                        "ssl_result_message" => $response["ssl_result_message"],
                        "ssl_txn_id" => $response["ssl_txn_id"],
                        "ssl_approval_code" => $response["ssl_approval_code"],
                        "ssl_cvv2_response" => $response["ssl_cvv2_response"],
                        "ssl_avs_response" => $response["ssl_avs_response"],
                        "ssl_account_balance" => $response["ssl_account_balance"],
                        "ssl_txn_time" => $response["ssl_txn_time"],
                        "orders_id" => $orderId,
                        "txn_id" => $txn_id
                    )
                );

                $this->Converge->save($convergeData["Converge"]);
                return $this->redirect(array(
                            "controller" => "orders",
                            "action" => "view",
                            $orderId
                ));
            }
        }        
    }

    /**
     * method checkPayment
     * url /payments/checkPayment/:orderId
     */
    public function checkPayment($orderId = null) {
        if (is_null($orderId))
            return $this->redirect($this->referer());

        $this->set("orderId", $orderId);

        $checkData = $this->CheckReceipt->find("first", array(
            "conditions" => array(
                "CheckReceipt.orders_id" => $orderId
            )
        ));

        $POData = $this->PoReceipt->find("count", array(
            "conditions" => array(
                "PoReceipt.orders_id" => $orderId
            )
        ));

        if (!empty($checkData) || $POData != 0) {
            $message = ($POData != 0) ? "You have already submitted a Purchase Order file." : "Check is already Uploaded.";
            $this->Flash->warning($message, array("key" => "check"));
        }

        $this->set("check", $checkData);
        $this->set("PO", $POData);

        if ($this->request->is("post")) {
            $requestData = $this->request->data;

            // validate first the data
            $this->CheckReceipt->set($requestData["CheckReceipt"]);
            if ($this->CheckReceipt->validates()) {
                if ($requestData["CheckReceipt"]["checkreceipt"] == 1) {
                    $response = $this->Utility->upload($requestData["CheckReceipt"]["file"], "/2dodash/po_receipts", true);
                    if ($response["type"] != "error") {
                        // add new data to check_receipt database
                        $checkReceiptData = array(
                            "CheckReceipt" => array(
                                "location" => "po_receipts/" . $response["url"],
                                "remarks" => $requestData["CheckReceipt"]["file"]["name"],
                                "orders_id" => $orderId
                            )
                        );

                        $this->CheckReceipt->save($checkReceiptData["CheckReceipt"]);

                        // update zen_orders
                        $this->Order->id = $orderId;
                        $this->Order->saveField("orders_status", 18);

                        // add new status history
                        $ordersStatusHistoryData = array(
                            "OrdersStatusHistory" => array(
                                "orders_id" => $orderId,
                                "orders_status_id" => 18,
                                "date_added" => date("Y-m-d"),
                                "customer_notified" => 1,
                                "comments" => "Check uploaded" . $requestData["CheckReceipt"]["file"]["name"] . " by customer"
                            )
                        );
                        $this->Order->OrdersStatusHistory->save($ordersStatusHistoryData["OrdersStatusHistory"]);

                        // redirect the customer and show the notification message
                        $this->Flash->success("Check uploaded successfully", array("key" => "check"));
                        $this->redirect(array(
                            "controller" => "payments",
                            "action" => "checkPayment",
                            $orderId
                        ));
                    } else {
                        $this->Flash->error($response["message"], array("key" => "check"));
                    }
                } else if ($requestData["CheckReceipt"]["checkreceipt"] == 'x') {
                    $requestData = $this->request->data;
                    $response = $this->Utility->removeFile($requestData["CheckReceipt"]["location"], "img/");

                    // delete check reciepts
                    $this->CheckReceipt->delete($requestData["CheckReceipt"]["id"]);

                    // update orders status
                    $statusHistoryData = $this->Order->OrdersStatusHistory->find("first", array(
                        "conditions" => array("orders_id" => $orderId),
                        "fields" => array("orders_status_id")
                    ));

                    // update orders data
                    $this->Order->id = $orderId;
                    $this->Order->saveField("orders_status", $statusHistoryData["OrdersStatusHistory"]["orders_status_id"]);

                    // add new orders history status data
                    $ordersStatusHistoryData = array(
                        "OrdersStatusHistory" => array(
                            "orders_id" => $orderId,
                            "orders_status_id" => $statusHistoryData["OrdersStatusHistory"]["orders_status_id"],
                            "date_added" => date("Y-m-d"),
                            "customer_notified" => 1,
                            "comments" => "Check deleted id: " . $requestData["CheckReceipt"]["id"] . " by customer"
                        )
                    );

                    $this->Order->OrdersStatusHistory->save($ordersStatusHistoryData["OrdersStatusHistory"]);
                    $this->Flash->success("Check Deleted.", array("key" => "check"));
                    $this->redirect(array(
                        "controller" => "payments",
                        "action" => "checkPayment",
                        $orderId
                    ));
                }
            } else {
                $errors = '';
                foreach ($this->CheckReceipt->validationErrors as $err) {
                    $errors .= $err[0];
                }
                $this->Flash->error($errors, array("key" => "check"));
            }
        }
    }

    /**
     * method uploadPO
     * url /payments/uploadPO/:orderId
     */
    public function uploadPO($orderId = null) {
        if (is_null($orderId))
            return $this->redirect($this->referer());

        $this->set("orderId", $orderId);

        $POData = $this->PoReceipt->find("first", array(
            "conditions" => array(
                "PoReceipt.orders_id" => $orderId
            )
        ));

        $checkData = $this->CheckReceipt->find("count", array(
            "conditions" => array(
                "CheckReceipt.orders_id" => $orderId
            )
        ));

        if (!empty($POData) || $checkData != 0) {
            $message = ($checkData != 0) ? "You have already submitted a check file." : "PO  is already Uploaded.";
            $this->Flash->warning($message, array("key" => "po"));
        }

        $this->set("check", $checkData);
        $this->set("PO", $POData);

        if ($this->request->is("post")) {
            $requestData = $this->request->data;

            // validate first the data
            $this->PoReceipt->set($requestData["PO"]);
            if ($this->PoReceipt->validates()) {
                if ($requestData["PO"]["poReceipt"] == 1) {
                    $response = $this->Utility->upload($requestData["PO"]["file"], "/2dodash/po_receipts", true);
                    if ($response["type"] != "error") {
                        // add new data to po_receipt database
                        $poData = array(
                            "PO" => array(
                                "location" => "po_receipts/" . $response["url"],
                                "remarks" => $requestData["PO"]["file"]["name"],
                                "orders_id" => $orderId,
                                "po_number" => $requestData["PO"]["po_number"]
                            )
                        );

                        $this->PoReceipt->save($poData["PO"]);

                        // update zen_orders
                        $this->Order->id = $orderId;
                        $this->Order->saveField("orders_status", 18);

                        // add new status history
                        $ordersStatusHistoryData = array(
                            "OrdersStatusHistory" => array(
                                "orders_id" => $orderId,
                                "orders_status_id" => 18,
                                "date_added" => date("Y-m-d"),
                                "customer_notified" => 1,
                                "comments" => "PO uploaded " . $requestData["PO"]["file"]["name"] . "by customer"
                            )
                        );
                        $this->Order->OrdersStatusHistory->save($ordersStatusHistoryData["OrdersStatusHistory"]);

                        // redirect the customer and show the notification message
                        $this->Flash->success("PO uploaded successfully", array("key" => "po"));
                        $this->redirect(array(
                            "controller" => "payments",
                            "action" => "uploadPO",
                            $orderId
                        ));
                    } else {
                        $this->Flash->error($response["message"], array("key" => "po"));
                    }
                } else if ($requestData["PO"]["poReceipt"] == 'x') {
                    $requestData = $this->request->data;
                    $response = $this->Utility->removeFile($requestData["PO"]["location"], "img/");

                    // delete check reciepts
                    $this->PoReceipt->delete($requestData["PO"]["id"]);

                    // update orders status
                    $statusHistoryData = $this->Order->OrdersStatusHistory->find("first", array(
                        "conditions" => array("orders_id" => $orderId),
                        "fields" => array("orders_status_id")
                    ));

                    // update orders data
                    $this->Order->id = $orderId;
                    $this->Order->saveField("orders_status", $statusHistoryData["OrdersStatusHistory"]["orders_status_id"]);

                    // add new orders history status data
                    $ordersStatusHistoryData = array(
                        "OrdersStatusHistory" => array(
                            "orders_id" => $orderId,
                            "orders_status_id" => $statusHistoryData["OrdersStatusHistory"]["orders_status_id"],
                            "date_added" => date("Y-m-d"),
                            "customer_notified" => 1,
                            "comments" => "PO deleted id: " . $requestData["PO"]["id"] . " by customer"
                        )
                    );

                    $this->Order->OrdersStatusHistory->save($ordersStatusHistoryData["OrdersStatusHistory"]);
                    $this->Flash->success("PO Deleted.", array("key" => "po"));
                    $this->redirect(array(
                        "controller" => "payments",
                        "action" => "uploadPO",
                        $orderId
                    ));
                }
            } else {
                $errors = '';
                foreach ($this->PoReceipt->validationErrors as $err) {
                    $errors .= $err[0];
                }
                $this->Flash->error($errors, array("key" => "po"));
            }
        }
    }

    /**
     * method readyShipPayment
     * description update the orders table if the customer successfully paid the order
     */
    public function readyShipPayment($orderData, array $params = array()) {
        // Set defaults for all passed options
        $options = array_merge([
            'date' => date('Ymd'),
            'credit_card' => false,
            'require_artwork' => false,
            'payment' => 0,
            'method' => 'Credit',
            'memo' => '',
            'txn_id' => 0,
            'reference_no' => '',
            'attachment' => ''
                ], $params);

        $payment = round($options['payment'], 2);
        $payment_method = $options['method'];
        $order_id = $orderData['Order']['orders_id'];

        $lanYards = $this->OrdersProduct->find("count", array(
            "conditions" => array(
                "OrdersProduct.products_id" => array("990", "853"),
                "AND" => array(
                    "OrdersProduct.orders_id" => $order_id
                )
            )
        ));

        $orig_total = $orderData["Order"]["order_total"];
        $artwork_approved = $orderData["Order"]["artwork_approved"];
        $ship_by = $orderData["Order"]["ship_by"];
        $hasLanyard = $lanYards > 0;

        $balance = round($orig_total - $options['amountPaid'], 2);
        $date = $options['date'];

        $sql = [];
        $ok = 8;
        if ($artwork_approved == 0) {
            $ok = 2;
        } elseif ($artwork_approved == 1) {
            $ok = 3;
        } else {
            //update ship by.
            $mod_date = $hasLanyard ? strtotime(date("Ymd") . "+ 15 weekdays") : strtotime($date . "+ 7 weekdays");
            $ship_by = ($ship_by == "0") ? date("m/d/Y", $mod_date) : $ship_by;
            if ($options['noProof']) {
                $sql = [
                    "cut" => $date,
                    'laminated' => $date,
                    'printed' => $date
                ];
            }
        }

        //IF EXACT OR SURPLUS
        if ($payment >= $balance) {
            // run normally
            $this->Order->id = $orderData["Order"]["orders_id"];
            $this->Order->save([
                "ship_by" => $ship_by,
                "payment_made" => $date,
                "orders_status" => $ok
                    ] + $sql);

            //ZenCart Status Update            
            $historyData = array(
                "OrdersStatusHistory" => array(
                    "orders_id" => $order_id,
                    "orders_status_id" => $ok,
                    "date_added" => $date,
                    "customer_notified" => 1,
                    "comments" => $payment . " " . $payment_method . " Payment made by customer"
                )
            );

            $this->Order->OrdersStatusHistory->save($historyData["OrdersStatusHistory"]);
        }

        if ($options['txn_id'] == 0) {
            $transactionData = array(
                "Transaction" => array(
                    "payment_method" => $options["method"],
                    "txn_type" => "Payment",
                    "amount" => $options["payment"],
                    "ref_no" => $options["reference_no"],
                    "memo" => $options["memo"],
                    "attachment" => $options["attachment"],
                    "txn_date" => $options["date"],
                    "customers_id" => $this->Auth->user("customers_id")
                )
            );

            $savedTransaction = $this->Transaction->save($transactionData["Transaction"]);
            $options["txn_id"] = $savedTransaction["Transaction"]["txn_id"];
        }

        $chargeData = array(
            "Charge" => array(
                "orders_id" => $order_id,
                "amount" => $payment,
                "method" => $payment_method,
                "insert_date" => $date,
                "txn_id" => $options["txn_id"]
            )
        );
        $this->Order->Charge->save($chargeData["Charge"]);

        return $options['txn_id'];
    }

}
