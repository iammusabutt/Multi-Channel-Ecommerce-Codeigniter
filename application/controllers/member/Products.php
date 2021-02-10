<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Products extends Member_Controller
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
		$this->load->model('vendors_model');
		$this->load->model('shippers_model');
		$this->load->model('users_model');
		$this->lang->load('auth');
		$this->access_type = 'member';
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$products = $this->get_products($type = 'product', $parent = NULL, $author = NULL, $status = 'publish');
			if(!empty($products)){
				$this->data['products'] = $products;
			}
			//echo '<pre>'; print_r($products_list); echo '</pre>'; die();
			$this->data['title'] = lang('title_products');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function details($object_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$product_gallery = $this->products_model->get_product_images($object_id);
			$product_details = $this->get_product($object_id);
			if(!empty($product_details)){
				$this->data['product'] = $product_details;
				$this->data['product_gallery'] = $product_gallery;
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

	
	public function add_product()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$member_id = $this->session->userdata('user_id');
			$post_type = $this->input->get('post_type', TRUE);
			//echo '<pre>'; print_r($post_type); echo '</pre>'; die();
			if(isset($post_type) && $post_type == 'product')
			{
				if(!$_POST)
				{
					$datestring = 'M, d Y h:i A';
					$timezone  = 'UP5';
					$gmt_time = local_to_gmt(time(), $timezone);
					$local_time = gmt_to_local($gmt_time, $timezone);
					$data = array(
						'object_type ' => $post_type,
						'object_status' => 'auto-draft',
						'object_date' => $local_time,
						'object_date_gmt' => $gmt_time,
						'object_modified' => $local_time,
						'object_modified_gmt' => $gmt_time,
					);
					$object_id = $this->products_model->add_object($data);
				}
				if($_POST)
				{
					$object_id = $this->input->post('object_id');
					$featured_image_id = $this->input->post('featured_image_id');
					$term_taxonomy_id = $this->input->post('term_id');
					$product_type = $this->input->post('product_type');
					$product_name = $this->input->post('product_name');
					$product_description = $this->input->post('product_description');
					$product_price = $this->input->post('product_price');
					$product_sku = $this->input->post('product_sku');
					$product_stock = $this->input->post('product_stock');
					$product_length = $this->input->post('product_length');
					$product_width = $this->input->post('product_width');
					$product_height = $this->input->post('product_height');
				
					// validate form input
					$this->form_validation->set_rules('term_id','Category','numeric');
					$this->form_validation->set_rules('product_type','Product Type','trim');
					$this->form_validation->set_rules('product_name','Product Name','trim|required');
					$this->form_validation->set_rules('product_description','Product Description','trim');
					$this->form_validation->set_rules('product_price','Price','trim');
					$this->form_validation->set_rules('product_sku','SKU','trim');
					$this->form_validation->set_rules('product_stock','Stock','trim');
					$this->form_validation->set_rules('product_length','Length','trim');
					$this->form_validation->set_rules('product_width','Width','trim');
					$this->form_validation->set_rules('product_height','Height','trim');
					if ($this->form_validation->run() === TRUE)
					{
						$datestring = 'M, d Y h:i A';
						$timezone  = 'UP5';
						$gmt_time = local_to_gmt(time(), $timezone);
						$local_time = gmt_to_local($gmt_time, $timezone);
						$data = array(
							'object_status' => 'publish',
							'object_title' => $product_name,
							'object_content' => $product_description,
							'object_modified' => $local_time,
							'object_modified_gmt' => $gmt_time,
							'object_author' => $member_id,
						);
						$this->products_model->update_object($object_id, $data);
						$term_by_slug = $this->products_model->get_term_by_slug($product_type);
						$term_relationship = array(
							'term_taxonomy_id' => $term_by_slug['term_taxonomy_id'],
						);
						$this->terms_selection_crud($term_relationship, $object_id, $taxonomy = 'product_type');
						$objectmeta = array(
							array(
								'object_id' => $object_id,
								'meta_key' => 'product_price',
								'meta_value' => $product_price,
							),
							array(
								'object_id' => $object_id,
								'meta_key' => 'product_sku',
								'meta_value' => $product_sku,
							),
							array(
								'object_id' => $object_id,
								'meta_key' => 'product_stock',
								'meta_value' => $product_stock,
							),
							array(
								'object_id' => $object_id,
								'meta_key' => 'product_length',
								'meta_value' => $product_length,
							),
							array(
								'object_id' => $object_id,
								'meta_key' => 'product_width',
								'meta_value' => $product_width,
							),
							array(
								'object_id' => $object_id,
								'meta_key' => 'product_height',
								'meta_value' => $product_height,
							)
						);
						$this->products_model->add_objectmeta($objectmeta);
						$taxonomy_data = array(
							array(
								'role_id' => $object_id,
								'taxonomy' => 'product_vendor',
								'parent' => '0',
							),
						);
						$this->users_model->add_role_taxonomy($taxonomy_data);
						$this->terms_selection_crud($term_taxonomy_id, $object_id, $taxonomy = 'product_category');
						if(!empty($featured_image_id))
						{
							$other_data = array(
								'product_id' => $object_id,
							);
							$this->products_model->update_featured_image_product_id($featured_image_id, $other_data);
						}
						$product_variations = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
						if(!empty($product_variations)){
							foreach ($product_variations as $variation) {
								$varTitle['object_title'] = $product_name.$variation['object_title'];
								$this->products_model->update_object($variation['object_id'], $varTitle);
							}
						}
						$this->session->set_flashdata('class', 'btn btn-success waves-effect waves-light');
						$this->session->set_flashdata('message', 'Product is Added');
						redirect($this->access_type . "/products/edit_product/".$object_id, 'refresh');
					}
				}
				$terms = $this->products_model->get_taxonomy_with_term($taxonomy = 'product_category', $object_id);
				if (!empty($terms))
				{
					$terms_id = array();
					for ($i = 0; $i < count($terms); $i++) {
						$terms_id[] = $terms[$i]['term_taxonomy_id'];
					}
					$new_array = [];
					if(!empty($terms_id)){
						foreach ($terms_id as $value) {
							$taxonomyitems = $this->products_model->get_terms_relations($object_id, $value);
							$tempArr['object_id'] = !empty($taxonomyitems['object_id']) ? $taxonomyitems['object_id'] : NULL;
							$tempArr['term_order'] = !empty($taxonomyitems['term_order']) ? $taxonomyitems['term_order'] : NULL;
							$new_array[] = $tempArr;
						}
					}
					$this->data['terms'] = (object)array_replace_recursive($terms, $new_array);
				}
				$objectmeta = $this->products_model->get_objectmeta_single($object_id);
				$product_attributes = unserialize(!empty($objectmeta['product_attributes']) ? $objectmeta['product_attributes'] : '');
				$new_attributes = $this->get_assigned_attribute_list($object_id, $product_attributes);
				$attribute_terms = $this->get_attribute_with_terms($object_id, $product_attributes);
				$product_variations = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
				$product = $this->products_model->get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);
				$meta = $this->products_model->get_objectmeta_single($object_id);
				$term_relation = $this->products_model->get_terms_relation_by_taxonomy($object_id, $taxonomy = 'product_type');
				//echo '<pre>';print_r($term_relation);echo '</pre>'; die();
				$this->data['product_name'] = array(
					'name' => 'product_name',
					'id' => 'product_name',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_name'),
					'class' => 'form-control',
					'placeholder' => 'Product name',
				);
				$this->data['product_description'] = array(
					'name' => 'product_description',
					'id' => 'product_description',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_description'),
					'class' => 'form-control',
					'class' => 'summernote',
				);
				$this->data['product_price'] = array(
					'name' => 'product_price',
					'id' => 'product_price',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_price'),
					'class' => 'form-control',
					'placeholder' => 'Selling price',
				);
				$this->data['product_sku'] = array(
					'name' => 'product_sku',
					'id' => 'product_sku',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_sku'),
					'class' => 'form-control',
					'placeholder' => 'Product stock keeping unit',
				);
				$this->data['product_stock'] = array(
					'name' => 'product_stock',
					'id' => 'product_stock',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_stock'),
					'class' => 'form-control',
					'placeholder' => 'Stock',
				);
				$this->data['product_length'] = array(
					'name' => 'product_length',
					'id' => 'product_length',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_length'),
					'class' => 'form-control',
					'placeholder' => 'Length',
				);
				$this->data['product_width'] = array(
					'name' => 'product_width',
					'id' => 'product_width',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_width'),
					'class' => 'form-control',
					'placeholder' => 'Width',
				);
				$this->data['product_height'] = array(
					'name' => 'product_height',
					'id' => 'product_height',
					'type' => 'text',
					'value' => $this->form_validation->set_value('product_height'),
					'class' => 'form-control',
					'placeholder' => 'Height',
				);
				// $product_image = $this->products_model->get_product_image_by_product_id($object_id);
				$product_image = $this->products_model->get_product_image_by_id(isset($meta['thumbnail_id']) ? $meta['thumbnail_id'] : NULL);
			    $product_gallery = $this->products_model->get_product_gallery($object_id);

				$this->data['product'] = $product;
				$this->data['meta'] = $meta;
				// $this->data['product_image'] = $product_image;
				$this->data['product_image'] = isset($product_image) ? $product_image : "";
				$this->data['product_gallery'] = $product_gallery;
				$this->data['term_relation'] = $term_relation;
				$this->data['product_attributes'] = $product_attributes;
				$this->data['product_variations'] = $product_variations;
				$this->data['all_attributes'] = $this->products_model->get_attributes();
				$this->data['new_attributes'] = $new_attributes;
				$this->data['attribute_terms'] = $attribute_terms;
				$this->data['title'] = lang('title_products_add');
				$this->data['vendors'] = $this->ion_auth->users(4)->result();
				$this->data['object_id'] = $object_id;
				$this->data['terms'] = $this->products_model->get_terms_by_taxonomy($taxonomy = 'product_category');
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'add_product', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function edit_product($object_id)
	{
		//echo '<pre>'; print_r($this->session->flashdata('message')); echo '</pre>'; die();
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			if($_POST)
			{
				$featured_image_id = $this->input->post('featured_image_id');
				$term_taxonomy_id = $this->input->post('term_id');
				$product_type = $this->input->post('product_type');
				$product_name = $this->input->post('product_name');
				$product_description = $this->input->post('product_description');
				$product_price = $this->input->post('product_price');
				$product_size = $this->input->post('product_size');
				$product_material = $this->input->post('product_material');
				$product_sku = $this->input->post('product_sku');
				$product_stock = $this->input->post('product_stock');
				$product_length = $this->input->post('product_length');
				$product_width = $this->input->post('product_width');
				$product_height = $this->input->post('product_height');

				// validate form input
				$this->form_validation->set_rules('term_id','Category','numeric');
				$this->form_validation->set_rules('product_type','Product Type','trim');
				$this->form_validation->set_rules('product_name','Product Name','trim');
				$this->form_validation->set_rules('product_description','Product Description','trim');
				$this->form_validation->set_rules('product_price','Product Price','trim');
				$this->form_validation->set_rules('product_sku','SKU','trim');
				$this->form_validation->set_rules('product_stock','Stock','trim');
				$this->form_validation->set_rules('product_length','Length','trim');
				$this->form_validation->set_rules('product_width','Width','trim');
				$this->form_validation->set_rules('product_height','Height','trim');
				if ($this->form_validation->run() === TRUE)
				{
					$datestring = 'M, d Y h:i A';
					$timezone  = 'UP5';
					$gmt_time = local_to_gmt(time(), $timezone);
					$local_time = gmt_to_local($gmt_time, $timezone);
					$data = array(
						'object_title' => $product_name,
						'object_content' => $product_description,
						'object_modified' => $local_time,
						'object_modified_gmt' => $gmt_time,
					);
					$this->products_model->update_object($object_id, $data);
					$term_by_slug = $this->products_model->get_term_by_slug($product_type);
					$term_relationship = array(
						'term_taxonomy_id' => $term_by_slug['term_taxonomy_id'],
					);
					$this->terms_selection_crud($term_relationship, $object_id, $taxonomy = 'product_type');
					$productmeta = $this->products_model->get_objectmeta($object_id);
					$objectmeta = array();
					foreach ($productmeta as $key => $product){
						if(!empty($this->input->post($product['meta_key'])))
						{
							$array['meta_key'] = "'".$product['meta_key']."'";
							$array['post_values'] = $this->input->post($product['meta_key']);
							$objectmeta[] = array(
								'ometa_id'  => $product['ometa_id'],
								'meta_key' => $product['meta_key'],
								'meta_value' => $array['post_values'],
							);
						}
					}
					if(!empty($objectmeta))
					{
						$this->products_model->update_objectmeta($object_id, $objectmeta);
					}
					$this->terms_selection_crud($term_taxonomy_id, $object_id, $taxonomy = 'product_category');
					if(!empty($featured_image_id))
					{
						$other_data = array(
							'product_id' => $object_id,
						);
						$this->products_model->update_featured_image_product_id($featured_image_id, $other_data);
					}
					redirect($this->access_type . '/products/edit_product/'.$object_id, 'refresh');
					$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					redirect($this->access_type . "/products", 'refresh');
				}
			}
			$terms = $this->products_model->get_taxonomy_with_term($taxonomy = 'product_category', $object_id);
			if (!empty($terms))
			{
				$terms_id = array();
				for ($i = 0; $i < count($terms); $i++) {
					$terms_id[] = $terms[$i]['term_taxonomy_id'];
				}
				$new_array = [];
				if(!empty($terms_id)){
					foreach ($terms_id as $value) {
						$taxonomyitems = $this->products_model->get_terms_relations($object_id, $value);
						$tempArr['object_id'] = !empty($taxonomyitems['object_id']) ? $taxonomyitems['object_id'] : NULL;
						$tempArr['term_order'] = !empty($taxonomyitems['term_order']) ? $taxonomyitems['term_order'] : NULL;
						$new_array[] = $tempArr;
					}
				}
				$this->data['terms'] = (object)array_replace_recursive($terms, $new_array);
			}
			$objectmeta = $this->products_model->get_objectmeta_single($object_id);
			$product_attributes = unserialize(!empty($objectmeta['product_attributes']) ? $objectmeta['product_attributes'] : '');
			$new_attributes = $this->get_assigned_attribute_list($object_id, $product_attributes);
			$attribute_terms = $this->get_attribute_with_terms($object_id, $product_attributes);
			$product_variations = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
			$product = $this->products_model->get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);
			$meta = $this->products_model->get_objectmeta_single($object_id);
			$term_relation = $this->products_model->get_terms_relation_by_taxonomy($object_id, $taxonomy = 'product_type');
			//echo '<pre>';print_r($this->session->flashdata('message'));echo '</pre>'; die();
			$this->data['product_name'] = array(
				'name' => 'product_name',
				'id' => 'product_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_name', !empty($product['object_title']) ? $product['object_title'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Product name',
			);
			$this->data['product_description'] = array(
				'name' => 'product_description',
				'id' => 'product_description',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_description', !empty($product['object_content']) ? $product['object_content'] : ""),
				'class' => 'form-control',
				'class' => 'summernote',
			);
			$this->data['product_price'] = array(
				'name' => 'product_price',
				'id' => 'product_price',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_price', !empty($meta['product_price']) ? $meta['product_price'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Selling price',
			);
			$this->data['product_sku'] = array(
				'name' => 'product_sku',
				'id' => 'product_sku',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_sku', !empty($meta['product_sku']) ? $meta['product_sku'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Product stock keeping unit',
			);
			$this->data['product_stock'] = array(
				'name' => 'product_stock',
				'id' => 'product_stock',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_stock', !empty($meta['product_stock']) ? $meta['product_stock'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Stock',
			);
			$this->data['product_length'] = array(
				'name' => 'product_length',
				'id' => 'product_length',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_length', !empty($meta['product_length']) ? $meta['product_length'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Length',
			);
			$this->data['product_width'] = array(
				'name' => 'product_width',
				'id' => 'product_width',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_width', !empty($meta['product_width']) ? $meta['product_width'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Width',
			);
			$this->data['product_height'] = array(
				'name' => 'product_height',
				'id' => 'product_height',
				'type' => 'text',
				'value' => $this->form_validation->set_value('product_height', !empty($meta['product_height']) ? $meta['product_height'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Height',
			);
			$product_image = $this->products_model->get_product_image_by_product_id($object_id);
			$product_gallery = $this->products_model->get_product_gallery($object_id);
			$this->data['product'] = $product;
			$this->data['meta'] = $meta;
			$this->data['product_image'] = $product_image;
			$this->data['product_gallery'] = $product_gallery;
			$this->data['term_relation'] = $term_relation;
			$this->data['product_attributes'] = $product_attributes;
			$this->data['product_variations'] = $product_variations;
			$this->data['all_attributes'] = $this->products_model->get_attributes();
			$this->data['new_attributes'] = $new_attributes;
			$this->data['attribute_terms'] = $attribute_terms;
			$this->data['title'] = lang('title_products_add');
			$this->data['vendors'] = $this->ion_auth->users(4)->result();
			$this->data['shippers'] = $this->ion_auth->users(5)->result();
			$this->data['countries'] = $this->locations_model->get_all_countries();
			$this->data['currencies'] = $this->currencies_model->get_currencies();
			$this->data['class'] = $this->session->flashdata('class');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'edit_product', $this->data);
		}
	}
	public function delete_product($object_id)
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{			
			if(!empty($object_id))
			{
				$variations = $this->products_model->get_objects('product_variation', $object_id, NULL, NULL);
				foreach ($variations as $key => $value){
					$this->products_model->delete_object($value['object_id']);
					$this->products_model->delete_objectmeta_by_object_id($value['object_id']);
				}
				$product_image = $this->products_model->get_product_image_by_product_id($object_id);
				if(!empty($product_image))
				{
					$id = $product_image->id;
					$path = $product_image->path;
					$thumb =$product_image->thumb;
					$this->products_model->delete_product_image($id, $path, $thumb);
				}
				$this->products_model->delete_term_relationship_by_object_id($object_id);
				$this->products_model->delete_object($object_id);
				$objects = $this->products_model->get_objectmeta($object_id);
				$deletearray = array();
				foreach ($objects as $key => $value)
				{
					$ometa_id = $value['ometa_id'];
					$this->products_model->delete_objectmeta($ometa_id);
				}
				redirect($this->access_type . '/products', 'refresh');	
			}
		}
	}
	public function product_vendors($object_id)
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			if($_POST)
			{
				//$att = $this->input->post('att');
				$vendor_id = $this->input->post('vendor_id');

				// validate form input
				$this->form_validation->set_rules('vendor_id','Vendor','numeric');
				if ($this->form_validation->run() === TRUE)
				{
					$args = array(
						'role_id' => $object_id,
						'taxonomy' => 'product_vendor',
						'parent' => NULL,
						'single' => TRUE,
					);
					$role_taxonomy = $this->users_model->get_role_taxonomy($args);
					$relation_data = array(
						'joint_id' => $vendor_id,
						'role_taxonomy_id' => $role_taxonomy['role_taxonomy_id'],
					);
					$role_relationship = $this->users_model->get_role_relationship_acol($relation_data);
					if(empty($role_relationship)){
					    $this->users_model->add_role_relationships($relation_data);
					    $this->session->set_flashdata('class', 'btn btn-success waves-effect waves-light');
					    $this->session->set_flashdata('message', 'Vendor has been assigned to this product');
					}
					else
					{
					    $this->session->set_flashdata('class', 'btn btn-danger waves-effect waves-light');
					    $this->session->set_flashdata('message', 'Vendor already assigned to this product');
					}
					redirect($this->access_type . '/products/product_vendors/'. $object_id, 'refresh');
				}
			}
			$assigned_vendors = $this->vendors_model->get_vendors_assigned_to_product($object_id);
			//echo '<pre>';print_r($assigned_vendors);echo '</pre>';die();
			$this->data['title'] = lang('title_products_categories');
			$this->data['assigned_vendors'] = $assigned_vendors;	
			$this->data['vendors'] = $this->ion_auth->users(4)->result();
			//list the users
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['class'] = $this->session->flashdata('class');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'product_vendors', $this->data);
		}
	}
	public function categories()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$this->data['category_name'] = array(
				'name' => 'category_name',
				'id' => 'category_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('category_name'),
				'class' => 'form-control',
				'placeholder' => 'Enter category name',
			);
			$this->data['category_slug'] = array(
				'name' => 'category_slug',
				'id' => 'category_slug',
				'type' => 'text',
				'value' => $this->form_validation->set_value('category_slug'),
				'class' => 'form-control',
				'placeholder' => 'Enter category slug',
			);
			$this->data['title'] = lang('title_products_categories');
			$this->data['categories'] = $this->products_model->get_terms_by_taxonomy($taxonomy = 'product_category');	
			//list the users
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['class'] = $this->session->flashdata('class');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'categories', $this->data);
		}
	}
	public function ajax_add_category()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$json_data = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$category_name = $this->input->post('category_name');
				$category_slug = $this->input->post('category_slug');
				$this->form_validation->set_rules('category_name','Name','trim|required');
				$this->form_validation->set_rules('category_slug','Slug','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'name' => $category_name,
						'slug' => $category_slug,
						'term_group' => 0,
					);
					$last_id = $this->products_model->add_term($data);
					$other_data = array(
						'term_id' => $last_id,
						'taxonomy' => 'product_category',
						'description' => '',
						'parent' => '',
						'count' => '',
					);
					$this->products_model->add_term_taxonomy($other_data);
					$last_category = $this->products_model->get_category_last_id($last_id);
					$categories = $this->products_model->get_terms();
					if($last_category)
					{
						$this->data['categories'] = $categories;
						$this->data['last_category'] = $last_category;
						$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_category', $this->data, true);
						$response = array('response'=>'yes','message'=>'Product categoty added successfully','content'=>$theHTMLResponse);
					}
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}

	public function ajax_delete_category()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$json_data = array('response'=>'no', 'message' =>'Failed to delete this file');
			if ($_POST)
			{
				$term_id = $this->input->post('term_id');
				$term_by_taxonomy_id = $this->products_model->get_term_by_taxonomy_id($term_id);
				//echo '<pre>';print_r($term_by_taxonomy_id);echo '</pre>';die();
				$this->products_model->delete_term($term_by_taxonomy_id->term_id);
				$this->products_model->delete_term_taxonomy($term_id);
				$this->products_model->delete_term_relationships_by_term_taxonomy_id($term_id);
				$categories = $this->products_model->get_terms();
				$this->data['categories'] = $categories;
				$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_category', $this->data, true);
				$json_data = array('response'=>'yes','message'=>'Product category deleted successfully','content'=> $theHTMLResponse);
			}
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($json_data));
		}
	}

	public function ajax_assign_product_attribute()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$json_data = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$object_id = $this->input->post('object_id');
				$attribute_value = $this->input->post('attribute_value');
				$this->form_validation->set_rules('object_id','Object','trim|required');
				$this->form_validation->set_rules('attribute_value','Value','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$objectmeta = $this->products_model->get_objectmeta_by_key($meta_key = 'product_attributes', $object_id);
					if(!empty($objectmeta))
					{
						$product_attributes = unserialize($objectmeta['meta_value']);
						$attribute_array = array('name' => $attribute_value,);
						if (!in_array($attribute_array, $product_attributes)) {
							$dataArr = array();
							$dataArr[$attribute_value] = $attribute_array;
							$array_merge = array_merge_recursive($product_attributes, $dataArr);
							$serialize = serialize($array_merge);
							$new_objectmeta[] = array(
								'ometa_id'  => $objectmeta['ometa_id'],
								'meta_key' => 'product_attributes',
								'meta_value' => $serialize,
							);
							$this->products_model->update_objectmeta($object_id, $new_objectmeta);
							$ometa = $this->products_model->get_objectmeta_by_key($meta_key = 'product_attributes', $object_id);
							$product_objectmeta = unserialize($ometa['meta_value']);
							$this->data['object_id'] = $object_id;
							$this->data['new_attributes'] = $this->get_assigned_attribute_list($object_id, $product_objectmeta);
							//echo '<pre>'; print_r($this->data['new_attributes']); echo '</pre>';die();
							$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_assign_attribute', $this->data, true);
							$response = array('response'=>'yes','message'=>'Product attribute added successfully','content'=>$theHTMLResponse);
						}
						else
						{
							$response = array('response'=>'no','message'=>'You cannot assign similar attribute more than once to a single product. Remove first to re-assign attribute.');
						}
					}
					else
					{
						$dataArr[$attribute_value] = array(
							'name' => $attribute_value,
						);
						$serialize = serialize($dataArr);
						$objectmeta = array(
							array(
								'object_id' => $object_id,
								'meta_key' => 'product_attributes',
								'meta_value' => $serialize,
							),
						);
						$this->products_model->add_objectmeta($objectmeta);
						$ometa = $this->products_model->get_objectmeta_by_key($meta_key = 'product_attributes', $object_id);
						$product_objectmeta = unserialize($ometa['meta_value']);
						$this->data['object_id'] = $object_id;
						$this->data['new_attributes'] = $this->get_assigned_attribute_list($object_id, $product_objectmeta);
						$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_assign_attribute', $this->data, true);
						$response = array('response'=>'yes','message'=>'Product categoty added successfully','content'=>$theHTMLResponse);
					}
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}
	
	public function ajax_unassign_product_attribute()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$json_data = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$object_id = $this->input->post('object_id');
				$attribute_value = $this->input->post('attribute_value');
				$this->form_validation->set_rules('object_id','Object','trim|required');
				$this->form_validation->set_rules('attribute_value','Value','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$objectmeta = $this->products_model->get_objectmeta_by_key($meta_key = 'product_attributes', $object_id);
					$product_attributes = unserialize($objectmeta['meta_value']);
					if(!empty($objectmeta))
					{
						$attribute_array = array('name' => $attribute_value,);
						$terms_array = $this->products_model->get_terms_relations_by_taxonomy($object_id, $attribute_value);	
						foreach ($terms_array as $key => $value) {
							$term_taxonomy_id = $value['term_taxonomy_id'];
							$object_id = $value['object_id'];
							$this->products_model->delete_term_relationships_id($term_taxonomy_id, $object_id);
						}	
						if (in_array($attribute_array, $product_attributes)) {
							$pos = array_search($attribute_array, $product_attributes);
							unset($product_attributes[$pos]);
							$serialize = serialize($product_attributes);
							$new_objectmeta[] = array(
								'ometa_id'  => $objectmeta['ometa_id'],
								'meta_key' => 'product_attributes',
								'meta_value' => $serialize,
							);
							//echo '<pre>'; print_r($new_objectmeta); echo '</pre>'; die();
							$this->products_model->update_objectmeta($object_id, $new_objectmeta);
							$ometa = $this->products_model->get_objectmeta_by_key($meta_key = 'product_attributes', $object_id);
							$product_objectmeta = unserialize($ometa['meta_value']);
							$this->data['object_id'] = $object_id;
							$this->data['new_attributes'] = $this->get_assigned_attribute_list($object_id, $product_objectmeta);
							//echo '<pre>'; print_r($this->data['new_attributes']); echo '</pre>';die();
							$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_assign_attribute', $this->data, true);
							$response = array('response'=>'yes','message'=>'Product attribute deleted successfully','content'=>$theHTMLResponse);
						}
						else
						{
							$response = array('response'=>'no','message'=>'Attribute does not exist.');
						}
					}
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}
	public function ajax_save_product_attribute()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$response = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$object_id = $this->input->post('object_id');
				$attribute_values = $this->input->post('attribute_values');
				
				//Unserializing js Array to php Array
				parse_str($attribute_values, $output);
				$this->form_validation->set_rules('object_id','Object','trim|required');
				$this->form_validation->set_rules('att','Value','trim');
				if ($this->form_validation->run() === TRUE)
				{
					// Output is a three dimentional unserialized js Array
					if (!empty($output))
					{
						//1st dimention loop through array to get indexes of attributes
						foreach($output['attribute_values'] as $attr)
						{
							//2nd dimention loop each attributes index to get attribute values eg: color, material
							foreach($attr as $taxonomy => $values)
							{
								if(!empty($values))
								{
									//3rd dimention loop attributes values to get terms of each  attribute value eg: color[red, blue]
									$term_taxonomy_id = array();
									foreach($values as $value)
									{
										$list = $this->products_model->get_term_by_slug($value);
										$term_taxonomy_id[] = $list['term_taxonomy_id'];
										$taxonomy = $list['taxonomy'];
									}
									// @ $term_taxonomy_id: Array | $object_id: String | $taxonomy: String
									$this->terms_selection_crud($term_taxonomy_id, $object_id, $taxonomy);
								}
								else
								{
									// @ $term_taxonomy_id: Array | $object_id: String | $taxonomy: String
									$this->terms_selection_crud($term_taxonomy_id = '', $object_id, $taxonomy);
								}
							}
						}
					}
					$response = array('response'=>'yes','message'=>'Product attributes updated successfully');
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}
	public function ajax_refresh_product_variation()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$response = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$object_id = $this->input->post('object_id');
				$object = $this->products_model->get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);

				$this->form_validation->set_rules('object_id','Object','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$objectmeta = $this->products_model->get_objectmeta_single($object_id);
					$product_attributes = unserialize(!empty($objectmeta['product_attributes']) ? $objectmeta['product_attributes'] : '');
					$this->data['attribute_terms'] = $this->get_attribute_with_terms($object_id, $product_attributes);
					$this->data['object_id'] = $object_id;
					$this->data['product_variations'] = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
					$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_assign_variation', $this->data, true);
					$response = array('response'=>'yes','message'=>'Product variation added successfully','content'=>$theHTMLResponse);
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}
	public function ajax_add_product_variation()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$response = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$action = $this->input->post('action');
				if(isset($action) && $action == 'add_product_variation')
				{
					$object_id = $this->input->post('object_id');
					$object = $this->products_model->get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);
					//echo '<pre>';print_r($object);echo '</pre>'; die();

					$this->form_validation->set_rules('object_id','Object','trim|required');
					if ($this->form_validation->run() === TRUE)
					{
						$datestring = 'M, d Y h:i A';
						$timezone  = 'UP5';
						$gmt_time = local_to_gmt(time(), $timezone);
						$local_time = gmt_to_local($gmt_time, $timezone);
						$data = array(
							'object_parent' => $object_id,
							'object_status' => 'publish',
							'object_title' => $object['object_title'],
							'object_type' => 'product_variation',
							'object_date' => $local_time,
							'object_date_gmt' => $gmt_time,
							'object_modified' => $local_time,
							'object_modified_gmt' => $gmt_time,
							'object_author' => !empty($object['object_author']) ? $object['object_author'] : "",
						);
						$variable_object_id = $this->products_model->add_object($data);
						$meta = array(
							array(
								'object_id' => $variable_object_id,
								'meta_key' => 'variable_regular_price',
								'meta_value' => '',
							),
							array(
								'object_id' => $variable_object_id,
								'meta_key' => 'variable_sku',
								'meta_value' => '',
							),
							array(
								'object_id' => $variable_object_id,
								'meta_key' => 'variable_stock',
								'meta_value' => '',
							)
						);
						$aArray = $this->get_product_attributes($meta_key = 'product_attributes', $object_id);
						foreach ($aArray as $key => $value) {
							$Arr['object_id'] = $variable_object_id;
							$Arr['meta_key'] = 'attribute_'.$key;
							$Arr['meta_value'] = '';
							$attribute_array[] = $Arr;
						}
						$objectmeta = array_merge($meta, $attribute_array);
						$this->products_model->add_objectmeta($objectmeta);
						
						$objectmeta = $this->products_model->get_objectmeta_single($object_id);
						$product_attributes = unserialize(!empty($objectmeta['product_attributes']) ? $objectmeta['product_attributes'] : '');
						$this->data['attribute_terms'] = $this->get_attribute_with_terms($object_id, $product_attributes);
						$this->data['object_id'] = $object_id;
						$this->data['product_variations'] = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
						$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_assign_variation', $this->data, true);
						$response = array('response'=>'yes','message'=>'Product variation added successfully','content'=>$theHTMLResponse);
						$this->output->set_content_type('application/json');
						$this->output->set_output(json_encode($response));
					}
				}
			}
		}
	}
	
	public function ajax_delete_product_variation()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$response = array('response'=>'no', 'message' =>'Failed to delete this file');
			if ($_POST)
			{
				$object_id = $this->input->post('object_id');
				$variation_id = $this->input->post('variation_id');
				$this->products_model->delete_object($variation_id);
				$this->products_model->delete_objectmeta_by_object_id($variation_id);
				$objectmeta = $this->products_model->get_objectmeta_single($object_id);
				$product_attributes = unserialize(!empty($objectmeta['product_attributes']) ? $objectmeta['product_attributes'] : '');
				$this->data['attribute_terms'] = $this->get_attribute_with_terms($object_id, $product_attributes);
				$this->data['object_id'] = $object_id;
				$this->data['product_variations'] = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
				$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_assign_variation', $this->data, true);
				$response = array('response'=>'yes','message'=>'Product category deleted successfully','content'=> $theHTMLResponse);
			}
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
		}
	}
	public function ajax_save_product_variation()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$response = array('response'=>'no', 'message' =>'Failed to add this category');
			if($_POST)
			{
				$object_id = $this->input->post('object_id');
				$variation_data_json = $this->input->post('variations_values');
				$this->form_validation->set_rules('object_id','Object','trim|required');
				$this->form_validation->set_rules('variations_values','Value','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					// Decoding $_POST data received in serializeJSON
					$decoded_variation_data_json = json_decode($variation_data_json);
					if (!empty($decoded_variation_data_json))
					{
						// Switching keys and values diagonally
						$variations = $this->array_transpose($decoded_variation_data_json);
						$variation_meta_array_batch = [];
						foreach($variations as $variation)
						{
							// 1st Loop through variations to get meta values
							$product_variation_meta_batch = [];
							$ometa_ids_array = [];
							foreach(array_slice($variation,1) as $k => $v) // skip key value pair
							{
								$object = $this->products_model->get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);
								$variation_id = $variation['variation_id'];
								$objectmeta = $this->products_model->get_objectmeta_by_key($k, $variation_id);
								if(empty($objectmeta)){
									$meta = array(
										array(
											'object_id' => $variation_id,
											'meta_key' => $k,
											'meta_value' => $v,
										)
									);
									$this->products_model->add_objectmeta($meta);
								}
								$veriationmeta = $this->products_model->get_objectmeta_by_key($k, $variation_id);
								$ometa_ids = array(
									'ometa_id'  => $veriationmeta['ometa_id'],
								);
								$ometa_ids_array[] = $ometa_ids;
								$product_variation_meta = array(
										'meta_key' => $k,
										'meta_value' => $v,
								);
								$product_variation_meta_batch[] = $product_variation_meta;
							}
							// Merge 1st and 2nd array to make an array for batch_update
							$result = [];
							foreach($ometa_ids_array as $key => $val){ // Loop though one array
								$val2 = $product_variation_meta_batch[$key]; // Get the values from the other array
								$result[$key] = array_merge($val, $val2); // combine 'em
							}
							$this->products_model->update_objectmeta($variation_id, $result);
							$variation_attributes = $this->get_variation_attributes($variation_id);
							$data = array(
								'object_title' => $object['object_title'].' - '.$variation_attributes,
							);
							$this->products_model->update_object($variation_id, $data);
						} 
					}
					$response = array('response'=>'yes','message'=>'Product categoty added successfully');
					$this->output->set_content_type('application/json');
					$this->output->set_output(json_encode($response));
				}
			}
		}
	}
	public function attributes()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			if($_POST)
			{
				$attribute_name = $this->input->post('attribute_name');
				$attribute_slug = $this->input->post('attribute_slug');
				$this->form_validation->set_rules('attribute_name','Name','trim|required');
				$this->form_validation->set_rules('attribute_slug','Slug','trim|required');
				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'attribute_name' => $attribute_name,
						'attribute_slug' => $attribute_slug,
					);
					$last_id = $this->products_model->add_attribute($data);
					redirect($this->access_type . "/products/attributes", 'refresh');
				}
			}
			$attributes = $this->products_model->get_attributes();
			$new_attributes = [];
			foreach($attributes as $attribute)
			{
				$attr['attribute_id'] = $attribute->attribute_id;
				$attr['attribute_name'] = $attribute->attribute_name;
				$attr['attribute_slug'] = $attribute->attribute_slug;
				$attribute_term = $this->products_model->get_attribute_term_by_attribute_id($attribute->attribute_id, $attribute->attribute_slug);
				$secondattr = [];
				foreach($attribute_term as $term)
				{
					$secondattr[] = !empty($term['name']) ? $term['name'] : NULL;
				}
				$attr['attribute_values'] = $secondattr;
				$new_attributes[] = $attr;
			}
			$this->data['attribute_name'] = array(
				'name' => 'attribute_name',
				'id' => 'attribute_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('attribute_name'),
				'class' => 'form-control',
				'placeholder' => 'Enter attribute name',
			);
			$this->data['attribute_slug'] = array(
				'name' => 'attribute_slug',
				'id' => 'attribute_slug',
				'type' => 'text',
				'value' => $this->form_validation->set_value('attribute_slug'),
				'class' => 'form-control',
				'placeholder' => 'Enter attribute slug',
			);
			$this->data['title'] = lang('title_products_categories');
			$this->data['new_attributes'] = $new_attributes;
			//list the users
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
			$this->data['class'] = $this->session->flashdata('class');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'attributes', $this->data);
		}
	}
	public function attribute_values()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$taxonomy = $this->input->get('taxonomy', TRUE);
			$action = $this->input->get('action', TRUE);
			$term_id = $this->input->get('term_id', TRUE);

			$attribute = $this->products_model->get_attribute_by_slug($taxonomy);
			if(!empty($attribute))
			{
				switch ($action) {
					case "":
						$this->attribute_value_view($attribute, $taxonomy);
						break;
					case "view":
						$this->attribute_value_view($attribute, $taxonomy);
						break;
					case "edit":
						$this->attribute_value_edit($term_id, $attribute, $taxonomy);
						break;
					case "delete":
						$this->attribute_value_delete($term_id, $taxonomy);
						break;
					default:
						$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
						$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
				}
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	private function attribute_value_delete($term_id)
	{
		$taxonomy = $this->input->get('taxonomy', TRUE);
		$this->products_model->delete_term($term_id);
		$this->products_model->delete_term_taxonomy_by_term_id($term_id);
		$this->products_model->delete_attribute_term($term_id);
		redirect($this->access_type . "/products/attribute_values?taxonomy=". $taxonomy, 'refresh');
	}
	private function attribute_value_edit($term_id, $attribute, $taxonomy)
	{
		if(isset($term_id))
		{
			$term = $this->products_model->get_term_by_id($term_id);
			//echo '<pre>';print_r($term);echo '</pre>'; die();
			if(!empty($term))
			{
				if($_POST)
				{
					$attribute_value_name = $this->input->post('attribute_value_name');
					$attribute_value_slug = $this->input->post('attribute_value_slug');
					$this->form_validation->set_rules('attribute_value_name','Name','trim|required');
					$this->form_validation->set_rules('attribute_value_slug','Slug','trim|required');
					if ($this->form_validation->run() === TRUE)
					{
						$data = array(
							'name' => $attribute_value_name,
							'slug' => $attribute_value_slug,
						);
						$this->products_model->update_term($term_id, $data);
						redirect($this->access_type . "/products/attribute_values?taxonomy=".$taxonomy."&edit=". $term['term_id'], 'refresh');
					}
				}
				$this->data['attribute_value_name'] = array(
					'name' => 'attribute_value_name',
					'id' => 'attribute_value_name',
					'type' => 'text',
					'value' => $this->form_validation->set_value('attribute_value_name', !empty($term['name']) ? $term['name'] : ""),
					'class' => 'form-control',
				);
				$this->data['attribute_value_slug'] = array(
					'name' => 'attribute_value_slug',
					'id' => 'attribute_value_slug',
					'type' => 'text',
					'value' => $this->form_validation->set_value('attribute_value_slug', !empty($term['slug']) ? $term['slug'] : ""),
					'class' => 'form-control',
				);
				$this->data['title'] = lang('title_products_categories');
				$this->data['attribute'] = $attribute;
				$this->data['term_taxonomy'] = $this->products_model->get_terms_by_taxonomy($taxonomy);
				//list the users
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'edit_attribute_values', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
		else
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
		}
	}
	private function attribute_value_view($attribute, $taxonomy)
	{
		if($_POST)
		{
			$attribute_value_name = $this->input->post('attribute_value_name');
			$attribute_value_slug = $this->input->post('attribute_value_slug');
			$this->form_validation->set_rules('attribute_value_name','Name','trim|required');
			$this->form_validation->set_rules('attribute_value_slug','Slug','trim|required');
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'name' => $attribute_value_name,
					'slug' => $attribute_value_slug,
					'term_group' => 0,
				);
				$last_id = $this->products_model->add_term($data);
				$additional_data = array(
					'attribute_id' => $attribute['attribute_id'],
					'term_id' => $last_id,
				);
				$this->products_model->add_attribute_terms($additional_data);
				$other_data = array(
					'term_id' => $last_id,
					'taxonomy' => $taxonomy,
					'description' => '',
					'parent' => '',
					'count' => '',
				);
				$this->products_model->add_term_taxonomy($other_data);
				redirect($this->access_type . "/products/attribute_values?taxonomy=". $taxonomy, 'refresh');
			}
		}
		$this->data['attribute_value_name'] = array(
			'name' => 'attribute_value_name',
			'id' => 'attribute_value_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('attribute_value_name'),
			'class' => 'form-control',
		);
		$this->data['attribute_value_slug'] = array(
			'name' => 'attribute_value_slug',
			'id' => 'attribute_value_slug',
			'type' => 'text',
			'value' => $this->form_validation->set_value('attribute_value_slug'),
			'class' => 'form-control',
		);
		$this->data['title'] = lang('title_products_categories');
		$this->data['attribute'] = $attribute;
		$this->data['term_taxonomy'] = $this->products_model->get_terms_by_taxonomy($taxonomy);
		//echo '<pre>';print_r($attribute);echo '</pre>'; die();
		//list the users
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->data['class'] = $this->session->flashdata('class');
		$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'attribute_values', $this->data);
	}

	public function edit_attribute($attribute_id = NULL)
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$attribute = $this->products_model->get_attribute_by_id($attribute_id);
			//echo '<pre>'; print_r($attribute); echo '</pre>'; die();
			if(!empty($attribute))
			{
				if($_POST)
				{
						$protection_name = $this->input->post('protection_name');
						$protection_description = $this->input->post('protection_description');
					
						// validate form input
						$this->form_validation->set_rules('protection_name','Aircraft Class Name','trim|required');
						$this->form_validation->set_rules('protection_description','Aircraft Class Details','trim');

					if ($this->form_validation->run() === TRUE)
					{
						$additional_data = array(
							'protection_name' => $protection_name,
							'protection_description' => $protection_description,
						);
						$this->flights_model->update_protection($id, $additional_data);
						$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
						redirect($this->access_type . "/products/edit_attribute/" . $attribute_id, 'refresh');
						//$this->user_model->update_user_data($additional_data);
					}
				}
				$this->data['attribute_name'] = array(
					'name' => 'attribute_name',
					'id' => 'attribute_name',
					'type' => 'text',
					'value' => $this->form_validation->set_value('attribute_name', !empty($attribute['attribute_name']) ? $attribute['attribute_name'] : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter attribute name',
				);
				$this->data['attribute_slug'] = array(
					'name' => 'attribute_slug',
					'id' => 'attribute_slug',
					'type' => 'text',
					'value' => $this->form_validation->set_value('attribute_slug', !empty($attribute['attribute_slug']) ? $attribute['attribute_slug'] : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter attribute slug',
				);
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'edit_attribute', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function delete_attribute($attribute_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect($this->access_type . '/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$attribute_term = $this->products_model->get_attribute_term_by_attribute_id($attribute_id);
			if(!empty($attribute_term))
			{
				foreach($attribute_term as $relation)
				{
					$this->products_model->delete_term($relation['term_id']);
					$this->products_model->delete_term_taxonomy_by_term_id($relation['term_id']);
					$this->products_model->delete_attribute_term($relation['term_id']);
				}
			}
			$this->products_model->delete_attribute($attribute_id);
			redirect($this->access_type . "/products/attributes", 'refresh');
		}
	}
	
	public function ajax_fetch_products()
	{
		$output = '';
		$query = '';
		if($this->input->post('product_search'))
		{
			$query = $this->input->post('product_search');
		}
		$response = $this->products_model->fetch_data($query);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response));
	}
	

	public function upload_file()
    {
		$response =  array("response"=>"no","content"=>"");
        $config['upload_path'] = './uploads/images/products/original/';

        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('file'))
		{
			$theHTMLResponse = '';
			$message = 'Upload failed. File size (max limit: 2mb) or file resolution too high.';
			$response = array('response'=>'yes', 'response_type'=>'error', 'content'=>$theHTMLResponse, 'message'=>$message);
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
		}
		else
        {
            $file = $this->upload->data();
            $thumb_config["image_library"] = "gd2";
            $thumb_config["source_image"] = $file["full_path"];
            $thumb_config['new_image'] = 'uploads/images/products/thumb/'.$file['file_name'];
            $thumb_config['create_thumb'] = TRUE;
            $thumb_config['thumb_marker'] = '';
            $thumb_config['maintain_ratio'] = TRUE;
            $thumb_config['width'] = 550;
            $thumb_config['height'] = 424;
            $this->load->library('image_lib', $thumb_config);
            $this->image_lib->initialize($thumb_config);
            $this->image_lib->resize();
			$this->image_lib->clear();
			
			//Product image id comes from product edit page.
			$product_id = $this->input->post('product_id');
					//echo '<pre>'; print_r($product_id); echo '</pre>';die();
            $data = array (
                'title' => $file['orig_name'],
                'path' => 'uploads/images/products/original/'.$file['file_name'],
                'ext' => $file['file_ext'],
                'thumb' => $thumb_config['new_image'],
                'size' => $file['file_size'],
	            'product_id' => $product_id,
	            'created_at' => date('Y-m-d h:i:s')
            );
			$image_type = $this->input->post('image_type');
			if($image_type == 'product-image')
			{
				$thumbnail = $this->products_model->get_objectmeta_by_key($meta_key = 'thumbnail_id', $product_id);
				if(isset($thumbnail)) {
					$product_image = $this->products_model->get_product_image_by_id($thumbnail['meta_value']);
					if(!empty($product_image))
					{
						$path = $product_image->path;
						$thumb =$product_image->thumb;
						$this->products_model->delete_product_image($product_id, $path, $thumb);
					}
					$last_id = $this->products_model->update_product_image($thumbnail['meta_value'], $data);
				} else {
					$last_id = $this->products_model->add_product_image($data);
					$metadata = array(
						'thumbnail_id' => $last_id,
					);
					$this->compute_object_meta($product_id, $metadata);
				}
				$product_image = $this->products_model->get_product_image_by_last_id($last_id);
				if($product_image)
				{
					$this->data['product_image_id'] = $last_id;
					$this->data['product_image'] = $product_image;
					$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_product_image', $this->data, true);
					$message = 'Product featured image is uploaded';
					$response = array('response'=>'yes','response_type'=>'custom','content'=>$theHTMLResponse, 'message'=>$message);
				}
			} else {
				$last_id = $this->products_model->add_product_image($data);
				$relational_data = array (
					'object_id' => $product_id,
					'product_image_id' => $last_id,
				);
				$this->products_model->assign_product_gallery_image($relational_data);
				$product_image = $this->products_model->get_product_image_by_last_id($last_id);
				if($product_image)
				{
					$this->data['product_image_id'] = $last_id;
					$this->data['product_image'] = $product_image;
					$theHTMLResponse = $this->load->view($this->access_type . '/ajax_blocks/block_product_gallery_image', $this->data, true);
					$message = 'Product featured image is uploaded';
					$response = array('response'=>'gallery','response_type'=>'custom','content'=>$theHTMLResponse, 'message'=>$message);
				}
			}
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
        }
	}

	/**
	 * Terms Relations CRUD
	 * @attribute term_taxonomy_id        Array of Id's
	 * @attribute object_id               String | Integer
	 * @attribute taxonomy                String | alpha_numeric
	 */
	public function terms_selection_crud($term_taxonomy_id, $object_id, $taxonomy) {
		if(!empty($term_taxonomy_id)) {
			foreach ($term_taxonomy_id as $key => $value) {
				$if_exist = $this->products_model->check_if_term_id_exist_by_id($value, $object_id, $taxonomy);
				$if_not_exist = $this->products_model->check_if_term_id_not_exist_by_id($value, $object_id, $taxonomy);
				if(empty($if_exist)) {
					if(isset($key) && isset($value)) {
						$new[] = array(
							'object_id' => $object_id,
							'term_taxonomy_id' => $value,
							'term_order' => '0',
						);
					}
				}
			}
			// Replacement for insert_batch to ignore duplicate
			if(!empty($new)) {
				foreach ($new as $data_item) {
					$insert_query = $this->db->insert_string('term_relationships', $data_item);
					$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
					$this->db->query($insert_query);
				}
			}
			foreach ($term_taxonomy_id as $key => $value) {
				$if_exist = $this->products_model->check_if_term_id_exist_by_id($value, $object_id, $taxonomy);
				$if_not_exist = $this->products_model->check_if_term_id_not_exist_by_id($value, $object_id, $taxonomy);
				if(!empty($if_exist)) {
					$old[] = $if_exist;
				}
			}
			if(!empty($old)) {
				$difference = $this->relation_array_diff($if_not_exist, $old);
				$deletearray = array();
				foreach ($difference as $key => $value) {
					$term_taxonomy_id = $value['term_taxonomy_id'];
					$object_id = $value['object_id'];
					$this->products_model->delete_term_relationships_id($term_taxonomy_id, $object_id);
				}
			}
		} else {
			$relationships = $this->products_model->get_taxonomy_with_relationships($object_id, $taxonomy);
			foreach ($relationships as $relationship) {
				$term_taxonomy_id = $relationship['term_taxonomy_id'];
				$this->products_model->delete_term_relationships_id($term_taxonomy_id, $object_id);
			}
		}
	}
	public function get_assigned_attribute_terms($object_id, $product_objectmeta)
	{
		if(!empty($product_objectmeta)){
			$available_attributes=[];
			foreach($product_objectmeta as $obj)
			{
				$available_attributes[] = $this->products_model->get_attribute_by_slug($obj['name']);
			};
			if(!empty($available_attributes)){
				$new_attributes = [];
				foreach($available_attributes as $attribute)
				{
					$attr_list = $this->products_model->get_terms_by_taxonomy($attribute['attribute_slug']);
					$secondattr = [];
					foreach($attr_list as $term)
					{
						$attribute_term = $this->products_model->get_descriptive_term_relation($object_id, $term['term_taxonomy_id']);
						if(!empty($attribute_term))
						{
							$secondattr[] = !empty($attribute_term['name']) ? $attribute_term['name'] : NULL;
						}
					}
					$attr[$attribute['attribute_slug']] = $secondattr;
					$new_attributes = $attr;
				}
				return $new_attributes;
			}
		}
	}
	public function get_assigned_attribute_list($object_id, $product_objectmeta)
	{
		if(!empty($product_objectmeta)){
			$available_attributes=[];
			foreach($product_objectmeta as $obj)
			{
				$available_attributes[] = $this->products_model->get_attribute_by_slug($obj['name']);
			};
			if(!empty($available_attributes)){
				$new_attributes = [];
				foreach($available_attributes as $attribute)
				{
					$attr['attribute_id'] = $attribute['attribute_id'];
					$attr['attribute_name'] = $attribute['attribute_name'];
					$attr['attribute_slug'] = $attribute['attribute_slug'];
					$attr_list = $this->products_model->get_terms_by_taxonomy($attr['attribute_slug']);
					$secondattr = [];
					foreach($attr_list as $term)
					{
						$attribute_term = $this->products_model->get_descriptive_term_relation($object_id, $term['term_taxonomy_id']);
						$secondattr[] = array(
							'name' => !empty($term['name']) ? $term['name'] : NULL,
							'object_id' => !empty($attribute_term['object_id']) ? 'selected' : NULL,
						);
					}
					$attr['attribute_values'] = $secondattr;
					$new_attributes[] = $attr;
				}
				return $new_attributes;
			}
		}
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
	
	public function get_product($object_id) {
		$product_type = $this->products_model->check_product_type($object_id);
		$product = $this->products_model->get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);
		if(!empty($product)){
			$assigned_vendors = $this->vendors_model->get_vendors_assigned_to_product($object_id);
			$productmeta = $this->products_model->get_objectmeta($object_id);
			$product_variations = $this->get_products($type = 'product_variation', $parent = $object_id, $author = NULL, $status = 'publish');
			$datestring = 'M, d Y';		
			$changeArr['object_id'] = $product['object_id'];
			$changeArr['object_type'] = $product['object_type'];
			$changeArr['product_type'] = $product_type['product_type'];
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
				foreach ($productmeta as $key => $item) {
					$product_array = array();
					$changeArr[$item['meta_key']] = $item['meta_value'];
					if($item['meta_key'] == 'thumbnail_id') {
						$productimage = $this->products_model->get_product_image_by_id($item['meta_value']);
						$changeArr['product_image'] = $productimage->thumb;
					}
				}
			}
			$changeArr['assigned_vendors'] = $assigned_vendors;
			if($product_type = 'variable') {
				if(!empty($product_variations)){
					foreach ($product_variations as $k => $v) {
						$vArr['variable_title'] = $v['object_title'];
						$vArr['variable_regular_price'] = $v['variable_regular_price'];
						$vArr['variable_stock'] = $v['variable_stock'];
						$variation_array[] = $vArr;
					}
					$changeArr['variations'] = $variation_array;
				}
			}
			//echo '<pre>';print_r($changeArr);echo '</pre>';die();
			if(!empty($changeArr)){
			    return $changeArr;
			}
		}
	}
	public function get_variation_attributes($object_id) {
		$object = $this->products_model->get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL);
		$product_attributes = $this->get_product_attributes($meta_key = 'product_attributes', $object['object_parent']);
		foreach($product_attributes as $key => $value) {
			$meta_key = 'attribute_'.$key;
			$objectmeta = $this->products_model->get_objectmeta($object_id);
			foreach($objectmeta as $k => $v) {
				if($meta_key == $v['meta_key']){
					$terms = $this->products_model->get_objectmeta_by_key($v['meta_key'], $object_id);
					$variation_attributes[] = $terms['meta_value'];
				}
			}
		}
		$variation_attributes = implode(', ', $variation_attributes);
		return $variation_attributes;
	}
	public function get_product_attributes($meta_key, $object_id) {
		$ometa = $this->products_model->get_objectmeta_by_key($meta_key, $object_id);
		return unserialize($ometa['meta_value']);
	}
	
	public function get_product_attributes_indexed($meta_key, $object_id) {
		$ometa = $this->products_model->get_objectmeta_by_key($meta_key, $object_id);
		$array = unserialize($ometa['meta_value']);
		foreach ($array as $key => $value) {
			$itemArr[] = $value;
		}
		return $itemArr;
	}
	//first parameter Array
	public function check_if_key_exist($array) {
		if(!empty($array)) {
			$found = current(array_filter($array, function($item) use($object_id) {
				return isset($item['object_id']) && $object_id == $item['object_id'];
			}));
		}
	}
	public function relation_array_diff($arraya, $arrayb) {
		foreach ($arraya as $keya => $valuea) {
			if (in_array($valuea, $arrayb)) {
				unset($arraya[$keya]);
			}
		}
		return $arraya;
	}
	function array_transpose($foo){
		$temp = [];
		array_walk($foo, function($item,$key) use(&$temp){
			foreach($item as $k => $v){
				$temp[$k][$key] = $v;     
			}
		});
		return $temp;
	}
	private function compute_object_meta($object_id, $metadata){
		$object_meta = $this->products_model->get_objectmeta($object_id);
		foreach ($object_meta as $key => $object){
			$o_meta[] = $object['meta_key'];
		}
		$meta_update = array();
		foreach ($metadata as $k => $v){
			$objectmeta = $this->products_model->get_objectmeta_by_key($k, $object_id);
			$array['meta_key'] = "'".$k."'";
			if($this->input->post($k)){
				$array['post_values'] = $this->input->post($k);
			} else {
				$array['post_values'] = $v;
			}
			if(!empty($o_meta)) {
				$key_exists = in_array($k, $o_meta);
				if($key_exists == 1){
					$meta_update[] = array(
						'ometa_id'  => $objectmeta['ometa_id'],
						'meta_key' => !empty($k) ? $k : "",
						'meta_value' => $array['post_values'],
					);
				} else {					
					$meta_add[] = array(
						'object_id'  => $object_id,
						'meta_key' => !empty($k) ? $k : "",
						'meta_value' => $array['post_values'],
					);
				}
			} else {
				$meta_add[] = array(
					'object_id'  => $object_id,
					'meta_key' => !empty($k) ? $k : "",
					'meta_value' => $array['post_values'],
				);
			}
		}
		//echo '<pre>'; print_r($meta_update); echo '</pre>';
		//echo '<pre>'; print_r($meta_add); echo '</pre>'; die();
		if(!empty($meta_update)){
			$this->products_model->update_objectmeta($object_id, $meta_update);
		}
		if(!empty($meta_add)){
			//echo '<pre>';print_r($meta_add);echo '</pre>'; die();
			$this->products_model->add_objectmeta($meta_add);
		}
	}
}
