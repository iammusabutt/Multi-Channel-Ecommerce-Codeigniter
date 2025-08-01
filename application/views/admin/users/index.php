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
						<a href="<?php echo base_url();?>admin/users/create_user" class="btn btn-gold waves-effect waves-light">Create New User</a>
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
									</tr>
								</thead>
								<tbody>
								<?php foreach ($users as $user):?>
									<tr class="action-row">
										<td>
											<?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?>
											<small id="emailHelp" class="form-text text-muted action-links">
												<a href="<?php echo base_url();?>admin/users/edit_user/<?php echo $user->id;?>" class="text-primary">edit</a> | <a href="<?php echo base_url();?>admin/users/delete_user/<?php echo $user->id;?>" class="text-danger">delete</a>
											</small>
										</td>
										<td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo ($user->active) ? anchor("admin/auth/deactivate/".$user->id, lang('index_active_link')) : anchor("admin/auth/activate/". $user->id, lang('index_inactive_link'));?></td>
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