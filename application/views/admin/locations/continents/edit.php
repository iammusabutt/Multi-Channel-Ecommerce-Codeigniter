<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item">Locations</li>
							<li class="breadcrumb-item active">Continents</li>
						</ol>
						<h4 class="page-title">Continents</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<form id="form" name="new_continent" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="continent_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Name <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($continent_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="continent_code" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Code</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($continent_code);?>
										</div>
									</div>
								<a href="#" class="btn btn-default btn-block waves-effect waves-light" onclick="document.new_continent.submit()">Update</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->