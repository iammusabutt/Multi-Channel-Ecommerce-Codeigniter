<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Notifications extends Vendor_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('shippers_model');
		$this->load->model('vendors_model');
		$this->load->model('locations_model');
		$this->load->model('users_model');
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
		else if (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$vendor_id = $this->session->userdata('user_id');
			$args = array(
				'sender_id' => NULL,
				'recipient_id' => $vendor_id,
				'notification_type' => 'connection_request',
				'notification_status' => 'pending',
				'row' => FALSE,
			);
			$this->data['notifications'] = $this->vendors_model->get_notification($args);
			//echo '<pre>'; print_r($this->data['notifications']); echo '</pre>'; die();
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('vendor' . DIRECTORY_SEPARATOR . 'notifications' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
	}

	public function connection_request()
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('admin/auth/login', 'refresh');
		}
		else if (!$this->ion_auth->in_group($group = 4)) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_error('errors' . DIRECTORY_SEPARATOR . '401', $this->data);
		}
		else
		{
			$vendor_id = $this->session->userdata('user_id');
			$member_id = $this->input->get('member', TRUE);
			$response = $this->input->get('response', TRUE);
			//echo '<pre>';print_r($response);echo '</pre>'; die();
			if(isset($vendor_id))
			{
				if(isset($response) && $response == 'accept')
				{
					$notification_data = 'has send you a connection request';
					$this->send_notification('update', $member_id , $vendor_id, 'connection_request', $notification_data, 'accepted');
				}
				else if (isset($response) && $response == 'reject')
				{
					$notification_data = 'has send you a connection request';
					$this->send_notification('update', $member_id , $vendor_id, 'connection_request', $notification_data, 'rejected');
				}
				redirect('vendor/notifications', 'refresh');
			}
			else
			{
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
				$this->_render_error('errors' . DIRECTORY_SEPARATOR . '404', $this->data);
			}
		}
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
