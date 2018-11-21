<?php $this->start("mainFooter"); ?>
<footer>
    <div class="container">
        <div class="col-md-4">
            <p>CUSTOMER SERVICE</p>
            <hr />
            <ul>
                <li>
                    <a href="tel:801-987-8351">
                        <i class="fa fa-phone-square"></i> 
                        801-987-8351
                    </a>
                </li>
                <li>
                    <?= $this->Html->link("<i class='fa fa-envelope-o'></i> Contact Us", "/pages/contactUs", array("escape" => false)); ?>
                </li>
                <li class="divider">&nbsp;</li>
            </ul>

            <p>Social Media</p>
            <hr />
            <ul>
                <li>
                    <h1>
                        <?= $this->Html->link("<img alt='facebook' src='https://www.boostpromotions.com/includes/templates/glasgow_neat/images/facebook.png' />", "https://www.facebook.com/BoostPromotions/", array("escape" => false, "target" => "_blank")); ?>
                        <?= $this->Html->link("<img alt='twitter' src='https://www.boostpromotions.com/includes/templates/glasgow_neat/images/twitter.png' />", "https://twitter.com/boostpromotion", array("escape" => false, "target" => "_blank")); ?>
                        <?= $this->Html->link("<img alt='youtube' src='https://www.boostpromotions.com/includes/templates/glasgow_neat/images/youtube.png'>", "https://www.youtube.com/user/boostpromotionstv", array("escape" => false, "target" => "_blank")); ?>
                        <?= $this->Html->link("<img alt='pinterest' src='https://www.boostpromotions.com/includes/templates/glasgow_neat/images/pinterest.png' />", "https://www.pinterest.com/boostpromotions/BoostPromotions/", array("escape" => false, "target" => "_blank")); ?>
                    </h1>
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <p>TEMPLATES</p>
            <hr />
            <ul>
                <li><?= $this->Html->link("Swag Tag", "/template/index/swag_tag"); ?></li>
                <li> <?= $this->Html->link("Spirit Tags", "/template/index/spirit_tags"); ?></li>
                <li> <?= $this->Html->link("Shape Tags", "/template/index/shape_tags"); ?></li>
                <li> <?= $this->Html->link("Shoe Tag", "/template/index/shoe_tag"); ?></li>
                <li> <?= $this->Html->link("Bag Tags", "/template/index/bag_tags"); ?></li>
                <li> <?= $this->Html->link("Jr Bag Tags", "/template/index/jr_bag_tags"); ?></li>
                <!--<li><a href="/index.php?main_page=template_tag&t=lanyards">Lanyards</a></li>-->
                <li><a href="http://www.pantone-colours.com/" target="_blank">Pantone Color Sheets</a></li>
            </ul>

        </div>
        <div class="col-md-4">
            <p>INFORMATION</p>
            <hr />
            <ul>
                <li><?= $this->Html->link("About Us", "/pages/aboutUs"); ?></li>
                <li><?= $this->Html->link("Contact Us", "/pages/contact_us"); ?></li>
                <li><?= $this->Html->link("Terms of Use", "/pages/terms"); ?></li>
                <li><?= $this->Html->link("My Account", "/customers/account"); ?></li>
                <li><?= $this->Html->link("Fundraisers", "/pages/fundraising"); ?></li>
                <li><?= $this->Html->link("How To Order", "/pages/how_to_order"); ?></li>
                <li><?= $this->Html->link("FAQ's", "/pages/faq"); ?></li>
            </ul>
        </div>
    </div>
</footer>
<?php $this->end(); ?>
