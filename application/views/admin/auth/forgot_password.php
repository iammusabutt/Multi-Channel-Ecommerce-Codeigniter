<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.ico">

        <title>Ubold - Responsive Admin Dashboard Template</title>

        <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css" />

        <script src="assets/js/modernizr.min.js"></script>
        
    </head>
    <body>

		<div class="account-pages"></div>
		<div class="clearfix"></div>
		<div class="wrapper-page">
			<div class=" card-box">
				<div class="panel-heading">
					<h4 class="text-center"><?php echo lang('forgot_password_heading');?></h4>
				</div>

				<div class="p-20">
				<div id="infoMessage"><?php echo $message;?></div>

				<?php $attributes = array('class' => 'form-horizontal m-t-20');?>
				<?php echo form_open('auth/forgot_password', $attributes);?>
					<form method="post" action="#" role="form" class="text-center">
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
								×
							</button>
							<?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?>
						</div>
						<div class="form-group m-b-0">
							<div class="input-group">
								<?php echo form_input($identity);?>
								<span class="input-group-append">
								<?php $submitattr = array(
								'class' => 'btn btn-pink w-sm waves-effect waves-light',
								'type' => 'submit'
								);?>
									<?php echo form_button($submitattr, lang('forgot_password_submit_btn'));?>
								</span>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/popper.min.js"></script><!-- Popper for Bootstrap -->
        <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/detect.js"></script>
        <script src="<?php echo base_url();?>assets/js/fastclick.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo base_url();?>assets/js/waves.js"></script>
        <script src="<?php echo base_url();?>assets/js/wow.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.app.js"></script>
	
	</body>
</html>