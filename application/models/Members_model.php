<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Members_model extends CI_Model
{
	function add_member_metadata($user_metadata)
	{
		$this->db->insert_batch('usermeta', $user_metadata);
	}

	function update_member_metadata($meta)
	{
		$this->db->update_batch('usermeta', $meta, 'umeta_id');
	}

	function get_member_by_id($user_id)
	{
		$this->db->select('users.id, users.first_name, users.last_name, users.phone, users.company, users.email');
		$this->db->where('id', $user_id);
		$result = $this->db->get('users');
		return $result->row_array();
	}

	function get_member_metadata($user_id)
	{
		$this->db->where('user_id', $user_id);
		$result = $this->db->get('usermeta');
		return $result->result_array();
	}
	function get_product_images($object_id)
	{
		$this->db->where('product_id', $object_id);
		$this->db->order_by('id', 'ASC');
		$result = $this->db->get('product_images');
		return $result->result();
	}

	function add_role_taxonomy($taxonomy_data)
	{
		$this->db->insert_batch('role_taxonomy', $taxonomy_data);
	}
}
