<!-- Start content -->
<div class="content">
		<div class="container-fluid">
		<!-- Page-Title -->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header-2">
						<ol class="breadcrumb pull-right mb-0">
							<li class="breadcrumb-item"><a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/attributes">Attributes</a></li>
							<li class="breadcrumb-item active">Product <?php echo $attribute['attribute_name'];?></li>
						</ol>
						<h4 class="page-title">Product <?php echo $attribute['attribute_name'];?></h4>
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
			<form id="form" name="attributevalue" method="post" class="form-horizontal">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-12">
						<div class="card-box">
							<div class="row">
								<div class="col-12">
									<p class="text-muted font-14">Attribute terms can be assigned to products and variations.</p>
									<p class="text-muted font-14">Note: Deleting a term will remove it from all products and variations to which it has been assigned. Recreating a term will not automatically assign it back to products.
                                    </p>
									<div class="form-group row">
										<label for="attribute_value_name" class="col-12 col-form-label">Name<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($attribute_value_name);?>
											<span class="help-block"><small>The name is how it appears on your site.</small></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="attribute_value_slug" class="col-12 col-form-label">Slug<span class="text-danger">*</span></label>
										<div class="col-12">
											<?php echo form_input($attribute_value_slug);?>
											<span class="help-block"><small>The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and underscores.</small></span>
										</div>
									</div>
									<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.attributevalue.submit()">Add <?php echo $attribute['attribute_name'];?></a>
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
											</tr>
										</thead>
										<tbody class="category">
											<?php if(!empty($term_taxonomy)): foreach ($term_taxonomy as $taxonomy): ?>
											<tr class="action-row">
												<td>
													<?php echo $taxonomy['name'];?>
													<small id="emailHelp" class="form-text text-muted action-links">
														<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/attribute_values?taxonomy=<?php echo $attribute['attribute_slug'];?>&action=edit&term_id=<?php echo $taxonomy['term_id'];?>" class="text-primary">edit</a> | <a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products/attribute_values?taxonomy=<?php echo $attribute['attribute_slug'];?>&action=delete&term_id=<?php echo $taxonomy['term_id'];?>" class="text-danger">delete</a>
													</small>
												</td>
												<td><?php echo $taxonomy['slug'];?></td>
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