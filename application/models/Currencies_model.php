<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Currencies_model extends CI_Model
{
	function add_currency($data)
	{
		$this->db->insert('currencies', $data);
	}
	function get_currencies()
	{
		$result = $this->db->get('currencies');
		return $result->result();
	}
	function update_currency($currency_id, $data)
	{
		$this->db->where('currency_id', $currency_id);
		$this->db->update('currencies', $data);
	}
	function delete_currency_by_id($currency_id)
	{
		$this->db->where('currency_id', $currency_id);
		$this->db->delete('currencies');
	}
	function get_currency_by_id($currency_id)
	{
		$this->db->where('currency_id', $currency_id);
		$result = $this->db->get('currencies');
		return $result->row_array();
	}
	function get_settings_options()
	{
		$result = $this->db->get('settings');
		foreach($result->result() as $row) {
			$options[$row->setting_name] = $row->setting_value;
		}
		if(!empty($options)){
			return $options;
		}
	}
	function get_post_types()
	{
		$this->db->where('setting_name', 'post_type');
		$result = $this->db->get('settings');
		return $result->result_array();
	}
	function get_post_types_by_type($type)
	{
		$this->db->where('id', $type);
		$this->db->where('setting_name', 'post_type');
		$result = $this->db->get('settings');
		return $result->result_array();
	}
}
