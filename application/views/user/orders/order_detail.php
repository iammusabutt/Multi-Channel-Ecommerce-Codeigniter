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
	<?php 
		// echo '<pre>'; print_r($order); echo '</pre>';exit(); 
	?>
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
					<div class="col-md-9 col-sm-12">
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
											<?php echo form_input($object_status);?>
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
								<?php if(isset($order['delivery_type'])){?>
									<div class="form-group row">
										<label for="delivery_type" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Delivery Type</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo $order['delivery_type'];?>
										</div>
									</div>
								<?php }?>
								</div>
								<div class="col-4">
									<h6 class="m-b-0 m-t-5">Information</h6>
									<hr/>
									<div class="form-group row">
										<label for="order_number" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Markerplace Order#</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<br>
										</div>
									</div>
									<div class="form-group row">
										<label for="order_number" class="col-lg-12 col-md-12 col-sm-12 col-form-label p-b-0">Your Earnings</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<br>
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
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="row">
										<div class="col-8">
											<h6 class="m-b-0 m-t-5">Item</h6>
										</div>
										<div class="col-2">
											<h6 class="m-b-0 m-t-5">Qty</h6>
										</div>
										<div class="col-2">
											<h6 class="m-b-0 m-t-5">Status</h6>
										</div>
									</div>
									<hr/>
									<?php if(!empty($order['order_items'])):?>
										<?php foreach ($order['order_items'] as $order_item):?>
										<div class="row">
											<div class="col-8">
												<div class="form-group row">
													<div class="col-lg-12 col-md-12 col-sm-12">
														<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=product&&product_id=<?php echo $order_item['product_id'];?>&&vendor_id=<?php echo $order_item['vendor_id'];?>&&action=detail"><?php echo $order_item['order_name'];?></a>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12">
														<span class="help-block"><small>Variation ID: <?php echo $order_item['variation_id'];?></small></span> | <span class="help-block"><small>Vendor: <?php echo $order_item['vendor_name'];?></small></span>
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
											<div class="col-2">
												<?php if($order_item['order_status'] =="pending"):?>
													<div class="form-group row">
														<div class="col-lg-12 col-md-12 col-sm-12">
															<a style="color:white;" id="change_status" class="btn btn-dark" data-vendor="<?=$order_item['vendor_id']?>" data-object="<?=$order_item['order_id']?>" data-product="<?=$order_item['product_id']?>" data-variation="<?=$order_item['variation_id']?>" data-quantity="<?=$order_item['item_quantity']?>" data-status="<?=$order_item['order_status']?>"
															>Change Status</a>
														</div>
													</div>
												<?php else:?>
													<div class="form-group row">
														<div class="col-lg-12 col-md-12 col-sm-12">
															<?=$order_item['order_status']?>
														</div>
													</div>
												<?php endif;?>
											</div>
										</div>
										<?php endforeach;?>
									<?php else:?>
									<?php endif;?>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
					<div class="col-md-3 col-sm-12">
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
						<div class="card-box" style="max-height: 250px; overflow: auto;">
							<div class="row">
								<div class="col-12">
									<h6 class="m-b-0 m-t-5">Notes</h6>
									<hr>
								</div>
								
								<div class="col-12">
									<div class="d-flex">
										<input type="text" class="form-control" id="note_comment">
										<button id="submit_comment" class="btn btn-primary btn-block w-25">
											<i class="fa fa-send"></i>
										</button>
									</div>
									<hr>
								</div>
								<div class="col-12">
									<div id="fresh_notes">
										
									</div>	
								</div>
								
								<div id="default_notes">
									<div class="col-12">
										<?php if(!empty($notes)): ?>
											<?php foreach ($notes as $key){?>
												<div class="card-box <?php if($key->note_author == $this->session->userdata('username')){echo 'bg-gold';}?>" style="padding: 20px !important">
													<p><?=$key->note_content?></p>
													<div class="d-flex justify-content-between" style="font-size:11px;">
														<?php if($key->note_author == $this->session->userdata('username')){ ?>
															<p><b>By:</b> You</p>
														<?php } else{ ?>
															<p><b>By:</b> <?=$key->note_author?></p>
														<?php } ?>
													</div>
												</div>
											<?php } ?>
		                                	
										<?php else:?>
		                                	
										<?php endif;?>
									</div>
								</div>

								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->

<!-- Modal -->
<div class="modal fade" id="check_status" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Item Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      	<form method="POST">
      		<div class="modal-body">
        		<input type="hidden" id="modal_product_id">
        		<input type="hidden" id="modal_vendor_id">	    
        		<input type="hidden" id="modal_object_id">	    
        		<input type="hidden" id="modal_variation_id">	    
        		<input type="hidden" id="modal_quantity">
	        	<div class="form-group">
	        		<label>Order Status</label>
	        		<select id="modal_status" class="form-control selectpicker" required>
	        			<option selected disabled="">Select Action</option>
	        			<option value="Return To User">Return To User</option>
	        			<option value="Return To Vendor">Return To Vendor</option>
	        		</select>
	        	</div>
	        	<div class="form-group">
	        		<label>Note</label>
	        		<textarea rows="1" class="form-control" id="modal_note_comment" required></textarea>
	        	</div>
        
	      	</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" id="change-item-status" class="btn btn-success" disabled>Save changes</button>
			</div>
	    </form>
    </div>
  </div>
</div>


	<script>
	$('select[name=order_status]').val('<?php echo $order['object_status'];?>');
	// $('.selectpicker').selectpicker('refresh');
	</script>
	<script>
	$('#change_status').on('click', function(e){
		// var l = Ladda.create(this);
		// l.start();
		$('#check_status').modal('show');
		var product_id = $(this).data('product');
		var vendor_id = $(this).data('vendor');
		var object_id = $(this).data('object');
		var variation_id = $(this).data('variation');
		var quantity = $(this).data('quantity');
		var status = $(this).data('status');
		
		$('#modal_product_id').val(product_id);
		$('#modal_vendor_id').val(vendor_id);
		$('#modal_object_id').val(object_id);
		$('#modal_variation_id').val(variation_id);
		$('#modal_quantity').val(quantity);
		$('#modal_status').val(status);
		// l.stop();
	});
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
    $('#modal_status').change(function(){
    	if($('#modal_status option:selected').text() == "Return To User"){
			$('#change-item-status').removeAttr('disabled');
			$('#change-item-status').removeAttr('disabled', 'disabled');
		}else if($('#modal_status option:selected').text() == "Return To Vendor"){
			$('#change-item-status').removeAttr('disabled');
			$('#change-item-status').removeAttr('disabled', 'disabled');
		}else{
			$('#change-item-status').attr('disabled');
            $('#change-item-status').attr('disabled','disabled');
		}
	});
    $('#change-item-status').on('click', function(e) {
    	e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
		var l = Ladda.create(this);
		l.start();
		var product_id = $('#modal_product_id').val();
		var vendor_id = $('#modal_vendor_id').val();
		var variation_id = $('#modal_variation_id').val();
		var quantity = $('#modal_quantity').val();
		var modal_status = $('#modal_status').val();
		var order_id = $('#modal_object_id').val();
		var note_comment = $('#modal_note_comment').val();
        var object_id = <?php echo $order['object_id'];?>;
        var url = base_url + account_type + "/orders/ajax_note/" + object_id;
        var url_orig = base_url + account_type + "/orders/item_status/" + object_id;
        $.ajax({
			url: url_orig,
			type: "POST",
			dataType: "html",
			data:{
				product_id:product_id,
				vendor_id:vendor_id,
				order_id:order_id,
				variation_id:variation_id,
				quantity:quantity,
				modal_status:modal_status,
				note_comment:note_comment,
			}, // serializes the form's elements.
			success: function(data) {
				
				if (data == "yes") {
					// l.stop();
					// $.Notification.autoHideNotify('success', 'top right', 'Status Changed', 'Status Of Order Item Changes Successfully');
         	 		$.ajax({
						url: url,
						type: "POST",
						dataType: "html",
						data:{
							note_comment:note_comment
						}, // serializes the form's elements.
						success: function(data) {
							// console.log(data);
							data = JSON.parse(data);
							if (data.response == "yes") {
								l.stop();
			         	 		$('#fresh_notes').html(data.content);
								$.Notification.autoHideNotify('success', 'top right', 'Comment Submitted', data.message);
								$('#default_notes').addClass('d-none');
								// $('#note_comment').val('');
								setTimeout(function(){ 
								     location.reload();
								}, 5000);
							} else {
								l.stop();
								$.Notification.autoHideNotify('error', 'top right', 'Something Went Wrong', data.message);
							}
						}
					});
				} else {
					l.stop();
					$.Notification.autoHideNotify('error', 'top right', 'Something Went Wrong', 'Make Sure Fields are not Empty.');
				}
			}
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
				// console.log(data);
				data = JSON.parse(data);
				if (data.response == "yes") {
					l.stop();
         	 		$('#fresh_notes').html(data.content);
					$.Notification.autoHideNotify('success', 'top right', 'Comment Submitted', data.message);
					$('#default_notes').addClass('d-none');
					$('#note_comment').val('');
				} else {
					l.stop();
					$.Notification.autoHideNotify('error', 'top right', 'Something Went Wrong', data.message);
				}
			}
		});
    });
  </script>