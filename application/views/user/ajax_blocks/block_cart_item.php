<?php if(!empty(isset($cart_items))):?>
<div class="contact-list nicescroll ">
    <ul class="list-group contacts-list">
    <?php foreach ($cart_items as $product): ?>
        <li class="list-group-item">
            <span class="name"><?php echo $product['object_title'];?></span>
            <a href="javsscript:void(0);" id="delete_cart_item" data-objectid="<?php echo $product['object_id'];?>" data-vendorid="<?php echo $product['vendor_id'];?>" data-spinner-color="#7e57c2" data-spinner-size="15px" data-spinner-lines="8" data-style="zoom-out" class="table-action-btn">
                <i class=" ti-trash"></i>
            </a>
            <span class="clearfix"></span>
        </li>
        <?php endforeach;?>
    </ul>
    <div class="order-list-btn p-20">
        <a href="<?php echo base_url();?><?php echo $this->uri->segment(1) ?>/orders/place_order" class="btn btn-gold btn-block waves-effect waves-light">Checkout</a>
    </div>
</div>
<?php else:?>
<?php endif;?>