<section class="top-40">
	<div class="row">
		<div class="col-md-12 text-center">
			<h1><?= strtoupper($productData["ProductsDescription"]["products_name"]) ?></h1>
			<p><?=  $productData["ProductsDescription"]["products_description"] ?></p>
		</div>

		<div class="col-md-12 top-20">
			<?= $this->Form->create(null, array(
				"url" => array("controller" => "shoppingCart", "action" => "add", $productData["Product"]["products_id"]),
				'enctype'=>'multipart/form-data'
			)); ?>

			<fieldset>
				<?php if(!Authcomponent::user("customers_id")): ?>
					<p class="text-center top-20">Once you are logged in, you will be able to add tags to your cart and edit your tags here. Remember all of your customizations are free! We dont charge extra for customizations at all. Please log in to get started...</p>
				<?php else: ?>

					<?= $this->Form->hidden("CustomersBasket.products_id", array("value" => $productData["Product"]["products_id"])); ?>

					<div class="row">
						<div class="col-md-2 top-20">
							<img src="https://www.boostpromotions.com/images/<?= $productData['Product']['products_image'] ?>" class="img-responsive img-thumbnail">
						</div>

						<div class="col-md-10 top-20">
							<fieldset>
								<legend>Customize Your Tag</legend>

								<fieldset>
									<legend>Would you like to change the title for this tag? </legend>
									<?php 
										$options = array("Y" => "Yes", "N" => "No");
										$attributes = array(
											"legend" => false,
											"value" => "N",
											"separator" => "&nbsp;"
										);

										echo $this->Form->radio("CustomersBasket.titleTag", $options, $attributes);

										echo "<div id='form1'>";
										echo $this->Form->input("CustomersBasket.title", array(
											"type" => "textarea",
											"class" => "form-control",
											"label" => "Tell us what you would like to change the title to, or list the tag number with the title you want."
										));
										echo "</div>";
									?>
								</fieldset>

								<fieldset>
									<legend>Would you like to change the background for this tag? </legend>
									<?php 
										$options = array("Y" => "Yes", "N" => "No");
										$attributes = array(
											"legend" => false,
											"value" => "N",
											"separator" => "&nbsp;"
										);

										echo $this->Form->radio("CustomersBasket.backgroundTag", $options, $attributes);

										echo "<div id='form2'>";
										echo $this->Form->input("CustomersBasket.background", array(
											"type" => "textarea",
											"class" => "form-control",
											"label" => " Describe to us what you would like to change the background to, or list the tag number with the background you want."
										));
										echo "</div>";
									?>
								</fieldset>

								<fieldset>
									<legend>Would you like to change the image for this tag? </legend>
									<?php 
										$options = array("Y" => "Yes", "N" => "No");
										$attributes = array(
											"legend" => false,
											"value" => "N",
											"separator" => "&nbsp;"
										);

										echo $this->Form->radio("CustomersBasket.imageTag", $options, $attributes);

										echo "<div id='form3'>";
										echo $this->Form->input("CustomersBasket.image", array(
											"type" => "textarea",
											"class" => "form-control",
											"label" => "Tell us what you would like to change the image to, or list the tag number with the title you want. "
										));
										echo $this->Form->input("CustomersBasket.imageFile", array(
											"type" => "file",
											"class" => "top-20",
											"label" => "You can also upload a image file that best describes what you wan't us to do wit the tag."
										));
										echo "</div>";
									?>
								</fieldset>

								<fieldset>
									<legend> Do you want a footer for this tag? (eg. school name, year) </legend>
									<?php 
										$options = array("Y" => "Yes", "N" => "No");
										$attributes = array(
											"legend" => false,
											"value" => "N",
											"separator" => "&nbsp;"
										);

										echo $this->Form->radio("CustomersBasket.footerTag", $options, $attributes);

										echo "<div id='form4'>";
										echo $this->Form->input("CustomersBasket.footer", array(
											"type" => "textarea",
											"class" => "form-control",
											"label" => " Describe to us what you would like on the footer, or list the tag number with the footer you want."
										));
										echo "</div>";
									?>
								</fieldset>
							</fieldset>
						</div>
					</div>
				<?php endif; ?>

				<div class="row">
					<div class="col-md-10 col-md-offset-2 top-20">
						<div class="table-responsive">
							<p>PERSONALIZE</p>
							<table class="table">
								<tr>
									<th>25+</th>
									<?php foreach($productData["ProductsDiscountQuantity"] as $discount):  ?>
										<th><?= $discount["discount_qty"] ?>+</th>
									<?php endforeach; ?>
								</tr>

								<tr>
									<td><?= $this->Number->currency(0.29, "USD"); ?></td>
									<?php foreach($productData["ProductsDiscountQuantity"] as $discount):  ?>
										<td><?= $this->Number->currency($discount["discount_price"], "USD"); ?></td>
									<?php endforeach; ?>
								</tr>
							</table>
						</div>

						<p>
							Model: <?= strtoupper($productData["Product"]["products_model"]) ?><br />
							Shipping Weight: <?= $productData["Product"]["products_weight"] ?>lbs<br />
							Manufactured by: <?= strtoupper($productData["Manufacturer"]["manufacturers_name"]) ?>
						</p>
					</div>
				</div>

				<div class="row">
					<?php if(!Authcomponent::user("customers_id")): ?>
						<div class="col-md-4 col-md-offset-4 top-20">
							<?= $this->Html->link("LOGIN TO SHOP", "/products/redirectToitem/".$productData["Product"]["products_id"], array("class" => "btn btn-primary btn-block")); ?>
						</div>
					<?php else: ?>
						<div class="col-md-2 col-md-offset-5 top-20">
							<small class="text-info">Minimum Order: 25pcs.</small>
							<div class="form-group">
								<label class="text-center">Enter Order QTY</label>
								<?= $this->Form->input("CustomersBasket.customers_basket_quantity", array(
									"type" => "number",
									"class" => "form-control",
									"value" => 25,
									"min" => 25,
									"label" => false,
									"required"
								)); ?>
							</div>

							<button type="submit" class="btn btn-block btn-primary">
								ADD TO CART
							</button>
						</div>

					<?php endif; ?>
				</div>
			</fieldset>

			<?= $this->form->end(); ?>
		</div>
	</div>
</section>

<?= $this->Html->script("viewjs/productsView.js", array("inline" => false)); ?>
