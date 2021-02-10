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
							<li class="breadcrumb-item"><a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products">Products</a></li>
							<li class="breadcrumb-item active">Add Product</li>
						</ol>
						<h4 class="page-title">Add Product</h4>
						<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/add_product?post_type=product" class="btn btn-gold waves-effect waves-light">Add New</a>
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
			<?php echo form_open(uri_string() . "/products/add_product?post_type=product", $attributes);?>
			<div class="product_image_id">
				<input type="hidden" name="featured_image_id" value="" />
			</div>
			<input type="hidden" id="object_id" name="object_id" value="<?php echo $object_id;?>" />
			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="vendor_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Vendors <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<select name="vendor_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
										<option selected="true" disabled="disabled">-- Select vendor --</option>
										<?php if(!empty($vendors)): foreach ($vendors as $vendor): ?>
											<option value="<?php echo $vendor->id;?>"><?php echo $vendor->company;?></option>
											<?php endforeach; else:?>
											<option>No Records Found</option>
											<?php endif;?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group row">
									<label for="product_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Product Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($product_name);?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="product_description" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Product Description</label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_textarea($product_description);?>
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end card-box -->
					<div class="card-box product_data_addon">
						<div class="row">
							<div class="col-2">
								<h6 class="m-b-0 m-t-5">Product Data</h6>
							</div>
							<div class="col-3">
								<select name="product_type" id="product_type" class="form-control selectpicker">
									<optgroup label="Product Type">
									<option value="simple">Simple Product</option>
									<option value="variable">Variable Product</option> 
									</optgroup>
								</select>
							</div>
						</div>
						<hr>
						<div class="tabs-vertical-env">
							<ul class="nav tabs-vertical" id="variable_tabs">
								<li class="nav-item variable visible" id="variable">
									<a href="#v-general" class="nav-link active" data-toggle="tab" aria-expanded="true"><i class="ti-settings m-r-5"></i> General</a>
								</li>
								<li class="nav-item variable visible" id="variable">
									<a href="#v-stock" class="nav-link" data-toggle="tab" aria-expanded="false"> <i class="ti-tag m-r-5"></i> Stock</a>
								</li>
								<li class="nav-item variable visible" id="variable">
									<a href="#v-shipping" class="nav-link" data-toggle="tab" aria-expanded="false"> <i class="ti-truck m-r-5"></i> Shipping</a>
								</li>
								<li class="nav-item variable visible" id="variable">
									<a href="#v-attributes" class="nav-link" data-toggle="tab" aria-expanded="false"><i class="ti-layout-grid2-thumb m-r-5"></i> Attributes</a>
								</li>
								<li class="nav-item simple" id="simple">
									<a href="#v-variations" class="nav-link variations" data-toggle="tab" aria-expanded="false"><i class="ti-layout-accordion-list"></i> Variations</a>
								</li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active" id="v-general">
									<div class="form-group row">
										<label for="product_price" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Price</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($product_price);?>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="v-stock">
									<div class="form-group row">
										<label for="product_sku" class="col-lg-12 col-md-12 col-sm-12 col-form-label">SKU</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($product_sku);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="product_stock" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Stock</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($product_stock);?>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="v-shipping">
									<div class="form-group row">
										<label for="share_percentage" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Dimensions</label>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<?php echo form_input($product_length);?>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<?php echo form_input($product_width);?>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<?php echo form_input($product_height);?>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="v-attributes">
									<div class="row">
										<div class="col-6">
											<div class="form-group row">
												<div class="col-lg-12 col-md-12 col-sm-12">
													<select name="attribute_value" id="attribute_value" class="selectpicker" data-style="btn-white">
														<?php if(!empty($all_attributes)):?>
														<optgroup label="Add Product Attributes">
														<?php foreach ($all_attributes as $attr):?>
															<option value="<?php echo $attr->attribute_slug;?>"><?php echo $attr->attribute_name;?></option>
														<?php endforeach;?>
														</optgroup>
														<?php else:?>
														<option>No Records Found</option>
														<?php endif;?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-6">
											<a href="#" id="select_attribute" data-object-id="<?php echo $object_id;?>" class="btn btn-gold waves-effect waves-light">Add</a>
										</div>
									</div>
									<hr>
									<div class="attributelist accordion-section attribute-rows row">
									<?php if(!empty($new_attributes)): foreach ($new_attributes as $index => $block):?>
										<div class="col-lg-12">
											<div class="card-header single-row p-0" id="headingOne">
												<span href="#<?php echo $block['attribute_slug'];?>" class="text-dark anchor-arch" data-toggle="collapse" aria-expanded="true" aria-controls="<?php echo $block['attribute_slug'];?>">
													<h6 class="m-0">
														<small class="text-muted">
															<a id="delete_attribute" data-object-id="<?php echo $object_id;?>" data-attributevalue="<?php echo $block['attribute_slug'];?>" class="remove_row delete text-danger">Remove</a>
														</small>
														<?php echo $block['attribute_slug'];?></h6>
												</span>
											</div>
											<div class="collapse" id="<?php echo $block['attribute_slug'];?>">
												<div class="card-body">
													<select id="<?php echo $block['attribute_slug'];?>" name="attribute_values[<?php echo $index;?>][<?php echo $block['attribute_slug'];?>][]" class="form-control select2" multiple="multiple" data-placeholder="Select terms">
													<?php if(!empty($block['attribute_values'])): foreach ($block['attribute_values'] as $index => $value):?>
														<option value="<?php echo strtolower($value['name']);?>" <?php if(!empty($value['object_id'])) { echo 'selected="selected"';} else { echo '';};?>><?php echo $value['name'];?></option>
													<?php endforeach;?>
													<?php endif;?>
													</select>
														<a class="label btn-gold btn-custom pointer select_all_attributes m-t-10">Select all</a>
														<a class="label btn-gold btn-custom pointer select_no_attributes m-t-10">Select none</a>
												</div>
											</div>
										</div>
									<?php endforeach; else:?>
									<?php endif;?>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											<a id="save_attribute" data-object-id="<?php echo $object_id;?>" class="btn btn-gold waves-effect waves-light">Save attributes</a>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="v-variations">
									<div class="row">
										<div class="col-6">
											<div class="form-group variation-select row">
												<div class="col-lg-12 col-md-12 col-sm-12">
													<select name="variation_action" id="variation_action" class="selectpicker" data-style="btn-white">
															<option value="add_product_variation">Add Variation</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-6">
											<a id="add_variation" data-object-id="<?php echo $object_id;?>" class="btn btn-gold waves-effect waves-light">Go</a>
										</div>
									</div>
									<hr>
									
									<div class="variationlist accordion-section variation-rows row">
									<?php if(!empty($product_variations)): foreach ($product_variations as $index => $variation):?>
										<input type="hidden" name="variation_id[]" value="<?php echo $variation['object_id'];?>">
										<div class="col-lg-12">
											<div class="card-header single-row p-0" id="heading<?php echo $variation['object_id'];?>">
												<div class="attributes-sections m-t-15">
													<strong>#<?php echo $variation['object_id'];?> </strong>
												</div>
												<?php if(!empty($attribute_terms)): foreach ($attribute_terms as $attribute => $a_terms):?>
													<div class="attributes-sections">
														<select name="attribute_<?php echo $attribute;?>[]" id="<?php echo $variation['object_id'];?>_attribute_<?php echo $attribute;?>" class="selectpicker" data-style="btn-white">
															<option value="" class="option-disabled">Any <?php echo $attribute;?></option>
															<?php foreach ($a_terms as $a_term):?>
																<option value="<?php echo $a_term;?>"><?php echo $a_term;?></option>
															<?php endforeach;?>
														</select>
														<script>												
															$('select[id=<?php echo $variation['object_id'];?>_attribute_<?php echo $attribute;?>]').val("<?php echo $variation['attribute_'.$attribute];?>");
															$('.selectpicker').selectpicker('refresh');
														</script>
													</div>
												<?php endforeach; else:?>
												<?php endif;?>
												<span href="#collapse<?php echo $variation['object_id'];?>" class="text-dark anchor-vari" data-target="#collapse<?php echo $variation['object_id'];?>" data-toggle="collapse" aria-controls="collapse<?php echo $variation['object_id'];?>">
													<h6 class="m-0">
														<small class="text-muted">
															<a id="delete_variation" data-object-id="<?php echo $object_id;?>" data-variation-id="<?php echo $variation['object_id'];?>" class="remove_row delete text-danger m-t-5">Remove</a>
														</small>
													</h6>
												</span>
											</div>
											<div class="collapse" aria-labelledby="heading<?php echo $variation['object_id'];?>" id="collapse<?php echo $variation['object_id'];?>">
												<div class="card-body">
													<div class="form-group row">
														<label for="variable_sku" class="col-lg-12 col-md-12 col-sm-12 col-form-label">SKU</label>
														<div class="col-lg-12 col-md-12 col-sm-12">
															<input type="text" name="variable_sku[]" value="<?php if(!empty($variation['variable_sku'])){echo $variation['variable_sku'];} else {echo '';}?>" id="variable_sku" class="form-control" placeholder="Stock Keeping Unit">
														</div>
													</div>
													<div class="form-group row">
														<label for="variable_regular_price" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Regular Price <span class="text-danger">*</span></label>
														<div class="col-lg-12 col-md-12 col-sm-12">
															<input type="text" name="variable_regular_price[]" value="<?php if(!empty($variation['variable_regular_price'])){echo $variation['variable_regular_price'];} else {echo '';}?>" id="variable_regular_price" class="form-control" placeholder="Variation Price (required)">
														</div>
													</div>
													<div class="form-group row">
														<label for="variable_stock" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Stock</label>
														<div class="col-lg-12 col-md-12 col-sm-12">
															<input type="text" name="variable_stock[]" value="<?php if(!empty($variation['variable_stock'])){echo $variation['variable_stock'];} else {echo '';}?>" id="variable_stock" class="form-control" placeholder="Stock">
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; else:?>
									<?php endif;?>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											<a id="save_variation" data-object-id="<?php echo $object_id;?>" class="btn btn-gold waves-effect waves-light">Save variation</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end col -->
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<a class="btn btn-gold btn-block waves-effect waves-light" onclick="document.addgamer.submit()">Add Product</a>
							</div>
						</div>
					</div>
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<h4 class="header-title m-t-0">Categories</h4>
								<a class="label label-success btn-default" href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/categories">Add new category</a>
								<div class="mt-3">
									<?php if(!empty($terms)): foreach ($terms as $term): ?>
									<div class="custom-control custom-checkbox">					
										<?php echo form_checkbox('term_id[]', $term['term_taxonomy_id'], FALSE, 'id="category_'.$term['name'].'" class="custom-control-input"');?>
										<label for="category_<?php echo $term['name'];?>" class="custom-control-label"><?php echo $term['name'];?></label>
									</div>
									<?php endforeach; else:?>
									<?php endif;?>
								</div>
							</div>
						</div>
					</div>
					<div class="card-box">
						<div class="row">
							<div class="col-md-12 portlets">
								<div class="m-b-0">
									<input type="file" class="dropify" name="file" data-height="120" data-allowed-file-extensions="jpeg jpg png"  data-show-remove="false">
									<span class="help-block"><small>Image size minimum 550px x 424px</small></span>
									<button id="uploadify" data-image-type="product-image" data-product-id="<?php echo $object_id;?>" class="btn btn-default btn-block waves-effect waves-light">Upload</button>
								</div>
							</div>
						</div>
					</div>
					<div class="portlet">
						<div class="portlet-heading bg-gold p-t-0 p-b-0">
							<div class="portlet-widgets portlet-header-sm">
								<a data-toggle="collapse" data-parent="#accordion1" href="#bg-gallery">
									<h6 class="pull-left text-white m-0 m-r-10 l-h-34">Product gallery</h6>
									<div class="pull-right text-white">
										<i class="ion-minus-round"></i>
									</div>
									<div class="clearfix"></div>
								</a>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="bg-gallery" class="panel-collapse collapse show">
							<div class="portlet-body">
								<div class="m-b-0">
								<?php if(!empty($product_image->thumb)){;?>
									<input type="file" class="dropify-gallery" name="file" data-height="120" data-allowed-file-extensions="jpeg jpg png"  data-show-remove="false">
								<?php } else {;?>
									<input type="file" class="dropify-gallery" name="file" data-height="120" data-allowed-file-extensions="jpeg jpg png"  data-show-remove="false">
								<?php } ;?>
									<button id="uploadify" data-product-id="<?php echo $object_id;?>" data-image-type="product-gallery" class="btn btn-default btn-block waves-effect waves-light m-t-10">Upload</button>
									<hr>
									<div class="row">
										<div class="next-image"></div>
										<?php if(!empty($product_image)): foreach ($product_image as $image): ?>
										<div class="col-lg-4 col-md-4 col-sm-4 gallery-img m-b-20">
											<img src="<?php echo base_url().$image->thumb;?>" alt="image" class="card-img-top img-fluid">
										</div>
										<?php endforeach; else:?>
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
	<script src="<?php echo base_url();?>assets/plugins/select2/js/select2.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/products.js"></script>
	<script>
	$('#variable_tabs a.variations').on('shown.bs.tab', function (e) {
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    	e.stopPropagation();
    	var object_id = "<?php echo $object_id;?>";
    	var form_data = new FormData();
    	form_data.append("object_id", object_id)
    	// var url = base_url + "admin/products/ajax_refresh_product_variation";
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
		var product_id = $(this).data('product-id');
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
			// var url = base_url + "admin/products/upload_file";
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
						$('.product_image_id').html(data.content);
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
	$('select[name=product_vendor_id]').val("<?php echo $product['object_author'];?>");
	$('.selectpicker').selectpicker('refresh');
	</script>
