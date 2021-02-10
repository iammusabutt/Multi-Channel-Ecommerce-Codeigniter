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
					<span property="name" class="active"><?php echo $this->uri->segment(2);?></span>
					<meta property="position" content="2">
				</span>
			</div>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><?php echo $this->uri->segment(2);?> Packages</h1>
          </div>
        </div>
      </div>
	</div>
	<section class="ftco-about d-md-flex home-hajj home-hajj-listing">
		<div class="container">
            <?php if(!empty($packages)): foreach ($packages as $package): ?>
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
								<li>Return Flight</li>
								<li>4-5 Person per Room</li>
								<li>Lunch and Dinner</li>
								<li>Group Transportation</li>
								<li>Guide (English, Arabic)</li>
							</ul>
						</div>
					</div>
					<div class="col-md-4"></div>
					<div class="col-md-2">
						<p class="details-button-blue"><a href="<?php echo base_url();?>contact_us">Book Now</a>
						</p>
					</div>
					<div class="col-md-2">
						<p class="details-button-lite"><a href="<?php echo base_url();?>packages/<?php echo $package->type;?>/details/<?php echo $package->id;?>">View Package</a>
						</p>
					</div>
					<div class="col-md-4"></div>
				</div>
			<?php endforeach; else:?>
			<?php endif;?>
		</div>
	</section>