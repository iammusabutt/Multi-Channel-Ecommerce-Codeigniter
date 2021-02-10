<div class="content">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-2">
                    <ol class="breadcrumb pull-right mb-0">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products">Products</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                    <h5 class="page-title">Products</h5>
                    <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/add_product?post_type=product"
                        class="btn btn-gold waves-effect waves-light">Add New</a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card-box product-detail-box">
                    <div class="row">
                    	  <div class="col-sm-4">
								<?php if(!empty($product['product_image'])):?>
                                	<a class="showGalleryFromArray"><img src="<?php echo base_url();?><?php echo $product['product_image'];?>" alt=""></a>
								<?php else:?>
                                	<a class="placeholder_image_anchor"><img src="<?php echo base_url();?>assets/images/placeholder500x500.jpg" alt=""></a>
								<?php endif;?>
                            <?php
                           // echo "<pre>"; 
                            //print_r($product_gallery);exit(); ?>

                        </div>
        
                        <div class="col-md-8">
                            <div class="product-right-info">
								<h4 class="page-title">
									<?php echo $product['object_title'];?>
								</h4>
								<hr />
								
								<div class="row">
									<div class="col-md-6">
									<form name="orderlist" class="variations_form cart" method="post" data-product_id="51" data-product_variations="<?php if(isset($attributes_json)) {echo $attributes_json;}?>">
									<div class="variations">
										<?php if(isset($attribute_terms)):?>
											<?php if(!empty($attribute_terms)): foreach ($attribute_terms as $attribute => $a_terms):?>
												<div class="form-group row">
													<label for="share_percentage" class="col-lg-12 col-md-12 col-sm-12 col-form-label text-capitalize"><?php echo $attribute;?></label>
													<div class="col-lg-12 col-md-12 col-sm-12">
													<?php 
													if(isset($attributes_json))
													 {	
														 
														 $test=strval($attributes_json);
													
														}
													?>
														<select onchange='variation()' name="attribute_<?php echo $attribute;?>[]" data-attribute_name="<?php echo $attribute;?>" id="var_<?php echo $attribute;?>" class="selectpicker var_<?php echo $attribute;?> " data-style="btn-white">
															<option value="" class="option-disabled">Select</option>
															<?php foreach ($a_terms as $a_term):?>
																<option value="<?php echo $a_term;?>"><?php echo $a_term;?></option>
															<?php endforeach;?>
														</select>
													</div>
												</div>
											<?php endforeach; else:?>
											<?php endif;?>
											<div class="row">
												<div class="single_variation_wrap">
													<div class="variation-add-to-cart variations_button variation-add-to-cart-enabled">
														<input type="hidden" id="product_id" name="product_id" value="<?php echo $_GET['product_id'];?>">
														<input type="hidden" id="variation_id" name="variation_id" class="variation_id" value="">
														<input type="hidden" id="vendor_id" name="vendor_id" class="vendor_id" value="<?php echo $_GET['vendor_id'];?>">
													</div>
												</div>
												<div class="col-md-6">
													<a class="btn btn-gold btn-block waves-effect waves-light" id="add_to_cart">Add to Order List</a> 
												</div>
												<div class="col-md-6">
													<a class="btn btn-inverse btn-block waves-effect waves-light">Order Now</a>
												</div>
											</div>
											<?php else:?>
												<p></p>
												<div class="alert alert-danger">
													Product is currently out of stock and unavailable for this vendor.
												</div>
											<?php endif;?>
										</div>
									</form>
									</div>
									<div class="col-md-6">
										<div class="card m-b-20 card-body text-xs-center">
											<h5 class="card-title">Instructions</h5>
											<p class="card-text">This card has supporting text below as a natural lead-in to
												additional content.</p>
											<p class="card-text">This card has supporting text below as a natural lead-in to
												additional content.</p>
											<p class="card-text">This card has supporting text below as a natural lead-in to
												additional content.</p>
											<p class="card-text">
												<small class="text-muted">Last updated 3 mins ago</small>
											</p>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-box/Product detai box -->
            </div> <!-- end col -->
		</div> <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card-box product-detail-box">
        			<div class="row">
						<div class="col-md-12">
                            <div class="product-right-info">
								<h4 class="page-title">Stock Information</h4>
								<?php if($product['product_type'] == 'variable'):?>
									<hr />
									<h5 class="font-600">Variations</h5>
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-12">
											<p><strong>Item</strong></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p><strong>Price</strong></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p><strong>Locations</strong></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p><strong>Stock</strong></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p><strong>Status</strong></p>
										</div>
									</div>
									<?php if(isset($product['variations'])): foreach($product['variations'] as $variation):?>
										<?php if(!empty($variation['warehouses_stock'])):?>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-12">
												<p class="text-muted">
													
												<?php $str = implode (", ", $variation['attributes']); echo $str;?></p>
											</div>
											
											<div class="col-lg-2 col-md-2 col-sm-12">
												<?php if(!empty($variation['variable_regular_price'])):?>
													<p class="text-muted"><?php echo $variation['variable_regular_price'];?></p>
												<?php else:?>
												<code>[unavailable]</code>
												<?php endif;?>
											</div>
											<div class="col-lg-2 col-md-2 col-sm-12">
												<?php if(!empty($variation['variable_regular_price'])):?>
													<?php if($variation['manage_stock'] == 'yes'):?>
														<a tabindex="0" role="button" data-placement="left" data-trigger="focus" class="pointer m-b-0" data-toggle="popover" data-content='<table class="table table-sm">
																<thead>
																	<tr>
																		<th>Location</th>
																		<th>Stock</th>
																	</tr>
																</thead>
																<?php if(!empty($variation['warehouses_stock'])): foreach($variation['warehouses_stock'] as $location):?>
																	<tr>
																		<td><?php echo $location->warehouse_name;?> (<?php echo $location->warehouse_location;?>)</td>
																		<td>
																			<?php echo $location->stock_quantity;?>
																		</td>
																	</tr>
																<?php endforeach; endif;?>
															</table>' data-html="true">(multiple)</a>
													<?php else:?>
													<code>[unmanaged]</code>
													<?php endif;?>
												<?php else:?>
												<code>[unavailable]</code>
												<?php endif;?>
											</div>
											<div class="col-lg-2 col-md-2 col-sm-12">
												<?php if(!empty($variation['variable_regular_price'])):?>
													<?php if($variation['manage_stock'] == 'yes'):?>
														<code>[ x <?php echo $variation['total_stock'];?> ]</code>
													<?php else:?>
													<code>[unmanaged]</code>
													<?php endif;?>
												<?php else:?>
												<code>[unavailable]</code>
												<?php endif;?>
											</div>
											<div class="col-lg-2 col-md-2 col-sm-12">
												<?php if(!empty($variation['variable_regular_price'])):?>
													<?php if($variation['manage_stock'] == 'yes'):?>
														<?php if(!empty($variation['total_stock']) || $variation['total_stock'] > 0):?>
															<span class="label label-success m-l-5">In Stock</span>
														<?php else:?>
															<span class="label label-danger m-l-5">Out of Stock</span>
														<?php endif;?>
													<?php else:?>
														<span class="label label-inverse m-l-5">Un metered</span>
													<?php endif;?>
												<?php else:?>
													<span class="label label-danger m-l-5">Out of Stock</span>
												<?php endif;?>
											</div>
										</div>
										<?php endif;?>
									<?php endforeach; endif;?>
									<hr />
									<?php if(isset($product['assigned_vendors'])):?>
											<p class="text-muted"><strong>Vendors selling this product:</strong>
										<?php foreach($product['assigned_vendors'] as $vendor):?>
											<span class="label label-inverse m-l-5"><?php echo $vendor['company'];?></span>
										<?php endforeach;?>
										</p>
									<?php endif;?>
								<?php else:?>
									<?php if(!empty($product['product_stock']) || $product['product_stock'] > 0):?>
										<span class="label label-success m-l-5">In Stock</span>
									<?php else:?>
										<span class="label label-danger m-l-5">Out of Stock</span>
									<?php endif;?>
									<div class="row">
										<div class="col-lg-2 col-md-2 col-sm-12">
											<h4><?php echo $product['product_price'];?></h4>
										</div>
									</div>
									<?php if(!empty($product['product_stock']) || $product['product_stock'] > 0):?>
										<p class="text-muted"><strong>Stock:</strong> <code>[ x <?php echo $product['product_stock'];?> ]</code></p>
									<?php else:?>
									<?php endif;?>
								<?php endif;?>
								<hr />
                            </div>
                        </div>
					</div>
                </div> <!-- end card-box/Product detai box -->
            </div> <!-- end col -->
		</div> <!-- end row -->
		
        <div class="row">
            <div class="col-12">
                <div class="card-box product-detail-box">
					<ul class="nav nav-pills navtab-bg nav-justified">
						<li class="nav-item">
							<a href="#description" data-toggle="tab" aria-expanded="true" class="nav-link active">
								Product Description
							</a>
						</li>
						<li class="nav-item">
							<a href="#specification" data-toggle="tab" aria-expanded="false" class="nav-link">
								Specifications
							</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="description">
							<h4 class="font-18">Product Description</h4>
							<p class="text-muted"><?php echo $product['object_content'];?></p>
						</div>
						<div class="tab-pane fade" id="specification">
                            <h4 class="font-18"><b>Specifications:</b></h4>
                            <div class="table-responsive m-t-20">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td width="400">SKU</td>
                                            <td>
												<?php echo $product['product_sku'];?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
                </div> <!-- end card-box/Product detai box -->
            </div> <!-- end col -->
		</div> <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->
<script>
	$("form").each(function(){
		var asd = $(this).find('select') //<-- Should return all input elements in that specific form.
		//console.log(asd[0].attributes.name);
	});
		
	function variation(){
		let variation_id='';
		let dynamic_exp='';
		let m;
		let regex1=/"\d*"$/
		let array=<?php echo $attributes_json;?>;
		var data=JSON.stringify(array);

		var phpvariable = <?php echo json_encode($attribute_terms);?>;
		
		for (var key in phpvariable) {
			var var_class =$( "#var_"+key ).val();
			let temp=`"`+key+`":?"`+var_class+`",?`;
			dynamic_exp=dynamic_exp+temp;
		}
		dynamic_exp=dynamic_exp+'\\D,"variation_id":"\\d{0,10}"';
		var re=RegExp(dynamic_exp,'g');

		if ((m = re.exec(data)) !== null) {
			m.forEach((match, groupIndex) => {

				m = regex1.exec(match);
				variation_id=m[0]
				variation_id=JSON.parse(variation_id)
				$( "#variation_id" ).attr('value',variation_id);;
			});
		}

		
	}
	$(document).on("click", "#add_to_cart", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
    	var product_id = $("#product_id").val();
    	var variation_id = $("#variation_id").val();
    	var vendor_id = $("#vendor_id").val();;

		var form_data = new FormData();
		form_data.append("product_id", product_id)
		form_data.append("variation_id", variation_id)
		form_data.append("vendor_id", vendor_id)
		var url = "<?php echo base_url();?>user/products/ajax_add_to_cart";

		$.ajax({
			url: url,
			type: "POST",
			async: "false",
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			data: form_data, // serializes the form's elements.
			success: function(data) {
				data = JSON.parse(data);
				if (data.response == "yes") {
					$('.cart_item').html(data.content);
					$.Notification.autoHideNotify('success', 'top left', 'Added to Cart', data.message);
				} else {
					$.Notification.autoHideNotify('error', 'top left', 'Stock Limit Reached', data.message);
				}
			}
		});
	});
    $('.showGalleryFromArray').on('click', function() {
        SimpleLightbox.open({
            items: [
                <?php if(!empty($product_gallery)): foreach ($product_gallery as $image): ?>
                		'<?php echo base_url().$image->thumb;?>', 
                <?php endforeach; else:?>
                <?php endif;?>
            ]
        });
	});
	$(function(){
		$(document).on('click', "#commentPopover", function(e){
			var variation_id = $(this).data('variation-id');
			console.log(variation_id);
			$(this).popover({
				html : true, 
				placement : "left",
				trigger : "focus",
				content: function() {
					return $('#popover'+variation_id).html();
				}
			});
		});
	});
  </script>