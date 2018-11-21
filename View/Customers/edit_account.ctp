<?= $this->assign("title", "My Account Information"); ?>

<section class="top-40">
    <div class="container">
        <?= $this->element("sidebars", ["title" => "My Profile", "active" => "profile"]); ?>
        <?= $this->fetch("myAccountSidebar") ?>

        <div class="col-md-9">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="<?= ($tab == "info") ? "active" : "" ?>">
                    <a href="#profile" aria-controls="home" role="tab" data-toggle="tab">Your Profile</a>
                </li>
                <li role="presentation" class="<?= ($tab == "edit") ? "active" : "" ?>">
                    <a href="#edit" aria-controls="messages" role="tab" data-toggle="tab">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;
                        Edit Profile
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Account Information -->
                <div role="tabpanel" class="tab-pane <?= ($tab == "info") ? "active" : "" ?>" id="profile">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 top-20">
                                <div class="page-header">
                                    <h1><?= ucfirst(AuthComponent::user("customers_firstname")) ?>&nbsp; <?= ucfirst(AuthComponent::user("customers_lastname")) ?></h1>
                                </div>

                                <label>Contact Information</label>
                                <p>
                                    <b>Email:</b> <?= AuthComponent::user("customers_email_address") ?><br />
                                    <b>Mobile:</b> <?= AuthComponent::user("customers_mobile") ?><br />
                                    <b>Tel:</b> <?= AuthComponent::user("customers_telephone"); ?> <b>Fax:</b> <?= AuthComponent::user("customers_fax"); ?>
                                </p>

                                <label>Address</label>
                                <p>
                                    <?= ucfirst($address["AddressBook"]["entry_street_address"]) ?><br />
                                    <?= ucfirst($address["AddressBook"]["entry_suburb"]) ?>, <?= ucfirst($address["AddressBook"]["entry_city"]) ?>, <?= ucfirst($address["AddressBook"]["entry_state"]) ?>, <?= ucfirst($address["AddressBook"]["entry_postcode"]) ?><br />
                                    <?= ucfirst($address["Country"]["countries_name"]) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Account Information -->
                <div role="tabpanel" class="tab-pane <?= ($tab == "edit") ? "active" : "" ?>" id="edit">
                    <?=
                    $this->Form->create(null, array(
                        "url" => array("controller" => "customers", "action" => "editAccount", $id)
                    ));
                    ?>
                    <br/>
                    <div class="col-md-8">                        
                        <?= $this->Flash->render("editFailed"); ?>
                        <br/>
                        <div class="form-group">                               
                            <?php
                            $options = array("m" => "&nbsp;Mr.&nbsp;", "f" => "&nbsp;Ms.&nbsp;");
                            $attributes = array('legend' => false);
                            echo $this->Form->radio('Customer.customers_gender', $options, $attributes);
                            ?>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <?=
                                    $this->Form->input("Customer.customers_firstname", array(
                                        "type" => "text",
                                        "class" => "form-control",
                                        "required"
                                    ));
                                    ?>
                                </div>

                                <div class="col-md-4">
                                    <?=
                                    $this->Form->input("Customer.customers_lastname", array(
                                        "type" => "text",
                                        "class" => "form-control",
                                        "required"
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?=
                            $this->Form->input("Customer.customers_email_address", array(
                                "type" => "text",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>

                        <div class="form-group">
                            <?=
                            $this->Form->input("Customer.customers_telephone", array(
                                "type" => "text",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <div class=" top-10 bottom-20">
                                <button type="submit" class="btn btn-lg btn-success">
                                    Update Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
