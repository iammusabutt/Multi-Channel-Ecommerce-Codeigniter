    <!-- Inner Title Banner -->
	<?php if(!empty($destination->thumb)):?>
	<div class="hero-wrap js-fullheight inner-banner" style="background-image: url('<?php echo base_url().$destination->thumb;?>');">
	<?php else:?>
	<div class="hero-wrap js-fullheight inner-banner" style="background-image: url('<?php echo base_url();?>assets/front/images/destinations-inner-banner.png');">
	<?php endif;?>
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-9 text-center ftco-animate inner-banner-text" data-scrollax=" properties: { translateY: '70%' }">
			<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
				<span>
					<a href="<?php echo base_url();?>" class="post post-page">
						<span>Home</span>
					</a>
				</span> &gt;
				<span property="itemListElement" typeof="ListItem">
					<a property="item" typeof="WebPage" title="Travel Destinations"
						href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/<?php echo $this->uri->segment(2);?>" class="post post-page">
						<span property="name">Destinations</span>
					</a>
					<meta property="position" content="1">
				</span> &gt;
				<span property="itemListElement" typeof="ListItem">
					<span property="name" class="active"><?php echo $destination->title;?></span>
					<meta property="position" content="2">
				</span>
			</div>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo $destination->title;?></h1>
          </div>
        </div>
      </div>
    </div>
	<?php if($destination) {;?>	
		<?php if($destination->description):?>
		<section class="ftco-about d-md-flex destinations-detail">
			<div class="container">	
				<div class="row">
					<div class="col-md-12 ftco-animate content">
						<div class="heading-section ftco-animate ">
							<h2 class="mb-4"><?php echo $destination->title;?></h2>
							<p><?php echo $destination->description;?></p>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<?php if(isset($target_destinations)):?>	
		<!-------- HOME DESTINATIONS -------->
		<section class="ftco-section flight-list">
			<div class="container">
				<div class="row justify-content-center mb-5 pb-3">
					<div class="col-md-7 heading-section text-center ftco-animate">
						<h4><?php echo $destination->title;?></h4>
					</div>
				</div>
				<div class="row d-flex">
					<div class="col-md-12 ftco-animate">
						<div class="flights-table">
							<div class="text p-3">
							<?php foreach($target_destinations as $value):?>
								<?php if($value):?>
								<?php foreach($value as $target_destination):?>
								<div class="content-row">
									<div class="row">
										<div class="col-md-7 flight-title">
											<img src="<?php echo base_url();?>assets/front/images/aeroplane.png">
											<h3><?php echo $target_destination['flight_from'];?> to <?php echo $target_destination['flight_to'];?></h3>
										</div>
										<div class="col-md-3 flight-price">
											<h3>Starting from: <strong><?php echo $settings->currency_unit;?><?php echo $target_destination['flight_price'];?></strong></h3>
										</div>
										<div class="col-md-2">
											<div><a class="button" href="<?php echo base_url();?>flights/route/<?php echo $target_destination['flight_from_code'];?>/<?php echo $target_destination['flight_to_code'];?>">Get Flights</a></div>
										</div>
									</div>
								</div>
								<?php endforeach;?>
								<?php endif;?>
								<?php endforeach;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<? else:?>
		<?php endif;?>
		<?php else:?>
		<?php endif;?>
	<?php } ;?>


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