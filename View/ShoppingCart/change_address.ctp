<section>
    <div class="container">
        <div class="page-header">
            <h1>Change <?= ucfirst($addressType) ?> Address</h1>
        </div>
        <?= $this->Flash->render("address"); ?>
        <div class="row">
            <div class="col-md-6">
                <div>
                    <?=
                    $this->Form->create(null, array(
                        "url" => array("controller" => "shoppingCart", "action" => "changeAddress", $addressType)
                    ));
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->hidden("AddressBook.address_book_id"); ?>
                            <?=
                            $this->Form->input("AddressBook.entry_company", array(
                                "type" => "text",
                                "placeholder" => "Company Name",
                                "class" => "form-control",
                                "label" => "Company Name",
                            ));
                            ?>
                        </div>
                    </div>
                    <hr/>
                    <?php
                    $options = array("m" => "&nbsp;Mr.&nbsp;", "f" => "&nbsp;Ms.");
                    $attributes = array('legend' => false, "required", "value" => "m");
                    echo $this->Form->radio('AddressBook.entry_gender', $options, $attributes);
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $this->Form->input("AddressBook.entry_firstname", array(
                                "type" => "text",
                                "label" => "Firstname",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>

                        <div class="col-md-6">
                            <?=
                            $this->Form->input("AddressBook.entry_lastname", array(
                                "type" => "text",
                                "label" => "Lastname",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="row top-20">
                        <div class="col-md-6">
                            <?=
                            $this->Form->input("AddressBook.entry_country_id", array(
                                "options" => $countries,
                                "empty" => "Select Your Country",
                                "label" => "Country",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>

                        <div class="col-md-12">
                            <?=
                            $this->Form->input("AddressBook.entry_street_address", array(
                                "type" => "text",
                                "label" => "Street Address",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>

                        <div class="col-md-12">
                            <?=
                            $this->Form->input("AddressBook.entry_suburb", array(
                                "type" => "text",
                                "label" => "Address Line 2",
                                "class" => "form-control"
                            ));
                            ?>
                        </div>

                        <div class="col-md-4">
                            <?=
                            $this->Form->input("AddressBook.entry_city", array(
                                "type" => "text",
                                "label" => "City",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>

                        <div class="col-md-4">
                            <?=
                            $this->Form->input("AddressBook.entry_state", array(
                                "type" => "text",
                                "label" => "State/Province",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>

                        <div class="col-md-4">
                            <?=
                            $this->Form->input("AddressBook.entry_postcode", array(
                                "type" => "text",
                                "label" => "Post/Zip Code",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>
                    </div>


                    <button class=" top-20 btn btn-success" type="submit">Change <?= ucfirst($addressType) ?> Address</button>

                    <?= $this->Form->end(); ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Choose From Your Address Book Entries</h4>
                    </div>
                    <div class="panel-body">
                        <?php foreach ($addressBooks as $address): ?>
                            <?= ucfirst($address["AddressBook"]["entry_company"]); ?>
                            <br />
                            <?= ucfirst($address["AddressBook"]["entry_firstname"]); ?>&nbsp;<?= ucfirst($address["AddressBook"]["entry_lastname"]); ?>
                            <br />
                            <?= ucfirst($address["AddressBook"]["entry_street_address"]); ?>
                            <div>
                                <?= ucfirst($address["AddressBook"]["entry_suburb"]); ?>
                            </div>
                            <?= ucfirst($address["AddressBook"]["entry_city"]); ?>, <?= ucfirst($address["AddressBook"]["entry_state"]); ?>
                            <?= ucfirst($address["AddressBook"]["entry_postcode"]); ?>
                            <br />
                            <?= ucfirst($address["Country"]["countries_name"]); ?>
                            <p></p>
                            <?=
                            $this->Form->postLink("Select Address", array(
                                "controller" => "shoppingCart",
                                "action" => "changeAddress",
                                $addressType,
                                $address["AddressBook"]["address_book_id"]
                                    ), array("class" => "btn btn-primary",)
                            );
                            ?>
                            <hr />
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
