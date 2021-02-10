<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php if(!empty($title)):?><?php echo $title;?><?php else:?><?php echo $settings->site_title;?><?php endif;?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="Sahal Travel Agency">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Abril+Fatface" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/aos.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/jquery.timepicker.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/flaticon.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/icomoon.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/custom.css">
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
		<div class="container">
			<a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/front/images/logo.png" ></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="oi oi-menu"></span> Menu
			</button>
			<div class="collapse navbar-collapse" id="ftco-nav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active"><a href="<?php echo base_url();?>" class="nav-link">Home</a></li>
					<li class="nav-item"><a href="<?php echo base_url();?>packages/hajj" class="nav-link">Hajj</a></li>
					<li class="nav-item"><a href="<?php echo base_url();?>packages/umrah" class="nav-link">Umrah</a></li>
					<li class="nav-item"><a href="<?php echo base_url();?>destinations" class="nav-link">Destinations</a></li>
					<li class="nav-item"><a href="<?php echo base_url();?>about_us" class="nav-link">About Us</a></li>
					<li class="nav-item"><a href="<?php echo base_url();?>contact_us" class="nav-link">Contact Us</a></li>
					<li class="hc-button"><span class="icon icon-phone"></span><?php echo $settings->primary_phone;?></li>
				</ul>
			</div>
		</div>
	</nav>
	<!-- END nav -->
	<div class="hero-wrap" style="background-image: url('<?php echo base_url();?>assets/front/images//banner.png');">
		<div class="overlay"></div>
		<div class="container">
			<div class="row no-gutters slider-text align-items-center justify-content-start" data-scrollax-parent="true">
				<div class="col-md-9 ftco-animate mb-5 pb-5 text-center text-md-left" data-scrollax=" properties: { translateY: '70%' }">
					<h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Discover <br>A new Place</h1>
					<p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Find great places to stay, eat, shop, or visit from local experts</p>
				</div>
			</div>
		</div>
	</div>


	<!-------- BANNER_FORM -------->
    <section class="ftco-section justify-content-end ftco-search banner-form">
    	<div class="container-wrap ml-auto">
			<div class="row no-gutters">
				<div class="col-md-12 nav-link-wrap">
					<div class="nav nav-pills justify-content-center text-center banner-form-heading" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					</div>
				</div>
				<div class="col-md-12 tab-wrap">
					<div class="tab-content p-4 px-5" id="v-pills-tabContent">
						<div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-nextgen-tab">
							<form class="search-destination" method="post">
								<div class="row">
									<div class="col-md align-items-end">
										<div class="form-group">
											<label for="#">Flight From</label>
											<div class="form-field">
												<div class="icon"><span class="icon-my_location"></span></div>
                                				<input type="hidden" name="autocomplete" id="flight_from-field-autocomplete">
                                				<input type="text" name="flight_from" class="form-control" id="flight_from-autocomplete" placeholder="Flying From" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="col-md align-items-end">
										<div class="form-group">
											<label for="#">Flight To</label>
											<div class="form-field">
												<div class="icon"><span class="icon-my_location"></span></div>
                                				<input type="hidden" name="autocomplete" id="flight_to-field-autocomplete"> 
                                				<input type="text" name="flight_to" class="form-control" id="flight_to-autocomplete" placeholder="Flying To" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="col-md align-self-end">
										<div class="form-group">
											<div class="form-field">
												<input type="submit" value="Search" class="form-control btn btn-primary">
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
    	</div>
    </section>
    <script src="<?php echo base_url();?>assets/front/js/typeahead.js"></script>