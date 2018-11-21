<?= $this->assign("title", "Forgot Password"); ?>

<div class="container">    
    <div class="col-md-4 col-md-offset-4">
        <div class="page-header text-center">
            <h1>Password Forgotten</h1>
            <p>Enter your email address and we will send you a password reset link.</p>
        </div>

        <?=
        $this->Form->create();
        ?>

        <?= $this->Flash->render(); ?>

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
            <button class="btn btn-block btn-success">
                Send
            </button>
        </div>

        <?= $this->Form->end(); ?>
    </div>
</div>