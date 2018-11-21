<?php

App::uses('AppModel', 'Model');

/**
 * Transaction Model
 *
 * @property Customers $Customers
 */
class Transaction extends AppModel {

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'txn_id';
    public $tablePrefix = 'zen_';

    // The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Customers' => array(
            'className' => 'Customers',
            'foreignKey' => 'customers_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
