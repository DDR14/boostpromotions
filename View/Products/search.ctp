<?php $this->assign('title', "Customizeable Student Rewards - Order Online - Boost Promotions"); ?>
<section>
    <div class="container">
        <div class="page-header">
            <h1><i class="fa fa-search"></i> Search Results</h1>
        </div>

        <?php if (!empty($products)): ?>
            <div class="item-list col-md-12">
                <h2><?= count($products); ?> Product Result(s)</h2>
                <ul>
                    <?php foreach ($products as $product): ?>
                        <li>
                            <img ng-src="https://boostpromotions.com/images/<?= $product['Product']['products_image']; ?>" />

                            <h3><?= strtoupper($product["Product"]["products_model"]) ?></h3>
                            <p><?= $product['ProductsDescription']['products_name'] ?></p>

                            <?=
                            $this->Html->link('View Product', '/products/order/' . $product['Product']['products_id'], [
                                'class' => 'btn btn-success btn-sm'
                            ]);
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="item-list col-md-12">
            <h2><?= count($designs); ?> Design Result(s)</h2>
            <ul>
                <?php foreach ($designs as $design): ?>
                    <li><div class="iteml">
                        <?php if ($design['DiscountedDesign']['dd_new_products_price']): ?>
                            <div class="ribbon">
                                <span><?= number_format($design['DiscountedDesign']['dd_new_products_price']); ?>% OFF</span>
                            </div>
                        <?php endif; ?>
                        <img ng-src="https://boostpromotions.com/images/designs/Resize/<?= $this->Custom->getImgDir($design['Design']['products_model']); ?>/<?= $design['Design']['products_image'] ?>" />

                        <h3><?= strtoupper($design["Design"]["products_model"]) ?></h3>
                        <p><?= $design['Design']['design_name'] ?></p>

                        <?=
                        $this->Html->link('View Tag Design', '/designs/view/' . $design['Design']['products_id'], [
                            'class' => 'btn btn-success btn-sm'
                        ]);
                        ?>
                    </div></li>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if (empty($designs)): ?>
                <h3><b>No Design Search Results</b></h3>
            <?php endif; ?>
        </div>
    </div>
</section>
