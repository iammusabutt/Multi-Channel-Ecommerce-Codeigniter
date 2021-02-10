<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends Frontend_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('url', 'language'));
		$this->load->model('flights_model');
	}
	public function index()
	{
		$flights = $this->flights_model->get_flights_list();
		foreach($flights as $terminal) {
			$flight_from = $terminal['terminal_from_code'];
			$flight_to = $terminal['terminal_to_code'];
			$url = $this->flight_url($flight_from, $flight_to);
			$terminalArr[] = $url;
		}
		//echo '<pre>'; print_r($terminalArr); echo '</pre>'; die();
		$this->data['flight_url'] = $terminalArr;
		$this->data['sitemap_url'] = base_url()."assets/css/sitemap.xsl";
		$this->load->view('sitemap/sitemap_index', $this->data);
	}
	public function flights()
	{
		$flights = $this->flights_model->get_flights_list();
		foreach($flights as $terminal) {
			$flight_from = $terminal['terminal_from_code'];
			$flight_to = $terminal['terminal_to_code'];
			$url = $this->flight_url($flight_from, $flight_to);
			$terminalArr[] = $url;
		}
		//echo '<pre>'; print_r($terminalArr); echo '</pre>'; die();
		$this->data['flight_url'] = $terminalArr;
		$this->data['sitemap_url'] = base_url()."assets/css/sitemap.xsl";
		$this->load->view('sitemap/sitemap_flights', $this->data);
	}
	function flight_url($flight_from, $flight_to)
	{
		$from = $this->flights_model->get_flight_from_city_by_code($flight_from);
		$to = $this->flights_model->get_flight_to_city_by_code($flight_to);
		$from_id = $from->id;
		$to_id = $to->id;
		$destination_flights = $this->flights_model->destination_flights($from_id, $to_id);
		if(count($destination_flights) == 0)
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('front' . DIRECTORY_SEPARATOR . 'bookings' . DIRECTORY_SEPARATOR . 'index', $this->data);
		}
		else
		{
			if ($destination_flights) {
				$flight_id = array();
				for ($i = 0; $i < count($destination_flights); $i++) {
						$flight_id[] = $destination_flights[$i]['id'];
				}
			}
			//echo '<pre>'; print_r($flight_id); echo '</pre>'; die();
			$new_array = [];
			if(!empty($flight_id)){
				foreach ($flight_id as $value) {
					$get_flights_from = $this->flights_model->destination_flights_from($value, $from_id);
					$get_flights_to = $this->flights_model->destination_flights_to($value, $to_id);
					$tempArr['flight_from'] = !empty($get_flights_from['flight_from']) ? $get_flights_from['flight_from'] : NULL;
					$tempArr['flight_from_code'] = !empty($get_flights_from['flight_from_code']) ? $get_flights_from['flight_from_code'] : NULL;
					$tempArr['flight_to'] = !empty($get_flights_to['flight_to']) ? $get_flights_to['flight_to'] : NULL;
					$tempArr['flight_to_code'] = !empty($get_flights_to['flight_to_code']) ? $get_flights_to['flight_to_code'] : NULL;
					$new_array[] = $tempArr;
				}
			}
			$combined_routes = array_replace_recursive($destination_flights, $new_array);
			$array_values = array_values($combined_routes);
			$all_routes = array_shift($array_values);
			
			$settings = (object)$this->settings_model->get_settings_options();
			//echo '<pre>'; print_r($all_routes); echo '</pre>'; die();
			$title = 'Cheap Flights from '.$all_routes['flight_from'].' to '.$all_routes['flight_to'].' from '.$settings->currency_unit.''.$all_routes['flight_price'].' - '.$settings->site_title.'';
			return $title;
		}
	}
}
