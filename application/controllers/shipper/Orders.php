<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Orders extends Shipper_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('orders_model');
		$this->load->model('currencies_model');
		$this->load->model('locations_model');
		$this->load->model('users_model');
		$this->load->model('products_model');
		$this->load->model('couriers_model');
		$this->lang->load('auth');
		$this->access_type = 'shipper';
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 5)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$shipper_id = $this->session->userdata('user_id');
			$from_date = $this->input->get('from_date', TRUE);
			$to_date = $this->input->get('to_date', TRUE);
			if($_POST)
			{
				$date_range = $this->input->post('date_range');
				$date_range_array = explode(" ", $date_range);
				redirect($this->access_type . "/orders?from_date=".$date_range_array[0]."&to_date=".$date_range_array[2], 'refresh');
			}
			if(isset($from_date) && isset($to_date) && !empty($from_date) && !empty($to_date)) {
				$set_range = $from_date." to ".$to_date;
			} else {
				$from_date = strtotime(date('Y-m-01'));
				$to_date = strtotime(date('Y-m-t'));
				$set_range = '';
			}
			$orders = $this->orders_model->get_orders($type = 'order', $author = NULL);
			if(!empty($orders)){
				foreach ($orders as $key => $value) {
					$order = $this->get_order($value);
					if($shipper_id == isset($order['shipper_id'])){
						$orders_list[] = $order;
					}
				}
				//echo '<pre>'; print_r($orders_list); echo '</pre>'; die();
				$this->data['orders'] = $orders_list;
			}
			$this->data['date_range'] = array(
				'name' => 'date_range',
				'id' => 'datepicker_opening',
				'type' => 'hidden',
				'value' => $this->form_validation->set_value('date_range', $set_range),
				'class' => 'form-control',
				'placeholder' => 'Custom -  From (Month DD, YYYY) to (Month DD, YYYY)',
			);
			$this->data['date_interval'] = array(
				'from_date' => $from_date,
				'to_date' => $to_date,
			);
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	public function order_detail($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 5)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$member_id = $this->session->userdata('user_id');
			if($_POST)
			{
				$this->form_validation->set_rules('date_created','Shipper','trim|required');
				$this->form_validation->set_rules('order_status','Tracking Number','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$date_created = $this->input->post('date_created');
					$order_status = $this->input->post('order_status');
					$timezone  = 'UP5';
					$gmt_time = local_to_gmt($date_created, $timezone);
					$local_time = gmt_to_local($gmt_time, $timezone);
					$data = array(
						'object_status' => $order_status,
						'object_date' => $local_time,
						'object_date_gmt' => $gmt_time,
					);
					$this->orders_model->update_object($object_id, $data);
					$this->session->set_flashdata('class', 'btn btn-success waves-effect waves-light');
					$this->session->set_flashdata('message', 'Order detail is updated');
					redirect($this->access_type . "/orders/order_detail/".$object_id, 'refresh');
				}
			}
		
			$object = $this->orders_model->get_order('order', $object_id);
			$order = $this->get_order($object);
						$this->data['date_created'] = array(
				'name' => 'date_created',
				'id' => 'date_created',
				'type' => 'text',
				'value' => $this->form_validation->set_value('object_date', !empty($object['object_date']) ? $object['object_date'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Month, DD YYYY',
			);
			$this->data['object_status'] = array(
				'name' => 'object_status',
				'id' => 'object_status',
				'type' => 'text',
				'value' => $this->form_validation->set_value('object_status', !empty($object['object_status']) ? $object['object_status'] : ""),
				'class' => 'form-control',
				'disabled' => 'disabled',
			);
			// $product_gallery = $this->orders_model->get_product_images('product_gallery' ,$object_id);
			$this->data['order'] = $order;
			// $this->data['product_gallery'] = $product_gallery;
			// echo '<pre>'; print_r($this->data['order']['product_gallery'] ); echo '</pre>'; die();
			
			$this->data['class'] = $this->session->flashdata('class');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'order_detail', $this->data);
		}
	}
	public function add_tracking($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 5)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$shipper_id = $this->session->userdata('user_id');
			if($_POST)
			{
				$tracking_number = $this->input->post('tracking_number');
				$courier_id = $this->input->post('courier_id');
			
				// validate form input
				$this->form_validation->set_rules('tracking_number','Tracking Number','trim|required');
				$this->form_validation->set_rules('courier_id','Courier','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$metadata = array(
						'tracking_number' => $tracking_number,
						'courier_id' => $courier_id,
					);
					$args = array(
						'id'  => $object_id,
						'metadata' => $metadata,
						'primary_key' => 'object_id',
						'table' => 'objectmeta',
						'meta_primary_key' => 'ometa_id',
					);
					$this->compute_meta_data($args);
					redirect($this->access_type . "/orders", 'refresh');
				}
			}
			$meta = $this->orders_model->get_ordermeta_single($object_id);
			$this->data['tracking_number'] = array(
				'name' => 'tracking_number',
				'id' => 'tracking_number',
				'type' => 'text',
				'value' => $this->form_validation->set_value('tracking_number', !empty($meta['tracking_number']) ? $meta['tracking_number'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Tracking Number',
			);
			$this->data['meta'] = $meta;
			$this->data['couriers'] = $this->couriers_model->get_couriers();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'add_tracking', $this->data);
		}
	}
	function ajax_datatable_pagination(){
		$shipper_id = $this->session->userdata('user_id');
		$postData = $this->input->post();
		$data = $this->orders_model->get_datatable_orders($postData, $type = 'order', $author = NULL, $postData['from_date'], $postData['to_date']);
		//echo '<pre>'; print_r($data); echo '</pre>'; die();
		echo json_encode($data);
	}
	public function ajax_update_status()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 5)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
			$json_data = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$user_id = $this->session->userdata('user_id');
				$object_id = $this->input->post('object_id');
				$vendor_id = $this->input->post('vendor_id');
				$this->form_validation->set_rules('object_id','Name','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'object_status'	=> 'returned'
					);
					$this->products_model->update_object($object_id, $data);
					$response = array(
						'response'	=> 'yes',
						'message'	=> 'Your cart is empty now',
						'content'	=> 'returned'
					);
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}

	private function get_order($value) {
		if(!empty($value)){
			$order_items = $this->orders_model->get_order_items($order_type = 'amazon_order', NULL, $value['object_id']);
			$objectmeta = $this->get_order_mera_data($value['object_id']);
			if(!empty($objectmeta)){
				foreach ($order_items as $order_item) {
					$order_meta = $this->orders_model->get_ordermeta($order_item['order_id']);
					$orderArr['order_id'] = $order_item['order_id'];
					$orderArr['order_name'] = $order_item['order_name'];
					$orderArr['order_type'] = $order_item['order_type'];
					$orderArr['object_id'] = $order_item['object_id'];
					$orderArr['order_status'] = $order_item['order_status'];
					$orderArr['vendor_id'] = $order_item['vendor_id'];
					if(!empty($order_item['vendor_id']))
					{
						$vendor = $this->ion_auth->user($order_item['vendor_id'])->row();
						$orderArr['vendor_name'] = !empty($vendor->company) ? $vendor->company : '';
					}
					if(!empty($order_meta)){
						foreach ($order_meta as $key => $item) {
							$orderArr[$item['meta_key']] = $item['meta_value'];
							if($item['meta_key'] == 'product_id') {
								$orderArr['product_gallery'] = $this->products_model->get_product_images($item['meta_value']);
								$productmeta = $this->products_model->get_objectmeta($item['meta_value']);
								if(!empty($productmeta)){
									foreach ($productmeta as $key => $item) {
										$product_array = array();
										$orderArr[$item['meta_key']] = $item['meta_value'];
										if($item['meta_key'] == 'thumbnail_id') {
											$productimage = $this->products_model->get_product_image_by_id($item['meta_value']);
											$orderArr['product_image'] = $productimage->thumb;
										}
									}
								}
							}
						}
					}
					$orderArray[] = $orderArr;
				}
				$order_details = array_merge($value, $objectmeta);
				$order_details['order_items'] = $orderArray;
			}
			if(!empty($order_details)){
			    return $order_details;
			}
		}
	}
	
	public function ajax_tracking_status()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 5)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$user_id = $this->session->userdata('user_id');
			$credential = $this->settings_model->get_credential($user_id);
			if(!empty($credential['secret_key']))
			{
				if($_POST)
				{
					$object_id = $this->input->post('object_id');
					$order_id = $this->input->post('order_id');
					$tracking_number = $this->input->post('tracking_number');
					if(!empty($tracking_number) && isset($tracking_number))
					{
						$url = 'https://api-eu.dhl.com/track/shipments?trackingNumber='.$tracking_number;
						$data = array(
							"DHL-API-Key: ". $credential['secret_key'],
						);
						$curl = curl_init();
						curl_setopt_array($curl, array(
							CURLOPT_URL => $url,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 30,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => "GET",
							CURLOPT_HTTPHEADER => $data,
						));
						$response = curl_exec($curl);
						$err = curl_error($curl);
						curl_close($curl);
						$result = json_decode($response);
						//echo '<pre>'; print_r($result); echo '</pre>'; die();
						if(!isset($result->status)){
							foreach($result->shipments as $shipment) {
								if(isset($shipment->status->status)){
									$status = $shipment->status->statusCode;
								} else {
									$description = $shipment->status->description;
									if($description == 'Shipment information received'){
										$status = "information received";
									} else {
										$description = $shipment->status->description;
									}
								}
							}
							$data = array(
								'object_status' => $status,
							);
							$this->orders_model->update_object($object_id, $data);
							$class = $this->class_type($status);
							$this->data['status'] = $status;
							$this->data['class'] = $class;
							//echo '<pre>'; print_r($status); echo '</pre>'; die();
							$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/tracking_status', $this->data, true);
							$response = array('response'=>'yes','message'=>'Tracking status updated to '.$status,'content'=>$theHTMLResponse);
							$this->output->set_content_type('application/json');
							$this->output->set_output(json_encode($response));
						} else {
							$data = array(
								'object_status' => 'invalid',
							);
							$class = $this->class_type($data['object_status']);
							$this->data['class'] = $class;
							if($result->status == '404'){
								$this->orders_model->update_object($object_id, $data);
								$this->data['status'] = $data['object_status'];
								$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/tracking_status', $this->data, true);
								$response = array('response'=>'no','title'=> $result->title,'class'=> $class,'message'=> $result->detail,'content'=>$theHTMLResponse);
								$this->output->set_content_type('application/json');
								$this->output->set_output(json_encode($response));
							} else {
								$this->data['status'] = 'unauthorized';
								$response = array('response'=>'no','title'=> $result->title, 'message'=> $result->detail);
								$this->output->set_content_type('application/json');
								$this->output->set_output(json_encode($response));
							}
						}
					} else {
						$response = array('response'=>'no','title'=>'No Tracking','message'=>'No tracking code assigned yet by shipper.');
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($response));
					}
				}
			} else {
				$response = array('response'=>'no','title'=>'API Secret Key Missing','message'=>'Get API secret key from DHL developer portal and Add it to API credentials in Settings');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($response));
			}
		}
	}
	private function get_order_mera_data($order_id) {
		$order_meta_data = $this->orders_model->get_objectmeta_single($order_id);
		//echo '<pre>'; print_r($order_id); echo '</pre>';
		//echo '<pre>'; print_r($order_meta_data); echo '</pre>'; die();
		if(!empty($order_meta_data)){
			$order_meta_array['ship_by_date'] = $order_meta_data['ship_by_date'];
			$order_meta_array['deliver_by_date'] = $order_meta_data['deliver_by_date'];
			$order_meta_array['customer_name'] = $order_meta_data['customer_name'];
			$order_meta_array['customer_phone'] = $order_meta_data['customer_phone'];
			$order_meta_array['shipping_address'] = $order_meta_data['shipping_address'];
			if(!empty($order_meta_data['country_id'])) {
				$country = $this->locations_model->get_country_by_id($order_meta_data['country_id']);
				$order_meta_array['country_id'] = $country->country_id;
				$order_meta_array['country_name'] = $country->country_name;
			}
			$shipper = $this->ion_auth->user($order_meta_data['shipper_id'])->row();
			if(!empty($shipper->company))
			{
				$order_meta_array['shipper_id'] = $shipper->id;
				$order_meta_array['shipper_name'] = $shipper->company;
			}
			$order_meta_array['tracking_number'] = $order_meta_data['tracking_number'];
			$order_meta_array['order_prefix'] = $order_meta_data['order_prefix'];
			$order_meta_array['order_serial'] = $order_meta_data['order_serial'];
		}
		if(!empty($order_meta_array)){
			return $order_meta_array;
		}
	}
	
	private function compute_meta_data($args){
		$meta_array = $this->settings_model->get_metadata($args['primary_key'], $args['id'], $args['table']);
		foreach ($meta_array as $key => $value) {
			$meta_keys[] = $value['meta_key'];
		}
		$meta_update = array();
		foreach ($args['metadata'] as $k => $v){
			$meta = $this->settings_model->get_metadata_by_key($k, $args['primary_key'], $args['id'], $args['table']);
			$array['meta_key'] = "'".$k."'";
			if($this->input->post($k)) {
				$array['post_values'] = $this->input->post($k);
			} else {
				$array['post_values'] = $v;
			}
			if(!empty($meta_keys)) {
				$key_exists = in_array($k, $meta_keys);
				if($key_exists == 1){
					$meta_update[] = array(
						$args['meta_primary_key']  => $meta[$args['meta_primary_key']],
						'meta_key' => !empty($k) ? $k : "",
						'meta_value' => $array['post_values'],
					);
				} else {
					$meta_add[] = array(
						$args['primary_key'] => $args['id'],
						'meta_key' => !empty($k) ? $k : "",
						'meta_value' => $array['post_values'],
					);
				}
			} else {
				$meta_add[] = array(
					$args['primary_key'] => $args['id'],
					'meta_key' => !empty($k) ? $k : "",
					'meta_value' => $array['post_values'],
				);
			}
		}
		if(!empty($meta_update)){
			$this->settings_model->update_metadata($args['primary_key'], $args['id'], $args['table'], $meta_update, $args['meta_primary_key']);
		}
		if(!empty($meta_add)){
			$this->settings_model->add_metadata($args['table'], $meta_add);
		}
	}
	private function class_type($status){
		switch ($status) {
			case "received":
				$class = 'info';
				break;
			case "transit":
				$class = 'warning';
				break;
			case "delivered":
				$class = 'success';
				break;
			case "invalid":
				$class = 'danger';
				break;
			case "unauthorized":
				$class = 'danger';
				break;
			default:
				$class = 'info';
		}
		return $class;
	}
}
