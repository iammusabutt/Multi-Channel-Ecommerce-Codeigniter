	<div class="hero-wrap js-fullheight inner-banner" style="background-image: url('<?php echo base_url();?>assets/front/images/flights-inner-banner.png');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-9 text-center ftco-animate inner-banner-text" data-scrollax=" properties: { translateY: '70%' }">
            <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.html">Home</a></span> <span>Contact Us</span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Contact Us</h1>
          </div>
        </div>
      </div>
	</div>
	<!-------- CONTACT PAGE -------->
	<section class="ftco-section contact-section ftco-degree-bg contact-page">
		<div class="container">
			<div class="row d-flex mb-5 contact-info">
				<div class="col-md-12 mb-4">
					<h2 class="h4">Contact Information</h2>
				</div>
				<div class="w-100"></div>
				<div class="col-md-4 ape">
					<ul>
						<li><img src="<?php echo base_url();?>assets/front/images/address-icon.png" /></li>
						<li><p><span>Address:</span><br> <?php echo $settings->admin_address;?></p></li>
					</ul>
				</div>
				<div class="col-md-4 ape">
					<ul>
						<li><img src="<?php echo base_url();?>assets/front/images/phone-icon.png" /></li>
						<li><p><span>Phone:</span><br> <a href="tel:<?php echo $settings->primary_phone;?>"><?php echo $settings->primary_phone;?></a>  |  <a href="tel:<?php echo $settings->secondary_phone;?>"><?php echo $settings->secondary_phone;?></a></p></li>
					</ul>
				</div>
				<div class="col-md-4 ape">
					<ul>
						<li><img src="<?php echo base_url();?>assets/front/images/email-icon.png" /></li>
						<li><p><span>Email:</span><br> <a href="mailto:<?php echo $settings->admin_email;?>"><?php echo $settings->admin_email;?></a></p></li>
					</ul>
				</div>
			</div>
			<div class="row block-9">
				<div class="col-md-12 order-md-last pr-md-5">
					<form name="contact_us_form" method="post">
						<div class="form-group">
							<input type="text" name ="first_name" class="form-control" placeholder="First Name">
						</div>
						<div class="form-group">
							<input type="text" name ="last_name" class="form-control" placeholder="Last Name">
						</div>
						<div class="form-group">
							<input type="text" name="mobile_number" class="form-control" placeholder="Phone">
						</div>
						<div class="form-group">
							<input type="email" name ="contact_email" class="form-control" placeholder="Email">
						</div>
						<div class="form-group">
							<textarea name="contact_message" id="" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>