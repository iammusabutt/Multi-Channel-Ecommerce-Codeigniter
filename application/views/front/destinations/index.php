<div class="hero-wrap js-fullheight inner-banner"
	    style="background-image: url('<?php echo base_url();?>assets/front/images/destinations-inner-banner.png');">
	    <div class="overlay"></div>
	    <div class="container">
	        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center"
	            data-scrollax-parent="true">
	            <div class="col-md-9 text-center ftco-animate inner-banner-text"
	                data-scrollax=" properties: { translateY: '70%' }">
	                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span
	                        class="mr-2"><a href="index.html">Home</a></span> <span>Destinations</span></p>
	                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Destinations
	                </h1>
	            </div>
	        </div>
	    </div>
	</div>
	<!-------- HOME DESTINATIONS -------->
	<section class="ftco-section home-destinations destinations-page">
		<div class="container">
			<div class="row d-flex">			
				<div class="col-md-12  d-flex ftco-animate">
					<div class="row">
	            	<?php if(!empty($destinations)): foreach ($destinations as $destination): ?>
						<div class="col-md-4 d-flex">
							<div class="blog-entry align-self-stretch">
								<a href="<?php echo base_url();?>destinations/<?php echo $destination['slug'];?>" class="block-20">
									<?php if(!empty($destination['thumb'])) { ;?>
										<img src="<?php echo base_url().$destination['thumb'];?>" width="100%">
									<?php } else { ;?>
										<img src="<?php echo base_url();?>assets/front/img/about-img.png" width="100%">
									<?php } ;?>
								</a>
								<div class="text">
									<span class="tag"><?php echo $destination['name'];?></span>
								</div>
							</div>
						</div>
	            	<?php endforeach; else:?>
	            	<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</section>