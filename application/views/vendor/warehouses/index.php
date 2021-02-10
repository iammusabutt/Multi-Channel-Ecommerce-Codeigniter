<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/dashboard">Dashboard</a></li>
							<li class="breadcrumb-item active">Warehouses</li>
						</ol>
						<h4 class="page-title">Warehouses</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<?php if(isset($class)){?>
			<div id="infoMessage">
				<p class="<?php echo $class;?>"><?php echo $message;?></p>
			</div>
			<?php } else {?>
				<div id="infoMessage"><?php echo $message;?></div>
			<?php };?>
			<form id="form" name="post_type" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="warehouse_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Warehouse Name <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($warehouse_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="warehouse_location" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Warehouse Location <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($warehouse_location);?>
										</div>
									</div>
								<a href="#" class="btn btn-gold btn-block waves-effect waves-light" onclick="document.post_type.submit()">Add Warehouse</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
					<div class="col-lg-7 col-md-7 col-sm-12">
						<div class="card-box table-responsive">
							<div class="row">
								<div class="col-12">
									<table id="datatable" class="table table-hover mails m-0 table table-actions-bar">
										<thead>
											<tr>
												<th>Name</th>
												<th>Type</th>
												<th>Location</th>
												<th width="145px">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty(isset($warehouses))): foreach ($warehouses as $warehouse): ?>
											<tr>
												<td><?php echo $warehouse->warehouse_name;?></td>
												<td><?php echo $warehouse->warehouse_type;?></td>
												<td><?php echo $warehouse->warehouse_location;?></td>
												<td>
													<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/<?php echo $this->uri->segment(2);?>/edit/<?php echo $warehouse->warehouse_id;?>" class="btn btn-icon waves-effect waves-light btn-gold"><i class="md md-edit"></i></a>
													
													<?php if($warehouse->warehouse_type != 'default'):?>
													<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/warehouses/change_warehouse_default/<?php echo $warehouse->warehouse_id;?>" data-style="zoom-in" data-object-id="1575" data-order-id="1575" data-tracking-number="" class="btn btn-gold waves-effect waves-light">Set as default</a>
													<?php endif;?>
												</td>
											</tr>
											<?php endforeach; else:?>
											<tr>
												<td class="center text-center" colspan="6">No Records Found</td>
											</tr>
											<?php endif;?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->