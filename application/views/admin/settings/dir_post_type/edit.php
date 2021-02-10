<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/airlines">Settings</a></li>
							<li class="breadcrumb-item active">Edit Post Type</li>
						</ol>
						<h4 class="page-title">Edit Post Type</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<form id="form" name="post_type" method="post" class="form-horizontal">
				<div class="airline_image_id">
					<input type="hidden" name="featured_image_id" value="" />
				</div>
				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="post_type_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Post Type Name <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($post_type_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="label" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Label <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($label);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="singular_label" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Singular Label <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($singular_label);?>
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
									<a href="#" class="btn btn-default btn-block waves-effect waves-light" onclick="document.post_type.submit()">Update Post Type</a>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->