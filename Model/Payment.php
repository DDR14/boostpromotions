<?php
/**
 * 
 * I modified C:\wamp\www\lib\Cake\View\Helper
 * line: 1061 to has-error from error
 * 
 */
App::uses("AppModel", "Model");

class Payment extends AppModel {

    public $name = 'Payment';
    public $useTable = false;

    /**
     * property $vaidate
     * descriptin model validation rules
     */
    public $validate = [
        "cc_amount" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "Amount is required."
            ],
            "maxLength" => [
                "rule" => ["maxLength", 13],
                'message' => 'Wow.'
            ],
        ],
        "cc_number" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "Card number is required."
            ],
            "maxLength" => [
                "rule" => ["maxLength", 19],
                'message' => 'Card number cannot be more than 19 digits.'
            ],
        ],
        "cc_cvc" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "CVC is required."
            ],
            "maxLength" => [
                "rule" => ["maxLength", 4],
                'message' => 'CVC cannot be more than 4 digits.'
            ],
        ],
        "cc_exp_date" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "CVC is required."
            ],
            "maxLength" => [
                "rule" => ["maxLength", 4],
                'message' => 'CVC cannot be more than 4 digits.'
            ],
        ],
        "billing_company" => [
            "maxLength" => [
                "rule" => ["maxLength", 50],
                'message' => 'Company name cannot be more than 50 characters.'
            ]
        ],
        "billing_firstname" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "First name is required."
            ],
            "maxLength" => [
                "rule" => ["maxLength", 20],
                'message' => 'First name cannot be more than 20 characters.'
            ],
            "custom" => [
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            ]
        ],
        "billing_lastname" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "Last name is required. "
            ],
            "maxLength" => [
                "rule" => ["maxLength", 30],
                'message' => 'Last name cannot be more than 30 characters.'
            ],
            "custom" => [
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            ]
        ],
        "billing_street_address" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "Street Address is required. "
            ],
            "maxLength" => [
                "rule" => ["maxLength", 30],
                'message' => 'Street address cannot be more than 30 characters. Please use Address Line 2'
            ],
//            "custom" => [
//                "rule" => "/^\s*\S+(?:\s+\S+){2}/",
//                "message" => 'Please input a valid address.'
//            ]
        ],
        "billing_state" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "State is required. "
            ],
            "maxLength" => [
                "rule" => ["maxLength", 30],
                'message' => 'State cannot be more than 30 characters.'
            ],
            "custom" => [
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            ]
        ],
        "billing_city" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "City is required. "
            ],
            "maxLength" => [
                "rule" => ["maxLength", 30],
                'message' => 'City cannot be more than 30 characters.'
            ],
        ],
        "billing_postcode" => [
            "notBlank" => [
                "rule" => "notBlank",
                "message" => "Zip is required. "
            ],
            "maxLength" => [
                "rule" => ["maxLength", 30],
                'message' => 'Zip cannot be more than 30 characters.'
            ],
        ]
    ];

}
