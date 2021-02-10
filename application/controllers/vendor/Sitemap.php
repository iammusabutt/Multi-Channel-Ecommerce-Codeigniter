<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Sitemap extends Backend_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->load->model('sitemap_model');

		$this->lang->load('auth');
	}
	public function generate_sitemap()
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
			// APPPATH will automatically figure out the correct path
			include APPPATH.'libraries/SitemapPHP/Sitemap.php';
	
			$this->load->library('session');
			// your website url
			$sitemap = new Sitemap('http://localhost/tickets/');
			//echo '<pre>'; print_r($sitemap); echo '</pre>'; die();
	
			// This will also need to be set by you. 
			// the full server path to the sitemap folder 
			$sitemap->setPath('C:\xampp\htdocs\tickets/');
	
			// the name of the file that is being written to
			$sitemap->setFilename('sitemap');
	
			// etc etc etc 
			
			$destinations = $this->sitemap_model->get_destination_slugs();
			foreach ($destinations as $destination) {
				$sitemap->addItem('/destinations/' . $destination['slug'], '0.6', 'weekly', $post['created_at']);
			}
			$sitemap->createSitemapIndex('http://localhost/tickets/', 'Today');
		}
	
	}
}
