<?= $this->assign("title", "My Personal Address Book") ?>
<section class="top-40">
    <div class="container">
        <?= $this->element("sidebars", ["title" => "My Address Book", "active" => "addressbook"]); ?>
        <?= $this->fetch("myAccountSidebar") ?>

        <div class="col-md-9">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#address-book" aria-controls="home" role="tab" data-toggle="tab">Address Book</a>
                </li>
                <li role="presentation">
                    <?= $this->Html->link("<i class='fa fa-plus'></i> &nbsp;Add New Entry", "/addressBooks/add", array("escape" => false)); ?>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="address-book">
                    <div class="row">
                        <div class="col-md-12"> 
                            <div class="col-md-12 top-20">

                                <?= $this->Flash->render("addressBookAdded"); ?>
                                <?= $this->Flash->render("maxEntries"); ?>
                                <?= $this->Flash->render("addressBookUpdated"); ?>
                                <?= $this->Flash->render("addressBookDeleted"); ?>
                                <?= $this->Flash->render("duplicateEntry"); ?>

                                <fieldset>
                                    <legend>Address Book Entries</legend>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <p>Note: A maximum of 5 address book entries allowed</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php foreach ($addressBooks as $address): ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <?= ucfirst($address["AddressBook"]["entry_company"]); ?>
                                                    </li>
                                                    <li>
                                                        <?= ucfirst($address["AddressBook"]["entry_firstname"]); ?>&nbsp;<?= ucfirst($address["AddressBook"]["entry_lastname"]); ?>
                                                    </li>
                                                    <li>
                                                        <?= ucfirst($address["AddressBook"]["entry_street_address"]); ?>
                                                    </li>
                                                    <li>
                                                        <?= ucfirst($address["AddressBook"]["entry_suburb"]); ?>
                                                    </li>
                                                    <li>
                                                        <?= ucfirst($address["AddressBook"]["entry_city"]); ?>, <?= ucfirst($address["AddressBook"]["entry_state"]); ?>
                                                        <?= ucfirst($address["AddressBook"]["entry_postcode"]); ?>
                                                    </li>
                                                    <li>
                                                        <?= ucfirst($address["Country"]["countries_name"]); ?>
                                                    </li>
                                                </ul>
                                                <?=
                                                $this->Html->link("<i class='fa fa-pencil-square-o'></i> &nbsp;Edit", "/addressBooks/edit/" . $address["AddressBook"]["address_book_id"], array(
                                                    "class" => "btn btn-primary btn-sm", "escape" => false
                                                ));
                                                ?>

                                                <?=
                                                $this->Form->postLink("<i class='fa fa-trash-o'></i> &nbsp;Delete", array(
                                                    "action" => "delete",
                                                    $address["AddressBook"]["address_book_id"]
                                                        ), array(
                                                    "class" => "btn btn-danger btn-sm", "escape" => false,
                                                    "confirm" => "Are you sure you want to delete this address book?"
                                                        )
                                                );
                                                ?>
                                            </div>

                                            <div class="col-md-6">                                               
                                                <?php if ($address['AddressBook']['address_book_id'] == $defaultAddressId): ?>
                                                    <div class="arrow_box">
                                                        <p class="text-warning">
                                                            <b>Note:</b><br />
                                                            This address is used as the pre-selected shipping and billing address for orders placed on this store. <br />This address is also used as the base for product and service tax calculations.
                                                        </p>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <hr/>
                                    <?php endforeach; ?>

                                    <?php if (empty($addressBooks)): ?>
                                        <p>No Address Books Entries.</p>
                                    <?php endif; ?>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>