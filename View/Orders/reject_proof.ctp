<?= $this->assign("title", "Reject Proof"); ?>

<section>
	<div class="row">
		<div class="col-md-12">
			<h1>Reject Proof</h1>

			<?= $this->Form->create(null, array(
				"url" => array("controller" => "orders", "action" => "rejectProof", $proofData["Proof"]["id"])
			)) ?>

			<label>Reason</label>
			<?= $this->Form->input("Proof.reason", array(
				"type" => "textarea",
				"label" => false,
				"required"
			)); ?>

			<button type="submit">Reject Proof</button>
			<?= $this->Form->end(); ?>
		</div>
	</div>
</section>