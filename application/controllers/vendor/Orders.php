<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Orders extends Vendor_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('orders_model');
		$this->load->model('products_model');
		$this->load->model('currencies_model');
		$this->load->model('locations_model');
		$this->load->model('couriers_model');
		$this->lang->load('auth');
		$this->access_type = 'vendor';
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$vendor_id = $this->session->userdata('user_id');
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
			$orders = $this->orders_model->get_orders_by_date_range($type = 'order', $author = NULL, $from_date, $to_date);
			if(!empty($orders)){
				foreach ($orders as $key => $value) {
					$order = $this->get_order($value);
					if($vendor_id == isset($order['vendor_id'])){
						$orders_list[] = $order;
					}
				}
				if(!empty($orders_list)){
					$this->data['orders'] = $orders_list;
				}
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
			$this->_render_page('vendor' . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function order_detail($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('user/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$vendor_id = $this->session->userdata('user_id');
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
					redirect("user/orders/order_detail/".$object_id, 'refresh');
				}
			}
			$notes = $this->orders_model->get_notes($note_id = NULL, $note_order_id = $object_id, $note_author = NULL);
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
			$this->data['order'] = $order;
			$this->data['notes'] = $notes;
			//echo '<pre>'; print_r($this->data['order']); echo '</pre>'; die();
			
			$this->data['class'] = $this->session->flashdata('class');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('vendor' . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'order_detail', $this->data);
		}
	}
	public function ajax_note($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
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
			// echo '<pre>'; print_r(strtotime(date('Y-m-d H:i:s'))); echo '</pre>';
			
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
		$vendor_id = $this->session->userdata('user_id');
		$postData = $this->input->post();
		$data = $this->orders_model->get_datatable_orders($postData, $type = 'order', $author = NULL, $vendor_id, $postData['from_date'], $postData['to_date']);
		//echo '<pre>'; print_r($data); echo '</pre>'; die();
		echo json_encode($data);
	}

	private function get_order($value) {
		if(!empty($value)){
			$vendor_id = $this->session->userdata('user_id');
			$order_items = $this->orders_model->get_order_items($order_type = 'amazon_order', $vendor_id, $value['object_id']);
			if(!empty($order_items))
			{
				$objectmeta = $this->get_order_mera_data($value['object_id']);
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
								$productmeta = $this->products_model->get_objectmeta_single($item['meta_value']);
								if(!empty($productmeta)){
									foreach ($productmeta as $k => $v) {
										$orderArr[$k] = $v;
									}
									if (array_key_exists("thumbnail_id", $productmeta)){
										$productimage = $this->products_model->get_product_image_by_id($productmeta['thumbnail_id']);
										$orderArr['product_image'] = $productimage->thumb;
									} else {
										$orderArr['product_image'] = '';
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
					//echo '<pre>'; print_r($order_details); echo '</pre>'; die();
					return $order_details;
				}
			}
		}
	}

	private function get_order_mera_data($order_id) {
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
		if(!empty($order_meta_array)){
			return $order_meta_array;
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
}
