<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item">Locations</li>
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
										<label for="country_name" class="col-12 col-form-label">Country <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($country_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="continent_id" class="col-12 col-form-label">Continent <span class="text-danger">*</span></label>
										<div class="col-12">
											<select name="continent_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
												<option class="option-disabled" selected="true" value="<?php echo $single_continent['id'];?>"><?php echo $single_continent['continent_name'];?></option>
											<?php if(!empty($all_continents)): foreach ($all_continents as $continent): ?>
												<option value="<?php echo $continent->id;?>"><?php echo $continent->continent_name;?></option>
												<?php endforeach; else:?>
												<option>No Records Found</option>
												<?php endif;?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="country_code" class="col-12 col-form-label">Code</label>
										<div class="col-12">
											<?php echo form_input($country_code);?>
										</div>
									</div>
									<a href="#" class="btn btn-default btn-block waves-effect waves-light" onclick="document.new_city.submit()">Publish</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->