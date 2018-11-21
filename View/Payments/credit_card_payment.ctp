<?= $this->assign("title", "Credit Card Payment"); ?>

<section>
    <div class="container">
         <ul class="breadcrumb">
            <li><?= $this->Html->link('Previous Orders', '/orders'); ?></li>
            <li><?= $this->Html->link('Payments: Order #' . $orderData["Order"]["orders_id"], '/payments/index/' . $orderData["Order"]["orders_id"]); ?></li>
            <li class="active">Credit Card Payment</li>
        </ul>
        <div class="page-header">            
            <h1>Pay with Credit Card (Payment Gateway)</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $this->Flash->render("paymentError"); ?>

                <?=
                $this->Form->create(null, array(
                    "url" => array("controller" => "payments", "action" => "creditCardPayment", $orderData["Order"]["orders_id"]),
                    'onsubmit' => 'return confirm("Confirm Payment?")'
                ));
                ?>

                <fieldset>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount to pay (USD)</label>
                                    <?=
                                    $this->Form->input("Payment.cc_amount", array(
                                        "type" => "number",
                                        "value" => $balance,
                                        "class" => "form-control",
                                        "min" => 1,
                                        "step" => "any",
                                        "label" => false,
                                        "required"
                                    ));
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Card Number <small class="text-info">(the 16 digits on front of your card)</small></label>
                                    <?=
                                    $this->Form->input("Payment.cc_number", array(
                                        "type" => "text",
                                        "class" => "form-control",
                                        "label" => false,
                                        "required"
                                    ))
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label>
                                        CVV2/CVC2 <small class="text-info">(the last 3 digits displayed on back of your card)</small>
                                    </label>
                                    <?=
                                    $this->Form->input("Payment.cc_cvc", array(
                                        "type" => "text",
                                        "label" => false,
                                        "class" => "form-control",
                                        "required"
                                    ));
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Card Expiration date <small class="text-info">(mmyy)</small></label>
                                    <?=
                                    $this->Form->input("Payment.cc_exp_date", array(
                                        "type" => "date",
                                        "dateFormat" => "MY",
                                        "label" => false,
                                        'maxYear' => date('Y') + 100,
                                        "required"
                                    ))
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 page-header">
                                <h1>Billing Address</h1>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company</label>
                                    <?=
                                    $this->Form->input("Payment.billing_company", array(
                                        "type" => "text",
                                        "class" => "form-control",
                                        "label" => false,
                                        "required"
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Firstname</label>
                                <?=
                                $this->Form->input("Payment.billing_firstname", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false,
                                    "required"
                                ));
                                ?>
                            </div>
                            <div class="col-md-6">
                                <label>Lastname</label>
                                <?=
                                $this->Form->input("Payment.billing_lastname", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false,
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="col-md-6 top-20">
                                <label>Country</label>
                                <?=
                                $this->Form->input("Payment.billing_country", array(
                                    "options" => $countries2,
                                    "empty" => "Select Your Country",
                                    "class" => "form-control",
                                    "label" => false,
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="col-md-12">
                                <label>Street Address</label>
                                <?=
                                $this->Form->input("Payment.billing_street_address", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false
                                ));
                                ?>

                                <label>Address Line 2</label>
                                <?=
                                $this->Form->input("Payment.billing_suburb", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false
                                ));
                                ?>
                            </div>

                            <div class="col-md-4">
                                <label>City</label>
                                <?=
                                $this->Form->input("Payment.billing_city", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false,
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="col-md-4">
                                <label>State/Province</label>
                                <?=
                                $this->Form->input("Payment.billing_state", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false,
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="col-md-4">
                                <label>Post Code</label>
                                <?=
                                $this->Form->input("Payment.billing_postcode", array(
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false,
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="col-md-4 top-20">
                                <button type="submit" class="btn  btn-block btn-success">
                                    Secure Payment
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <fieldset>
                            <legend>Order Details</legend>

                            <div class="table-responsive">
                                <table class="table">
                                    <?php foreach ($orderData["OrdersTotal"] as $total): ?>
                                        <tr>
                                            <?php if ($total["title"] == "Discount Coupon: " . $orderData["Order"]["coupon_code"] . " :"): ?>
                                                <td><?= $this->Html->link($total["title"], "coupons/help/" . $orderData["Order"]["coupon_code"], array("class" => "single-link")); ?></td>
                                            <?php else: ?>
                                                <td><?= ucfirst($total["title"]) ?></td>
                                            <?php endif; ?>

                                            <td><?= $total["text"]; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td>Amount Paid:</td>
                                        <td><?= $this->Number->currency($amountPaid, "USD"); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Balance Due:</td>
                                        <td><?= $this->Number->currency($balance, "USD"); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                </fieldset>

                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>