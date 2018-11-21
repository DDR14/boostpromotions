<p><b>Order Confirmation</b></p>
<p>
    <?= ucfirst($name) ?><br />
    Thanks for shopping with us today!<br />
    The following are the details of your order.
</p>

<div style="border:1px solid #c2c2c2; padding: 10px">
    <small><b>IMPORTANT NOTICE:</b><br />

        In an effort to provide prompt customer service please notify the individual responsible for your EMAIL SERVER that you need to have BOOSTPROMOTIONS.COM enabled as a safe sender. Our ability to contact you through email and your prompt replies are essential to avoid delays to your order. We will be sending emails with images for artwork approvals that can, and often are blocked by firewall and email server settings if not specifically set up to allow emails and images from BOOSTPROMOTIONS.COM.
        <br /><br />
        If you do not have control over the email address you provided or you believe that this may be an issue, please log into your account and change your email preference to a personal email for Boost Promotions correspondence. 
    </small>
</div>

<p>
    Order Number: <?= $orderId ?><br />
    Date Ordered: <?= date("m d, Y", strtotime($dateOrdered)) ?><br />
    <a href="" target="_blank">Click for a detailed invoice</a>
</p>

<p><b>Products</b></p>
<table style="border:1px solid #c2c2c2; width: 600px; text-align: left">
    <tr>
        <th style="border-bottom: 1px solid #c2c2c2">QTY.</th>
        <th style="border-bottom: 1px solid #c2c2c2">Model</th>
        <th style="border-bottom: 1px solid #c2c2c2">Unit</th>
        <th style="border-bottom: 1px solid #c2c2c2">Total</th>
    </tr>
    <?php foreach ($basketData as $order): ?>
        <tr>
            <td><?= $order["CustomersBasket"]["customers_basket_quantity"] ?></td>
            <td><?= $order["Product"]["products_model"] ?></td>
            <td><?= $this->Number->currency($order[0]["products_price"], '$'); ?></td>
            <td><?= $this->Number->currency($order[0]["products_price"] * $order["CustomersBasket"]["customers_basket_quantity"], '$'); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td></td>
        <td></td>
        <td colspan="2">
            <br />
            <table class="table text-right">
                <?php
                foreach ($orderSummary as $os):
                    if ($os[1] == 0) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td ><?= $os[0] ?>:</td>
                        <td><?= $this->Number->currency($os[1], "$"); ?></td>
                    </tr>	
                <?php endforeach; ?>
            </table> 
        </td>
    </tr>
</table>
<div style="border:1px solid #c2c2c2; padding: 10px">
    <small><b>Special Instructions or Order Comments:</b><br />
        <?= $orderInfo["OrderStatusHistory"]["comments"]; ?>
    </small>
</div>
<p><b>Address Information</b></p>
<table style="border:1px solid #c2c2c2; width:600px; text-align: left; padding: 5px">
    <tr>
        <td>
            <p>
                <b>Delivery Address</b><br />
                <?= strtoupper($orderInfo["Order"]["delivery_company"]) ?><br />
                <?= strtoupper($orderInfo["Order"]["delivery_street_address"]) ?><br />
                <?= strtoupper($orderInfo["Order"]["delivery_suburb"]) ?><br />
                <?= strtoupper($orderInfo["Order"]["delivery_city"]) ?>, <?= strtoupper($orderInfo["Order"]["delivery_state"]) ?>&nbsp; <?= strtoupper($orderInfo["Order"]["delivery_postcode"]) ?><br />
                <br />
                <b>Shipping Method</b><br />
                <?= $orderInfo["Order"]["shipping_method"] ?>
            </p>
        </td>
        <td>
            <p>
                <b>Billing Address</b><br />
                <?= strtoupper($orderInfo["Order"]["billing_company"]) ?><br />
                <?= strtoupper($orderInfo["Order"]["billing_street_address"]) ?><br />
                <?= strtoupper($orderInfo["Order"]["billing_suburb"]) ?><br />
                <?= strtoupper($orderInfo["Order"]["billing_city"]) ?>, <?= strtoupper($orderInfo["Order"]["billing_state"]) ?>&nbsp; <?= strtoupper($orderInfo["Order"]["billing_postcode"]) ?><br />
                <br />
                <b>Payment Method</b><br />
                <?= $orderInfo["Order"]["payment_method"] ?>
            </p>
        </td>
    </tr>
</table>