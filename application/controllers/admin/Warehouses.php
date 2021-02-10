<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Warehouses extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('warehouses_model');
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
				$warehouse_name = $this->input->post('warehouse_name');
				$warehouse_location = $this->input->post('warehouse_location');
				$warehouse_author = $this->input->post('warehouse_author');
				
				// validate form input
				$this->form_validation->set_rules('warehouse_name','Warehouse Name','trim|required');
				$this->form_validation->set_rules('warehouse_location','Warehouse Location','trim|required');
				$this->form_validation->set_rules('warehouse_author','Warehouse Author','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'warehouse_type' => 'additional',
						'warehouse_name' => $warehouse_name,
						'warehouse_location' => $warehouse_location,
						'warehouse_author' => $warehouse_author,
						'warehouse_status' => 'publish',
					);
					$this->warehouses_model->add_warehouse($data);
					redirect("admin/warehouses", 'refresh');
				}
			}
			$this->data['warehouse_name'] = array(
				'name' => 'warehouse_name',
				'id' => 'warehouse_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('warehouse_name'),
				'class' => 'form-control',
				'placeholder' => '',
			);
			$this->data['warehouse_location'] = array(
				'name' => 'warehouse_location',
				'id' => 'warehouse_location',
				'type' => 'text',
				'value' => $this->form_validation->set_value('warehouse_location'),
				'class' => 'form-control',
				'placeholder' => '',
			);
			$this->data['warehouses'] = $this->warehouses_model->get_warehouse_by_author();
			$this->data['users'] = $this->ion_auth->users(3)->result();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'warehouses' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	public function edit($warehouse_id)
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
				$warehouse_name = $this->input->post('warehouse_name');
				$warehouse_location = $this->input->post('warehouse_location');
				
				// validate form input
				$this->form_validation->set_rules('warehouse_name','Courier Name','trim|required');
				$this->form_validation->set_rules('warehouse_location','Courier Name','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'warehouse_name' => $warehouse_name,
						'warehouse_location' => $warehouse_location,
						'warehouse_status' => 'publish',
					);
					$this->warehouses_model->update_warehouse($warehouse_id, $data);
					redirect("admin/warehouses/edit/". $warehouse_id, 'refresh');
				}
			}
			$warehouses = $this->warehouses_model->get_warehouse_by_id($warehouse_id);
			$this->data['warehouse_name'] = array(
				'name' => 'warehouse_name',
				'id' => 'warehouse_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('warehouse_name', !empty($warehouses['warehouse_name']) ? $warehouses['warehouse_name'] : ""),
				'class' => 'form-control',
				'placeholder' => '',
			);
			$this->data['warehouse_location'] = array(
				'name' => 'warehouse_location',
				'id' => 'warehouse_location',
				'type' => 'text',
				'value' => $this->form_validation->set_value('warehouse_location', !empty($warehouses['warehouse_location']) ? $warehouses['warehouse_location'] : ""),
				'class' => 'form-control',
				'placeholder' => '',
			);
			$this->data['warehouses'] = $warehouses;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page($this->access_type . DIRECTORY_SEPARATOR . 'warehouses' . DIRECTORY_SEPARATOR . 'edit', $this->data);
		}
	}
}
