<section>
    <div class="container">
        <div class="page-header">
            <h1>Merchandise</h1>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-body" style="height: 500px">
                        <ul class="list-unstyled">
                            <?php foreach ($cat_merch as $cat): ?>
                                <li><a href="<?= $this->webroot; ?>products/merchandise">
                                        <h5 class="text-black"><?= $cat['CategoriesDescription']['categories_name'] ?></h5>
                                    </a></li>
                            <?php endforeach; ?>                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="row">
                    <?php if ($merchandise): ?>
                        <div class="col-sm-6">


                            <img id="product-img" src="https://boostpromotions.com/images/<?= $merchandise['Product']['products_image'] ?>" class="img-responsive thumbnail">
                        </div>
                        <div class="col-sm-6">
                            <h1 id="productName">
                                <?= $merchandise["ProductsDescription"]["products_name"] ?>
                                <br/>
                                <small><?= $merchandise["Product"]["products_model"] ?></small>
                            </h1>
                            <hr/>
                            <div class="table-responsive">
                                <table class="table table-bordered striped-colums">
                                    <tr>
                                        <?php if (in_array($merchandise["Product"]["products_id"], [990, 853])): ?>
                                            <th></th>
                                        <?php endif; ?>
                                        <th><?= $merchandise["Product"]["products_quantity_order_min"]; ?>+</th>
                                        <?php foreach ($merchandise['ProductsDiscountQuantity'] as $data): ?>
                                            <th><?= $data["discount_qty"] ?>+</th>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <?php if (in_array($merchandise["Product"]["products_id"], [990, 853])): ?>
                                            <td><b>With Tag Order (Minimum of 500 Tags)</b></td>
                                        <?php endif; ?>
                                        <td><?= $this->Number->currency($merchandise["Product"]["products_price"], "$"); ?></td>
                                        <?php foreach ($merchandise['ProductsDiscountQuantity'] as $data): ?>
                                            <td><?= $this->Number->currency($data["discount_price"], "$") ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php
                                    //IF LANYARDS, ADD $1 for each
                                    if (in_array($merchandise["Product"]['products_id'], [990, 853])):
                                        echo "<tr><td><b>Without Tag Order</b></td>";
                                        echo '<td>' . $this->Number->currency($merchandise["Product"]["products_price"] + 1, "$") . '</td>';
                                        foreach ($merchandise['ProductsDiscountQuantity'] as $data):
                                            ?>
                                            <td><?= $this->Number->currency($data["discount_price"] + 1, "$") ?></td>
                                            <?php
                                        endforeach;
                                        echo "</tr>";
                                    endif;
                                    ?>
                                </table>
                            </div>
                            <p><?= $merchandise["ProductsDescription"]["products_description"] ?></p>
                            <button type="button" class="btn btn-lg btn-primary">
                                CALL 801-987-8351 TO ORDER THIS PRODUCT
                            </button>
                        </div>
                        <?php
                    else:
                        foreach ($cat_merch as $cat):
                            ?>
                            <div class="clearfix"></div>
                            <h3 class="text-uppercase"><?= $cat['CategoriesDescription']['categories_name'] ?></h3>
                            <?php
                            $ctr = 0; //color
                            foreach ($merchandises as $item):
                                if ($item['Product']['master_categories_id'] == $cat['Category']['categories_id']):
                                    $ctr++;
                                    $price = $item['ProductsDiscountQuantity'][count($item['ProductsDiscountQuantity']) - 1]['discount_price'];
                                    ?>
                                    <div class="catCard col-md-3 col-sm-4">
                                        <a href="<?= $this->webroot ?>products/merchandise/<?= $item['Product']['products_id'] ?>" class="thumbnail bg<?= $ctr; ?> text-center">
                                            <h3 style="height:64px"><?= $item['ProductsDescription']['products_name'] ?></h3>
                                            <img src="https://boostpromotions.com/images/<?= $item['Product']['products_image'] ?>" class="img-responsive with-price">                  
                                            <div class="starburst fa-stack fa-4x fa-lg">
                                                <i class="fa fa-starburst back fa-stack-2x"></i>
                                                <i class="fa fa-starburst front fa-stack-2x"></i>
                                                <div class="fa fa-stack-1x text">
                                                    AS LOW AS
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
                                endif;
                            endforeach;
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
