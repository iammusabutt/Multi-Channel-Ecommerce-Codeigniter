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
	                    <div class="clearfix"></div>
	                </div>
	            </div>
	        </div>
	        <div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="table-responsive">
							<table class="table table-hover mails m-0 table table-actions-bar">
								<thead>
								<tr>
									<th width="200px">Vendors</th>
									<th>Product</th>
									<th>Action</th>
								</tr>
								</thead>

								<tbody>
								<?php if(!empty(isset($vendors))): foreach ($vendors as $vendor): ?>
								<tr class="action-row">
									<td>
										<?php echo $vendor['company'];?>
									</td>
									<td>Brown WW2 B3 Bomber Leather Jacket Men's Sheepskin Cafe Real Shearling Jacket</td>
									<td><a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=<?php echo $_GET['post_type'];?>&&product_id=<?php echo $_GET['product_id'];?>&&vendor_id=<?php echo $vendor['id'];?>&&action=detail" class="btn btn-gold waves-effect waves-light">Select Options</a></td>
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