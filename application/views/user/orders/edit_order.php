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
							<li class="breadcrumb-item active">Edit Order</li>
						</ol>
						<h4 class="page-title">Edit Order</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<?php $attributes = array('class' => 'form-horizontal', 'name' => 'updateorder');?>
			<?php echo form_open(uri_string(), $attributes);?>
			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-12">
					<div class="card-box">
					<div class="row">
							<div class="col-12">
								<h6 class="m-b-0 m-t-5"><?php echo $product['object_title'];?></h6>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-6">
								<div class="form-group row">
									<label for="item_quantity" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Quantity <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($item_quantity);?>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group row">
									<label for="your_earning" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Your Earning <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($your_earning);?>
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
							<div class="col-6">
								<div class="form-group row">
									<label for="order_number" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Marketplace Order Number <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($order_number);?>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group row">
									<label for="currency_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Currency <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<select name="currency_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
										<?php if(!empty($currencies)): foreach ($currencies as $currency): ?>
											<option value="<?php echo $currency->currency_id;?>" data-subtext="<?php echo $currency->currency_code;?>"><?php echo $currency->currency_name;?> (<?php echo $currency->currency_symbol;?>)</option>
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
							<div class="col-12">
								<div class="form-group row">
									<label for="country_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Country <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<select name="country_id" class="selectpicker dropup" data-live-search="true"  data-style="btn-white" data-dropup-auto="false">
										<?php if(!empty($countries)): foreach ($countries as $country): ?>
											<option value="<?php echo $country->country_id;?>" data-subtext="<?php echo $country->continent_name;?>"><?php echo $country->country_name;?></option>
											<?php endforeach; else:?>
											<option>No Records Found</option>
											<?php endif;?>
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
								<a class="btn btn-gold btn-block waves-effect waves-light" onclick="document.updateorder.submit()">Update Order</a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end row -->
				<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->
	<script>
	$('select[name=country_id]').val('<?php echo $ordermeta['country_id'];?>');
	$('select[name=currency_id]').val('<?php echo $ordermeta['currency_id'];?>');
	$('.selectpicker').selectpicker('refresh')
	</script>
	<script>var baseurl = "<?php echo base_url(); ?>";</script>
	<script src="<?php echo base_url();?>assets/js/typeahead.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/common.js"></script>