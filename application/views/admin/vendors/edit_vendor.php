	<!-- Start content -->
	<div class="content">
	    <div class="container-fluid">
	        <!-- Page-Title -->
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="page-header-2">
	                    <ol class="breadcrumb pull-right mb-0">
	                        <li class="breadcrumb-item active">Edit Vendor</li>
	                    </ol>
	                    <h4 class="page-title">Edit Vendor</h4>
	                    <div class="clearfix"></div>
	                </div>
	                <div id="infoMessage"><?php echo $message;?></div>
	                <?php $attributes = array('class' => 'form-horizontal', 'name' => 'edit_vendor');?>
	                <?php echo form_open($this->uri->segment(1)."/vendors/edit_vendor/". $user['id'], $attributes);?>
	                <div class="row">
	                    <div class="col-lg-9 col-md-9 col-sm-12">
	                        <div class="card-box">
	                            <div class="row">
	                                <div class="col-6">
	                                    <div class="form-group row">
	                                        <label for="company"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">Company Name <span
	                                                class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_input($company);?>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="col-6">
	                                    <div class="form-group row">
	                                        <label for="shipper_type"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">Store Type <span
	                                                class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <select name="shipper_type" class="selectpicker" data-style="btn-white">
	                                                <option class="option-disabled">-- Select Store Type --</option>
	                                                <option value="company">Company</option>
	                                                <option value="partnership">Partnership</option>
	                                                <option value="individual">Individual</option>
	                                            </select>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="col-6">
	                                    <div class="form-group row">
	                                        <label for="country_id"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">Country <span
	                                                class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <select name="country_id" class="selectpicker" data-live-search="true"
	                                                data-style="btn-white">
	                                                <?php if(!empty($countries)): foreach ($countries as $country): ?>
	                                                <option value="<?php echo $country->country_id;?>"
	                                                    data-subtext="<?php echo $country->continent_name;?>">
	                                                    <?php echo $country->country_name;?></option>
	                                                <?php endforeach; else:?>
	                                                <option>No Records Found</option>
	                                                <?php endif;?>
	                                            </select>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="col-6">
	                                    <div class="form-group row">
	                                        <label for="phone" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Phone
	                                            <span class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_input($phone);?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="col-12">
	                                    <div class="form-group row">
	                                        <label for="shipper_term_conditions"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">Terms $ Conditions
	                                            <span class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_textarea($shipper_term_conditions);?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div><!-- end row -->
	                        </div> <!-- end card-box -->
	                        <div class="card-box">
	                            <div class="row">
	                                <div class="col-6">
	                                    <div class="form-group row">
	                                        <label for="first_name"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">First Name <span
	                                                class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_input($first_name);?>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="col-6">
	                                    <div class="form-group row">
	                                        <label for="last_name"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">Last Name <span
	                                                class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_input($last_name);?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="col-12">
	                                    <div class="form-group row">
	                                        <label for="email" class="col-lg-12 col-md-12 col-sm-12 col-form-label">Email
	                                            <span class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_input($email);?>
	                                        </div>
	                                    </div>
	                                    <div class="form-group row">
	                                        <label for="branch_name"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">Password <span
	                                                class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_input($password);?>
	                                        </div>
	                                    </div>
	                                    <div class="form-group row">
	                                        <label for="branch_name"
	                                            class="col-lg-12 col-md-12 col-sm-12 col-form-label">Confirm Password <span
	                                                class="text-danger">*</span></label>
	                                        <div class="col-lg-12 col-md-12 col-sm-12">
	                                            <?php echo form_input($password_confirm);?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div><!-- end col -->
	                    <div class="col-lg-3 col-md-3 col-sm-12">
	                        <div class="card-box">
	                            <div class="row">
	                                <div class="col-12">
	                                    <a class="btn btn-gold btn-block waves-effect waves-light"
	                                        onclick="document.edit_vendor.submit()">Update Vendor</a>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div><!-- end row -->
	                <?php echo form_close();?>
	            </div> <!-- container -->
	        </div> <!-- content -->

	        <script>
	        $('select[name=shipper_type]').val('<?php echo $user_metadata['shipper_type'];?>');
	        $('select[name=country_id]').val('<?php echo $user_metadata['shipper_country_id'];?>');
	        $('.selectpicker').selectpicker('refresh');
	        </script>