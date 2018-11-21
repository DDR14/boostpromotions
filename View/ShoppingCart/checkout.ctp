<section  ng-controller="CheckoutCtrl">
    <div class="container">
        <div class="page-header">
            <h1>Check Out</h1>
        </div>
        <?= $this->Flash->render("checkout"); ?>
        <div class="row">
            <?=
            $this->Form->create(null, array(
                "url" => array("controller" => "shoppingCart", "action" => "checkout"),
                "id" => "checkout-frm"
            ))
            ?>
            <!-- First pannel -->
            <div class="col-md-6">
                <fieldset>
                    <legend>Terms and Conditions</legend>
                    <label
                    <?= $this->Form->checkbox("Order.terms"); ?>
                    &nbsp; I have read and agreed to the terms and conditions of this website.
                    </label>
                </fieldset>

                <fieldset class="top-20">
                    <legend>Shipping Notice</legend>
                    <label>
                    <?= $this->Form->checkbox("Order.shipping_notice"); ?>
                    &nbsp;<b>PRODUCTION TIME:</b> Tag only orders: 7-10 business days, Orders including Custom Lanyards: 3-4 weeks. If you would like the tags shipped separately, please advise before completing your order so we can add the 2nd shipping fee.
                    </label>
                    <div class="alert alert-info top-20" style="border-radius: 0">
                        <b>FRIENDLY REMINDER</b><br />
                        <b>Note:</b> August - October is our busiest time of the year. Orders placed during these months may experience a longer than normal production time. If your order is time sensitive, please inquire about the current production time.
                    </div>

                </fieldset>

                <fieldset class="top-40">
                    <legend>Shipping Information</legend>

                    <div class="row">
                        <div class="col-md-7">
                            <?= strtoupper($shippingAddress["AddressBook"]["entry_company"]) ?><br />
                            <?= strtoupper($shippingAddress["AddressBook"]["entry_firstname"]) ?>
                            <?= strtoupper($shippingAddress["AddressBook"]["entry_lastname"]) ?><br />
                            <?= strtoupper($shippingAddress["AddressBook"]["entry_street_address"]) ?>
                            <div><?= strtoupper($shippingAddress["AddressBook"]["entry_suburb"]) ?></div>
                            <?= strtoupper($shippingAddress["AddressBook"]["entry_city"]) ?>,
                            <?= strtoupper($shippingAddress["AddressBook"]["entry_state"]) ?><br/>
                            <?= strtoupper($shippingAddress["Country"]["countries_name"]) ?>
                            <?= strtoupper($shippingAddress["AddressBook"]["entry_postcode"]) ?><br />
                        </div>

                        <div class="col-md-5 top-20">
                            <?=
                            $this->Html->link("Change Shipping Address", "/shoppingCart/changeAddress/shipping/"
                                    . $shippingAddress["AddressBook"]['address_book_id'], array("class" => "btn btn-block btn-primary"));
                            ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="top-40">
                    <legend>Shipping Method</legend>
                    <p><small>This is currently the only shipping method available to use on this order.</small></p>
                    <?php
                    $options = [];
                    if (isset($upsCharge) && $upsCharge > 0) {
                        $options += [
                            "UPS (Ground)" => "&nbsp;&nbsp;UPS Ground (" . $this->Number->currency($upsCharge, "$") . ")"
                        ];
                    }
                    if (isset($flatBoxPrice) && $flatBoxPrice < 400) {
                        $options += [
                            "USPS-Priority Mail" => "&nbsp;&nbsp;USPS-Priority Mail (" . $this->Number->currency($flatBoxPrice, "$") . ")"
                        ];
                    }
                    if (!$options) {
                        $options += [
                            "unspecified" => "&nbsp;&nbsp;You will be contacted for shipping information"
                        ];
                    }
                    
                    $attributes = [
                        "separator" => "<br />",
                        "required",
                        "legend" => false
                    ];
                    ?>

                    <?= $this->Form->radio('Order.shipping_method', $options, $attributes); ?>
                </fieldset>

                <fieldset class="top-40">
                    <legend>Special Instructions or Order Comments</legend>
                    <?=
                    $this->Form->input("OrderStatusHistory.comments", array(
                        "type" => "textarea",
                        "class" => "form-control"
                    ));
                    ?>
                </fieldset>

            </div>

            <!-- Second pannel -->
            <div class="col-md-6">
                <div class="well">
                    <fieldset>
                        <legend>Billing Address</legend>
                        <div class="row">
                            <div class="col-md-7">
                                <?= strtoupper($billingAddress["AddressBook"]["entry_company"]) ?><br />
                                <?= strtoupper($billingAddress["AddressBook"]["entry_firstname"]) ?>
                                <?= strtoupper($billingAddress["AddressBook"]["entry_lastname"]) ?><br />
                                <?= strtoupper($billingAddress["AddressBook"]["entry_street_address"]) ?>,
                                <div><?= strtoupper($billingAddress["AddressBook"]["entry_suburb"]) ?></div>
                                <?= strtoupper($billingAddress["AddressBook"]["entry_city"]) ?>,
                                <?= strtoupper($billingAddress["AddressBook"]["entry_state"]) ?><br/>
                                <?= strtoupper($billingAddress["Country"]["countries_name"]) ?>
                                <?= strtoupper($billingAddress["AddressBook"]["entry_postcode"]) ?><br />
                            </div>

                            <div class="col-md-5">
                                <?=
                                $this->Html->link("Change Billing Address", "/shoppingCart/changeAddress/billing/"
                                        . $billingAddress["AddressBook"]['address_book_id'], array("class" => "btn btn-block btn-primary top-20"));
                                ?>
                            </div>
                        </div>
                    </fieldset>

                    <?php if (AuthComponent::user("customers_authorization") != 4): ?>
                        <fieldset class="top-20">
                            <legend>
                                Do you have a
                                <a ng-hide="showCoupon" ng-click="showCouponCodeFrm()" href="">coupon code?</a>
                            </legend>

                            <legend ng-show="showCoupon">
                                <b>Your Coupon: <span class="text-white">{{ couponCode}}</span></b>
                                <button class="btn btn-primary btn-xs">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </legend>
                        </fieldset>
                    <?php else: ?>
                        <p class="text-warning">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> &nbsp;
                            Your account is banned you cannot use any referal cards. Please contact us for more details.
                        </p>
                    <?php endif; ?>

                    <div class="top-20"></div>
                    <?php
                    $poptions = array(2 => "&nbsp;Pay after approved artwork <small class='text-dark-gray'>(by credit card or check)</small>", 1 => "&nbsp;Purchase Order<small class='text-dark-gray'> (A copy of the PO must be received before production can commence)</small>");
                    echo $this->Form->radio("Order.payment_method", $poptions, ["separator" => "<br />", "required"]);
                    ?>

                    <fieldset class="top-20">
                        <legend>Text Notification</legend>
                        <span>We now notify customers through email and PHONE TEXT to insure that artwork approval requests and other important notices are being received to keep the order moving forward.</span>

                        <div class="row top-20">
                            <div class="col-md-6">
                                <?php
                                $cellphone = explode('@', $customers_mobile);


                                echo $this->Form->input("Order.customers_mobile_no", array(
                                    "value" => (is_array($cellphone)) ? $cellphone[0] : '',
                                    "type" => "text",
                                    "class" => "form-control",
                                    "label" => false,
                                    "placeholder" => "Mobile Number",
                                    "required"
                                ))
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo $this->Form->input("Order.customers_mobile_carrier", array(
                                    'default' => (isset($cellphone[1])) ? '@' . $cellphone[1] : 0,
                                    "options" => $mobileCarriers,
                                    "empty" => "Select Your Mobile Carrier",
                                    "class" => "form-control",
                                    "label" => false,
                                    "required"
                                ))
                                ?>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <br/>
                        <button type="submit" class="btn btn-warning top-100">
                            PROCEED TO ORDER CONFIRMATION
                        </button>
                    </div>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
    </div>
    <div class="modal fade center-modal" id="coupon-code-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p class="text-center text-white"><b>Enter Your Coupon Code.</b></p>
                    <hr />

                    <div class="form-group">
                        <input type="text" ng-model="coupon" class="form-control" placeholder="Enter Your Coupon Code Here." />
                    </div>

                    <button type="button" class="btn btn-primary btn-block" ng-click="enterCouponCode(coupon)">DONE</button>
                </div>
            </div>
        </div>
    </div>
</section>
