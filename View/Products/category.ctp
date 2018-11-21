<?php 
$mcategory = $mainCategory["CategoriesDescription"]["categories_name"];
$this->assign('title', $mcategory . " — Boost Promotions"); ?>
<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('All Products', '/products'); ?></li>
            <li class="active"><?= $mcategory; ?></li>
        </ul>
        <div class="page-header">
            <h1><?= $mainCategory["CategoriesDescription"]["categories_name"] ?></h1>
        </div>

        <div class="row">
            <?php
            $ctr = 1; //Only Custom Key Fob Uses this
            $col = 4;
            if (count($subCategories) > 3) {
                $col = 3;
            }

            foreach ($subCategories as $category):
                if ($category['ChildCategory']):
                    $price = $category['ChildCategory'][0]['Product'][0]['ProductsDiscountQuantity'][0]['discount_price'];
                    $link = $this->webroot . 'products/order/' . $category['Category']['categories_id'];
                else:
                    $price = $category['Product'][0]['ProductsDiscountQuantity'][0]['discount_price'];
                    $link = $this->webroot . 'products/order/' . $category['Product'][0]['products_id'];
                    if (count($category['Product']) > 1) {
                        $link = $this->webroot . 'products/accessories/' . $category['Category']['categories_id'];
                    }
                endif;
                ?>
                <div class="catCard col-md-<?= $col; ?> col-sm-6">
                    <a href="<?= $link; ?>" class="thumbnail bg<?= $ctr; ?> text-center">
                        <h3><?= $category['CategoriesDescription']['categories_name'] ?></h3>
                        <img src="https://boostpromotions.com/images/<?= $category['Category']['categories_image'] ?>" class="img-responsive with-price">  
                        <div class="starburst fa-stack fa-4x fa-lg">
                            <i class="fa fa-starburst back fa-stack-2x"></i>
                            <i class="fa fa-starburst front fa-stack-2x"></i>
                            <div class="fa fa-stack-1x text">
                                STARTING AT
                            </div>
                            <div class="fa fa-stack-1x number">
                                    <?php
                                    if ($price > 1) {
                                        $price_text = number_format($price, 2);
                                        echo "<div style='font-size:40px;line-height:3;'><small>$</small>$price_text </div>";
                                    } else {
                                        echo $price * 100 . '<small>¢</small>';
                                    }
                                    ?>
                            </div>
                        </div>      
                    </a>
                </div>
                <?php
                $ctr++;
            endforeach;
            ?>
        </div>
    </div>
</section>