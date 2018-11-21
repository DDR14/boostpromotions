<!-- Main header -->
<?php $this->start("mainHeader"); ?>
<section class="page-toolbar">
    <div class="container">
        <!--        <h1>Save HALF OFF on School Swag Tags - JUST .11 EACH. Largest Selection. Swag Tags for students and schools.</h1>-->
        <ul class="navbar-right" id="top-nav">
            <li><?= $this->Html->link("<i class='fa fa-home'></i> &nbsp;FAQ", "/pages/faq", array("escape" => false)); ?></li>
            <?php if (AuthComponent::user("customers_id")): ?>
                <li><?= $this->Html->link("<i class='fa fa-user-circle'></i> &nbsp;Account", "/orders/", array("escape" => false)); ?></li>
                <li><?= $this->Html->link("<i class='fa fa-sign-out'></i> &nbsp;Logout", "/customers/logout", array("escape" => false)); ?></li>
            <?php else: ?>
                <li>
                    <?= $this->Html->link("<i class='fa fa-user-plus'></i> &nbsp;Register", "/customers/register", array("escape" => false)); ?>
                </li>

                <li>
                    <a href="<?= $this->webroot; ?>customers/login" ng-click="showModal('#login-modal', '', $event)">
                        <i class='fa fa-sign-in'></i> &nbsp;Login
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <?= $this->Html->link("<i class='fa fa-shopping-cart'></i>&nbsp;{$headerData['cart_qty']} Item(s) - {$this->Number->currency($headerData['cart_total'], 'USD')}", "/shoppingCart", array("escape" => false)); ?>
            </li>
        </ul>
    </div>
</section>
<header class="main-header hidden-xs hidden-sm">
    <div class="container">
        <div class="main-header-top">
            <div class="pull-left">
                <?= $this->Html->link($this->Html->image("logo.png", ["height" => "77px", "alt" => 'Boost Promotions']), "/", ["escape" => false]) ?>
            </div>            
            <div class="pull-right">
                <?php if (AuthComponent::user("customers_id")): ?>
                    <div class="text-left">
                        <?=
                        ucfirst(AuthComponent::user('customers_firstname')) . ' '
                        . ucfirst(AuthComponent::user('customers_lastname'))
                        ?><br/>
                        <?php if ($headerData["default_address"]): ?>
                            <?= ucfirst($headerData["default_address"]["AddressBook"]["entry_street_address"]); ?>
                            <div><?= ucfirst($headerData["default_address"]["AddressBook"]["entry_suburb"]) ?></div>
                            <?= ucfirst($headerData["default_address"]["AddressBook"]["entry_city"]) ?>,
                            <?php
                            echo (empty($headerData['default_address']["Zone"]["zone_name"]) ? $headerData['default_address']["AddressBook"]["entry_state"] : $headerData['default_address']["Zone"]["zone_name"]);
                            echo ' ' . $headerData['default_address']["AddressBook"]["entry_postcode"];
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="pull-right" style="margin-top: 3px;">
                <span class="input-group" >
                    <?=
                    $this->Form->create(null, array(
                        "url" => array("controller" => "products", "action" => "search"),
                        "class" => "navbar-form",
                        "role" => "search",
                        "type" => "get"
                    ))
                    ?>
                    <?=
                    $this->Form->input("Product.search", array(
                        "type" => "search",
                        "class" => "form-control",
                        "placeholder" => "Search products and designs",
                        "label" => false,
                        "required",
                        "div" => false,
                        "value" => isset($_GET['search'])?$_GET['search']:''
                    ));
                    ?>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>

                    <?= $this->Form->end(); ?>
                </span>
            </div>
            <aside class="header-tarp">
                <a href="tel:801-987-8351" >
                    <i class="fa fa-phone"></i>
                    <div class="header-tarp-talk">
                        <div class="text-uppercase small">Talk to a Real Person</div>
                        <strong >801-987-8351</strong>
                    </div>
                </a>

                <div class="header-tarp-action">
                    <?=
                    $this->Html->link("START AN ORDER", "/products", ['class' => 'btn btn-primary'])
                    ?>
                </div>
            </aside>
            <div class="clearfix"></div>
        </div>

        <div id="main-nav">
            <div class="btn-group btn-group-justified">
                <div class="btn-group dropdown" role="group">  
                    <?=
                    $this->Html->link('PRODUCTS&nbsp; <i class="fa fa-caret-down"></i>', "/products", array(
                        "escape" => false,
                        "id" => "products",
                        'class' => 'btn btn-default'
                    ))
                    ?>
                    <div class="row mega-menu dropdown-menu">
                        <div class="col-md-4">
                            <ul class="nav">
                                <li><?= $this->Html->link("Swag Tags", '/products/order/294'); ?></li>
                                <li><?= $this->Html->link("Shape Tags", '/products/order/299'); ?></li>
                                <li><?= $this->Html->link("Spirit Tags", '/products/order/313'); ?></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul class="nav">
                                <li><?= $this->Html->link("JR Bag Tags", '/products/order/317'); ?></li>
                                <li><?= $this->Html->link("Bag Tags", '/products/order/322'); ?></li>
                                <li><?= $this->Html->link('Custom Key Fobs', '/products/category/441'); ?></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul class="nav">
                                <li><?= $this->Html->link("Lanyards", '/products/accessories/519'); ?></li>
                                <li><?= $this->Html->link('Accessories', '/products/category/520'); ?></li>
                                <li><?= $this->Html->link("Teacher Kits", '/products/accessories/101'); ?></li>   
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="btn-group dropdown" role="group">                  
                    <?=
                    $this->Html->link('DESIGN LIBRARY&nbsp; <i class="fa fa-caret-down"></i>', "/designs/", array(
                        "escape" => false,
                        'class' => 'btn btn-default'
                    ))
                    ?>
                    <div class="row mega-menu dropdown-menu">
                        <?php foreach ($subCategoriesGrp as $group): ?>
                            <div class="col-md-4">
                                <ul class="nav">
                                    <?php foreach ($group as $cat): ?>
                                        <li>
                                            <?= $this->Html->link($cat["CategoriesDescription"]["categories_name"], "/designs/index/" . $cat["categories_id"]); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?= $this->Html->link("FUNDRAISING", "/pages/fundraising", ['class' => 'btn btn-default']); ?>
                <?= $this->Html->link("HOW TO ORDER", "/pages/howToOrder", ['class' => 'btn btn-default']); ?>
                <?= $this->Html->link("OUR MISSION", "/pages/our_mission", ['class' => 'btn btn-default']); ?>
                <?= $this->Html->link("PRICING", "/pages/pricing", ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</header>
<!-- SMALL DISPLAY -->
<div class="hidden-lg hidden-md">
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="navbar-header">
            <button id="common-nav-btn" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-main-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <?php if (!AuthComponent::user('customers_id')): ?>
                <button type="button" class="navbar-toggle nav-icon" ng-click="showModal('#login-modal', '', $event)">
                    <span class="fa fa-sign-in"></span>
                </button>
            <?php else: ?>

                <button id="profile-nav-btn" type="button" class="navbar-toggle nav-icon" data-toggle="collapse" data-target="#mobile-profile-nav">
                    <span class="fa fa-user-circle"></span>
                </button>
            <?php endif; ?>

            <?=
            $this->Html->link($this->Html->image('logo.png', ['class' => "nav-img", "alt" => "Boost Promotions"]), '/', [
                'class' => 'navbar-brand',
                'escape' => false
            ]);
            ?>

        </div>
        <!--  nav bar options for mobile -->
        <div class="collapse navbar-collapse" id="mobile-main-nav">
            <ul class="nav navbar-nav">
                <li><?= $this->Html->link("PRODUCTS", "/products", ['class' => 'btn btn-default']); ?></li>
                <li><?= $this->Html->link("DESIGNS", "/designs/index/All%20Designs", ['class' => 'btn btn-default']); ?></li>
                <li><?= $this->Html->link("FUNDRAISING", "/pages/fundraising", ['class' => 'btn btn-default']); ?></li>
                <li><?= $this->Html->link("HOW TO ORDER", "/pages/howToOrder", ['class' => 'btn btn-default']); ?></li>
                <li><?= $this->Html->link("OUR MISSION", "/pages/our_mission", ['class' => 'btn btn-default']); ?></li>
                <li><?= $this->Html->link("PRICING", "/pages/pricing", ['class' => 'btn btn-default']); ?></li>
            </ul>
        </div>

        <div class="collapse navbar-collapse" id="mobile-profile-nav">
            <ul class="nav navbar-nav">
                <li><?= $this->Html->link("<i class='fa fa-home'></i> &nbsp;FAQ", "/pages/faq", array("escape" => false)); ?></li>

                <?php if (AuthComponent::user("customers_id")): ?>
                    <li><?= $this->Html->link("<i class='fa fa-user-circle'></i> &nbsp;Account", "/orders/", array("escape" => false)); ?></li>

                    <li>
                        <?= $this->Html->link("<i class='fa fa-shopping-cart'></i>&nbsp;{$headerData['cart_qty']} Item(s) - {$this->Number->currency($headerData['cart_total'], 'USD')}", "/shoppingCart", array("escape" => false)); ?>

                    </li>
                    <li><?= $this->Html->link("<i class='fa fa-sign-out'></i> &nbsp;Logout", "/customers/logout", array("escape" => false)); ?></li>
                <?php else: ?>
                    <li>
                        <?= $this->Html->link("<i class='fa fa-user-plus'></i> &nbsp;Register", "/customers/register", array("escape" => false)); ?>
                    </li>
                    <li>
                        <a href="<?= $this->webroot; ?>customers/login" ng-click="showModal('#login-modal', '', $event)">
                            <i class='fa fa-sign-in'></i> &nbsp;Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>


    </div>
</div>

<div class="bottom-60 hidden-lg hidden-md"></div>
</div>
<?php $this->end(); ?>