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
        
                        <div class="col-sm-8">
                            <div class="product-right-info">
								<h4 class="page-title">
									<?php echo $product['object_title'];?>
								</h4>
								<?php if($product['product_type'] == 'variable'):?>
									<hr />
									<h5 class="font-600">Variations</h5>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12">
											<p><strong>Item</strong></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p><strong>Price</strong></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p><strong>Stock</strong></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p><strong>Status</strong></p>
										</div>
									</div>
									<?php if(isset($product['variations'])): foreach($product['variations'] as $variation):?>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12">
											<p class="text-muted"><?php echo $variation['variable_title'];?></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<p class="text-muted"><?php echo $variation['variable_regular_price'];?></p>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<?php if(!empty($variation['variable_stock']) || $variation['variable_stock'] > 0):?>
											<code>[ x <?php echo $variation['variable_stock'];?> ]</code>
											<?php else:?>
											<code>[ N/A ]</code>
											<?php endif;?>
										</div>
										<div class="col-lg-2 col-md-2 col-sm-12">
											<?php if(!empty($variation['variable_stock']) || $variation['variable_stock'] > 0):?>
												<span class="label label-success m-l-5">In Stock</span>
											<?php else:?>
												<span class="label label-danger m-l-5">Out of Stock</span>
											<?php endif;?>
										</div>
									</div>
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
                                        <tr>
                                            <td>Length x Width x Height</td>
                                            <td>
												<?php echo $product['product_length'];?> x <?php echo $product['product_width'];?> x <?php echo $product['product_height'];?>
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
  </script>