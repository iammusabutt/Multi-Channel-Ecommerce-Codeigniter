<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/settings">Settings</a></li>
							<li class="breadcrumb-item active">Post Type</li>
						</ol>
						<h4 class="page-title">Post Type</h4>
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
										<label for="flight_airline" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Post Type Name <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($post_type_name);?>
											<span class="help-block"><small>All lowercase letters. Spaces should be replaced with underscores</small></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="flight_aircraft_class" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Label <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($label);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="flight_aircraft_class" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Singular Label <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($singular_label);?>
										</div>
									</div>
								<a href="#" class="btn btn-default btn-block waves-effect waves-light" onclick="document.post_type.submit()">Publish</a>
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
												<th>Type</th>
												<th>Label</th>
												<th>Label Singular</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty(isset($post_types))): foreach ($post_types as $types): ?>
											<tr>
												<td><?php echo $types['post_type_name'];?></td>
												<td><?php echo $types['label'];?></td>
												<td><?php echo $types['singular_label'];?></td>
												<td><?php echo anchor("admin/settings/edit_post_type/".$types['id'], '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?>
												<?php echo anchor("admin/settings/delete_post_type/". $types['post_type_name']."/".$types['id'], '<i class="md md-delete"></i>', 'class="table-action-btn" data-type="'. $types['post_type_name'].'"') ;?></td>
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