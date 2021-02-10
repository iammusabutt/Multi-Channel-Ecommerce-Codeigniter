	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Edit Group</li>
						</ol>
						<h4 class="page-title">Edit Group</h4>
						<a href="<?php echo base_url();?>admin/auth/create_group" class="btn btn-default waves-effect waves-light">Create Group</a>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
									<div id="infoMessage"><?php echo $message;?></div>
									<?php $attributes = array('class' => 'form-horizontal');?>
									<?php echo form_open(current_url(), $attributes);?>
									<div class="form-group row">
										<?php echo lang('edit_group_name_label', 'group_name', 'class="col-2 col-form-label"');?>
										<div class="col-10">
											<?php echo form_input($group_name);?>
										</div>
									</div>
									<div class="form-group row">
										<?php echo lang('edit_group_desc_label', 'group_description', 'class="col-2 col-form-label"');?>
										<div class="col-10">
											<?php echo form_input($group_description);?>
										</div>
									</div>
									<?php $submitattr = array(
										'class' => 'btn btn-primary',
										'type' => 'submit'
									);?>
									<?php echo form_submit($submitattr, lang('edit_group_submit_btn'));?>
									<?php echo form_close();?>
							</div>
						</div><!-- end row -->
					</div>
				</div> <!-- end col -->
			</div>
		</div> <!-- container -->
	</div> <!-- content -->