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
			<?php if(isset($class)){?>
			<div id="infoMessage">
				<p class="<?php echo $class;?>"><?php echo $message;?></p>
			</div>
			<?php }else{?>
				<div id="infoMessage"><?php echo $message;?></div>
			<?php };?>
			<form id="form" name="email_config" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-9">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="currency_unit" class="col-12 col-form-label">Site Title <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($site_title);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="currency_unit" class="col-12 col-form-label">Site Description <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_textarea($site_description);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="currency_unit" class="col-12 col-form-label">Currency Unit <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($currency_unit);?>
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