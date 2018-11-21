<?php 
App::uses("AppModel", "Model");

class OrdersStatusHistory extends AppModel
{
	public $useTable = "orders_status_history";
	public $primaryKey = 'orders_history_id';

	public $belongsTo = array(
		"Order" => array(
			"className" => "Order",
			"foreignKey" => "orders_id"
		),
		"OrdersStatus" => array(
			"className" => "OrdersStatus",
			"foreignKey" => "orders_status_id"
		)
	);
}