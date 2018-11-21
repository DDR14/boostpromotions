<?php

App::uses("AppModel", "Model");

class Order extends AppModel {

    public $tablePrefix = 'zen_';
    public $primaryKey = 'orders_id';
    public $belongsTo = array(
        "OrdersStatus" => array(
            "className" => "OrdersStatus",
            "foreignKey" => "orders_status"
        ),
        "Customer" => array(
            "className" => "Customer",
            "foreignKey" => "customers_id"
        )
    );
    public $hasOne = array(
    );
    public $hasMany = array(
        "OrdersStatusHistory" => array(
            "className" => "OrdersStatusHistory",
            "foreignKey" => "orders_id"
        ),
        "OrdersProduct" => array(
            "className" => "OrdersProduct",
            "foreignKey" => "orders_id"
        ),
        "CustomCo" => array(
            "className" => "CustomCo",
            "foreignKey" => "order_id"
        ),
        "OrdersTotal" => array(
            "className" => "OrdersTotal",
            "foreignKey" => "orders_id"
        ),
        "Proof" => array(
            "className" => "Proof",
            "foreignKey" => "order_id"
        ),
        "Charge" => array(
            "className" => "Charge",
            "foreignKey" => "order_id"
        )
    );

    public function hasCoupon($code) {
        return $this->Coupon->hasAny(['coupon_code' => $code]);
    }

    /**
     * method isOwnedBy
     * description check the customer owned the data
     */
    public function isOwnedBy($order, $customer) {
        return $this->field('customers_id', array('orders_id' => $order, 'customers_id' => $customer)) !== false;
    }

    /**
     * method getCustomerPrevOrderss
     * description get customer previous orders 
     * 
     * @param $customerId {{ int }} customers id
     */
    public function getCustomerPrevOrderss($customersId)
    {
        return $this->find("all", array(
            "conditions" => array(
                "Order.customers_id" => $customersId
            ),
            "order" => array(
                "date_purchased" => "desc"
            ),
            "contain" => array(
                "OrdersTotal",
                "OrdersProduct",
                "OrdersStatus"
            )
        ));
    }

}
