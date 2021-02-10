<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Settings extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('settings_model');

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
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'bookings' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	public function general()
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
			if($_POST)
			{
				$site_title = $this->input->post('site_title');
				$site_description = $this->input->post('site_description');
				$currency_unit = $this->input->post('currency_unit');
			
				// validate form input
				$this->form_validation->set_rules('site_title','Phone','trim|required');
				$this->form_validation->set_rules('site_description','Phone','trim|required');
				$this->form_validation->set_rules('currency_unit','Phone','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$settings = $this->settings_model->get_settings();
					$data = array();
					foreach ($settings as $key => $setting){
						if(!empty($this->input->post($setting['setting_name'])))
						{
							$array['setting_name'] = "'".$setting['setting_name']."'";
							$array['post_values'] = $this->input->post($setting['setting_name']);
							$data[] = array(
								'id'  => $setting['id'],
								'setting_name' => $setting['setting_name'],
								'setting_value' => $array['post_values'],
							);
						}
					}
					$this->db->update_batch('settings', $data, 'id');
					redirect('admin/settings/general', 'refresh');
				}
			}
			$setting_option = $this->settings_model->get_settings_options();
			$this->data['site_title'] = array(
				'name' => 'site_title',
				'id' => 'site_title',
				'type' => 'text',
				'value' => $this->form_validation->set_value('site_title', !empty($setting_option['site_title']) ? $setting_option['site_title'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter site title number',
			);
			$this->data['site_description'] = array(
				'name' => 'site_description',
				'id' => 'site_description',
				'value' => $this->form_validation->set_value('site_description', !empty($setting_option['site_description']) ? $setting_option['site_description'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter description number',
			);
			$this->data['currency_unit'] = array(
				'name' => 'currency_unit',
				'id' => 'currency_unit',
				'type' => 'email',
				'value' => $this->form_validation->set_value('currency_unit', !empty($setting_option['currency_unit']) ? $setting_option['currency_unit'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter currency unit here',
			);
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'general', $this->data);
		}
	}
	public function communication()
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
			if($_POST)
			{
				$primary_phone = $this->input->post('primary_phone');
				$secondary_phone = $this->input->post('secondary_phone');
				$admin_email = $this->input->post('admin_email');
				$admin_address = $this->input->post('admin_address');
			
				// validate form input
				$this->form_validation->set_rules('primary_phone','Primary phone','trim|required');
				$this->form_validation->set_rules('secondary_phone','Secondary phone','trim');
				$this->form_validation->set_rules('admin_email','Phone','trim|required');
				$this->form_validation->set_rules('admin_address','Phone','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$settings = $this->settings_model->get_settings();
					$data = array();
					foreach ($settings as $key => $setting){
						if(!empty($this->input->post($setting['setting_name'])))
						{
							$array['setting_name'] = "'".$setting['setting_name']."'";
							$array['post_values'] = $this->input->post($setting['setting_name']);
							$data[] = array(
								'id'  => $setting['id'],
								'setting_name' => $setting['setting_name'],
								'setting_value' => $array['post_values'],
							);
						}
					}
					$this->db->update_batch('settings', $data, 'id');
					redirect('admin/settings/communication', 'refresh');
				}
			}
			$setting_option = $this->settings_model->get_settings_options();
			//echo '<pre>'; print_r($setting_option); echo '</pre>'; die();
			$this->data['primary_phone'] = array(
				'name' => 'primary_phone',
				'id' => 'primary_phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('primary_phone', !empty($setting_option['primary_phone']) ? $setting_option['primary_phone'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter phone number',
			);
			$this->data['secondary_phone'] = array(
				'name' => 'secondary_phone',
				'id' => 'secondary_phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('secondary_phone', !empty($setting_option['secondary_phone']) ? $setting_option['secondary_phone'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter secondary phone number',
			);
			$this->data['admin_email'] = array(
				'name' => 'admin_email',
				'id' => 'admin_email',
				'type' => 'email',
				'value' => $this->form_validation->set_value('admin_email', !empty($setting_option['admin_email']) ? $setting_option['admin_email'] : ""),
				'class' => 'form-control',
				'placeholder' => 'example@example.com',
			);
			$this->data['admin_address'] = array(
				'name' => 'admin_address',
				'id' => 'admin_address',
				'type' => 'email',
				'value' => $this->form_validation->set_value('admin_address', !empty($setting_option['admin_address']) ? $setting_option['admin_address'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter address here',
			);
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'communication', $this->data);
		}
	}
	public function social_media()
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
			if($_POST)
			{
				$medium_facebook = $this->input->post('medium_facebook');
				$medium_twitter = $this->input->post('medium_twitter');
				$medium_linkedin = $this->input->post('medium_linkedin');
				$medium_gplus = $this->input->post('medium_gplus');
				$medium_instagram = $this->input->post('medium_instagram');
				$medium_pinterest = $this->input->post('medium_pinterest');
			
				// validate form input
				$this->form_validation->set_rules('medium_facebook','Facebook','trim');
				$this->form_validation->set_rules('medium_twitter','Twitter','trim');
				$this->form_validation->set_rules('medium_linkedin','Linked In','trim');
				$this->form_validation->set_rules('medium_gplus','Google+','trim');
				$this->form_validation->set_rules('medium_instagram','Instagram','trim');
				$this->form_validation->set_rules('medium_pinterest','Pinterest','trim');

				if ($this->form_validation->run() === TRUE)
				{
					$settings = $this->settings_model->get_settings();
					$data = array();
					foreach ($settings as $key => $setting){
						if(!empty($this->input->post($setting['setting_name'])))
						{
							$array['setting_name'] = "'".$setting['setting_name']."'";
							$array['post_values'] = $this->input->post($setting['setting_name']);
							$data[] = array(
								'id'  => $setting['id'],
								'setting_name' => $setting['setting_name'],
								'setting_value' => $array['post_values'],
							);
						}
					}
					//echo '<pre>'; print_r($data); echo '</pre>'; die();
					$this->db->update_batch('settings', $data, 'id');
					redirect('admin/settings/social_media', 'refresh');
				}
			}
			$setting_option = $this->settings_model->get_settings_options();
			$this->data['medium_facebook'] = array(
				'name' => 'medium_facebook',
				'id' => 'medium_facebook',
				'type' => 'text',
				'value' => $this->form_validation->set_value('medium_facebook', !empty($setting_option['medium_facebook']) ? $setting_option['medium_facebook'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter url here',
			);
			$this->data['medium_twitter'] = array(
				'name' => 'medium_twitter',
				'id' => 'medium_twitter',
				'type' => 'text',
				'value' => $this->form_validation->set_value('medium_twitter', !empty($setting_option['medium_twitter']) ? $setting_option['medium_twitter'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter url here',
			);
			$this->data['medium_linkedin'] = array(
				'name' => 'medium_linkedin',
				'id' => 'medium_linkedin',
				'type' => 'text',
				'value' => $this->form_validation->set_value('medium_linkedin', !empty($setting_option['medium_linkedin']) ? $setting_option['medium_linkedin'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter url here',
			);
			$this->data['medium_gplus'] = array(
				'name' => 'medium_gplus',
				'id' => 'medium_gplus',
				'type' => 'text',
				'value' => $this->form_validation->set_value('medium_gplus', !empty($setting_option['medium_gplus']) ? $setting_option['medium_gplus'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter url here',
			);
			$this->data['medium_instagram'] = array(
				'name' => 'medium_instagram',
				'id' => 'medium_instagram',
				'type' => 'text',
				'value' => $this->form_validation->set_value('medium_instagram', !empty($setting_option['medium_instagram']) ? $setting_option['medium_instagram'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter url here',
			);
			$this->data['medium_pinterest'] = array(
				'name' => 'medium_pinterest',
				'id' => 'medium_pinterest',
				'type' => 'text',
				'value' => $this->form_validation->set_value('medium_pinterest', !empty($setting_option['medium_pinterest']) ? $setting_option['medium_pinterest'] : ""),
				'class' => 'form-control',
				'placeholder' => 'Enter url here',
			);
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'social_media', $this->data);
		}
	}
	public function convert_lowercase() {
		return strtolower($this->input->post('post_type_name'));
	}
}
