<!-- Start content -->
<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item active">Attributes</li>
						</ol>
						<h4 class="page-title">Attributes</h4>
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
			<form id="form" method="post" name="addattribute" class="form-horizontal">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<p class="text-muted font-14">Attributes let you define extra product data, such as size or color. You can use these attributes in the product page using the "Product data" Section. 
                                    </p>
									<div class="form-group row">
										<label for="attribute_name" class="col-12 col-form-label">Name<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($attribute_name);?>
											<span class="help-block"><small>Name for the attribute (shown on the front).</small></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="attribute_slug" class="col-12 col-form-label">Slug<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($attribute_slug);?>
											<span class="help-block"><small>Unique slug/reference for the attribute.</small></span>
										</div>
									</div>
									<a onclick="document.addattribute.submit()" class="btn btn-default btn-block waves-effect waves-light">Add attribute</a>
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
												<th>values</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody class="category">
										<?php if(!empty($new_attributes)): foreach ($new_attributes as $index => $block):?>
											<tr class="action-row">
												<td>
													<?php echo $block['attribute_name'];?>
													<small id="emailHelp" class="form-text text-muted action-links">
														<a href="<?php echo base_url();?>admin/products/edit_attribute/<?php echo $block['attribute_id'];?>" class="text-primary">edit</a> | <a href="<?php echo base_url();?>admin/products/delete_attribute/<?php echo $block['attribute_id'];?>" class="text-danger">delete</a>
													</small>
												</td>
												<td><?php echo $block['attribute_slug'];?></td>
												<td>
													<?php if(!empty($block['attribute_values'])): foreach ($block['attribute_values'] as $index => $value):?>
														<code><?php echo $value;?></code>
													<?php endforeach;?>
													<?php endif;?>
												</td>
												<td><a href="<?php echo base_url();?>admin/products/attribute_values?taxonomy=<?php echo $block['attribute_slug'];?>" class="btn btn-block btn-gold waves-effect waves-light btn-sm">configure <?php echo $block['attribute_slug'];?></a></td>
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
	$(document).on("click", "#delete_category", function(e) {
		e.preventDefault(); // avoid to execute the actual submit of the form.
		e.stopPropagation();
	 	var l = Ladda.create(this);
	 	l.start();
		var term_id = $(this).data('categoryid');
		
		var form_data = new FormData();
		form_data.append("term_id", term_id)
		var url = "<?php echo base_url();?>admin/products/ajax_delete_category";

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