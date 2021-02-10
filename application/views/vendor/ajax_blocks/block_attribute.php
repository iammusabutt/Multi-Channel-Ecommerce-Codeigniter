<?php if(!empty($attributes)): foreach ($attributes as $attribute): ?>
<tr>
	<td><?php echo $attribute->attribute_name;?></td>
	<td><?php echo $attribute->attribute_slug;?></td>
	<td><a href="javsscript:void(0);" id="delete_category" data-categoryid="<?php echo $attribute->attribute_id;?>" data-imagetype="gallery" data-spinner-color="#7e57c2" data-spinner-size="15px" data-spinner-lines="8" class="table-action-btn"><i class="md md-delete"></i></a></td>
</tr>
<?php endforeach; else:?>
<tr>
	<td class="center text-center" colspan="6">No Records Found</td>
</tr>
<?php endif;?>