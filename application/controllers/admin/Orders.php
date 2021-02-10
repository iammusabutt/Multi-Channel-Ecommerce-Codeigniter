<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Orders extends Backend_Controller
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
		$this->load->model('products_model');
		$this->lang->load('auth');
		$this->access_type = 'admin';
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
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
				$from_date = strtotime('this month');
				$to_date = strtotime('+ 1 month');
				$set_range = '';
			}
			$orders = $this->orders_model->get_orders_by_date_range($type = 'order', $author = NULL, $from_date, $to_date);
			if(!empty($orders)){
				$this->data['orders'] = $this->get_orders($orders);
			}
			$this->data['date_range'] = array(
				'name' => 'date_range',
				'id' => 'datepicker_opening',
				'type' => 'hidden',
				'value' => $this->form_validation->set_value('date_range', $set_range),
				'class' => 'form-control',
				'placeholder' => 'Custom -  From (Month DD, YYYY) to (Month DD, YYYY)',
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
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
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
					redirect($this->access_type . "/orders/order_detail/" . $object_id, 'refresh');
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
			$this->data['order'] = $order;
			//echo '<pre>'; print_r($this->data['order']); echo '</pre>'; die();
			
			$this->data['class'] = $this->session->flashdata('class');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . 'order_detail', $this->data);
		}
	}
	function ajax_datatable_pagination(){
		$member_id = $this->session->userdata('user_id');
		$postData = $this->input->post();
		$data = $this->orders_model->get_datatable_orders($postData, $type = 'order', $author = NULL, $postData['from_date'], $postData['to_date']);
		//echo '<pre>'; print_r($data); echo '</pre>'; die();
		echo json_encode($data);
	}
	public function get_order($value) {
		if(!empty($value)){
			$order_meta = $this->orders_model->get_ordermeta($value['order_id']);
			//echo '<pre>'; print_r($order_meta); echo '</pre>'; die();
			$datestring = 'M, d Y';		
			$orderArr['object_id'] = $value['object_id'];
			$orderArr['object_type'] = $value['object_type'];
			$orderArr['object_parent'] = $value['object_parent'];
			$orderArr['object_status'] = $value['object_status'];
			$orderArr['object_title'] = $value['object_title'];
			$orderArr['object_author'] = $value['object_author'];
			$orderArr['object_content'] = $value['object_content'];
			$orderArr['object_excerpt'] = $value['object_excerpt'];
			$orderArr['object_slug'] = $value['object_slug'];
			$orderArr['comment_status'] = $value['comment_status'];
			$orderArr['comment_count'] = $value['comment_count'];
			$orderArr['object_date'] = date($datestring, $value['object_date']);
			$orderArr['object_date_gmt'] = date($datestring, $value['object_date_gmt']);
			$orderArr['object_modified'] = date($datestring, $value['object_modified']);
			$orderArr['object_modified_gmt'] = date($datestring, $value['object_modified_gmt']);
			$orderArr['menu_order'] = $value['menu_order'];
			$orderArr['order_id'] = $value['order_id'];
			$orderArr['order_type'] = $value['order_type'];
			if(!empty($order_meta)){
				foreach ($order_meta as $key => $item) {
					$orderArr[$item['meta_key']] = $item['meta_value'];
					if($item['meta_key'] == 'country_id')
					{
						$country = $this->locations_model->get_country_by_id($item['meta_value']);
						$orderArr['country_id'] = $country->country_id;
						$orderArr['country_name'] = $country->country_name;
					}
					if($item['meta_key'] == 'vendor_id')
					{
						$vendor = $this->ion_auth->user($item['meta_value'])->row();
						if(!empty($vendor->company))
						{
							$orderArr['vendor_id'] = $vendor->id;
							$orderArr['vendor_name'] = $vendor->company;
						}
					}
					if($item['meta_key'] == 'shipper_id')
					{
						$shipper = $this->ion_auth->user($item['meta_value'])->row();
						if(!empty($shipper->company))
						{
							$orderArr['shipper_id'] = $shipper->id;
							$orderArr['shipper_name'] = $shipper->company;
						}
					}
					if($item['meta_key'] == 'ship_by_date')
					{
						$orderArr['ship_by_date'] = date($datestring, $item['meta_value']);
					}
					if($item['meta_key'] == 'deliver_by_date')
					{
						$orderArr['deliver_by_date'] = date($datestring, $item['meta_value']);
					}
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
					$orderArray = $orderArr;
				}
			}
			// echo '<pre>'; print_r($orderArray); echo '</pre>'; die();
			
			if(!empty($orderArray)){
			    return $orderArray;
			}
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
