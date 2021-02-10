<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Users extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('members_model');
		$this->load->model('users_model');
		$this->load->model('currencies_model');
		$this->load->model('locations_model');
		$this->load->model('marketplaces_model');
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
			$this->data['users'] = $this->ion_auth->users(3)->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('admin/auth', 'refresh');
		}

		$admin_id = $this->session->userdata('user_id');
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		}
		else
		{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
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
			$member_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $groups = array(2));
			$taxonomy_data = array(
				array(
					'role_id' => $member_id,
					'taxonomy' => 'member_user',
					'parent' => '0',
				),
				array(
					'role_id' => $member_id,
					'taxonomy' => 'member_product',
					'parent' => '0',
				),
			);
			$this->members_model->add_role_taxonomy($taxonomy_data);
			$user_metadata = array(
				array(
					'user_id' => $member_id,
					'meta_key' => 'member_address',
					'meta_value' => $this->input->post('member_address'),
				),
			);
			$this->users_model->add_user_metadata($user_metadata);
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("admin/users", 'refresh');
		}

		$this->data['first_name'] = array(
			'name' => 'first_name',
			'id' => 'first_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('first_name'),
			'class' => 'form-control',
			'placeholder' => 'First Name (User)',
		);
		$this->data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('last_name'),
			'class' => 'form-control',
			'placeholder' => 'Last Name (User)',
		);
		$this->data['company'] = array(
			'name' => 'company',
			'id' => 'company',
			'type' => 'text',
			'value' => $this->form_validation->set_value('company'),
			'class' => 'form-control',
			'placeholder' => 'Company Name',
		);
		$this->data['member_address'] = array(
			'name' => 'member_address',
			'id' => 'member_address',
			'type' => 'text',
			'value' => $this->form_validation->set_value('member_address'),
			'class' => 'form-control',
			'placeholder' => 'Address',
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
		$this->data['phone'] = array(
			'name' => 'phone',
			'id' => 'phone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('phone'),
			'class' => 'form-control',
			'placeholder' => 'Phone Number',
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
		$this->data['currencies'] = $this->currencies_model->get_currencies();
		$this->data['marketplaces'] = $this->marketplaces_model->get_marketplaces();
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
	}

	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('admin/auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();
		$tables = $this->config->item('tables', 'ion_auth');

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');

		if (isset($_POST) && !empty($_POST))
		{
			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
				$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'phone' => $this->input->post('phone'),
					'company' => $this->input->post('company'),
					'email' => strtolower($this->input->post('email')),
				);

				$usermeta = $this->users_model->get_user_metadata($id);
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
				//echo '<pre>'; print_r($meta); echo '</pre>'; die();
				$this->users_model->update_user_metadata($meta);
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
					redirect('admin/members/edit_member/'.$id, 'refresh');
				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('admin/members/edit_member/'.$id, 'refresh');
				}
			}
		}
		$member = $this->members_model->get_member_by_id($id);
		$member_metadata = $this->members_model->get_member_metadata($id);
		if(!empty($member_metadata)){
			foreach ($member_metadata as $key => $item) {
				$flight_array = array();
				$itemArr[$item['meta_key']] = $item['meta_value'];
				$member_array = $itemArr;
			}
			$this->data['member_metadata'] = $member_array;
		}
		$this->data['first_name'] = array(
			'name' => 'first_name',
			'id' => 'first_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('first_name', !empty($member['first_name']) ? $member['first_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'First Name (Member))',
		);
		$this->data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('last_name', !empty($member['last_name']) ? $member['last_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Last Name (Member)',
		);
		$this->data['company'] = array(
			'name' => 'company',
			'id' => 'company',
			'type' => 'text',
			'value' => $this->form_validation->set_value('company', !empty($member['company']) ? $member['company'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Company Name',
		);
		$this->data['member_address'] = array(
			'name' => 'member_address',
			'id' => 'member_address',
			'type' => 'text',
			'value' => $this->form_validation->set_value('member_address', !empty($member_array['member_address']) ? $member_array['member_address'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Address',
		);
		$this->data['phone'] = array(
			'name' => 'phone',
			'id' => 'phone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('phone', !empty($member['phone']) ? $member['phone'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Phone Number',
		);
		$this->data['email'] = array(
			'name' => 'email',
			'id' => 'email',
			'type' => 'text',
			'value' => $this->form_validation->set_value('email', !empty($member['email']) ? $member['email'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Email',
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
		$this->data['member'] = $member;
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->_render_page('admin' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'edit_user', $this->data);
	}

	public function delete_user($member_id)
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
			$products = $this->users_model->get_objects('product', NULL, $member_id);
			if(!empty($products)){
				foreach($products as $product){
					$this->delete_product($product['object_id']);
				}
			}
			$users = $this->users_model->get_role_joint($member_id, $taxonomy = 'member_user', $table = 'users', $column = 'id', $is_object = FALSE);
			foreach($users as $user){
				$objects = $this->users_model->get_objects('order', NULL, $user['joint_id']);
				//echo '<pre>'; print_r($objects); echo '</pre>'; die();
				$deletearray = array();
				foreach ($objects as $object)
				{
					$this->users_model->delete_object($object['object_id']);
					$this->users_model->delete_orders($object['order_id']);
					$this->users_model->delete_ordermeta($object['order_id']);
				}
				$this->users_model->delete_user($user['joint_id']);
				$this->users_model->delete_usermeta($user['joint_id']);
				$this->users_model->delete_role_taxonomy($user['joint_id']);
				$this->users_model->delete_role_relationships($user['joint_id']);
			}
			$this->users_model->delete_user($member_id);
			$this->users_model->delete_role_taxonomy($member_id);
			redirect("admin/users", 'refresh');
		}
	}
	
	private function delete_product($object_id)
	{		
		if(!empty($object_id))
		{
			$variations = $this->products_model->get_objects('product_variation', $object_id, NULL);
			foreach ($variations as $key => $value){
				$this->products_model->delete_object($value['object_id']);
				$this->products_model->delete_objectmeta_by_object_id($value['object_id']);
			}
			$product_image = $this->products_model->get_product_image_by_product_id($object_id);
			if(!empty($product_image))
			{
				$id = $product_image->id;
				$path = $product_image->path;
				$thumb =$product_image->thumb;
				$this->products_model->delete_product_image($id, $path, $thumb);
			}
			$this->products_model->delete_term_relationship_by_object_id($object_id);
			$this->products_model->delete_object($object_id);
			$objects = $this->products_model->get_objectmeta($object_id);
			$deletearray = array();
			foreach ($objects as $key => $value)
			{
				$ometa_id = $value['ometa_id'];
				$this->products_model->delete_objectmeta($ometa_id);
			}
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
