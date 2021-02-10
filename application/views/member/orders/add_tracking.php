	<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/orders/view_orders">Orders</a></li>
							<li class="breadcrumb-item active">Add Tracking</li>
						</ol>
						<h4 class="page-title">Add Tracking</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div id="infoMessage"><?php echo $message;?></div>
			<form id="form" method="post" name="addtracking" class="form-horizontal">
				<div class="row">
					<div class="col-9">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="shipper_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Shipper <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<select name="shipper_id" class="selectpicker dropup" data-live-search="true"  data-style="btn-white">
											<option class="option-disabled">-- Select Shipper --</option>
											<?php if(!empty($shippers)): foreach ($shippers as $shipper): ?>
												<option value="<?php echo $shipper->id;?>"><?php echo $shipper->company;?></option>
												<?php endforeach; else:?>
												<option>No Records Found</option>
												<?php endif;?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="tracking_number" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Tracking Number <span class="text-danger">*</span></label>
										<div class="col-lg-12 col-md-12 col-sm-12">
											<?php echo form_input($tracking_number);?>
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
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.addtracking.submit()">Add Tracking</a>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->
<script>
	$(document).on("click", "#uploadify", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
	 	var l = Ladda.create(this);
	 	l.start();
		var airline_id = "<?php echo $this->uri->segment(4);?>";
		var uploadfile = $('.dropify')[0].files[0];
		
		type = uploadfile.type;
		var form_data = new FormData(); 
		form_data.append("file", uploadfile)
		form_data.append("airline_id", airline_id)
		var url = "<?php echo base_url();?>admin/airlines/upload_file";

		$.ajax({
			url: url,
			type: "POST",
			async:"false",
			dataType: "html",
			cache:false,
			contentType: false,
			processData: false,
			data: form_data, // serializes the form's elements.
			success: function(data)
			{
				data = JSON.parse(data);
				if(data.response == "yes")
				{
					l.stop();
					$.Notification.autoHideNotify(data.response_type, 'top right', 'Success', data.message);
				}
			}
		});
	});
</script>