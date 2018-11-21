<?= $this->assign("title", "My Orders"); ?>



<section class="top-40">
    <div class="container">
	<?= $this->element("sidebars", ["title" => "Previous Orders", "active" => "orders"]); ?>
	<?= $this->fetch("myAccountSidebar"); ?>

	<div class="col-md-9">
		<ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active">
		    	<a href="#all-orders" aria-controls="home" role="tab" data-toggle="tab">Previous Orders</a>
		    </li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="all-orders">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12 top-20 bottom-20">
							<?php foreach($orders as $order): ?>
								<div class="bordered bottom-20">
									<fieldset>
										<div class="col-md-12 page-header">
											<h5>Order # <?= $order['Order']['orders_id'] ?></h5>
										</div>
										
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-8">
													<p>
														<b>Order Date: </b><br />
														<?= date("l d F, Y", strtotime($order["Order"]["date_purchased"])) ?>
													</p>
													<p>
														<b>Shipped To: </b><br />
														<?= ucfirst($order["Order"]["delivery_name"]); ?>
													</p>
												</div>

												<div class="col-md-4">
													<p>
														<b>Products: </b><br />
														<?= count($order["OrdersProduct"]) ?>
													</p>
													<p>
														<b>Order Cost: </b><br />
														<?= $this->Number->currency($order["Order"]["order_total"], "USD") ?>
													</p>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12 bottom-20">	
													<p>
														<b>Orders Status: </b>
														<?= ucfirst($order["OrdersStatus"]["orders_status_name"]); ?>
													</p>
												</div>

												<div class="col-md-4">
													<?= $this->Html->link("VIEW", "/orders/view/".$order["Order"]["orders_id"], array(
														"class" => "btn btn-sm btn-primary btn-block"
													)) ?>
												</div>

												<div class="col-md-4">
													<?= $this->Html->link("PROOFS", "/orders/proof/".$order["Order"]["orders_id"], array(
														"class" => "btn btn-sm btn-primary btn-block"
													)) ?>
												</div>

												<div class="col-md-4">
													<?= $this->Html->link("PAYMENTS", "/payments/index/".$order["Order"]["orders_id"], array(
														"class" => "btn btn-sm btn-primary btn-block"
													)) ?>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							<?php endforeach; ?>

							<?php if(empty($orders)): ?>
								<div class="alert alert-info">
									<p class="text-warning"><b><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No Previous Orders</b></p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    </div>
</section>
