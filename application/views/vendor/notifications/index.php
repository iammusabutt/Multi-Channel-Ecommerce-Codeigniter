<!-- Start content -->
<div class="content">
	<div class="container-fluid">
	<!-- Page-Title -->
		<div class="row">
			<div class="col-sm-12">
				<div class="page-header-2">
					<ol class="breadcrumb pull-right mb-0">
						<li class="breadcrumb-item active">Requests</li>
					</ol>
					<h4 class="page-title">Requests</h4>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div id="infoMessage"><?php echo $message;?></div>
		<form id="form" name="tournament" method="post" class="form-horizontal">
			<div class="row">
				<div class="col-12">
					<?php  if($notifications):?>
					<?php foreach($notifications as $notification):?>
						<div class="card-box m-b-10">
							<div class="table-box opport-box">
								<div class="table-detail">
									<p class="text-dark m-b-5"><b><?php echo $notification['sender_name'];?></b> <span class="text-muted"><?php echo $notification['notification_data'];?></span></p>
								</div>
								<div class="table-detail pull-right">
								<?php if($notification['notification_status'] == 'pending'){?>
									<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/notifications/connection_request?member=<?php echo $notification['sender_id'];?>&response=accept" class="btn btn-sm btn-success waves-effect waves-light"><span class="btn-label"><i class="fa fa-warning"></i></span>Accept</a>
									<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/notifications/connection_request?member=<?php echo $notification['sender_id'];?>&response=reject" class="btn btn-sm btn-danger waves-effect waves-light"><span class="btn-label"><i class="fa fa-warning"></i></span>Reject</a>
								<?php } else if($notification['notification_status'] == 'accepted'){ ?>
									<span class="label label-inverse">You are now connected</span>
								<?php } else { ?>
									<span class="label label-danger">Connection request rejected</span>
								<?php };?>
								</div>
							</div>
						</div>
					<?php endforeach;?>
					<? else:?>
					You have no other friend requests pending.
				<?php endif;?>
				</div><!-- end col -->
			</div><!-- end row -->
		<?php echo form_close();?>
	</div> <!-- container -->
</div> <!-- content -->
