<?php if(!empty($product_warehouse)): foreach ($product_warehouse as $warehouse):?>
<div class="card-box warehouse-locations">
    <div class="row">
        <div class="col-4">
            <h6 class="m-b-0 m-t-5"><?php echo $warehouse->warehouse_name;?> (<?php echo $warehouse->warehouse_location;?>)</h6>
            <span class="help-block"><small>Name of Warehouse.</small></span>
        </div>
        <div class="col-5">
            <input type="text" name="variable_stock[]" value="<?php echo $warehouse->stock_quantity;?>" id="variable_stock" class="form-control" placeholder="Quantity">
            <span class="help-block"><small>Available stock in hand.</small></span>
        </div>
        <div class="col-3">
            <a id="delete_warehouse" class="btn btn-icon waves-effect waves-light btn-danger" data-warehouse-id="<?php echo $warehouse->warehouse_id;?>" data-warehouse-type="additional" data-style="zoom-out"><i class="md md-delete"></i> remove</a>
            <a class="btn btn-icon waves-effect waves-light btn-primary"><i class="md md-history"></i></a>
        </div>
    </div>
</div>
<?php endforeach; else:?>
<?php endif;?>