<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('settings_model');
		$this->load->model('products_model');
	}
	
	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{
		$user_id = $this->session->userdata('user_id');
		$this->viewdata = (empty($data)) ? $this->data : $data;

		$settings = $this->settings_model->get_post_types();
		if(!empty($settings))
		{
			$post_type_list = array();
			foreach ($settings as $key => $setting) {
				$post_type_list[] = unserialize($setting['setting_value']);
			}
			$head_info = array();
			foreach ($settings as $key => $setting) {
				$unserialized_data = unserialize($setting['setting_value']);
				$type = $this->uri->segment(3);
				if($type == $unserialized_data['post_type_name'])
				{
					$head_info = unserialize($setting['setting_value']);
				}
			}
		}
		//echo '<pre>'; print_r($data); echo '</pre>'; die();
		$this->data['user'] = $this->session->userdata();
		if(!empty($post_type_list) || !empty($head_info))
		{
			$this->data['post_types_menu'] = $post_type_list;
			$this->data['head_info'] = $head_info;
		}
		$cart_items = $this->products_model->get_cart_items($type = 'product_variation', $user_id, $vendor_id = NULL);
		if(!empty($cart_items))
		{
			$this->data['cart_items'] = $cart_items;
		}
		//echo '<pre>'; print_r($cart_items); echo '</pre>'; die();
		$this->load->view('user' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'header', $this->data);
		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);
		$this->load->view('user' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'footer', $this->data);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}
	public function _render_error($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
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