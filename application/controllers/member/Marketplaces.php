<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Marketplaces extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('marketplaces_model');

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
			if($_POST)
			{
				$marketplace_name = $this->input->post('marketplace_name');
				
				// validate form input
				$this->form_validation->set_rules('marketplace_name','Currency Name','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'marketplace_name' => $marketplace_name,
					);
					$this->marketplaces_model->add_marketplace($data);
					redirect("admin/marketplaces", 'refresh');
				}
			}
			$this->data['marketplace_name'] = array(
				'name' => 'marketplace_name',
				'id' => 'marketplace_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('marketplace_name'),
				'class' => 'form-control',
				'placeholder' => 'eg: Amazon',
			);
			$this->data['marketplaces'] = $this->marketplaces_model->get_marketplaces();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'marketplaces' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	public function edit_marketplace($marketplace_id)
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
				$marketplace_name = $this->input->post('marketplace_name');
				
				// validate form input
				$this->form_validation->set_rules('marketplace_name','Currency Name','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'marketplace_name' => $marketplace_name,
					);
					$this->marketplaces_model->update_marketplace($marketplace_id, $data);
					redirect("admin/marketplaces", 'refresh');
				}
			}
			$marketplaces = $this->marketplaces_model->get_marketplace_by_id($marketplace_id);
			$this->data['marketplace_name'] = array(
				'name' => 'marketplace_name',
				'id' => 'marketplace_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('marketplace_name', !empty($marketplaces['marketplace_name']) ? $marketplaces['marketplace_name'] : ""),
				'class' => 'form-control',
				'placeholder' => 'eg: Amazon',
			);
			$this->data['currencies'] = $marketplaces;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'marketplaces' . DIRECTORY_SEPARATOR . 'edit', $this->data);
		}
	}
}
