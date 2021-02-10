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
        <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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