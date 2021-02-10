<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('settings_model');
	}
	
	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _template_home($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{
		$this->viewdata = (empty($data)) ? $this->data : $data;

		
		$this->data['settings'] = (object)$this->settings_model->get_settings_options();
		$this->load->view('front' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'header-home', $this->data);
		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);
		$this->load->view('front' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'footer', $this->data);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{
		$this->viewdata = (empty($data)) ? $this->data : $data;
		$settings = $this->settings_model->get_post_types();
		if(!empty($settings))
		{
			$head_info = array();
			foreach ($settings as $key => $setting) {
				$unserialized_data = unserialize($setting['setting_value']);
				$type = $this->uri->segment(2);
				if($type == $unserialized_data['post_type_name'])
				{
					$head_info = unserialize($setting['setting_value']);
				}
			}
		}
		//echo '<pre>'; print_r($head_info); echo '</pre>'; die();
		$this->data['head_info'] = $head_info;
		$this->data['settings'] = (object)$this->settings_model->get_settings_options();
		$this->load->view('front' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'header-inner', $this->data);
		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);
		$this->load->view('front' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'footer', $this->data);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}
}