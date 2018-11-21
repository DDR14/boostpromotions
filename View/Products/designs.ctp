<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('Design Library', '/products/designs'); ?></li>
            <li class="active"><?= $designs[0]['Category']['categories_name']; ?></li>
        </ul>
        <?php
        if ($categoryId != "All Designs") {
            $this->assign('title', $designs[0]['Category']['categories_name'] . " Designs — For any type of tag at Boost Promotions");
        } else {
            $this->assign('title', "Tag Designs & Templates —  Use Free at Boost Promotions");
        }
        foreach ($designs as $category):
            ?>
            <div class="page-header">
                <h1><?= $category['Category']['categories_name'] ?></h1>
                <p><?= strtoupper($category['Category']['categories_description']) ?></p>
            </div>
            <div class="row">
                <?php foreach ($category['Designs'] as $item): ?>
                    <div class="col-md-2 col-xs-3 col-sm-4 no-padding tags">
                        <a ng-click="viewTag(<?= $item['Design']['products_id'] ?>, $event)" href="<?= $this->webroot; ?>products/viewDesign/<?= $item['Design']['products_id'] ?>">
                            <img ng-src="https://boostpromotions.com/images/designs/Resize/<?= $this->Custom->getImgDir($item['Design']['products_model']) ?>/<?= $item['Design']['products_image'] ?>" 
                                 alt="<?= $item['Design']['design_name']; ?>"
                                 class="img-200 img-responsive">

                            <p class="text-center text-white">
                                <b><?= $item['Design']['products_model'] ?></b>
                            </p>
                        </a>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($category['Designs'])): ?>
                    <div class="col-md-12">
                        <h3 class="text-warning">No Available Designs.</h3>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<!-- Pop-up design for easy addition instead of going to load another page, 
but keep the view pages in case someone shares the designs -->
<div class="modal" id="view-tag">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Add Design to Favorites</h5>
            </div>
            <div class="modal-body">
                <!-- Loading Spinner -->
                <div class="col-md-6 col-md-offset-3 text-center top-20" ng-show="showLoading">
                    <i class="fa fa-spinner  fa-pulse fa-fw fa-3x"></i>
                    <p class="top-5"><b>Loading Data Please Wait...</b></p>
                </div>
                <div ng-hide="showLoading">
                    <div class="row">
                        <div class="col-md-3">
                            <img ng-src="https://boostpromotions.com/images/designs/Resize/{{ design.Design.products_model | getImgDir }}/{{ design.Design.products_image}}" class="img-responsive" />
                        </div>

                        <div class="col-md-9">
                            <h2 ng-bind="design.ProductsDescription.products_name"></h2>
                            <p ng-bind="design.ProductsDescription.products_description"></p>                       
                            <button ng-hide="added" class="btn btn-primary top-20" 
                                    ng-click="addToFavorites(design.Design, design.Category.parent_id)">Add To Favorites</button>

                            <p ng-show="added" class="text-success">
                                <b><i class="fa fa-check"></i></b>
                                Aleady added to favorites
                            </p>
                            <p>
                                <em class="text-success">Favorite designs are retrievable when ordering tags.</em>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <a href="<?= $this->webroot . 'products'; ?>" class="btn btn-info" >Go to Products</a>
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
            </div>
        </div>
    </div>
</div>