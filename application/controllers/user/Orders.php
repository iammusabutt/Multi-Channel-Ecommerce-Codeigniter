<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Orders extends User_Controller
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
		$this->load->model('marketplaces_model');
		$this->load->model('couriers_model');
		$this->load->model('stock_model');
		$this->lang->load('auth');
		$this->access_type = 'user';
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$role_id = $this->session->userdata('user_id');
			$from_date = $this->input->get('from_date', TRUE);
			$to_date = $this->input->get('to_date', TRUE);
			if($_POST)
			{
				$date_range = $this->input->post('date_range');
				$date_range_array = explode(" ", $date_range);
				redirect($this->access_type . "/orders?from_date=".$date_range_array[0]."&to_date=".$date_range_array[2], 'refresh');
			}
			//echo '<pre>'; print_r($orders); echo '</pre>'; die();
			if(isset($from_date) && isset($to_date) && !empty($from_date) && !empty($to_date)) {
				$set_range = $from_date." to ".$to_date;
			} else {
				$from_date = strtotime(date('Y-m-01'));
				$to_date = strtotime(date('Y-m-t'));
				$set_range = '';
			}
			//echo '<pre>'; print_r($from_date); echo '</pre>';
			$orders = $this->users_model->get_role_joint_by_date_range($role_id, $taxonomy = 'user_order', $table = 'orders', $column = 'order_id', $is_object = TRUE, $from_date, $to_date);
			if(!empty($orders)){
				$this->data['orders'] = $this->get_orders($orders);
				//echo '<pre>'; print_r($this->data['orders']); echo '</pre>'; die();
			}
			$this->data['date_range'] = array(
				'name' => 'date_range',
				'id' => 'datepicker_opening',
				'type' => 'hidden',
				'value' => $this->form_validation->set_value('date_range', $set_range),
				'class' => 'form-control',
				'placeholder' => 'Custom -  From (Month DD, YYYY) to (Month DD, YYYY)',
			);
			//echo '<pre>'; print_r($tm_gmt_time); echo '</pre>'; die();
			$this->data['date_interval'] = array(
				'from_date' => $from_date,
				'to_date' => $to_date,
			);
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function ajax_update_status()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
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
	public function ajax_tracking_status()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
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
						"DHL-API-Key: vsqLgrATs9zb77GCXKIOmVylxBOzQKsh",
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
					foreach($result->shipments as $shipment) {
						//echo '<pre>'; print_r($shipment); echo '</pre>'; die();
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
						case "canceled":
							$class = 'danger';
							break;
						default:
							$class = 'info';
					}
					$this->data['status'] = $status;
					$this->data['class'] = $class;
					//echo '<pre>'; print_r($status); echo '</pre>'; die();
					$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/tracking_status', $this->data, true);
					$response = array('response'=>'yes','message'=>'Tracking status','content'=>$theHTMLResponse);
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
				else
				{
					$response = array('response'=>'no','message'=>'No tracking code assigned yet by shipper.');
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}

	public function edit_order($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$object = $this->orders_model->get_order('order', $object_id);
			if($_POST)
			{
				$product_id = $this->input->post('autocomplete');
				$item_quantity = $this->input->post('item_quantity');
				$ship_by_date = $this->input->post('ship_by_date');
				$deliver_by_date = $this->input->post('deliver_by_date');
				$your_earning = $this->input->post('your_earning');
				$order_number = $this->input->post('order_number');
				$currency_id = $this->input->post('currency_id');
				$customer_name = $this->input->post('customer_name');
				$customer_phone = $this->input->post('customer_phone');
				$shipping_address = $this->input->post('shipping_address');
				$country_id = $this->input->post('country_id');
			
				// validate form input
				$this->form_validation->set_rules('item_quantity','Product Quantity','trim|required');
				$this->form_validation->set_rules('ship_by_date','Ship by Date','trim|required');
				$this->form_validation->set_rules('deliver_by_date','Deliver by Date','trim|required');
				$this->form_validation->set_rules('your_earning','Earnings','trim|required');
				$this->form_validation->set_rules('order_number','Order Number','trim|required');
				$this->form_validation->set_rules('currency_id','Currency','trim|required');
				$this->form_validation->set_rules('customer_name','Customer Name','trim|required');
				$this->form_validation->set_rules('customer_phone','Customer Phone','trim|required');
				$this->form_validation->set_rules('shipping_address','Shipping Address','trim|required');
				$this->form_validation->set_rules('country_id','Country','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$user_id = $this->session->userdata('user_id');
					$timezone  = 'UP5';
					$gmt_time = local_to_gmt(time(), $timezone);
					$local_time = gmt_to_local($gmt_time, $timezone);
					$data = array(
						'object_modified' => $local_time,
						'object_modified_gmt' => $gmt_time,
					);
					$this->orders_model->update_object($object_id, $data);
					$ordermeta = $this->orders_model->get_ordermeta($object['order_id']);
					$order_array = array();
					foreach ($ordermeta as $key => $order){
						if(!empty($this->input->post($order['meta_key'])))
						{
							$array['meta_key'] = "'".$order['meta_key']."'";
							$array['post_values'] = $this->input->post($order['meta_key']);
							$order_array[] = array(
								'order_meta_id'  => $order['order_meta_id'],
								'meta_key' => $order['meta_key'],
								'meta_value' => $array['post_values'],
							);
						}
					}
					$this->orders_model->update_ordermeta($object['order_id'], $order_array);
					$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					redirect($this->access_type . "/orders/edit_order/".$object_id, 'refresh');
				}
			}
			$meta = $this->orders_model->get_ordermeta_single($object['order_id']);
			$this->data['item_name'] = array(
				'name' => 'item_name',
				'id' => 'item_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('item_name', !empty($meta['item_name']) ? $meta['item_name'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter product name here',
			);
			$this->data['item_quantity'] = array(
				'name' => 'item_quantity',
				'id' => 'item_quantity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('item_quantity', !empty($meta['item_quantity']) ? $meta['item_quantity'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter product quantity here',
			);
			$this->data['ship_by_date'] = array(
				'name' => 'ship_by_date',
				'id' => 'ship_by',
				'type' => 'text',
				'value' => $this->form_validation->set_value('ship_by_date', !empty($meta['ship_by_date']) ? $meta['ship_by_date'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Month, DD YYYY',
			);
			$this->data['deliver_by_date'] = array(
				'name' => 'deliver_by_date',
				'id' => 'deliver_by',
				'type' => 'text',
				'value' => $this->form_validation->set_value('deliver_by_date', !empty($meta['deliver_by_date']) ? $meta['deliver_by_date'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Month, DD YYYY',
			);
			$this->data['your_earning'] = array(
				'name' => 'your_earning',
				'id' => 'your_earning',
				'type' => 'text',
				'value' => $this->form_validation->set_value('your_earning', !empty($meta['your_earning']) ? $meta['your_earning'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter your earning here',
			);
			$this->data['order_number'] = array(
				'name' => 'order_number',
				'id' => 'order_number',
				'type' => 'text',
				'value' => $this->form_validation->set_value('order_number', !empty($meta['order_number']) ? $meta['order_number'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter marketplace order number here',
			);
			$this->data['customer_name'] = array(
				'name' => 'customer_name',
				'id' => 'customer_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('customer_name', !empty($meta['customer_name']) ? $meta['customer_name'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter customer name here',
			);
			$this->data['customer_phone'] = array(
				'name' => 'customer_phone',
				'id' => 'customer_phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('customer_phone', !empty($meta['customer_phone']) ? $meta['customer_phone'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter customer phone here',
			);
			$this->data['shipping_address'] = array(
				'name' => 'shipping_address',
				'id' => 'shipping_address',
				'type' => 'text',
				'value' => $this->form_validation->set_value('shipping_address', !empty($meta['shipping_address']) ? $meta['shipping_address'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter shipping address here',
			);
			$this->data['product'] = $object;
			$this->data['ordermeta'] = $meta;
			//echo '<pre>'; print_r($this->data['ordermeta']); echo '</pre>'; die();
			$this->data['countries'] = $this->locations_model->get_all_countries();
			$this->data['currencies'] = $this->currencies_model->get_currencies();
			$this->data['vendors'] = $this->ion_auth->users(4)->result();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'edit_order', $this->data);
		}
	}
	public function place_order()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$user_id = $this->session->userdata('user_id');
			$cart_items = $this->products_model->get_cart_items($type = 'product_variation', $user_id, $vendor_id = NULL);
			// echo '<pre>'; print_r($cart_items); echo '</pre>'; die();
			if($_POST)
			{
				$ship_by_date = $this->input->post('ship_by_date');
				$deliver_by_date = $this->input->post('deliver_by_date');
				$your_earning = $this->input->post('your_earning');
				$order_number = $this->input->post('order_number');
				$customer_name = $this->input->post('customer_name');
				$customer_phone = $this->input->post('customer_phone');
				$shipping_address = $this->input->post('shipping_address');
				$country_id = $this->input->post('country_id');
				$shipper_id = $this->input->post('shipper_id');
				$delivery_type = $this->input->post('delivery_type');

				// validate form input
				$this->form_validation->set_rules('ship_by_date','Ship by Date','trim|required');
				$this->form_validation->set_rules('deliver_by_date','Deliver by Date','trim|required');
				$this->form_validation->set_rules('your_earning[]','Earnings','trim|required');
				$this->form_validation->set_rules('order_number[]','Order Number','trim|required');
				$this->form_validation->set_rules('customer_name','Customer Name','trim|required');
				$this->form_validation->set_rules('customer_phone','Customer Phone','trim|required');
				$this->form_validation->set_rules('shipping_address','Shipping Address','trim|required');
				$this->form_validation->set_rules('country_id','Country','trim|required');
				$this->form_validation->set_rules('shipper_id','Shipper','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$timezone  = 'UP5';
					$gmt_time = local_to_gmt(time(), $timezone);
					$local_time = gmt_to_local($gmt_time, $timezone);
					$data = array(
						'object_type ' => 'order',
						'object_content' => '',
						'object_status' => 'pending',
						'object_date' => $local_time,
						'object_date_gmt' => $gmt_time,
						'object_modified' => $local_time,
						'object_modified_gmt' => $gmt_time,
						'object_author' => $user_id,
					);
					$object_id = $this->orders_model->add_object($data);
					$user_account = $this->get_user_account($user_id);
					$order_serial = $this->order_serial($user_id, $user_account['order_prefix'], $timezone);
					$objectmeta_array = array(
						'ship_by_date' => $ship_by_date,
						'deliver_by_date' => $deliver_by_date,
						'customer_name' => $customer_name,
						'customer_phone' => $customer_phone,
						'shipping_address' => $shipping_address,
						'country_id' => $country_id,
						'shipper_id' => $shipper_id,
						'courier_id' => '',
						'tracking_number' => '',
						'order_prefix' => $user_account['order_prefix'],
						'order_serial' => $order_serial,
						'delivery_type' => $delivery_type,
					);
					$objectmeta = $this->build_meta_data($primary_key = 'object_id', $object_id, $objectmeta_array);
					$this->products_model->add_objectmeta($objectmeta);
					if (!empty($cart_items)){

						foreach($cart_items as $i=> $v){
							$v['your_earning'] = $your_earning[$i];
							$v['order_number'] = $order_number[$i];
							$cartitems[] = $v;
						}
						// echo '<pre>'; print_r($cartitems); echo '</pre>'; die();
						foreach($cartitems as $index => $item){
							$stockmanagement = $this->stock_model->update_stock_on_order($item['object_id'], $item['vendor_id'], $item['quantity']);

							$order_items = array(
								'order_status' => 'pending',
								'order_name ' => $item['object_title'],
								'order_type' => 'amazon_order',
								'vendor_id' => $item['vendor_id'],
								'object_id' => $object_id
							);
							$order_id = $this->orders_model->add_order($order_items);
							$ordermeta = array(
								array(
									'order_id' => $order_id,
									'meta_key' => 'item_quantity',
									'meta_value' => $item['quantity'],
								),
								array(
									'order_id' => $order_id,
									'meta_key' => 'vendor_id',
									'meta_value' => $item['vendor_id'],
								),
								array(
									'order_id' => $order_id,
									'meta_key' => 'your_earning',
									'meta_value' => $item['your_earning'],
								),
								array(
									'order_id' => $order_id,
									'meta_key' => 'order_number',
									'meta_value' => $item['order_number'],
								),
							);
							$product = $this->products_model->get_object($item['object_id'], $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);
							if($product['object_type'] == 'product_variation'){
								$additional_ordermeta = array(
									array(
										'order_id' => $order_id,
										'meta_key' => 'product_id',
										'meta_value' => $product['object_parent'],
									),
									array(
										'order_id' => $order_id,
										'meta_key' => 'variation_id',
										'meta_value' => $product['object_id'],
									),
								);
							} else {
								$additional_ordermeta = array(
									array(
										'order_id' => $order_id,
										'meta_key' => 'product_id',
										'meta_value' => $product['object_id'],
									)
								);
							}
							$order_meta = array_merge($ordermeta, $additional_ordermeta);
							$this->orders_model->add_ordermeta($order_meta);
							$args = array(
								'role_id' => $user_id,
								'taxonomy' => 'user_order',
								'parent' => NULL,
								'single' => TRUE,
							);
							$role_taxonomy = $this->users_model->get_role_taxonomy($args);
							$relation_data = array(
								'joint_id' => $order_id,
								'role_taxonomy_id' => $role_taxonomy['role_taxonomy_id'],
							);
							$this->users_model->add_role_relationships($relation_data);
						}
						$this->products_model->delete_cart_item($object_id = NULL, $user_id, $vendor_id = NULL);
					}
					$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					redirect($this->access_type . "/orders", 'refresh');
					//echo '<pre>'; print_r($this->data['cart_items']); echo '</pre>'; die();
				}
			}
			$this->data['item_name'] = array(
				'name' => 'item_name',
				'id' => 'item_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('item_name'),
				'class' => 'form-control',
				'placeholder' => 'Enter product name here',
			);
			$this->data['item_quantity'] = array(
				'name' => 'item_quantity',
				'id' => 'item_quantity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('item_quantity'),
				'class' => 'form-control',
				'placeholder' => 'Enter product quantity here',
			);
			$this->data['ship_by_date'] = array(
				'name' => 'ship_by_date',
				'id' => 'ship_by',
				'type' => 'text',
				'value' => $this->form_validation->set_value('ship_by_date'),
				'class' => 'form-control',
				'placeholder' => 'Month, DD YYYY',
			);
			$this->data['deliver_by_date'] = array(
				'name' => 'deliver_by_date',
				'id' => 'deliver_by',
				'type' => 'text',
				'value' => $this->form_validation->set_value('deliver_by_date'),
				'class' => 'form-control',
				'placeholder' => 'Month, DD YYYY',
			);
			$this->data['your_earning'] = array(
				'name' => 'your_earning[]',
				'id' => 'your_earning',
				'type' => 'text',
				'value' => $this->form_validation->set_value('your_earning'),
				'class' => 'form-control',
				'placeholder' => 'Enter your earning here',
			);
			$this->data['order_number'] = array(
				'name' => 'order_number[]',
				'id' => 'order_number',
				'type' => 'text',
				'value' => $this->form_validation->set_value('order_number'),
				'class' => 'form-control',
				'placeholder' => 'Enter marketplace order number here',
			);
			$this->data['customer_name'] = array(
				'name' => 'customer_name',
				'id' => 'customer_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('customer_name'),
				'class' => 'form-control',
				'placeholder' => 'Enter customer name here',
			);
			$this->data['customer_phone'] = array(
				'name' => 'customer_phone',
				'id' => 'customer_phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('customer_phone'),
				'class' => 'form-control',
				'placeholder' => 'Enter customer phone here',
			);
			$this->data['shipping_address'] = array(
				'name' => 'shipping_address',
				'id' => 'shipping_address',
				'type' => 'text',
				'value' => $this->form_validation->set_value('shipping_address'),
				'class' => 'form-control',
				'placeholder' => 'Enter shipping address here',
			);
			$this->data['cart_items'] = $cart_items;
			$this->data['countries'] = $this->locations_model->get_all_countries();
			$this->data['currencies'] = $this->currencies_model->get_currencies();
			$this->data['vendors'] = $this->ion_auth->users(4)->result();
			$this->data['shippers'] = $this->ion_auth->users(5)->result();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data['cart_items']); echo '</pre>'; die();
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'place_order', $this->data);
		}
	}
	public function delete_order($object_id)
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{			
			if(!empty($object_id))
			{
				$this_order = $this->orders_model->get_order('order', $object_id);
				$ordermeta = $this->orders_model->get_ordermeta($this_order['order_id']);
				if(!empty($ordermeta))
				{
					foreach ($ordermeta as $key => $value)
					{
						$order_meta_id = $value['order_meta_id'];
						$this->orders_model->delete_ordermeta($order_meta_id);
						//echo '<pre>'; print_r($order_meta_id); echo '</pre>'; die();
					}
				}
				$this->orders_model->delete_object($object_id);
				$this->orders_model->delete_order($object_id);
				redirect($this->access_type . "/orders/view_orders", 'refresh');	
			}
		}
	}

	public function order_detail($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
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
			$notes = $this->orders_model->get_notes($note_id = NULL, $note_order_id = $object_id, $note_author = NULL);
			// echo '<pre>'; print_r($notes); echo '</pre>'; exit();
			$object = $this->orders_model->get_order('order', $object_id);
			//echo '<pre>'; print_r($object); echo '</pre>';
			$order = $this->get_order($object);
			//echo '<pre>'; print_r($order); echo '</pre>'; die();
			
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
			$this->data['order'] = $order;
			$this->data['notes'] = $notes;
			//echo '<pre>'; print_r($this->data['order']); echo '</pre>'; die();
			
			$this->data['class'] = $this->session->flashdata('class');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'order_detail', $this->data);
		}
	}
	public function ajax_note($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			// echo '<pre>'; print_r($this->session->userdata()); echo '</pre>';
			$user_id = $this->session->userdata('user_id');
			$username = $this->session->userdata('username');
			
			$content = $this->input->post('note_comment');
			$timezone  = 'UP5';
			$gmt_time = local_to_gmt(strtotime(date('Y-m-d H:i:s')), $timezone);
			$local_time = gmt_to_local($gmt_time, $timezone);
			$data = array(
				'note_order_id' => $object_id,
				'note_author' => $username,
				'note_date' => $local_time,
				'note_date_gmt' => $gmt_time,
				'note_content' => $content,
				'note_approved' => 'approved',
				'user_id' => $user_id,
			);
			if($this->orders_model->insert_note($data))
			{
				echo 'yes';
			}else{
				echo 'no';
			}
		}
	}
	function ajax_datatable_pagination(){
		$user_id = $this->session->userdata('user_id');
		$postData = $this->input->post();
		$data = $this->orders_model->get_datatable_orders($postData, $type = 'order', $author = $user_id, $vendor_id = NULL, $postData['from_date'], $postData['to_date']);
		//echo '<pre>'; print_r($data); echo '</pre>'; die();
		echo json_encode($data);
	}
	private function get_order($value) {
		if(!empty($value)){
			$order_items = $this->orders_model->get_order_items($order_type = 'amazon_order', NULL, $value['object_id']);
			$objectmeta = $this->get_order_meta_data($value['object_id']);
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
			if(!empty($order_details)){
			    return $order_details;
			}
		}
	}
	private function build_meta_data($primary_key, $id, $metadata_array) {
		foreach ($metadata_array as $k => $v) {
			$array[$primary_key] = $id;
			$array['meta_key'] = $k;
			$array['meta_value'] = $v;
			$array_result[] = $array;
		}
		return $array_result;
	}
	private function get_order_meta_data($order_id) {
		$order_meta_data = $this->orders_model->get_objectmeta_single($order_id);
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
			if(isset($order_meta_data['delivery_type'])){
				$order_meta_array['delivery_type'] = $order_meta_data['delivery_type'];
			}
		}
		return $order_meta_array;
	}
	private function get_user_account($id) {
		$member = $this->users_model->get_user_by_id($id);
		$member_metadata = $this->users_model->get_user_metadata($id);
		if(!empty($member_metadata)){
			foreach ($member_metadata as $key => $item) {
				$flight_array = array();
				$itemArr[$item['meta_key']] = $item['meta_value'];
				if($item['meta_key'] == 'country_id')
				{
					$country = $this->locations_model->get_country_by_id($item['meta_value']);
					$itemArr['country_name'] = $country->country_name;
					$itemArr['continent_name'] = $country->continent_name;
				}
				if($item['meta_key'] == 'currency_id')
				{
					$currency = $this->currencies_model->get_currency_by_id($item['meta_value']);
					$itemArr['currency_name'] = $currency['currency_name'];
					$itemArr['currency_code'] = $currency['currency_code'];
					$itemArr['currency_symbol'] = $currency['currency_symbol'];
					//echo '<pre>'; print_r($currency); echo '</pre>'; die();
				}
				if($item['meta_key'] == 'marketplace_id')
				{
					$currency = $this->marketplaces_model->get_marketplace_by_id($item['meta_value']);
					$itemArr['marketplace_name'] = isset($currency['marketplace_name']);
					//echo '<pre>'; print_r($currency); echo '</pre>'; die();
				}
				$member_array = $itemArr;
			}
			
			$user_account = array_merge($member, $member_array);
			return $user_account;
		}
	}
	private function get_orders($orders){
		if(!empty($orders)){
			foreach ($orders as $key => $value) {
				$ordermeta = $this->orders_model->get_ordermeta($value['order_id']);
				foreach ($ordermeta as $key => $item) {
					$product_array = array();
					$itemArr[$item['meta_key']] = $item['meta_value'];
					if($item['meta_key'] == 'country_id')
					{
						$country = $this->locations_model->get_country_by_id($item['meta_value']);
						$itemArr['country_id'] = $country->country_name;
					}
					if($item['meta_key'] == 'shipper_id')
					{
						$shipper = $this->ion_auth->user($item['meta_value'])->row();
						if(!empty($shipper->company))
						{
							$itemArr['shipper_id'] = $shipper->company;
						}
					}
					$product_array = $itemArr;
				}
				$orders_list[] = array_merge($value, $product_array);
			}
			return $orders_list;
		}
	}
	
	private function order_serial($role_id, $order_prefix, $timezone)
	{
		$orders = $this->users_model->get_role_joint($role_id, $taxonomy = 'user_order', $table = 'orders', $column = 'order_id', $is_object = TRUE);
		$orders_list = $this->get_orders($orders);
		$nm_gmt_time = local_to_gmt(strtotime('+ 1 month'), $timezone);
		$tm_gmt_time = local_to_gmt(strtotime('this month'), $timezone);
		$nextmonth = date('F', $nm_gmt_time);
		$thismonth = date('F', $tm_gmt_time);
		$lastday = date(strtotime("1 .$nextmonth. 2020"));
		$firstday = date(strtotime("1 .$thismonth. 2020"));
		$current_time = local_to_gmt(time(), $timezone);
		if(!empty($orders_list)) {
			foreach($orders_list as $order) {
				if($order['object_date_gmt'] > $firstday && $order['object_date_gmt'] < $lastday){
					$serials[] = $order['order_serial'];
				}
			}
			if(!empty($serials)){
				$max_serial_number = max($serials);
				$order_serial = $max_serial_number + 1;
				return $order_serial;
			} else {
				$order_serial = 1;
				return $order_serial;
			}
		} else {
			$order_serial = 1;
			return $order_serial;
		}
	}
}
