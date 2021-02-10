<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Products extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));
        $config =  array(
            'encrypt_name'    => TRUE,
			'upload_path'     => "./uploads/images/products/original/",
			'allowed_types'   => "gif|jpg|png|jpeg",
			'overwrite'       => FALSE,
			'max_size'        => "2000",
			'max_height'      => "2024",
			'max_width'       => "2024"
        );
        $this->load->library('upload', $config);
        $this->load->library('image_lib');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('orders_model');
		$this->load->model('currencies_model');
		$this->load->model('locations_model');
		$this->load->model('products_model');
		$this->load->model('warehouses_model');
		$this->load->model('vendors_model');
		$this->load->model('shippers_model');
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
			$post_type = $this->input->get('post_type', TRUE);
			$object_id = $this->input->get('product_id', TRUE);
			$variation_id = $this->input->get('variation_id', TRUE);
			$vendor_id = $this->input->get('vendor_id', TRUE);
			$action = $this->input->get('action', TRUE);
			switch ($action) {
				case "":
					if(isset($post_type) && !empty($post_type) && $post_type == 'product'){
						$this->product_list();
					} else {
						$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
						$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
					}
					break;
				case "detail":
					if(isset($post_type) && !empty($post_type) && $post_type == 'product' && isset($object_id) && !empty($object_id) && isset($vendor_id) && !empty($vendor_id)){
						$this->product_detail($object_id, $vendor_id);
					} else {
						$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
						$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
					}
					break;
				case "vendors":
					if(isset($post_type) && !empty($post_type) && $post_type == 'product' && isset($object_id) && !empty($object_id)){
						$this->product_vendors($object_id);
					} else {
						$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
						$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
					}
					break;
				default:
					$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
					$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	private function product_list()
	{	
		$products = $this->get_products($type = 'product', $parent = NULL, $author = NULL, $status = 'publish');
		//echo '<pre>';print_r($products);echo '</pre>';die();
		if(!empty($products)){
			$this->data['products'] = $products;
		}
		$this->data['title'] = lang('title_products');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'index', $this->data);
	}

	private function product_detail($object_id, $vendor_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$product_gallery = $this->products_model->get_product_images($object_id);
			$additional = array(
				'vendor_id' => $vendor_id,
				'is_detail' => 'yes',
			);
			$product_details = $this->get_product($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL, $additional);
			//echo '<pre>';print_r($product_details); echo '</pre>'; die();
			
			if(!empty($product_details)){
				$objectmeta = $this->products_model->get_objectmeta_single($object_id);
				$product_attributes = unserialize(!empty($objectmeta['product_attributes']) ? $objectmeta['product_attributes'] : '');
				$attribute_terms = $this->get_attribute_with_terms($object_id, $product_attributes);
				if(!empty($product_details['variations'])){
					foreach($product_details['variations'] as $variations){
						$key = 'stock_quantity';
						$total_stock = array_sum(array_column($variations['warehouses_stock'], $key));
						if(!empty($variations['variable_regular_price']) && !empty($variations['warehouses_stock'])){
							if($variations['manage_stock'] == 'no'){
								$attributes[] = $variations['attributes'];
							}
							elseif($variations['manage_stock'] == 'yes' && $total_stock > 0)
							{
								$attributes[] = $variations['attributes'];
							}
						}
					}
					if(!empty($attributes))
					{
						//echo '<pre>';print_r($product_details['variations']); echo '</pre>'; die();
						foreach($product_details['variations'] as $var_key => $var_val){
							$attributes_options['attributes'] = $var_val['attributes'];
							$attributes_options['variation_id'] = $var_val['object_id'];
							$attributes_options['variable_regular_price'] = $var_val['variable_regular_price'];
							$attributes_result[] = $attributes_options;
						}
						foreach($attribute_terms as $key => $value){
							foreach($value as $attr){
								$result_attribute = $this->in_array_r($attr, $attributes);
								if(!empty($result_attribute)){
									$avail_attr_terms[$key][] = $result_attribute;
								}
							}
						}
					}
				}
				//echo '<pre>';print_r($this->data['attributes_json']); echo '</pre>'; die();
				//echo '<pre>';print_r($attributes_result); echo '</pre>'; die();
				$this->data['product'] = $product_details;
				$this->data['product_gallery'] = $product_gallery;
				if(!empty($attributes_result)){
					$this->data['attributes_json'] = json_encode($attributes_result);
				}
				if(!empty($avail_attr_terms)){
					$this->data['attribute_terms'] = $avail_attr_terms;
					//echo '<pre>';print_r($avail_attr_terms); echo '</pre>'; die();
				}
				$this->data['title'] = lang('title_products');
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'product_details', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	private function product_vendors($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$assigned_vendors = $this->vendors_model->get_vendors_assigned_to_product($object_id);
			//echo '<pre>';print_r($assigned_vendors); echo '</pre>'; die();
			if(!empty($assigned_vendors)){
				$this->data['vendors'] = $assigned_vendors;
				$this->data['title'] = lang('title_products');
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'product_vendors', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function get_products($type, $parent, $author, $status) {
		$products = $this->products_model->get_objects($type, $parent, $author, $status);
		if(!empty($products)){
			foreach ($products as $key => $value) {
				$assigned_vendors = $this->vendors_model->get_vendors_assigned_to_product($value['object_id']);
				$datestring = 'M, d Y';		
				$changeArr['object_id'] = $value['object_id'];
				$changeArr['object_type'] = $value['object_type'];
				$changeArr['object_parent'] = $value['object_parent'];
				$changeArr['object_status'] = $value['object_status'];
				$changeArr['object_title'] = $value['object_title'];
				$changeArr['object_author'] = $value['object_author'];
				$changeArr['object_content'] = $value['object_content'];
				$changeArr['object_excerpt'] = $value['object_excerpt'];
				$changeArr['object_slug'] = $value['object_slug'];
				$changeArr['comment_status'] = $value['comment_status'];
				$changeArr['comment_count'] = $value['comment_count'];
				$changeArr['object_date'] = date($datestring, $value['object_date']);
				$changeArr['object_date_gmt'] = date($datestring, $value['object_date_gmt']);
				$changeArr['object_modified'] = date($datestring, $value['object_modified']);
				$changeArr['object_modified_gmt'] = date($datestring, $value['object_modified_gmt']);
				$changeArr['menu_order'] = $value['menu_order'];
				$changeArr['assigned_vendors'] = $assigned_vendors;
				$products_array[] = $changeArr;
			}
			foreach ($products_array as $key => $value) {
				$productmeta = $this->products_model->get_objectmeta($value['object_id']);
				if(!empty($productmeta)){
					$itemArr = array();
					foreach ($productmeta as $key => $item) {
						$itemArr[$item['meta_key']] = $item['meta_value'];
							if($item['meta_key'] == 'thumbnail_id') {
								$productimage = $this->products_model->get_product_image_by_id($item['meta_value']);
								$itemArr['product_image'] = $productimage->thumb;
							}
						$product_array = $itemArr;
					}
					$products_list[] = array_merge($value, $product_array);
				}
			}
			//echo '<pre>';print_r($products_list);echo '</pre>'; die();
			if(!empty($products_list)){
			    return $products_list;
			}
		}
	}
	public function get_product($object_id, $type, $parent, $author, $slug, $status, $additional) {
		$product_type = $this->products_model->check_product_type($object_id);
		$product = $this->products_model->get_object($object_id, $type, $parent, $author, $slug, $status);
		if(!empty($product)){
			$assigned_vendors = $this->vendors_model->get_vendors_assigned_to_product($object_id);
			//echo '<pre>';print_r($assigned_vendors);echo '</pre>'; die();
			$productmeta = $this->products_model->get_objectmeta($object_id);
			$product_variations = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
			$datestring = 'M, d Y';		
			$changeArr['object_id'] = $product['object_id'];
			$changeArr['object_type'] = $product['object_type'];
			if(!empty($product_type))
			{
				$changeArr['product_type'] = $product_type['product_type'];
			}
			$changeArr['object_parent'] = $product['object_parent'];
			$changeArr['object_status'] = $product['object_status'];
			$changeArr['object_title'] = $product['object_title'];
			$changeArr['object_author'] = $product['object_author'];
			$changeArr['object_content'] = $product['object_content'];
			$changeArr['object_excerpt'] = $product['object_excerpt'];
			$changeArr['object_slug'] = $product['object_slug'];
			$changeArr['comment_status'] = $product['comment_status'];
			$changeArr['comment_count'] = $product['comment_count'];
			$changeArr['object_date'] = date($datestring, $product['object_date']);
			$changeArr['object_date_gmt'] = date($datestring, $product['object_date_gmt']);
			$changeArr['object_modified'] = date($datestring, $product['object_modified']);
			$changeArr['object_modified_gmt'] = date($datestring, $product['object_modified_gmt']);
			$changeArr['menu_order'] = $product['menu_order'];
			if(!empty($product_image->thumb)) {
				$changeArr['product_image'] = $product_image->thumb;
			}
			if(!empty($productmeta)){
				//$this->product_feature_image($productmeta, $meta_key);
				foreach ($productmeta as $key => $item) {
					$product_array = array();
					$changeArr[$item['meta_key']] = $item['meta_value'];
					if($item['meta_key'] == 'thumbnail_id') {
						$productimage = $this->products_model->get_product_image_by_id($item['meta_value']);
						$changeArr['product_image'] = $productimage->thumb;
					}
					if($item['meta_key'] == 'product_attributes') {
						$product_attributes = unserialize(!empty($item['meta_value']) ? $item['meta_value'] : '');
					}
				}
				//echo '<pre>'; print_r($product_attributes); echo '</pre>'; die();
			}
			$changeArr['assigned_vendors'] = $assigned_vendors;
			if($product_type = 'variable') {
				if(!empty($product_variations)){
					foreach ($product_variations as $k => $v) {
						$stock = $this->warehouses_model->get_warehouses_with_stock($type =NULL, $warehouse_id = NULL, $v['object_id'], $additional['vendor_id'], $status = NULL);
						$key = 'stock_quantity';
						$sum = array_sum(array_column($stock, $key));
						//echo '<pre>'; print_r($product_attributes); echo '</pre>'; die();
						$vArr['object_id'] = $v['object_id'];
						$vArr['variable_title'] = $v['object_title'];
						$vArr['variable_regular_price'] = $v['variable_regular_price'];
						$vArr['manage_stock']= $v['manage_stock'];
						$vArr['warehouses_stock'] = $stock;
						$vArr['total_stock'] = $sum;
						$vArr['attributes'] = array();
						foreach ($product_attributes as $pk => $pv) {
							if(isset($v['attribute_'.$pv['name']])) {
								if($additional['is_detail'] == 'no') {
									$vArr['attributes'][] = $v['attribute_'.$pv['name']];
								} else {
									$vArr['attributes'][$pk] = $v['attribute_'.$pv['name']];
								}
							}
						}
						$variation_array[] = $vArr;
					}
					$changeArr['variations'] = $variation_array;
				}
			}
			//echo '<pre>';print_r($changeArr);echo '</pre>'; die();
			if(!empty($changeArr)){
			    return $changeArr;
			}
		}
	}
	private function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict)!==FALSE)) {
				//echo '<pre>';print_r($needle); echo '</pre>';
				return $needle;
			}
		}
		return false;
	}
	
	public function get_attribute_with_terms($object_id, $attributes)
	{
		if(!empty($attributes)){
			$available_attributes=[];
			foreach($attributes as $obj)
			{
				$available_attributes[] = $this->products_model->get_attribute_by_slug($obj['name']);
			};
			if(!empty($available_attributes)){
				$new_attributes = [];
				foreach($available_attributes as $attribute)
				{
					$attribute_name = $attribute['attribute_name'];
					$attribute_slug = $attribute['attribute_slug'];
					$attribute_terms = $this->products_model->get_terms_by_taxonomy($attribute_slug);
					$secondattr = [];
					foreach($attribute_terms as $term)
					{
						$attribute_term = $this->products_model->get_descriptive_term_relation($object_id, $term['term_taxonomy_id']);
						if(!empty($attribute_term)){
							$secondattr[] = !empty($attribute_term['name']) ? $attribute_term['name'] : NULL;
						}
					}
					$new_attributes[$attribute_slug] = $secondattr;
				}
				return $new_attributes;
			}
		}
	}
	public function ajax_fetch_products()
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
			$output = '';
			$query = '';
			if($this->input->post('product_search'))
			{
				$query = $this->input->post('product_search');
			}
			$taxonomy = 'variable';
			//echo '<pre>'; print_r($taxonomy); echo '</pre>'; die();
			$response = $this->products_model->fetch_data($query);
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
		}
	}
	public function ajax_fetch_product_vendors()
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
				if(!empty($object_id) && isset($object_id))
				{
					$this->form_validation->set_rules('object_id','Vendor','numeric');
					if ($this->form_validation->run() === TRUE)
					{
						$product = $this->vendors_model->check_product_type($object_id);
						if($product['product_variation'] = 'product_variation'){
							$assigned_vendors = $this->vendors_model->get_vendors_assigned_to_product($product['object_parent']);
							//echo '<pre>'; print_r($assigned_vendors); echo '</pre>'; die();
							$this->data['assigned_vendors'] = $assigned_vendors;

						}
					}
					$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_product_vendors', $this->data, true);
					$response = array('response'=>'yes','message'=>'Product variation added successfully','content'=>$theHTMLResponse);
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
				else
				{
					$response = array('response'=>'no','message'=>'Unauthorized Action');
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}
	public function ajax_add_to_cart()
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
			if($_POST)
			{
				$user_id = $this->session->userdata('user_id');
				$product_id = $this->input->post('product_id');
				$variation_id = $this->input->post('variation_id');
				$vendor_id = $this->input->post('vendor_id');
				
				$this->form_validation->set_rules('product_id','Product ID','trim|required');
				$this->form_validation->set_rules('variation_id','Variation ID','trim|required');
				$this->form_validation->set_rules('vendor_id','Vendor ID','trim|required');

				$stock = $this->products_model->get_stocks($warehouse_id = NULL, $variation_id, $vendor_id);
				$sum = $this->sum_by_key_in_array($stock, 'stock_quantity');
				$stock_array['total_stock'] = $sum;
				$stock_array['stock_by_warehouse'] = $stock;
				$cart_items = $this->products_model->get_cart_items($type = 'product_variation', $user_id, $vendor_id);
				if(!empty($cart_items)) {
					$available_in_cart = current(array_filter($cart_items, function($item) use($variation_id) {
						return isset($item['object_id']) && $variation_id == $item['object_id'];
					}));
					if(!empty($available_in_cart)) {
						$found['quantity'] = $available_in_cart['quantity'];
					} else {
						$found['quantity'] = 0;
					}
				} else {
					$found['quantity'] = 0;
				}
				if($stock_array['total_stock'] > 0 && $found['quantity'] < $stock_array['total_stock'])
				{
					if ($this->form_validation->run() === TRUE)
					{
						if($found['quantity'] != 0) {
							$qty = $found['quantity'] + 1;
							$data = array(
								'quantity' => $qty,
							);
							$this->products_model->update_cart($variation_id, $user_id, $vendor_id, $data);
						}
						else
						{
							$data = array(
								'object_id' => $variation_id,
								'user_id' => $user_id,
								'vendor_id' => $vendor_id,
								'quantity' => 1
							);
							$last_id = $this->products_model->add_to_cart($data);
						}
						$cart_items = $this->products_model->get_cart_items($type = 'product_variation', $user_id, $vendor_id = NULL);
						if($cart_items)
						{
							$this->data['cart_items'] = $cart_items;
							$theHTMLResponse = $this->load->view('user/ajax_blocks/block_cart_item', $this->data, true);
							$response = array(
								'response'	=> 'yes',
								'message'	=> 'Product item added to cart successfully',
								'content'	=> $theHTMLResponse
							);
						}
					}
				} else if($found['quantity'] > $stock_array['total_stock']) {
					$response = array('response'=>'no','message'=>'Not enough stock');
				} else {
					$response = array('response'=>'no','message'=>'You cannot add to cart more than available stock.');
				}
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($response));
			}
		}
	}
	public function ajax_delete_cart_item()
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
					$cart_items = $this->products_model->get_cart_items($type = 'product_variation', $user_id, $vendor_id);
					if(!empty($cart_items)) {
						$available_in_cart = current(array_filter($cart_items, function($item) use($object_id) {
							return isset($item['object_id']) && $object_id == $item['object_id'];
						}));
						if(!empty($available_in_cart)) {
							$this->products_model->delete_cart_item($object_id, $user_id = NULL, $vendor_id);
						}
					}
					$cart_items = $this->products_model->get_cart_items($type = 'product_variation', $user_id, $vendor_id = NULL);
					if($cart_items)
					{
						$this->data['cart_items'] = $cart_items;
						$theHTMLResponse = $this->load->view('user/ajax_blocks/block_cart_item', $this->data, true);
						$response = array(
							'response'	=> 'yes',
							'message'	=> 'Product item deleted from cart successfully',
							'content'	=> $theHTMLResponse
						);
					}
					else
					{
						$this->data['cart_items'] = $cart_items;
						$theHTMLResponse = $this->load->view('user/ajax_blocks/block_cart_item', $this->data, true);
						$response = array(
							'response'	=> 'yes',
							'message'	=> 'Your cart is empty now',
							'content'	=> $theHTMLResponse
						);
					}
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}
	private function sum_by_key_in_array($array, $key){
		$total = array_sum(array_column($array, $key));
		return $total;
	}
}
