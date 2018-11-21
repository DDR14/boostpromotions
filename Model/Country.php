<?php
App::uses("AppModel", "Model");

class Country extends AppModel
{
	/**
	* property $primaryKey
	* description database primary key
	*/
	public $primaryKey = 'countries_id';

	/**
	* property $hasMany
	* description model has many relationships
	*/
	public $hasMany = array(
		"AddressBook" => array(
			"className" => "AddressBook",
			"foreignKey" => "entry_country_id"
		),
		"Zone" => array(
			"className" => "Zone",
			"foreignKey" => "zon_country_id"
		)
	);
}