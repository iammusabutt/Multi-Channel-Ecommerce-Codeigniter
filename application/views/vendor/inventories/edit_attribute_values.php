<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>vendors/products/attributes">Attribute</a></li>
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>vendors/products/attribute_values?taxonomy=<?php echo $attribute['attribute_slug'];?>">Values</a></li>
							<li class="breadcrumb-item active">Edit Product <?php echo $attribute['attribute_name'];?></li>
						</ol>
						<h4 class="page-title">Edit Product <?php echo $attribute['attribute_name'];?></h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<form id="form" name="editattributevalue" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-5">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="attribute_name" class="col-12 col-form-label">Name<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($attribute_value_name);?>
											<span class="help-block"><small>Name for the attribute (shown on the front).</small></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="attribute_slug" class="col-12 col-form-label">Slug<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($attribute_value_slug);?>
											<span class="help-block"><small>Unique slug/reference for the attribute.</small></span>
										</div>
									</div>
									<a onclick="document.editattributevalue.submit()" class="btn btn-default btn-block waves-effect waves-light">Update <?php echo $attribute['attribute_name'];?></a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->