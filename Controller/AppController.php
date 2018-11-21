<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * property $helpers
     * description application main helpers
     */
    public $helpers = array(
        "Number"
    );

    /**
     * property $componets
     * description application main componets
     */
    public $components = array(
        "RequestHandler",
        "Paginator",
        "Flash",
        "Utility",
        "Session",
        "Cookie",
        "Auth" => array(
            "loginAction" => array("controller" => "customers", "action" => "login"),
            "loginRedirect" => array("controller" => "pages", "action" => "display", "home"),
            "logoutRedirect" => array("controller" => "customers", "action" => "login"),
            "authError" => "Access Denied",
            "authenticate" => array(
                "Boost" => array(
                    "userModel" => "Customer",
                    "fields" => array("username" => "customers_email_address")
                ),
            ),
            "unauthorizedRedirect" => "/",
            "authorize" => array("Controller")
        )
    );

    /**
     * method beforeFilter
     * description app main beforeFilter
     */
    public function beforeFilter() {

        $this->Auth->allow("display");

        // Initialize Cookie
        $this->Cookie->name = 'basket';
        $this->Cookie->time = '2 weeks';
//        $this->Cookie->domain = '';//$this->request->host();
//      //  $this->Cookie->secure = true;  // i.e. only sent if using secure HTTPS
        $this->Cookie->key = 'HSJ3U48jiZe16d3bntAA5JfG9ZeNE8sZ';
        $this->Cookie->httpOnly = true;

        //load Cart Total and BASKET
        $cart_new_total = 0;
        $default_address = '';

        $this->loadModel('CustomersBasket');

        if ($this->Auth->loggedIn()) {
            $this->loadModel('AddressBook');
            $default_address = $this->AddressBook->find('first', ['conditions' => [
                    'AddressBook.customers_id' => $this->Auth->user('customers_id'),
                    'AddressBook.address_book_id' => $this->Auth->user('customers_default_address_id')],
                'contain' => ['Country', 'Zone'],
                'fields' => ['AddressBook.*', 'Country.*', 'Zone.*']]);

            $basketData = $this->CustomersBasket->getCustomerItems($this->Auth->user("customers_id"));
        } else {
            /* if there is a remember me cookie log this guy in */
            if ($this->Cookie->check('remember_me') && !$this->Auth->loggedIn()):

                $this->loadModel('Customer');
                $customer = $this->Customer->findByCustomersId($this->Cookie->read('remember_me'));
                $this->request->data['Customer']['customers_email_address'] = $customer['Customer']['customers_email_address'];
                $this->request->data['Customer']['customers_password'] = 53341090;
                if ($this->Auth->login()) {
                    //update last log in date
                    $this->Customer->CustomersInfo->id = $this->Auth->user('customers_id');
                    $this->Customer->CustomersInfo->updateAll(
                            [
                                'CustomersInfo.customers_info_date_of_last_logon' => 'NOW()',
                                'CustomersInfo.customers_info_number_of_logons' => 'CustomersInfo.customers_info_number_of_logons+1'
                            ]
                    );

                    $this->redirect($this->referer());
                }
            endif;

            // LOAD COOKIE CART
            $tmpCart = [];
            if ($this->Cookie->check('tmpCart')):
                $tmpCart = $this->Cookie->read('tmpCart');
            else:
                $this->Cookie->write('tmpCart', []);
            endif;

            $cart_qty = count($tmpCart);

            $basketData = [];
            foreach ($tmpCart as $key => $cart) {
                $product_price = $this->CustomersBasket->getProductPrice($cart['CustomersBasket']['products_id'], $cart['CustomersBasket']['customers_basket_quantity']);
                $basketData[$key] = $cart + $product_price[0];
            }
        }

        $lanyards = 0;
        $lanyardsQty = 0;
        $totalTagQty = 0;
        $cart_total = 0;
        $cart_qty = 0;
        $this->loadModel('DiscountedDesign');
        $dds = $this->DiscountedDesign->find('all', [
            'conditions' => ['DiscountedDesign.expires_date >' => date('Y-m-d')],
            'fields' => ['dd_new_products_price'],
            'contain' => ['Design' => 'products_model']]);

        foreach ($basketData as $k => $order) {
            $quantity = $order["CustomersBasket"]["customers_basket_quantity"];
            $price = $order[0]["products_price"];

            // Check if the order cart has lanyards included
            if (in_array($order["Product"]["products_id"], [990, 853])) {
                $lanyards += 1;
                $lanyardsQty += $quantity;
            }

            // discounted designs processing
            if (!empty($order['CustomersBasket']['customs'])) {
                                
                $totalDQty = 0;
                $totalDPrice = 0;
                foreach ($dds as $dd) {
                    // this statement finds without looping -Elvis
                    $tmp = strstr($order['CustomersBasket']['customs'], $dd['Design']['products_model']);
                    if ($tmp !== false) {
                        $x = explode(', ', $tmp);
                        $y = explode('=', current($x));

                        $sub2DPrice = $price * $y[1];
                        $totalDQty += $y[1];
                        $totalDPrice += $sub2DPrice - ($sub2DPrice * $dd['DiscountedDesign']['dd_new_products_price'] / 100);                      
                    }
                }
                //compute new price
                $price = ($price * ($quantity - $totalDQty) + $totalDPrice) / $quantity;
                $basketData[$k][0]['products_price'] = $price; // update for viewing
            }

            // Check for the ordered tag qty 
            $totalTagQty += $this->Utility->getTagQty($order);

            $cart_total += ($quantity * $price);
            $cart_qty += $quantity;
            
            //determine product type
            $basketData[$k]['type'] = $this->Utility->checkTagType($order);
        }

        $this->Session->write("accumulatedQty", $totalTagQty);

        // compute the cart total
        $accumulatedQty = ($lanyards > 0) ? $totalTagQty : null;
        if (!is_null($accumulatedQty)) {
            $cart_new_total = ($accumulatedQty < 500) ? $cart_total + $lanyardsQty : $cart_total;
        } else {
            $cart_new_total = $cart_total;
        }

        $this->set('headerData', [
            'cart_qty' => $cart_qty,
            'cart_total' => $cart_new_total,
            'default_address' => $default_address
        ]);
        //for Controller use
        $this->set('basketData', $basketData);

        // Get subcategories for the dropdown link
        $this->loadModel('CategoriesDescription');
        $subCategories = $this->CategoriesDescription->getSubCategories("Design Library");
        // Chunck the array here into 3       
        $subCategoriesGrp = array_chunk($subCategories["Category"], 9);
        $this->set("subCategoriesGrp", $subCategoriesGrp);
    }

    /**
     * @method insAuthorized
     * @description checks user authorization
     */
    public function isAuthorized($customer = null) {
        return true;
    }

    private function initCart($basketData){
        
    }
}
