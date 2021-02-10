<div class="hero-wrap js-fullheight inner-banner"
    style="background-image: url('<?php echo base_url();?>assets/front/images/flights-inner-banner.png');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center"
            data-scrollax-parent="true">
            <div class="col-md-9 text-center ftco-animate inner-banner-text"
                data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span
                        class="mr-2"><a href="index.html">Home</a></span> <span>Booking</span></p>
                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Booking</h1>
            </div>
        </div>
    </div>
</div>
<!------- BOOKING ------->
<section class="ftco-section contact-section ftco-degree-bg booking-page">
    <div class="container">
        <div class="row d-flex mb-5 contact-info">
            <div class="col-md-12 mb-4">
                <h2 class="h4">Booking Form</h2>
            </div>
        </div>
        <div class="row block-12">
            <div class="col-md-12 pr-md-5">
                <form id="form" name="booking" method="post">
                    <div class="row">
                        <div class="input-field col s4">
                            <input type="text" name="full_name" class="form-control" placeholder="Full Name *">
                        </div>
                        <div class="input-field col s4">
                            <input type="text" name="phone" class="form-control" placeholder="Your Phone *">
                        </div>
                        <div class="input-field col s4">
                            <input type="text" name="email" class="form-control" placeholder="Your Email *">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input type="text" name="flight_from" class="form-control" placeholder="Leaving From *">
                        </div>
                        <div class="input-field col s6">
                            <input type="text" name="flight_to" class="form-control" placeholder="Going To *">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input type="text" name="takeoff_time" id="checkin_date" class="form-control"
                                placeholder="Arrival Date *">
                        </div>
                        <div class="input-field col s6">
                            <input type="text" name="landing_time" id="checkin_date" class="form-control"
                                placeholder="Departure Date *">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s4">
                            <select name="adults" class="form-control" placeholder="No. of Adults *">
                                <option value="">No of Adults *</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="input-field col s4">
                            <select name="kids" class="form-control" placeholder="No. of Kids">
                                <option value="">No of Adults</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="input-field col s4">
                            <select name="infants" class="form-control" placeholder="No. of Infants">
                                <option value="">No of Adults</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <select name="type" id="type" class="form-control">
                                <option value="Return Flight">Return Flight</option>
                                <option value="One Way">One Way</option>
                            </select>
                        </div>
                        <div class="input-field col s6">
                            <select name="class" id="class" class="form-control">
                                <option value="Economy">Economy</option>
                                <option value="Premium">Premium</option>
                                <option value="First Class">First Class</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s4">
                            <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>