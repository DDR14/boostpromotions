<?php

App::uses("AppModel", "Model");

class Proof extends AppModel {

    public $useTable = "proofs";
    public $primaryKey = "id";
    public $tablePrefix = false;
    public $belongsTo = array(
        "Order" => array(
            "className" => "Order",
            "foreignKey" => "order_id"
        ),
        "CustomCo" => array(
            "className" => "CustomCo",
            "foreignKey" => "naz_custom_id"
        ),
        'OrdersProduct' => [
            'className' => 'OrdersProduct',
            'foreignKey' => false,
            'conditions' => 'OrdersProduct.orders_products_id = CustomCo.orders_products_id'
        ],
    );

}
