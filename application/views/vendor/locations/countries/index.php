<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item">Locations</li>
							<li class="breadcrumb-item active">Countries</li>
						</ol>
						<h4 class="page-title">Countries</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<?php if(isset($class)){?>
			<div id="infoMessage">
				<p class="<?php echo $class;?>"><?php echo $message;?></p>
			</div>
			<?php }else{?>
				<div id="infoMessage"><?php echo $message;?></div>
			<?php };?>
			<form id="form" name="new_country" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="continent_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Continent <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<select name="continent_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
											<option selected="true" disabled="disabled">-- Select continent --</option>
											<?php if(!empty($all_continents)): foreach ($all_continents as $continent): ?>
												<option value="<?php echo $continent->id;?>"><?php echo $continent->continent_name;?></option>
												<?php endforeach; else:?>
												<option>No Records Found</option>
												<?php endif;?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="country_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Country <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($country_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="country_code" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Code</label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($country_code);?>
										</div>
									</div>
								<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.new_country.submit()">Publish</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
					<div class="col-lg-7 col-md-7 col-sm-12 ">
						<div class="card-box table-responsive">
							<div class="row">
								<div class="col-12">
									<table id="datatable" class="table table-hover mails m-0 table table-actions-bar">
										<thead>
											<tr>
												<th>Country</th>
												<th>Continent</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty($all_countries)): foreach ($all_countries as $country): ?>
											<tr>
												<td><?php echo $country->country_name;?></td>
												<td><?php echo $country->continent_name;?></td>
												<td><?php echo anchor("admin/locations/edit_country/".$country->country_id, '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?><?php echo anchor("admin/locations/delete_country/".$country->country_id, '<i class="md md-delete"></i>', 'class="table-action-btn"') ;?></td>
											</tr>
											<?php endforeach; else:?>
											<tr>
												<td class="center text-center" colspan="6">No Records Found</td>
											</tr>
											<?php endif;?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->