<?php

App::uses("AppController", "Controller");
App::import("Controller", "Orders");

class ShoppingCartController extends AppController {

    var $uses = array(
        "CustomersBasket",
        "Product",
        "AddressBook",
        "Order",
        "Coupon",
        "OrdersProduct",
        "OrdersStatusHistory",
        "OrdersTotal",
        "CustomCo",
        "Customer",
        "CouponRedeemTrack"
    );

    /**
     * method beforeFilter
     * description this controller beforeFilter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("login_as_customer", "index", "delete");
    }

    /**
     * method index
     * url shoppingCart/index
     */
    public function index() {
        // get from header data 
        $basketData = $this->viewVars['basketData'];

        // Check if there is teachers kits if positive show the add add ons button
        // also check for the qty of the ordered tags if the accumulated qty reach
        // 500pcs if it doesn't display a message that the lanyards will be 1$ more expensive.
        $teachersKits = 0;
        $addOns = [];
        $lanyards = 0;
        $accumulatedQty = 0;
        foreach ($basketData as $order) {

            // Check for teachers kits
            if ($order["Product"]["master_categories_id"] == 101) {
                $teachersKits += 1;
            }

            // Check for teachers kits add ons
            if ($order["Product"]["master_categories_id"] == 24) {
                $addOns[] = $order["CustomersBasket"]["customers_basket_id"];
            }

            // Check if the order cart has lanyards included
            if (in_array($order["Product"]["products_id"], [990, 853])) {
                $lanyards += 1;
            }

            // Check for the ordered tag qty
            $accumulatedQty += $this->Utility->getTagQty($order);
        }

        // show the teachers kits add add-on button if it is present
        // in the basket data.
        if ($teachersKits != 0) {
            $this->set("teachersKitsFound", true);

            $tkExtra = $this->Product->getTeachersKitsAddOns();
            $this->set("tkExtra", $tkExtra);
            $this->Flash->info("Add EXTRAS with your teacher kit. See Below.", array("key" => "newItemAdded"));
        }

        // check if there is any teachers kits add on products
        // if any remove it from the list.
        if ($addOns && $teachersKits == 0) {
            $this->CustomersBasket->deleteAll(['customers_basket_id' => $addOns]);
            // reload page to call init cart
            $this->redirect([
                "controller" => "shoppingCart",
                "action" => "index"
            ]);
        }
        $this->set("accumulatedQty", $accumulatedQty);
        $this->set("basketItem", $basketData);
        $this->set("lanyards", $lanyards);
    }

    /**
     * method update
     * url shoppinCart/update/:cartId
     *
     * @var $itemId {{ int }} shopping cart id
     */
    public function update($itemId) {
        if (!$this->request->is("post")) {
            return $this->redirect($this->referer());
        }
        // update cart item here
        $requestData = $this->request->data;

        $this->CustomersBasket->id = $itemId;
        $this->CustomersBasket->saveField("customers_basket_quantity", $requestData["CustomersBasket"]["customers_basket_quantity"]);

        $this->Flash->success("Quantity updated", array("key" => "update"));
        $this->redirect(array(
            "controller" => "shoppingCart",
            "action" => "index"
        ));
    }

    /**
     * method delete
     * url shoppingCart/delete/:itemId
     *
     * @var itemId {{ int }} shopping cart item id
     */
    public function delete($itemId, $cookie = false) {
        if (!$this->request->is("post"))
            return $this->redirect($this->referer());

        if ($cookie) {
            $tmpCart = $this->Cookie->read('tmpCart');
            unset($tmpCart[$itemId]);
            $this->Cookie->write('tmpCart', $tmpCart);
        } else {
            $this->CustomersBasket->delete($itemId);
        }

        $this->Flash->success("Item Deleted", array("key" => "itemDeleted"));

        return $this->redirect(array(
                    "controller" => "shoppingCart",
                    "action" => "index"
        ));
    }

    /**
     * method checkOut1
     * url shoppingCart/checkOut
     */
    public function checkout() {
        // check if the billing address id is set in the session if not load the default addresses data
        $shippingAddressId = (!is_null($this->Session->read("shippingAddress.id"))) ? $this->Session->read("shippingAddress.id") : $this->Auth->user("customers_default_address_id");
        $billingAddressId = (!is_null($this->Session->read("billingAddress.id"))) ? $this->Session->read("billingAddress.id") : $this->Auth->user("customers_default_address_id");

        // get the customers default address
        $defaultAddress = $this->AddressBook->find("first", array(
            "conditions" => array("address_book_id" => $this->Auth->user("customers_default_address_id")),
            "contain" => array("Zone", "Country")
        ));

        if (!$defaultAddress) {
            $this->Flash->error('Address not found. Please specify your address so we can proceed to checkout.', ['key' => 'address']);
            return $this->redirect('changeAddress/shipping');
        }

        // get the shipping address
        $shippingAddress = $this->AddressBook->find("first", array(
            "conditions" => array("address_book_id" => $shippingAddressId),
            "contain" => array("Zone", "Country")
        ));

        // get the billing address
        $billingAddress = $this->AddressBook->find("first", array(
            "conditions" => array("address_book_id" => $billingAddressId),
            "contain" => array("Zone", "Country")
        ));

        // get the flat box rate data
        $basketData = $this->viewVars['basketData'];

        if (empty($basketData)) {
            return $this->redirect($this->referer());
        }

        $flatBoxPrice = $this->CustomersBasket->flatBoxPrice($basketData, $shippingAddress["Country"]["countries_iso_code_2"]);
        $upsCharge = $this->CustomersBasket->upsCharge($basketData, $shippingAddress["Country"]["countries_iso_code_2"], $defaultAddress["AddressBook"]["entry_postcode"]);

        if (isset($upsCharge["charge"])) {
            $this->set("upsCharge", $upsCharge["charge"]);
        }

        if (!isset($flatBoxPrice['error'])) {
            $this->set("flatBoxPrice", $flatBoxPrice);
        }

        $customers_mobile = $this->Auth->user("customers_mobile");
        $this->set(compact('shippingRate', 'billingAddress', 'defaultAddress', 'shippingAddress', 'customers_mobile'));

        if ($this->request->is("post")) {
            $requestData = $this->request->data;

            // check first if the terms and conditions check box are checked
            if (!isset($requestData["Order"]["terms"])) {
                $this->Flash->warning("Please confirm the terms and conditions bound to this order by ticking the box below.", array("key" => "checkout")
                );
            } else if ($requestData["Order"]["shipping_notice"] != 1) {
                $this->Flash->warning("Please confirm that you have read the shipping notice by ticking the box below.", ["key" => "checkout"]
                );
            } else {
                // validate the data the coupon code
                if (!empty($requestData["Order"]["coupon_code"])) {
                    $couponData = ["coupon_code" => $requestData["Order"]["coupon_code"],
                        "customers_id" => $this->Auth->user('customers_id')];
                    $this->Coupon->set($couponData);

                    if (!$this->Coupon->validates()) {
                        // set errrors variable here
                        $errors = $this->Coupon->validationErrors;
                    } else {

                        ///////////////////////////////////////////////////////////////////
                        // Ban Customers here if they are using a duplicate account
                        // as a referral
                        ///////////////////////////////////////////////////////////////////
                        // get the address of the customer
                        $matchAddress = $this->AddressBook->find("all", [
                            "conditions" => [
                                "AddressBook.customers_id !=" => $this->Auth->user('customers_id'),
                                "AddressBook.entry_street_address" => $shippingAddress['AddressBook']['entry_street_address'],
                                "AddressBook.entry_postcode" => $shippingAddress['AddressBook']['entry_postcode'],
                                "AddressBook.entry_city" => $shippingAddress['AddressBook']['entry_city']
                            ]
                        ]);

                        if (!empty($matchAddress)) {
                            // Ban Customer here
//                            $this->Customer->id = $this->Auth->user();
//                            $this->Customer->saveField("customers_authorization", 4);
                            // Notify the user that his/her account is banned
                            $errors = [
                                "coupon_code" => ['Duplicate account suspected. Coupon code cannot be used. If this is an error, please contact us.']
                            ];
                        } else {
                            $this->Flash->success('Congratulations you have redeemed the Discount Coupon');
                        }

                        ///////////////////////////////////////////////////////////////////
                        ///////////////////              END              /////////////////
                        ///////////////////////////////////////////////////////////////////
                    }
                }

                // check if the errors coupon variable error is set if not save the orders data into
                // the session.
                if (!isset($errors)) {
                    $requestData["Order"]["customers_mobile"] = $requestData["Order"]["customers_mobile_no"] . $requestData["Order"]["customers_mobile_carrier"];

                    // $this->Customer->id = $this->Auth->User('customers_id');
                    // $this->Customer->saveField('customers_mobile', $requestData["Order"]["customers_mobile"]);

                    $requestData["Order"]["customers_company"] = $defaultAddress["AddressBook"]["entry_company"];
                    $requestData["Order"]["customers_street_address"] = $defaultAddress["AddressBook"]["entry_street_address"];
                    $requestData["Order"]["customers_suburb"] = $defaultAddress["AddressBook"]["entry_suburb"];
                    $requestData["Order"]["customers_city"] = $defaultAddress["AddressBook"]["entry_city"];
                    $requestData["Order"]["customers_postcode"] = $defaultAddress["AddressBook"]["entry_postcode"];

                    $requestData["Order"]["customers_state"] = (empty($defaultAddress["Zone"]["zone_name"])) ? $defaultAddress["AddressBook"]["entry_state"] : $defaultAddress["Zone"]["zone_name"];

                    $requestData["Order"]["customers_country"] = $defaultAddress["Country"]["countries_name"];

                    // set delivery address here
                    $requestData["Order"]["delivery_name"] = $shippingAddress["AddressBook"]["entry_firstname"] . " " . $shippingAddress["AddressBook"]["entry_lastname"];
                    $requestData["Order"]["delivery_company"] = $shippingAddress["AddressBook"]["entry_company"];
                    $requestData["Order"]["delivery_street_address"] = $shippingAddress["AddressBook"]["entry_street_address"];
                    $requestData["Order"]["delivery_suburb"] = $shippingAddress["AddressBook"]["entry_suburb"];
                    $requestData["Order"]["delivery_city"] = $shippingAddress["AddressBook"]["entry_city"];
                    $requestData["Order"]["delivery_postcode"] = $shippingAddress["AddressBook"]["entry_postcode"];

                    $requestData["Order"]["delivery_state"] = (empty($shippingAddress["Zone"]["zone_name"])) ? $shippingAddress["AddressBook"]["entry_state"] : $shippingAddress["Zone"]["zone_name"];

                    $requestData["Order"]["delivery_country"] = $shippingAddress["Country"]["countries_name"];

                    // set billing address here
                    $requestData["Order"]["billing_name"] = $billingAddress["AddressBook"]["entry_firstname"] . " " . $billingAddress["AddressBook"]["entry_lastname"];
                    $requestData["Order"]["billing_company"] = $billingAddress["AddressBook"]["entry_company"];
                    $requestData["Order"]["billing_street_address"] = $billingAddress["AddressBook"]["entry_street_address"];
                    $requestData["Order"]["billing_suburb"] = $billingAddress["AddressBook"]["entry_suburb"];
                    $requestData["Order"]["billing_city"] = $billingAddress["AddressBook"]["entry_city"];
                    $requestData["Order"]["billing_postcode"] = $billingAddress["AddressBook"]["entry_postcode"];

                    $requestData["Order"]["billing_state"] = (empty($billingAddress["Zone"]["zone_name"])) ? $billingAddress["AddressBook"]["entry_state"] : $billingAddress["Zone"]["zone_name"];

                    $requestData["Order"]["billing_country"] = $billingAddress["Country"]["countries_name"];

                    // set payment method data
                    if ($requestData["Order"]["payment_method"] == 1) {
                        $requestData["Order"]["payment_method"] = "Purchase Order";
                        $requestData["Order"]["payment_module_code"] = "purchaseorder";
                    } else {
                        $requestData["Order"]["payment_method"] = "Pay with credit card after approved artwork.";
                        $requestData["Order"]["payment_module_code"] = "invoice";
                    }

                    $this->Session->write("Customers.order", $requestData);

                    // set the shipping rate here
                    if ($requestData["Order"]["shipping_method"] == "USPS-Priority Mail") {
                        $shippingRate = $flatBoxPrice;
                    } else if ($requestData["Order"]["shipping_method"] == "UPS (Ground)") {
                        $shippingRate = $upsCharge['charge'];
                    } else {
                        $shippingRate = 0;
                    }

                    $this->Session->write("shippingCost", $shippingRate);
                    // redirect the user to the orders confirmation page
                    return $this->redirect(array(
                                "controller" => "shoppingCart",
                                "action" => "confirmOrder"
                    ));
                }

                // notify the user about the coupon code error
                $this->Flash->error($errors["coupon_code"][0], array("key" => "checkout"));
            }
        }

        $this->set("mobileCarriers", $this->Utility->getMobileCarriers());
    }

    /**
     * method confirmOrder
     * url /shoppingCart/confirmOrder
     */
    public function confirmOrder() {
        // check if the customers orders session data is set
        $sessionData = $this->Session->read("Customers.order");
        $accumulatedQty = $this->Session->read("accumulatedQty");

        $this->set("accumulatedQty", $accumulatedQty);

        // if the coupon code is empty in the sessionData
        // unset it to avoid errors
        if (empty($sessionData["Order"]["coupon_code"])) {
            unset($sessionData["Order"]["coupon_code"]);
        }

        // check if the data customers order session data is set
        // if not redirect the back the user
        if (is_null($sessionData)) {
            return $this->redirect($this->referer());
        }
        // get the customers shopping cart data
        $basketData = $this->viewVars['basketData'];

        // get the total of all the orders
        $subTotal = 0;
        $hasProof = false;
        foreach ($basketData as $key => $order) {
            if ($order['Product']['require_artwork']) {
                $hasProof = true;
            }
            if (in_array($order["Product"]['products_id'], [990, 853]) && $accumulatedQty < 500) {
                $basketData[$key][0]["products_price"] += 1;
            }
            $subTotal += ($order["CustomersBasket"]["customers_basket_quantity"] * $basketData[$key][0]["products_price"]);
        }

        $salesTax = 0;
        if (in_array($sessionData["Order"]["delivery_country"], [
                    'United States', 'US']) && in_array(strtolower($sessionData["Order"]['delivery_state']), [
                    'ut', 'utah']) && !$this->Auth->user('tax_exempt')) {
            $salesTax = $subTotal * (6.85 / 100);
        }

        $shippingRate = $this->Session->read("shippingCost");
        $lowOrderFee = ($subTotal < 25.00) ? 10.00 : 0;
        $order_status = 2;
        if (!$hasProof) {
            $order_status = 6;
            $sessionData["Order"]["artwork_approved"] = date("Y-m-d");
        }

        //could be a separate function
        $couponAmount = 0;
        if (isset($sessionData["Order"]["coupon_code"])) {
            $discount = $this->Coupon->find('first', [
                'conditions' => ['coupon_code' => $sessionData["Order"]["coupon_code"]],
                'fields' => ['coupon_amount', 'coupon_type', 'coupon_id']]);
            if (!empty($discount)) {
                switch ($discount['Coupon']['coupon_type']) {
                    case 'P':
                        $couponAmount = $discount['Coupon']['coupon_amount'] / 100 * $subTotal;
                        break;
                    case 'F':
                        $couponAmount = $discount['Coupon']['coupon_amount'] * ($subTotal > 0);
                        break;
                }
            }
        }

        //overall total
        $orderTotal = $subTotal + $salesTax + $shippingRate + $lowOrderFee - $couponAmount;

        if (isset($sessionData["Order"]["coupon_code"])) {
            $orderSummary = array(
                ['Sub-Total', $subTotal],
                [$sessionData["Order"]['shipping_method'], $shippingRate],
                ['Discount Coupon: ' . $sessionData["Order"]["coupon_code"], $couponAmount * -1],
                ['Sales Tax', $salesTax],
                ['Low Order Fee', $lowOrderFee],
                ['Total', $orderTotal]
            );
        } else {
            $orderSummary = array(
                ['Sub-Total', $subTotal],
                [$sessionData["Order"]['shipping_method'], $shippingRate],
                ['Sales Tax', $salesTax],
                ['Low Order Fee', $lowOrderFee],
                ['Total', $orderTotal]
            );
        }

        $this->set(compact("orderSummary", "basketData", "sessionData"));

        if ($this->request->is("post")) {
            // Save the orders data
            $sessionData["Order"]["customers_id"] = $this->Auth->user('customers_id');
            $sessionData["Order"]["customers_telephone"] = $this->Auth->user('customers_telephone');
            $sessionData["Order"]["customers_address_format_id"] = 2;
            $sessionData["Order"]["customers_name"] = $this->Auth->user('customers_firstname') . " " . $this->Auth->user('customers_lastname');

            $sessionData["Order"]["customers_email_address"] = $this->Auth->user('customers_email_address');
            $sessionData["Order"]["shipping_module_code"] = "table";
            $sessionData["Order"]["currency"] = "USD";
            $sessionData["Order"]["currency_value"] = "1.000000";
            $sessionData["Order"]["order_total"] = $orderTotal;
            $sessionData["Order"]["order_tax"] = $salesTax;
            $sessionData["Order"]["orders_status"] = $order_status; //what if the order doesnt need any artwork?
            $sessionData["Order"]["checked"] = 1;
            $sessionData["Order"]["delivery_address_format_id"] = 2;
            $sessionData["Order"]["billing_address_format_id"] = 2;
            $sessionData["Order"]["ip_address"] = $_SERVER['REMOTE_ADDR'];

            $sessionData["Order"]["date_purchased"] = date("Y-m-d");

            $this->Order->save($sessionData);
            $orders_id = $this->Order->getLastInsertId();

            // save the basket data to zen_orders_products table one by one

            foreach ($basketData as $product) {
                // set data here
                $orders_product_data = array(
                    "orders_id" => $orders_id,
                    "products_id" => $product["Product"]["products_id"],
                    "products_model" => $product["Product"]["products_model"],
                    "products_name" => $product["ProductsDescription"]["products_name"],
                    "products_price" => $product[0]["products_price"],
                    "products_quantity" => $product["CustomersBasket"]["customers_basket_quantity"],
                    "products_discount_type" => $product["Product"]["products_id"],
                    "final_price" => $product[0]["products_price"]
                );

                $this->OrdersProduct->create();
                $this->OrdersProduct->save($orders_product_data);

                $orders_products_id = $this->OrdersProduct->getLastInsertId();

                // Save also the the naz_custom_co data one by one.
                $custom_co_data = array('order_id' => $orders_id,
                    'orders_products_id' => $orders_products_id,
                    'date' => date("Ymd"),
                    'model' => $product["Product"]["products_model"],
                    'title' => $product['CustomersBasket']['title'],
                    'footer' => $product['CustomersBasket']['footer'],
                    'website' => $product['CustomersBasket']['website'],
                    'upload' => $product['CustomersBasket']['upload'],
                    'background' => $product['CustomersBasket']['background'],
                    'customs' => $product['CustomersBasket']['customs'],
                    'user_id' => $this->Auth->user("customers_id")
                );
                $this->CustomCo->create();
                $this->CustomCo->save($custom_co_data);
            }

            // save orders status history data
            $orders_status_history_data = array(
                "orders_id" => $orders_id,
                "orders_status_id" => $order_status,
                "date_added" => date("Y-m-d"),
                "customer_notified" => 1,
                "comments" => $sessionData["OrderStatusHistory"]["comments"]
            );
            $this->OrdersStatusHistory->save($orders_status_history_data);

            // Set orders total data
            $ordersTotalData = array(
                array(
                    "orders_id" => $orders_id,
                    "title" => 'Sub-Total:',
                    "text" => '$' . number_format($subTotal, 2),
                    "value" => $subTotal,
                    "class" => "ot_subtotal",
                    "sort_order" => 100
                ),
                array(
                    "orders_id" => $orders_id,
                    "title" => $sessionData["Order"]["shipping_method"],
                    "text" => '$' . number_format($shippingRate, 2),
                    "value" => $shippingRate,
                    "class" => "ot_shipping",
                    "sort_order" => 200
                ),
                array(
                    "orders_id" => $orders_id,
                    "title" => "Total",
                    "text" => '$' . number_format($orderTotal, 2),
                    "value" => $orderTotal,
                    "class" => "ot_total",
                    "sort_order" => 999
                )
            );

            // add low order fee if the customers order is less than $25.00
            if ($lowOrderFee > 0) {
                $otLowOrderFee = array(
                    "orders_id" => $orders_id,
                    "title" => "Low Order Fee",
                    "text" => "$10.00",
                    "value" => 10.00,
                    "class" => "ot_loworderfee",
                    "sort_order" => 400
                );
                array_push($ordersTotalData, $otLowOrderFee);
            }

            // add tax if taxable
            if ($salesTax > 0) {
                $otTax = array(
                    "orders_id" => $orders_id,
                    "title" => "Tax",
                    "text" => '$' . number_format($salesTax, 2),
                    "value" => $salesTax,
                    "class" => "ot_tax",
                    "sort_order" => 300
                );
                array_push($ordersTotalData, $otTax);
            }

            if ($couponAmount > 0) {
                $otCoupon = array(
                    "orders_id" => $orders_id,
                    "title" => "Discount Coupon: " . $sessionData['Order']['coupon_code'],
                    "text" => '-$' . number_format($couponAmount, 2),
                    "value" => $couponAmount,
                    "class" => "ot_coupon",
                    "sort_order" => 280
                );
                array_push($ordersTotalData, $otCoupon);

                //redeem track insert
                $redeem_track_data = [
                    'coupon_id' => $discount['Coupon']['coupon_id'],
                    'redeem_date' => date('Y-m-d H:i:s'),
                    'redeem_ip' => $_SERVER['REMOTE_ADDR'],
                    'customer_id' => $this->Auth->user('customers_id'),
                    'order_id' => $orders_id
                ];

                $this->CouponRedeemTrack->create();
                $this->CouponRedeemTrack->save($redeem_track_data);

                //update customer
                $this->Customer->id = $this->Auth->User('customers_id');
                $this->Customer->saveField('customers_referral', $sessionData['Order']['coupon_code']);
            }

            // save the data here
            $this->OrdersTotal->create();
            $this->OrdersTotal->saveMany($ordersTotalData);


            // give credit to customer
            // TODO::give only credit when the referral code is not empty
            $this->Coupon->createReferralCodeCoupon($orders_id);

            // Send confirmation order
            $view_vars = array(
                "name" => $this->Auth->user("customers_firstname") . " " . $this->Auth->user("customers_lastname"),
                "orderId" => $orders_id,
                "dateOrdered" => $sessionData["Order"]["date_purchased"],
                "basketData" => $basketData,
                "orderInfo" => $sessionData,
                "orderSummary" => $orderSummary,
                "shippingMethod" => $sessionData["Order"]["shipping_method"],
                "shippingRate" => $shippingRate
            );

            $layout = "orderConfirmation";
            $tpl = "default";
            $subject = "Order Confirmation No " . $orders_id;
            $toAdd = $this->Auth->user('customers_email_address');

            $Email = new CakeEmail('default');
            $Email->viewVars($view_vars);

            $Email->template($layout, $tpl)
                    ->subject($subject)
                    ->emailFormat('html')
                    ->to($toAdd)
                    ->bcc([
                        'info@boostpromotions.com',
                        'graphics@boostpromotions.com',
                        'michelle@boostpromotions.com',
                        'kendra@boostpromotions.com'
                    ])
                    ->from('customerservice@boostpromotions.com')
                    ->send();
            // Send confirmation text
            $userData = array(
                "mobile" => $sessionData["Order"]["customers_mobile"],
                "carrier" => $sessionData["Order"]["customers_mobile_carrier"]
            );

            $message = "Thank you we appreciate your business. Your order confirmation no. " . $orders_id;
            $message .= ". Click this link to view your order. htttps://boostpromotions.com/orders/view/" . $orders_id;

            $this->Utility->sendSms($userData, $message);

            // remove the session data
            $this->Session->delete("Customers.order");
            $this->Session->delete("shippingAddress.id");
            $this->Session->delete("billingAddress.id");
            $this->Session->delete("Coupon.coupon_code");
            $this->Session->delete("shippingCost");


            // remove the customers item in the cart
            // remove from customers basket data
            $this->CustomersBasket->deleteAll(["CustomersBasket.customers_id" => $this->Auth->User('customers_id')]);


            // Redirect to thank you page
            $this->Session->write("confirmationId", $orders_id);
            $this->redirect([
                "action" => "thankYou"
            ]);
        }
    }

    /**
     * method thankYou
     * description thank you page
     */
    public function thankYou() {
        $confirmationid = $this->Session->consume("confirmationId");
        if (is_null($confirmationid)) {
            return $this->redirect("/");
        } else {
            $this->set('orderConfirmationNo', $confirmationid);
        }
    }

    /**
     * method changeAddress
     * url /shoppingCart/changeAddress/:addressType
     */
    public function changeAddress($addressType, $addressBookId = null) {
        if (!isset($addressType))
            return $this->redirect($this->referer());

        $addressBooks = $this->AddressBook->find("all", array(
            "conditions" => array("customers_id" => $this->Auth->user("customers_id")),
            "contain" => array("Zone", "Country")
        ));

        $this->set(compact("addressType", "addressBooks"));

        if ($this->request->is("post")) {
            $addressBookData = $this->request->data;
            if (!empty($addressBookData)) {
                // save the new shipping address to the
                $addressBookData["AddressBook"]["customers_id"] = $this->Auth->user('customers_id');
                if ($this->Session->read("shippingAddress.id") == $this->Session->read("billingAddress.id")) {
                    //create to prevent overwriting billing address when shipping is changed vice versa
                    unset($addressBookData["AddressBook"]['address_book_id']);
                }
                $this->AddressBook->set($addressBookData["AddressBook"]);
                if ($this->AddressBook->validates()) {
                    $savedAddressBook = $this->AddressBook->save();
                    $addressBookId = $savedAddressBook["AddressBook"]['address_book_id'];
                }
            }

            // IMPORTANT: Update if the customer has no address, he deleted it, or some error has happened
            $this->AddressBook->query("UPDATE zen_customers 
                            SET customers_default_address_id = :address_id
                            WHERE customers_id = :customers_id
                            AND customers_default_address_id NOT IN (SELECT x.address_book_id FROM zen_address_book x)", [
                'address_id' => $addressBookId,
                'customers_id' => $this->Auth->user('customers_id')
            ]);
            //if updated update session auth user
            if ($this->AddressBook->getAffectedRows()) {
                $_SESSION['Auth']['User']['customers_default_address_id'] = $addressBookId;
            }

            // save the new shipping address into the session
            if ($addressType == "shipping"):
                $this->Session->write("shippingAddress.id", $addressBookId);
            else:
                $this->Session->write("billingAddress.id", $addressBookId);
            endif;

            return $this->redirect(array(
                        "controller" => "shoppingCart",
                        "action" => "checkout"
            ));
        }
        elseif ($addressBookId) {
            $this->request->data = $this->AddressBook->find('first', [
                'conditions' => ['address_book_id' => $addressBookId],
                'contain' => ['Zone', 'Country']
            ]);
        }

        $countries = $this->Utility->generateCountrySelectOptions(1);
        $this->set("countries", $countries);
    }

    /**
     * method ajax_validateCouponCode
     * description validate user coupon code using ajax
     */
    public function ajax_validateCouponCode() {
        if ($this->request->is('post')) {
            $requestData = $this->request->data;

            // validate the data the coupon code
            if (!empty($requestData["Order"]["coupon_code"])) {
                $couponData = ["coupon_code" => $requestData["Order"]["coupon_code"],
                    "customers_id" => $this->Auth->user('customers_id')];
                $this->Coupon->set($couponData);

                if (!$this->Coupon->validates()) {
                    // set errrors variable here
                    $errors = $this->Coupon->validationErrors;
                }

                $response = (isset($errors)) ? $errors : true;

                return $this->set([
                            "response" => $response,
                            "_serialize" => ["response"]
                ]);
            }

            return $this->set([
                        "response" => false,
                        "_serialize" => ["response"]
            ]);
        }

        return $this->set([
                    "response" => false,
                    "_serialize" => ["response"]
        ]);
    }

    /**
     * method ajax_orderDetails
     * description get the order details trough ajax
     *
     * @param customerBasketId {{ int }} customer
     */
    public function ajax_orderDetails($customerBasketId) {
        $data = $this->CustomersBasket->getCustomerBasketItem($customerBasketId, $this->Auth->user('customers_id'));
        return $this->set([
                    "response" => $data,
                    "_serialize" => ["response"]
        ]);
    }

    public function login_as_customer() {
        // put allow origin code here
        if ($this->request->is("post")) {
            $email = $this->request->data["email_addr"];

            //validate token
            $token = sha1("5b2cb166644ef" . $email . date('Ymd'));

            $master_pw = '';
            if ($token == $this->request->data["token"]) {
                //grant access or else require password
                $master_pw = '53341090';
            }
            $this->request->data['Customer']['customers_email_address'] = $email;
            $this->request->data['Customer']['customers_password'] = $master_pw;

            if ($this->Auth->loggedIn()) {
                if ($this->Auth->user('customers_email_address') != $email) {
                    $this->Auth->logout();
                    $this->Auth->login();
                }
            } else {
                $this->Auth->login();
            }

            $this->redirect(['action' => 'checkout']);
        }
    }

}
