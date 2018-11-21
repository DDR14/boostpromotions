<?php
App::uses('AppModel', 'Model');
/**
 * Special Model
 *
 * @property Products $Products
 */
class Special extends AppModel {

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'specials_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Products' => array(
			'className' => 'Products',
			'foreignKey' => 'products_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
