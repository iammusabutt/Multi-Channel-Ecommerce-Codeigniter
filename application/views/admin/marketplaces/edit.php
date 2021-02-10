<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/marketplaces">Marketplaces</a></li>
							<li class="breadcrumb-item active">Edit Marketplace</li>
						</ol>
						<h4 class="page-title">Marketplaces</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<form id="form" name="marketplaces" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="currency_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Marketplace Name <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($marketplace_name);?>
										</div>
									</div>
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.marketplaces.submit()">Update</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->