<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Vendors extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('vendors_model');
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
			redirect('admin/auth/login', 'refresh');
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
			$this->data['users'] = $this->ion_auth->users(4)->result();
			//echo '<pre>'; print_r($this->data['users']); echo '</pre>'; die();
			foreach ($this->data['users'] as $k => $user)
			{
				$notifications = $this->vendors_model->get_notifications();
				foreach ($notifications as $notification)
				{
					if($notification['recipient_id'] == $user->id)
					{
						$args = array(
							'sender_id' => NULL,
							'recipient_id' => $user->id,
							'notification_type' => 'connection_request',
							'notification_status' => 'pending',
							'row' => TRUE,
						);
						$notification_status = $this->vendors_model->get_notification($args)['notification_status'];
						//echo '<pre>'; print_r($notification_status); echo '</pre>'; die();
						$this->data['users'][$k]->connection_status = $notification_status;
						$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
					}
				}
				//$this->users_model->add_role_relationships($relation_data);
			}
			//echo '<pre>'; print_r($this->data); echo '</pre>'; die();
			$this->_render_page('member' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}
	
	public function connect()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 2)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$member_id = $this->session->userdata('user_id');
			$vendor_id = $this->input->get('identity', TRUE);
			if(isset($vendor_id))
			{
				$notification_data = 'has send you a connection request';
				$this->send_notification('add', $member_id , $vendor_id, 'connection_request', $notification_data, 'pending');
				redirect('member/vendors', 'refresh');
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
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

	public function send_notification($operation, $sender, $receiver, $type, $notification_data, $status)
	{
		$timezone  = 'UP5';
		$gmt_time = local_to_gmt(time(), $timezone);
		$local_time = gmt_to_local($gmt_time, $timezone);
		$args = array(
			'sender_id' => $sender,
			'recipient_id' => $receiver,
			'notification_type' => $type,
			'notification_data' => $notification_data,
			'notification_status' => $status,
			'viewed_status' => 1,
			'created' => $local_time ,
		);
		if($operation == 'add'){
			$this->vendors_model->add_notification($args);
		} else {
			$this->vendors_model->update_notification($args);
		}
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