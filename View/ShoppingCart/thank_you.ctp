<section class="top-40">
    <div class="container">
        <div class="page-header">
            <h1>THANK YOU! WE APPRECIATE YOUR BUSINESS!</h1>
        </div>
        <h5><b>Your order confirmation number is #<?= $orderConfirmationNo ?></b></h5>

        <?=
        $this->Html->link("PAYMENTS PAGE", '/payments/index/' . $orderConfirmationNo, [
            "class" => "btn btn-md btn-success bottom-20 top-20"
        ]);
        ?>

        <hr />

    </div>
</section>
