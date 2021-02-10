<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends User_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->load->model('users_model');
		$this->load->model('marketplaces_model');
		$this->load->model('locations_model');
		$this->load->model('currencies_model');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	public function index()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('user/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 3)) // remove this elseif if you want to enable this for non-admins
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('errors' . DIRECTORY_SEPARATOR . 'manager', $this->data);
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
			$this->_render_page('user' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{
		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('user/dashboard', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('user/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->load->model('settings_model');
			$this->data['settings'] = (object)$this->settings_model->get_settings_options();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array(
				'name' => 'identity',
				'id' => 'identity',
				'class' => 'form-control',
				'required' => '',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				'Placeholder' => 'Username',
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id' => 'password',
				'class' => 'form-control',
				'required' => '',
				'type' => 'password',
				'Placeholder' => 'Password',
			);
			$this->load->view('user/auth/login', $this->data);
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('user/login', 'refresh');
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
			redirect('user/login', 'refresh');
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
				'placeholder' => 'Old Password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
				'placeholder' => 'New Password',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class' => 'form-control',
				'placeholder' => 'Confirm Password',
			);
			$this->data['user_id'] = array(
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
				'class' => 'form-control',
				'placeholder' => 'User',
			);

			// render
			$this->_render_page('user' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'change_password', $this->data);
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
				redirect('user/change_password', 'refresh');
			}
		}
	}

	
	/**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->in_group($group = 3)){
			redirect('user/auth', 'refresh');
		}
		redirect('user/auth', 'refresh');
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */

	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');
		
		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('user/login', 'refresh');
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
					redirect('user/edit_user/'.$id, 'refresh');

				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					redirect('user/edit_user/'.$id, 'refresh');
				}
			}
		}

		$member = $this->users_model->get_user_by_id($id);
		$member_metadata = $this->users_model->get_user_metadata($id);
		if(!empty($member_metadata)){
			foreach ($member_metadata as $key => $item) {
				$flight_array = array();
				$itemArr[$item['meta_key']] = $item['meta_value'];
				if($item['meta_key'] == 'country_id')
				{
					$country = $this->locations_model->get_country_by_id($item['meta_value']);
					$itemArr['country_name'] = $country->country_name;
					$itemArr['continent_name'] = $country->continent_name;
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
					$currency = $this->marketplaces_model->get_marketplace_by_id($item['meta_value']);
					$itemArr['marketplace_name'] = isset($currency['marketplace_name']);
					//echo '<pre>'; print_r($currency); echo '</pre>'; die();
				}
				$member_array = $itemArr;
			}
		}
		//echo '<pre>'; print_r($member_array); echo '</pre>'; die();
		$this->data['first_name'] = array(
			'name' => 'first_name',
			'id' => 'first_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('marketplace', !empty($member['first_name']) ? $member['first_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Your First Name Here',
		);
		$this->data['last_name'] = array(
			'name' => 'last_name',
			'id' => 'last_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('marketplace', !empty($member['last_name']) ? $member['last_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Your Last Name Here',
		);
		$this->data['brand_name'] = array(
			'name' => 'brand_name',
			'id' => 'brand_name',
			'type' => 'text',
			'value' => $this->form_validation->set_value('brand_name', !empty($member_array['brand_name']) ? $member_array['brand_name'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Your Brand Name Here',
		);
		$this->data['order_prefix'] = array(
			'name' => 'order_prefix',
			'id' => 'order_prefix',
			'type' => 'text',
			'value' => $this->form_validation->set_value('order_prefix', !empty($member_array['order_prefix']) ? $member_array['order_prefix'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Order Prefix Here',
		);
		$this->data['phone'] = array(
			'name' => 'phone',
			'id' => 'phone',
			'type' => 'text',
			'value' => $this->form_validation->set_value('phone', !empty($member['phone']) ? $member['phone'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Phone',
		);
		$this->data['share_percentage'] = array(
			'name' => 'share_percentage',
			'id' => 'share_percentage',
			'type' => 'text',
			'value' => $this->form_validation->set_value('share_percentage', !empty($member_array['share_percentage']) ? $member_array['share_percentage'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Share Holder Percentage',
		);
		$this->data['term_conditions'] = array(
			'name' => 'term_conditions',
			'id' => 'term_conditions',
			'type' => 'text',
			'value' => $this->form_validation->set_value('term_conditions', !empty($member_array['term_conditions']) ? $member_array['term_conditions'] : ""),
			'class' => 'form-control',
			'placeholder' => 'Enter Terms & Conditions Here',
		);
		$this->data['email'] = array(
			'name' => 'email',
			'id' => 'email',
			'type' => 'text',
			'value' => $this->form_validation->set_value('email', !empty($member['email']) ? $member['email'] : ""),
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
		$this->data['member'] = $member;
		$this->data['member_metadata'] = $member_array;
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		$this->_render_page('user' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'edit_user', $this->data);
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
