<?php 
App::uses("AppModel", "Model");

class Zone extends AppModel
{
	public $useTable = "zones";
	public $primaryKey = 'zone_id';

	public $belongsTo = array(
		"Country" => array(
			"className" => "Country",
			"foreignKey" => "zone_country_id"
		)
	);

	public $hasMany = array(
		"AddressBook" => array(
			"className" => "AddressBook",
			"foreignKey" => "entry_zone_id"
 		)
	);
}