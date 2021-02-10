<?php
/**
 * Name:    Posts Model
 * Author:  Ben Edmunds
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Locations_model extends CI_Model
{
	public function __construct()
    {
		parent::__construct();
	}
	function get_all_cities()
	{
		$this->db->join('cities', 'countries.country_id = cities.country_id');
		$result = $this->db->get('countries');
		return $result->result();
	}
	function get_single_city($id)
	{	
		$this->db->select('*');
		$this->db->from('cities');
		$this->db->where('cities.city_id', $id);
		return $this->db->get()->row();
	}
	function get_city_by_id($city_id)
	{
		$this->db->where('city_id', $city_id);
		$result = $this->db->get('cities');
		return $result->row();
	}
	function add_city($city_data)
	{
		$this->db->insert('cities',$city_data);
	}
	function update_city($city_id, $city_data)
	{
		$this->db->where('city_id', $city_id);
		$this->db->update('cities',$city_data);
	}
	function delete_city($city_data)
	{
		$this->db->where('cities.city_id', $city_data);
		$result = $this->db->delete('cities');
		return $result;
	}
	function get_all_countries()
	{
		$this->db->select('countries.country_id, countries.continent_id, countries.country_code, countries.country_name, continents.continent_code, continents.continent_name');
		$this->db->join('continents', 'continents.continent_id = countries.continent_id');
		$result = $this->db->get('countries');
		return $result->result();
	}
	function get_single_country($country_id)
	{	
		$this->db->select('*');
		$this->db->from('countries');
		$this->db->where('countries.country_id', $country_id);
		return $this->db->get()->row();
	}
	function add_country($country_data)
	{
		$this->db->insert('countries',$country_data);
	}
	function update_country($country_id, $country_data)
	{
		$this->db->where('country_id', $country_id);
		$this->db->update('countries',$country_data);
	}
	function delete_country($id)
	{
		$this->db->where('countries.country_id', $id);
		$result = $this->db->delete('countries');
		return $result;
	}
	function get_country_by_id($country_id)
	{
		$this->db->select('countries.country_id, countries.continent_id, countries.country_code, countries.country_name, continents.continent_code, continents.continent_name');
		$this->db->join('continents', 'continents.continent_id = countries.continent_id');
		$this->db->where('country_id', $country_id);
		$result = $this->db->get('countries');
		return $result->row();
	}
	function get_all_continents()
	{
		$result = $this->db->get('continents');
		return $result->result();
	}
	function get_single_continent($continent_id)
	{
		$this->db->where('continents.continent_id', $continent_id);
		return $this->db->get('continents')->row();
	}
	function get_continent_by_id($continent_id)
	{
		$this->db->where('continent_id', $continent_id);
		$result = $this->db->get('continents');
		return $result->row();
	}
	function add_continent($continent_data)
	{
		$this->db->insert('continents',$continent_data);
	}
	function update_continent($continent_id, $continent_data)
	{
		$this->db->where('continent_id', $continent_id);
		$this->db->update('continents',$continent_data);
	}
	function delete_continent($continent_id)
	{
		$this->db->where('continents.continent_id', $continent_id);
		$result = $this->db->delete('continents');
		return $result;
	}
	function check_terminals_exist_in_city($id)
	{
		$this->db->join('cities', 'cities.city_id = terminals.city_id', 'inner');
		$this->db->where('cities.city_id', $id);
		$result = $this->db->get('terminals');
		return $result->result();
	}
	function check_cities_exist_in_country($id)
	{
		$this->db->join('countries', 'countries.country_id = cities.country_id', 'inner');
		$this->db->where('countries.country_id', $id);
		$result = $this->db->get('cities');
		return $result->result();
	}
	function check_countries_exist_in_continent($id)
	{
		$this->db->join('continents', 'continents.continent_id = countries.continent_id', 'inner');
		$this->db->where('continents.continent_id', $id);
		$result = $this->db->get('countries');
		return $result->result();
	}
}
