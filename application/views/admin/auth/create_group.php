	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Create Group</li>
						</ol>
						<h4 class="page-title">Create Group</h4>
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
									<?php echo form_open("admin/auth/create_group", $attributes);?>
									<div class="form-group row">
										<?php echo lang('create_group_name_label', 'group_name', 'class="col-2 col-form-label"');?>
										<div class="col-10">
											<?php echo form_input($group_name);?>
										</div>
									</div>
									<div class="form-group row">
										<?php echo lang('create_group_desc_label', 'description', 'class="col-2 col-form-label"');?>
										<div class="col-10">
											<?php echo form_input($description);?>
										</div>
									</div>
									<?php $submitattr = array(
										'class' => 'btn btn-primary',
										'type' => 'submit'
									);?>
									<?php echo form_submit($submitattr, lang('create_group_submit_btn'));?>
									<?php echo form_close();?>
							</div>
						</div><!-- end row -->
					</div>
				</div> <!-- end col -->
			</div>
		</div> <!-- container -->
	</div> <!-- content -->