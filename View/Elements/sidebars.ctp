<?php $this->start("myAccountSidebar"); ?>
<div class="col-md-3">
    <div class="row">
        <h1><?= $title ?></h1>
    </div>
    <div class="row top-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa fa-bars"></i>
                    Menu
                </h4>
            </div>

            <ul class="sidebar nav nav-pills nav-stacked ">
                <li class="<?= ($active == "orders") ? "active" : "" ?>"><?= $this->Html->link("PREVIOUS ORDERS", "/orders/") ?></li>
                <li class="<?= ($active == "profile") ? "active" : "" ?>"><?= $this->Html->link("PROFILE INFORMATION", "/customers/editAccount/") ?></li>
                <li class="<?= ($active == "addressbook") ? "active" : "" ?>"><?= $this->Html->link("ADDRESS BOOK", "/addressBooks/view"); ?></li>
                <li class="<?= ($active == "password") ? "active" : "" ?>"><?= $this->Html->link("CHANGE PASSWORD", "/customers/changePassword/"); ?></li>
            </ul>
        </div>
    </div>
</div>
<?php $this->end(); ?>