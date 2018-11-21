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
                                    <div class="col-md-3 col-sm-4">
                                        <a href="<?= $this->webroot ?>products/merchandise/<?= $item['Product']['products_id'] ?>" 
                                           class="thumbnail text-center">
                                            <img src="https://boostpromotions.com/images/<?= $item['Product']['products_image'] ?>" class="img-responsive with-price">                        
                                            <div><b><?= $item['ProductsDescription']['products_name'] ?></b></div>
                                            <p>as low as <?php
                                                if ($price < 1) {
                                                    echo $price * 100 . '¢';
                                                } else {
                                                    $price_text = number_format($price, 2);
                                                    echo '$' . $price_text;
                                                }
                                                ?></p>
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