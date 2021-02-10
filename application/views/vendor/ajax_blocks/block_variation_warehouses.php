<?php if(!empty($product_warehouse)): foreach ($product_warehouse as $warehouse):?>
<div class="card-box p-20 warehouse-locations action-row">
    <div class="row">
        <div class="col-4">
            <h6 class="m-b-0 m-t-5"><?php echo $warehouse->warehouse_name;?> (<?php echo $warehouse->warehouse_location;?>)</h6>
            <small id="emailHelp" class="form-text text-muted action-links">
                <a class="text-primary pointer">stock movement</a> | <a id="delete_warehouse"  data-warehouse-id="<?php if(!empty($warehouse)){echo $warehouse->warehouse_id;};?>" data-warehouse-type="additional" class="text-danger pointer">delete</a>
            </small>
        </div>
        <div class="col-8">
            <input type="text" name="variable_stock[]" value="<?php echo $warehouse->stock_quantity;?>" id="variable_stock" class="form-control" placeholder="Quantity">
            <span class="help-block"><small>Available stock in hand.</small></span>
        </div>
    </div>
</div>
<?php endforeach; else:?>
<?php endif;?>