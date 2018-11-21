<?php

App::uses('AppModel', 'Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Coupon Model
 *
 * @property CouponEmailTrack $CouponEmailTrack
 * @property CouponRedeemTrack $CouponRedeemTrack
 * @property CouponRestrict $CouponRestrict
 */
class Coupon extends AppModel {

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'coupon_id';
    public $tablePrefix = 'zen_';
    // The Associations below have been created with all possible keys, those that are not needed can be removed
    public $hasOne = array(
        'CouponsDescription' => array(
            'className' => 'CouponsDescription',
            'foreignKey' => 'coupon_id',
            'dependent' => false
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'CouponEmailTrack' => array(
            'className' => 'CouponEmailTrack',
            'foreignKey' => 'coupon_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'CouponRedeemTrack' => array(
            'className' => 'CouponRedeemTrack',
            'foreignKey' => 'coupon_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'CouponRestrict' => array(
            'className' => 'CouponRestrict',
            'foreignKey' => 'coupon_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => false,
            'conditions' => 'Order.orders_id = CouponRedeemTrack.order_id',
        )
    );

    /**
     * property $validate
     * description model validation rules
     */
    public $validate = array(
        "coupon_code" => array(
            "validateCoupon" => array(
                "rule" => array("validateCoupon"),
                "message" => "Coupon is invalid"
            )
        )
    );

    // Custom validation rules

    /**
     * method validateCoupon
     * description validate coupon code
     */
    public function validateCoupon() {

        if (isset($this->data[$this->alias]["customers_id"])) {
            $couponCode = $this->data[$this->alias]["coupon_code"];
            $customers_id = $this->data[$this->alias]["customers_id"];

            // check if the entered coupon is equal to the user coupon
            if ($couponCode == $customers_id . 'REF') {
                return 'You cannot use your own referral coupon code.';
            }

            // check if the coupon code actuallly exists in the database  
            $couponData = $this->query("select a.coupon_id, a.coupon_amount,
        (a.coupon_start_date <= now()) AS available,
        a.coupon_expire_date >= now() AS notexpired,
        a.loyal_customer,
        b.redeem_ip,
        z.last_order,
        (CASE WHEN z.last_order > DATE_SUB(a.coupon_start_date, INTERVAL 365 DAY) then 1 ELSE 0 END) AS last365
        from zen_coupons a
        left join zen_coupon_redeem_track b
        on a.coupon_id = b.coupon_id
        AND b.customer_id = $customers_id
        left join (SELECT MAX(x.date_purchased) as last_order
            FROM zen_orders x 
            WHERE x.customers_id = '" . $customers_id . "') as z   
        on 1 = 1
        where a.coupon_code= '$couponCode'
        and a.coupon_active='Y'
        and a.coupon_type !='G'");

            if (empty($couponData)) {
                return "Coupon code is invalid!";
            } else {
                if ($couponData[0]['b']['redeem_ip']) {
                    return 'You have already redeemed this coupon';
                }
                if ($couponData[0]['a']['loyal_customer']) {
                    if (!$couponData[0]['z']['last_order']) {
                        return $couponData[0]['z']['last_order'] . 'This coupon is for customers who have ordered from us before.';
                    }
                    if ($couponData[0][0]['last365']) {
                        //last order must NOT be made within last 365 at the activation of this coupon
                        return 'Cannot redeem coupon. You have existing order(s) within the last 365 days.';
                    }
                } elseif ((int) $couponData[0]['z']['last_order']) {
                    return 'This coupon is for first time customers only.';
                }
                if ($couponData[0][0]['available'] == false) {
                    return 'This coupon is not available yet.';
                }
                if ($couponData[0][0]['notexpired'] == false) {
                    return 'This coupon is expired.';
                }
            }
        }

        return true;
    }

    /**
     * method
     * along with checkout to give credit to the one who referred this cutomer
     */
    public function createReferralCodeCoupon($order_id) {
        $new_referral_code = CakeSession::read("Auth.User.customers_id") . 'REF';

        // check if new coupon code exists
        $hasCoupon = $this->hasAny(['coupon_code' => $new_referral_code]);
        if ($hasCoupon) {
            //Do nothing
        } else {
            // create duplicate coupon
            $coupon_data = array('coupon_code' => $new_referral_code,
                'coupon_amount' => '5%',
                'coupon_type' => 'P',
                'uses_per_coupon' => 0,
                'uses_per_user' => 1,
                'coupon_minimum_order' => 0,
                'coupon_start_date' => date('Y-m-d H:i:s'),
                'coupon_expire_date' => '2030-07-27 00:00:00', // I think this is cousing the error
                'date_created' => date('Y-m-d H:i:s'),
                'date_modified' => date('Y-m-d H:i:s'),
                'coupon_active' => 'Y',
                'coupon_zone_restriction' => 0);

            $this->create();

            // This is cousing the coupon code error
            $savedCouponData = $this->save($coupon_data);
            $coupon_id = $savedCouponData["Coupon"]["coupon_id"];

            // create coupon description
            $coupon_description_data = array('coupon_id' => $coupon_id,
                'language_id' => 1,
                'coupon_name' => CakeSession::read("Auth.User.customers_firstname") . ' Referral Code',
                'coupon_description' => 'Refer friends using referral code and earn 5% total of referees order as credit for every order the customer you refer.'
            );
            $this->CouponsDescription->create();
            $this->CouponsDescription->save($coupon_description_data);

            // restrict from using own coupon
            $data = array('coupon_id' => $coupon_id,
                'redeem_date' => date('Y-m-d H:i:s'),
                'redeem_id' => $_SERVER['REMOTE_ADDR'],
                'customer_id' => CakeSession::read("Auth.User.customers_id"),
                'order_id' => $order_id);

            $this->CouponRedeemTrack->create();
            $this->CouponRedeemTrack->save($data);
        }

        //GIVE CREDIT
        $referer_data = $this->Order->find('first', array(
            'contain' => ['Customer'],
            'conditions' => [
                'Customer.customers_id' => CakeSession::read("Auth.User.customers_id")
            ],
            'fields' => ['Customer.customers_referral', "(SELECT SUM(products_price * products_quantity) "
                . "FROM zen_orders_products WHERE orders_id = $order_id) AS order_total"]));

        if ($referer_data) {
            //give 5% of total credit to referrer
            $referer = (int) substr($referer_data['Customer']['customers_referral'], 0, -3);

            if ($referer != 0) {
                $new_credit = ($referer_data[0]['order_total'] / 100) * 5;

                $this->Order->query("INSERT INTO zen_transactions (txn_type, payment_method, ref_no, amount, memo, txn_date, customers_id) "
                        . "SELECT 'Credit', 'Credit Memo', '" . $referer_data['Customer']['customers_referral'] . "#$order_id', '$new_credit','Referral Credit from Order $order_id', NOW(), '$referer' FROM zen_customers where customers_id ='" . $referer . "'");

                //this is the referrer's data
                $referer_data2 = $this->Order->Customer->find('first', array('conditions' => ['Customer.customers_id' => $referer],
                    'fields' => ['customers_email_address',
                        'customers_firstname', 'customers_lastname']));

                // TODO::uncomment remove to send message.
                // notify
                $EmailCust = new CakeEmail();
                $EmailCust->template('default')
                        ->subject("Credit of $$new_credit earned from your referral")
                        ->emailFormat('html')
                        ->to($referer_data2['Customer']['customers_email_address'])
                        ->from('customerservice@boostpromotions.com')
                        ->send('Your referral '
                                . CakeSession::read("Auth.User.customers_firstname") . ' ' . CakeSession::read("Auth.User.customers_lastname")
                                . " has made an order thereby granting a credit of $$new_credit for your next order.");
            }
        }
    }

}
