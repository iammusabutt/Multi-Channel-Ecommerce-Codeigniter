<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Marketplaces_model extends CI_Model
{
	function add_marketplace($data)
	{
		$this->db->insert('marketplaces', $data);
	}
	function get_marketplaces()
	{
		$result = $this->db->get('marketplaces');
		return $result->result();
	}
	function update_marketplace($marketplace_id, $data)
	{
		$this->db->where('marketplace_id', $marketplace_id);
		$this->db->update('marketplaces', $data);
	}
	function delete_marketplace_by_id($marketplace_id)
	{
		$this->db->where('marketplace_id', $marketplace_id);
		$this->db->delete('marketplaces');
	}
	function get_marketplace_by_id($marketplace_id)
	{
		$this->db->where('marketplace_id', $marketplace_id);
		$result = $this->db->get('marketplaces');
		return $result->row_array();
	}
}
