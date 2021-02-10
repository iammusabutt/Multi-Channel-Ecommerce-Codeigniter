	<!-------- ECONOMY UMRAH PACKAGE -------->
	<?php if(!empty($packages)): ?>
	<section class="ftco-about d-md-flex home-hajj">
		<div class="container">
		<?php $i = 0; foreach ($packages as $package): if ($i++ == 1) break; ?>
			<div class="row">
				<div class="col-md-6 img">
					<?php if($package->path):?>
						<img src="<?php echo base_url().$package->path;?>" width="100%">
					<?php else:?>
						<img src="<?php echo base_url().$package->path;?>" width="100%">
					<?php endif;?>
				</div>
				<div class="col-md-6 ftco-animate content">
					<div class="heading-section ftco-animate ">
						<h2 class="mb-4"><?php echo $package->title;?></h2>
						<p class="rate">
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							<span>5 Rating</span>
						</p>
						<p class="text-lite"><?php echo $package->sub_title;?></p>
					</div>
					<div class="p-details-features">
						<ul>
							<li><b>Start Date:</b> <?php $human = unix_to_human($package->departure_date); echo nice_date($human, 'd M Y');?></li>
							<li><b>End Date:</b> <?php $human = unix_to_human($package->return_date); echo nice_date($human, 'd M Y');?></li>
						</ul>
					</div>
					<div class="package-details">
						<h4>Package Details</h4>
						<ul>
							<li>Package Price: <?php echo $settings->currency_unit;?><?php echo $package->price;?></li>
							<li>Tickets Available</li>
							<li><b>Duration:</b>  <?php echo $package->no_of_days;?> Days</li>
						</ul>
					</div>
				</div>
				<div class="col-md-4"></div>
				<div class="col-md-2">
					<p class="details-button-blue"><a href="<?php echo base_url();?>packages/<?php echo $package->type;?>/details/<?php echo $package->id;?>">View Detail</a>
					</p>
				</div>
				<div class="col-md-2">
					<p class="details-button-lite"><a href="<?php echo base_url();?>packages/umrah">More Packages</a>
					</p>
				</div>
				<div class="col-md-4"></div>				
			<?php endforeach;?>
			</div>
		</div>
	</section>
	<?php else:?>
	<?php endif;?>
	

	<!-------- HOME FLIGHTS CAROUSEL -------->
    <section class="ftco-section testimony-section home-flight-carousel">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
					<h2 class="mb-4">Best Flight Offers</h2>
				</div>
			</div>
			<div class="row ftco-animate">
				<div class="col-md-12">
					<div class="carousel-testimony owl-carousel ftco-owl">
						<?php if(!empty($flight_offers)): foreach ($flight_offers as $index => $block):?>
						<div class="item">
							<div class="col-sm col-md-6 col-lg ftco-animate">
								<div class="destination">
									<div class="hf-main-heading">
										<h2><?php echo $block['0'];?></h2>
									</div>
									<div class="text p-3">
									<?php if(!empty($block)): foreach ($block as $index => $location): if ($index < 1) continue;?>
										<div class="d-flex">
											<div class="one">
												<h3><a href="<?php echo base_url();?>flights/route/<?php echo $location['flight_from_code'];?>/<?php echo $location['flight_to_code'];?>"><?php echo $location['flight_to'];?></a></h3>
											</div>
											<div class="two">
												<span class="price"><?php echo $settings->currency_unit;?><?php echo $location['flight_price'];?></span>
											</div>
										</div>
										<hr>
									<?php endforeach;?>
									<?php endif;?>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; else:?>
						No Destination Found
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
    </section>


	<!-------- 4 FEATURES THUMBNAILS -------->
    <section class="ftco-section services-section home-features">
		<div class="container">
			<div class="row d-flex">
				<div class="col-md-3 d-flex align-self-stretch ftco-animate text-center">
					<div class="media block-6 services d-block home-features-thumbs">
						<div class="icon-img"><img src="<?php echo base_url();?>assets/front/images/lowest-fares-icon.png"></div>
						<div class="media-body">
							<h3 class="heading mb-3">Lowest Fares Assured</h3>
							<p>Maecenas imperdiet quam quis scelerisque ultricies.</p>
						</div>
					</div>      
				</div>
				<div class="col-md-3 d-flex align-self-stretch ftco-animate text-center">
					<div class="media block-6 services d-block home-features-thumbs">
						<div class="icon-img"><img src="<?php echo base_url();?>assets/front/images/easy-quick-icon.png"></div>
						<div class="media-body">
							<h3 class="heading mb-3">Easy & Quick</h3>
							<p>Maecenas imperdiet quam quis scelerisque ultricies.</p>
						</div>
					</div>    
				</div>
				<div class="col-md-3 d-flex align-self-stretch ftco-animate text-center">
					<div class="media block-6 services d-block home-features-thumbs">
						<div class="icon-img"><img src="<?php echo base_url();?>assets/front/images/hajj-umrah-icon.png"></div>
						<div class="media-body">
							<h3 class="heading mb-3">Hajj & Umrah</h3>
							<p>Maecenas imperdiet quam quis scelerisque ultricies.</p>
						</div>
					</div>      
				</div>
				<div class="col-md-3 d-flex align-self-stretch ftco-animate text-center">
					<div class="media block-6 services d-block home-features-thumbs">
						<div class="icon-img"><img src="<?php echo base_url();?>assets/front/images/customer-care-icon.png"></div>
						<div class="media-body">
							<h3 class="heading mb-3">24/7 Customer Care</h3>
							<p>Maecenas imperdiet quam quis scelerisque ultricies.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
    </section>
	<!-------- HOME DESTINATIONS -------->
	<section class="ftco-section home-destinations">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section text-center ftco-animate">
					<h2><strong>Top</strong> Destinations</h2>
				</div>
			</div>
			<div class="row d-flex">
			<?php if(!empty($lowest_fares)): foreach ($lowest_fares as $index => $block):?>
				<div class="col-md-4 d-flex ftco-animate">
					<div class="flight-table">
						<div class="text p-3">
							<h3 class="table-heading"><?php echo $block['0'];?></h3>
							<?php if(!empty($block)): foreach ($block as $index => $location): if ($index < 1) continue;?>
							<div class="content-row">
								<div class="one">
									<h3><a href="<?php echo base_url();?>flights/route/<?php echo $location['flight_from_code'];?>/<?php echo $location['flight_to_code'];?>"><?php echo $location['flight_to'];?></a></h3>
								</div>
								<div class="two">
									<h3><?php echo $settings->currency_unit;?><?php echo $location['flight_price'];?></h3>
								</div>
							</div>
							<?php endforeach;?>
							<?php endif;?>
						</div>
					</div>
				</div>
				<?php endforeach; else:?>
				<?php endif;?>
				<div class="col-md-8  d-flex ftco-animate">
					<div class="row">
						<?php $i = 0; if(!empty($destinations)): ?>
							<?php foreach ($destinations as $destination): if ($i++ == 4) break;?>
									<div class="col-md-6 d-flex">
										<div class="blog-entry align-self-stretch">
											<a href="<?php echo base_url();?>destinations/<?php echo $destination['slug'];?>" class="block-20">
												<?php if(!empty($destination['thumb'])) { ;?>
												<img src="<?php echo base_url().$destination['thumb'];?>" width="100%">
												<?php } else { ;?>
												<img src="<?php echo base_url();?>assets/images/airline-default.jpg" width="100%">
												<?php } ;?>
											</a>
											<div class="text">
												<span class="tag"><?php echo $destination['name'];?></span>
											</div>
										</div>
									</div>
							<?php endforeach;?>
						<?php else:?>
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-------- TESTIMONIALS -------->
	<section class="ftco-section testimony-section home-testimonials">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
					<h2 class="mb-4">Our satisfied customer says</h2>
				</div>
			</div>
			<div class="row ftco-animate">
				<div class="col-md-12">
					<div class="carousel-testimony owl-carousel ftco-owl">
						<!-- Testimonials Section -->
				        <?php if(!empty($testimonials)): foreach ($testimonials as $testimonial):?>
						<div class="item">
							<div class="testimony-wrap p-4 pb-5">
								<div class="testimonial-ico">
									<span class="quote d-flex align-items-center justify-content-center">
										<i class="icon-quote-left"></i>
									</span>
								</div>
								<div class="text text-center">
									<p class="mb-5"><?php echo $testimonial->comment;?></p>
									<p class="name"><?php echo $testimonial->name;?></p>
									<span class="position"><?php echo $testimonial->designation;?><?php if(!empty($testimonial->company)):?>, <?php echo $testimonial->company;?><?php endif;?></span>
								</div>
							</div>
						</div>
    					<?php endforeach;?>
    					<?php endif;?>					
					</div>
				</div>
			</div>
		</div>
	</section>