<div class="content dashboard">
	    <div class="container-fluid">
	        <!-- Page-Title -->
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="page-header-2">
	                    <ol class="breadcrumb pull-right mb-0">
	                        <li class="breadcrumb-item active">Dashboard</li>
	                    </ol>
	                    <h5 class="page-title">Dashboard</h5>
	                    <div class="clearfix"></div>
	                </div>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-md-6 col-lg-6 col-xl-3">
	                <div class="widget-panel widget-style-2 card-box">
	                    <i class="md md-attach-money text-primary"></i>
	                    <h2 class="m-0 counter font-600"><?php if(!empty($ait_branch)):?><?php echo $ait_branch;?><?php else:?>0<?php endif;?></h2>
	                    <div class="text-muted m-t-5">Iqbal Town</div>
	                </div>
	            </div>
	            <div class="col-md-6 col-lg-6 col-xl-3">
	                <div class="widget-panel widget-style-2 card-box">
	                    <i class="md md-attach-money text-primary"></i>
	                    <h2 class="m-0 counter font-600"><?php if(!empty($t_branch)):?><?php echo $t_branch;?><?php else:?>0<?php endif;?></h2>
	                    <div class="text-muted m-t-5">Township</div>
	                </div>
	            </div>
	            <div class="col-md-6 col-lg-6 col-xl-3">
	                <div class="widget-panel widget-style-2 card-box">
	                    <i class="md md-store-mall-directory text-info"></i>
	                    <h2 class="m-0 counter font-600">0</h2>
	                    <div class="text-muted m-t-5">Cafeteria</div>
	                </div>
	            </div>
	            <div class="col-md-6 col-lg-6 col-xl-3">
	                <div class="widget-panel widget-style-2 card-box">
	                    <i class="md md-account-child text-custom"></i>
	                    <h2 class="m-0 counter font-600">0</h2>
	                    <div class="text-muted m-t-5">Users</div>
	                </div>
	            </div>
	        </div>

	        <div class="row">
	            <div class="col-lg-12">
	                <div class="card-box">
	                    <h4 class="header-title m-t-0">Total Revenue</h4>
	                    <div class="row">
	                        <div class="col-md-8">
	                            <div class="text-center">
	                                <ul class="list-inline chart-detail-list">
	                                    <li class="list-inline-item">
	                                        <h5><i class="fa fa-circle m-r-5" style="color: #36404a;"></i>Desktops</h5>
	                                    </li>
	                                    <li class="list-inline-item">
	                                        <h5><i class="fa fa-circle m-r-5" style="color: #5d9cec;"></i>Consoles</h5>
	                                    </li>
	                                    <li class="list-inline-item">
	                                        <h5><i class="fa fa-circle m-r-5" style="color: #bbbbbb;"></i>Edibles</h5>
	                                    </li>
	                                </ul>
	                            </div>
	                            <div id="morris-area-with-dotted" style="height: 300px;"></div>
	                        </div>

	                        <div class="col-md-4">
	                            <p class="font-600">iMacs <span class="text-primary pull-right">80%</span></p>
	                            <div class="progress m-b-30">
	                                <div class="progress-bar progress-bar-primary progress-animated wow animated"
	                                    role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"
	                                    style="width: 80%">
	                                </div><!-- /.progress-bar .progress-bar-danger -->
	                            </div><!-- /.progress .no-rounded -->

	                            <p class="font-600">iBooks <span class="text-pink pull-right">50%</span></p>
	                            <div class="progress m-b-30">
	                                <div class="progress-bar progress-bar-pink progress-animated wow animated"
	                                    role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"
	                                    style="width: 50%">
	                                </div><!-- /.progress-bar .progress-bar-pink -->
	                            </div><!-- /.progress .no-rounded -->

	                            <p class="font-600">iPhone 5s <span class="text-info pull-right">70%</span></p>
	                            <div class="progress m-b-30">
	                                <div class="progress-bar progress-bar-info progress-animated wow animated"
	                                    role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"
	                                    style="width: 70%">
	                                </div><!-- /.progress-bar .progress-bar-info -->
	                            </div><!-- /.progress .no-rounded -->

	                            <p class="font-600">iPhone 6 <span class="text-warning pull-right">65%</span></p>
	                            <div class="progress m-b-30">
	                                <div class="progress-bar progress-bar-warning progress-animated wow animated"
	                                    role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"
	                                    style="width: 65%">
	                                </div><!-- /.progress-bar .progress-bar-warning -->
	                            </div><!-- /.progress .no-rounded -->

	                            <p class="font-600">iPhone 6s <span class="text-success pull-right">40%</span></p>
	                            <div class="progress m-b-30">
	                                <div class="progress-bar progress-bar-success progress-animated wow animated"
	                                    role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
	                                    style="width: 40%">
	                                </div><!-- /.progress-bar .progress-bar-success -->
	                            </div><!-- /.progress .no-rounded -->
	                        </div>
	                    </div>
	                    <!-- end row -->
	                </div>
	            </div>
	        </div>
	        <!-- end row -->
	    </div> <!-- container -->
	</div> <!-- content -->