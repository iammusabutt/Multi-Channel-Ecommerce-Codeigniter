<?php if(!empty($product_variations)): foreach ($product_variations as $index => $variation):?>
	<input type="hidden" name="variation_id[]" value="<?php echo $variation['object_id'];?>">
	<div class="col-lg-12">
		<div class="card-header single-row p-0" id="heading<?php echo $variation['object_id'];?>">
			
			<span class="text-dark anchor-vari clearfix" data-target="#collapse<?php echo $variation['object_id'];?>" data-toggle="collapse" aria-controls="collapse<?php echo $variation['object_id'];?>">
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
				<h6 class="m-0 var-delete-position">
					<small class="text-muted">
							<a href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/products?post_type=product_variation&&product_id=<?php echo $object_id;?>&&action=edit&&variation_id=<?php echo $variation['object_id'];?>" class="btn btn-gold waves-effect waves-light variation_edit">Edit</a> 
						  <a id="delete_variation" data-object-id="<?php echo $variation['object_parent'];?>" data-variation-id="<?php echo $variation['object_id'];?>" class="remove_row delete text-danger variation_row">Remove</a>
					</small>
				</h6>
			</span>
		</div>
	</div>
<?php endforeach; else:?>
<?php endif;?>