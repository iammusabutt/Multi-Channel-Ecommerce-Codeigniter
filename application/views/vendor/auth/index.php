	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<h4 class="page-title"><?php echo lang('index_heading');?></h4>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
						<li class="breadcrumb-item active">Users</li>
					</ol>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="row">
							<div class="col-12">
							<?php echo anchor('admin/auth/create_user', lang('index_create_user_link'), 'class="btn btn-default btn-md waves-effect waves-light m-b-30"')?>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-hover mails m-0 table table-actions-bar">
								<thead>
									<tr>
										<th><?php echo lang('index_fname_th');?></th>
										<th><?php echo lang('index_lname_th');?></th>
										<th><?php echo lang('index_email_th');?></th>
										<th><?php echo lang('index_groups_th');?></th>
										<th><?php echo lang('index_status_th');?></th>
										<th><?php echo lang('index_action_th');?></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($users as $user):?>
									<tr>
										<td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
										<td>
											<?php foreach ($user->groups as $group):?>
											<?php echo anchor("admin/auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
											<?php endforeach?>
										</td>
										<td><?php echo ($user->active) ? anchor("admin/auth/deactivate/".$user->id, lang('index_active_link')) : anchor("admin/auth/activate/". $user->id, lang('index_inactive_link'));?></td>
										<td><?php echo anchor("admin/auth/edit_user/".$user->id, '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?></td>
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