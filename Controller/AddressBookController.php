<?php

App::uses("AppController", "Controller");

class AddressBooksController extends AppController {

    /**
     * property $uses
     * description models being used by this controller
     */
    var $uses = array("AddressBook", "Customer");

    /**
     * method isAuthorized
     * description controller level isAuthorized method
     */
    public function isAuthorized($customer = null) {
        // All registered users can add posts
        if ($this->action === 'add') {
            return true;
        }

        // The owner of a post can edit and delete it
        if (in_array($this->action, array('edit', 'delete'))) {
            $addressBookId = (int) $this->request->params['pass'][0];
            if ($this->AddressBook->isOwnedBy($addressBookId, $customer['customers_id'])) {
                return true;
            }
        }

        return parent::isAuthorized($customer);
    }

    /**
     * method add
     * descriptin add customer address book details
     * url  /addressBooks/add
     */
    public function add() {
        if ($this->request->is("post")) {
            $customersId = $this->Auth->user("customers_id");

            $requestData = $this->request->data;
            $requestData["AddressBook"]["customers_id"] = $customersId;

            $this->AddressBook->set($requestData["AddressBook"]);

            if ($this->AddressBook->checkForDuplicateEntry($requestData["AddressBook"])) {
                if ($this->AddressBook->validates()) {

                    // limit the address book entries to 5
                    $addressBookCount = $this->AddressBook->find("count", [
                        "conditions" => [
                            "customers_id" => $customersId
                        ]
                    ]);

                    if ($addressBookCount <= 6) {
                        $addressBookData = $this->AddressBook->save($requestData["AddressBook"]);
                        if ($requestData["Misc"]["primary"] == "1") {
                            $this->Customer->id = $customersId;
                            $this->Customer->saveField("customers_default_address_id", $addressBookData["AddressBook"]["address_book_id"]);
                            $_SESSION['Auth']['User']['customers_default_address_id'] = $addressBookData["AddressBook"]["address_book_id"];
                        }

                        $this->Flash->success("New Address Book added.", array("key" => "addressBookAdded"));
                    } else {
                        $this->Flash->warning("Address book entries exceeds limit. Please consider deleting some entries.", array(
                            "key" => "maxEntries"
                        ));
                    }

                    // redirect the user
                    return $this->redirect(array(
                                "controller" => "addressBooks",
                                "action" => "view"
                    ));
                }
            }

            $this->Flash->warning("Address book already exists in the database. Please choose another one.", array("key" => "duplicateEntry"));
        }
        
        $countries = $this->Utility->generateCountrySelectOptions(1);
        $this->set("countries", $countries);
    }

    /**
     * method view
     * description view customer address book details
     * url /addressBooks/view
     */
    public function view() {
        $customersId = $this->Auth->user("customers_id");
        $customerData = $this->Customer->findByCustomersId($customersId);
        $defaultAddressId = $customerData["Customer"]["customers_default_address_id"];

        $addressBooks = $this->AddressBook->find("all", array(
            "conditions" => array(
                "customers_id" => $customersId
            ),
            "contain" => array(
                "Country"
            ),
            "order" => [["AddressBook.address_book_id = $defaultAddressId DESC"]]
        ));

        $this->set(compact("addressBooks", "defaultAddressId"));
    }

    /**
     * method edit
     * description edit customer address book details
     * url /addressBooks/edit/:id
     */
    public function edit($id = null) {
        if ($this->request->is("post") || $this->request->is("put")) {
            $requestData = $this->request->data;
            $this->AddressBook->set($requestData["AddressBook"]);
            $customersId = $this->Auth->user("customers_id");

            if ($this->AddressBook->validates()) {
                $addressBookData = $this->AddressBook->save($requestData["AddressBook"]);

                if ($requestData["Misc"]["primary"] == 1) {
                    $this->Customer->id = $customersId;
                    $this->Customer->saveField("customers_default_address_id", $addressBookData["AddressBook"]["address_book_id"]);
                    $_SESSION['Auth']['User']['customers_default_address_id'] = $addressBookData["AddressBook"]["address_book_id"];
                }

                $this->Flash->success("Address Book Updated.", array("key" => "addressBookUpdated"));

                // redirect the user
                return $this->redirect(array(
                            "controller" => "addressBooks",
                            "action" => "view"
                ));
            }
        }

        if (is_null($id))
            return $this->redirect($this->referer());

        // pre-populate the form
        $addressBookData = $this->AddressBook->findByAddressBookId($id);
        $addressBookData['Misc']['primary'] = $this->Customer->hasAny([
            'customers_default_address_id' => $addressBookData['AddressBook']['address_book_id'],
            'customers_id' => $addressBookData['AddressBook']['customers_id']
        ]);
        $this->request->data = $addressBookData;

        $this->set("edit", true);
        $this->set("addressBookId", $id);
        
        $countries = $this->Utility->generateCountrySelectOptions(1);
        $this->set("countries", $countries);
        
        $this->render("add");        

    }

    /**
     * method delete
     * description delete customer address book data
     * url /addressBooks/delete/:id
     */
    public function delete($id = null) {
        if ($this->request->is("post")) {
            $this->AddressBook->delete($id);
            $this->Flash->success("Address has been deleted.", array("key" => "addressBookDeleted"));

            $this->redirect(array(
                "controller" => "addressBooks",
                "action" => "view"
            ));
        }

        if (is_null($id))
            return $this->redirect($this->referer());
    }

}
