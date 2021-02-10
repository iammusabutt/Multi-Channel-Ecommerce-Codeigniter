<div class="hero-wrap js-fullheight inner-banner" style="background-image: url('<?php echo base_url();?>assets/front/images/hajj-inner-banner.png');">
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
					<a property="item" typeof="WebPage" title="Packages"
						href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>" class="post post-page">
						<span property="name">Packages</span>
					</a>
					<meta property="position" content="1">
				</span> &gt;
				<span property="itemListElement" typeof="ListItem">
					<a property="item" typeof="WebPage" title="Hajj Packages"
						href="<?php echo base_url();?><?php echo $this->uri->segment(1);?>/<?php echo $this->uri->segment(2);?>" class="post post-page">
						<span property="name"><?php echo $this->uri->segment(2);?></span>
					</a>
					<meta property="position" content="2">
				</span> &gt;
				<span property="itemListElement" typeof="ListItem">
					<span property="name" class="active"><?php echo $package->title;?></span>
					<meta property="position" content="3">
				</span>
			</div>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo $this->uri->segment(2);?> Packages</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="info-action">
        <div class="container">
			<div class="row">
            <div class="banner_book_1">
					<ul>
						<li class="dl1">Location : <?php echo $package->location_name;?></li>
                        <li class="dl2">
                        </li>
                        <li class="dl3">Duration : <?php echo $package->no_of_days;?> Days</li>
						<li class="dl4"><div class="button button_color_1 trans_200"><a href="<?php echo base_url();?>contact_us">Book Now<span></span><span></span><span></span></a></div></li>
					</ul>
				</div>
                
            </div>
        </div>
    </div>
    <section class="ftco-section ftco-degree-bg hajj-package-detail">
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<div class="row">
						<div class="col-md-12 ftco-animate">
							<div class="single-slider owl-carousel">
								<div class="item">
                            <?php if(!empty($featured_image->path)):?>
                            	<div class="hotel-img" style="background-image: url(<?php echo base_url().$featured_image->path;?>);"></div>
                            <?php else:?>
                            <?php endif;?>
								</div>
							</div>
						</div>
						<div class="col-md-12 mt-4 mb-5 ftco-animate">
							<h2><?php echo $package->title;?></h2>
							<p class="rate mb-5">
								<span class="star">
									<i class="icon-star"></i>
									<i class="icon-star"></i>
									<i class="icon-star"></i>
									<i class="icon-star"></i>
									<i class="icon-star"></i>
								5 Rating</span>
							</p>
                            <?php if(isset($package->description)):?>
                                <div class="blog_post_text">
                                    <p><?php echo $package->description;?></p>
                                </div>
                            <?php else:?>
                            <?php endif;?>
                            
							<?php if(!empty($package->prices)): foreach ($package->prices as $departure => $price):?>
								<p class="text-dark m-b-5"><b>Departure</b> from <?php echo $price['city_name'];?>: <span class="text-muted"><?php echo $settings->currency_unit;?><?php echo $price['sale_price'];?></p>
							<?php endforeach;?>
							<?php endif;?>
                            <?php if($gallery):?>
							<h2>Gallery</h2>
                            <div class="row">
                                <div class="col-md-12 ftco-animate">
                                    <div class="single-slider owl-carousel">   
                                    <?php foreach ($gallery as $image): ?>
                                        <div class="item">
                                            <div class="hotel-img" style="background-image: url(<?php echo base_url().$image->path;?>);"></div>
                                        </div>
                                    <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                            <?php else:?>
                            <?php endif;?>
                            
                            <?php if($location):?>
							<h2>Location</h2>
    						<div class="d-md-flex mt-5 mb-5 listing">
                                <?php echo $location->meta_value;?>
                            </div>
                            <?php else:?>
                            <?php endif;?>
                            
                            <?php if($features):?>
							<h2>Features</h2>
    						<div class="d-md-flex mt-5 mb-5 listing">
	    						<ul class="ml-md-5">
                                    <?php foreach ($features as $feature): ?>
                                    <li><?php echo $feature->meta_value;?></li>
                                    <?php endforeach;?>
								</ul>
							</div>
                            <?php else:?>
                            <?php endif;?>
                            <?php if($amenities):?>
							<h2>Amenities</h2>
    						<div class="d-md-flex mt-5 mb-5 listing">
	    						<ul class="ml-md-5">
                                    <?php foreach ($amenities as $amenity): ?>
                                    <li><?php echo $amenity->meta_value;?></li>
                                    <?php endforeach;?>
								</ul>
							</div>
                            <?php else:?>
                            <?php endif;?>
						</div>
						<?php if($itinerary):?>
						<div class=" col-md-12 tour_head1 l-info-pack-days days">
							<h4>Detailed Day Wise Itinerary</h4>
							<ul class="itinerary-bullets">
                                <?php foreach ($itinerary as $iti): ?>
								<li class="l-info-pack-plac">
									<h6><span>Day : <?php echo $iti->day;?></span> <?php echo $iti->title;?></h6>
                                    <p><?php echo $iti->detail;?></p>
								</li>
                                <?php endforeach;?>
							</ul>
						</div>
                        <?php else:?>
                        <?php endif;?>
					</div>
				</div>
				<?php $this->load->view('front' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'sidebar');?>
			</div>
		</div>
    </section>