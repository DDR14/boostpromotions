<?php

App::uses('AppModel', 'Model');

/**
 * Source Model
 *
 * @property CustomersInfo $CustomersInfo
 */
class Source extends AppModel {

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'sources_id';

    public $displayField = 'sources_name';
}
