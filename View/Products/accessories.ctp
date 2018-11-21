<?php
$mcategory = $accessories[0]["Category"][0]["CategoriesDescription"]["categories_name"];
$this->assign('title', $accessories[0]["Category"][0]["CategoriesDescription"]["categories_name"] . " — Boost Promotions");
?>
<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('All Products', '/products'); ?></li>
            <?= (!in_array($accessories[0]['Product']['master_categories_id'], [519, 101])) ? '<li>' . $this->Html->link('Accessories', '/products/category/520') . '</li>' : ''; ?>
            <li class="active"><?= $mcategory; ?></li>
        </ul>
        <div class="page-header">
            <h1><?= $accessories[0]["Category"][0]["CategoriesDescription"]["categories_name"] ?></h1>
        </div>

        <?php if ($accessories[0]["Product"]["master_categories_id"] == 101): ?>
            <p>We know that each classroom is different. Therefore, we think each teacher should be able to customize their own kit.</p>
            <h2>Choose from our 2 *Build Your Own* Kits</h2>
            <p class="text-center lead">For a kit that best matches your classroom size. choose from our following 3 kits sizes</p>
            <div class="row">
                <div class="col-md-6" >
                    <?= $this->Html->image('custom-tk.jpg', ['class' => 'img-responsive']); ?>
                    <ul class="list-group">
                        <li class="list-group-item clearfix">250 Packs 25 each of 10 designs - <strong class="text-danger">$40</strong> <a href="<?= $this->webroot . "products/order/879" ?>" class="btn btn-xs pull-right btn-primary">Order Now</a></li>
                        <li class="list-group-item clearfix">300 Packs 30 each of 10 designs - <strong class="text-danger">$48</strong> <a href="<?= $this->webroot . "products/order/880" ?>" class="btn btn-xs pull-right btn-primary">Order Now</a></li>
                        <li class="list-group-item clearfix">350 Packs 35 each of 10 designs - <strong class="text-danger">$56</strong> <a href="<?= $this->webroot . "products/order/881" ?>" class="btn btn-xs pull-right btn-primary">Order Now</a></li>
                    </ul>
                    <p class="text-center">* Must be 10 different stock design. No Customization, No duplicates. <br> Teacher's name must be personalized on all the tags.</p>
                </div>
                <div class="col-md-6" >
                    <?= $this->Html->image('dog-tk.jpg', ['class' => 'img-responsive']); ?>
                    <ul class="list-group">
                        <li class="list-group-item clearfix">250 Packs 25 each of 10 designs - <strong class="text-danger">$38</strong> <a href="<?= $this->webroot . "products/order/876" ?>" class="btn btn-xs pull-right btn-primary">Order Now</a></li>
                        <li class="list-group-item clearfix">300 Packs 30 each of 10 designs - <strong class="text-danger">$45</strong> <a href="<?= $this->webroot . "products/order/877" ?>" class="btn btn-xs pull-right btn-primary">Order Now</a></li>
                        <li class="list-group-item clearfix">350 Packs 35 each of 10 designs - <strong class="text-danger">$53</strong> <a href="<?= $this->webroot . "products/order/878" ?>" class="btn btn-xs pull-right btn-primary">Order Now</a></li>
                    </ul>
                    <p class="text-center">* Must be 10 different stock design. No Customization, No duplicates. <br> Teacher's name must be personalized on all the tags.</p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3"></div>
                <div class=" col-md-6">
                    <?= $this->Html->image('extra-tk.jpg', ['class' => 'img-responsive']); ?>
                </div>
                <div class="col-md-3"></div>
            </div>
            <?php
        else:

            $ctr = 0; //color
            foreach ($accessories as $item):
                $ctr++;
                $price = $item['ProductsDiscountQuantity'][count($item['ProductsDiscountQuantity']) - 1]['discount_price'];
                ?>
                <div class="catCard col-md-4 col-sm-6">
                    <a href="<?= $this->webroot ?>products/order/<?= $item['Product']['products_id'] ?>" class="thumbnail bg<?= $ctr; ?> text-center">
                        <h3 style="height:64px"><?= $item['ProductsDescription']['products_name'] ?></h3>
                        <img src="https://boostpromotions.com/images/<?= $item['Product']['products_image'] ?>" class="img-responsive with-price">                  
                        <div class="starburst fa-stack fa-4x fa-lg">
                            <i class="fa fa-starburst back fa-stack-2x"></i>
                            <i class="fa fa-starburst front fa-stack-2x"></i>
                            <div class="fa fa-stack-1x text">
                                STARTING AT
                            </div>
                            <div class="fa fa-stack-1x number">
                                <?php
                                if ($price < 1) {
                                    echo $price * 100 . '<small>¢</small>';
                                } else {
                                    $price_text = number_format($price, 2);
                                    echo "<div style='font-size:40px;line-height:3;'><small>$</small>$price_text </div>";
                                }
                                ?>
                            </div>
                        </div>                    
                    </a>
                </div>
                <?php
            endforeach;
        endif;
        ?>
    </div>
</section>
