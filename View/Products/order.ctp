<?php
$mcategory = '';
if ($mainCategory):
    $mcategory = $mainCategory["CategoriesDescription"]["categories_name"];
    $this->assign('title', $mcategory
            . " - Customizeable Student Rewards | Boost Promotions");
else:
    $mcategory = $products[$first_tab]["ProductsDescription"]["products_name"];
    $this->assign('title', $mcategory . " | Boost Promotions");
endif;
?>
<script type="text/javascript">
<?php if (!empty($selectedDesigns)): ?>
        // this script tag will show ony if the
        var selectedDesigns = '<?= $selectedDesigns ?>';
<?php endif; ?>

    localStorage.removeItem("openStockModels");</script>

<input type=hidden hidden="searchData" ng-value="{{searchData}}"/>

<section ng-controller="OrderCtrl">
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('All Products', '/products'); ?></li>
            <?php if (isset($mainCategory["ParentCategory"]) && $mainCategory["ParentCategory"]['categories_id'] != 293): ?>
                <li><?=
                    $this->Html->link($mainCategory["ParentCategory"]['CategoriesDescription']['categories_name'], '/products/category/'
                            . $mainCategory["ParentCategory"]['categories_id']);
                    ?></li>
            <?php endif; ?>
            <?php if (!$mainCategory && !$customersBasketId): ?>
                <?= (!in_array($products[$first_tab]['Product']['master_categories_id'], [519, 101])) ? '<li>' . $this->Html->link('Accessories', '/products/category/520') . '</li>' : ''; ?>
                <li><?=
                    $this->Html->link($products[$first_tab]['MasterCategoriesDescription']['categories_name'], '/products/accessories/'
                            . $products[$first_tab]['MasterCategoriesDescription']['categories_id']);
                    ?></li>
            <?php endif; ?>
            <li class="active"><?= $mcategory; ?></li>
        </ul>
        <div class="row">
            <!-- Product image and description -->
            <div class="col-md-3" >
                <h1 id="productName">
                    <?= $products[$first_tab]["ProductsDescription"]["products_name"] ?>
                </h1>
                <div class="row top-20 hidden-xs hidden-sm">
                    <div class="col-md-10">             
                        <img id="product-img" src="https://boostpromotions.com/images/<?= $products[$first_tab]['Product']['products_image'] ?>" 
                             alt="<?= $products[$first_tab]['ProductsDescription']['products_name']; ?>"
                             class="img-responsive thumbnail">
                    </div>
                </div>
                <p id="productDesc">
                    <?= $products[$first_tab]["ProductsDescription"]["products_description"] ?>
                </p>
                <hr class="hidden-lg hidden-md" />
            </div>

            <!-- Product Order Form -->
            <div class="col-md-9">

                <?= $this->Flash->render("newItemAdded"); ?>
                <?php if (AuthComponent::user("customers_id")): ?>
                    <button ng-click="previousOrders()" class="btn btn-info btn-sm pull-right">
                        CHOOSE FROM PREVIOUS ORDERS
                    </button>
                <?php endif; ?>
                <?php if (isset($subCategories)) : ?>
                    <h5>Choose Specific Shape</h5>
                    <div class="mobile-categories-list">
                        <?php foreach ($subCategories as $shape): ?>

                            <a href="<?= $this->webroot; ?>products/order/<?= $shape['Category']['categories_id'] ?>" class="img-thumbnail bottom-5 <?= ($higlight == $shape['Category']['categories_id']) ? 'highlight' : '' ?> ">
                                <img src="https://www.boostpromotions.com/images/<?= $shape['Category']['categories_image'] ?>" 
                                     alt="<?= $shape['CategoriesDescription']['categories_name']; ?>"
                                     class="img-square center-block"/>
                                <p class="text-white text-center">
                                    <small><?= strtoupper($shape["CategoriesDescription"]["categories_name"]) ?></small><br />
                                    <small><?= $shape["Category"]["size"] ?></small>
                                </p>
                            </a>

                        <?php endforeach; ?>
                    </div>
                    <h5>Choose Tag Type</h5>
                <?php endif; ?>
                <ul class="nav nav-tabs">
                    <?php
                    foreach ($products as $key => $product):
                        // get the model of the product and convert it to array
                        $productType = explode("-", $product["Product"]["products_model"]);

                        if (in_array($productType[0], ["STOCK", "2STOCK"])) {
                            $products[$key]['type'] = "stockProduct";
                        } elseif (in_array("MODIFIED", $productType)) {
                            $products[$key]['type'] = "modifiedProduct";
                        } elseif (in_array("CUSTOM", $productType)) {
                            $products[$key]['type'] = "customProduct";
                        } elseif ($product["Product"]["master_categories_id"] == 101 || $product["Product"]["master_categories_id"] == 24) {
                            $products[$key]['type'] = "teachersKits";
                        } else {
                            $products[$key]['type'] = "accessories";
                        }

                        $storage = $categoryId;
                        if ($categoryId != 'update') {
                            $storage = $products[$key]['type'] == "modifiedProduct" ? 'modified-tag' : 'open-stock';
                        }
                        ?>
                        <li <?= ($first_tab == $key) ? 'class="active"' : ''; ?> >
                            <a href="#<?= $product['Product']['products_id'] ?>" data-toggle="tab" 
                               ng-click="changeTab({
                                                   img: '<?= $product['Product']['products_image'] ?>',
                                                                   type: '<?= $storage; ?>',
                                                                                   name: '<?= $product["ProductsDescription"]["products_name"] ?>',
                                                                                                   desc: '<?= $product["ProductsDescription"]["products_description"] ?>'})">
                                <img src="https://boostpromotions.com/images/<?= $product['Product']['products_image'] ?>" 
                                     alt="<?= $product['ProductsDescription']['products_name']; ?>" class="img-square"/>
                                <br/><?= $product['ProductsDescription']['products_name'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content">
                    <?php
                    foreach ($products as $key => $product):
                        ?>
                        <div id="<?= $product['Product']['products_id'] ?>" 
                             class="tab-pane fade in <?= ($first_tab == $key) ? 'active' : ''; ?>">
                            <div class="col-md-12">  
                                <h4 class="top-20 pull-right text-muted"><?= $product['Product']['products_model'] ?></h4>
                                <h4 class="top-20">Pricing <br/>
                                    <small>
                                        <?php
                                        if ($product['type'] == 'stockProduct'):
                                            echo 'Must be ordered in quantities of 25. All selected designs will be added together to determine pricing.';
                                        elseif (in_array($product['type'], ['modifiedProduct', 'customProduct'])):
                                            echo '(Pricing is PER Design)';
                                        endif;
                                        ?>
                                    </small>
                                </h4>
                                <!-- Discount Quantity Pricing -->
                                <div class="table-responsive">
                                    <table class="table table-bordered striped-colums">
                                        <tr>
                                            <?php if (isset($products[$key]['merge']) || in_array($product["Product"]["products_id"], [990, 853])): ?>
                                                <th></th>
                                            <?php endif; ?>
                                            <th><?= $product["Product"]["products_quantity_order_min"]; ?>+</th>
                                            <?php foreach ($product['ProductsDiscountQuantity'] as $data): ?>
                                                <th><?= $data["discount_qty"] ?>+</th>
                                            <?php endforeach; ?>
                                        </tr>
                                        <tr>
                                            <?php if (in_array($product["Product"]["products_id"], [990, 853])): ?>
                                                <td><b>With Tag Order (Minimum of 500 Tags)</b></td>
                                            <?php endif; ?>
                                            <?php if (isset($products[$key]['merge'])): ?>
                                                <td><b>1 Sided <?= $product["ProductsDescription"]["products_name"]; ?></b></td>
                                            <?php endif; ?>
                                            <td><?= $this->Number->currency($product["Product"]["products_price"], "$"); ?></td>
                                            <?php foreach ($product['ProductsDiscountQuantity'] as $data): ?>
                                                <td><?= $this->Number->currency($data["discount_price"], "$") ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                        <?php
                                        //IF LANYARDS, ADD $1 for each
                                        if (in_array($product["Product"]['products_id'], [990, 853])):
                                            echo "<tr><td><b>Without Tag Order</b></td>";
                                            echo '<td>' . $this->Number->currency($product["Product"]["products_price"] + 1, "$") . '</td>';
                                            foreach ($product['ProductsDiscountQuantity'] as $data):
                                                ?>
                                                <td><?= $this->Number->currency($data["discount_price"] + 1, "$") ?></td>
                                                <?php
                                            endforeach;
                                            echo "</tr>";
                                        endif;

                                        //if merge
                                        if (isset($products[$key]['merge'])) {
                                            foreach ($products[$key]['merge'] as $mdata):
                                                echo "<tr><td><b>" . $mdata['ProductsDescription']['products_name'] . "</b></td>";
                                                echo '<td>' . $this->Number->currency($mdata["Product"]["products_price"], "$") . '</td>';
                                                foreach ($mdata['ProductsDiscountQuantity'] as $data):
                                                    ?>
                                                    <td><?= $this->Number->currency($data["discount_price"], "$") ?></td>
                                                    <?php
                                                endforeach;
                                                echo "</tr>";
                                            endforeach;
                                        }
                                        ?>
                                    </table>
                                </div>                                

                                <?php if (isset($products[$key]['merge'])): ?>
                                    <p class="sidescount">
                                    <style>                                        
                                        .sidescount label { 
                                            margin: 5px 5px 10px 5px;
                                        }
                                        .sidescount label > input + img{
                                            cursor:pointer;
                                            border:2px solid transparent;
                                        }
                                        .sidescount label > input:checked + img{
                                            border:2px solid #f00;
                                        }                                        
                                    </style>
                                    <?php $this->start('script'); ?>
                                    <script>
                                                $('input[type=radio][name=rd_<?= $product['Product']['products_id']; ?>]').change(function () {
                                                    $("form#<?= $product['Product']['products_id']; ?>-frm input[name='data[CustomersBasket][products_id]']").val(this.value);
                                                });
                                    </script>
                                    <?php $this->end(); ?>
                                    <label>
                                        <input checked required type="radio" name="rd_<?= $product['Product']['products_id']; ?>" 
                                               value="<?= $product["Product"]["products_id"]; ?>" />
                                        <img id="product-img" src="https://boostpromotions.com/images/<?= $product['Product']['products_image'] ?>" 
                                             alt="<?= $product['ProductsDescription']['products_name']; ?>"
                                             class="img-square">
                                        <br/>
                                        <small><?= $product['ProductsDescription']['products_name']; ?></small>
                                    </label>
                                    <?php foreach ($products[$key]['merge'] as $mdata): ?>
                                        <label>
                                            <input required type="radio" name="rd_<?= $product['Product']['products_id']; ?>" 
                                                   value="<?= $mdata["Product"]["products_id"]; ?>" />
                                            <img id="product-img" src="https://boostpromotions.com/images/<?= $mdata['Product']['products_image'] ?>" 
                                                 alt="<?= $mdata['ProductsDescription']['products_name']; ?>"
                                                 class="img-square">
                                            <br/>
                                            <small><?= $mdata['ProductsDescription']['products_name']; ?></small>
                                        </label>
                                    <?php endforeach;
                                    ?>
                                    </p>
                                <?php endif; ?>

                                <?php
                                // View/Elements/forms.ctp
                                echo $this->element("forms", ["product" => $product]);
                                ?>
                                <p class="small text-muted">Shipping Weight: <?= $product['Product']['products_weight']; ?>lbs</p>
                            </div>
                            <div class="clearfix"></div>                            
                        </div>                
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Previous Orders Modal Box -->
    <div class="modal fade" id="previous-orders">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h5 class="modal-title">Previous Orders</h5>
                </div>

                <div class="modal-body">
                    <!-- Loading Spinner -->
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center top-20" ng-show="showLoading">
                            <i class="fa fa-spinner  fa-pulse fa-fw fa-3x"></i>
                            <p class="top-5"><b>Loading Data Please Wait...</b></p>
                        </div>
                    </div>

                    <!-- Previous Orders List -->
                    <div class="row">
                        <div class="col-md-12" ng-hide="showLoading">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Order No.</th>
                                        <th>Date</th>
                                        <th>Ship To</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>

                                    <tr ng-repeat="order in ordersHistory">
                                        <td># {{ order.Order.orders_id}}</td>
                                        <td>{{ order.Order.date_purchased}}</td>
                                        <td>{{ order.Order.delivery_name}}</td>
                                        <td>{{ order.OrdersTotal | getTotal | currency}}</td>
                                        <td>
                                            <a ng-href="<?= $this->webroot ?>orders/view/{{ order.Order.orders_id}}" class="btn btn-info btn-xs">
                                                OPEN ORDER
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
                </div>
            </div>
        </div>
    </div>
</section>
