<?php

App::uses("AppModel", "Model");

class CustomCo extends AppModel {

    public $useTable = "custom_co";
    public $primaryKey = "id";
    public $tablePrefix = "naz_";
    public $belongsTo = array(
        "Order" => array(
            "className" => "Order",
            "foreignKey" => "order_id"
        ),
        "Customer" => array(
            "className" => "Customer",
            "foreignKey" => "user_id"
        ),
        "OrdersProduct" => array(
            "className" => "OrdersProduct",
            "foreignKey" => "orders_products_id"
        )
    );
    public $hasMany = array(
        "Proof" => array(
            "className" => "Proof",
            "foreignKey" => "naz_custom_id"
        )
    );

    /**
     * method getItem
     * description get the selected custom co data in the database
     * 
     * @param $id {{ int }} selected naz_custom_co id
     * @param $customerId {{ int }} logged in customer id
     */
    public function getItem($id, $customerId) {
        return $this->find("first", [
                    "conditions" => [
                        "id" => $id,
                        "user_id" => $customerId
                    ],
                    "contain" => [
                        "OrdersProduct" => [
                            "Product" => [
                                "ProductsDescription",
                                "ProductsDiscountQuantity",
                                "Manufacturer"
                            ]
                        ]
                    ]
        ]);
    }

}
