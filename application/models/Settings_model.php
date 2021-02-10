<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Settings_model extends CI_Model
{
	function add_setting($settings_data)
	{
		$this->db->insert('settings', $settings_data);
	}
	function get_settings()
	{
		$result = $this->db->get('settings');
		return $result->result_array();
	}
	function update_setting($id, $settings_data)
	{
		$this->db->where('id', $id);
		$this->db->update('settings', $settings_data);
	}
	function delete_post_type_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('settings');
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
	function add_credentials($data)
	{
		$result = $this->db->insert('api_credentials', $data);
		return $this->db->insert_id();
	}
	function get_credential($user_id)
	{
		$this->db->where('user_id', $user_id);
		$result = $this->db->get('api_credentials');
		return $result->row_array();
	}
	function update_credentials($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('api_credentials', $data);
	}
	
	function compute_credentials($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('api_credentials', $data);
		$res = $this->db->get('api_credentials');
		if ($res->num_rows() > 0) {
            $this->db->update('api_credentials', $data);
		} else {
            $result = $this->db->insert('api_credentials', $data);
			return $this->db->insert_id();
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
	function get_post_types_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->where('setting_name', 'post_type');
		$result = $this->db->get('settings');
		return $result->row_array();
	}
	function get_object_record()
	{
		$result = $this->db->get('settings');
		return $result->result_array();
	}
	function get_objectmeta_record($object_id, $meta_key, $meta_table)
	{
		$this->db->where('object_id', $object_id);
		if ($meta_key != NULL)
        {
			$this->db->where('meta_key', $meta_key);
        }
		$result = $this->db->get($meta_table);
		foreach($result->result() as $row) {
			$options[$row->meta_key] = $row->meta_value;
		}
		if(!empty($options)){
			return $options;
		}
	}
	function add_objectmeta($objectmeta)
	{
		$this->db->insert_batch('objectmeta', $objectmeta);
	}
	function delete_objectmeta_by_meta_key($meta_key, $meta_table)
	{
		$this->db->where('meta_key', $meta_key);
		$this->db->delete($meta_table);
	}
	function get_metadata($primary_key, $id, $table)
	{
		$this->db->where($primary_key, $id);
		$result = $this->db->get($table);
		return $result->result_array();
	}
	function get_metadata_by_key($meta_key, $primary_key, $id, $table)
	{
		$this->db->where('meta_key', $meta_key);
		$this->db->where($primary_key, $id);
		$result = $this->db->get($table);
		return $result->row_array();
	}
	
	function add_metadata($table, $meta_array)
	{
		$this->db->insert_batch($table, $meta_array);
	}
	function update_metadata($primary_key, $id, $table, $meta_array, $meta_primary_key)
	{
		$this->db->where($primary_key, $id);
		$this->db->update_batch($table, $meta_array, $meta_primary_key);
	}
}