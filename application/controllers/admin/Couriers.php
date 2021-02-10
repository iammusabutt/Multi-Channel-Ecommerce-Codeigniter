<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Couriers extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('couriers_model');
		$this->lang->load('auth');
		$this->access_type = 'admin';
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
				$courier_name = $this->input->post('courier_name');
				
				// validate form input
				$this->form_validation->set_rules('courier_name','Courier Name','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'courier_name' => $courier_name,
						'courier_status' => 'publish',
					);
					$this->couriers_model->add_courier($data);
					redirect("admin/couriers", 'refresh');
				}
			}
			$this->data['courier_name'] = array(
				'name' => 'courier_name',
				'id' => 'courier_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('courier_name'),
				'class' => 'form-control',
				'placeholder' => '',
			);
			$this->data['couriers'] = $this->couriers_model->get_couriers();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'couriers' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	public function edit($courier_id)
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
				$courier_name = $this->input->post('courier_name');
				
				// validate form input
				$this->form_validation->set_rules('courier_name','Courier Name','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'courier_name' => $courier_name,
					);
					$this->couriers_model->update_courier($courier_id, $data);
					redirect("admin/couriers/edit/". $courier_id, 'refresh');
				}
			}
			$couriers = $this->couriers_model->get_courier_by_id($courier_id);
			$this->data['courier_name'] = array(
				'name' => 'courier_name',
				'id' => 'courier_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('courier_name', !empty($couriers['courier_name']) ? $couriers['courier_name'] : ""),
				'class' => 'form-control',
				'placeholder' => '',
			);
			$this->data['couriers'] = $couriers;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'couriers' . DIRECTORY_SEPARATOR . 'edit', $this->data);
		}
	}
}
