<?php 
App::uses("AppModel", "Model");

class DiscountQuantityTemplate extends AppModel
{
	public $tablePrefix = "products_";
	public $useTable = "discount_quantity_template";
	public $primaryKey = "discount_qty_id";
}