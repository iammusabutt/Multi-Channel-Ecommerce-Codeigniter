	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Edit member</li>
						</ol>
						<h4 class="page-title">Edit member</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<?php $attributes = array('class' => 'form-horizontal', 'name' => 'edit_member');?>
			<?php echo form_open("admin/users/edit_user/". $member['id'], $attributes);?>
			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-6">
								<div class="form-group row">
									<label for="first_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">First Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($first_name);?>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group row">
									<label for="last_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Last Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($last_name);?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group row">
									<label for="company" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Company <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($company);?>
									</div>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group row">
									<label for="phone" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Phone <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($phone);?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="member_address" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Company <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($member_address);?>
									</div>
								</div>
							</div>
						</div>
					</div> <!-- end card-box -->
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="email" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Email <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($email);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="branch_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Password <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($password);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="branch_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Confirm Password <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($password_confirm);?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end col -->
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<a class="btn btn-gold btn-block waves-effect waves-light" onclick="document.edit_member.submit()">Update User</a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end row -->
				<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->