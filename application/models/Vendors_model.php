<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendors_model extends CI_Model
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
		$this->db->select('users.id, users.first_name, users.last_name, users.phone, users.email');
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
	function get_vendors_assigned_to_product($object_id)
	{
		$this->db->select('users.id, users.first_name, users.last_name, users.phone, users.email, users.company');
		$this->db->join('role_taxonomy', 'role_taxonomy.role_id = objects.object_id');
		$this->db->join('role_relationships', 'role_relationships.role_taxonomy_id = role_taxonomy.role_taxonomy_id');
		$this->db->join('users', 'users.id = role_relationships.joint_id');
		$this->db->where('object_id', $object_id);
		$result = $this->db->get('objects');
		return $result->result_array();
	}
	function check_product_type($object_id)
	{
		$this->db->where('objects.object_id', $object_id);
		$result = $this->db->get('objects');
		return $result->row_array();
	}
   	function get_product_images($object_id)
	{
		$this->db->where('product_id', $object_id);
		$this->db->order_by('id', 'ASC');
		$result = $this->db->get('product_images');
		return $result->result();
	}
	function get_connection_status($user_id)
	{
		$this->db->join('role_taxonomy', 'role_taxonomy.role_id = users.id');
		$this->db->join('role_relationships', 'role_relationships.role_taxonomy_id = role_taxonomy.role_taxonomy_id', 'left');
		//$this->db->join('users', 'users.id = role_relationships.joint_id');
		$this->db->where('id', $user_id);
		$result = $this->db->get('users');
		return $result->row_array();
	}
	function add_notification($args){
		$this->db->insert('notifications', $args);
	}

	function update_notification($args){
		if($args['sender_id'] != NULL){
			$this->db->where('sender_id', $args['sender_id']);
		}
		if($args['recipient_id'] != NULL){
			$this->db->where('recipient_id', $args['recipient_id']);
		}
		if($args['notification_type'] != NULL){
			$this->db->where('notification_type', $args['notification_type']);
		}
		$this->db->update('notifications', $args);
	}

	function get_notifications()
	{
		$result = $this->db->get('notifications');
		return $result->result_array();
	}

	function get_notification($args)
	{
		$this->db->select('notifications.*, sender.first_name as sender_name, recipient.first_name as recipient_name');
		$this->db->join('users as sender', 'sender.id = notifications.sender_id');
		$this->db->join('users as recipient', 'recipient.id = notifications.recipient_id');
		if($args['sender_id'] != NULL){
			$this->db->where('sender_id', $args['sender_id']);
		}
		if($args['recipient_id'] != NULL){
			$this->db->where('recipient_id', $args['recipient_id']);
		}
		if($args['notification_type'] != NULL){
			$this->db->where('notification_type', $args['notification_type']);
		}
		$result = $this->db->get('notifications');
		if($args['row'] == TRUE){
			return $result->row_array();
		} else {
			return $result->result_array();
		}
	}
}
