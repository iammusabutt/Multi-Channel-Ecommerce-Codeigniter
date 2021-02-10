<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">General Settings</li>
						</ol>
						<h4 class="page-title">General Settings</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<form id="form" name="email_config" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-9">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="primary_phone" class="col-12 col-form-label">Phone <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($primary_phone);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="secondary_phone" class="col-12 col-form-label">Secondary Phone <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($secondary_phone);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="admin_email" class="col-12 col-form-label">Email <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($admin_email);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="admin_address" class="col-12 col-form-label">Address <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($admin_address);?>
										</div>
									</div>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
					<div class="col-3">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.email_config.submit()">Save</a>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->