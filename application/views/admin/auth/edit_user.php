	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Edit User</li>
						</ol>
						<h4 class="page-title">Edit User</h4>
						<a href="<?php echo base_url();?>admin/auth/create_user" class="btn btn-default waves-effect waves-light">Create New User</a>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<?php $attributes = array('class' => 'form-horizontal', 'name' => 'editgamer');?>
			<?php echo form_open(uri_string(), $attributes);?>
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
									<label for="branch_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Password (if changing password)</label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($password);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="branch_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Confirm Password (if changing password)</label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($password_confirm);?>
									</div>
								</div>
								<?php echo form_hidden('id', $user->id);?>
								<?php echo form_hidden($csrf); ?>
								<?php $submitattr = array(
									'class' => 'btn btn-primary',
									'type' => 'submit'
								);?>
							</div>
						</div><!-- end row -->
					</div>
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<div class="form-group row">
									<label for="branch_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">First Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($first_name);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="branch_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Last Name <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($last_name);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="branch_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Phone <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<?php echo form_input($phone);?>
									</div>
								</div>
								<div class="form-group row">
									<label for="town_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Location <span class="text-danger">*</span></label>
									<div class="col-lg-12 col-md-12 col-sm-12">
										<select name="town_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
											<option class="option-disabled" selected="true" value="<?php echo $location->id;?>"><?php echo $location->town_name;?>, <?php echo $location->city_name;?></option>
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
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<h4 class="header-title"><?php echo lang('edit_user_groups_heading');?></h4>
								<div class="mt-3 mb-3">
									<?php if ($this->ion_auth->is_admin()): ?>
									<?php foreach ($groups as $group):?>
										<div class="custom-control custom-checkbox">
											<?php
												$gID=$group['id'];
												$checked = null;
												$item = null;
												foreach($currentGroups as $grp) {
													if ($gID == $grp->id) {
														$checked= ' checked="checked"';
													break;
													}
												}
											?>
											<input type="checkbox" class="custom-control-input" name="groups[]" value="<?php echo $group['id'];?>" id="customCheck<?php echo $group['id'];?>" <?php echo $checked;?>>
											<label class="custom-control-label" for="customCheck<?php echo $group['id'];?>">
											<?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?></label>
										</div>
									<?php endforeach?>
									<?php endif ?>
								</div>
							</div>
						</div><!-- end row -->
					</div>
				</div> <!-- end col -->
				<div class="col-lg-3 col-md-3 col-sm-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.editgamer.submit()">Update Gamer</a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->