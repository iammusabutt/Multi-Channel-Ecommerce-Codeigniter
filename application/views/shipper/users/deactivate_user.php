	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<h4 class="page-title"><?php echo lang('deactivate_heading');?></h4>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
						<li class="breadcrumb-item active">Users</li>
					</ol>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
								<div class="p-20">
									<?php $attributes = array('class' => 'form-horizontal');?>
									<?php echo form_open("admin/auth/deactivate/".$user->id, $attributes);?>
									<div class="mt-3 mb-3">
									<h4 class="m-t-0 header-title">Do we really want to deactivate?</h4>
										<div class="custom-control custom-radio">
											<input type="radio" id="customRadio1" name="confirm" class="custom-control-input" value="yes" checked="checked" />
											<label class="custom-control-label" for="customRadio1">Yes</label>
										</div>
										<div class="custom-control custom-radio">
											<input type="radio" id="customRadio2" name="confirm" class="custom-control-input" value="no" />
											<label class="custom-control-label" for="customRadio2">No</label>
										</div>
									</div>
									<?php echo form_hidden('id', $user->id);?>
									<?php echo form_hidden($csrf); ?>
									<?php $submitattr = array(
										'class' => 'btn btn-primary',
										'type' => 'submit'
									);?>
									<?php echo form_submit($submitattr, lang('deactivate_submit_btn'));?>
									<?php echo form_close();?>
								</div>
							</div>
						</div><!-- end row -->
					</div>
				</div> <!-- end col -->
			</div>
		</div> <!-- container -->
	</div> <!-- content -->