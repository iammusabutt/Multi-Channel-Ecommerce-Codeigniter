<!-- Start content -->
	<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Developer Tool</li>
						</ol>
						<h4 class="page-title">Developer Tool</h4>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<?php if(isset($class)){?>
			<div id="infoMessage">
				<p class="<?php echo $class;?>"><?php echo $message;?></p>
			</div>
			<?php }else{?>
				<div id="infoMessage"><?php echo $message;?></div>
			<?php };?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Warning:</strong> This section is for developers only. Use available tools in this section only if you know what you are doing. Any mistake here can result in complete application faliure or data loss.
			</div>
			<form id="form" name="add_missing_record" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-12">
						<div class="card-box">
						<div class="row">
							<div class="col-12">
								<h6 class="m-b-0 m-t-0 page-title">Metadata</h6>
							</div>
						</div>
						<hr>
							<div class="row">
								<div class="col-3">
									<div class="form-group row">
										<label for="meta_table" class="col-12 col-form-label">Meta Table <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($meta_table);?>
										</div>
									</div>
								</div>
								<div class="col-3">
									<div class="form-group row">
										<label for="data_type" class="col-12 col-form-label">Data Type <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($data_type);?>
										</div>
									</div>
								</div>
								<div class="col-3">
									<div class="form-group row">
										<label for="meta_key" class="col-12 col-form-label">Meta Key <span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($meta_key);?>
										</div>
									</div>
								</div>
								<div class="col-3">
									<div class="form-group row">
										<label for="meta_value" class="col-12 col-form-label">Meta Value (Default)</label>
										<div class="col-12">
											<?php echo form_input($meta_value);?>
										</div>
									</div>
								</div>
							</div><!-- end row -->
							<div class="row">
								<div class="col-12">
									<a id="add_missing_record" class="btn btn-default waves-effect waves-light">Add metadata</a> 
									<a id="delete_missing_record" class="btn btn-danger waves-effect waves-light">Delete metadata</a>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-12">
									<h6 class="m-b-20 m-t-5">Available meta tables and their data types</h6>
								</div>
								<div class="col-12">
								<table class="table table-hover mails m-0 table table-actions-bar">
									<thead>
										<tr>
											<th width="50%">Item</th>
											<th width="50%">Values</th>
										</tr>
									</thead>
									<tbody>
										<tr class="action-row">
											<td>Products</td>
											<td>Meta Table: <code>objectmeta</code><br>
												Data type: <code>product</code>, <code>product_variation</code>
											</td>
										</tr>
									</tbody>
							</table>
							</div>
						</div> <!-- end card-box -->
					</div><!-- end col -->
				</div><!-- end row -->
		</div> <!-- container -->
	</div> <!-- content -->
<script>
	$(document).on("click", "#add_missing_record", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
		var l = Ladda.create(this);
		l.start();
    	var data_type = $("#data_type").val();
    	var meta_table = $("#meta_table").val();
    	var meta_key = $("#meta_key").val();
    	var meta_value = $("#meta_value").val();
		var form_data = new FormData(); 
		form_data.append("data_type", data_type)
		form_data.append("meta_table", meta_table)
		form_data.append("meta_key", meta_key)
		form_data.append("meta_value", meta_value)
		var url = base_url + account_type + "/settings/ajax_add_product_metadata";


		$.ajax({
			url: url,
			type: "POST",
			async:"false",
			dataType: "html",
			cache:false,
			contentType: false,
			processData: false,
			data: form_data, // serializes the form's elements.
			success: function(data) {
				data = JSON.parse(data);
    			console.log(data);
				if(data.response == "yes") {
					l.stop();
					$.Notification.autoHideNotify('success', 'top right', 'Success', data.message);
				} else {
					l.stop();
					$.Notification.autoHideNotify('error', 'top right', 'Something went wrong', data.message);
				}
			}
		});
	});
	$(document).on("click", "#delete_missing_record", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
		var l = Ladda.create(this);
		l.start();
    	var data_type = $("#data_type").val();
    	var meta_table = $("#meta_table").val();
    	var meta_key = $("#meta_key").val();
    	var meta_value = $("#meta_value").val();
		var form_data = new FormData(); 
		form_data.append("data_type", data_type)
		form_data.append("meta_table", meta_table)
		form_data.append("meta_key", meta_key)
		form_data.append("meta_value", meta_value)
		var url = base_url + account_type + "/settings/ajax_delete_product_metadata";


		$.ajax({
			url: url,
			type: "POST",
			async:"false",
			dataType: "html",
			cache:false,
			contentType: false,
			processData: false,
			data: form_data, // serializes the form's elements.
			success: function(data) {
				data = JSON.parse(data);
    			console.log(data);
				if(data.response == "yes") {
					l.stop();
					$.Notification.autoHideNotify('success', 'top right', 'Success', data.message);
				} else {
					l.stop();
					$.Notification.autoHideNotify('error', 'top right', 'Something went wrong', data.message);
				}
			}
		});
	});
</script>