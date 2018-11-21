<style>
    #FAQMainContent h4 {font-style: italic;}
    #FAQMainContent div{padding:15px;}
    .accordion-toggle {cursor: pointer;}
    .accordion-content {display: none;}
    .accordion-content.default {display: block;}
</style>
<?php
$this->Html->scriptStart(array("block" => 'script', "inline" => FALSE));
?>
$(document).ready(function () {
$('#accordion').find('.accordion-toggle').click(function () {
//Expand or collapse this panel
$(this).next().next().slideToggle('fast');

//Hide the other panels
//$(".accordion-content").not($(this).next()).slideUp('fast');
});
});
<?php
$this->Html->scriptEnd();
?>

<div class="container" id="FAQMainContent">
    <h1 class="page-header">Frequently Asked Questions</h1>
    <div id="accordion" class="text-black">
        <h4>Before the Order</h4>
        <a class="accordion-toggle">Q. Are your tags made in China? </a><br/>
        <div class="accordion-content">
            Our tags are 100% made in the USA. Our production team creates and ships them from our Draper, UT location.
        </div>

        <a class="accordion-toggle">Q. What is the average turnaround time?</a><br/>
        <div class="accordion-content">
            We pride ourselves in our short turnaround time! For tag orders, it will only take us seven to ten business days to create and ship your order. Most orders are shipped via USPS Priority Mail which generally takes 2-3 business days (including Saturdays).
        </div>
        <a class="accordion-toggle">Q. Where do my customizations go?</a><br/>
        <div class="accordion-content">
            Our website does not build your custom tags. Your order request is sent to our graphics department who will personally create a proof of all of your tags and upload it to your account within 24-48 hours. If your customizations were not met, email your changes to our graphics team at: <a href="mailto:graphics@boostpromotions.com">graphics@boostpromotions.com</a>
        </div>
        <a class="accordion-toggle">Q. Do non-custom tag orders process faster?</a><br/>
        <div class="accordion-content">
            Our stock tags are not stored in house, every tag is produced on demand.
        </div>
        <a class="accordion-toggle">Q. Do customizations cost more?</a><br/>
        <div class="accordion-content">
            Customizations are free! Change your header, footer, image, or background all for free!
        </div>
        <a class="accordion-toggle">Q. How do I get my order faster?</a><br/>
        <div class="accordion-content">
            If you need your order by a specific date, sooner than the 7-10 business days, we can rush your order. It is an additioal 20% rush fee. If you would like to rush your order, give our office a call or send us an email and we will be happy to assist you!
        </div>
        <a class="accordion-toggle">Q Can I design my own tag?</a><br/>
        <div class="accordion-content">
            Yes you can design your own tag! The easiest way to place an order for your custom tags is to call or email us. If you need the tag template, we can send you a copy.
        </div>
        <a class="accordion-toggle">Q. Can I upload my own image onto a tag?</a><br/>
        <div class="accordion-content">
            You can put just about any picture you want on a tag. If you have an image you want to use please let us know. The higher the resolution, the better it will look. If you are uploading the image at the time of your order make sure it is JPEG or a PNG file. Otherwise, please email it to graphics@boostpromotions.com
        </div>
        <a class="accordion-toggle">Q. Can I call and place my order?</a><br/>
        <div class="accordion-content">
            We encourage our customers to place an order through the website, as it will contain all the information you'll need. however if you're finding the process difficult, feel free to reach our office (801) 987-8351. We'd be happy to walk through it with you!
        </div>
        <a class="accordion-toggle">Q. How do I customize a tag? </a><br/>
        <div class="accordion-content">
            Customization options will be available when you browse tags on our website such as changing the header, footer, background, or uploading your own image onto the tag.
        </div>

        <br/>
        <h4>Shipping &amp; Payment</h4>
        <a class="accordion-toggle">Q. Do you take Purchase Orders? </a><br/>
        <div class="accordion-content">
            We do take purchase orders, but only from schools or school districts.
        </div>
        <a class="accordion-toggle">Q. How do I pay for my order?</a><br/>
        <div class="accordion-content">
            When you checkout, you may choose to pay with credit card, check or purchase order (for schools and school districts).
            <br/>
            If you come across errors within our payment system, feel free to reach our office between 9am to 5pm (MST) at (801) 987-8351 and our customer service team would be happy to help resolve any issues!
        </div>
        <a class="accordion-toggle">Q. Can I rush my custom lanyard order? </a><br/>
        <div class="accordion-content">
            Our lanyards are not made inhouse, and therefore we are not able to dictate production time. We can assure you every lanyard is hand stitched! If your order included tags, we are happy to send those first and then send your lanyards separately when we receive them.
        </div>
        <a class="accordion-toggle">Q. How do I deny my proof?</a><br/>
        <div class="accordion-content">
            Before you deny your proof, there will be an area to explain why you denied and include your changes. If you have already denied your proof and did not include your changes, give our office a call at (801)987-8351 or email graphics directly:
            graphics@boostpromotions.com
        </div>
        <a class="accordion-toggle">Q. What format of an image do I upload to a tag order? </a><br/>
        <div class="accordion-content">
            If you are uploading an image to the order online, you will need to attach a jpeg or a png file. Our website does not support other formats. If you have another file type, you can email it directly to: graphics@boostpromotions.com
        </div>
        <a class="accordion-toggle">Q. Do I get the $0.11 price if I order 750 tags total? </a><br/>
        <div class="accordion-content">
            To get the $0.11 price for tags, you must order 750 of the exact same shape. The pricing is per shape, so the higher quantity per shape you buy, the cheaper they will be!
        </div>
        <a class="accordion-toggle">Q. How do I get the lowest price on lanyards? </a><br/>
        <div class="accordion-content">
            If you order 500 tags, then custom lanyards will be cheaper for you. If you place your order online, and the lanyard price does not reflect the discount, please send us an email or give us a call to let us know and we will be happy to make that adjustment for you!
        </div>
        <a class="accordion-toggle">Q. Can I order less than 25 tags?</a><br/>
        <div class="accordion-content">
            Our website does not support orders with less than 25 tags.
        </div>
        <a class="accordion-toggle">Q. Is there a minimum? </a><br/>
        <div class="accordion-content">
            If your order subtotal is less than $25.00, there will be a $10.00 small order fee
        </div>
        <a class="accordion-toggle">Q. Are chains included with the tags?  </a><br/>
        <div class="accordion-content">
            Chains are not included with your tags, but they are available at a very low cost!
        </div>
        <a class="accordion-toggle">Q. What is the best shipping option?</a><br/>
        <div class="accordion-content">
            We ship most of our orders through the USPS. It takes approximately 2-3 days, including Saturday!
        </div>
        <br/>
        <h4>After Order &amp; General Questions</h4>
        <a class="accordion-toggle">Q. Why do custom lanyards take longer? </a><br/>
        <div class="accordion-content">
            Our custom lanyards are made overseas, which re-quires additional production and shipping time.
        </div>
        <a class="accordion-toggle">Q. What address do I send checks to? </a><br/>
        <div class="accordion-content">
            Our mailing address is: 1192 Draper Pkwy #515 Draper UT 84020
        </div>
        <a class="accordion-toggle">Q. Are you open on Saturday?</a><br/>
        <div class="accordion-content">
            Our business hours are Monday-Friday 9-5 (MST). We are closed Saturday and Sunday.
        </div>
        <a class="accordion-toggle">Q.1 don't know my password. What do I do? </a><br/>
        <div class="accordion-content">
            Go to LOGIN. Then right under the information boxes, there is a button that says FORGOT PASSWORD, use this link to re-set your password.
        </div>
        <a class="accordion-toggle">Q. Can I combine two orders into one? </a><br/>
        <div class="accordion-content">
            Most often we can combine your orders! Send us an email or give us a call and we can get that taken care of for you.
        </div>

        <a class="accordion-toggle">Q. How can I add to my order?</a><br/>
        <div class="accordion-content">
            Contact our office via email or phone, and we'll be happy to assist you.
        </div>
        <a class="accordion-toggle">Q. How can I change my order?</a><br/>
        <div class="accordion-content">
            If you would like to change anything about your order, you can get in contact with one of our team members via by phone or email and we'll be happy to assist you in making the changes.
        </div>
        <a class="accordion-toggle">Q. Is my information kept confidential? </a><br/>
        <div class="accordion-content">
            Yes, any personal or payment information you provide is kept confidential. We want to give you a wonderful customer-service experience.
        </div>

    </div>
</div>