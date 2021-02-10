<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Email Configuration</li>
						</ol>
						<h4 class="page-title">Email Configuration</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<div class="row">
				<div class="col-12">
				<?php if($email_config):?>
					<?php foreach($email_config as $config): ?>
						<div class="card-box m-b-10">
							<div class="table-box opport-box">
								<div class="table-detail title-detail">
									<div class="member-info">
										<h4 class="m-t-0"><b><?php echo $config->type;?></b></h4>
									</div>
								</div>

								<div class="table-detail" style="width:300px;">
									<p class="text-dark m-b-5"><b>Sender Name:</b> <span class="text-muted"><?php echo $config->sender_name;?></span></p>
									<p class="text-dark m-b-0"><b>Sender Email:</b> <span class="text-muted"><?php echo $config->sender_email;?></span></p>
								</div>
								<div class="table-detail lable-detail">
									<span class="label label-success">Success</span>
								</div>
								<div class="table-detail lable-detail">
									<a href="<?php echo base_url();?>admin/configurations/email/<?php echo $config->type;?>" class="btn btn-sm btn-success waves-effect waves-light"><span class="btn-label"><i class="fa fa-check"></i></span>Configure</a>
								</div>
							</div>
						</div>
					<?php endforeach;?>
				<?php endif;?>
				</div><!-- end col -->
			</div><!-- end row -->
		</div> <!-- container -->
	</div> <!-- content -->