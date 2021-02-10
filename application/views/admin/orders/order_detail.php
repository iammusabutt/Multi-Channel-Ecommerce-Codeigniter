<script>
	$( function() {
		$("#date_created").flatpickr({
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
							<li class="breadcrumb-item"><a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/orders/view_orders">Orders</a></li>
							<li class="breadcrumb-item active">Order Detail</li>
						</ol>
						<h4 class="page-title">Order #<?php echo $order['order_id'];?> Detail</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<?php if(isset($class)){?>
			<div id="infoMessage">
				<p class="<?php echo $class;?>"><?php echo $message;?></p>
			</div>
			<?php }else{?>
				<div id="infoMessage"><?php echo $message;?></div>
			<?php };?>
			<form id="form" method="post" name="orderdetail" class="form-horizontal">
				<div class="row">
					<div class="col-9">
						<div class="card-box">
							<div class="row">
								<div class="col-4">
									<h6 class="m-b-0 m-t-5">General</h6>
									<hr/>
									<div class="form-group row">
									<label for="date_created" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Date Created</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
										<div class="input-group">
											<?php echo form_input($date_created);?>
											<div class="input-group-append">
												<span class="input-group-text"><i class="md md-event-note"></i></span>
											</div>
										</div>
										</div>
									</div>
									<div class="form-group row">
										<label for="order_status" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Order Status</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<select name="order_status" class="selectpicker"  data-style="btn-white" data-dropup-auto="false">
											<option value="pending">Pending</option>
											<option value="processing">Processing</option>
											<option value="onhold">On Hold</option>
											<option value="completed">Completed</option>
											<option value="canceled">Canceled</option>
											<option value="refunded">Refunded</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-4">
									<h6 class="m-b-0 m-t-5">Shipping</h6>
									<hr/>
									<div class="form-group row">
										<label for="customer_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Customer Name</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['customer_name'];?>
										</div>
									</div>
									<div class="form-group row">
										<label for="shipping_address" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Address</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['shipping_address'];?><br>
										</div>
									</div>
									<div class="form-group row">
										<label for="country_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Country</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['country_name'];?>
										</div>
									</div>
									<div class="form-group row">
										<label for="customer_phone" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Phone</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<a href="tel:<?php echo $order['customer_phone'];?>"><?php echo $order['customer_phone'];?></a>
										</div>
									</div>
								</div>
								<div class="col-4">
									<h6 class="m-b-0 m-t-5">Information</h6>
									<hr/>
									<div class="form-group row">
										<label for="order_number" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Markerplace Order#</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['order_number'];?><br>
										</div>
									</div>
									<div class="form-group row">
										<label for="ship_by_date" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Ship By</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['ship_by_date'];?>
										</div>
									</div>
									<div class="form-group row">
										<label for="deliver_by_date" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Deliver By</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['deliver_by_date'];?>
										</div>
									</div>
									<div class="form-group row">
										<label for="your_earning" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Your Earning</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['your_earning'];?>
										</div>
									</div>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="row">
										<div class="col-10">
											<h6 class="m-b-0 m-t-5">item</h6>
											<hr/>
											<div class="form-group row">
												<div class="col-lg-12 col-md-12 col-sm-12">
													<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/edit_product/<?php echo $order['product_id'];?>"><?php echo $order['object_title'];?></a>
												</div>
												<div class="col-lg-12 col-md-12 col-sm-12">
													<span class="help-block"><small>Variation ID: <?php echo $order['variation_id'];?></small></span>
												</div>
											</div>
										</div>
										<div class="col-2">
											<h6 class="m-b-0 m-t-5">Qty</h6>
											<hr/>
											<div class="form-group row">
												<div class="col-lg-12 col-md-12 col-sm-12">
													x <?php echo $order['item_quantity'];?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
				    	<div class="col-3">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.orderdetail.submit()">Update</a>
								</div>
							</div>
						</div>
						<div class="card-box">
							<div class="row">
								<div class="col-12">
								<?php if(!empty($order['product_image'])):?>
                                	<a class="showGalleryFromArray"><img src="<?php echo base_url();?><?php echo $order['product_image'];?>" alt=""></a>
								<?php else:?>
                                	<a class="placeholder_image_anchor"><img src="<?php echo base_url();?>assets/images/placeholder500x500.jpg" alt=""></a>
								<?php endif;?>

								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->
	<script>
	$('select[name=order_status]').val('<?php echo $order['object_status'];?>');
	$('.selectpicker').selectpicker('refresh')
	</script>
    	<script>
    $('.showGalleryFromArray').on('click', function() {
        SimpleLightbox.open({
            items: [
                <?php if(!empty($order['product_gallery'])): foreach ($order['product_gallery'] as $image): ?>
                		'<?php echo base_url().$image->thumb;?>', 
                <?php endforeach; else:?>
                <?php endif;?>
            ]
        });
    });
  </script>