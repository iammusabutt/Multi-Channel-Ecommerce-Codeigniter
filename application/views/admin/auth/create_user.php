	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Create New User</li>
						</ol>
						<h4 class="page-title">Create New User</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
				<?php $attributes = array('class' => 'form-horizontal', 'name' => 'addgamer');?>
				<?php echo form_open("admin/auth/create_user", $attributes);?>
			<div class="row">
				<div class="col-lg-9 col-md-9 col-sm-12">
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
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="first_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">First Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($first_name);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="last_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Last Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($last_name);?>
									</div>
								</div>
								<?php
									if($identity_column!=='email') {
									echo '<p>';
									echo lang('create_user_identity_label', 'identity');
									echo '<br />';
									echo form_error('identity');
									echo form_input($identity);
									echo '</p>';
									}
								?>
								<div class="form-group row">
									<label for="phone" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Phone <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($phone);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="town_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Location <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<select name="town_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
										<option selected="true" disabled="disabled">-- Select Location --</option>
										<?php if(!empty($towns)): foreach ($towns as $town): ?>
											<option value="<?php echo $town->id;?>"><?php echo $town->town_name;?>, <?php echo $town->city_name;?></option>
											<?php endforeach; else:?>
											<option>No Records Found</option>
											<?php endif;?>
										</select>
									</div>
								</div>
							</div>
						</div><!-- end row -->
					</div> <!-- end card-box -->
				</div><!-- end col -->
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.addgamer.submit()">Add Gamer</a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end row -->
				<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->