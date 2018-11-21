<?php

App::uses("AppController", "Controller");

class CustomersController extends AppController {

    /**
     * property $uses
     * description list of models being used by this controller
     */
    var $uses = array("Customer", "Country", "AddressBook", "Order");

    /**
     * method beforeFilter
     * description this controller beforeFilter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("login", "register", "forgotPassword", "reset");
    }

    /**
     * method login
     */
    public function login() {
        if ($this->Auth->loggedIn()) {
            return $this->redirect($this->referer());
        }
        if ($this->request->is("post")) {
            if ($this->Auth->login()) {
                //update last log in date
                $this->Customer->CustomersInfo->id = $this->Auth->user('customers_id');
                $this->Customer->CustomersInfo->updateAll(
                        [
                            'CustomersInfo.customers_info_date_of_last_logon' => 'NOW()',
                            'CustomersInfo.customers_info_number_of_logons' => 'CustomersInfo.customers_info_number_of_logons+1'
                        ]
                );

                //remember me
                if ($this->request->data('Misc.remember_me')) {
                    $this->Cookie->write('remember_me', $this->Auth->user('customers_id'));
                }

                //add cookie to this customers cart
                $tmpCart = $this->Cookie->read('tmpCart');
                if ($tmpCart) {
                    foreach ($tmpCart as $cart) {
                        //TODO:: if stock tag, merge with existing stock tag with listing, if any.
                        $cart['CustomersBasket']['customers_id'] = $this->Auth->user("customers_id");
                        $this->Customer->CustomersBasket->create();
                        $this->Customer->CustomersBasket->save($cart);
                    }
                    $this->Cookie->delete('tmpCart');
                    return $this->redirect(['controller' => 'ShoppingCart', 'action' => 'checkout']);
                }

                // redirect to home page
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error("Email or Password is incorrect. Please try again.", array("key" => "loginFailed"));
            }
        }
    }

    /**
     * method logout
     */
    public function logout() {
        $this->Session->destroy();
        $this->Cookie->delete('remember_me');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * method add
     * description add new user to database
     * url /customers/add
     */
    public function register() {
        if ($this->request->is("post")) {
            $requestData = $this->request->data;

            $this->Customer->set($requestData["Customer"]);
            $this->AddressBook->set($requestData["AddressBook"]);

            $errors = [];
            if (!$this->Customer->validates()) {
                $errors = $this->Customer->validationErrors;
            }
            if (!$this->AddressBook->validates()) {
                $errors = (isset($errors)) ? array_push($errors, $this->AddressBook->validationErrors) : $this->AddressBook->validationErrors;
            }
            if ($errors) {
                $this->Flash->error('Please check your form.');
            } else {
                // save the customer data
                $requestData["Customer"]["customers_mobile"] = $requestData["Customer"]["customers_mobile"] . $requestData["Customer"]["customers_mobile_carrier"];
                $requestData["Customer"]["customers_country"] = $requestData["AddressBook"]["entry_country_id"];
                $requestData["Customer"]["customers_state"] = $requestData["AddressBook"]["entry_state"];
                $requestData["Customer"]["state"] = $requestData["AddressBook"]["entry_state"];
                $customer = $this->Customer->save($requestData["Customer"]);

                // save customers info
                $this->Customer->CustomersInfo->save([
                    'customers_info_id' => $customer["Customer"]["customers_id"],
                    'customers_info_date_account_created' => date('Y-m-d H:i:s'),
                    'customers_info_source_id' => $requestData['CustomersInfo']['sources_id']
                ]);

                // save the AddressBook data
                $requestData["AddressBook"]["customers_id"] = $customer["Customer"]["customers_id"];
                $requestData["AddressBook"]["entry_firstname"] = $customer["Customer"]["customers_firstname"];
                $requestData["AddressBook"]["entry_lastname"] = $customer["Customer"]["customers_lastname"];
                $requestData["AddressBook"]["entry_gender"] = $customer["Customer"]["customers_gender"];
                $address = $this->AddressBook->save($requestData["AddressBook"]);

                // update the customers_default_country_id
                $this->Customer->id = $customer["Customer"]["customers_id"];
                $this->Customer->saveField("customers_default_address_id", $address["AddressBook"]["address_book_id"]);

                // send notification email to customer
                $this->registrationNotification($customer);
                // render registration complete page
                $this->Flash->success("Registration Complete! You may now log in.", array("key" => "loginFailed"));
                $this->redirect('login');
            }
        }

        // List of mobile carriers
        $this->set("mobileCarriers", $this->Utility->getMobileCarriers());

        // get the countries data and generate data for the country select box        
        $countries = $this->Utility->generateCountrySelectOptions(1);
        $this->set("countries", $countries);

        $this->loadModel('Source');
        $this->set('sources', $this->Source->find('list'));
    }

    /**
     * method registrationNotification
     * description send registration notification email and welcome message
     */
    private function registrationNotification($customer) {
        // email the customer here
        $viewVars = array(
            "lastname" => $customer["Customer"]["customers_lastname"],
            "gender" => $customer["Customer"]["customers_gender"]
        );

        $this->Utility->email($viewVars, "welcome", "default", "Welcome to BoostPromotions.com", $customer["Customer"]["customers_email_address"]);
    }

    /**
     * method edit
     * description change customers account information
     * url /customers/editAccount
     *
     * @param $id {{ }}
     */
    public function editAccount($id = null) {

        $tab = (is_null($id)) ? "info" : "edit";
        $this->set("tab", $tab);
        $this->set("id", $id);

        $defaultAddress = $this->AddressBook->find("first", array(
            "conditions" => array(
                "address_book_id" => $this->Auth->user("customers_default_address_id")
            ),
            "contain" => array("Country")
        ));

        $this->set("address", $defaultAddress);

        if ($this->request->is("post") || $this->request->is("put")) {
            // process form here
            $customerData = $this->request->data['Customer'];
            if ($customerData["customers_email_address"] == $this->Auth->user('customers_email_address')):
                unset($customerData["customers_email_address"]);
            endif;
            $this->Customer->set($customerData);

            if ($this->Customer->validates()) {
                $this->Customer->id = $this->Auth->user("customers_id");
                $this->Customer->save($customerData);

                //update session because this is used on checkout
                $_SESSION['Auth']['User']['customers_email_address'] = $this->request->data['Customer']["customers_email_address"];
                $_SESSION['Auth']['User']['customers_gender'] = $customerData["customers_gender"];
                $_SESSION['Auth']['User']['customers_firstname'] = $customerData["customers_firstname"];
                $_SESSION['Auth']['User']['customers_lastname'] = $customerData["customers_lastname"];
                $_SESSION['Auth']['User']['customers_telephone'] = $customerData["customers_telephone"];

                return $this->Flash->success("Profile Updated", ['key' => 'editFailed']);
            }

            $this->Flash->error("Some thing went wrong. Please check your form", array("key" => "editFailed"));
        } else {
            $id = $this->Auth->user("customers_id");
            $data = $this->Customer->find("first", array(
                "conditions" => array("customers_id" => $id)
            ));

            // pre-populate the form
            $this->request->data = $data;

            $this->set("id", $id);
            $this->set("edit", true);
        }
    }

    /**
     * method changePassword
     * description chagen cusutomer passwrod
     */
    public function changePassword() {
        if ($this->request->is("post")) {
            $customersId = $this->Auth->user("customers_id");
            $requestData = $this->request->data;

            if ($this->Customer->checkOldPassword($customersId, $requestData["Customer"]["customers_current_password"])) {
                $this->Customer->set($requestData["Customer"]);
                if ($this->Customer->validates()) {
                    $requestData["Customer"]["customers_id"] = $customersId;
                    $this->Customer->save($requestData["Customer"]);

                    $this->Flash->success("Your password has been changed.", array("key" => "incorrectPass"));

                    return $this->redirect(array(
                                "controller" => "customers",
                                "action" => "changePassword"
                    ));
                }
            } else {
                $this->Flash->warning("Current password is incorrect. Please try again.", array("key" => "incorrectPass"));
            }
        }
    }

    /**
     * method forgotPassword
     * description 
     */
    public function forgotPassword() {
        if ($this->Auth->loggedIn()) {
            return $this->redirect($this->referer());
        }

        if ($this->request->is('post')) {
            $email = $this->data['Customer']['customers_email_address'];
            if (empty($email)) {
                $this->Flash->error('Please Provide the Email Adress that you used to Register with us');
            } else {
                $fu = $this->Customer->findFirstByCustomersEmailAddress($email);
                if ($fu) {
                    $key = Security::hash(CakeText::uuid(), 'sha512', true);
                    $hash = sha1($fu['Customer']['customers_lastname'] . rand(0, 100));
                    $url = Router::url(array('controller' => 'customers', 'action' => 'reset'), true) . '/' . $key . '#' . $hash;
                    $ms = wordwrap($url, 1000);
                    $fu['Customer']['tokenhash'] = $key;
                    $this->Customer->id = $fu['Customer']['customers_id'];
                    if ($this->Customer->saveField('tokenhash', $fu['Customer']['tokenhash'])) {

                        //============Email================//
                        $this->Utility->email(['ms' => $ms], "resetpw", "default", "Reset your BoostPromotions.com password", $email);

                        $this->Flash->success(__('Please check your email for the password reset link. Thank you.', true));
                    } else {
                        $this->Flash->error("Error Generating Reset link");
                    }
                } else {
                    $this->Flash->error('Email does Not Exist');
                }
            }
        }
    }

    function reset($token = null) {
        $this->Customer->recursive = -1;
        if (!empty($token)) {
            $u = $this->Customer->findFirstByTokenhash($token);
            if (!empty($u)) {
                if ($this->request->is("post")) {
                    $customersId = $u['Customer']['customers_id'];
                    $requestData = $this->request->data;

                    $this->Customer->set($requestData["Customer"]);
                    if ($this->Customer->validates()) {
                        $requestData["Customer"]["customers_id"] = $customersId;
                        $requestData["Customer"]["tokenhash"] = uniqid();
                        $this->Customer->save($requestData["Customer"]);

                        $this->Flash->success("Your password has been changed.", array("key" => "loginFailed"));

                        return $this->redirect(array(
                                    "controller" => "customers",
                                    "action" => "login"
                        ));
                    }
                }
            } else {
                $this->Flash->error('Token Corrupted, the reset link work only for once.');
            }
        } else {
            $this->redirect('/');
        }
    }

}
