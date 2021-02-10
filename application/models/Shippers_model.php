<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Shippers_model extends CI_Model
{
	function add_user_metadata($user_metadata)
	{
		$this->db->insert_batch('usermeta', $user_metadata);
	}

	function update_user_metadata($meta)
	{
		$this->db->update_batch('usermeta', $meta, 'umeta_id');
	}

	function get_user_by_id($user_id)
	{
		$this->db->select('users.id, users.first_name, users.last_name, users.phone, users.email, users.company');
		$this->db->where('id', $user_id);
		$result = $this->db->get('users');
		return $result->row_array();
	}

	function get_user_metadata($user_id)
	{
		$this->db->where('user_id', $user_id);
		$result = $this->db->get('usermeta');
		return $result->result_array();
	}
}
