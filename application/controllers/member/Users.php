<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Users extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
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
			redirect('member/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
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
			$role_id = $this->session->userdata('user_id');
			$this->data['users'] = $this->users_model->get_role_joint($role_id, $taxonomy = 'member_user', $table = 'users', $column = 'id', $is_object = FALSE);
			//echo '<pre>'; print_r($this->data['users']); echo '</pre>'; die();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]['groups'] = $this->ion_auth->get_users_groups($user['id'])->result();
			}

			$this->_render_page('member' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	/**
	 * Change password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('member/auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
				'class' => 'form-control',
				'placeholder' => 'Enter Email',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
				'placeholder' => 'Enter Email',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
				'placeholder' => 'Enter Email',
			);
			$this->data['user_id'] = array(
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
				'class' => 'form-control',
				'placeholder' => 'Enter Email',
			);

			// render
			$this->_render_page('member' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('member/auth/change_password', 'refresh');
			}
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("member/auth", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("member/auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group($group = 2))
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}

		$id = (int)$id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');
		
		if ($this->form_validation->run() === FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('member' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					return show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
					
				}
			}

			//echo '<pre>',print_r($this->input->post('confirm')),'</pre>'; die();
			// redirect them back to the auth page
			redirect('member/auth/all_users', 'refresh');
		}
	}
	
	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group($group = 2))
		{
			redirect('member/auth', 'refresh');
		}

		$member_id = $this->session->userdata('user_id');
		//echo '<pre>'; print_r($asdasdasd); echo '</pre>'; die();
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
			);
			$user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $groups = array(3));
			$taxonomy_data = array(
				array(
					'role_id' => $user_id,
					'taxonomy' => 'user_order',
					'parent' => $member_id,
				),
			);
			$this->users_model->add_role_taxonomy($taxonomy_data);
			$args = array(
				'role_id' => $member_id,
				'taxonomy' => 'member_user',
				'parent' => NULL,
				'single' => TRUE,
			);
			$role_taxonomy = $this->users_model->get_role_taxonomy($args);
			//echo '<pre>'; print_r($role_taxonomy); echo '</pre>'; die();
			$relation_data = array(
				'joint_id' => $user_id,
				'role_taxonomy_id' => $role_taxonomy['role_taxonomy_id'],
			);
			$this->users_model->add_role_relationships($relation_data);
			$user_metadata = array(
				array(
					'user_id' => $user_id,
					'meta_key' => 'marketplace_id',
					'meta_value' => $this->input->post('marketplace_id'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'brand_name',
					'meta_value' => $this->input->post('brand_name'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'order_prefix',
					'meta_value' => $this->input->post('order_prefix'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'store_type',
					'meta_value' => $this->input->post('store_type'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'share_percentage',
					'meta_value' => $this->input->post('share_percentage'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'country_id',
					'meta_value' => $this->input->post('country_id'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'currency_id',
					'meta_value' => $this->input->post('currency_id'),
				),
				array(
					'user_id' => $user_id,
					'meta_key' => 'term_conditions',
					'meta_value' => $this->input->post('term_conditions'),
				),
			);
			$this->users_model->add_user_metadata($user_metadata);
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("member/users", 'refresh');
		}

		$this->data['first_name'] = array(
			'name' => 'first_name',
			'id' => 'first_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('first_name'),
			'class' => 'form-control',
			'placeholder' => 'Enter Your First Name Here',
		);
		$this->data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('last_name'),
			'class' => 'form-control',
			'placeholder' => 'Enter Your Last Name Here',
		);
		$this->data['brand_name'] = array(
			'name' => 'brand_name',
			'id' => 'brand_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('brand_name'),
			'class' => 'form-control',
			'placeholder' => 'Enter Your Brand Name Here',
		);
		$this->data['order_prefix'] = array(
			'name' => 'order_prefix',
			'id' => 'order_prefix',
			'type' => 'text',
			'value' => $this->form_validation->set_value('order_prefix'),
			'class' => 'form-control',
			'placeholder' => 'Enter Order Prefix Here',
		);
		$this->data['share_percentage'] = array(
			'name' => 'share_percentage',
			'id' => 'share_percentage',
			'type' => 'text',
			'value' => $this->form_validation->set_value('share_percentage'),
			'class' => 'form-control',
			'placeholder' => 'Enter Share Holder Percentage',
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
		$this->data['currencies'] = $this->currencies_model->get_currencies();
		$this->data['marketplaces'] = $this->marketplaces_model->get_marketplaces();
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->_render_page('member' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'create_user', $this->data);
	}

	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->in_group($group = 2) && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('member/auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

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
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'phone' => $this->input->post('phone'),
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
					redirect('member/users/edit_user/'.$id, 'refresh');

				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('member/users/edit_user/'.$id, 'refresh');
				}
			}
		}

		$user = $this->users_model->get_user_by_id($id);
		$user_metadata = $this->users_model->get_user_metadata($id);
		if(!empty($user_metadata)){
			foreach ($user_metadata as $key => $item) {
				$itemArr[$item['meta_key']] = $item['meta_value'];
				if($item['meta_key'] == 'country_id')
				{
					$country = $this->locations_model->get_country_by_id($item['meta_value']);
					$itemArr['country_name'] = $country->country_name;
					$itemArr['continent_name'] = $country->continent_name;
					//echo '<pre>'; print_r($country); echo '</pre>';
				}
				if($item['meta_key'] == 'currency_id')
				{
					$currency = $this->currencies_model->get_currency_by_id($item['meta_value']);
					$itemArr['currency_name'] = $currency['currency_name'];
					$itemArr['currency_code'] = $currency['currency_code'];
					$itemArr['currency_symbol'] = $currency['currency_symbol'];
					//echo '<pre>'; print_r($currency); echo '</pre>'; die();
				}
				if($item['meta_key'] == 'marketplace_id')
				{
					$marketplace = $this->marketplaces_model->get_marketplace_by_id($item['meta_value']);
					$itemArr['marketplace_name'] = isset($marketplace['marketplace_name']);
					//echo '<pre>'; print_r($item); echo '</pre>'; die();
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
			'placeholder' => 'Enter Your First Name Here',
		);
		$this->data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('marketplace', !empty($user['last_name']) ? $user['last_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Your Last Name Here',
		);
		$this->data['brand_name'] = array(
			'name' => 'brand_name',
			'id' => 'brand_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('brand_name', !empty($user_array['brand_name']) ? $user_array['brand_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Your Brand Name Here',
		);
		$this->data['order_prefix'] = array(
			'name' => 'order_prefix',
			'id' => 'order_prefix',
			'type' => 'text',
			'value' => $this->form_validation->set_value('order_prefix', !empty($user_array['order_prefix']) ? $user_array['order_prefix'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Order Prefix Here',
		);
		$this->data['phone'] = array(
			'name' => 'phone',
			'id' => 'phone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('phone', !empty($user['phone']) ? $user['phone'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Phone',
		);
		$this->data['share_percentage'] = array(
			'name' => 'share_percentage',
			'id' => 'share_percentage',
			'type' => 'text',
			'value' => $this->form_validation->set_value('share_percentage', !empty($user_array['share_percentage']) ? $user_array['share_percentage'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Share Holder Percentage',
		);
		$this->data['term_conditions'] = array(
			'name' => 'term_conditions',
			'id' => 'term_conditions',
			'type' => 'text',
			'value' => $this->form_validation->set_value('term_conditions', !empty($user_array['term_conditions']) ? $user_array['term_conditions'] : ""),
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
		$this->data['currencies'] = $this->currencies_model->get_currencies();
		$this->data['marketplaces'] = $this->marketplaces_model->get_marketplaces();
		$this->data['member'] = $user;
		$this->data['member_metadata'] = $user_array;
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->_render_page('member' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'edit_user', $this->data);
	}

	public function delete_user($member_id)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('member/auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$this->managers_model->delete_manager($manager_id);
			redirect("member/managers", 'refresh');
		}
	}

	public function user_detail($user_id)
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('member/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$this->data['users_accounts'] = $this->users_model->get_users_accounts_by_user_id($user_id); 
			//echo '<pre>'; print_r($this->data['users_accounts']); echo '</pre>'; die();
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('member' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . 'detail', $this->data);

		}
	}
	/**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('member/auth', 'refresh');
		}
		redirect('member/auth', 'refresh');
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
