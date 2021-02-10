<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Users_model extends CI_Model
{
	function add_user_metadata($user_metadata)
	{
		$this->db->insert_batch('usermeta', $user_metadata);
	}

	function update_user_metadata($meta)
	{
		$this->db->update_batch('usermeta', $meta, 'umeta_id');
	}

	function get_objects($type, $parent, $author)
	{
		//echo '<pre>'; print_r($author); echo '</pre>'; die();
		$this->db->select('objects.*, users.company AS object_author, orders.*');
		$this->db->join('users', 'users.id = objects.object_author', 'left');
		if ($type = 'order')
        {	
			$this->db->join('orders', 'orders.object_id = objects.object_id', 'inner');
		}
		if ($author != NULL)
        {
			$this->db->where('object_author', $author);
        }
		if ($parent != NULL)
        {
			$this->db->where('object_parent', $parent);
        }
		$this->db->where('object_type', $type);
		$result = $this->db->get('objects');
		return $result->result_array();
	}
	function delete_object($object_id)
	{
		$this->db->where('object_id', $object_id);
		$this->db->delete('objects');
	}
	function get_objectmeta($object_id)
	{
		$this->db->where('object_id', $object_id);
		$result = $this->db->get('objectmeta');
		return $result->result_array();
	}
	function delete_objectmeta($ometa_id)
	{
		$this->db->where('ometa_id', $ometa_id);
		$this->db->delete('objectmeta');
	}
	
	function delete_orders($order_id)
	{
		$this->db->where('order_id', $order_id);
		$this->db->delete('orders');
	}
	function delete_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete('users');
	}
	function delete_usermeta($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('usermeta');
	}
	function delete_role_relationships($user_id)
	{
		$this->db->where('joint_id', $user_id);
		$this->db->delete('role_relationships');
	}
	function delete_role_taxonomy($user_id)
	{
		$this->db->where('role_id', $user_id);
		$this->db->delete('role_taxonomy');
	}
	function get_ordermeta($order_id)
	{
		$this->db->where('order_id', $order_id);
		$result = $this->db->get('ordermeta');
		return $result->result_array();
	}
	function delete_ordermeta($order_id)
	{
		$this->db->where('order_id', $order_id);
		$this->db->delete('ordermeta');
	}
	function get_user_by_id($user_id)
	{
		$this->db->select('users.id, users.first_name, users.last_name, users.phone, users.email');
		$this->db->where('id', $user_id);
		$result = $this->db->get('users');
		return $result->row_array();
	}
	function get_users_by_member($member_id)
	{
		$this->db->join('users_members', 'users_members.user_id = users.id', 'inner');
		$this->db->where('member_id', $member_id);
		$result = $this->db->get('users');
		return $result->result();
	}

	function get_role_joint($role_id, $taxonomy, $table, $column, $is_object)
	{
		$this->db->join('role_taxonomy', 'role_taxonomy.role_taxonomy_id = role_relationships.role_taxonomy_id', 'inner');
		$this->db->join($table, $table.'.'.$column.' = role_relationships.joint_id', 'inner');
		if($is_object == TRUE){
			$this->db->join('objects', 'objects.object_id = orders.object_id', 'inner');
		}
		$this->db->where('role_id', $role_id);
		$this->db->where('taxonomy', $taxonomy);
		$result = $this->db->get('role_relationships');
		return $result->result_array();
	}
	function get_role_joint_by_date_range($role_id, $taxonomy, $table, $column, $is_object, $from_date, $to_date)
	{
		$this->db->join('role_taxonomy', 'role_taxonomy.role_taxonomy_id = role_relationships.role_taxonomy_id', 'inner');
		$this->db->join($table, $table.'.'.$column.' = role_relationships.joint_id', 'inner');
		if($is_object == TRUE){
			$this->db->join('objects', 'objects.object_id = orders.object_id', 'inner');
		}
		$this->db->where('role_id', $role_id);
		$this->db->where('taxonomy', $taxonomy);
		$this->db->where('object_date >', $from_date);
		$this->db->where('object_date <', $to_date);
		$result = $this->db->get('role_relationships');
		return $result->result_array();
	}

	function get_user_metadata($user_id)
	{
		$this->db->where('user_id', $user_id);
		$result = $this->db->get('usermeta');
		return $result->result_array();
	}
	
	function add_users_members($users_members)
	{
		$this->db->insert('users_members', $users_members);
	}

	function add_role_taxonomy($taxonomy_data)
	{
		$this->db->insert_batch('role_taxonomy', $taxonomy_data);
	}

	function get_role_relationship_acol($data)
	{
		$this->db->where('joint_id', $data['joint_id']);
		$this->db->where('role_taxonomy_id', $data['role_taxonomy_id']);
		$result = $this->db->get('role_relationships');
		return $result->result_array();
	}
	function add_role_relationships($relation_data)
	{
		$this->db->insert('role_relationships', $relation_data);
	}

	function get_role_taxonomy($args)
	{
		if($args['role_id'] != NULL){
			$this->db->where('role_id', $args['role_id']);
		}
		if($args['taxonomy'] != NULL){
			$this->db->where('taxonomy', $args['taxonomy']);
		}
		if($args['parent'] != NULL){
			$this->db->where('parent', $args['parent']);
		}
		$result = $this->db->get('role_taxonomy');
		if($args['single'] == TRUE){
			return $result->row_array();
		} else {
			return $result->result_array();
		}
	}
	function get_users_orders_by_member($member_id, $from_date, $to_date)
	{
		$this->db->select('objects.*, role_taxonomy.*, orders.*, users.id, users.first_name, users.last_name, users.phone, users.email');
		$this->db->join('role_taxonomy', 'role_taxonomy.role_taxonomy_id = role_relationships.role_taxonomy_id', 'left');
		$this->db->join('objects', 'objects.object_author = role_relationships.joint_id', 'left');
		$this->db->join('orders', 'orders.object_id = objects.object_id', 'left');
		$this->db->join('users', 'users.id = role_relationships.joint_id');
		$this->db->where('object_type', 'order');
		$this->db->where('object_status !=', 'delivered');
		$this->db->where('taxonomy', 'member_user');
		$this->db->where('role_id', $member_id);
		$this->db->where('object_date >', $from_date);
		$this->db->where('object_date <', $to_date);
		$result = $this->db->get('role_relationships');
		return $result->result_array();
	}
	
}
