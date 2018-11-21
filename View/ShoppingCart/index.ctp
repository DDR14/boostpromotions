<section >
    <div class="container">
        <h1>SHOPPING CART</h1>        
        <?= $this->Flash->render("itemDeleted"); ?>
        <?= $this->Flash->render("newItemAdded"); ?>
        <?php if (!empty($basketItem)): ?>

            <!-- LANYARDS -->
            <?php if ($accumulatedQty < 500 && $lanyards > 0): ?>
                <p class="text-warning">
                    You only ordered <?= $accumulatedQty ?> tags. LANYARDS is 1$ more expensive.
                </p>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover shopping-cart-table">
                    <tr>
                        <th>ITEM <br />IMAGE</th>
                        <th>ITEM <br />DESCRIPTION</th>
                        <th>SELECTED <br />DESIGN(S)</th>
                        <th>MODIFICATIONS/<br />CUSTOMIZATIONS</th>
                        <th>UNIT<br /> PRICE</th>
                        <th>QTY</th>
                        <th>SUBTOTAL</th>
                        <th class="text-center">OPTIONS</th>
                    </tr>

                    <?php
                    $cookie = false;
                    foreach ($basketItem as $key => $item):
                        if (!AuthComponent::user("customers_id")) {
                            $item['CustomersBasket']['customers_basket_id'] = $key;
                            $cookie = TRUE;
                        }

                        $products_price = $item[0]['products_price'];
                        if ($item['type'] == 'LANYARDS' && $accumulatedQty < 500) {
                            $products_price += 1;
                        }
                        $subTotal = $products_price * $item['CustomersBasket']['customers_basket_quantity'];
                        ?>

                        <tr>
                            <td>
                                <img src="https://boostpromotions.com/images/<?= $item['Product']['products_image'] ?>" class="thumbnail">
                            </td>
                            <td>
                                <p>
                                    MODEL:<br />
                                    <span class="text-dark-gray">
                                        <?= strtoupper($item['Product']['products_model']) ?>
                                    </span>
                                </p>
                                <p>
                                    DESCRIPTION: <br />
                                    <span class="text-dark-gray">
                                        <?= strtoupper($item['ProductsDescription']['products_name']) ?>
                                    </span>
                                </p>

                            </td>
                            <td>
                                <?php if (in_array($item['type'], ['STOCK', 'MODIFIED', 'TEACHERS KITS']) || $item['Product']['products_id'] == 888): ?>
                                    <p class="text-dark-gray">
                                        <?php
                                        $models = $this->Custom->displaySelectedModel($item['CustomersBasket']['customs']);

                                        $count = 0;
                                        ?>

                                        <?php foreach ($models as $model): ?>
                                            <?php $count += 1 ?>
                                            <?= $model ?>,

                                            <?php if ($count >= 3): ?>
                                                <?php $count = 0 ?>
                                                <br />
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </p>

                                    <a class="btn btn-sm btn-default" href="" ng-click="modelsList('<?= $item['CustomersBasket']['customs'] ?>', <?= $item['CustomersBasket']['customers_basket_id'] ?>)" >
                                        <small>MORE DETAILS</small>
                                    </a>
                                    <?php
                                elseif ($item['type'] == 'CUSTOM'):
                                    if ($item['CustomersBasket']['upload']):
                                        ?>
                                        <?php
                                        foreach (explode(',', $item['CustomersBasket']['upload']) as $img):
                                            ?>
                                            <img class="pull-left img-responsive" style="max-height:100px; max-width:100px"  
                                                 src="<?= 'https://boostpromotions.com/images/uploads/' . $img ?>" />
                                                 <?php
                                             endforeach;
                                             ?>
                                         <?php else: ?>
                                        <div class="text-warning">No File uploaded.</div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <small class="text-info">NOT APPLICABLE</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['type'] == 'CUSTOM'): ?>
                                    <!-- CUSTOM TAGS -->
                                    <?php
                                    $comment = explode("/image-comment=", $item['CustomersBasket']['customs']);
                                    $comment = (count($comment) == 2) ? $comment[1] : "NONE";
                                    ?>
                                    <p>
                                        <span>WHAT YOU WOULD LIKE THE TAG TO LOOK LIKE?</span> <br />
                                        <?= $comment; ?>
                                    </p>

                                    <?php if (!empty($item['CustomersBasket']['upload'])): ?>
                                        <?php
                                        $path = explode("/", $item['CustomersBasket']['upload']);
                                        $image = end($path);
                                        ?>

                                        UPLOADED IMAGE(S):<br />                                                   
                                        <?php
                                        foreach (explode(',', $image) as $img):
                                            echo $this->Html->link($img, 'https://boostpromotions.com/images/uploads/'
                                                    . $img, ['target' => '_blank']);
                                        endforeach;
                                        ?>
                                    <?php endif; ?>

                                <?php elseif ($item['type'] == 'TEACHERS KITS'): ?>
                                    <!-- TEACHERS KITS -->
                                    <p>TEACHER NAME:<br />
                                        <span class="text-dark-gray">
                                            <?= strtoupper($item['CustomersBasket']['footer']) ?>
                                        </span>
                                    </p>
                                <?php else: ?>

                                    <!-- MODIFIED -->
                                    <?php if ($this->Custom->checkIfThereIsCustomization($item['CustomersBasket'])): ?>
                                        <p>
                                            <?php
                                            if (!empty($item['CustomersBasket']['title'])):
                                                echo $item['type'] == 'LANYARDS' ? 'TEXT COLOR' : 'TITLE';
                                                ?>: <span class="text-dark-gray"><?= strtoupper($item['CustomersBasket']['title']) ?></span><br />
                                            <?php endif; ?>

                                            <?php
                                            if (!empty($item['CustomersBasket']['background'])):
                                                echo in_array($item['type'], ['LANYARDS', 'OTHER']) ? 'COLOR' : 'BACKGROUND';
                                                ?>: <span class="text-dark-gray"><?= strtoupper($item['CustomersBasket']['background']) ?></span><br />
                                            <?php endif; ?>

                                            <?php
                                            if (!empty($item['CustomersBasket']['footer'])):
                                                echo $item['type'] == 'LANYARDS' ? 'DESCRIPTION' : 'FOOTER';
                                                ?>: <span class="text-dark-gray"><?= strtoupper($item['CustomersBasket']['footer']) ?></span><br />
                                            <?php endif; ?>

                                            <?php
                                            if (!empty($item['CustomersBasket']['customs'])):
                                                $comment = explode("/image-comment=", $item['CustomersBasket']['customs']);
                                                $comment = (count($comment) == 2) ? $comment[1] : "";
                                                if ($comment):
                                                    ?>
                                                    <span>WHAT YOU WOULD LIKE THE IMAGE TO LOOK LIKE?</span><br />
                                                    <?= $comment; ?>
                                                    <?php
                                                endif;
                                            endif;
                                            ?>

                                            <?php ?>

                                        </p>

                                        <?php if (!empty($item['CustomersBasket']['upload'])): ?>
                                            <?php
                                            $path = explode("/", $item['CustomersBasket']['upload']);
                                            $image = end($path);
                                            ?>

                                            UPLOADED IMAGE:<br />
                                            <?= $this->Html->link($image, 'https://boostpromotions.com/images/uploads/' . $item['CustomersBasket']['upload'], ['target' => '_blank']); ?>
                                        <?php endif; ?>
                                        </p>
                                    <?php elseif ($item['Product']['products_id'] == 888): ?>
                                        <p class="text-primary"><small>TEACHER KIT EXTRA</small></p>
                                    <?php else: ?>                                            
                                        <p class="text-success"><small>NO MODIFICATIONS</small></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                $<?= $products_price; ?>
                            </td>
                            <td>
                                <?= $item['CustomersBasket']['customers_basket_quantity'] ?> pcs.
                            </td>
                            <td><?= $this->Number->currency($subTotal, '$'); ?></td>
                            <td>
                                <div class="col-md-12">
                                    <?php
                                    if ($item['Product']['master_categories_id'] != 24 || $item['Product']['products_id'] == 888):
                                        ?>
                                        <a href="<?= $this->webroot; ?>products/order/update/<?= $item['CustomersBasket']['customers_basket_id'] ?>" class="btn btn-primary btn-sm btn-block">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;
                                            UPDATE
                                        </a>
                                    <?php endif; ?>
                                    <?=
                                    $this->Form->postLink("<i class='fa fa-trash'></i>&nbsp;REMOVE", [
                                        "controller" => "ShoppingCart",
                                        "action" => "delete",
                                        $item["CustomersBasket"]["customers_basket_id"],
                                        $cookie,
                                            ], ["escape" => false, "class" => "btn-block btn btn-danger btn-sm top-10",
                                        "confirm" => 'Confirm deletion?'
                                    ]);
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="coll-md-3 col-md-offset-9">
                <h4> Sub Total: <span class="text-success"><?= $this->Number->currency($headerData['cart_total']); ?></span></h4>
                <?php if (!empty($basketItem)): ?>
                    <?= $this->Html->link("PROCEED TO CHECKOUT", ['action' => 'checkout'], ['escape' => false, 'class' => 'btn-block btn btn-warning top-20']); ?>
                <?php endif; ?>
            </div>

            <?php if (isset($teachersKitsFound)): ?>
                <div class="row top-20">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h1 class="panel-title">WOULD YOU LIKE TO ADD EXTRA'S TO YOUR TEACHER KIT?</h1>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <?php foreach ($tkExtra as $tkex): ?>
                                        <div class="col-md-4">
                                            <div class="col-md-12">
                                                <?php if (!empty($tkex['Product']['products_image'])): ?>
                                                    <img style="max-height: 150px; float:left; margin-right: 20px;" src="https://boostpromotions.com/images/<?= $tkex["Product"]["products_image"] ?>" class="img-responsive thumbnail" />

                                                    <p><b class="text-warning"><?= $tkex['ProductsDescription']['products_name']; ?></b> 
                                                        <br/>$<?= number_format($tkex['Product']['products_price'], 2); ?></p>
                                                    <p><?= $tkex['ProductsDescription']['products_description']; ?></p>

                                                    <?php if ($tkex['Product']['products_id'] != 888): ?>
                                                        <?=
                                                        $this->Form->postLink("Add to Cart", array(
                                                            "controller" => "teachersKits",
                                                            "action" => "addAddOns",
                                                            $tkex["Product"]["products_id"]
                                                                ), ['class' => 'btn btn-success']);
                                                        ?>
                                                    <?php else: ?>

                                                        <?=
                                                        $this->Html->link("Add to Cart", "/products/order/" . $tkex["Product"]["products_id"], array(
                                                            "class" => "btn btn-success"
                                                        ))
                                                        ?>
                                                    <?php endif; ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h3 class="text-dark-gray">You have no items in your shopping cart.
                Click <?= $this->Html->link('here', '/products'); ?> to continue shopping.</h3>

        <?php endif; ?>
    </div>
</section>
