<?= $this->assign("title", "Proof"); ?>

<section>
	<div class="">
		<?= $this->Flash->render("proofApproved"); ?>
		<?= $this->Flash->render("quantiyReqSent"); ?>
		<?= $this->Flash->render("artworkRejected"); ?>

		<div class="col-md-12"></div>
			<div class="page-header">
				<h1>ART WORKS PROOFS</h1>
			</div>

			<?php if(!is_null($currentProof)): ?>
				<div class="alert alert-info">
					<p class="text-info">Please review the images below and either approve or provide comments on changes that need to be made. By accepting the proof, you acknowledge that you have checked and approved each tag for correct content, image, colors, spelling and quantity despite what may or may not have been provide as original comments or instructions at the time of the order.</p>
				</div>
			<?php else: ?>
				<div class="alert alert-info">
					<p class="text-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> &nbsp;No proofs uploaded yet.</p>
				</div>
			<?php endif; ?>

			<fieldset>
				<div class="row top-20">
					<div class="col-md-12">
						<?php if(!is_null($currentProof)): ?>
							<div class="col-md-4 col-md-offset-4 text-center">
								<p>Current Proof</p>
								<img src="https://www.boostpromotions.com/2dodash/<?= $currentProof["Proof"]['location'] ?>"
							 	class="img-responsive img-thumbnail" />

							 	<div class="row top-20">

							 		<?php if($currentProof["Proof"]["status"] == 1): ?>
							 			<p>
											<b>[Approved]</b><br />
											Note: If you want to recall this approval, please contact us.
										</p>
									<?php elseif($currentProof["Proof"]["status"] == 3): ?>
										<p>
											<b>[Rejected]</b><br />
											Reason: <?= ucfirst($currentProof["Proof"]["reason"]) ?>
										</p>
									<?php else: ?>
										<div class="col-md-6">
										 	<?= $this->Form->postLink("Approve Proof",
												array(
													"controller" => "orders",
													"action" => "approveProof",
													$currentProof["Proof"]["id"]
												),
												array(
													"class" => "btn btn-primary btn-block",
													"confirm" => "Are you sure you want to approve this proof?"
												)
											);  ?>
										</div>

										<div class="col-md-6">
												<?= $this->Html->link("Reject Proof", "/orders/rejectProof/".$currentProof["Proof"]["id"], array(
									 				"class" => "btn btn-danger"
									 			));  ?>
										</div>
									<?php endif; ?>
								</div>

								<div class="text-left top-20">
									<?= $this->Form->create(null, array(
										"url" => array("controller" => "orders", "action" => "changeQty")
									)) ?>
										<?= $this->Form->hidden("Proof.id", array("value" => $currentProof["Proof"]["id"])); ?>

										<p><b>From x <?= $currentProof["OrdersProduct"]["products_quantity"] ?></b></p>

										<div class="form-group">
											<?= $this->Form->input("Proof.new_qty", array(
												"type" => "number",
												"label" => "to &nbsp;",
												"min" => 1,
												"required"
											)); ?>
										</div>

										<button type="submit" class="btn btn-default btn-block">Change Quantity Request</button>
									<?= $this->Form->end(); ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="col-md-6 top-20">
						<div class="row">

							<?php foreach($proofData as $proof): ?>
								<div class="col-md-4">
									<img src="https://www.boostpromotions.com/2dodash/<?= $proof['Proof']['location'] ?>"
						 			class="img-responsive img-thumbnail" />

						 			<?php if($proof["Proof"]["status"] == 1): ?>
							 			<div class="top-20">
							 				<p>
												<b>[Approved]</b><br />
												Note: If you want to recall this approval, please contact us.
											</p>
										</div>
									<?php elseif($proof["Proof"]["status"] == 3): ?>
										<div class="top-20">
											<p>
												<b>[Rejected]</b><br />
												Reason: <?= ucfirst($proof["Proof"]["reason"]) ?>
											</p>
										</div>
									<?php else: ?>
										<div class="top-20">
											<?= $this->Form->postLink("Approve Proof",
												array(
													"controller" => "orders",
													"action" => "approveProof",
													$proofData[0]["Proof"]["id"]
												),
												array(
													"class" => "btn btn-primary",
													"confirm" => "Are you sure you want to approve this proof?"
												)
											);  ?>

								 			<?= $this->Html->link("Reject Proof", "/orders/rejectProof/".$proof["Proof"]["id"], array(
								 				"class" => "btn btn-danger"
								 			));  ?>
										</div>
									<?php endif; ?>

									<div class="text-left top-20">
										<?= $this->Form->create(null, array(
											"url" => array("controller" => "orders", "action" => "changeQty")
										)) ?>
											<?= $this->Form->hidden("Proof.id", array("value" => $proof["Proof"]["id"])); ?>

											<p><b>From x <?= $proof["OrdersProduct"]["products_quantity"] ?></b></p>

											<div class="form-group">
												<?= $this->Form->input("Proof.new_qty", array(
													"type" => "number",
													"label" => "to &nbsp;",
													"value" => $proof["Proof"]["new_qty"],
													"min" => 1,
													"required"
												)); ?>
											</div>

											<button type="submit" class="btn btn-default">Change Quantity Request</button>
										<?= $this->Form->end(); ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</fieldset>

			<div class="alert alert-info">
				<p class="text-info"><b>PRODUCTION TIME</b>  business days, Orders including Custom Lanyards: 3-4 weeks. If you would like the tags shipped separately, please advise before completing your order so we can add the 2nd shipping fee.</p>
			</div>

			<fieldset>
				<legend>Friendly Reminder: Busiest Months</legend>
				<p class="text-warning">Note: August - October is our busiest time of the year. Orders placed during these months may experience a longer than normal production time. If your order is time sensitive, please inquire about the current production time.</p>
			</fieldset>
		</div>
	</div>
</section>
