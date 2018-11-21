<?= $this->assign("title", "Proof"); ?>
<style>
   .shadow{ -webkit-filter: drop-shadow(5px 5px 5px #222);
  filter: drop-shadow(5px 5px 5px #222);}
</style>
<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('Previous Orders', '/orders'); ?></li>
            <li class="active">Proofs: Order #<?= $order_products[0]["OrdersProduct"]["orders_id"]; ?></li>
        </ul>
        <div class="page-header">
            <h1>REVIEW PROOFS for Order #<?= $order_products[0]["OrdersProduct"]["orders_id"]; ?></h1>
        </div>
        <?= $this->Flash->render("proofApproved"); ?>
        <?= $this->Flash->render("quantiyReqSent"); ?>
        <?= $this->Flash->render("artworkRejected"); ?>
        <?php if ($hasProof): ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="alert alert-info">
                        <p>Please review the images below and either approve or provide comments on changes that need to be made. By accepting the proof, 
                            you acknowledge that you have checked and approved each tag for correct content, image, colors, 
                            spelling and quantity despite what may or may not have been provide as original comments or instructions 
                            at the time of the order. <?=
                            $this->Form->postLink("Approve All Artworks", array(
                                "controller" => "orders",
                                "action" => "approveALL",
                                $order_products[0]["OrdersProduct"]["orders_id"]
                                    ), array(
                                "class" => "btn btn-sm btn-primary",
                                "confirm" => "Are you sure you want to approve all artworks?"
                                    )
                            );
                            ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-info">
                        <p><b>PRODUCTION TIME</b>  business days, Orders including Custom Lanyards: 3-4 weeks. If you would like the tags shipped separately, please advise before completing your order so we can add the 2nd shipping fee.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-warning">
                        <h4>Friendly Reminder: Busiest Months</h4>
                        <p>Note: August - October is our busiest time of the year. Orders placed during these months may experience a longer than normal production time. If your order is time sensitive, please inquire about the current production time.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> &nbsp;No proofs uploaded yet.</p>
            </div>
        <?php endif; ?>
        <?php foreach ($order_products as $order): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"># <?= $order["OrdersProduct"]["orders_products_id"] ?></h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <b><?= $order["OrdersProduct"]["products_model"] ?></b>
                            <img src="https://boostpromotions.com/images/<?= $order["Product"]["products_image"] ?>" class="img-responsive bordered" />
                        </div>

                        <div class="col-md-10">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>                                        
                                        <?php
                                        for ($x = 0; $x < count($order["designs"]) && $x < 3; $x++):
                                            if (isset($order['designs'][0]['products_model'])):
                                                ?>
                                                <th width="50px">SELECTED DESIGN(S)</th>
                                            <?php endif; ?>
                                            <th>UPLOADED PROOF <small>(Click image to enlarge)</small></th>
                                            <th></th>
                                            <?php
                                        endfor;
                                        ?>
                                    </tr>
                                </thead> 
                                <tr>
                                    <?php
                                    $odd = 0;
                                    foreach ($order["designs"] as $design):
                                        if (isset($design["products_model"])):
                                            ?>
                                            <td>                                               
                                                <!-- SELECTED DESIGN --->
                                                <img src="https://boostpromotions.com/images/designs/Resize/<?= $this->Custom->getImgDir($design['products_model']); ?>/<?= $design['products_image'] ?>" 
                                                     class="img-responsive tags" />
                                                <p>
                                                    <?= $design["products_model"] ?> X <?= $design['quantity'] ?>
                                                </p>                                            
                                            </td>
                                        <?php endif; ?>    
                                        <td>
                                            <?php if (!$design['proofs']): ?>
                                                <img src="https://boostpromotions.com/2dodash/img/no_artwork.png" class="img-responsive" />
                                            <?php endif; ?>
                                            <?php foreach ($design['proofs'] as $proof): ?> 
                                                <div>
                                                    <div class="pull-left">
                                                        <a href="https://boostpromotions.com/2dodash/<?= $proof['location'] ?>" target="_blank">
                                                            <img src="https://boostpromotions.com/2dodash/<?= $proof['location'] ?>"
                                                                 style="max-height: 185px"
                                                                 class="img-responsive shadow" />
                                                        </a>           
                                                        <p>
                                                            <?php
                                                            
                                                            if (!in_array($status, [9, 13]) && $proof['status'] != 3):
                                                                echo $this->Form->create(null, array(
                                                                    "url" => array("controller" => "orders", "action" => "changeQty"),
                                                                    "class" => "form-inline",
                                                                    "onsubmit" => "return confirm('This will send a request to update quantity; price change may occur afterwards, Proceed?')"
                                                                ))
                                                                ?>
                                                                <?= $this->Form->hidden("OrdersProduct.products_model", array("value" => $order["OrdersProduct"]["products_model"])); ?>
                                                                <?= $this->Form->hidden("Proof.id", array("value" => $proof["id"])); ?>
                                                                <?= $this->Form->hidden("Proof.order_id", array("value" => $proof["order_id"])); ?>
                                                                <?= $this->Form->hidden("OrdersProduct.quantity", array("value" => $design['quantity'])); ?>

                                                                <b>From x <?= $design['quantity'] ?></b>

                                                                <?=
                                                                $this->Form->input("Proof.new_qty", array(
                                                                    "type" => "number",
                                                                    "label" => "to &nbsp;",
                                                                    "value" => $proof["new_qty"],
                                                                    "min" => 25,
                                                                    "step" => (substr($order["OrdersProduct"]["products_model"], 0, 5) == 'STOCK' ? 25 : 1),
                                                                    "required",
                                                                    "div" => false,
                                                                    "style" => "width:80px"
                                                                ));
                                                                ?>
                                                                <br/>

                                                                <button type="submit"
                                                                        class="btn btn-xs btn-default">Change Quantity Request
                                                                </button>
                                                                <?php
                                                                echo $this->Form->end();
                                                            elseif (!array_key_exists('quantity', $design)):
                                                                echo "x " . $order["OrdersProduct"]["products_quantity"];
                                                            else:
                                                                echo "x " . $design['quantity'];
                                                            endif;
                                                            ?>
                                                        </p>
                                                    </div>
                                                    <div class="pull-left">
                                                        <?php if ($proof["status"] == 1): ?>
                                                            <div class="top-20">
                                                                <p>
                                                                    <b class="text-success">[Approved]</b><br />
                                                                    Note: If you want to recall this approval, please contact us.
                                                                </p>
                                                            </div>
                                                        <?php elseif ($proof["status"] == 3): ?>
                                                            <div class="top-20">
                                                                <p>
                                                                    <b class="text-danger">[Rejected]</b><br />
                                                                    Reason: <?= ucfirst($proof["reason"]) ?>
                                                                </p>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="top-20">
                                                                <?=
                                                                $this->Form->postLink("Approve Proof", array(
                                                                    "controller" => "orders",
                                                                    "action" => "approveProof",
                                                                    $proof["id"],
                                                                    $proof["order_id"]
                                                                        ), array(
                                                                    "class" => "btn btn-sm btn-primary",
                                                                    "confirm" => "Are you sure you want to approve this proof?"
                                                                        )
                                                                );
                                                                ?>

                                                                <button class="btn-danger btn btn-sm" onclick="toggleReason(<?= $proof["id"]; ?>)">Reject Proof</button>
                                                                <?=
                                                                $this->Form->create(null, array(
                                                                    "url" => array("controller" => "orders", "action" => "rejectProof", $proof["id"]),
                                                                    "id" => 'deny' . $proof["id"],
                                                                    "style" => 'display:none;',
                                                                    "onsubmit" => "return confirm('Are you sure of the requested changes?')"
                                                                ))
                                                                ?>
                                                                <?= $this->Form->hidden("Proof.order_id", array("value" => $proof["order_id"])); ?>
                                                                <label>Reason</label>
                                                                <?=
                                                                $this->Form->input("Proof.reason", array(
                                                                    "type" => "textarea",
                                                                    "placeholder" => 'Please enter modification request',
                                                                    "label" => false,
                                                                    "required"
                                                                ));
                                                                ?>

                                                                <button type="submit">Reject Proof</button>
                                                                <?= $this->Form->end(); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>  
                                                </div>
                                                <div class="clearfix"></div>
                                            <?php endforeach; ?>
                                        </td>
                                        <td></td>
                                        <?php
                                        //line break;
                                        $odd++;
                                        if ($odd % 3 == 0) {
                                            echo '</tr><tr><td class="last" colspan="8" ></td></tr><tr>';
                                        }
                                    endforeach;
                                    ?>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>   
        <?php endforeach; ?>
    </div>
</section>
<script>
    function toggleReason(a) {
        var denyForm = document.getElementById("deny" + a);
        denyForm.style.display = denyForm.style.display === 'none' ? '' : 'none';
    }
</script>

