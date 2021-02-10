<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Currencies extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('currencies_model');

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
				$currency_name = $this->input->post('currency_name');
				$currency_code = $this->input->post('currency_code');
				$currency_symbol = $this->input->post('currency_symbol');
				
				// validate form input
				$this->form_validation->set_rules('currency_name','Currency Name','trim|required');
				$this->form_validation->set_rules('currency_code','Currency Code','trim|required');
				$this->form_validation->set_rules('currency_symbol','Currency Symbol','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'currency_name' => $currency_name,
						'currency_code' => $currency_code,
						'currency_symbol' => $currency_symbol,
					);
					$this->currencies_model->add_currency($data);
					redirect("admin/currencies", 'refresh');
				}
			}
			$this->data['currency_name'] = array(
				'name' => 'currency_name',
				'id' => 'currency_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('currency_name'),
				'class' => 'form-control',
				'placeholder' => 'eg: Pakistan Rupee',
			);
			$this->data['currency_code'] = array(
				'name' => 'currency_code',
				'id' => 'currency_code',
				'type' => 'text',
				'value' => $this->form_validation->set_value('currency_code'),
				'class' => 'form-control',
				'placeholder' => 'eg: PKR',
			);
			$this->data['currency_symbol'] = array(
				'name' => 'currency_symbol',
				'id' => 'currency_symbol',
				'type' => 'text',
				'value' => $this->form_validation->set_value('currency_symbol'),
				'class' => 'form-control',
				'placeholder' => 'eg: Rs',
			);
			$this->data['currencies'] = $this->currencies_model->get_currencies();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'currencies' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	public function edit_currency($currency_id)
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
				$currency_name = $this->input->post('currency_name');
				$currency_code = $this->input->post('currency_code');
				$currency_symbol = $this->input->post('currency_symbol');
				
				// validate form input
				$this->form_validation->set_rules('currency_name','Currency Name','trim|required');
				$this->form_validation->set_rules('currency_code','Currency Code','trim|required');
				$this->form_validation->set_rules('currency_symbol','Currency Symbol','trim|required');

				if ($this->form_validation->run() === TRUE)
				{
					$data = array(
						'currency_name' => $currency_name,
						'currency_code' => $currency_code,
						'currency_symbol' => $currency_symbol,
					);
					$this->currencies_model->update_currency($currency_id, $data);
					redirect("admin/currencies/edit_currency/". $currency_id, 'refresh');
				}
			}
			$currencies = $this->currencies_model->get_currency_by_id($currency_id);
			$this->data['currency_name'] = array(
				'name' => 'currency_name',
				'id' => 'currency_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('currency_name', !empty($currencies['currency_name']) ? $currencies['currency_name'] : ""),
				'class' => 'form-control',
				'placeholder' => 'eg: Pakistan Rupee',
			);
			$this->data['currency_code'] = array(
				'name' => 'currency_code',
				'id' => 'currency_code',
				'type' => 'text',
				'value' => $this->form_validation->set_value('currency_code', !empty($currencies['currency_code']) ? $currencies['currency_code'] : ""),
				'class' => 'form-control',
				'placeholder' => 'eg: PKR',
			);
			$this->data['currency_symbol'] = array(
				'name' => 'currency_symbol',
				'id' => 'currency_symbol',
				'type' => 'text',
				'value' => $this->form_validation->set_value('currency_symbol', !empty($currencies['currency_symbol']) ? $currencies['currency_symbol'] : ""),
				'class' => 'form-control',
				'placeholder' => 'eg: Rs',
			);
			$this->data['currencies'] = $currencies;
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'currencies' . DIRECTORY_SEPARATOR . 'edit', $this->data);
		}
	}
}
