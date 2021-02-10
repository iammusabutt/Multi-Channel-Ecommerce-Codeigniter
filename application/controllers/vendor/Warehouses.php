<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Warehouses extends Vendor_Controller
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
		$this->access_type = 'vendor';
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$vendor_id = $this->session->userdata('user_id');
			//echo '<pre>'; print_r($vendor_id); echo '</pre>'; die();
			if($_POST)
			{
				$warehouse_name = $this->input->post('warehouse_name');
				$warehouse_location = $this->input->post('warehouse_location');
				
				// validate form input
				$this->form_validation->set_rules('warehouse_name','Warehouse Name','trim|required');
				$this->form_validation->set_rules('warehouse_location','Warehouse Location','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'warehouse_type' => 'additional',
						'warehouse_name' => $warehouse_name,
						'warehouse_location' => $warehouse_location,
						'warehouse_author' => $vendor_id,
						'warehouse_status' => 'publish',
					);
					$this->warehouses_model->add_warehouse($data);
					redirect($this->access_type."/warehouses", 'refresh');
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
			$this->data['warehouses'] = $this->warehouses_model->get_warehouses($type = NULL, $name = NULL, $location = NULL, $author = $vendor_id, $status = NULL);
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
		elseif (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
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
					redirect($this->access_type."/warehouses/edit/". $warehouse_id, 'refresh');
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
	public function change_warehouse_default($warehouse_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$vendor_id = $this->session->userdata('user_id');
			$default_warehouse = $this->warehouses_model->get_warehouse(NULL, $type = 'default', $name = NULL, $location = NULL, $author = $vendor_id, $status = NULL);
			$where_conditions = array(
				'warehouse_id' => $warehouse_id,
				'warehouse_author' => $vendor_id,
			);
			$warehouse_exist = $this->record_exist($where_conditions, $table_name = 'warehouses');
			if($warehouse_exist){
				$default_data = array(
					'warehouse_type' => 'default',
				);
				$this->warehouses_model->update_warehouse($warehouse_id, $default_data);
			}
			//echo '<pre>'; print_r($default_warehouse); echo '</pre>'; die();
			if(!empty($default_warehouse)){
				$additional_data = array(
					'warehouse_type' => 'additional',
				);
				$this->warehouses_model->update_warehouse($default_warehouse->warehouse_id, $additional_data);
			}
			redirect($this->access_type."/warehouses", 'refresh');
		}
	}
	public function record_exist($where_conditions, $table_name)
	{
		$record = $this->warehouses_model->get_record($where_conditions, $table_name);
		if(!empty($record)){
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
