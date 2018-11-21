<section class="top-20">
	<div class="row">
		<div class="col-md-12">
			<h1>STEP 1 OF 3 - DELIVERY INFORMATION</h1>
		</div>

		<!-- Shipping Information -->
		<div class="col-md-12">
			<fieldset>
				<legend>Shipping Information</legend>
				<div class="row">
					<div class="col-md-6">
						<p>
							<?= strtoupper($defaultAddress["AddressBook"]["entry_company"]) ?><br />
							<?= strtoupper($defaultAddress["AddressBook"]["entry_firstname"]) ?>
							<?= strtoupper($defaultAddress["AddressBook"]["entry_lastname"]) ?><br />
							<?= strtoupper($defaultAddress["AddressBook"]["entry_street_address"]) ?>
							<?= strtoupper($defaultAddress["AddressBook"]["entry_suburb"]) ?>
							<?= strtoupper($defaultAddress["AddressBook"]["entry_city"]) ?>,<br />
							<?= strtoupper($defaultAddress["AddressBook"]["entry_postcode"]) ?>,<br />
							<?= strtoupper($defaultAddress["AddressBook"]["entry_state"]) ?>,
							<?= strtoupper($defaultAddress["Country"]["countries_name"]) ?>
						</p>
					</div>

					<div class="col-md-6">
						<p class="text-info">Your order will be shipped to the address at the left or you may change the shipping address by clicking the Change Address button.</p>

						<?= $this->Html->link("CHANGE YOUR SHIPPING ADDRESS", "/shoppingCart/changeYourAddress",
							array("class" => "btn btn-primary btn-md top-20")
						); ?>
					</div>
				</div>
			</fieldset>
		</div>

		<div class="col-md-12">
			<p>
				<b>Shipping Method</b><br />
				<small>Please select the preferred shipping method to use on this order.</small>
			</p>
			
			<fieldset>
				<legend>Flat Rate Shipping</legend>
				<?= $this->Form->checkbox("Order.shipping_method", array("value" => "Best Way")); ?> 
				<label>Best Way</label>
			</fieldset>

			<fieldset>
				<legend>United Parcel Service (1 x 6.31lbs)</legend>
			</fieldset>

			<fieldset>
				<p class="top-20">
					<b>PRODUCTION TIME </b>
					 Tag only orders: 7-10 business days, Orders including Custom Lanyards: 3-4 weeks. If you would like the tags shipped separately, please advise before completing your order so we can add the 2nd shipping fee.
				</p>
			</fieldset>

			<fieldset>
				<legend>Friendly Reminder: Busiest Months</legend>
				<p>Note: August - October is our busiest time of the year. Orders placed during these months may experience a longer than normal production time. If your order is time sensitive, please inquire about the current production time.</p>
			</fieldset>
		</div>

		<div class="col-md-12">
			<p class="pull-left">Continue to step 2: Choose your payment method</p>
			<?= $this->Html->link("CONTINUE TO STEP 2", "/shoppingCart/checkout2", array(
				"class" => "btn btn-primary btn-md pull-right"
			)); ?>
		</div>
	</div>
</section>