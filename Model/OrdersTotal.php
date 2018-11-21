<?php 
App::uses("AppModel", "Model");

class OrdersTotal extends AppModel
{
	public $useTable = "orders_total";
	public $primaryKey = 'orders_total_id';

	public $belongsTo = array(
		"Order" => array(
			"className" => "Order",
			"foreignKey" => "orders_id"
		)
	);
}