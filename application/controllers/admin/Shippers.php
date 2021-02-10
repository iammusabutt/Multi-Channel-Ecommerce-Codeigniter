<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Shippers extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('shippers_model');
		$this->load->model('locations_model');
		$this->lang->load('auth');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
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
			$this->data['users'] = $this->ion_auth->users(5)->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'shippers' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	/**
	 * Create a new user
	 */
	public function create_shipper()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('admin/auth', 'refresh');
		}

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		}
		else
		{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('company', 'Company', 'trim|required');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone' => $this->input->post('phone'),
				'company' => $this->input->post('company'),
			);
			$groups = array(5);
			$user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $groups);
			$user_metadata = array(
				array(
					'user_id' => $user_id,
					'meta_key' => 'shipper_type',
					'meta_value' => $this->input->post('shipper_type'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'shipper_country_id',
					'meta_value' => $this->input->post('country_id'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'shipper_term_conditions',
					'meta_value' => $this->input->post('term_conditions'),
				),
			);
			$this->shippers_model->add_user_metadata($user_metadata);
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("admin/shippers", 'refresh');
		}

		$this->data['first_name'] = array(
			'name' => 'first_name',
			'id' => 'first_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('first_name'),
			'class' => 'form-control',
			'placeholder' => 'First Name',
		);
		$this->data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('last_name'),
			'class' => 'form-control',
			'placeholder' => 'Last Name',
		);
		$this->data['term_conditions'] = array(
			'name' => 'term_conditions',
			'id' => 'term_conditions',
			'type' => 'text',
			'value' => $this->form_validation->set_value('term_conditions'),
			'class' => 'form-control',
			'placeholder' => 'Enter Terms & Conditions Here',
		);
		$this->data['identity'] = array(
			'name' => 'identity',
			'id' => 'identity',
			'type' => 'text',
			'value' => $this->form_validation->set_value('identity'),
			'class' => 'form-control',
			'placeholder' => 'Identity',
		);
		$this->data['email'] = array(
			'name' => 'email',
			'id' => 'email',
			'type' => 'text',
			'value' => $this->form_validation->set_value('email'),
			'class' => 'form-control',
			'placeholder' => 'Email',
		);
		$this->data['company'] = array(
			'name' => 'company',
			'id' => 'company',
			'type' => 'text',
			'value' => $this->form_validation->set_value('company'),
			'class' => 'form-control',
			'placeholder' => 'Company',
		);
		$this->data['phone'] = array(
			'name' => 'phone',
			'id' => 'phone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('phone'),
			'class' => 'form-control',
			'placeholder' => 'Phone',
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id' => 'password',
			'type' => 'password',
			'value' => $this->form_validation->set_value('password'),
			'class' => 'form-control',
			'placeholder' => 'Password',
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id' => 'password_confirm',
			'type' => 'password',
			'value' => $this->form_validation->set_value('password_confirm'),
			'class' => 'form-control',
			'placeholder' => 'Confirm Password',
		);

		// display the create user form
		// set the flash data error message if there is one
		$this->data['countries'] = $this->locations_model->get_all_countries();
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'shippers' . DIRECTORY_SEPARATOR . 'create_shipper', $this->data);
	}

	public function edit_shipper($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('admin/auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('company', 'Company', 'trim|required');

		if (isset($_POST) && !empty($_POST))
		{
			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'phone' => $this->input->post('phone'),
					'company' => $this->input->post('company'),
				);
				$usermeta = $this->shippers_model->get_user_metadata($id);
				$meta = array();
				foreach ($usermeta as $key => $metadata){
					if(!empty($this->input->post($metadata['meta_key'])))
					{
						$array['meta_key'] = "'".$metadata['meta_key']."'";
						$array['post_values'] = $this->input->post($metadata['meta_key']);
						$meta[] = array(
							'umeta_id'  => $metadata['umeta_id'],
							'user_id' => $id,
							'meta_key' => $metadata['meta_key'],
							'meta_value' => $array['post_values'],
						);
					}
				}
				$this->shippers_model->update_user_metadata($meta);
				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}
				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data))
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					redirect('admin/shippers/edit_shipper/'.$id, 'refresh');
				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('admin/shippers/edit_shipper/'.$id, 'refresh');
				}
			}
		}

		$user = $this->shippers_model->get_user_by_id($id);
		$user_metadata = $this->shippers_model->get_user_metadata($id);
		if(!empty($user_metadata)){
			foreach ($user_metadata as $key => $item) {
				$flight_array = array();
				$itemArr[$item['meta_key']] = $item['meta_value'];
				if($item['meta_key'] == 'country_id')
				{
					$country = $this->locations_model->get_country_by_id($item['meta_value']);
					$itemArr['country_name'] = $country->country_name;
					$itemArr['continent_name'] = $country->continent_name;
				}
				$user_array = $itemArr;
			}
		}
		//echo '<pre>'; print_r($user_array); echo '</pre>'; die();
		$this->data['first_name'] = array(
			'name' => 'first_name',
			'id' => 'first_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('marketplace', !empty($user['first_name']) ? $user['first_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'First Name',
		);
		$this->data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('marketplace', !empty($user['last_name']) ? $user['last_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Last Name',
		);
		$this->data['company'] = array(
			'name' => 'company',
			'id' => 'company',
			'type' => 'text',
			'value' => $this->form_validation->set_value('company', !empty($user['company']) ? $user['company'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Company',
		);
		$this->data['phone'] = array(
			'name' => 'phone',
			'id' => 'phone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('phone', !empty($user['phone']) ? $user['phone'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Phone',
		);
		$this->data['shipper_term_conditions'] = array(
			'name' => 'shipper_term_conditions',
			'id' => 'shipper_term_conditions',
			'type' => 'text',
			'value' => $this->form_validation->set_value('shipper_term_conditions', !empty($user_array['shipper_term_conditions']) ? $user_array['shipper_term_conditions'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Terms & Conditions Here',
		);
		$this->data['email'] = array(
			'name' => 'email',
			'id' => 'email',
			'type' => 'text',
			'value' => $this->form_validation->set_value('email', !empty($user['email']) ? $user['email'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Email',
			'disabled' => 'disabled',
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id' => 'password',
			'type' => 'password',
			'value' => $this->form_validation->set_value('password'),
			'class' => 'form-control',
			'placeholder' => 'Password',
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id' => 'password_confirm',
			'type' => 'password',
			'value' => $this->form_validation->set_value('password_confirm'),
			'class' => 'form-control',
			'placeholder' => 'Confirm Password',
		);

		$this->data['countries'] = $this->locations_model->get_all_countries();
		$this->data['user'] = $user;
		$this->data['user_metadata'] = $user_array;
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'shippers' . DIRECTORY_SEPARATOR . 'edit_shipper', $this->data);
	}

	public function delete_shipper($member_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$this->managers_model->delete_manager($manager_id);
			redirect("admin/managers", 'refresh');
		}
	}

	/**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('admin/auth', 'refresh');
		}
		redirect('admin/auth', 'refresh');
	}
	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')){
			return TRUE;
		}
			return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_content($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}
}
