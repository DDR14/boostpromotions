<section>
   <div class="row">
       <div class="col-md-8">
            <?= $this->Flash->render("itemDeleted"); ?>
            <?= $this->Flash->render("newItemAdded"); ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">YOUR SHOPPING CART CONTENTS (<?= count($basketItem) ?>)</h2>
                </div>
                <div class="panel-body">
                    <div class="row">

                        <?php 
                            $tk = array("881", "878", "877", "880", "879", "876");
                            $tk_extra = array("882", "883", "884", "885", "886", "887", "888");
                            $is_tk = FALSE;
                        ?>

                        <?php foreach($basketItem as $item): ?>
                            <?php if(!empty($item["CustomersBasket"]["customs"])): ?>
                                <div class="col-md-12">
                                    <img style="max-height:150px; float:left; margin-right: 20px" class="thumbnail" src="https://boostpromotions.com/images/<?= $item['Product']['products_image'] ?>">

                                    <h4 class="pull-right text-warning">
                                      <b><?= $this->Number->currency($item[0]["products_price"] * $item['CustomersBasket']['customers_basket_quantity'], '$'); ?></b> 
                                    </h4>

                                    <p>
                                        <b> <a href="<?= $this->webroot; ?>products/order/update/<?= $item['CustomersBasket']['customers_basket_id'] ?>" ><span class="text-warning">
                                        <?= $item["ProductsDescription"]["products_name"] . ' / ' . $item["Product"]['products_model']; ?></span></a></b>
                                        <br />

                                        <?php if(in_array($item['Product']['products_id'], $tk)): ?>
                                            
                                            <?php $is_tk = TRUE; ?>
                                            <br/><span><?php echo 'Teacher Name: ' . $item['CustomersBasket']['footer']; ?></span>
                                            <br/><span style="width:500px"><?php echo 'Model List: ' . $item['CustomersBasket']['customs']; ?></span>
                                            <br/><span><a href="#" style="color:green"><strong>Teacher kit orders are entitled for EXTRA. </strong>See below.</a></span>

                                        <?php elseif(in_array($item['Product']['products_id'], $tk_extra)): ?>
                                             <br/><span><?php echo $item['CustomersBasket']['customs']; ?></span>
                                        <?php else: ?>

                                            <?php if($item['CustomersBasket']['title'] == '' &&
                                                $item['CustomersBasket']['footer'] == '' &&
                                                $item['CustomersBasket']['background'] == '' &&
                                                // $item['CustomersBasket']['customs'] == '' &&
                                                $item['CustomersBasket']['upload'] == ''
                                            ): ?>

                                                <br />
                                                <span class="text-info">No Customizations</span>      
                                                <br/><span class="top-10">Image: <?php
                                                    echo $item['CustomersBasket']['customs'];
                                                    if ($item['CustomersBasket']['upload'] != '') {
                                                        echo "<br />Image Upload: <a target='_blank' href='https://www.boostpromotions.com/{$item['CustomersBasket']['upload']}'>{$item['CustomersBasket']['upload']}</a>";
                                                    }
                                                    ?>
                                                </span>

                                            <?php else: ?>
                                                <b class="text-warning">Customizations</b>
                                                <br/><span>Title: <?php echo $item['CustomersBasket']['title']; ?></span>
                                                <br/><span>Footer: <?php echo $item['CustomersBasket']['footer']; ?></span>
                                                <br/><span>Background: <?php echo $item['CustomersBasket']['background']; ?></span>
                                                <br/><span>Image: <?php
                                                    echo $item['CustomersBasket']['customs'];
                                                    if ($item['CustomersBasket']['upload'] != '') {
                                                        echo "<br />Image Upload: <a class='text-yellow' target='_blank' href='https://www.boostpromotions.com/{$item['CustomersBasket']['upload']}'>{$item['CustomersBasket']['upload']}</a>";
                                                    }
                                                    ?>
                                                </span>
                                            <?php endif; ?>

                                        <?php endif; ?>

                                        <br /><span>Total Qty: <?= $item["CustomersBasket"]["customers_basket_quantity"] ?></span>
                                    </p>

                                    <!-- 
                                    TODO:: ADD THIS FUNCTIONALITY IN THE FUTURE

                                    <?php if(in_array($item['Product']['products_id'], $tk)): ?>
                                        <button class="btn btn-info btn-sm" ng-click="modelsList('<?= $item['CustomersBasket']['customs'] ?>', true)">
                                            VIEW TAG DESIGNS
                                        </button>
                                    <?php else: ?>
                                         <button class="btn btn-info btn-sm" ng-click="modelsList('<?= $item['CustomersBasket']['customs'] ?>')">
                                            VIEW TAG DESIGNS
                                        </button>
                                    <?php endif; ?> 
                                    -->

                                    <?=
                                        $this->Form->postLink("<i class='fa fa-trash'></i>&nbsp;REMOVE", [
                                            "controller" => "ShoppingCart",
                                            "action" => "delete",
                                            $item["CustomersBasket"]["customers_basket_id"]
                                                ], ["escape" => false, "class" => "btn btn-default btn-sm"]);
                                    ?>

                                    <a href="<?= $this->webroot; ?>products/order/update/<?= $item['CustomersBasket']['customers_basket_id'] ?>" class="btn btn-default btn-sm">

                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;
                                        EDIT
                                    </a>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr class="clearfix">
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-12">
                                    <img style="width: 150px; height: 150px; float:left; margin-right: 20px" class="thumbnail" src="https://boostpromotions.com/images/<?= $item['Product']['products_image'] ?>">

                                    <h4 class="pull-right text-warning">
                                        <?php if($item["Product"]["master_categories_id"] == 94): ?>
                                            <b>
                                                <?php
                                                    $lanyardsPrice = ($accumulatedQty < 500)? $item[0]["products_price"] + 1 : $item[0]["products_price"];
                                                ?>

                                                <?= $this->Number->currency($lanyardsPrice * $item["CustomersBasket"]["customers_basket_quantity"]); ?>
                                            </b>
                                        <?php else: ?>
                                            <b><?= $this->Number->currency($item[0]["products_price"] * $item['CustomersBasket']['customers_basket_quantity'], '$'); ?></b> 
                                        <?php endif; ?>
                                    </h4>

                                    <p>
                                        <b> <a href="<?= $this->webroot; ?>products/order/update/<?= $item['CustomersBasket']['customers_basket_id'] ?>" ><span class="text-warning">
                                        <?= $item["ProductsDescription"]["products_name"] . ' / ' . $item["Product"]['products_model']; ?></span></a></b>
                                        <br />

                                        <?php if($item["Product"]["master_categories_id"] == 94): ?>
                                            <span>
                                                You only ordered <?= $accumulatedQty ?> tags. LANYARDS is 1$ more expensive.
                                            </span>
                                        <?php endif; ?>

                                        <?php if(in_array($item['Product']['products_id'], $tk_extra)): ?>
                                             <br/><span><?php echo $item['CustomersBasket']['customs']; ?></span>
                                        <?php endif; ?>

                                        <br /><span>Total Qty: <?= $item["CustomersBasket"]["customers_basket_quantity"] ?></span>
                                    </p>

                                    <?=
                                        $this->Form->postLink("<i class='fa fa-trash'></i>&nbsp;REMOVE", [
                                            "controller" => "ShoppingCart",
                                            "action" => "delete",
                                            $item["CustomersBasket"]["customers_basket_id"]
                                                ], ["escape" => false, "class" => "btn btn-default btn-sm"]);
                                    ?>

                                    <a href="<?= $this->webroot; ?>products/order/update/<?= $item['CustomersBasket']['customers_basket_id'] ?>" class="btn btn-default btn-sm">

                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;
                                        EDIT
                                    </a>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr class="clearfix">
                                        </div>
                                    </div>
                                    
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if(empty($basketItem)): ?>
                            <div class="col-md-12">
                                <p><b>Your cart is empty</b></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
           </div>
       </div>

       <div class="col-md-4">
           <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">SUMMARY</h4>
                </div>
                <div class="panel-body">
                    <h5>
                        Sub Total: <span class="text-success"><?= $this->Number->currency($headerData['cart_total']); ?></span>
                    </h5>

                    <?php if(!empty($basketItem)): ?>
                        <?= $this->Html->link("CHECKOUT", ['action' => 'checkout'], ['escape' => false, 'class' => 'btn-block btn btn-warning top-20']); ?>
                    <?php endif; ?>
                </div>
           </div>
       </div>
   </div>

   <?php if(isset($teachersKitsFound)): ?>
        <div class="row">
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
                                        <?php if(!empty($tkex['Product']['products_image'])): ?>
                                          <img style="max-height: 150px; float:left; margin-right: 20px;" src="https://boostpromotions.com/images/<?= $tkex["Product"]["products_image"] ?>" class="img-responsive thumbnail" />
                                      

                               
                                                <p><b class="text-warning"><?= $tkex['ProductsDescription']['products_name']; ?> &nbsp; <?= $tkex['Product']['products_id'] ?></b></p>
                                                <p><?= $tkex['ProductsDescription']['products_description']; ?></p>

                                                <?php if($tkex['Product']['products_id'] != 888):  ?>
                                                    <?=
                                                        $this->Form->postLink("Add to Cart", array(
                                                            "controller" => "teachersKits",
                                                           "action" => "addAddOns",
                                                            $tkex["Product"]["products_id"]
                                                        ), ['class' => 'btn btn-success']);
                                                    ?>
                                                <?php else: ?>
                                                   
                                                    <?= $this->Html->link("Add to Cart", "/products/order/teachersKits/".$tkex["Product"]["products_id"], array(
                                                        "class" => "btn btn-success"
                                                    )) ?>
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
</section>