<?= $this->assign("title", "Boost Promotions - Register"); ?>
<section >
    <div class="container">
        <h1>MY ACCOUNT INFORMATION</h1>
        <p class="text-info">NOTE: If you already have an account with us, please login at the login page.</p>

        <p class="top-20 text-warning">
            IMPORTANT NOTICE
        </p>
        <div class="alert alert-info">
            <p>In an effort to provide prompt customer service please notify the individual responsible for your <b class="text-white">EMAIL SERVER</b> that you need to have <b class="text-white">BOOSTPROMOTIONS.COM</b> enabled as a safe sender. Our ability to contact you through email and your prompt replies are essential to avoid delays to your order. We will be sending emails with images for artwork approvals that can, and often are blocked by firewall and email server settings if not specifically set up to allow emails and images from <b class="text-white">BOOSTPROMOTIONS.COM</b>. </p>

            <p>If you at all believe that this may be an issue, please assign a personal email for Boost Promotions correspondence.</p>
        </div>
        <?= $this->Flash->render(); ?>
    </div>

    <?=
    $this->Form->create(null, array(
        "url" => array("controller" => "customers", "action" => "register")
    ));
    ?>
    <div class="container">
        <fieldset>
            <fieldset>
                <div class="col-md-12">
                    <div class="form-group">
                        <legend>Your Personal Details</legend>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>How did you hear about us?</label>
                        <?=
                        $this->Form->input("CustomersInfo.sources_id", array(
                            "options" => $sources,
                            "empty" => 'Select Source',
                            "class" => "form-control",
                            "label" => false
                        ));
                        ?>
                    </div>

                    <div class="form-group">
                        <label>Company Details</label>
                        <?=
                        $this->Form->input("AddressBook.entry_company", array(
                            "type" => "text",
                            "class" => "form-control",
                            "placeholder" => "Company Name",
                            "label" => false
                        ));
                        ?>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="col-md-12">
                    <div class="form-group">
                        <legend>Address Details</legend>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                $options = array("m" => "&nbsp;Mr. &nbsp;&nbsp;", "f" => "&nbsp;Ms.");
                                $attributes = array('legend' => false, "required");
                                echo $this->Form->radio('Customer.customers_gender', $options, $attributes);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?=
                        $this->Form->input("Customer.customers_firstname", array(
                            "type" => "text",
                            "placeholder" => "First Name",
                            "class" => "form-control",
                            "label" => "First Name",
                            "required"
                        ));
                        ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <?=
                        $this->Form->input("Customer.customers_lastname", array(
                            "type" => "text",
                            "placeholder" => "Last Name",
                            "class" => "form-control",
                            "label" => "Last Name",
                            "required"
                        ));
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
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
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <?=
                        $this->Form->input("AddressBook.entry_street_address", array(
                            "type" => "text",
                            "placeholder" => "Street Address",
                            "class" => "form-control",
                            "label" => "Street Address",
                            "required"
                        ));
                        ?>
                    </div>
                    <div class="form-group">
                        <?=
                        $this->Form->input("AddressBook.entry_suburb", array(
                            "type" => "text",
                            "placeholder" => "Address Line 2",
                            "class" => "form-control",
                            "label" => "Address Line 2",
                        ));
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <?=
                                $this->Form->input("AddressBook.entry_city", array(
                                    "type" => "text",
                                    "placeholder" => "City",
                                    "class" => "form-control",
                                    "label" => "City",
                                    "required"
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <?=
                                $this->Form->input("AddressBook.entry_state", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "placeholder" => "State Province",
                                    "label" => "State/Province",
                                    "required"
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <?=
                                $this->Form->input("AddressBook.entry_postcode", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => "Post/Zip Code",
                                    "required"
                                ));
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="col-md-12">
                    <legend>Additional Contact Details</legend>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <?=
                            $this->Form->input("Customer.customers_telephone", array(
                                "type" => "text",
                                "class" => "form-control",
                                "placeholder" => "Telepone Number",
                                "label" => "Telephone Number",
                                "required"
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="col-md-12 top-20">
                        <div class="form-group">
                            <div class="col-md-3">
                                <?=
                                $this->Form->input("Customer.customers_mobile", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "placeholder" => "Mobile Number",
                                    "label" => "Mobile Number",
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="col-md-3">
                                <?=
                                $this->Form->input("Customer.customers_mobile_carrier", array(
                                    "options" => $mobileCarriers,
                                    "class" => "form-control",
                                    "empty" => "Select Mobile Carrier",
                                    "label" => "Mobile Carrier",
                                    "required"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <div class="col-md-12 top-20">
                    <legend>Login Details</legend>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?=
                                $this->Form->input("Customer.customers_email_address", array(
                                    "type" => "email",
                                    "class" => "form-control",
                                    "label" => "Email Address",
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="form-group">
                                <?=
                                $this->Form->input("Customer.customers_password", array(
                                    "type" => "password",
                                    "class" => "form-control",
                                    "label" => "Password",
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="form-group">
                                <?=
                                $this->Form->input("Customer.customers_confirm_password", array(
                                    "type" => "password",
                                    "class" => "form-control",
                                    "label" => "Confirm Password",
                                    "required"
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </fieldset>

            <fieldset>

                <div class="col-md-2 top-20">
                    <button type="submit" class="btn btn-success btn-block">
                        Register
                    </button>
                </div>
            </fieldset>
        </fieldset>
    </div>
    <?= $this->Form->end(); ?>
</section>
