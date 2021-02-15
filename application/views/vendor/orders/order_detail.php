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
		// echo '<pre>'; print_r($order); echo '</pre>';
	?>
	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<a class="btn btn-gold waves-effect waves-light pull-right" onclick="document.orderdetail.submit()">Update</a>
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
			<div class="row">
				<div class="col-md-8 col-sm-12">
					<form id="form" method="post" name="orderdetail" class="form-horizontal">
						<div class="card-box">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12">
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
								<div class="col-lg-6 col-md-6 col-sm-12">
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
						<?php if(!empty($order['order_items'])):?>
							<?php foreach ($order['order_items'] as $order_item):?>
							<div class="card-box">
								<div class="row">
									<div class="col-2">
										<div class="order-image-section">
											<?php if(!empty($order_item['product_image'])):?>
												<a class="showGalleryFromArray gallery_<?php echo $order_item['order_id'];?>"><img src="<?php echo base_url();?><?php echo $order_item['product_image'];?>" alt=""></a>
											<?php else:?>
												<a class="placeholder_image_anchor"><img src="<?php echo base_url();?>assets/images/placeholder500x500.jpg" alt=""></a>
											<?php endif;?>
										</div>
									</div>
									<div class="col-10">
										<div class="row">
											<div class="col-12">
												<h6 class="m-b-0 m-t-5"><?php echo $order_item['order_name'];?></h6>
											</div>
										</div>
										<hr/>
										<div class="row">
											<div class="col-10">
												<div class="form-group row">
													
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
											<div class="col-12 d-flex justify-content-between">
												<div>Current Default Warehouse: </div>
												<div></div>
											</div>
										</div>

									</div>
								</div><!-- end row -->
							</div> <!-- end card-box -->
							<?php endforeach;?>
						<?php else:?>
						<?php endif;?>
					<?php echo form_close();?>
				</div><!-- end col -->
			
				<div class="col-md-4 col-sm-12">
					<?php if(isset($order['delivery_type'])){?>
						<div class="card-box">
							<h6 class="m-b-0 m-t-5">Delivery Type</h6>
							<hr/>
							<div>
								<div class="col-lg-12 col-md-12 col-sm-12">
									<?php echo $order['delivery_type'];?>
								</div>
							</div>
						</div>

					<?php }?>
					<div class="card-box" style="max-height: 420px; overflow: auto;">
						<h6 class="m-b-0 m-t-5">Notes</h6>
						<hr>
						<div class="d-flex">
							<input type="text" class="form-control" id="note_comment">
							<button id="submit_comment" class="btn btn-primary btn-block w-25">
								<i class="fa fa-send"></i>
							</button>
						</div>
						<hr>
						<div id="fresh_notes">
							
						</div>
						<div id="default_notes">
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
	                    	<?php endif;?>
                    	</div>
					</div>
					
					
					
				</div>
			</div>
		</div> <!-- container -->
	</div> <!-- content -->
	<script>
		$('select[name=order_status]').val('<?php echo $order['object_status'];?>');
		$('.selectpicker').selectpicker('refresh');
	</script>
	<script>
		<?php if(!empty($order['order_items'])):?>
			<?php foreach ($order['order_items'] as $order_item):?>
			$('.gallery_<?php echo $order_item['order_id'];?>').on('click', function() {
				SimpleLightbox.open({
					items: [
						<?php if(!empty($order_item['product_gallery'])):?>
						<?php foreach ($order_item['product_gallery'] as $image): ?>
							'<?php echo base_url().$image->thumb;?>', 
						<?php endforeach;?>
						<?php else:?>
							'<?php echo base_url();?>assets/images/placeholder500x500.jpg'
						<?php endif;?>
					]
				});
			});
			<?php endforeach;?>
		<?php else:?>
		<?php endif;?>

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