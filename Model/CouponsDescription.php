<?php

App::uses('AppModel', 'Model');

/**
 * CouponsDescription Model
 *
 * @property Coupon $Coupon
 */
class CouponsDescription extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'coupons_description';

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'coupon_id';


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
        )
    );

}
