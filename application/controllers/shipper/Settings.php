<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Settings extends Shipper_Controller
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
		$this->access_type = 'shipper';
	}
	
	public function api_credentials()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 5)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
			$courier_type = $this->input->get('courier_type', TRUE);
			$action = $this->input->get('action', TRUE);
			if(isset($courier_type) && isset($action)) {
				switch ($courier_type) {
					case "dhl":
						$this->edit($courier_type, $action);
						break;
						case "":
							$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
							$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
						break;
					default:
						$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
						$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
				}
			} else {
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}

	private function edit($courier_type, $action)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 5)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
			if(isset($courier_type) && $action == 'edit')
			{
				$user_id = $this->session->userdata('user_id');
				if($_POST)
				{
					$secret_key = $this->input->post('secret_key');
					
					// validate form input
					$this->form_validation->set_rules('secret_key','Secret Keys','trim');

					if ($this->form_validation->run() === TRUE)
					{
						$data = array(
							'user_id' => $user_id,
							'secret_key' => $secret_key,
						);
						$last_id = $this->settings_model->compute_credentials($user_id, $data);
						$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
						redirect($this->access_type ."/settings/api_credentials?action=edit&courier_type=dhl", 'refresh');
					}
				}
				$credential = $this->settings_model->get_credential($user_id);
				$this->data['secret_key'] = array(
					'name' => 'secret_key',
					'id' => 'secret_key',
					'type' => 'text',
					'value' => $this->form_validation->set_value('secret_key', !empty($credential['secret_key']) ? $credential['secret_key'] : ""),
					'class' => 'form-control"',
					'placeholder' => '',
				);
				$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
				$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'api_credentials' . DIRECTORY_SEPARATOR . 'edit', $this->data);
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_page('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
	}
}
