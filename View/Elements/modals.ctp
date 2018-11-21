<?php $this->start("modalBoxes"); ?>
<?php if (!AuthComponent::user('customers_id')): ?>  
    <div class="modal fade" id="login-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header teal-bg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <div class="col-md-2">
                        <?= $this->Html->link($this->Html->image("logo-white.png", array("class" => "img-responsive", "alt" => "Boost Promotions")), "/", array("escape" => false)) ?>
                    </div>
                </div>
                <div class="modal-body light-blue-bg">
                    <div class="row">
                        <!-- Login Form  -->
                        <div class="col-md-5 bordered-left">
                            <h4>
                                <i class="fa fa-lock"></i> &nbsp; Login to Boost Promotions
                            </h4>
                            <hr />

                            <?=
                            $this->Form->create(null, array(
                                "url" => array("controller" => "customers", "action" => "login")
                            ));
                            ?>

                            <div class="form-group">
                                <?=
                                $this->Form->input("Customer.customers_email_address", array(
                                    "type" => "email",
                                    "class" => "form-control",
                                    "placeholder" => "Email address",
                                    "label" => "Email Address",
                                    "required"
                                ));
                                ?>
                            </div>

                            <div class="form-group">
                                <?=
                                $this->Form->input("Customer.customers_password", array(
                                    "type" => "password",
                                    "class" => "form-control",
                                    "placeholder" => "Password",
                                    "label" => "Password",
                                    "required"
                                ));
                                ?>

                            </div>

                            <div class="form-group">
                                <label>
                                    <?= $this->Form->checkbox("Misc.remember_me"); ?>&nbsp; Keep me logged in.
                                </label>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-block btn-primary">
                                    Login
                                </button>

                                <br />
                                <?= $this->Html->link("Forgot password?", "/customers/forgotPassword"); ?>
                            </div>

                            <?= $this->Form->end(); ?>
                        </div>

                        <!-- Registration Form -->
                        <div class="col-md-7">
                            <h4>
                                New Customer
                            </h4>
                            <hr />

                            <div class="alert alert-info">
                                <h5 class="text-black">Create your customer Profile to get started.</h5>
                                <div class="row top-20">
                                    <div class="col-md-6 col-md-offset-6">
                                        <?=
                                        $this->Html->link("Register", "/customers/register", array(
                                            "class" => "btn btn-block btn-success"
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-default pull-right top-20" data-dismiss="modal">
                                Close
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Products Selection Modal Box -->
<div class="modal fade" id="products-category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Loading Spinner -->
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 text-center top-20" ng-show="showLoading">
                        <i class="fa fa-spinner  fa-pulse fa-fw fa-3x"></i>
                        <p class="top-5"><b>Loading...</b></p>
                    </div>
                </div>

                <!-- Items List -->
                <div class="row" ng-show="productsList">
                    <div ng-hide="showLoading">
                        <div class="modal-header" ng-hide="itemDetails">
                            <div ng-hide="searchQuery">
                                <!-- Back to sub categories button -->
                                <button ng-show="subCatItems" ng-hide="noBack" ng-click="backToSubCategories()" class="btn btn-default btn-sm pull-left">
                                    <i class="fa fa-chevron-left"></i>&nbsp; Back
                                </button>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                <h4>
                                    &nbsp; &nbsp; {{ headerText | uppercase }}
                                </h4>
                            </div>

                            <div ng-show="searchQuery">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4>
                                    <i class="fa fa-search"></i>
                                    Search
                                </h4>
                            </div>
                        </div>

                        <div ng-hide="itemDetails">
                            <!-- Search Box -->
                            <div class="col-md-12">
                                <div class="row bottom-20">
                                    <div class="col-md-6">
                                        <label>Search Tag Designs</label>
                                        <input type="search" placeholder="Enter Keyword(s)" ng-model="searchQuery" class="form-control input-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <button ng-hide="allItems" ng-click="viewAllDesigns()" class="pull-right btn top-25 btn-success btn-sm">
                                            VIEW ALL DESIGNS
                                        </button>

                                        <button ng-show="allItems" ng-click="backToSubCategories()" class="btn btn-success btn-sm top-25 pull-right">
                                            VIEW ALL CATEGORIES
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div ng-hide="searchQuery">
                                <!-- Sub Categories -->
                                <div ng-hide="subCategories" class="row">
                                    <div class="col-md-4 col-md-offset-4 text-center top-20">
                                        <i class="fa fa-cog  fa-spin fa-fw fa-3x"></i>
                                        <p class="top-5"><b>Loading...</b></p>
                                    </div>
                                </div>

                                <div ng-hide="subCatItems" ng-repeat="item in subCategories">
                                    <div class="col-md-3">
                                        <div ng-repeat="cat in item">
                                            <a href="" class="" ng-click="loadItems(cat.categories_id, $event)">
                                                <b>{{cat.CategoriesDescription.categories_name| uppercase }}</b>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Sub Categories Items -->
                                <div ng-show="subCatItems && !allItems" ng-repeat="item in items.Design" class="col-md-2 col-xs-3 col-sm-3 no-padding " id="design_{{ items.Category.categories_id }}">
                                    <a  href="" class="thumbnail" ng-click="viewItem(item, item.Category[0].parent_id)">
                                        <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ item.Design.products_model | getImgDir }}/{{ item.Design.products_image }}" class="img-tag img-responsive" />

                                        <p class="text-center"><b>{{ item.Design.products_model | removeTrailing01 }}</b></p>
                                    </a>
                                </div>

                                <div ng-show="items.length == 0 && subCatItems && !allItems" class="col-md-12">
                                    <p><b class="text-warning">No Avaialble Design.</b></p>
                                </div>


                                <!-- All designs list -->
                                <div ng-show="allItems && subCatItems">

                                    <div ng-repeat="category in AllTagData">
                                        <div class="col-md-12">
                                            <h5><b>{{category.category}}</b></h5>
                                            <hr />
                                        </div>

                                        <div class="col-md-2 col-xs-3 col-sm-3 no-padding" ng-repeat="item in category.item" id="{{ item.Design.products_id}}">
                                            <a href="" class="thumbnail" ng-click="viewItem(item, item.Category[0].parent_id, item.Category[0].categories_image)">
                                                <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ item.Design.products_model | getImgDir }}/{{ item.Design.products_image}}"  class="img-120" />
                                                <p class="text-center"><b>{{ item.Design.products_model}}</b></p>
                                            </a>
                                        </div>

                                        <!-- Show when there is no design in the category -->
                                        <div class="col-md-12" ng-if="category.item.length == 0">
                                            <p><b class="text-warning">No Avaialble Design.</b></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search results -->
                <div ng-hide="searchQuery == null || searchQuery == ''">
                    <h5><i class="fa fa-search"></i> Search Results</h5>
                    <hr />

                    <div class="item-list">
                        <ul>
                            <li dir-paginate="item in searchData | filter:searchQuery | itemsPerPage: 20 " id="{{ item.Design.products_id}}">
                                <div>
                                    <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ item.Design.products_model | getImgDir }}/{{ item.Design.products_image}}" imageonload >

                                    <h3>{{ item.Design.products_model | uppercase | removeTrailing01 }}</h3>
                                    <p>{{ item.Design.design_name | uppercase }}</p>
                                    <a href="" class="btn btn-success btn-xs" ng-click="viewItem(item, item.Category[0].parent_id, item.Category[0].categories_image)">
                                        <i class="fa fa-cart-plus"></i> &nbsp;
                                        Select
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <dir-pagination-controls></dir-pagination-controls>
                </div>

                <!-- Item Details -->
                <div ng-show="itemDetails && !showLoading">
                    <div class="row">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <button ng-hide="noBackSubCat" ng-click="backToSubCategoriesItem()" class="btn btn-default btn-sm pull-left">
                                <i class="fa fa-chevron-left"></i>&nbsp; Back
                            </button>

                            <h4>
                                &nbsp; &nbsp; Product Details
                            </h4>
                        </div>

                        <div class="col-md-6 col-md-offset-3">

                            <div class="alert alert-danger" ng-show="notDivisble">
                                <p><b>Order QTY. should be divsible by 25.</b></p>
                            </div>

                            <div class="text-center">
                                <img ng-src="https://boostpromotions.com/images/designs/{{ itemData.Design.products_model | getImgDir }}/{{ itemData.Design.products_image}}" class="img-responsive center-block img-preview">
                                <p class="text-white top-10"><b>MODEL: {{ itemData.Design.products_model | uppercase | removeTrailing01 }}</b></p>

                                <!-- Open Stock form  -->
                                <form ng-if="(productType === 'open-stock' || productType === 'open-stock-update') && !teachersKits" ng-submit="selectItem(itemData, qty, productType, subFolder)">
                                    <label>
                                        <small class="text-warning">( You can only order in quantities of 25.)</small>
                                    </label><br />

                                    <div class="col-md-8 col-md-offset-2">
                                        <div ng-hide="added" class="input-group">
                                            <input  type="number" step="25" ng-model="qty" min="25" class="form-control input-sm" required/>

                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-primary" ng-disabled="added">
                                                    <span ng-hide="added">Add</span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <span ng-show="added" class="text-success" >
                                        <i class="fa fa-check"></i> &nbsp;
                                        Design Added
                                    </span>
                                </form>

                                <!-- modified tag form -->
                                <form  ng-if="productType == 'modified-tag' || productType == 'update-modified-tag'"  ng-submit="selectItem(itemData, qty, productType)">
                                    <div class="col-md-10 col-md-offset-1">
                                        <label>
                                            <small class="text-warning">( Minimum Order is 25pcs.)</small>
                                        </label>

                                        <div ng-hide="added" class="input-group">
                                            <input type="number" min="25" ng-model="qty" class="form-control input-sm" required />

                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-primary" ng-disabled="added">
                                                    <span ng-hide="added">Add</span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <span ng-show="added" class="text-success" >
                                        <i class="fa fa-check"></i> &nbsp;
                                        Design Added
                                    </span>
                                </form>

                                <!-- Teacherkits form -->
                                <form ng-if="teachersKits && qty" class="form-inline" ng-submit="selectItem(itemData, qty, productType)">
                                    <div class="center-block">
                                        <div ng-hide="added">
                                            <input style="width:30%" ng-if="teachersKits && qty" type="number" ng-model="qty" class="form-control input-sm" disabled />

                                            <select ng-if="[879, 880, 881].indexOf(teachersKits) !== - 1" 
                                                    ng-options="sh.shape as (sh.shape + ' (' + sh.left + ')')  disable when sh.left === 0 for sh in shapes"
                                                    required ng-model="itemData.shape" class="input-sm form-control">
                                                <option value="" >-Shape-</option>
                                            </select>

                                            <button class="btn btn-sm btn-primary" ng-disabled="added">
                                                <span ng-hide="added">Add</span>
                                            </button>
                                        </div>
                                    </div>
                                    <span ng-show="added" class="text-success" >
                                        <i class="fa fa-check"></i> &nbsp;
                                        Design Added
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" ng-hide="showLoading">
                <button class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!--  Edit Product Quantity Modal Box -->
<div class="modal fade center-modal" id="edit-qty">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Quantity</h4>
            </div>

            <div class="modal-body">
                <p><b>Model: {{ itemData.productModel | uppercase }}</b></p>
                <p ng-show="notDivisble">
                    <b class="text-danger">
                        Order quantity should be divisible by 25.
                    </b>
                </p>

                <!-- Show only if the product type is not modified tags -->
                <div class="form-group" ng-if="productType != 'modified-tag' && productType != 'update-modified-tag'">
                    <form ng-submit="updateQty(allData, itemData, qty, productType)">
                        <label>
                            Enter Order Quantity<br />
                            <small class="text-danger">( You can only order in quantities of 25.)</small>
                        </label>
                        <input type="number" min="25" step="25" ng-model="qty" class="form-control" required />

                        <div class="top-20">
                            <button type="submit" class="btn btn-primary btn-block">
                                Edit Quantity
                            </button>
                            <button type="button" data-dismiss="modal" class="btn btn-default btn-block">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Show only if the product type is modified-tag -->
                <div class="form-group" ng-if="productType == 'modified-tag' || productType == 'update-modified-tag'">
                    <form ng-submit="updateQty(allData, itemData, qty, productType)">
                        <label>
                            Enter Order Quantity<br />
                            <small class="text-danger">( Minimun order is 25pcs.)</small>
                        </label>
                        <input type="number" min="25" ng-model="qty" class="form-control" required />

                        <div class="top-20">
                            <button type="submit" class="btn btn-primary btn-block">
                                Edit Quantity
                            </button>
                            <button type="button" data-dismiss="modal" class="btn btn-default btn-block">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- favorite tags list modal -->
<div class="modal fade" id="favorites-modal">
    <div class="modal-dialog">
        <div class="modal-content">             
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div ng-hide="itemDetails">
                    <h4 class="modal-title">Favorite Tags</h4>
                </div>

                <div ng-show="itemDetails">
                    <button ng-click="backToSubCategoriesItem()" class="btn btn-default btn-sm pull-left">
                        <i class="fa fa-chevron-left"></i>&nbsp; Back
                    </button>

                    <h4 class="modal-title">
                        &nbsp; &nbsp; Product Details
                    </h4>
                </div>
            </div>
            <div class="modal-body">
                <div class="row" ng-hide="itemDetails">
                    <!-- Favorite items list -->
                    <div ng-repeat="fav in favorites" class="col-md-2 col-xs-3 col-sm-3 padd-5">
                        <a href="" class="thumbnail" ng-click="viewItem(fav, fav.productCategoryParentId)">
                            <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ fav.Design.products_model | getImgDir }}/{{ fav.Design.products_image}}" class="img-responsive" />
                            <p class="text-center"><b>{{ fav.Design.products_model}}</b></p>
                        </a>
                    </div>
                </div>

                <!-- Item Details -->
                <div ng-show="itemDetails">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">

                            <div class="alert alert-danger" ng-show="notDivisble">
                                <p><b>Order QTY. should be divsible by 25.</b></p>
                            </div>

                            <div class="text-center">
                                <img ng-src="https://boostpromotions.com/images/designs/{{ itemData.Design.products_model | getImgDir }}/{{ itemData.Design.products_image}}" class="img-responsive center-block">

                                <p><b>MODEL: {{ itemData.Design.products_model | uppercase }}</b></p>

                                <!-- Stock Tag / TeacherKits Form -->
                                <form class="form-inline" ng-submit="selectItem(itemData, qty, productType)">
                                    <div class="center-block">
                                        <div ng-hide="added">
                                            <input style="width:30%" ng-if="teachersKits && qty && teachersKits !== 888" type="number" ng-model="qty" class="form-control input-sm" disabled />

                                            <select ng-if="[879, 880, 881].indexOf(teachersKits) !== - 1" 
                                                    ng-options="sh.shape as (sh.shape + ' (' + sh.left + ')')  disable when sh.left === 0 for sh in shapes"
                                                    required ng-model="itemData.shape" class="input-sm form-control">
                                                <option value="" >-Shape-</option>
                                            </select>

                                            <span ng-if="(productType === 'open-stock' || productType === 'open-stock-update') && !teachersKits" >
                                                <label>
                                                    <small class="text-warning">( You can only order in quantities of 25.)</small>
                                                </label>
                                                <input  type="number" step="25" ng-model="$parent.qty" min="25" class="form-control input-sm" required/>
                                            </span>

                                            <span ng-if="productType === 'modified-tag' || productType === 'update-modified-tag'" >
                                                <label>
                                                    <small class="text-warning">( Minimum Order is 25pcs.)</small>
                                                </label>
                                                <input  type="number" ng-model="$parent.qty" min="25" class="form-control input-sm" required/>
                                            </span>

                                            <button class="btn btn-sm btn-primary" ng-disabled="added">
                                                <span ng-hide="added">Add</span>
                                            </button>
                                        </div>
                                    </div>                                   
                                    <span ng-show="added" class="text-success" >
                                        <i class="fa fa-check"></i> &nbsp;
                                        Design Added
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Models list modal box -->
<div class="modal fade" id="model-list">
    <div class="modal-dialog moda-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Selected Tag Designs</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 no-padding" ng-repeat="model in selectedModels" ng-hide="loading">
                        <div class="thumbnail">

                            <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ model.Design.products_model | getImgDir }}/{{ model.Design.products_image}}" class="img-tag"/>

                            <p class="text-center top-5">
                                {{ model.Design.products_model}}
                                <b ng-if="model.Design.shape"><br/> {{ model.Design.shape}}</b>
                                <br />
                                <span>{{ model.Design.orderQty}} pcs.</span>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6" ng-show="loading">
                        <p><b>
                                <i class="fa fa-spinner fa-pulse"></i>&nbsp; Loading Data Please Wait...
                            </b></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?= $this->webroot; ?>products/order/update/{{ basketId}}" class="btn btn-primary btn-sm">
                    UPDATE
                </a>
                <button class="btn btn-default btn-sm" data-dismiss="modal">
                    CLOSE
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->end(); ?>

<?php $this->start("notifModal"); ?>
<!-- Notification modal box -->
<div class="modal fade center-modal" id="notif-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p class="modal-title"><i class="fa fa-2x fa-info-circle"></i> &nbsp; Notification Message</p>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <p ng-bind-html="skipHtmlValidation(notifMessage)"></p>
                </div>

                <button class="btn btn-primary btn-block top-5" data-dismiss="modal">
                    DISMISS
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal center-modal" id="loading-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-spinner fa-pulse fa-3x"></i>
                <p><b>Loading Please Wait...</b></p>
            </div>
        </div>
    </div>
</div>
<?php $this->end(); ?>
