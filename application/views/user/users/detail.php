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
							<table class="table table-hover mails m-0 table table-actions-bar">
								<thead>
									<tr>
										<th>Username</th>
										<th>Branch</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($users_accounts as $account):?>
									<tr>
										<td><?php echo htmlspecialchars($account->username,ENT_QUOTES,'UTF-8');?></td>
										<td><?php echo htmlspecialchars($account->name,ENT_QUOTES,'UTF-8');?></td>
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