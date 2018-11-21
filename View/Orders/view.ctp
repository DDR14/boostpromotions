<?= $this->assign("title", "Order information"); ?>

<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('Previous Orders', '/orders'); ?></li>
            <li class="active">View Order #<?= $orderData["Order"]["orders_id"]; ?></li>
        </ul>
        <div class="row">
            <div class="col-md-6">
                <h1>Order Information - Order #<?= $orderData["Order"]["orders_id"] ?></h1>

                <div class="row top-20">
                    <div class="col-md-6">
                        <p>
                            <b class="text-white">Delivery Address</b><br />
                            <?= strtoupper($orderData["Order"]["delivery_company"]) ?><br />
                            <?= ucfirst($orderData["Order"]["delivery_name"]) ?><br />
                            <?= ucfirst($orderData["Order"]["delivery_street_address"]) ?>,<br />
                            <?= ucfirst($orderData["Order"]["delivery_city"]) ?>,
                            <?= ucfirst($orderData["Order"]["delivery_state"]) ?>,
                            <?= ucfirst($orderData["Order"]["delivery_postcode"]) ?>,<br />
                            <?= ucfirst($orderData["Order"]["delivery_country"]) ?>
                        </p>
                        <p class="top-20">
                            <b class="text-white">Shipping Method</b><br />
                            <?= ucfirst($orderData["Order"]["shipping_method"]) ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <b class="text-white">Billing Address</b><br />
                            <?= strtoupper($orderData["Order"]["billing_company"]) ?><br />
                            <?= ucfirst($orderData["Order"]["billing_name"]) ?><br />
                            <?= ucfirst($orderData["Order"]["billing_street_address"]) ?>,<br />
                            <?= ucfirst($orderData["Order"]["billing_city"]) ?>,
                            <?= ucfirst($orderData["Order"]["billing_state"]) ?>,
                            <?= ucfirst($orderData["Order"]["billing_postcode"]) ?>,<br />
                            <?= ucfirst($orderData["Order"]["billing_country"]) ?>
                        </p>
                        <p class="top-20">
                            <b class="text-white">Payment Method</b><br />
                            <?= ucfirst($orderData["Order"]["payment_method"]) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-8 col-md-offset-4 padding-5">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>
                                <strong>Status:</strong> <?= $orderData["OrdersStatus"]["orders_status_name"] ?>
                            </p>
                            <p>
                                <b>Order Date:</b>
                                &nbsp;<?= date("l d F, Y", strtotime($orderData["Order"]["date_purchased"])) ?>
                            </p>

                            <p>
                                <?php if (!empty($orderData["Order"]["tracking"])): ?>
                                    <b>Tracking #:</b>&nbsp; <?= $orderData["Order"]["tracking"] ?>
                                <?php else: ?>
                                    <span class="text-warning">Your tracking number will be displayed here when your order is shipped.</span>
                                <?php endif; ?>
                            </p>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row top-20">

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php
                        $tk = array("881", "878", "877", "880", "879", "876");
                        $tk_extra = array("882", "883", "884", "885", "886", "887", "888");
                        ?>

                        <?php
                        foreach ($orderData["OrdersProduct"] as $item):
                            $tagType = substr($item['Product']['products_model'], 0, 6);
                            ?>
                            <div class="thumbnail" style="max-width:150px;float:left; margin-right: 20px" >
                                <img style="max-height: 150px; " class="img-responsive" src="https://boostpromotions.com/images/<?= $item['Product']['products_image'] ?>" />
                                <?= $tagType == 'STOCK-' ? $this->Html->link("RE-ORDER", "/products/order/re-order/" . $item["CustomCo"]["id"], ["class" => "btn btn-success top-5 btn-sm"]) : ''; ?>
                            </div>
                            <?php foreach ($item['CustomCo']['Proof'] as $sproof): ?>
                                <div class="thumbnail" style="max-width:150px; float:left; margin-right: 20px" >
                                    PROOF:
                                    <img style="max-height: 140px;" class="img-responsive" src="https://boostpromotions.com/2dodash/<?= $sproof['location'] ?>" />
                                    <?= $tagType == 'STOCK-' ? '' : $this->Html->link("RE-ORDER PROOF", "/products/order/re-order/" . $item["CustomCo"]["id"], ["class" => "btn btn-success top-5 btn-sm"]); ?>
                                </div>
                            <?php endforeach; ?>

                            <h4 class="pull-right text-warning">
                                <b><?= $this->Number->currency($item["products_price"] * $item['products_quantity'], '$'); ?></b> 
                            </h4>
                            <p>
                                <b> <span class="text-warning">
                                        <?= $item["Product"]["ProductsDescription"]["products_name"] . ' / ' . $item["Product"]['products_model']; ?></span></b>
                                <br />
                                <?php if ($item["CustomCo"]["customs"]): ?>
                                    <?php if (in_array($item['Product']['products_id'], $tk)): ?>
                                        <br/><span><?php echo 'Teacher Name: ' . $item['CustomCo']['footer']; ?></span>
                                        <br/><span style="width:500px"><?php echo 'Model List: ' . $item['CustomCo']['customs']; ?></span>
                                    <?php elseif (in_array($item['Product']['products_id'], $tk_extra)): ?>
                                        <br/><span><?php echo $item['CustomCo']['customs']; ?></span>
                                    <?php else: ?>

                                        <?php
                                        if ($item['CustomCo']['title'] == '' &&
                                                $item['CustomCo']['footer'] == '' &&
                                                $item['CustomCo']['background'] == '' &&
                                                // $item['CustomCo']['customs'] == '' &&
                                                $item['CustomCo']['upload'] == ''
                                        ):
                                            ?>

                                            <br />
                                            <span class="text-info">No Customizations</span>      
                                            <br/><span class="top-10">Image: <?php
                                                echo $item['CustomCo']['customs'];
                                                if ($item['CustomCo']['upload'] != '') {
                                                    echo "<a target='_blank' href='images/uploads/{$item['CustomCo']['upload']}'>{$item['CustomCo']['upload']}</a>";
                                                }
                                                ?>
                                            </span>

                                        <?php else: ?>
                                            <b class="text-warning">Customizations</b>
                                            <br/><span>Title: <?php echo $item['CustomCo']['title']; ?></span>
                                            <br/><span>Footer: <?php echo $item['CustomCo']['footer']; ?></span>
                                            <br/><span>Background: <?php echo $item['CustomCo']['background']; ?></span>
                                            <br/><span>Image: <?php
                                                echo $item['CustomCo']['customs'];
                                                if ($item['CustomCo']['upload'] != '') {
                                                    echo "<a target='_blank' href='https://boostpromotions.com/images/uploads/{$item['CustomCo']['upload']}'>{$item['CustomCo']['upload']}</a>";
                                                }
                                                ?>
                                            </span>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if (in_array($item['Product']['products_id'], $tk_extra)): ?>
                                        <br/><span><?php echo $item['CustomCo']['customs']; ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <br /><span>Total Qty: <?= $item["products_quantity"] ?></span>
                            </p>  
                            <div class="clearfix" ></div>
                            <hr/>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table">
                        <?php foreach ($orderData["OrdersTotal"] as $total): ?>
                            <tr>
                                <td>
                                    <?= ucfirst($total["title"]); ?>
                                </td>
                                <td>
                                    <?= $this->Number->currency($total["value"], "USD"); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <?=
                $this->Html->link("Click here to view invoice", "https://www.boostpromotions.com/2dodash/invoice/index.php?orderid=" . $orderData["Order"]["orders_id"] . "&invoice=1", array(
                    "target" => "_blank", "class" => "btn btn-info"
                ));
                ?><br/> <br/>

                <?=
                $this->Html->link("Click here to view quote", "https://www.boostpromotions.com/2dodash/invoice/index.php?orderid=" . $orderData["Order"]["orders_id"], array(
                    "target" => "_blank", "class" => "btn btn-default"
                ));
                ?>
            </div>
        </div>
        <div class="page-header">
            <h2>Order Status History</h2>
        </div>
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>DATE</th>
                    <th>ORDER STATUS</th>
                    <th>COMMENTS</th>
                </tr>
                <?php foreach ($orderData['OrdersStatusHistory'] as $history): ?>
                    <tr>
                        <td>
                            <?= $history['date_added'] ?>
                        </td>
                        <td>
                            <?= $history['OrdersStatus']['orders_status_name'] ?>
                        </td>
                        <td>
                            <?= $history['comments'] ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
        </div>
    </div>
</section>