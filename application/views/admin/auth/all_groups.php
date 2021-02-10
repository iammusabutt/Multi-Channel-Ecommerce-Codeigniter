<div id="infoMessage"><?php echo $message;?></div>
	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
			<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">User Groups</li>
						</ol>
						<h4 class="page-title">User Groups </h4>
						<a href="<?php echo base_url();?>admin/auth/create_group" class="btn btn-default waves-effect waves-light">Create Group</a>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
							<div class="row">
							<div class="col-12">
							<?php echo anchor('admin/auth/create_group', lang('index_create_group_link'), 'class="btn btn-default btn-md waves-effect waves-light m-b-30"')?>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-hover mails m-0 table table-actions-bar">
								<thead>
									<tr>
										<th><?php echo lang('index_groups_th');?></th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($groups as $group):?>
									<tr>
										<td>
											<?php echo anchor("admin/auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
										</td>
										<td><?php echo anchor("admin/auth/edit_group/".$group->id, '<i class="md md-edit"></i>', 'class="table-action-btn"') ;?></td>
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