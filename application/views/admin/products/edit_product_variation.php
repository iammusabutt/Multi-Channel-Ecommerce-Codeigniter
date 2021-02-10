<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
					<a class="btn btn-gold waves-effect waves-light pull-right" onclick="document.addgamer.submit()">Save</a>
						<h4 class="page-title">Variation</h4>
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
			<?php $attributes = array('class' => 'form-horizontal', 'name' => 'addgamer');?>
			<?php echo form_open("admin/products/edit_product/". $this->uri->segment(4), $attributes);?>
			<div class="product_image_id">
				<input type="hidden" name="featured_image_id" value="" />
			</div>
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="portlet variants-section">
						<div class="portlet-heading bg-gold p-t-0 p-b-0">
							<div class="portlet-widgets portlet-header-sm">
								<a data-toggle="collapse" data-parent="#accordion1" href="#bg-primary">
									<h6 class="pull-left text-white m-0 m-r-10 l-h-34">Variants</h6>
									<div class="pull-right text-white">
										<i class="ion-minus-round"></i>
									</div>
									<div class="clearfix"></div>
								</a>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="bg-primary" class="panel-collapse collapse show">
							<div class="portlet-body">
								<div class="m-b-0">
									<ul class="list-group list-group-flush">
										<?php if(!empty($product['variations'])): foreach ($product['variations'] as $key => $value):?>
											<li class="list-group-item">
												<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/edit_product/<?php echo $product['object_id'];?>/variation/<?php echo $value['object_id'];?>" class="waves-effect btn-block variant-btn <?php if($this->uri->segment(6) == $value['object_id']){ echo 'active';};?>"><?php foreach ($value['attributes'] as $attribute):?><?php echo $attribute;?> <?php endforeach;?></a>
													
											</li>
										<?php endforeach; else:?>
										<?php endif;?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-9 col-md-9 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-2">
								<h6 class="m-b-0 m-t-5">Pricing</h6>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="product_price" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Selling Price</label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($variable_regular_price);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="variable_sku" class="col-lg-12 col-md-12 col-sm-12 col-form-label">SKU</label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($variable_sku);?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-box">
						<div class="row">
							<div class="col-2">
								<h6 class="m-b-0 m-t-5">Inventory</h6>
							</div>
						</div>
						<hr>
						<div class="card-box warehouse-locations">
							<div class="row">
								<div class="col-4">
									<h6 class="m-b-0 m-t-5">Balkan</h6>
									<span class="help-block"><small>Name of Warehouse.</small></span>
								</div>
								<div class="col-4">
									<?php echo form_input($variable_stock);?>
									<span class="help-block"><small>Available stock in hand.</small></span>
								</div>
								<div class="col-4">
									<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/<?php echo $this->uri->segment(2);?>/edit" class="btn btn-icon waves-effect waves-light btn-success"><i class="md md-check"></i> save</a>
									<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/<?php echo $this->uri->segment(2);?>/edit" class="btn btn-icon waves-effect waves-light btn-danger"><i class="md md-delete"></i> remove</a>
									<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/<?php echo $this->uri->segment(2);?>/edit" class="btn btn-icon waves-effect waves-light btn-primary"><i class="md md-history"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end col -->
			</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->
	<script src="<?php echo base_url();?>assets/plugins/select2/js/select2.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/products.js"></script>
	<script>
	$('#variable_tabs a.variations').on('shown.bs.tab', function (e) {
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    	e.stopPropagation();
    	var object_id = "<?php echo $this->uri->segment(4);?>";
    	var form_data = new FormData();
    	form_data.append("object_id", object_id)
    	var url = base_url + account_type + "/products/ajax_refresh_product_variation";

    	$.ajax({
			url: url,
			type: "POST",
			async:"false",
			dataType: "html",
			cache:false,
			contentType: false,
			processData: false,
			data: form_data, // serializes the form's elements.
			success: function(data)
			{				
				data = JSON.parse(data);
				if(data.response == "yes")
				{
					$('.variationlist').html(data.content);
					$('.selectpicker').selectpicker('refresh');
				}
			}
    	});
   	});
	$(document).on("click", "#uploadify", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
		var l = Ladda.create(this);
		l.start();
		var product_id = "<?php echo $this->uri->segment(4);?>";
		var image_type = $(this).data('image-type');
		if(image_type == 'product-image'){
			var uploadfile = $('.dropify')[0].files[0];
		} else {
			var uploadfile = $('.dropify-gallery')[0].files[0];
		}
			
		if (uploadfile) {
			type = uploadfile.type;
			var form_data = new FormData(); 
			form_data.append("file", uploadfile)
			form_data.append("product_id", product_id)
			form_data.append("image_type", image_type)
			var url = base_url + account_type + "/products/upload_file";

			$.ajax({
				url: url,
				type: "POST",
				async:"false",
				dataType: "html",
				cache:false,
				contentType: false,
				processData: false,
				data: form_data, // serializes the form's elements.
				success: function(data) {
					data = JSON.parse(data);
					if(data.response == "yes") {
						l.stop();
						$.Notification.autoHideNotify(data.response_type, 'top right', 'Success', data.message);
					} else if (data.response == "gallery") {
						l.stop();
						$(data.content).insertAfter( ".next-image" );
						$.Notification.autoHideNotify(data.response_type, 'top right', 'Success', data.message);
					}
				}
			});
		}else{
			l.stop();
			$.Notification.autoHideNotify('error', 'top right', 'Image not selected', 'Select an image for before you click upload.');
		}
	});

	$( '.attributelist' ).on( 'click', 'a.select_all_attributes', function() {
		$( this ).closest( '.card-body' ).find( 'select option' ).attr( 'selected', 'selected' );
		$( this ).closest( '.card-body' ).find( 'select' ).change();
		return false;
	});

	$( '.attributelist' ).on( 'click', 'a.select_no_attributes', function() {
		$( this ).closest( '.card-body' ).find( 'select option' ).removeAttr( 'selected' );
		$( this ).closest( '.card-body' ).find( 'select' ).change();
		return false;
	});
	$('select[name=vendor_id]').val("<?php echo $product['object_author'];?>");
	$('.selectpicker').selectpicker('refresh');
	</script>