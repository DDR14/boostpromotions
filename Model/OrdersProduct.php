<?php
App::uses("AppModel", "Model");

class OrdersProduct extends AppModel
{
	public $useTable = "orders_products";
	public $primaryKey = 'orders_products_id';

	public $belongsTo = array(
		"Order" => array(
			"className" => "Order",
			"foreignKey" => "orders_id"
		),
		"Product" => array(
			"className" => "Product",
			"foreignKey" => "products_id"
		)
	);

	public $hasOne = array(
		"CustomCo" => array(
			"className" => "CustomCo",
			"foreignKey" => "orders_products_id"
		) 
	);
}