<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/locations/cities">Locations</a></li>
							<li class="breadcrumb-item active">Cities</li>
						</ol>
						<h4 class="page-title">Cities</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<form id="form" name="new_city" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="city_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">City <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($city_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="country_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Country <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<select name="country_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
												<option class="option-disabled" selected="true" value="<?php echo $single_country['id'];?>"><?php echo $single_country['country_name'];?></option>
											<?php if(!empty($all_countries)): foreach ($all_countries as $country): ?>
												<option value="<?php echo $country->country_id;?>"><?php echo $country->country_name;?></option>
												<?php endforeach; else:?>
												<option>No Records Found</option>
												<?php endif;?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="city_code" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Code</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($city_code);?>
										</div>
									</div>
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.new_city.submit()">Update</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->