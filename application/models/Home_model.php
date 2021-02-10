<?php
/**
 * Name:    Posts Model
 * Author:  Ben Edmunds
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model
{
	public function __construct()
    {
		parent::__construct();
	}
	function get_flight_by_from_name($flight_from)
	{
		$this->db->where('terminal_name', $flight_from);
		$result = $this->db->get('terminals');
		return $result->row();
	}
	function get_flight_by_to_name($flight_to)
	{
		$this->db->where('terminal_name', $flight_to);
		$result = $this->db->get('terminals');
		return $result->row();
	}
	function get_relations_by_type($action_type)
	{
		$this->db->where('action_type', $action_type);
		$result = $this->db->get('relations');
		return $result->result_array();
	}
	function get_term_taxonomy_by_block($taxonomy, $block)
	{
		$this->db->join('blocks', 'blocks.term_id = term_taxonomy.term_taxonomy_id', 'left');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'left');
		$this->db->where('taxonomy', $taxonomy);
		$this->db->where('position', $block);
		$result = $this->db->get('term_taxonomy');
		return $result->result_array();
	}
	function get_term_taxonomy_with_block($block, $taxonomy, $position)
	{
		$this->db->join('blocks', 'blocks.term_id = term_taxonomy.term_taxonomy_id', 'left');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'left');
		$this->db->where('blocks.term_id', $block);
		$this->db->where('taxonomy', $taxonomy);
		$this->db->where('position', $position);
		$result = $this->db->get('term_taxonomy');
		return $result->row_array();
	}
	function get_term_relationships($term_taxonomy_id)
	{
		//$this->db->join('term_relationships', 'term_relationships.term_taxonomy_id = blocks.term_id', 'left');
		$this->db->where('term_taxonomy_id', $term_taxonomy_id);
		$result = $this->db->get('term_relationships');
		return $result->result_array();
	}
	
	function get_flights_from_in_terms($object_id, $block)
	{	
		$this->db->select('term_relationships.*, terms.*, flights.id, flights.flight_route, flights.flight_price, airlines.airline_name, classes.aircraft_class_name, protections.protection_name, terminals.terminal_name AS flight_from, terminals.terminal_short_code AS flight_from_code, cities.city_name AS flight_from_city_name');
		$this->db->join('flights', 'flights.id = term_relationships.object_id', 'left');
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id', 'left');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'left');
		$this->db->join('airlines', 'airlines.id = flights.flight_airline', 'inner');
		$this->db->join('classes', 'classes.id = flights.flight_aircraft_class', 'inner');
		$this->db->join('protections', 'protections.id = flights.flight_protection', 'inner');
		$this->db->join('terminals', 'terminals.id = flights.flight_from', 'inner');
		$this->db->join('cities', 'cities.id = terminals.city_id');
		$this->db->join('countries', 'countries.id = cities.country_id');
		$this->db->join('continents', 'continents.id = countries.continent_id');
		$this->db->where('object_id', $object_id);
		$this->db->where('term_relationships.term_taxonomy_id', $block);
		$result = $this->db->get('term_relationships');
		return $result->row_array();
	}
	function get_flights_to_in_terms($object_id, $block)
	{
		$this->db->select('term_relationships.*, terms.*, flights.id, flights.flight_route, flights.flight_price, airlines.airline_name, classes.aircraft_class_name, protections.protection_name, terminals.terminal_name AS flight_to, terminals.terminal_short_code AS flight_to_code, cities.city_name AS flight_to_city_name');
		$this->db->join('flights', 'flights.id = term_relationships.object_id', 'left');
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id', 'left');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'left');
		$this->db->join('airlines', 'airlines.id = flights.flight_airline', 'inner');
		$this->db->join('classes', 'classes.id = flights.flight_aircraft_class', 'inner');
		$this->db->join('protections', 'protections.id = flights.flight_protection', 'inner');
		$this->db->join('terminals', 'terminals.id = flights.flight_to', 'inner');
		$this->db->join('cities', 'cities.id = terminals.city_id');
		$this->db->join('countries', 'countries.id = cities.country_id');
		$this->db->join('continents', 'continents.id = countries.continent_id');
		$this->db->where('object_id', $object_id);
		$this->db->where('term_relationships.term_taxonomy_id', $block);
		$result = $this->db->get('term_relationships');
		return $result->row_array();
	}
}
