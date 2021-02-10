<div class="col-12">
	<div class="form-group row">
		<label for="vendor_id" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Vendors <span class="text-danger">*</span></label>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<select name="vendor_id" class="selectpicker" data-style="btn-white">
			<?php if(!empty($assigned_vendors)): foreach ($assigned_vendors as $vendor): ?>
				<option value="<?php echo $vendor['id'];?>"><?php echo $vendor['company'];?> (<?php echo $vendor['first_name'];?> <?php echo $vendor['last_name'];?>)</option>
				<?php endforeach; else:?>
				<option>No Records Found</option>
				<?php endif;?>
			</select>
			<small id="emailHelp" class="form-text text-muted">Select product from product search to fetch assigned vendors.</small>
		</div>
	</div>
</div>