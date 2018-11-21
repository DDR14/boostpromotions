<?= $this->assign("title", "Boost Promotions - Account Login"); ?>

<div class="container">    
    <div class="col-md-4 col-md-offset-4">
        <div class="page-header text-center">
            <h1>Login</h1>
            <p >Need an account? <?= $this->Html->link("Sign up", "register") ?></p>
        </div>

        <?=
        $this->Form->create(null, array(
            "Url" => array("controller" => "customers", "action" => "login")
        ));
        ?>

        <?= $this->Flash->render("loginFailed"); ?>

        <div class="form-group">
            <?=
            $this->Form->input("Customer.customers_email_address", array(
                "type" => "email",
                "class" => "form-control",
                "placeholder" => "Email address",
                "label" => "Email address",
                "required"
            ));
            ?>
        </div>
        <div class="form-group">
            <?=
            $this->Form->input("Customer.customers_password", array(
                "type" => "password",
                "class" => "form-control",
                "placeholder" => "Password",
                "label" => "Password",
                "required"
            ));
            ?>

        </div>

        <div class="form-group">
            <label>
                <?= $this->Form->checkbox("Misc.remember_me"); ?>&nbsp; Keep me logged in.
            </label>
        </div>
        <div class="form-group">
            <button class="btn btn-block btn-success">
                Login
            </button>

            <br />
            <?= $this->Html->link("Forgot password?", "/customers/forgotPassword"); ?>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>