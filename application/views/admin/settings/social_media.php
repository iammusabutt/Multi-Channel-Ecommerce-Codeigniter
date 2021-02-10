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
										<label for="medium_facebook" class="col-12 col-form-label">Facebook <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($medium_facebook);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="medium_twitter" class="col-12 col-form-label">Twitter <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($medium_twitter);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="medium_linkedin" class="col-12 col-form-label">Linkedin <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($medium_linkedin);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="medium_gplus" class="col-12 col-form-label">Google Plus <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($medium_gplus);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="medium_instagram" class="col-12 col-form-label">Instagram <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($medium_instagram);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="medium_pinterest" class="col-12 col-form-label">Pinterest <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($medium_pinterest);?>
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