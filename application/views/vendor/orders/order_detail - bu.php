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
						<h4 class="page-title">Order #<?php echo $order['object_id'];?> Detail</h4>
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
								<div class="col-6">
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
								</div>
								<div class="col-6">
									<h6 class="m-b-0 m-t-5">Information</h6>
									<hr/>
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
							</div><!-- end row -->
						</div> <!-- end card-box -->
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="row">
										<div class="col-10">
											<h6 class="m-b-0 m-t-5">item</h6>
										</div>
										<div class="col-2">
											<h6 class="m-b-0 m-t-5">Qty</h6>
										</div>
									</div>
									<hr/>
									<?php if(!empty($order['order_items'])):?>
										<?php foreach ($order['order_items'] as $order_item):?>
										<div class="row">
											<div class="col-10">
												<div class="form-group row">
													<div class="col-lg-12 col-md-12 col-sm-12">
														<?php echo $order_item['order_name'];?>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12">
														<span class="help-block"><small>Variation ID: <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=product_variation&&product_id=<?php echo $order_item['product_id'];?>&&action=edit&&variation_id=<?php echo $order_item['variation_id'];?>"><?php echo $order_item['variation_id'];?></a></small></span> | <span class="help-block"><small>Vendor: <?php echo $order_item['vendor_name'];?></small></span>
													</div>
												</div>
											</div>
											<div class="col-2">
												<div class="form-group row">
													<div class="col-lg-12 col-md-12 col-sm-12">
														x <?php echo $order_item['item_quantity'];?>
													</div>
												</div>
											</div>
										</div>
										<?php endforeach;?>
									<?php else:?>
									<?php endif;?>
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

    $('#submit_comment').on('click', function(e) {
    	e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
		var l = Ladda.create(this);
		l.start();
        var note_comment = $('#note_comment').val();
        var object_id = <?php echo $order['object_id'];?>;
        var url = base_url + account_type + "/orders/ajax_note/" + object_id;

        // alert(url);
        $.ajax({
			url: url,
			type: "POST",
			dataType: "html",
			data:{
				note_comment:note_comment
				}, // serializes the form's elements.
			success: function(data) {
				if(data == "yes") {
					l.stop();
         	 		
					$.Notification.autoHideNotify('success', 'top right', 'Comment Submitted!');
					setTimeout(function(){
		                  location.reload();
		                }, 2500); 
					
				} else {
					l.stop();
					$.Notification.autoHideNotify('error', 'top right', 'Something went wrong');
				}
			}
		});
    });
  </script>