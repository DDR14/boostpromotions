<?= $this->assign("title", "Payment Method"); ?>

<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('Previous Orders', '/orders'); ?></li>
            <li class="active">Payments: Order #<?= $orderId; ?></li>
        </ul>
        <div class="row">
            <?php if ($balance > 0): ?>
                <div class="page-header">
                    <h1>SELECT YOUR PAYMENT METHOD</h1>
                </div>

                <h2 class="text-warning bottom-20">Amount to pay: <?= $this->Number->currency($balance, "USD"); ?></h2>

                <div class="col-md-4">
                    <div class="well bordered">
                        <fieldset>
                            <legend>Credit Card Payment</legend>
                            <p><b>For your convenience, we accept Visa, MasterCard, American Express and Discover.</b></p>
                            <?=
                            $this->Html->link("Go", "/payments/creditCardPayment/" . $orderId, array(
                                "class" => "btn btn-primary"
                            ));
                            ?>
                        </fieldset>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="well bordered">
                        <fieldset>
                            <legend>Check</legend>
                            <p><b>Upload a scanned copy of check as payment. No need to mail the check.</b></p>
                            <?=
                            $this->Html->link("Go", "/payments/checkPayment/" . $orderId, array(
                                "class" => "btn btn-primary"
                            ));
                            ?>
                        </fieldset>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="well bordered">
                        <fieldset>
                            <legend>Purchase Order</legend>
                            <p><b>Upload a Purchase Order.A copy of your PO must be received before production can proceed.</b></p>
                            <?=
                            $this->Html->link("Go", "/payments/uploadPO/" . $orderId, array(
                                "class" => "btn btn-primary"
                            ));
                            ?>
                        </fieldset>
                    </div>
                </div>
            <?php else: ?>
                <h3>You have already paid for this order.</h3>
                <?=
                $this->Html->link("Go Back", "/orders/", array(
                    "class" => "btn btn-primary"
                ));
                ?>
            <?php endif; ?>
        </div>
    </div>
</section>