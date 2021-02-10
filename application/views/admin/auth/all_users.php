	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">All Users</li>
						</ol>
						<h4 class="page-title">All Users</h4>
						<a href="<?php echo base_url();?>admin/auth/create_user" class="btn btn-default waves-effect waves-light">Create New User</a>
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
										<th><?php echo lang('index_fname_th');?></th>
										<th><?php echo lang('index_lname_th');?></th>
										<th><?php echo lang('index_email_th');?></th>
										<th><?php echo lang('index_status_th');?></th>
										<th><?php echo lang('index_action_th');?></th>
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
										<td><?php echo anchor("admin/auth/edit_user/".$user->id, '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?></td>
										<td><a href="<?php echo base_url();?>admin/users/detail/<?php echo $user->id;?>" class="btn btn-block btn-success waves-effect waves-light">View Details</a></td>
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