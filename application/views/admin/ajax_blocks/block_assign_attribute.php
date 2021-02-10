<?php if(!empty($new_attributes)): foreach ($new_attributes as $index => $block):?>
	<div class="col-lg-12">
		<div class="card-header single-row p-0" id="headingOne">
			<span href="#<?php echo $block['attribute_slug'];?>" class="text-dark anchor-arch" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
				<h6 class="m-0">
					<small class="text-muted">
						<a id="delete_attribute" data-object-id="<?php echo $object_id;?>" data-attributevalue="<?php echo $block['attribute_slug'];?>" class="remove_row delete text-danger">Remove</a>
					</small>
					<?php echo $block['attribute_slug'];?></h6>
			</span>
		</div>
		<div class="collapse" id="<?php echo $block['attribute_slug'];?>">
			<div class="card-body">
				<select id="<?php echo $block['attribute_slug'];?>" name="attribute_values[<?php echo $index;?>][<?php echo $block['attribute_slug'];?>][]" class="form-control select2" multiple="multiple" data-placeholder="Select terms">
				<?php if(!empty($block['attribute_values'])): foreach ($block['attribute_values'] as $index => $value):?>
					<option value="<?php echo strtolower($value['name']);?>" <?php if(!empty($value['object_id'])) { echo 'selected="selected"';} else { echo '';};?>><?php echo $value['name'];?></option>
				<?php endforeach;?>
				<?php endif;?>
				</select>
				<a class="label btn-gold btn-custom pointer select_all_attributes m-t-10">Select all</a>
				<a class="label btn-gold btn-custom pointer select_no_attributes m-t-10">Select none</a>
			</div>
		</div>
	</div>
<?php endforeach; else:?>
<?php endif;?>