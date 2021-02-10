<!-- Start content -->
<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Product Categories</li>
						</ol>
						<h4 class="page-title">Product Categories</h4>
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
			<form id="form" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<div class="form-group row">
										<label for="category_name" class="col-12 col-form-label">Name<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($category_name);?>
										</div>
									</div>
									<div class="form-group row">
										<label for="category_slug" class="col-12 col-form-label">Slug<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($category_slug);?>
										</div>
									</div>
									<a href="javsscript:void(0);" class="btn btn-default btn-block waves-effect waves-light" id="add_category">Add category</a>
								</div>
							</div><!-- end row -->
						</div> <!-- end card-box -->
					</div><!-- end col -->
					<div class="col-lg-7 col-md-7 col-sm-12">
						<div class="card-box table-responsive">
							<div class="row">
								<div class="col-12">
									<table id="datatable" class="table table-hover mails m-0 table table-actions-bar">
										<thead>
											<tr>
												<th>Name</th>
												<th>Slug</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody class="category">
											<?php if(!empty($categories)): foreach ($categories as $category): ?>
											<tr>
												<td><?php echo $category['name'];?></td>
												<td><?php echo $category['slug'];?></td>
												<td><a href="javsscript:void(0);" id="delete_category" data-categoryid="<?php echo $category['term_taxonomy_id'];?>" data-imagetype="gallery" data-spinner-color="#7e57c2" data-spinner-size="15px" data-spinner-lines="8" class="table-action-btn"><i class="md md-delete"></i></a></td>
											</tr>
											<?php endforeach; else:?>
											<tr>
												<td class="center text-center" colspan="6">No Records Found</td>
											</tr>
											<?php endif;?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div><!-- end row -->
			<?php echo form_close();?>
		</div> <!-- container -->
	</div> <!-- content -->
	<script>
	$(document).on("click", "#add_category", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
	 	var l = Ladda.create(this);
	 	l.start();
		var category_name = $("#category_name").val();
		var category_slug = $("#category_slug").val();
		
		var form_data = new FormData();
		form_data.append("category_name", category_name)
		form_data.append("category_slug", category_slug)
		var url = "<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/ajax_add_category";

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
					$("#form")[0].reset();
					$('.category').html(data.content);
					$.Notification.autoHideNotify('custom', 'top right', 'Success', data.message);
				}
			}
		});
	});
	$(document).on("click", "#delete_category", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
	 	var l = Ladda.create(this);
	 	l.start();
		var term_id = $(this).data('categoryid');
		
		var form_data = new FormData();
		form_data.append("term_id", term_id)
		var url = "<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/ajax_delete_category";

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
					$('.category').html(data.content);
					$.Notification.autoHideNotify('error', 'top right', 'Success', data.message);
				}
			}
		});
	});
	</script>