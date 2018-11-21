<?php

App::uses("AppController", "Controller");

class TeachersKitsController extends AppController {

    /**
     * property $uses
     * description models being used by this controller
     */
    var $uses = array("Product", "CategoriesDescription", "CustomersBasket");

    /**
     * method beforeFilter
     * description controller before filter method
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("index");
    }

    /**
     * method index
     * description teachers kits main page
     */
    public function index() {
        $teachersKitsData = $this->CategoriesDescription->getSubCategories("Teacher Kits");
        $this->set("teachersKits", $teachersKitsData);
    }

    /**
     * method customize
     * description customize teachers kits order form
     *
     * @param $productId {{ int }} teacher kit products_id
     */
    public function customize($productId) {
        $productData = $this->Product->find("first", array(
            "conditions" => array(
                "Product.products_id" => $productId
            ),
            "contain" => array(
                "Manufacturer", "ProductsDescription"
            )
        ));

        if (empty($productData))
            return $this->redirect($this->referer());

        $this->set("productData", $productData);

        if ($this->request->is("post")) {
            $requestData = $this->request->data;
            $requestData["CustomersBasket"]["customers_id"] = $this->Auth->user("customers_id");
            $requestData["CustomersBasket"]["products_id"] = $productId;
            $requestData["CustomersBasket"]["customers_basket_date_added"] = date("Y-m-d");

            debug($requestData);

            // save the customers data
            $this->CustomersBasket->save($requestData["CustomersBasket"]);

            // redirect to shopping cart index
            $this->redirect(array(
                "controller" => "shoppingCart",
                "action" => "index"
            ));
        }
    }

    /**
     * method addAddOns
     * description add teacherKits add-ons
     * 
     * @param $productId {{ int }} selected product add on id
     */
    public function addAddOns($productId = null) {
        $addOnsProduct = $this->Product->getTeachersKitsAddOns();
        $this->set("addOns", $addOnsProduct);

        if ($this->request->is("post")) {

            if (is_null($productId)){
                return $this->redirect($this->referer());
            }

            $addOnData = $this->Product->find("first", array(
                "conditions" => array(
                    "Product.products_id" => $productId,
                    "master_categories_id" => 24
                ),
                "contain" => array(
                    "ProductsDescription"
                )
            ));

            $customersBaket = [];
            // save the product data here
            $customersBaket["CustomersBasket"]["customers_basket_quantity"] = 1;
            $customersBaket["CustomersBasket"]["products_id"] = $addOnData["Product"]["products_id"];
            $customersBaket["CustomersBasket"]["customers_id"] = $this->Auth->user("customers_id");
            $customersBaket["CustomersBasket"]["customers_basket_date_added"] = date("Ymd");

            $this->CustomersBasket->save($customersBaket["CustomersBasket"]);
            $this->Flash->success("New teacher kit extra added to cart", array("key" => "added"));

            // TODO::Extra dog tagas sheet

            return $this->redirect(array(
                        "controller" => "shoppingCart",
                        "action" => "index"
            ));
        }
    }

}
