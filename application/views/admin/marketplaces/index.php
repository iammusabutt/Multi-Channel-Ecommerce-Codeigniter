<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
							<li class="breadcrumb-item active">Marketplaces</li>
						</ol>
						<h4 class="page-title">Marketplaces</h4>
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
			<form id="form" name="post_type" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="marketplace_name" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Marketplace Name <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($marketplace_name);?>
										</div>
									</div>
								<a href="#" class="btn btn-gold btn-block waves-effect waves-light" onclick="document.post_type.submit()">Publish</a>
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
												<th>Marketplace Name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty(isset($marketplaces))): foreach ($marketplaces as $marketplace): ?>
											<tr>
												<td><?php echo $marketplace->marketplace_name;?></td>
												<td><?php echo anchor("admin/marketplaces/edit_marketplace/".$marketplace->marketplace_id, '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?>
												<?php echo anchor("admin/marketplaces/delete_marketplace/". $marketplace->marketplace_id, '<i class="md md-delete"></i>', 'class="table-action-btn" data-type="'. $marketplace->marketplace_id) ;?></td>
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