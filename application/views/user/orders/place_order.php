<script>
	$( function() {
		$("#ship_by, #deliver_by").flatpickr({
			//minDate: new Date(),
			altInput: true,
			weekNumbers: true,
			utc: true,
			altFormat: "F, d Y",
			dateFormat: "U",
		});
	});
</script>
<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item">Orders</li>
							<li class="breadcrumb-item active">Order Checkout</li>
						</ol>
						<h4 class="page-title">Order Checkout</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<?php $attributes = array('class' => 'form-horizontal', 'name' => 'addorder');?>
			<?php echo form_open("user/orders/place_order", $attributes);?>
			<div class="row">
				<?php if(!empty($cart_items)):?>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<div class="card-box">
						<div class="row">
							<?php foreach ($cart_items as $product): ?>
							<div class="col-md-6">
								<div class="product-card card m-b-20 card-body text-xs-center">
									<p class="card-text"><?php echo $product['object_title'];?></p>
									<div class="form-group row">
										<label for="your_earning" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Order Earning <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($your_earning);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="order_number" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Marketplace Order Number <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($order_number);?>
										</div>
									</div>
								</div>
							</div>
							<?php endforeach;?>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="shipper_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Shipper <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<select name="shipper_id" class="selectpicker" data-style="btn-white">
										<?php if(!empty($shippers)): foreach ($shippers as $shipper): ?>
											<option value="<?php echo $shipper->id;?>"><?php echo $shipper->company;?></option>
											<?php endforeach; else:?>
											<option>No Records Found</option>
											<?php endif;?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group row">
								<label for="ship_by_date" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Ship By <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="input-group">
										<?php echo form_input($ship_by_date);?>
										<div class="input-group-append">
											<span class="input-group-text"><i class="md md-event-note"></i></span>
										</div>
									</div><span class="help-block"><small>checkin opening date & time for registered participants</small></span>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group row">
								<label for="deliver_by_date" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Deliver By <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="input-group">
										<?php echo form_input($deliver_by_date);?>
										<div class="input-group-append">
											<span class="input-group-text"><i class="md md-event-note"></i></span>
										</div>
									</div><span class="help-block"><small>checkin closing date & time for registered participants</small></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group row">
									<label for="customer_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Customer Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($customer_name);?>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group row">
									<label for="customer_phone" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Customer Phone <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($customer_phone);?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="shipping_address" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Shipping Address <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($shipping_address);?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="form-group row">
									<label for="country_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Country <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<select name="country_id" class="selectpicker dropup" data-live-search="true"  data-style="btn-white" data-dropup-auto="false">
										<option class="option-disabled">-- Select Location --</option>
										<?php if(!empty($countries)): foreach ($countries as $country): ?>
											<option value="<?php echo $country->country_id;?>" data-subtext="<?php echo $country->continent_name;?>"><?php echo $country->country_name;?></option>
											<?php endforeach; else:?>
											<option>No Records Found</option>
											<?php endif;?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<div class="form-group row">
									<label for="delivery_type" class="col-12 col-form-label">Delivery Type</label>
									<div class="col-12">
										<select name="delivery_type" class="selectpicker dropup" data-style="btn-white" data-dropup-auto="false">
											<option value="Normal">Normal</option>
											<option value="Fast">Fast</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end card-box -->
				</div><!-- end col -->
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<a class="btn btn-gold btn-block waves-effect waves-light" onclick="document.addorder.submit()">Place order</a>
							</div>
						</div>
					</div>
				</div>
				<?php else:?>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-md-12">
								<p>Your cart is currently empty.</p>
							</div>
						</div>
					</div>
				</div>
				<?php endif;?>
			</div><!-- end row -->
				<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->
<script>var baseurl = "<?php echo base_url(); ?>";</script>
<script src="<?php echo base_url();?>assets/js/typeahead.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/common.js"></script>