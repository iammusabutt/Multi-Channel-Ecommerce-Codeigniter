<?php if(!empty($categories)): foreach ($categories as $category): ?>
<tr>
	<td><?php echo $category->name;?></td>
	<td><?php echo $category->slug;?></td>
	<td><a href="javsscript:void(0);" id="delete_category" data-categoryid="<?php echo $category->term_taxonomy_id;?>" data-spinner-color="#7e57c2" data-spinner-size="15px" data-spinner-lines="8" class="table-action-btn"><i class="md md-delete"></i></a></td>
</tr>
<?php endforeach; else:?>
<tr>
	<td class="center text-center" colspan="6">No Records Found</td>
</tr>
<?php endif;?>