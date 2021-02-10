<?php if(!empty($product_variations)): foreach ($product_variations as $index => $variation):?>
	<input type="hidden" name="variation_id[]" value="<?php echo $variation['object_id'];?>">
	<div class="col-lg-12">
		<div class="card-header single-row p-0" id="heading<?php echo $variation['object_id'];?>">
			<div class="attributes-sections m-t-15">
				<strong>#<?php echo $variation['object_id'];?> </strong>
			</div>
			<?php if(!empty($attribute_terms)): foreach ($attribute_terms as $attribute => $a_terms):?>
				<div class="attributes-sections">
					<select name="attribute_<?php echo $attribute;?>[]" id="<?php echo $variation['object_id'];?>_attribute_<?php echo $attribute;?>" class="selectpicker" data-style="btn-white">
						<option value="" class="option-disabled">Any <?php echo $attribute;?></option>
						<?php foreach ($a_terms as $a_term):?>
							<option value="<?php echo $a_term;?>"><?php echo $a_term;?></option>
						<?php endforeach;?>
					</select>
					<script>												
						$('select[id=<?php echo $variation['object_id'];?>_attribute_<?php echo $attribute;?>]').val("<?php echo $variation['attribute_'.$attribute];?>");
						$('.selectpicker').selectpicker('refresh');
					</script>
				</div>
			<?php endforeach; else:?>
			<?php endif;?>
			<span href="#collapse<?php echo $variation['object_id'];?>" class="text-dark anchor-vari" data-target="#collapse<?php echo $variation['object_id'];?>" data-toggle="collapse" aria-controls="collapse<?php echo $variation['object_id'];?>">
				<h6 class="m-0 var-delete-position">
					<small class="text-muted">
						<a id="delete_variation" data-object-id="<?php echo $variation['object_parent'];?>" data-variation-id="<?php echo $variation['object_id'];?>" class="remove_row delete text-danger">Remove</a>
					</small>
				</h6>
			</span>
		</div>
		<div class="collapse" aria-labelledby="heading<?php echo $variation['object_id'];?>" id="collapse<?php echo $variation['object_id'];?>">
			<div class="card-body">
				<div class="form-group row">
					<label for="variable_sku" class="col-lg-12 col-md-12 col-sm-12 col-form-label">SKU</label>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<input type="text" name="variable_sku[]" value="<?php if(!empty($variation['variable_sku'])){echo $variation['variable_sku'];} else {echo '';}?>" id="variable_sku" class="form-control" placeholder="Stock Keeping Unit">
					</div>
				</div>
				<div class="form-group row">
					<label for="variable_regular_price" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Regular Price <span class="text-danger">*</span></label>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<input type="text" name="variable_regular_price[]" value="<?php if(!empty($variation['variable_regular_price'])){echo $variation['variable_regular_price'];} else {echo '';}?>" id="variable_regular_price" class="form-control" placeholder="Variation Price (required)">
					</div>
				</div>
				<div class="form-group row">
					<label for="variable_stock" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Stock</label>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<input type="text" name="variable_stock[]" value="<?php if(!empty($variation['variable_stock'])){echo $variation['variable_stock'];} else {echo '';}?>" id="variable_stock" class="form-control" placeholder="Stock">
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; else:?>
<?php endif;?>