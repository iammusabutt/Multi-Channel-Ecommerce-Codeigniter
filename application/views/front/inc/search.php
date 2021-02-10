<div class="search">
	<!-- Search Contents -->
	<div class="container fill_height">
		<div class="row fill_height">
			<div class="col fill_height">
				<!-- Search Panel -->
				<div class="search_panel active">
					<form id="search_form_3" class="search_panel_content d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-lg-between justify-content-start" method="post">
						<div class="search_item">
							<div>Flight From</div>
							<input type="hidden" name="autocomplete" id="flight_from-field-autocomplete">
							<input type="text" name="flight_from" class="destination search_input" id="flight_from-autocomplete" placeholder="Flying From" autocomplete="off">
						</div>
						<div class="search_item">
							<div>Flight To</div>
							<input type="hidden" name="autocomplete" id="flight_to-field-autocomplete"> 
							<input type="text" name="flight_to" class="destination search_input" id="flight_to-autocomplete" placeholder="Flying To" autocomplete="off">
						</div>
						<button class="button search_button">search<span></span><span></span><span></span></button>
					</form>
				</div>

			</div>
		</div>
	</div>		
</div>
<script src="<?php echo base_url();?>assets/front/js/typeahead.js"></script>