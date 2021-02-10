<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
							<li class="breadcrumb-item active">Couriers</li>
						</ol>
						<h4 class="page-title">Couriers</h4>
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
										<label for="currency_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Courier Name <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($courier_name);?>
										</div>
									</div>
								<a href="#" class="btn btn-gold btn-block waves-effect waves-light" onclick="document.post_type.submit()">Add Courier</a>
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
												<th>Courier Name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty(isset($couriers))): foreach ($couriers as $courier): ?>
											<tr>
												<td><?php echo $courier->courier_name;?></td>
												<td><?php echo anchor("admin/couriers/edit/".$courier->courier_id, '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?>
												<?php echo anchor("admin/couriers/delete/". $courier->courier_id, '<i class="md md-delete"></i>', 'class="table-action-btn" data-type="'. $courier->courier_id) ;?></td>
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