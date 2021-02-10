<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Packages extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
        $config =  array(
            'encrypt_name'    => TRUE,
			'upload_path'     => "./uploads/images/packages/galleries/",
			'allowed_types'   => "gif|jpg|png|jpeg",
			'overwrite'       => FALSE,
			'max_size'        => "2000",
			'max_height'      => "2024",
			'max_width'       => "2024"
        );
        $this->load->library('upload', $config);
        $this->load->library('image_lib');
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('packages_model');
		$this->load->model('settings_model');
		$this->load->model('seo_model');

		$this->lang->load('auth');
	}
	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$this->data['packages'] = $this->packages_model->get_packages();
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function package_type($post_type)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$packages_array = $this->packages_model->get_packages_by_type($type);
			foreach($packages_array as $key => $package)
			{
				$package_id = $package['id'];
				$prices = $this->packages_model->get_package_prices_id($package_id);
				$tempArr['id'] = $package_id;
				$tempArr['type'] = $package['type'];
				$tempArr['title'] = $package['title'];
				$tempArr['sub_title'] = $package['sub_title'];
				$tempArr['no_of_days'] = $package['no_of_days'];
				$tempArr['departure_date'] = $package['departure_date'];
				$tempArr['return_date'] = $package['return_date'];
				$tempArr['status'] = $package['status'];
				$tempArr['date'] = $package['date'];
				$tempArr['prices'] = $prices;
				$n_packages_array[] = $tempArr;
			}
			//echo '<pre>'; print_r($n_packages_array); echo '</pre>'; die();
			if(!empty($n_packages_array))
			{
				$this->data['packages'] = json_decode(json_encode($n_packages_array));
			}
			// set the flash data error message if there is one
			$this->data['settings'] = (object)$this->settings_model->get_settings_options();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function create($post_type, $action = NULL)
	{
		$slug = $this->uri->segment(3);
		$type = strtolower($slug);
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			if($_POST)
			{
				$departure = $this->input->post('departure');
				$price = $this->input->post('price');
				$sale_price = $this->input->post('sale_price');
				$featured_image_id = $this->input->post('featured_image_id');
				$package_title = $this->input->post('package_title');
				$sub_title = $this->input->post('sub_title');
				$no_of_days = $this->input->post('no_of_days');
				$departure_date = $this->input->post('departure_date');
				$return_date = $this->input->post('return_date');
				$status = $this->input->post('status');
				$title = $this->input->post('title');
				$description = $this->input->post('description');
			
				// validate form input
				$this->form_validation->set_rules('featured_image_id','Image','trim');
				$this->form_validation->set_rules('package_title','Package title','trim|required');
				$this->form_validation->set_rules('sub_title','Sub Title Stopover','trim|required');
				$this->form_validation->set_rules('departure','Departure','trim|required');
				$this->form_validation->set_rules('price','Price','trim|required');
				$this->form_validation->set_rules('sale_price','Sale Price','trim');
				$this->form_validation->set_rules('no_of_days','No. of Days','trim|required');
				$this->form_validation->set_rules('departure_date','Departure Date','trim|required');
				$this->form_validation->set_rules('return_date','Return Date','trim|required');
				$this->form_validation->set_rules('status','Status','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$additional_data = array(
						'type' => $type,
						'title' => $package_title,
						'sub_title' => $sub_title,
						'no_of_days' => $no_of_days,
						'departure_date' => $departure_date,
						'return_date' => $return_date,
						'status' => $status,
					);
					$last_id = $this->packages_model->add_package($additional_data);
					$departure_pricing = array(
						'departure' => $departure,
						'price' => $price,
						'sale_price' => $sale_price,
					);
					$transpose = $this->transpose($departure_pricing);
					foreach ($transpose as $t_item){
						$tempArr['package_id'] = $last_id;
						$tempArr['departure'] = $t_item['departure'];
						$tempArr['price'] = $t_item['price'];
						$tempArr['sale_price'] = $t_item['sale_price'];
						$newArray[] = $tempArr;
					}
					$this->packages_model->add_package_prices($newArray);
					//echo '<pre>'; print_r($newArray); echo '</pre>'; die();
					if(!empty($featured_image_id))
					{
						$other_data = array(
							'package_id' => $last_id,
						);
						$this->packages_model->update_featured_image_package_id($featured_image_id, $other_data);
					}
					$data = array(
						'page_id' => $last_id,
						'page' => $type,
						'title' => $title,
						'description' => $description,
					);
					$this->seo_model->add_update_seo_by_page_id($type, $last_id, $data);
					redirect("admin/packages/". $type. "/". $last_id, 'refresh');
				}
			}
			$this->data['package_title'] = array(
				'name' => 'package_title',
				'id' => 'package_title',
				'type' => 'text',
				'value' => $this->form_validation->set_value('title'),
				'class' => 'form-control',
				'placeholder' => 'Enter ticket price',
			);
			$this->data['sub_title'] = array(
				'name' => 'sub_title',
				'id' => 'sub_title',
				'type' => 'text',
				'value' => $this->form_validation->set_value('sub_title'),
				'class' => 'form-control',
				'placeholder' => 'Enter package title',
			);
			$this->data['price'] = array(
				'name' => 'price',
				'id' => 'price',
				'type' => 'text',
				'value' => $this->form_validation->set_value('price'),
				'class' => 'form-control',
				'placeholder' => 'Enter package sub title',
			);
			$this->data['sale_price'] = array(
				'name' => 'sale_price',
				'id' => 'sale_price',
				'type' => 'text',
				'value' => $this->form_validation->set_value('sale_price'),
				'class' => 'form-control',
				'placeholder' => 'Enter sale price',
			);
			$this->data['no_of_days'] = array(
				'name' => 'no_of_days',
				'id' => 'no_of_days',
				'type' => 'number',
				'value' => $this->form_validation->set_value('no_of_days'),
				'class' => 'form-control',
				'placeholder' => 'Enter ticket price',
			);
			$this->data['departure_date'] = array(
				'name' => 'departure_date',
				'id' => 'datepicker_departure',
				'type' => 'text',
				'value' => $this->form_validation->set_value('departure_date'),
				'class' => 'form-control',
				'placeholder' => 'Month dd, yyyy',
			);
			$this->data['return_date'] = array(
				'name' => 'return_date',
				'id' => 'datepicker_return',
				'type' => 'text',
				'value' => $this->form_validation->set_value('return_date'),
				'class' => 'form-control',
				'placeholder' => 'Month dd, yyyy',
			);
			$this->data['title'] = array(
				'name' => 'title',
				'id' => 'title',
				'type' => 'text',
				'value' => $this->form_validation->set_value('title'),
				'class' => 'form-control',
				'placeholder' => 'Enter seo title',
			);
			$this->data['description'] = array(
				'name' => 'description',
				'id' => 'description',
				'type' => 'text',
				'value' => $this->form_validation->set_value('description'),
				'class' => 'form-control',
				'placeholder' => 'Enter seo description',
			);
			$this->data['cities'] = $this->packages_model->get_all_cities();
			$this->data['settings'] = (object)$this->settings_model->get_settings_options();
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'create', $this->data);
		}
	}
	public function edit($type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$package = $this->packages_model->get_package_by_id($id, $type);
			$package_prices = $this->packages_model->get_package_prices_id($id);
			if(!empty($package->id))
			{
				if($_POST)
				{
					$price_id_array = $this->input->post('price_id');
					$departure_array = $this->input->post('departure');
					$price_array = $this->input->post('price');
					//echo '<pre>'; print_r($price_array); echo '</pre>'; die();
					$sale_price_array = $this->input->post('sale_price');
					$package_title = $this->input->post('package_title');
					$sub_title = $this->input->post('sub_title');
					$location = $this->input->post('location');
					$no_of_days = $this->input->post('no_of_days');
					$departure_date = $this->input->post('departure_date');
					$return_date = $this->input->post('return_date');
					$status = $this->input->post('status');
					$title = $this->input->post('title');
					$description = $this->input->post('description');
				
					// validate form input
					$this->form_validation->set_rules('package_title','Package title','trim|required');
					$this->form_validation->set_rules('sub_title','Sub Title Stopover','trim|required');
					$this->form_validation->set_rules('location','Trip Location','trim|required');
					$this->form_validation->set_rules('departure','Departure','trim');
					$this->form_validation->set_rules('price[]','Price','trim|required');
					$this->form_validation->set_rules('sale_price','Sale Price','trim');
					$this->form_validation->set_rules('no_of_days','No. of Days','trim|required');
					$this->form_validation->set_rules('departure_date','Departure Date','trim|required');
					$this->form_validation->set_rules('return_date','Return Date','trim|required');
					$this->form_validation->set_rules('status','Status','trim|required');

					if ($this->form_validation->run() === TRUE)
					{
						$additional_data = array(
							'type' => $type,
							'title' => $package_title,
							'sub_title' => $sub_title,
							'location' => $location,
							'no_of_days' => $no_of_days,
							'departure_date' => $departure_date,
							'return_date' => $return_date,
							'status' => $status,
						);
						$this->packages_model->update_package($additional_data, $id);
						function filled_values_callback($val) {
							$val = trim($val);
							return $val != '';
						}
						function empty_values_callback($val) {
							$val = trim($val);
							return empty($val);
						}
						$post_array_with_values = array_filter($price_id_array, 'filled_values_callback');
						$post_array_empty_values = array_filter($price_id_array, 'empty_values_callback');
						if(!empty($post_array_with_values))
						{
							$t_departure = $this->parallel_key_value_array($post_array_with_values, $departure_array);
							$t_price = $this->parallel_key_value_array($post_array_with_values, $price_array);
							$t_sale_price = $this->parallel_key_value_array($post_array_with_values, $sale_price_array);

							$pricing = array(
								'id' => $post_array_with_values,
								'departure' => $t_departure,
								'price' => $t_price,
								'sale_price' => $t_sale_price,
							);
							$transpose = $this->transpose($pricing);
							
							$prices_same = $this->filter_prices_same($package_prices, $transpose);
							foreach ($prices_same as $t_item){
								$tempArr['id'] = $t_item['id'];
								$tempArr['package_id'] = $id;
								$tempArr['departure'] = $t_item['departure'];
								$tempArr['price'] = $t_item['price'];
								$tempArr['sale_price'] = $t_item['sale_price'];
								$newArray[] = $tempArr;
							}
							if(!empty($newArray))
							{
								$this->db->update_batch('package_prices', $newArray, 'id');
							}
							foreach ($package_prices as $t_item){
								$Arr['id'] = $t_item['id'];
								$Arr['package_id'] = $id;
								$Arr['departure'] = $t_item['departure'];
								$Arr['price'] = $t_item['price'];
								$Arr['sale_price'] = $t_item['sale_price'];
								$Arr['city_id'] = $t_item['city_id'];
								$Arr['city_name'] = $t_item['city_name'];
								$rArr[$t_item['id']] = $Arr;
							}
							$prices_difference = $this->filter_prices_difference($transpose, $rArr);
							//echo '<pre>'; print_r($prices_difference); echo '</pre>'; die();
							if(!empty($prices_difference))
							{
								$deletearray = array();
								foreach ($prices_difference as $key => $value)
								{
									$price_id = $value['id'];
									$this->packages_model->delete_package_prices_by_id($price_id);
								}
							}
						}
						if(!empty($post_array_empty_values))
						{
							$t_departure = $this->parallel_key_value_array($post_array_empty_values, $departure_array);
							$t_price = $this->parallel_key_value_array($post_array_empty_values, $price_array);
							$t_sale_price = $this->parallel_key_value_array($post_array_empty_values, $sale_price_array);

							$pricing = array(
								'departure' => $t_departure,
								'price' => $t_price,
								'sale_price' => $t_sale_price,
							);
							$transpose = $this->transpose($pricing);
							foreach ($transpose as $n_item){
								$tempnArr['package_id'] = $id;
								$tempnArr['departure'] = $n_item['departure'];
								$tempnArr['price'] = $n_item['price'];
								$tempnArr['sale_price'] = $n_item['sale_price'];
								$newitemArray[] = $tempnArr;
							}
							$this->db->insert_batch('package_prices', $newitemArray);
						}
						$data = array(
							'page_id' => $id,
							'page' => $type,
							'title' => $title,
							'description' => $description,
						);
						$this->seo_model->add_update_seo_by_page_id($type, $id, $data);
						redirect("admin/packages/".$type."/".$id."/edit", 'refresh');
					}
				}
				$seo = $this->seo_model->get_seo_by_page_id($type, $id);
				$this->data['package_title'] = array(
					'name' => 'package_title',
					'id' => 'package_title',
					'type' => 'text',
					'value' => $this->form_validation->set_value('title', !empty($package->title) ? $package->title : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter ticket price',
				);
				$this->data['sub_title'] = array(
					'name' => 'sub_title',
					'id' => 'sub_title',
					'type' => 'text',
					'value' => $this->form_validation->set_value('sub_title', !empty($package->sub_title) ? $package->sub_title : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter package title',
				);
				$this->data['price'] = array(
					'name' => 'price',
					'id' => 'price',
					'type' => 'text',
					'value' => $this->form_validation->set_value('price', !empty($package->price) ? $package->price : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter package sub title',
				);
				$this->data['sale_price'] = array(
					'name' => 'sale_price',
					'id' => 'sale_price',
					'type' => 'text',
					'value' => $this->form_validation->set_value('sale_price', !empty($package->sale_price) ? $package->sale_price : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter sale price',
				);
				$this->data['no_of_days'] = array(
					'name' => 'no_of_days',
					'id' => 'no_of_days',
					'type' => 'number',
					'value' => $this->form_validation->set_value('no_of_days', !empty($package->no_of_days) ? $package->no_of_days : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter ticket price',
				);
				$this->data['departure_date'] = array(
					'name' => 'departure_date',
					'id' => 'datepicker_departure',
					'type' => 'text',
					'value' => $this->form_validation->set_value('departure_date', !empty($package->departure_date) ? $package->departure_date : ""),
					'class' => 'form-control',
					'placeholder' => 'Month dd, yyyy',
				);
				$this->data['return_date'] = array(
					'name' => 'return_date',
					'id' => 'datepicker_return',
					'type' => 'text',
					'value' => $this->form_validation->set_value('return_date', !empty($package->return_date) ? $package->return_date : ""),
					'class' => 'form-control',
					'placeholder' => 'Month dd, yyyy',
				);
				$this->data['title'] = array(
					'name' => 'title',
					'id' => 'title',
					'type' => 'text',
					'value' => $this->form_validation->set_value('title', !empty($seo['title']) ? $seo['title'] : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter seo title',
				);
				$this->data['description'] = array(
					'name' => 'description',
					'id' => 'description',
					'type' => 'text',
					'value' => $this->form_validation->set_value('description', !empty($seo['description']) ? $seo['description'] : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter seo description',
				);
				$this->data['prices_count'] = count($package_prices);
				$this->data['package_prices'] = $package_prices;
				$this->data['cities'] = $this->packages_model->get_all_cities();
				$this->data['settings'] = (object)$this->settings_model->get_settings_options();
				$this->data['package'] = $package;
				$this->data['featured_image'] = $this->packages_model->get_package_featured_images($id);
				//echo '<pre>'; print_r($this->data['package']); echo '</pre>'; die();
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'edit', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function details($post_type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$package = $this->packages_model->get_package_by_id($id, $type);
			if(!empty($package->id))
			{
				$this->data['package'] = $package;
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'details', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function description($post_type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$package = $this->packages_model->get_package_by_id($id, $type);
			$package_array = (array)$package;
			$package_meta = $this->packages_model->get_package_meta_by_id($id);
			if(!empty($package_array) && !empty($package_meta))
			{
				$pack = array_replace_recursive($package_array, $package_meta);
				$package_id = $pack['id'];
			}
			elseif(!empty($package_array) && empty($package_meta))
			{
				$pack = array_replace_recursive($package_array);
				$package_id = $package_array['id'];
			}
			else
			{
				$package_id = '';
			}
			if(!empty($package_id))
			{
				$this->data['description'] = array(
					'name' => 'description',
					'id' => 'description',
					'type' => 'text',
					'value' => $this->form_validation->set_value('description', !empty($pack['description']) ? $pack['description'] : ""),
					'class' => 'summernote"',
					'placeholder' => 'Enter ticket price',
				);
				$this->data['package'] = (object)$pack;
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'description', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function photo_gallery($post_type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$package = $this->packages_model->get_package_by_id($id, $type);
			if(!empty($package->id))
			{
				$type = 'gallery';
				$this->data['images'] = $this->packages_model->get_package_images_by_package_id($id, $type);
				$this->data['package'] = $package;
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'photo_gallery', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	
	public function features($post_type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$package = $this->packages_model->get_package_by_id($id, $type);
			$package_id = $package->id;
			$key = 'feature';
			$features = $this->packages_model->get_package_meta_by_key_result($package_id, $key);
			if(!empty($package_id))
			{
				$this->data['feature'] = array(
					'name' => 'feature',
					'id' => 'feature',
					'type' => 'text',
					'value' => $this->form_validation->set_value('feature'),
					'class' => 'form-control',
					'placeholder' => 'Enter Feature',
				);
				$this->data['features'] = $features;
				$this->data['package'] = $package;
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'features', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function amenities($post_type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$package = $this->packages_model->get_package_by_id($id, $type);
			$package_id = $package->id;
			$key = 'amenity';
			$amenities = $this->packages_model->get_amenities($package_id, $key);
			if(!empty($package_id))
			{
				$this->data['amenity'] = array(
					'name' => 'amenity',
					'id' => 'amenity',
					'type' => 'text',
					'value' => $this->form_validation->set_value('amenity'),
					'class' => 'form-control',
					'placeholder' => 'Enter Amenity',
				);
				$this->data['amenities'] = $amenities;
				$this->data['package'] = $package;
				//echo '<pre>'; print_r($this->data['package']); echo '</pre>'; die();
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'amenities', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function itinerary($post_type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$package = $this->packages_model->get_package_by_id($id, $type);
			$package_id = $package->id;
			$itinerary = $this->packages_model->get_itinerary($package_id, $type);
			if(!empty($package_id))
			{
				$this->data['day'] = array(
					'name' => 'day',
					'id' => 'day',
					'type' => 'text',
					'value' => $this->form_validation->set_value('day'),
					'class' => 'form-control',
					'placeholder' => 'Enter itinerary day',
				);
				$this->data['itinerary_title'] = array(
					'name' => 'itinerary_title',
					'id' => 'itinerary_title',
					'type' => 'text',
					'value' => $this->form_validation->set_value('itinerary_title'),
					'class' => 'form-control',
					'placeholder' => 'Enter itinerary title',
				);
				$this->data['itinerary_detail'] = array(
					'name' => 'itinerary_detail',
					'id' => 'itinerary_detail',
					'type' => 'text',
					'value' => $this->form_validation->set_value('itinerary_detail'),
					'class' => 'form-control',
					'placeholder' => 'Enter itinerary details',
				);
				$this->data['itinerary'] = $itinerary;
				$this->data['package'] = $package;
				//echo '<pre>'; print_r($this->data['package']); echo '</pre>'; die();
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'itinerary', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function location($post_type, $id, $action = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$type = $this->uri->segment(3);
			$key = $this->uri->segment(5);
			$package = $this->packages_model->get_package_by_id($id, $type);
			$package_id = $package->id;
			$loc = $this->packages_model->get_package_meta_by_key_row($package_id, $key);
			//echo '<pre>'; print_r($location_exist); echo '</pre>'; die();
			if(!empty($package_id))
			{
				$this->data['location'] = array(
					'name' => 'location',
					'id' => 'location',
					'type' => 'text',
					'value' => $this->form_validation->set_value('location', !empty($loc->meta_value) ? $loc->meta_value : ""),
					'cols' => '102',
					'placeholder' => 'paste your map <iframe> code here',
				);
				$this->data['package'] = $package;
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'travel' . DIRECTORY_SEPARATOR . 'post_type' . DIRECTORY_SEPARATOR . 'location', $this->data);
			}
			else
			{
				// set any errors and display the form
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['class'] = $this->session->flashdata('class');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	function transpose($array) {
		$keys = array_keys($array);
		return array_map(function($array) use ($keys) {
			return array_combine($keys, $array);
		}, array_map(null, ...array_values($array)));
	}
	function parallel_key_value_array($post_array_with_values, $post_array) {
		foreach ($post_array_with_values as $key => $value)
		{
			$resultedArray = array();
			foreach ($post_array as $post_key =>  $post_value)
			{
				if($key == $post_key)
				{
					$filledArr[$key] = $post_value;
					$resultedArray = $filledArr;
				}
			}
		}
		return $resultedArray;
	}
	function filter_prices_same($post_array_with_values, $post_array) {
		foreach ($post_array_with_values as $key => $value)
		{
			$resultedArray = array();
			foreach ($post_array as $post_key =>  $post_value)
			{
				if($value['id'] == $post_value['id'])
				{
					$filledArr[$key] = $post_value;
					$resultedArray = $filledArr;
				}
			}
		}
		return $resultedArray;
	}
	function filter_prices_difference($post_array_empty_values, $post_array) {
		foreach ($post_array_empty_values as $key => $value)
		{
			$resultedArray = array();
			foreach ($post_array as $post_key =>  $post_value)
			{
				if($value['id'] == $post_value['id'])
				{
					$filledArr[$value['id']] = $post_value;
					$resultedArray = $filledArr;
				}
			}
		}
		$difference = $this->array_diff_assoc_recursive($post_array, $resultedArray);
		return $difference;
	}
	function array_diff_assoc_recursive($array1, $array2)
	{
		foreach($array1 as $key => $value)
		{
			if(is_array($value))
			{
				if(!isset($array2[$key]))
				{
					$difference[$key] = $value;
				}
				elseif(!is_array($array2[$key]))
				{
					$difference[$key] = $value;
				}
			}
			elseif(!isset($array2[$key]) || $array2[$key] != $value)
			{
				$difference[$key] = $value;
			}
			else
			{
				$new_diff = array_diff_assoc_recursive($value, $array2[$key]);
				if($new_diff != FALSE)
				{
					$difference[$key] = $new_diff;
				}
			}
		}
		return !isset($difference) ? 0 : $difference;
	}
}
