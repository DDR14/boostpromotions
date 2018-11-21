<?= $this->assign("title", "Upload PO"); ?>

<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('Previous Orders', '/orders'); ?></li>
            <li><?= $this->Html->link('Payments: Order #' . $orderId, '/payments/index/' . $orderId); ?></li>
            <li class="active">Purchase Order</li>
        </ul>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <?= $this->Flash->render("po"); ?>

                <?php if (empty($POData)): ?>
                    <h1>PAY THE APPROVED PROOFS BY PO.</h1>
                    <p><b>No need to mail the po.</b></p>
                <?php endif; ?>

                <fieldset>
                    <?php if (empty($PO)): ?>
                        <legend>Submit a new PO</legend>
                    <?php else: ?>
                        <legend>PO Submited</legend>
                    <?php endif; ?>

                    <?=
                    $this->Form->create(null, array(
                        "url" => array("controller" => "payments", "action" => "uploadPO", $orderId),
                        'enctype' => 'multipart/form-data'
                    ));
                    ?>

                    <?php if (!empty($PO)): ?>

                        <?php if ($PO["PoReceipt"]["status"] == 0): ?>

                            <?php
                            echo $this->Form->hidden("PO.id", array("value" => $PO["PoReceipt"]["id"]));
                            echo $this->Form->hidden("PO.location", array("value" => $PO["PoReceipt"]["location"]));
                            echo $this->Form->hidden("PO.poReceipt", array("value" => "x"));
                            ?>

                            <?= $this->Html->image($PO["PoReceipt"]["location"], array("class" => "img-responsive")); ?>

                            <button type="submit" class="btn btn-danger btn-block top-20">
                                Remove PO
                            </button>

                        <?php elseif ($POData["PoReceipt"]["status"] == 1): ?>
                            <p><strong>Approved</strong> Your order is now being processed.</p>
                        <?php else: ?>
                            <!-- Do Nothing -->
                        <?php endif; ?>

                    <?php else: ?>
                        <?= $this->Form->hidden("PO.poReceipt", array("value" => 1)); ?>

                        <div class="form-group">
                            <?=
                            $this->Form->input("PO.file", array(
                                "type" => "file",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>	
                        </div>

                        <div class="form-group">
                            <?=
                            $this->Form->input("PO.po_number", array(
                                "label" => "P.O. #:",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>	
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            Upload PO
                        </button>
                    <?php endif; ?>
                    <?= $this->Form->end(); ?>
                </fieldset>
            </div>
        </div>
    </div>
</section>