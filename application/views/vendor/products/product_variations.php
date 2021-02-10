<div class="content dashboard">
	    <div class="container-fluid">
	        <!-- Page-Title -->
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="page-header-2">
						<a class="btn btn-gold waves-effect waves-light pull-right" onclick="document.bulkupdate.submit()">Bulk Update</a>
	                    <h5 class="page-title"><?php echo $product['object_title'];?></h5>
	                    <div class="clearfix"></div>
	                </div>
	            </div>
	        </div>
			<form id="form" method="post" name="bulkupdate" class="form-horizontal">
	        <div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="table-responsive">
							<table class="table table-hover mails m-0 table table-actions-bar">
								<thead>
									<tr>
										<th>Name</th>
										<th>SKU</th>
										<th>Price</th>
										<th>Managed</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php if(!empty(isset($product['variations']))): foreach ($product['variations'] as $variation): ?>
								<tr class="action-row">
									<td>
										<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=<?php echo $product['object_type'];?>&&product_id=<?php echo $product['object_id'];?>&&action=edit"><?php echo $variation['variable_title'];?></a>
									</td>
									<td>
										<input type="text" name="variable_sku[<?php echo $variation['object_id'];?>]" value="<?php echo $variation['variable_sku'];?>" id="variable_sku" class="form-control" placeholder="SKU"></td>
									<td>
										<input type="text" name="variable_regular_price[<?php echo $variation['object_id'];?>]" value="<?php echo $variation['variable_regular_price'];?>" id="variable_regular_price" class="form-control" placeholder="Regular Price">
									</td>
									<td>
										<?php if($variation['manage_stock'] == 'yes'): ?>
											<span class="label label-success"><?php echo $variation['manage_stock'];?></span>
										<?php else:?>
                                			<span class="label label-danger"><?php echo $variation['manage_stock'];?></span>
										<?php endif;?>
									</td>
									<td>
										<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=product_variation&&product_id=<?php echo $product['object_id'];?>&&action=edit&&variation_id=<?php echo $variation['object_id'];?>" class="btn btn-gold waves-effect waves-light">Edit</a>
									</td>
								</tr>
								<?php endforeach; else:?>
								<tr>
									<td class="center text-center" colspan="10">No Records Found</td>
								</tr>
								<?php endif;?>
								</tbody>
							</table>
						</div>
					</div>
				</div> <!-- end col -->
			</div>
			<?php echo form_close();?>
	    </div> <!-- container -->
	</div> <!-- content -->