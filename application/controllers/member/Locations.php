<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Locations extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('locations_model');

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
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//list the users
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	
	public function cities()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			if($_POST)
			{
				$city_name = $this->input->post('city_name');
				$country_id = $this->input->post('country_id');
				$city_code = $this->input->post('city_code');
				
				// validate form input
				$this->form_validation->set_rules('city_name','City Name','trim|required');
				$this->form_validation->set_rules('country_id','Country Name','trim|required');
				$this->form_validation->set_rules('city_code','City Code','trim');

				if ($this->form_validation->run() === TRUE)
				{
					$city_data = array(
						'country_id' => $country_id,
						'city_code' => $city_code,
						'city_name' => $city_name,
					);
					$this->locations_model->add_city($city_data);
					$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					redirect('admin/locations/cities');
				}
			}
			$this->data['city_code'] = array(
				'name' => 'city_code',
				'id' => 'city_code',
				'type' => 'text',
				'value' => $this->form_validation->set_value('city_code'),
				'class' => 'form-control',
				'placeholder' => 'Enter city code eg: LHE for Lahore',
			);
			$this->data['city_name'] = array(
				'name' => 'city_name',
				'id' => 'city_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('city_name'),
				'class' => 'form-control',
				'placeholder' => 'Enter city name',
			);
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['all_cities'] = $this->locations_model->get_all_cities();
			$this->data['all_countries'] = $this->locations_model->get_all_countries();
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . 'cities' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function edit_city($city_id = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$city = $this->locations_model->get_city_by_id($city_id);
			//echo '<pre>'; print_r($city); echo '</pre>'; die();
			if(!empty($city))
			{
				if($_POST)
				{
					$country_id = $this->input->post('country_id');
					$city_code = $this->input->post('city_code');
					$city_name = $this->input->post('city_name');
					
					// validate form input
					$this->form_validation->set_rules('country_id','Country Name','trim|required');
					$this->form_validation->set_rules('city_code','City Code','trim|required');
					$this->form_validation->set_rules('city_name','City Name','trim');

					if ($this->form_validation->run() === TRUE)
					{
						$city_data = array(
							'country_id' => $country_id,
							'city_code' => $city_code,
							'city_name' => $city_name,
						);
						$this->locations_model->update_city($city_id, $city_data);
						$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
						redirect("admin/locations/cities", 'refresh');
					}
				}
				$country_id = $city->country_id;
				$single_country = (array)$this->locations_model->get_single_country($country_id);
				$this->data['country_id'] = array(
					'name' => 'country_id',
					'id' => 'country_id',
					'type' => 'text',
					'value' => $this->form_validation->set_value('country_id', !empty($single_country['country_id']) ? $single_country['country_id'] : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter any stopover',
				);
				$this->data['city_code'] = array(
					'name' => 'city_code',
					'id' => 'city_code',
					'type' => 'text',
					'value' => $this->form_validation->set_value('city_code', !empty($city->city_code) ? $city->city_code : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter city code eg: LHE for Lahore',
				);
				$this->data['city_name'] = array(
					'name' => 'city_name',
					'id' => 'city_name',
					'type' => 'text',
					'value' => $this->form_validation->set_value('city_name', !empty($city->city_name) ? $city->city_name : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter city name',
				);
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['single_country'] = $single_country;
				$this->data['all_cities'] = $this->locations_model->get_all_cities();
				$this->data['all_countries'] = $this->locations_model->get_all_countries();
				//list the users
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . 'cities' . DIRECTORY_SEPARATOR . 'edit', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function delete_city($terminal_id)
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
			$check_terminals_exist = $this->locations_model->check_terminals_exist_in_city($terminal_id);
			$exist_terminals = count($check_terminals_exist);
			//echo '<pre>'; print_r($exist_terminals); echo '</pre>'; die();
			if($exist_terminals == 0)
			{
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				//list the users
				$this->locations_model->delete_city($terminal_id);
				redirect("admin/locations/cities", 'refresh');
			}
			else
			{
				if($exist_terminals > 1)
				{
					$this->session->set_flashdata('message', 'This city is linked with '.$exist_terminals.' terminals, Unlink first to delete this country');
					$this->session->set_flashdata('class', 'btn btn-danger waves-effect waves-light');
					redirect('admin/locations/countries');
				}
				else
				{
					$this->session->set_flashdata('message', 'This city is linked with 1 terminal, Unlink first to delete this city');
					$this->session->set_flashdata('class', 'btn btn-danger waves-effect waves-light');
					redirect('admin/locations/countries');
				}
			}
		}
	}
	public function countries()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			if($_POST)
			{
				$country_name = $this->input->post('country_name');
				$continent_id = $this->input->post('continent_id');
				$country_code = $this->input->post('country_code');
				
				// validate form input
				$this->form_validation->set_rules('country_name','Country Name','trim|required');
				$this->form_validation->set_rules('continent_id','Continent Name','trim|required');
				$this->form_validation->set_rules('country_code','Continent Name','trim');

				if ($this->form_validation->run() === TRUE)
				{
					$country_data = array(
						'continent_id' => $continent_id,
						'country_code' => $country_code,
						'country_name' => $country_name,
					);
					//echo '<pre>'; print_r($country_data); echo '</pre>'; die();
					$this->locations_model->add_country($country_data);
					$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					redirect("admin/locations/countries", 'refresh');
				}
			}
			$this->data['country_code'] = array(
				'name' => 'country_code',
				'id' => 'country_code',
				'type' => 'text',
				'value' => $this->form_validation->set_value('country_code'),
				'class' => 'form-control',
				'placeholder' => 'Enter country code eg: LHE for Lahore',
			);
			$this->data['country_name'] = array(
				'name' => 'country_name',
				'id' => 'country_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('country_name'),
				'class' => 'form-control',
				'placeholder' => 'Enter country name',
			);
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['all_cities'] = $this->locations_model->get_all_cities();
			$this->data['all_countries'] = $this->locations_model->get_all_countries();
			$this->data['all_continents'] = $this->locations_model->get_all_continents();
			$this->data['class'] = $this->session->flashdata('class');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . 'countries' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function edit_country($country_id = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$country = $this->locations_model->get_country_by_id($country_id);
			if(!empty($country))
			{
				if($_POST)
				{
					$continent_id = $this->input->post('continent_id');
					$country_name = $this->input->post('country_name');
					$country_code = $this->input->post('country_code');
					
					// validate form input
					$this->form_validation->set_rules('continent_id','Continent Name','trim|required');
					$this->form_validation->set_rules('country_name','Country Name','trim|required');
					$this->form_validation->set_rules('country_code','Country Code','trim');

					if ($this->form_validation->run() === TRUE)
					{
						$country_data = array(
							'continent_id' => $continent_id,
							'country_name' => $country_name,
							'country_code' => $country_code,
						);
						$this->locations_model->update_country($country_id, $country_data);
						$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
						redirect("admin/locations/countries", 'refresh');
					}
				}
				$continent_id = $country->continent_id;
				$single_continent = (array)$this->locations_model->get_single_continent($continent_id);
				$this->data['continent_id'] = array(
					'name' => 'continent_id',
					'id' => 'continent_id',
					'type' => 'text',
					'value' => $this->form_validation->set_value('continent_id', !empty($single_continent['continent_id']) ? $single_country['continent_id'] : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter any stopover',
				);
				$this->data['country_code'] = array(
					'name' => 'country_code',
					'id' => 'country_code',
					'type' => 'text',
					'value' => $this->form_validation->set_value('country_code', !empty($country->country_code) ? $country->country_code : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter city code eg: LHE for Lahore',
				);
				$this->data['country_name'] = array(
					'name' => 'country_name',
					'id' => 'country_name',
					'type' => 'text',
					'value' => $this->form_validation->set_value('country_name', !empty($country->country_name) ? $country->country_name : ""),
					'class' => 'form-control',
					'placeholder' => 'Enter city name',
				);
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->data['single_continent'] = $single_continent;
				$this->data['all_cities'] = $this->locations_model->get_all_cities();
				$this->data['all_countries'] = $this->locations_model->get_all_countries();
				$this->data['all_continents'] = $this->locations_model->get_all_continents();
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . 'countries' . DIRECTORY_SEPARATOR . 'edit', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function delete_country($id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$check_cities_exist = $this->locations_model->check_cities_exist_in_country($id);
			//echo '<pre>'; print_r($check_cities_exist); echo '</pre>'; die();
			$exist_cities = count($check_cities_exist);
			if($exist_cities == 0)
			{
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->locations_model->delete_country($id);
				//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
				redirect('admin/locations/countries');
			}
			else
			{
				if($exist_cities > 1)
				{
					$this->session->set_flashdata('message', 'This country is linked with '.$exist_cities.' cities, Unlink first to delete this country');
					$this->session->set_flashdata('class', 'btn btn-danger waves-effect waves-light');
					redirect('admin/locations/countries');
				}
				else
				{
					$this->session->set_flashdata('message', 'This country is linked with 1 city, Unlink first to delete this country');
					$this->session->set_flashdata('class', 'btn btn-danger waves-effect waves-light');
					redirect('admin/locations/countries');
				}
			}
		}
	}
	
	public function continents()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			if($_POST)
			{
				$continent_name = $this->input->post('continent_name');
				$continent_code = $this->input->post('continent_code');
				
				// validate form input
				$this->form_validation->set_rules('continent_name','Continent Name','trim|required');
				$this->form_validation->set_rules('continent_code','Continent Code','trim');

				if ($this->form_validation->run() === TRUE)
				{
					$continent_data = array(
						'continent_name' => $continent_name,
						'continent_code' => $continent_code,
					);
					//echo '<pre>'; print_r($this->session->all_userdata()); echo '</pre>'; die();
					$this->locations_model->add_continent($continent_data);
					$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
					redirect("admin/locations/continents", 'refresh');
				}
			}
			$this->data['continent_name'] = array(
				'name' => 'continent_name',
				'id' => 'continent_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('continent_name'),
				'class' => 'form-control',
				'placeholder' => 'Enter name of the continent',
			);
			$this->data['continent_code'] = array(
				'name' => 'continent_code',
				'id' => 'continent_code',
				'type' => 'text',
				'value' => $this->form_validation->set_value('continent_code'),
				'class' => 'form-control',
				'placeholder' => 'Enter continent code',
			);
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['all_cities'] = $this->locations_model->get_all_cities();
			$this->data['all_continents'] = $this->locations_model->get_all_continents();
			$this->data['class'] = $this->session->flashdata('class');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . 'continents' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function edit_continent($continent_id = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$continent = $this->locations_model->get_continent_by_id($continent_id);
			if(!empty($continent))
			{
				if($_POST)
				{
					$continent_name = $this->input->post('continent_name');
					$continent_code = $this->input->post('continent_code');
					
					// validate form input
					$this->form_validation->set_rules('continent_name','Terminal Short Code','trim|required');
					$this->form_validation->set_rules('continent_code','Terminal Short Name','trim|required');
					$this->form_validation->set_rules('city_name','Terminal Name','trim');

					if ($this->form_validation->run() === TRUE)
					{
						$continent_data = array(
							'continent_name' => $continent_name,
							'continent_code' => $continent_code,
						);
						//echo '<pre>'; print_r($this->session->all_userdata()); echo '</pre>'; die();
						$this->locations_model->update_continent($continent_id, $continent_data);
						$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
						redirect("admin/locations/continents", 'refresh');
					}
				}
				$this->data['continent_name'] = array(
					'name' => 'continent_name',
					'id' => 'continent_name',
					'type' => 'text',
					'value' => $this->form_validation->set_value('continent_name', $continent->continent_name),
					'class' => 'form-control',
					'placeholder' => 'Enter name of the continent',
				);
				$this->data['continent_code'] = array(
					'name' => 'continent_code',
					'id' => 'continent_code',
					'type' => 'text',
					'value' => $this->form_validation->set_value('continent_code', $continent->continent_code),
					'class' => 'form-control',
					'placeholder' => 'Enter continent code',
				);
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['all_cities'] = $this->locations_model->get_all_cities();
				$this->data['all_continents'] = $this->locations_model->get_all_continents();
				//echo '<pre>'; print_r($this->data['all_cities']); echo '</pre>'; die();
				//list the users
				$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'locations' . DIRECTORY_SEPARATOR . 'continents' . DIRECTORY_SEPARATOR . 'edit', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
	public function delete_continent($id)
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
			$check_countries_exist = $this->locations_model->check_countries_exist_in_continent($id);
			$exist_countries = count($check_countries_exist);
			if($exist_countries == 0)
			{
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->locations_model->delete_continent($id);
				redirect('admin/locations/continents');
			}
			else
			{
				if($exist_countries > 1)
				{
					$this->session->set_flashdata('message', 'This continent is linked with '.$exist_countries.' countries, Unlink first to delete this continent');
					$this->session->set_flashdata('class', 'btn btn-danger waves-effect waves-light');
					redirect('admin/locations/continents');
				}
				else
				{
					$this->session->set_flashdata('message', 'This continent is linked with 1 country, Unlink first to delete this continent');
					$this->session->set_flashdata('class', 'btn btn-danger waves-effect waves-light');
					redirect('admin/locations/continents');
				}
			}
		}
	}
}
