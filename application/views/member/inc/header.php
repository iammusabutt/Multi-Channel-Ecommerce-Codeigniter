<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Ecommerce Management System">

        <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.ico">
        <link href="<?php echo base_url();?>assets/plugins/custombox/css/custombox.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css" />
        <title><?php if(isset($title)){echo $title.' -';}?> Fulfillment By Merchant</title>
        <link href="<?php echo base_url();?>assets/plugins/summernote/summernote-bs4.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/plugins/ladda-buttons/css/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css"  rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>assets/plugins/dropify/css/dropify.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url();?>assets/plugins/smoothproducts/css/smoothproducts.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/plugins/nestable/jquery.nestable.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/plugins/morris/morris.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css" />
		<link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css" rel="stylesheet"/>
        <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css" />
         <!-- ----------simpleLightbox -->
        <link href="<?php echo base_url();?>assets/plugins/simpleLightbox/simpleLightbox.css" rel="stylesheet" />
        <link href="<?php echo base_url();?>assets/plugins/simpleLightbox/simpleLightbox.min.css" rel="stylesheet" />
        <!-- ----------end simpleLightbox -->
        <script src="<?php echo base_url();?>assets/js/modernizr.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/typeahead.js"></script>
        <script>var base_url = '<?php echo base_url() ?>';</script>
        <script>var account_type = '<?php echo $this->uri->segment(1) ?>';</script>
        <script>
            $( function() {
                $("#datepicker_opening").flatpickr({
                    mode: "range",
                    altInput: true,
                    weekNumbers: true,
                    utc: true,
                    altFormat: "F, d Y",
                    dateFormat: "U",
                });
            });
        </script>
    </head>

    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="logo-box">
                        <a href="<?php echo base_url();?>admin/dashboard" class="logo text-center">
                            <span class="logo-lg">
                                <img src="<?php echo base_url();?>assets/images/logo_admin.png" alt="" height="35">
                                <!-- <span class="logo-lg-text-light">UBold</span> -->
                            </span>
                            <span class="logo-sm">
                                <!-- <span class="logo-sm-text-dark">U</span> -->
                                <img src="<?php echo base_url();?>assets/images/logo_admin.png" alt="" height="24">
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <nav class="navbar-custom">
                    <ul class="list-inline float-right mb-0">
                        <li class="list-inline-item notification-list">
                            <a class="nav-link waves-light waves-effect" href="#" id="btn-fullscreen">
                                <i class="dripicons-expand noti-icon"></i>
                            </a>
                        </li>

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo base_url();?>assets/images/logo-ld.png" alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5 class="text-overflow"><small>Welcome </small><div class="text-muted user-email"><?php echo $user['email'];?></div>  </h5>
                                </div>

                                <!-- item-->
                                <a href="<?php echo base_url();?>member/change_password" class="dropdown-item notify-item">
                                    <i class="md md-lock-outline"></i> <span>Change Password</span>
                                </a>

                                <!-- item-->
                                <a href="<?php echo base_url();?>member/edit_member/<?php echo $user['user_id'];?>" class="dropdown-item notify-item">
                                    <i class="md md-account-circle"></i> <span>Member Details</span>
                                </a>

                                <!-- item-->
                                <a href="<?php echo base_url();?>member/logout" class="dropdown-item notify-item">
                                    <i class="md md-settings-power"></i> <span>Logout</span>
                                </a>
                            </div>
                        </li>
                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="dripicons-menu"></i>
                            </button>
                        </li>
                        <li class="float-left view-site">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" href="<?php echo base_url();?>">View Site</a>
                        </li>
                    </ul>

                </nav>

            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->

            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                    <!--- Divider -->
                    <div id="sidebar-menu">
                        <ul>
                            <li>
                                <a href="<?php echo base_url();?>member/dashboard" class="waves-effect"><i class="ti-dashboard"></i><span> Dashboard </span> </a>
							</li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-clipboard"></i> <span> Order Management </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url();?>member/orders">View Orders</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-package"></i> <span> Products </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url();?>member/products">View products</a></li>
                                    <li><a href="<?php echo base_url();?>member/products/add_product?post_type=product">Add Product</a></li>
                                    <li><a href="<?php echo base_url();?>member/products/attributes">Attributes</a></li>
                                </ul>
                            </li>
                            <li class="text-muted menu-title">Profile Management</li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-link"></i> <span> Link Accounts </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url();?>member/users">Accounts</a></li>
                                    <li><a href="<?php echo base_url();?>member/users/create_user">Create Account</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-id-badge"></i> <span> Vendors </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url();?>member/vendors">View Vendors</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-truck"></i> <span> Shippers </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url();?>member/shippers">View Shippers</a></li>
                                </ul>
                            </li>
                            <li class="text-muted menu-title">Settings</li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-settings"></i> <span> Settings </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="<?php echo base_url();?>member/change_password">Change Password</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?php echo base_url();?>member/logout" class="waves-effect"><i class="ti-home"></i><span> Logout </span> </a>
							</li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- Left Sidebar End -->
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">