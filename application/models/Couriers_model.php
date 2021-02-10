<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Couriers_model extends CI_Model
{
	function add_courier($data)
	{
		$this->db->insert('couriers', $data);
	}
	function get_couriers()
	{
		$result = $this->db->get('couriers');
		return $result->result();
	}
	function get_courier($courier_id)
	{
		$this->db->where('courier_id', $courier_id);
		$result = $this->db->get('couriers');
		return $result->row();
	}
	function update_courier($courier_id, $data)
	{
		$this->db->where('courier_id', $courier_id);
		$this->db->update('couriers', $data);
	}
	function delete_courier_by_id($courier_id)
	{
		$this->db->where('courier_id', $courier_id);
		$this->db->delete('couriers');
	}
	function get_courier_by_id($courier_id)
	{
		$this->db->where('courier_id', $courier_id);
		$result = $this->db->get('couriers');
		return $result->row_array();
	}
}
