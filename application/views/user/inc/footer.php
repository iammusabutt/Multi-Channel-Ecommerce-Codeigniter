                <footer class="footer text-right">
                    &copy; 2019. All rights reserved. Made with love by <a href="https://www.drcodex.com">DrCodeX Technologies</a>
                </footer>
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
            <!-- Right Sidebar -->
            <div class="side-bar right-bar nicescroll">
                <h4 class="text-center">Order List</h4>
                <div class="cart_item">
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
                </div>
            </div>
            <!-- /Right-bar -->
        </div>
        <!-- END wrapper -->
        <script type="text/javascript">
            $(document).on("click", "#delete_cart_item", function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                e.stopPropagation();
                var l = Ladda.create(this);
                l.start();
                var object_id = $(this).data('objectid');
                var vendor_id = $(this).data('vendorid');

                var form_data = new FormData();
                form_data.append("object_id", object_id)
                form_data.append("vendor_id", vendor_id)
                var url = "<?php echo base_url();?>user/products/ajax_delete_cart_item";

                $.ajax({
                    url: url,
                    type: "POST",
                    async: "false",
                    dataType: "html",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data, // serializes the form's elements.
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data.response == "yes") {
                            l.stop();
                            $('.cart_item').html(data.content);
                            $.Notification.autoHideNotify('error', 'top left', 'Deleted from Cart', data.message);
                        }
                    }
                });
            });
        </script>
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo base_url();?>assets/js/popper.min.js"></script><!-- Popper for Bootstrap -->
        <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/detect.js"></script>
        <script src="<?php echo base_url();?>assets/js/fastclick.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo base_url();?>assets/js/waves.js"></script>
        <script src="<?php echo base_url();?>assets/js/wow.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.scrollTo.min.js"></script>
		<!-- ------------simpleLightbox---- -->
        <script src="<?php echo base_url();?>assets/plugins/simpleLightbox/simpleLightbox.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/simpleLightbox/simpleLightbox.js"></script>
        <!-- ------------ end simpleLightbox---- -->
        <script type="text/javascript">
            // wait for images to load
            $(window).on('load', function () {
                $('.sp-wrap').smoothproducts();
            });
        </script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/plugins/dropify/js/dropify.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.dropify').dropify({
                    messages: {
                        'default': 'Drag and drop a file here or click',
                        'replace': 'Drag and drop or click to replace',
                        'remove':  'Remove',
                        'error':   'Ooops, something wrong happended.'
                    },
                    tpl: {
                        wrap:            '<div class="dropify-wrapper"></div>',
                        loader:          '<div class="dropify-loader"></div>',
                        message:         '<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',
                        preview:         '<div class="dropify-preview"><span class="dropify-render"></span></div>',
                        filename:        '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
                        clearButton:     '<button type="button" class="dropify-clear">{{ remove }}</button>',
                        errorLine:       '<p class="dropify-error">{{ error }}</p>',
                        errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
                    }
                });
                $('.dropify-gallery').dropify({
                    messages: {
                        'default': 'Drag and drop a file here or click',
                        'replace': 'Drag and drop or click to replace',
                        'remove':  'Remove',
                        'error':   'Ooops, something wrong happended.'
                    },
                    tpl: {
                        wrap:            '<div class="dropify-wrapper"></div>',
                        loader:          '<div class="dropify-loader"></div>',
                        message:         '<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',
                        preview:         '<div class="dropify-preview"><span class="dropify-render"></span></div>',
                        filename:        '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
                        clearButton:     '<button type="button" class="dropify-clear">{{ remove }}</button>',
                        errorLine:       '<p class="dropify-error">{{ error }}</p>',
                        errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
                    }
                });
            });

        </script>
        <!-- Page Specific JS Libraries -->
        <script src="<?php echo base_url();?>assets/plugins/smoothproducts/js/smoothproducts.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/switchery/js/switchery.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/multiselect/js/jquery.multi-select.js"></script>
        
		<!-- Required datatable js -->
        <script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/notifyjs/js/notify.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.app.js"></script>
        <script type="text/javascript">
			$(document).ready(function() {
				$('#datatable').DataTable();
			} );
        </script>

        <script src="<?php echo base_url();?>assets/plugins/ladda-buttons/js/spin.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/ladda-buttons/js/ladda.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>
        
        <!-- Modal-Effect -->
        <script src="<?php echo base_url();?>assets/plugins/custombox/js/custombox.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/custombox/js/legacy.min.js"></script>
        <script src="<?php echo base_url();?>assets/pages/jquery.form-pickers.init.js"></script>
		<!--  Flatpickr  -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/summernote/summernote-bs4.min.js"></script>
        <script>
            jQuery(document).ready(function(){

                $('.summernote').summernote({
                    height: 350,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                    focus: false,
					toolbar: [
						[ 'style', [ 'style' ] ],
						[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
						[ 'fontsize', [ 'fontsize' ] ],
						[ 'color', [ 'color' ] ],
						[ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
						[ 'table', [ 'table' ] ],
                        [ 'view', ['fullscreen', 'codeview', 'help']],
						[ 'insert', [ 'link'] ]
					],
                    callbacks: {
                        onPaste: function(e) {
                            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    }
                });
            });
        </script>
    </body>
</html>