<!-- Start content -->
<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Product Vendors</li>
						</ol>
						<h4 class="page-title">Product Vendors</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<?php if(isset($class)){?>
			<div id="infoMessage">
				<p class="<?php echo $class;?>"><?php echo $message;?></p>
			</div>
			<?php } else {?>
				<div id="infoMessage"><?php echo $message;?></div>
			<?php };?>
			<form id="form" name="assignvendor" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="vendor_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Vendors <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<select name="vendor_id" class="selectpicker" data-live-search="true"  data-style="btn-white">
											<option selected="true" disabled="disabled">-- Select vendor --</option>
											<?php if(!empty($vendors)): foreach ($vendors as $vendor): ?>
												<option value="<?php echo $vendor->id;?>"><?php echo $vendor->company;?></option>
												<?php endforeach; else:?>
												<option>No Records Found</option>
												<?php endif;?>
											</select>
										</div>
									</div>
									<a class="btn btn-gold btn-block waves-effect waves-light" onclick="document.assignvendor.submit()">Assign Vendor</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
					<div class="col-lg-7 col-md-7 col-sm-12">
						<div class="card-box table-responsive">
							<div class="row">
								<div class="col-12">
									<table id="datatable" class="table table-hover mails m-0 table table-actions-bar">
										<thead>
											<tr>
												<th>Vendor</th>
												<th>Name</th>
											</tr>
										</thead>
										<tbody class="category">
											<?php if(!empty($assigned_vendors)): foreach ($assigned_vendors as $vendor): ?>
											<tr>
												<td><?php echo $vendor['company'];?></td>
												<td><?php echo $vendor['first_name'];?> <?php echo $vendor['last_name'];?></td>
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