<?php
App::uses("AppModel", "Model");

class ProductsDescription extends AppModel
{
	public $useTable = "products_description";
	public $primaryKey = "products_id";

	public $belongsTo = array(
		"Product" => array(
			"className" => "Product",
			"foreignKey" => "products_id"
		)
	);
}