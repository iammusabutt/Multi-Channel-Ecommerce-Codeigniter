<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard_model extends CI_Model
{
	public function __construct()
    {
		parent::__construct();
	}
	
	function count_inquiries()
	{
		return $this->db->count_all_results('bookings');
	}
	function count_flights()
	{
		return $this->db->count_all_results('flights');
	}
	function count_airlines()
	{
		return $this->db->count_all_results('airlines');
	}
	function count_destinations()
	{
		return $this->db->count_all_results('destinations');
	}

	function count_packages($type)
	{
		$this->db->where('type', $type);
		return $this->db->count_all_results('packages');
	}
}
