<?php $this->assign('title', "Customizeable Student Rewards — Check Out Boost Promotions' Catalog"); ?>
<section class="container">
    <div class="page-header">
        <h1>All Products</h1>
    </div>
    <?php if (isset($this->request->query['not_found'])): ?>
        <div class="alert alert-info">
            <h4>We're sorry! That product is not available at this time.</h4>
            <p>Double check that you have the URL correct if you typed it in or cut and pasted it.
                If you need help finding what you're looking, please give us a call and we can help you. <a href="tel:801-987-8351">801-987-8351</a></p>
        </div>
    <?php endif; ?>
    <div class="row">
        <?php
        $mains = [
            ['name' => 'Swag Tags', 'link' => 'products/order/294', 'img' => 'category/swag_tags.png', 'price' => '.09'],
            ['name' => 'Shape Tags', 'link' => 'products/order/299', 'img' => 'category/shape_tags.png', 'price' => '.09'],
            ['name' => 'Spirit Tags', 'link' => 'products/order/313', 'img' => 'category/spirit_tags.png', 'price' => '.28'],
            ['name' => 'Jr Bag Tags', 'link' => 'products/order/317', 'img' => 'category/jrbag_tags.png', 'price' => '.42'],
            ['name' => 'Bag Tags', 'link' => 'products/order/322', 'img' => 'category/bag_tags.png', 'price' => '.75'],
            ['name' => 'Custom Key Fobs', 'link' => 'products/category/441', 'img' => 'category/key_fobs.png', 'price' => '.75'],
            ['name' => 'Lanyards', 'link' => 'products/accessories/519', 'img' => 'category/lanyards.png', 'price' => '.36'],
            ['name' => 'Accessories', 'link' => 'products/category/520', 'img' => 'category/accessories.png', 'price' => '.03'],
            ['name' => 'Teacher Kits', 'link' => 'products/accessories/101', 'img' => 'category/teacher_kits.png', 'price' => '38']
        ];

        $ctr = 0;
        foreach ($mains as $main):
            $ctr++;
            ?>
            <div class="catCard col-md-3 col-sm-6">
                <a href="<?= $this->webroot . $main['link']; ?>" class="thumbnail bg<?= $ctr; ?> text-center">
                    <h3><?= $main['name']; ?></h3>
                    <?= $this->Html->Image($main['img'], ['class' => 'img-responsive' . (isset($main['price']) ? ' with-price' : '')]); ?>
                    <?php if (isset($main['price'])): ?>
                        <div class="starburst fa-stack fa-4x fa-lg">
                            <i class="fa fa-starburst back fa-stack-2x"></i>
                            <i class="fa fa-starburst front fa-stack-2x"></i>
                            <div class="fa fa-stack-1x text">
                                STARTING AT
                            </div>
                            <div class="fa fa-stack-1x number"><strong>
                                    <?php
                                    if ($main['price'] > 1) {
                                        echo '<small>$</small>' . $main['price'];
                                    } else {
                                        echo $main['price'] * 100 . '<small>¢</small>';
                                    }
                                    ?></strong>
                            </div>
                        </div>
                    <?php endif; ?>                       
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>