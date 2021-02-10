	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">All Members</li>
						</ol>
						<h4 class="page-title">All Members</h4>
						<a href="<?php echo base_url();?>admin/members/create_member" class="btn btn-gold waves-effect waves-light">Create New Member</a>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="table-responsive">
							<table id="datatable" class="table table-hover mails m-0 table table-actions-bar">
								<thead>
									<tr>
										<th>Email</th>
										<th>First Name</th>
										<th>Last name</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($users as $user):?>
									<tr>
										<td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo ($user->active) ? anchor("admin/auth/deactivate/".$user->id, lang('index_active_link')) : anchor("admin/auth/activate/". $user->id, lang('index_inactive_link'));?></td>
										<td><?php echo anchor("admin/members/edit_member/".$user->id, '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?></td>
									</tr>
								<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
				</div> <!-- end col -->
			</div>
		</div> <!-- container -->
	</div> <!-- content -->