<?php 
App::uses("AppModel", "Model");

class OrdersStatus extends AppModel
{
	public $useTable = "orders_status";
	public $primaryKey = 'orders_status_id';

	public $hasMany = array(
		"Order" => array(
			"className" => "Order",
			"foreignKey" => "orders_status"
		),
		"OrdersStatusHistory" => array(
			"className" => "ordersStatusHistory",
			"foreignKey" => "orders_status_id"
		)
	);
}