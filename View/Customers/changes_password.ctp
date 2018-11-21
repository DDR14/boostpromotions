<?= $this->assign("title", "My Password"); ?>

<section class="top-40">
    <div class="container">
        <?= $this->element("sidebars", ["title" => "Change Password", "active" => "password"]); ?>
        <?= $this->fetch("myAccountSidebar") ?>

        <div class="col-md-9">
            <?=
            $this->Form->create(null, array(
                "url" => array("controller" => "customers", "action" => "changePassword")
            ));
            ?>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#password" aria-controls="home" role="tab" data-toggle="tab">Change Password</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="password">
                    <div class="row">

                        <div class="col-md-12 top-20">
                            <div class="col-md-12">
                                <?= $this->Flash->render("incorrectPass"); ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-12 top-20">

                                <fieldset>
                                    <legend>My Password</legend>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?=
                                                $this->Form->input("Customer.customers_current_password", array(
                                                    "type" => "password",
                                                    "class" => "form-control",
                                                    "placeholder" => "Current Password",
                                                    "label" => "Current Password",
                                                    "required"
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?=
                                                $this->Form->input("Customer.customers_password", array(
                                                    "type" => "password",
                                                    "class" => "form-control",
                                                    "placeholder" => "New Password",
                                                    "label" => "New Password",
                                                    "required"
                                                ));
                                                ?>

                                                <?=
                                                $this->Form->input("Customer.customers_confirm_password", array(
                                                    "type" => "password",
                                                    "class" => "form-control",
                                                    "placeholder" => "Confirm Password",
                                                    "label" => "Confirm Password",
                                                    "required"
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">
                                                    Change Password
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
    </div>
</section>
