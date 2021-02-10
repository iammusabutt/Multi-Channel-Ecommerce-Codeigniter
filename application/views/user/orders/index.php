<div class="content dashboard">
	    <div class="container-fluid">
	        <!-- Page-Title -->
	        <div class="row">
	            <div class="col-sm-12">
	                <div class="page-header-2">
	                    <ol class="breadcrumb pull-right mb-0">
	                        <li class="breadcrumb-item active">Orders</li>
	                    </ol>
	                    <h5 class="page-title">Orders</h5>
	                    <div class="clearfix"></div>
	                </div>
	            </div>
	        </div>
			<form id="form" name="filter" method="post" class="form-horizontal">
	        <div class="row filter-section-small m-b-20">
				<div class="col-lg-6">
					<div class="row">
						<div class="col-lg-10">
							<div class="form-input">
								<?php echo form_input($date_range);?>
							</div>
						</div>
						<div class="col-lg-2">
							<a class="btn btn-default btn-block waves-effect waves-light" onclick="document.filter.submit()">Filter</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
				</div>
			</div>
			<?php echo form_close();?>
	        <div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<div class="table-responsive">
							<table id='empTable' class='table table-hover mails m-0 table table-actions-bar'>
							<thead>
								<tr>
									<th>Order No</th>
									<th width="100px">Date</th>
									<th>Shipper</th>
									<th>Courier</th>
									<th>Tracking No</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>

							</table>
						</div>
					</div>
				</div> <!-- end col -->
			</div>
	    </div> <!-- container -->
	</div> <!-- content -->
	
	<script type="text/javascript">
	$(document).ready(function(){
		var from_date = '<?php echo $date_interval['from_date'];?>';
		var to_date = '<?php echo $date_interval['to_date'];?>';
		var table = $('#empTable').DataTable({
	      	'processing': true,
	      	'serverSide': true,
	      	'serverMethod': 'post',
	      	'ajax': {
	          	'url':'<?php echo base_url();?><?php echo $this->uri->segment(1);?>/orders/ajax_datatable_pagination',
				'data': {
					'from_date': from_date,
					'to_date': to_date
				}
	      	},
	      	'columns': [
	         	{ 
					data: 'object_id',
					render : function(data, type, row) {
						return '<a href='+base_url+account_type+'/orders/order_detail/'+row.object_id+' class="text-primary">#'+row.object_id+'</a>';
          			},
				},
				{ data: 'object_date' },
	         	{
					data: 'shipper',
					render : function(data, type, row) {
						return '<strong>'+data+'</strong>'
          			}
				},
	         	{
					data: 'courier',
					render : function(data, type, row) {
						return '<strong>'+data+'</strong>'
          			}
				},
	         	{ data: 'tracking_number' },
	         	{
					data: 'object_status',
					render : function(data, type, row) {
						if(row.object_status == 'delivered'){
              				return '<span class="label label-success">'+data+'</span>'
						} else if(row.object_status == 'pending') {
							return '<span class="label label-primary">'+data+'</span>'
						} else if(row.object_status == 'information received') {
							return '<span class="label label-info">'+data+'</span>'
						} else if(row.object_status == 'transit') {
							return '<span class="label label-warning">'+data+'</span>'
						} else if(row.object_status == 'failure') {
							return '<span class="label label-inverse">'+data+'</span>'
						} else if(row.object_status == 'invalid') {
							return '<span class="label label-danger">'+data+'</span>'
						} else if(row.object_status == 'refunded') {
							return '<span class="label label-danger">'+data+'</span>'
						} else if(row.object_status == 'returned') {
							return '<span class="label label-danger">'+data+'</span>'
						}
          			}
				},
	         	{ 
					render : function(data, type, row) {
						if(row.object_status == 'returned'){
							return 'Order action is no longer available';
						} else {
							return '<a class="btn btn-gold waves-effect waves-light mark-as-returned" data-objectid="'+row.object_id+'">Mark as Returned</a>';
						}
          			},
				},
	      	],
			createdRow: function (row, data, dataIndex) {
				$(row).addClass('action-row');
			}
	   	});
		
		$('#empTable tbody').on( 'click', '.mark-as-returned', function () {
			console.log(table.row( $(this).parents('tr') ).data());
    		var object_id = table.row( $(this).parents('tr') ).data().object_id;
			console.log(object_id);
			$.ajax({
				url: '<?php echo base_url();?><?php echo $this->uri->segment(1);?>/orders/ajax_update_status',
				type: "POST",
				async: "false",
				dataType: "html",
				data: {
					object_id: object_id
				},
				success: function(data) {
					table.draw();
				}
			});
		});
	});
	</script>