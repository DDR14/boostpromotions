<div class="col-md-12">
    <?php if ($product['type'] == 'stockProduct'):
        ?> 
        <h4>Design</h4>
        <div class="row">
            <div class="col-md-6">
                <p class=""><b>Please enter design # and desired quantity below and click the Add button.</b></p>
                <form class="form-inline" ng-submit="addToOrder(model, '#products-category', '<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'open-stock-update' : 'open-stock'; ?>', $event, null, <?= $product['Product']['master_categories_id'] ?>)">
                    <div class="form-group">
                        <input ng-model="model.keyword" type="search" class="form-control design-no-frm" placeholder="Design #" required/>

                        <input ng-model="model.qty" type="number" min="25" step="25" class="form-control design-qty-frm" placeholder="QTY" required/>
                    </div>

                    <button class="btn btn-primary">Add</button>
                </form>
            </div>
            <div class="col-md-6">
                <p><b>You can also browse our existing tag designs or the designs that you added in your favorite list using the buttons below.</b></p>
                <button type="button" ng-hide="loading" class="btn btn-primary" ng-click="showModal('#products-category', '<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'open-stock-update' : 'open-stock'; ?>', $event, false)">
                    Choose From Our Existing Designs
                </button>
                <button ng-hide="loading" type="button" class="btn btn-default" ng-click="showFavorites('<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'open-stock-update' : 'open-stock'; ?>', <?= $product['Product']['master_categories_id'] ?>)">
                    Open Favorite Designs List
                </button>
            </div>
        </div>
        <div class="top-20">                                        
            <p ng-show="loading"><b>
                    <i class="fa fa-spinner fa-pulse" aria-hidden="true"></i> Loading data...
                </b></p>
            <h4 class="top-20" ng-if="selectedModels.length != 0">
                Selected Tag
                <span ng-if="selectedModels.length > 1">Designs</span>
                <span ng-if="selectedModels.length == 1">Design</span>
            </h4>
            <hr />
            <div ng-repeat="model in selectedModels" class="col-md-4 col-sm-6">
                <div class="row">
                    <div class="col-xs-6">
                        <img src="https://boostpromotions.com/images/designs/Resize/{{ model.productModel | getImgDir }}/{{ model.productImage}}" class="img-responsive">
                    </div>
                    <div class="col-xs-6 no-padding">
                        <p><b>Model: <span ng-bind="model.productModel | uppercase | removeTrailing01"></span></b></p>
                        <p><b>Order: <span ng-bind="model.orderQty + 'pcs.'"></span></b></p>

                        <button class="btn btn-sm btn-primary" type="button" ng-click="editQty(selectedModels, model, '<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'open-stock-update' : 'open-stock'; ?>')">Edit Qty</button>
                        <button class="btn btn-sm btn-danger" type="button" ng-click="removeItem(selectedModels, model.productId, '<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'open-stock-update' : 'open-stock'; ?>')">Remove</button>
                    </div>
                </div>
            </div>

            <div ng-show="!hasData" class=" alert alert-info" >
                <p><b>You have not selected any designs. Please choose from our existing designs by clicking the button above or type the model in the text box.</b></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th>TOTAL QTY</th>
                        <th><span ng-bind="totalQty + 'pcs.'"></span></th>
                    </tr>
                </table>
            </div>
        </div>

        <?=
        $this->Form->create(null, array(
            "url" => array("controller" => "products", "action" => "order", $categoryId, $customersBasketId),
            "id" => $product['Product']['products_id'] . "-frm",
            "enctype" => "multipart/form-data"
        ));
        ?>

        <!-- Hidden input fields -->
        <?= $this->Form->hidden("CustomersBasket.products_id", array("value" => $product["Product"]["products_id"])); ?> 
        <div ng-if="hasData">                                        
            <?= $this->Form->hidden("CustomersBasket.customers_basket_date_added", array("value" => date("Ymd"))); ?>
            <?= $this->Form->hidden("CustomersBasket.website", array("value" => "wwww.boostpromotions.com")); ?>

            <input type="hidden" name="data[CustomersBasket][customers_basket_quantity]" value="{{ totalQty}}">
            <input type="hidden" name="data[CustomersBasket][customs]" value="{{ stringData}}">
        </div>
        <!-- Submit Button -->
        <div class="pull-right col-md-6">
            <button ng-click="submitOrder($event, '#<?= $product['Product']['products_id'] ?>-frm')" type="submit" class="btn btn-success btn-lg" ng-hide="!hasData">
                <i class="fa fa-cart-plus"></i> &nbsp;
                <?= ($categoryId == "update") ? "Update Order" : "Add To Cart"; ?>
            </button>

            <button type="button" ng-click="orderIsEmpty($event)" class="btn btn-success btn-lg" ng-show="!hasData">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <?= ($categoryId == "update") ? "Update Order" : "Add To Cart"; ?>
            </button>
        </div>
        <?= $this->Form->end(); ?>
    <?php elseif ($product['type'] == 'modifiedProduct'):
        ?>
        <div class='row'>
            <div class="col-md-6">

                <div class="">
                    <h4>Design</h4>
                    <p class="">
                        <b>Please enter
                            design # and desired quantity below and click the Add button before entering modification details.
                        </b>
                    </p>
                </div>

                <div class="">
                    <form class="form-inline" ng-submit="addToOrder(model, '#products-category', '<?= ($categoryId == 'update' || $categoryId == 're-order' ) ? 'update-modified-tag' : 'modified-tag'; ?>', $event, null, <?= $product['Product']['master_categories_id'] ?>)">
                        <div class="form-group">
                            <input ng-model="model.keyword" type="search" class="form-control design-no-frm" placeholder="Design #" required/>

                            <input ng-model="model.qty" type="number" min="25" class="form-control design-qty-frm" placeholder="QTY" required/>
                        </div>

                        <button class="btn btn-primary">Add</button>
                    </form>
                </div>

                <p class="top-10"><b>You can also browse our existing tag designs or the designs that you added in your favorite list using the buttons below.</b></p>

                <button ng-hide="loading" class="btn btn-primary btn-block" ng-click="showModal('#products-category', '<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'update-modified-tag' : 'modified-tag'; ?>', $event, false)">
                    Choose From Our Existing Designs
                </button>

                <p ng-show="loading"><b>
                        <i class="fa fa-spinner fa-pulse" aria-hidden="true"></i> Loading data...
                    </b></p>

                <button ng-hide="loading" type="button" class="btn btn-default btn-block top-10" ng-click="showFavorites('<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'update-modified-tag' : 'modified-tag'; ?>', <?= $product['Product']['master_categories_id'] ?>)">
                    Open Favorite Designs List
                </button>
                <h4 class="top-20">Selected Tag Design</h4>
                <hr />
                <div ng-repeat="model in selectedModels" >
                    <div class="row">
                        <div class="col-sm-6">
                            <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ model.productModel | getImgDir }}/{{ model.productImage}}" class="img-responsive">
                        </div>

                        <div class="col-sm-6">
                            <p><b>Model: <span ng-bind="model.productModel | uppercase | removeTrailing01"></span></b></p>
                            <p><b>Order: <span ng-bind="model.orderQty + 'pcs.'"></span></b></p>

                            <button class="btn btn-sm btn-primary" type="button" ng-click="editQty(selectedModels, model, '<?= ($categoryId == 'update' || $categoryId == 're-order' ) ? 'update-modified-tag' : 'modified-tag'; ?>')">Edit Qty</button>
                            <button class="btn btn-sm btn-danger" type="button" ng-click="removeItem(selectedModels, model.productId, '<?= ($categoryId == 'update' || $categoryId == 're-order' ) ? 'update-modified-tag' : 'modified-tag'; ?>')">Remove</button>
                        </div>
                    </div>
                </div>

                <div ng-hide="selectedModels" >
                    <div class="col-md-4 col-md-offset-4 text-center top-20">
                        <i class="fa fa-cog  fa-spin fa-fw fa-3x"></i>
                        <p class="top-5"><b>Loading...</b></p>
                    </div>
                </div>

                <div ng-show="!hasData" class=" alert alert-info" >
                    <p><b>You have not selected any designs. Please choose from our existing designs by clicking the button above or type the model in the text box.</b></p>
                </div>

                <div class="table-responsive">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <th>ORDER QTY.</th>
                            <th><span ng-bind="totalQty + 'pcs.'"></span></th>
                        </tr>
                    </table>
                </div>
            </div>
            <?=
            $this->Form->create(null, array(
                "url" => array("controller" => "products", "action" => "order", $categoryId, $customersBasketId),
                "enctype" => "multipart/form-data",
                "id" => $product['Product']['products_id'] . "-frm"
            ));
            ?>

            <!-- Hidden input fields -->
            <?= $this->Form->hidden("CustomersBasket.products_id", array("value" => $product["Product"]["products_id"])); ?>
            <div ng-if="hasData">                                            
                <?= $this->Form->hidden("CustomersBasket.customers_basket_date_added", array("value" => date("Ymd"))); ?>
                <?= $this->Form->hidden("CustomersBasket.website", array("value" => "wwww.boostpromotions.com")); ?>


                <input type="hidden" name="data[CustomersBasket][customers_basket_quantity]" value="{{ totalQty}}">
                <input type="hidden" name="data[CustomersBasket][customs]" value="{{ stringData}}">
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <p class="panel-title">
                            Modify Your Tag
                        </p>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>
                                Title<br />
                                <small class="text-dark-gray">Tell us how you would like the Title to read.</small>
                            </label>
                            <?=
                            $this->Form->input("CustomersBasket.title", array(
                                "type" => "textarea",
                                "class" => "form-control",
                                "rows" => 2,
                                "label" => false
                            ));
                            ?>

                            <label>
                                Background<br />
                                <small class="text-dark-gray">Describe how you would like the background changed.</small>
                            </label>
                            <?=
                            $this->Form->input("CustomersBasket.background", array(
                                "type" => "textarea",
                                "class" => "form-control",
                                "rows" => 2,
                                "label" => false
                            ));
                            ?>

                            <label>
                                Footer<br />
                                <small class="text-dark-gray">Tell us how you would like the Footer to read</small>
                            </label>
                            <?=
                            $this->Form->input("CustomersBasket.footer", array(
                                "type" => "textarea",
                                "class" => "form-control",
                                "rows" => 2,
                                "label" => false
                            ));
                            ?>
                        </div>

                        <div class="form-group">
                            <label>
                                Image<br />
                                <small class="text-dark-gray">Describe what you would like the image to look like</small>
                            </label>
                            <?=
                            $this->Form->input("CustomersBasket.image", array(
                                "type" => "textarea",
                                "class" => "form-control",
                                "rows" => 2,
                                "label" => false
                            ));
                            ?>
                            <br/>
                            <?php if (isset($uploadedImage)): ?>
                                <b>Uploaded Image</b><br />
                                <?php
                                $path = explode("/", $uploadedImage);
                                $imgName = end($path);
                                $label = "Replace Image";
                                ?>
                                <img class="img-responsive" style="max-height:200px"  src="<?= 'https://boostpromotions.com/images/uploads/' . $uploadedImage ?>" />
                                <?= $this->Html->link($imgName, 'https://boostpromotions.com/images/uploads/' . $uploadedImage, ['target' => '_blank']); ?>
                            <?php endif; ?>
                            <?= $this->Form->hidden("CustomersBasket.upload"); ?>

                            <label class="top-20">
                                <?= (isset($imgName)) ? $label : "Upload Image"; ?>
                                <br/>
                                <small class="text-dark-gray">Replace the image on the tag with your own.</small>
                            </label>

                            <div class="row">
                                <div class="col-md-6">
                                    <?=
                                    $this->Form->input("CustomersBasket.imageFile.", array(
                                        "type" => "file",
                                        "class" => "top-5",
                                        "label" => false,
                                        "id" => "modified_img_frm",
                                        "accept" => "image/*, .docx, .doc, .psd"
                                    ));
                                    ?>
                                </div>

                                <div class="col-md-6">
                                    <button ng-click="removeFile('#modified_img_frm')"  style="margin-left: 10px;" type="button" class="btn btn-danger btn-sm pull-right">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>                                
            <?= $this->Form->end(); ?>
        </div>

        <!-- Submit Button -->
        <div class="col-md-12 bottom-20">
            <div class="pull-right">
                <button ng-click="submitOrder($event, '#<?= $product['Product']['products_id'] ?>-frm')" type="submit" class="btn btn-success btn-lg" ng-hide="!hasData">
                    <i class="fa fa-cart-plus"></i> &nbsp;
                    <?= ($categoryId == "update") ? "Update Order" : "Add To Cart"; ?>
                </button>

                <button type="button" ng-click="orderIsEmpty($event)" class="btn btn-success btn-lg" ng-show="!hasData">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    <?= ($categoryId == "update") ? "Update Order" : "Add To Cart"; ?>
                </button>
            </div>
        </div>
    <?php elseif ($product['type'] == 'customProduct'):
        ?>
        <h4>Design</h4>
        <?=
        $this->Form->create(null, array(
            "url" => array("controller" => "products", "action" => "order", $categoryId, $customersBasketId),
            "enctype" => "multipart/form-data",
            "id" => $product['Product']['products_id'] . "-frm"
        ));
        ?>

        <p>
            <?php if (isset($uploadedImage)): ?>                                        
                <b class="text-white">Uploaded Image(s)</b><br />
                <?php
                $path = explode("/", $uploadedImage);
                $imgName = end($path);
                $label = "Replace Image";
                foreach (explode(',', $path[0]) as $uploads):
                    ?>
                <p class="pull-left">
                    <img class="img-responsive" style="max-height:200px"  src="<?= 'https://boostpromotions.com/images/uploads/' . $uploads ?>" />
                    <?php
                    echo $this->Html->link($imgName, 'https://boostpromotions.com/images/uploads/' . $uploads, ['target' => '_blank']);
                    ?>
                </p><?php
            endforeach;
            ?>          
        <?php endif; ?>
        <?= $this->Form->hidden("CustomersBasket.upload"); ?>
    </p>
    <label>
        <?= isset($label) ? $label : "Upload File(s)"; ?>
        <br />
        <small class="text-dark-gray">(To select multiple files, hold down the <kbd>CTRL</kbd> or <kbd>SHIFT</kbd> key while selecting.)</small>
    </label>

    <!-- Hidden input fields -->
    <div>
        <?= $this->Form->hidden("CustomersBasket.products_id", array("value" => $product["Product"]["products_id"])); ?>
        <?= $this->Form->hidden("CustomersBasket.customers_basket_date_added", array("value" => date("Ymd"))); ?>
        <?= $this->Form->hidden("CustomersBasket.website", array("value" => "wwww.boostpromotions.com")); ?>

    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-3">
                <?=
                $this->Form->input("CustomersBasket.imageFile.", array(
                    "type" => "file",
                    "multiple",
                    "class" => "top-5",
                    "label" => false,
                    "required" => false,
                    "id" => "image_frm",
                    "accept" => "image/*, .docx, .doc, .psd"
                ));
                ?>
            </div>    

            <div class="col-md-3">
                <button ng-click="removeFile('#image_frm')"  style="margin-left: 10px;" type="button" class="btn btn-warning btn-sm pull-right">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <p><b>Note: If you choose 2 Sided - Different Design or 2 Sided - Different Design Variables, please select two (2) images so that your order could be processed.</b></p>
    </div> 

    <div class="form-group">
        <label>
            Tag design<br />
            <small class="text-dark-gray">Describe what you would like the tag to look like.</small>
        </label>
        <?=
        $this->Form->input("CustomersBasket.image", array(
            "type" => "textarea",
            "class" => "form-control",
            "id" => "custom-textarea",
            "rows" => 2,
            "label" => false
        ));
        ?>
    </div>

    <div class="form-group">
        <div class="col-md-2">
            <label>
                Quantity
            </label>
            <?=
            $this->Form->input("CustomersBasket.customers_basket_quantity", array(
                "type" => "number",
                "class" => "form-control",
                "min" => 25,
                "label" => false,
                "id" => "custom-qty",
                "placeholder" => "QTY",
                "required"
            ));
            ?>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="col-md-12 bottom-20">
        <div class="pull-right">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fa fa-cart-plus"></i> &nbsp;
                <?= ($categoryId == "update") ? "Update Order" : "Add To Cart" ?>
            </button>
        </div>
    </div>

    <?= $this->Form->end(); ?>
<?php elseif ($product['type'] == 'teachersKits'):
    ?>
    <div class="alert alert-info">
        <?php
        $allDogTagKit = [876, 877, 878];
        $comboKit = [879, 880, 881];
        ?>

        <?php if (in_array($product["Product"]["products_id"], $allDogTagKit)): ?>
            <label>
                Choose  10 unique tags per quantity.
            </label>
        <?php elseif (in_array($product["Product"]["products_id"], $comboKit)): ?>
            <label>
                List 5 Dog tags, 1 Pencil, 1 Paw, 1 Star,
                1 Heart, and 1 Guitar tag per quantity.
            </label>
        <?php else: ?>
            <label>
                Extra Dog Tags Choose from one of the following combinations.
                <div class="col-md-12">
                    <ul>
                        <li>5 each of 6 Designs</li>
                        <li>6 each of 5 Designs</li>
                        <li>10 each of 3 Designs</li>
                        <li>3 each of 10 Designs</li>
                        <li>1 each of 30 Designs</li>
                    </ul>
                </div>
            </label>
        <?php endif; ?>
    </div>
    <?php
// Add teachersk kits qty here
    if ($product["Product"]["products_id"] == 876 || $product["Product"]["products_id"] == 879) {
        $qty = 25;
    } else if ($product["Product"]["products_id"] == 877 || $product["Product"]["products_id"] == 880) {
        $qty = 30;
    } else if ($product["Product"]["products_id"] == 878 || $product["Product"]["products_id"] == 881) {
        $qty = 35;
    } else {
        $qty = 10; //well deal with making it smaller later
    }
    ?>

    <div class="col-md-12" ng-init="model.qty = <?= $qty ?>">
        <p class=""><b>Please enter design # below and click the Add button.</b></p>
        <form class="form-inline" ng-submit="addToOrder(model, '#products-category', '<?= ($categoryId == 'update' || $categoryId == 're-order' ) ? 'update' : 'open-stock'; ?>', $event, <?= $product["Product"]["products_id"] ?>)">
            <input ng-model="model.keyword" type="search" class="form-control design-no-frm" placeholder="Design #" required/>

            <?php if ($product["Product"]["products_id"] != 888): ?>
                <input disabled type="number" ng-model="model.qty" disable class="form-control design-qty-frm"/>
            <?php endif; ?>

            <?php if (in_array($product["Product"]["products_id"], $comboKit)): ?>
                <select required ng-model="model.shape" 
                        ng-options="sh.shape as (sh       .shape + 
                                                                                                               ' (' +                                      s       h.left +                                      ')')  disable when sh.left === 
                                                0 for sh in shapes"
                        class="form-control">
                    <option value="" >-Select Shape-</option>
                </select> 
            <?php endif; ?>

            <button class="btn btn-primary">Add</button>
        </form>

        <p class="top-10"><b>You can also browse our existing tag designs or the designs that you added in your favorite list using the buttons below.</b></p>
        <button ng-hide="loading" type="button" class="btn btn-primary" ng-click="showModal('#products-category', '<?= ($categoryId != 'update') ? 'open-stock' : 'update'; ?>', $event, <?= $product["Product"]["products_id"] ?>)">
            Choose From Our Existing Designs
        </button>

        <p ng-show="loading"><b>
                <i class="fa fa-spinner fa-pulse" aria-hidden="true"></i> Loading data...
            </b></p>

        <button ng-hide="loading" type="button" class="btn btn-default" ng-click="showFavorites('<?= ($categoryId == 'update' || $categoryId == 're-order') ? 'open-stock-update' : 'open-stock'; ?>', <?= $product['Product']['master_categories_id'] ?>, <?= $product['Product']['products_id'] ?>)">
            Open Favorite Designs List
        </button>

        <h4 class="top-20" ng-if="selectedModels.length != 0">
            Selected Tag
            <span ng-if="selectedModels.length > 1">Designs</span>
            <span ng-if="selectedModels.length == 1">Design</span>
        </h4>

        <?=
        $this->Form->create(null, [
            "url" => array("controller" => "products", "action" => "order", $categoryId, $customersBasketId),
            "id" => "teachers-kits-frm",
            "name" => "teacher-kits-frm",
            "enctype" => "multipart/form-data",
            "escape" => false,
            "ng-submit" => 'submitOrder($event, \'#teachers-kits-frm\', \'' . $product['Product']['products_id'] . '\', \'' . (($categoryId != 're-order' && $categoryId != 'update') ? 'false' : 'true') . '\')'
        ]);
        ?>

        <!-- Hidden input fields -->
        <?= $this->Form->hidden("CustomersBasket.products_id", array("value" => $product["Product"]["products_id"])); ?>
        <div ng-if="hasData">
            <?= $this->Form->hidden("CustomersBasket.customers_basket_date_added", array("value" => date("Ymd"))); ?>
            <?= $this->Form->hidden("CustomersBasket.website", array("value" => "wwww.boostpromotions.com")); ?>

            <input type="hidden" name="data[CustomersBasket][customers_basket_quantity]" value="1"> <!-- dont change because the model=25 will be really tricky -->
            <input type="hidden" name="data[CustomersBasket][title]" value='[[v2_proof:on]]' /> <!-- all new orders are individual -->
            <input type="hidden" name="data[CustomersBasket][customs]" value="{{ stringData}}">
        </div>
        <div class="row top-20">
            <div class="col-md-7">
                <div class="form-group">                                                
                    <label>Teacher's name required for personalization on all tags. <br />
                        <small>Enter teacher's name</small></label>
                    <?=
                    $this->Form->input("CustomersBasket.footer", array(
                        "type" => "text",
                        "class" => "form-control",
                        "label" => false,
                        "ng-required" => true
                    ));
                    ?>                                                    
                </div>
            </div>
        </div>                
        <hr />                                    
        <div class="row">
            <div ng-repeat="model in selectedModels" class="col-md-4 col-sm-6">
                <div class="row">
                    <div class="col-xs-6">
                        <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ model.productModel | getImgDir }}/{{ model.productImage}}" class="img-responsive img-180">
                    </div>
                    <div class="pull-left">
                        Model: <b><span ng-bind="model.productModel | uppercase"></span></b>
                        <span ng-if="model.shape"><br/>Shape: <b><span ng-bind="model.shape"></span></b></span>
                        <p>Order: <b><span ng-bind="model.orderQty + 'pcs.'"></span></b></p>

                        <button class="btn btn-sm btn-danger" type="button" 
                                ng-click="removeItem(selectedModels, model.productId, '<?= ($categoryId == 'update' || $categoryId == 're-order' ) ? 'update' : 'open-stock'; ?>')">Remove</button>
                    </div>
                </div>
            </div>
        </div>

        <div ng-hide="selectedModels" >
            <div class="col-md-4 col-md-offset-4 text-center top-20">
                <i class="fa fa-cog  fa-spin fa-fw fa-3x"></i>
                <p class="top-5"><b>Loading...</b></p>
            </div>
        </div>

        <div ng-show="!hasData" class=" alert alert-info" >
            <p><b>You have not selected any designs. Please choose from our existing designs by clicking the button above.</b></p>
        </div>

        <div class="alert alert-warning" ng-if="tkExtraMessage && hasData">
            {{ tkExtraMessage}}                                        
        </div>
    </div>

    <!-- Submit Button -->
    <div class="col-md-12 bottom-20 top-20">

        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table table-condensed table-bordered">
                    <tr>
                        <th>TOTAL QTY</th>
                        <th><span ng-bind="totalQty + 'pcs.'"></span></th>
                    </tr>
                </table>
            </div>
        </div>

        <div class="pull-right">
            <button ng-hide="!hasData"  type="submit" class="btn btn-success btn-lg" ng-disabled="!hasData">
                <i class="fa fa-cart-plus"></i> &nbsp;
                <?= ($categoryId == "update") ? "Update Order" : "Add To Cart"; ?>
            </button>

            <button type="button" ng-click="orderIsEmpty($event)" class="btn btn-success btn-lg" ng-show="!hasData">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <?= ($categoryId == "update") ? "Update Order" : "Add To Cart"; ?>
            </button>
        </div>
    </div>
    <?= $this->Form->end(); ?>
<?php else:
    ?>                              
    <?=
    $this->Form->create(null, array(
        "url" => array("controller" => "products", "action" => "order", $categoryId, $customersBasketId),
        "id" => "teachers-kits-frm",
        "name" => "teacher-kits-frm",
        "enctype" => "multipart/form-data"
    ));
    ?>

    <?= $this->Form->hidden("CustomersBasket.products_id", array("value" => $product["Product"]["products_id"])); ?>
    <?= $this->Form->hidden("CustomersBasket.customers_basket_date_added", array("value" => date("Ymd"))); ?>
    <?= $this->Form->hidden("CustomersBasket.website", array("value" => "wwww.boostpromotions.com")); ?>


    <div class="col-md-8">

        <?php
        if ($product['Product']['require_artwork'] == 1):
            if (in_array($product['Product']['products_id'], [990, 853])):
                ?>

                <div class="form-group">
                    <label>
                        Lanyard Color<br />
                    <!--<small class="text-dark-gray">Describe how you would like the background changed.</small>-->
                    </label>
                    <?=
                    $this->Form->input("CustomersBasket.background", array(
                        "type" => "text",
                        "class" => "form-control",
                        "label" => false
                    ));
                    ?>
                    <br/>
                    <label>
                        Text Color<br />
            <!--<small class="text-dark-gray">Tell us how you would like the Title to read.</small>-->
                    </label>
                    <?=
                    $this->Form->input("CustomersBasket.title", array(
                        "type" => "text",
                        "class" => "form-control",
                        "label" => false
                    ));
                    ?>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label>
                    Description<br />
                    <small class="text-dark-gray">Tell us how you would like this to look like</small>
                </label>
                <?=
                $this->Form->input("CustomersBasket.footer", array(
                    "type" => "textarea",
                    "class" => "form-control",
                    "rows" => 2,
                    "label" => false
                ));
                ?>
            </div>

            <div class="form-group">
                <?php if (isset($uploadedImage)): ?>
                    <b>Uploaded Image</b><br />
                    <?php
                    $path = explode("/", $uploadedImage);
                    $imgName = end($path);
                    $label = "Replace Image";
                    ?>
                    <img class="img-responsive" style="max-height:200px"  src="<?= 'https://boostpromotions.com/images/uploads/' . $uploadedImage ?>" />
                    <?= $this->Html->link($imgName, 'https://boostpromotions.com/images/uploads/' . $uploadedImage, ['target' => '_blank']); ?>
                <?php endif; ?>
                <?= $this->Form->hidden("CustomersBasket.upload"); ?>

                <label class="top-20">
                    <?= (isset($imgName)) ? $label : "Upload Image"; ?>
                    <br/>
                    <small class="text-dark-gray">the image to be used on the lanyard.</small>
                </label>

                <div class="row">
                    <div class="col-md-6">
                        <?=
                        $this->Form->input("CustomersBasket.imageFile.", array(
                            "type" => "file",
                            "class" => "top-5",
                            "label" => false,
                            "required" => false,
                            "id" => "modified_img_frm",
                            "accept" => "image/*, .docx, .doc, .psd"
                        ));
                        ?>
                    </div>

                    <div class="col-md-6">
                        <button ng-click="removeFile('#modified_img_frm')"  style="margin-left: 10px;" type="button" class="btn btn-danger btn-sm pull-right">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <?php
        elseif (in_array($product['Product']['products_id'], [4482, 4520, 4483])):
            $options = ['RED' => 'RED', 'BLUE' => 'BLUE', 'BLACK' => 'BLACK'];
            switch ($product['Product']['products_id']) {
                case 4482: $options = $options + ['GRAY' => 'GRAY'];
                case 4520: $options = $options + ['WHITE' => 'WHITE'];
                    break;
            }
            ?>
            <div class="form-group">
                <label>
                    Color<br />
                    <small class="text-dark-gray">Choose from available colors.</small>
                </label>
                <?=
                $this->Form->input("CustomersBasket.background", array(
                    "empty" => '-Select-',
                    "options" => $options,
                    "min" => $product['Product']['products_quantity_order_min'],
                    "class" => "form-control",
                    "label" => false,
                    "required"
                ));
                ?>
            </div>
        <?php endif;
        ?>

        <div class="form-group">
            <label>
                Enter Order Quantity<br />
                <small>(Minimum order <?= $product['Product']['products_quantity_order_min'] ?>pcs.)</small>
            </label>
            <?=
            $this->Form->input("CustomersBasket.customers_basket_quantity", array(
                "type" => "number",
                "min" => $product['Product']['products_quantity_order_min'],
                "class" => "form-control",
                "label" => false,
                "required"
            ));
            ?>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fa fa-cart-plus"></i>
                <?= ($categoryId == "update") ? "Update Order" : "Add To Cart"; ?>
            </button>
        </div>
    </div>



    <?= $this->Form->end(); ?>                           
<?php endif; ?>
</div> 