	<footer class="footer text-right">
		&copy; 2019. All rights reserved. Made with love by <a href="https://www.drcodex.com">DrCodeX Technologies</a>
	</footer>
</div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
        <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
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
        <!-- Page Specific JS Libraries -->
        <script src="<?php echo base_url();?>assets/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/switchery/js/switchery.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/multiselect/js/jquery.multi-select.js"></script>
        
		<!-- Required datatable js -->
        <script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
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
			});

		</script>
        <script src="<?php echo base_url();?>assets/plugins/notifyjs/js/notify.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/nestable/jquery.nestable.js"></script>
        <script src="<?php echo base_url();?>assets/pages/nestable.js"></script>
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
        <script src="<?php echo base_url();?>assets/plugins/morris/morris.min.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/raphael/raphael-min.js"></script>
        <script src="<?php echo base_url();?>assets/pages/jquery.dashboard_ecommerce.js"></script>
    </body>
</html>