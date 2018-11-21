<?php

App::uses("AppModel", "Model");

class ProductsDiscountQuantity extends AppModel {

    public $useTable = "products_discount_quantity";
    public $primaryKey = false;
    public $belongsTo = array(
        "Product" => array(
            "className" => "Product",
            "foreignKey" => "products_id"
        ),
        "CustomersBasket" => array(
            "className" => "CustomersBasket",
            "foreignKey" => "products_id"
        )
    );

}
