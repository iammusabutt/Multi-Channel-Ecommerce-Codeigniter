<div class="hero-wrap js-fullheight inner-banner" style="background-image: url(<?php echo base_url();?>assets/front/images/flights-inner-banner.png);">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-9 text-center ftco-animate inner-banner-text" data-scrollax=" properties: { translateY: '70%' }">
            <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="<?php echo base_url();?>">Home</a></span> <span>Flights</span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo $pagetitle['from']->city_name;?> to <?php echo $pagetitle['to']->city_name;?></h1>
          </div>
        </div>
      </div>
    </div>
	
	<!------- FLIGHTS ------->
    <section class="ftco-section ftco-degree-bg flights-page">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
          	<div class="row">
				<?php if($all_routes):?>
				<?php foreach($all_routes as $route):?>
				<div class="col-md-3 ftco-animate">
					<div class="destination">
						<?php if(!empty($route['thumb'])) { ;?>
						<a href="<?php echo base_url();?>bookings" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url(<?php echo base_url(). $route['thumb'];?>);"></a>
						<?php } else { ;?>
						<a href="<?php echo base_url();?>bookings" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url(<?php echo base_url();?>assets/images/airline-default.jpg);"></a>
						<?php } ;?>
						<div class="text p-3">
							<div class="d-flex">
								<div class="one">
									<h3><a href="<?php echo base_url();?>bookings"><?php echo $route['flight_from'];?> to <?php echo $route['flight_to'];?></a></h3>
								</div>
								<div class="two">
									<span class="price"><?php echo $settings->currency_unit;?><?php echo $route['flight_price'];?></span>
								</div>
							</div>
							<hr>
							<p class="bottom-area d-flex">
								<span><i class="icon-map-o"></i> Return</span> 
								<span class="ml-auto"><a href="<?php echo base_url();?>bookings">Book Now</a></span>
							</p>
						</div>
					</div>
				</div>
				<?php endforeach;?>
				<?php else:?>
					There is no flight yet.
				<?php endif;?>
          	</div>
          </div>
        </div>
      </div>
    </section>