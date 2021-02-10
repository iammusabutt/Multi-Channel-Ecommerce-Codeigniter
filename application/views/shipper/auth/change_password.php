	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Change Password</li>
						</ol>
						<h4 class="page-title">Change Password</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<div class="row">
				<div class="col-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<?php $attributes = array('class' => 'form-horizontal', 'name' => 'change_password');?>
								<?php echo form_open(uri_string(), $attributes);?>
								<div class="form-group row">
									<?php echo lang('change_password_old_password_label', 'old_password', 'class="col-12 col-form-label"');?>
									<div class="col-12">
										<?php echo form_input($old_password);?>
									</div>
								</div>
								<div class="form-group row">
									<?php echo lang('change_password_new_password_label', 'new_password', 'class="col-12 col-form-label"');?>
									<div class="col-12">
										<?php echo form_input($new_password);?>
									</div>
								</div>
								<div class="form-group row">
									<?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm', 'class="col-12 col-form-label"');?>
									<div class="col-12">
										<?php echo form_input($new_password_confirm);?>
									</div>
								</div>
								<?php echo form_input($user_id);?>
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.change_password.submit()">Change</a>
								<?php echo form_close();?>
							</div>
						</div><!-- end row -->
					</div> <!-- end card-box -->
				</div><!-- end col -->
			</div><!-- end row -->
		</div> <!-- container -->
	</div> <!-- content -->