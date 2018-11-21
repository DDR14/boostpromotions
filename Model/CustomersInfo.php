<?php

App::uses('AppModel', 'Model');

/**
 * CustomersInfo Model
 *
 * @property CustomersInfo $CustomersInfo
 * @property CustomersInfoSource $CustomersInfoSource
 */
class CustomersInfo extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'customers_info';

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'customers_info_id';


    // The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasOne associations
     *
     * @var array
     */
    public $hasOne = array(
        'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'customers_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'CustomersInfoSource' => array(
            'className' => 'Source',
            'foreignKey' => 'customers_info_source_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
