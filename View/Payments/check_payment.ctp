<?= $this->assign("title", "Check Payment"); ?>

<section>
    <div class="container">
        <ul class="breadcrumb">
            <li><?= $this->Html->link('Previous Orders', '/orders'); ?></li>
            <li><?= $this->Html->link('Payments: Order #' . $orderId, '/payments/index/' . $orderId); ?></li>
            <li class="active">Check</li>
        </ul>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                <?= $this->Flash->render("check"); ?>

                <?php if (empty($check)): ?>
                    <h1>UPLOAD A SCANNED COPY OF THE CHECK</h1>
                    <p><b>No need to mail the check.</b></p>
                <?php endif; ?>

                <fieldset>
                    <?php if (empty($check)): ?>
                        <legend>Submit a scanned Check</legend>
                    <?php else: ?>
                        <legend>Submited Check</legend>
                    <?php endif; ?>

                    <?=
                    $this->Form->create(null, array(
                        "url" => array("controller" => "payments", "action" => "checkPayment", $orderId),
                        'enctype' => 'multipart/form-data'
                    ))
                    ?>

                    <?php if (!empty($check)): ?>

                        <?php if ($check["CheckReceipt"]["status"] == 0): ?>
                            <p>Please wait for the approval of this Check.</p>

                            <?php
                            echo $this->Form->hidden("CheckReceipt.id", array("value" => $check["CheckReceipt"]["id"]));
                            echo $this->Form->hidden("CheckReceipt.location", array("value" => $check["CheckReceipt"]["location"]));
                            echo $this->Form->hidden("CheckReceipt.checkreceipt", array("value" => "x"));
                            ?>

                            <?= $this->Html->image($check["CheckReceipt"]["location"], array("class" => "img-responsive")); ?>

                            <button type="submit" class="btn btn-danger btn-block top-20">
                                Remove Check
                            </button>

                        <?php elseif ($check["CheckReceipt"]["status"] == 1): ?>
                            <p><strong>Approved</strong> Your order is now being processed.</p>
                        <?php else: ?>
                            <!-- Do Nothing -->
                        <?php endif; ?>

                    <?php else: ?>
                        <?= $this->Form->hidden("CheckReceipt.checkreceipt", array("value" => 1)); ?>

                        <div class="form-group">
                            <?=
                            $this->Form->input("CheckReceipt.file", array(
                                "type" => "file",
                                "class" => "form-control",
                                "required"
                            ));
                            ?>	
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            Upload Check
                        </button>
                    <?php endif; ?>
                    <?= $this->Form->end(); ?>
                </fieldset>
            </div>
        </div>
    </div>
</section>
