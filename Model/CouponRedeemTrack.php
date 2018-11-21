<?php
App::uses('AppModel', 'Model');
/**
 * CouponRedeemTrack Model
 *
 * @property Coupon $Coupon
 * @property Customer $Customer
 * @property Order $Order
 */
class CouponRedeemTrack extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'coupon_redeem_track';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'unique_id';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Coupon' => array(
			'className' => 'Coupon',
			'foreignKey' => 'coupon_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'order_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
