<?php $this->assign('title',  $design['Design']['products_model'] . " - Add to Favorites | Boost Promotions"); ?>
<section ng-init='tmpData = {
			"products_id" : <?= $design['Design']['products_id']; ?>,
			"products_model" : "<?= $design['Design']['products_model']; ?>",
			"products_image" : "<?= $design['Design']['products_image']; ?>",
			"master_categories_id" : <?= $design['Design']['master_categories_id']; ?>,
			"parentId" : <?= $design['Category'][0]['parent_id'] ?>,
		};'>
    <div class="container">
        <div class="page-header">
            <h1><?= strtoupper($design['Design']['products_model']) ?></h1>
        </div>

        <div class="row bottom-60">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <img src="https://boostpromotions.com/images/designs/Resize/<?= $this->Custom->getImgDir($design['Design']['products_model']); ?>/<?= $design['Design']['products_image'] ?>" class="img-responsive" />
                    </div>

                    <div class="col-md-9">
                        <h2><?= $design['ProductsDescription']['products_name'] ?></h2>
                        <p><?= $design['ProductsDescription']['products_description'] ?></p>                       
                        <button ng-hide="added" class="btn btn-primary top-20" 
                                ng-click="addToFavorites(tmpData, tmpData.parentId)">Add To Favorites</button>

                        <p ng-show="added" class="text-success">
                            <b><i class="fa fa-check"></i></b>
                            Aleady added to favorites
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
