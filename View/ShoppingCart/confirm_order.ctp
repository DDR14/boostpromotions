<section ng-controller="CheckoutCtrl">    
    <div class="container">
        <?= $this->Flash->render(); ?>
        <div class="page-header">
            <h1>Order Confirmation</h1>
        </div>
        <div class="row">
            <div class="col-md-6">
                <fieldset>
                    <legend>Billing/Payment information</legend>

                    <div class="row">
                        <div class="col-md-7">
                            <p class="text-white"><b>Billing Address</b></p>
                            <p>
                                <?= strtoupper($sessionData["Order"]["billing_company"]) ?><br />
                                <?= strtoupper($sessionData["Order"]["billing_name"]) ?><br />
                                <?= strtoupper($sessionData["Order"]["billing_street_address"]) ?>,
                                <?= strtoupper($sessionData["Order"]["billing_suburb"]) ?>,
                                <?= strtoupper($sessionData["Order"]["billing_city"]) ?>,
                                <?= strtoupper($sessionData["Order"]["billing_state"]) ?>,
                                <?= strtoupper($sessionData["Order"]["billing_postcode"]) ?>,<br />
                                <?= strtoupper($sessionData["Order"]["billing_country"]) ?>
                            </p>
                        </div>

                    </div>

                    <p class="text-white"><b>Payment Method</b></p>
                    <p><?= strtoupper($sessionData["Order"]["payment_method"]) ?></p>
                </fieldset>

                <fieldset class="top-20">
                    <legend>Delivery/Shipping information</legend>
                    <div class="row">
                        <div class="col-md-7">
                            <p class="text-white"><b>Shipping Address</b></p>
                            <p>
                                <?= strtoupper($sessionData["Order"]["delivery_company"]) ?><br />
                                <?= strtoupper($sessionData["Order"]["delivery_name"]) ?><br />
                                <?= strtoupper($sessionData["Order"]["delivery_street_address"]) ?>,
                                <?= strtoupper($sessionData["Order"]["delivery_suburb"]) ?>,
                                <?= strtoupper($sessionData["Order"]["delivery_city"]) ?>,
                                <?= strtoupper($sessionData["Order"]["delivery_state"]) ?>,
                                <?= strtoupper($sessionData["Order"]["delivery_postcode"]) ?>,<br />
                                <?= strtoupper($sessionData["Order"]["delivery_country"]) ?>
                            </p>
                        </div>

                    </div>

                    <p class="text-white"><b>Shipping Method</b></p>
                    <p><?= strtoupper($sessionData["Order"]["shipping_method"]) ?></p>
                </fieldset>
            </div>

            <div class="col-md-6 well">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <legend><h4>Special Instructions or Order Comments</h4></legend>

                            <?php if (!empty($sessionData["OrderStatusHistory"]["comments"])): ?>
                                <?= strtoupper($sessionData["OrderStatusHistory"]["comments"]) ?>
                            <?php else: ?>
                                NONE
                            <?php endif; ?>
                        </fieldset>
                    </div>

                    <div class="col-md-12 top-20">
                        <fieldset>
                            <h4>Shopping Cart Contents</h4>

                            <div class="">
                                <table class="table table-hover">
                                    <tr>
                                        <th>QTY</th>
                                        <th>ITEM NAME</th>
                                        <th><span class="pull-right">TOTAL</span></th>
                                    </tr>

                                    <?php foreach ($basketData as $order): ?>
                                        <tr>
                                            <td class="text-left"><?= $order["CustomersBasket"]["customers_basket_quantity"] ?>pcs.</td>
                                            <td>
                                                <?= strtoupper($order["Product"]["products_model"]) ?>
                                                <button class="btn btn-primary btn-sm pull-right" ng-click="orderDetails(<?= $order['CustomersBasket']['customers_basket_id'] ?>)">
                                                    Details
                                                </button>
                                            </td>
                                            <td class="text-right">                                                
                                                <?= $this->Number->currency($order["CustomersBasket"]["customers_basket_quantity"] * $order[0]["products_price"], '$'); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-6">
                                        <table class="table text-right">
                                            <?php
                                            foreach ($orderSummary as $os):

                                                if ($os[1] == 0 && $os[0] != 'unspecified') {
                                                    continue;
                                                }
                                                ?>
                                                <tr>
                                                    <?php if ($os[0] == 'unspecified'): ?>
                                                        <td>Shipping method:</td>
                                                        <td>Unspecified</td>
                                                    <?php else: ?>
                                                        <td ><?= $os[0] ?>:</td>
                                                        <td><?= $this->Number->currency($os[1], "$"); ?></td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <?=
                            $this->Form->create(null, array(
                                "url" => array("controller" => "shoppingCart", "action" => "confirmOrder")
                            ))
                            ?>
                            <div class="text-right">
                                <button type="submit" class="btn btn-warning">
                                    Confirm Order
                                </button>
                            </div>
                            <?= $this->Form->end(); ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="more-details">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">ORDER DETAILS</h4>
                </div>

                <div class="modal-body">
                    <h4 ng-show="loadingData" class="text-center">LOADING...</h4>

                    <div class="row">
                        <div class="col-md-12" ng-hide="loadingData">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="https://boostpromotions.com/images/{{details.Product.products_image}}" class="img-responsive thumbnail">
                                </div>

                                <div class="col-md-8">
                                    <div class="col-md-12">
                                        <h4>{{ details.Product.products_model}}</h4>
                                        <hr class="no-margin" />

                                        <div ng-show="productType == 'TEACHERS KITS'">
                                            <p class="top-10 text-white"><b>TEACHER NAME</b><br /></p>
                                            <p>{{ details.CustomersBasket.footer | uppercase }}</p>
                                        </div>

                                        <div ng-show="productType == 'MODIFIED' || productType == 'CUSTOM'">
                                            <p class="top-10">
                                                <b class="text-white" ng-show="productType == 'MODIFIED'">MODIFICATIONS</b>
                                                <b class="text-white" ng-show="productType == 'CUSTOM'">CUSTOMIZATIONS</b>
                                                <br />
                                                <span ng-hide="noModifications">
                                                    TITLE: {{ title}}<br />
                                                    BACKGROUND: {{ background}}<br />
                                                    FOOTER: {{ footer}} <br />
                                                    UPLOADED IMAGE: {{ upload}}<br /><br />
                                                </span>
                                                <span class="text-primary" ng-show="noModifications && !tagDesignComment">
                                                    NO MODIFICATIONS
                                                </span>

                                                <span ng-show="tagDesignComment">
                                                    <span ng-show="productType == 'CUSTOM'">
                                                        WHAT YOU WOULD LIKE THE TAG TO LOOK LIKE?
                                                    </span>
                                                    <span ng-show="productType == 'MODIFIED'">
                                                        WHAT YOU WOULD LIKE THE IMAGE TO LOOK LIKE?
                                                    </span>

                                                    <br />
                                                    <span>{{ designComment}}</span>
                                                </span>
                                            </p>
                                        </div>

                                        <div class="top-20" ng-show="productType == 'MODIFIED' || productType == 'TEACHERS KITS' || productType == 'STOCK'">
                                            <p>
                                                <b class="text-white">SELECTED MODELS</b><br />
                                            </p>
                                            <hr class="no-margin" />
                                            <div class="row">
                                                <div class="col-md-3" ng-repeat="model in selectedModels">

                                                    <img ng-show="productType == 'TEACHERS KITS'" ng-src="https://boostpromotions.com/images/{{ model.Design.products_image}}" class=" img-height-80 top-5" />

                                                    <img ng-hide="productType == 'TEACHERS KITS'" ng-src="https://boostpromotions.com/images/designs/Resize/{{ model.Design.products_model | getImgDir }}/{{ model.Design.products_image}}" class=" img-100 top-5">

                                                    <p class="text-center top-5">
                                                        <span class="text-white">{{ model.Design.products_model | removeTrailing01 }}</span><br />
                                                        <span>{{ model.Design.orderQty}} pcs.</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
</section>
