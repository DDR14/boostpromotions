<?php

App::uses("AppModel", "Model");

class AddressBook extends AppModel {

    /**
     * property $useTable
     * description the name of the table being used by this model
     */
    public $useTable = "address_book";

    /**
     * property $primaryKey
     * description database primary key
     */
    public $primaryKey = 'address_book_id';

    /**
     * property $belongsTo
     * description model belongs to relationships
     */
    public $belongsTo = array(
        "Country" => array(
            "className" => "Country",
            "foreignKey" => "entry_country_id"
        ),
        "Zone" => array(
            "className" => "Zone",
            "foreignKey" => "entry_zone_id"
        ),
        "Customer" => array(
            "className" => 'Customer',
            "foreignKey" => "customers_id"
        )
    );

    /**
     * property $vaidate
     * descriptin model validation rules
     */
    public $validate = [
        "entry_firstname" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "Firstname is required."
            ],
            "custom" => [
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            ]
        ],
        "entry_lastname" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "Lastname is required. "
            ],
            "custom" => [
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            ]
        ],
        "entry_street_address" => [
            "minLength" => [
                "rule" => ["minLength", 8],
                'message' => 'Street address must be at least 8 characters long.'
            ],
//            "custom" => [
//                "rule" => "/^\s*\S+(?:\s+\S+){2}/",
//                "message" => 'Please input a valid address.'
//            ]
        ],
        "entry_state" => [
            "minLength" => [
                "rule" => ["minLength", 2],
                'message' => 'State must be at least 2 characters long.'
            ],
            "custom" => [
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            ]
        ],
        "entry_city" => [
            "minLength" => [
                "rule" => ["minLength", 2],
                'message' => 'City must be at least 2 characters long.'
            ]
        ]
    ];

    /**
     * method isOwnedBy
     * description check the customer owned the data
     */
    public function isOwnedBy($addressBook, $customer) {
        return $this->field('customers_id', array('address_book_id' => $addressBook, 'customers_id' => $customer)) !== false;
    }

    /**
     * method checkForDuplicateEntry
     * description check if the addressBook is a duplicate entry
     */
    public function checkForDuplicateEntry($addressBook) {
        $dupicate = $this->find("first", array(
            "recursive" => -1,
            "conditions" => array(
                "entry_street_address" => $addressBook["entry_street_address"],
                "entry_suburb" => $addressBook["entry_suburb"],
                "entry_city" => $addressBook["entry_city"],
                "entry_state" => $addressBook["entry_state"],
                "entry_postcode" => $addressBook["entry_postcode"]
            )
        ));

        return (empty($duplicate)) ? true : false;
    }

}
