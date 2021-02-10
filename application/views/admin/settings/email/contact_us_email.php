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
			<form id="form" name="email_config" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-9">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="sender_name" class="col-12 col-form-label">Sender Name <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($sender_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="sender_email" class="col-12 col-form-label">Sender Email <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($sender_email);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="sender_cc" class="col-12 col-form-label">CC</label>
										<div class="col-12">
											<?php echo form_input($sender_cc);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="sender_bcc" class="col-12 col-form-label">BCC</label>
										<div class="col-12">
											<?php echo form_input($sender_bcc);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="subject" class="col-12 col-form-label">Sender Email Subject <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($sender_subject);?>
										</div>
									</div>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
					<div class="col-3">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.email_config.submit()">Save</a>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->