<?php
App::uses("AppModel", "Model");

class Manufacturer extends AppModel
{
	public $useTable = "manufacturers";
	public $primaryKey ="manufacturers_id";

	public $hasMany = array(
		"Product" => array(
			"className" => "Product",
			"foreignKey" => "manufacturers_id"
		)
	);
}