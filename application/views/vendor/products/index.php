<div class="content dashboard">
	    <div class="container-fluid">
	        <!-- Page-Title -->
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="page-header-2">
						<div class="btn-group pull-right m-t-5">
							<button type="button" class="btn btn-gold dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Filter by</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1" x-placement="bottom-end" style="position: absolute; transform: translate3d(87px, 35px, 0px); top: 0px; left: 0px; will-change: transform;">
								<a class="dropdown-item" href="#">Unshipped Orders</a>
								<a class="dropdown-item" href="#">Shipped Orders</a>
								<a class="dropdown-item" href="#">Replacements</a>
							</div>
						</div>
	                    <ol class="breadcrumb pull-right mb-0">
	                        <li class="breadcrumb-item active">Products</li>
	                    </ol>
	                    <h5 class="page-title">Products</h5>
						<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=product&&action=add" class="btn btn-gold waves-effect waves-light">Add New</a>
	                    <div class="clearfix"></div>
	                </div>
	            </div>
	        </div>
	        <div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="table-responsive">
							<table id="datatable" class="table table-hover mails m-0 table table-actions-bar">
								<thead>
								<tr>
									<th></th>
									<th>Name</th>
									<th>SKU</th>
									<th>Price</th>
									<th>Vendor</th>
									<th>Status</th>
									<th>Date</th>
								</tr>
								</thead>

								<tbody>
								<?php if(!empty(isset($products))): foreach ($products as $product): ?>
								<tr class="action-row">
									<td>
										<?php if(!empty($product['product_image'])): ?>
											<img src="<?php echo base_url();?><?php echo $product['product_image'];?>" class="thumb-sm" alt="">
										<?php else:?>
                                			<img src="<?php echo base_url();?>assets/images/placeholder32x32.jpg" class="thumb-sm" alt="">
										<?php endif;?>
									</td>
									<td>
										<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=<?php echo $product['object_type'];?>&&product_id=<?php echo $product['object_id'];?>&&action=edit"><?php echo $product['object_title'];?></a>
										<small id="emailHelp" class="form-text text-muted action-links">
										<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=<?php echo $product['object_type'];?>&&product_id=<?php echo $product['object_id'];?>&&action=detail" class="text-success">details</a> | <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=<?php echo $product['object_type'];?>&&product_id=<?php echo $product['object_id'];?>&&action=edit" class="text-primary">edit</a> | <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=<?php echo $product['object_type'];?>&&product_id=<?php echo $product['object_id'];?>&&action=variations" class="text-inverse">variations</a> | <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=<?php echo $product['object_type'];?>&&product_id=<?php echo $product['object_id'];?>&&action=delete" class="text-danger">delete</a>
										</small>
									</td>
									<td><?php echo $product['product_sku'];?></td>
									<td><?php echo $product['product_price'];?></td>
									<td>
										<?php if(!empty(isset($product['assigned_vendors']))): foreach ($product['assigned_vendors'] as $vendor): ?>
											<code>[<?php echo $vendor['company'];?>]</code>
										<?php endforeach; else:?>
										<?php endif;?>
										</td>
									<td>
										<span class="label label-success"><?php echo $product['object_status'];?></span>
									</td>
									<td><?php echo $product['object_date'];?></td>
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
	    </div> <!-- container -->
	</div> <!-- content -->