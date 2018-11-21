<section class="top-40">
    <div class="container">
        <?php
        $title = (isset($edit)) ? "Update Address" : "Add New Address";
        ?>

        <?= $this->element("sidebars", ["title" => $title, "active" => "addressbook"]); ?>
        <?= $this->fetch("myAccountSidebar") ?>

        <div class="col-md-9">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation">
                    <?= $this->Html->link("Address Book", "/addressBooks/view", array("escape" => false)); ?>
                </li>
                <li role="presentation" class="active">
                    <a href="#address-book" aria-controls="home" role="tab" data-toggle="tab">
                        <i class='fa fa-plus'></i> &nbsp;
                        Add New Entry
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="addform">
                    <?php if (isset($edit)): ?>
                        <?=
                        $this->Form->create(null, array(
                            "url" => array("controller" => "AddressBooks", "action" => "edit")
                        ));
                        ?>
                        <?= $this->Form->hidden("AddressBook.address_book_id", array("value" => $addressBookId)); ?>
                    <?php else: ?>
                        <?=
                        $this->Form->create(null, array(
                            "url" => array("controller" => "AddressBooks", "action" => "add")
                        ));
                        ?>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 top-20">
                                <fieldset>
                                    <legend>Adress Details</legend>
                                    <div class="row">
                                        <div class="col-md-6">
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

                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr />
                                            <div class="form-group">
                                                <?php
                                                $options = array("m" => "&nbsp;Mr.&nbsp;", "f" => "&nbsp;Ms.");
                                                $attributes = array('legend' => false, "required", "value" => "m");
                                                echo $this->Form->radio('AddressBook.entry_gender', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?=
                                                $this->Form->input("AddressBook.entry_firstname", array(
                                                    "type" => "text",
                                                    "class" => "form-control",
                                                    "placeholder" => "First Name",
                                                    "label" => "First Name",
                                                    "required"
                                                ));
                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?=
                                                $this->Form->input("AddressBook.entry_lastname", array(
                                                    "type" => "text",
                                                    "class" => "form-control",
                                                    "placeholder" => "Last Name",
                                                    "label" => "Lastname",
                                                    "required"
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?=
                                                $this->Form->input("AddressBook.entry_country_id", array(
                                                    "options" => $countries,
                                                    "class" => "form-control",
                                                    "empty" => "Select Your Country",
                                                    "label" => "Country",
                                                    "required"
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="from-group">
                                                <?=
                                                $this->Form->input("AddressBook.entry_street_address", array(
                                                    "type" => "text",
                                                    "class" => "form-control",
                                                    "placeholder" => "Street Address",
                                                    "label" => "Street Address",
                                                    "required"
                                                ));
                                                ?>
                                            </div>
                                            <div class="from-group">
                                                <?=
                                                $this->Form->input("AddressBook.entry_suburb", array(
                                                    "type" => "text",
                                                    "class" => "form-control",
                                                    "placeholder" => "Address Line 2",
                                                    "label" => "Address Line 2"
                                                ));
                                                ?>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?=
                                                $this->Form->input("AddressBook.entry_city", array(
                                                    "type" => "text",
                                                    "class" => "form-control",
                                                    "placeholder" => "City",
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
                                                    "placeholder" => "State/Province",
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
                                                    "placeholder" => "Post/ZIP Code",
                                                    "label" => "Post/Zip Code",
                                                    "required"
                                                ));
                                                ?>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <?= $this->Form->checkbox("Misc.primary"); ?>
                                                <label>Set as primary.</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 bottom-20">
                                            <button type="submit" class="btn btn-block btn-success">
                                                <?= $label = (isset($edit)) ? "Update" : "Submit"; ?>
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
