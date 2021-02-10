<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouses_model extends CI_Model
{
	function add_warehouse($data)
	{
		$this->db->insert('warehouses', $data);
		return $this->db->insert_id();
	}
	function get_warehouses($type, $name, $location, $author, $status)
	{
		$type			!= NULL ? $this->db->where('warehouse_type', $type) : NULL;
		$name			!= NULL ? $this->db->where('warehouse_name', $name) : NULL;
		$location		!= NULL ? $this->db->where('warehouse_location', $location) : NULL;
		$author			!= NULL ? $this->db->where('warehouse_author', $author) : NULL;
		$status			!= NULL ? $this->db->where('warehouse_status', $status) : NULL;
		$result = $this->db->get('warehouses');
		return $result->result();
	}
	function get_warehouse($warehouse_id, $type, $name, $location, $author, $status)
	{
		$warehouse_id	!= NULL ? $this->db->where('warehouse_id', $warehouse_id) : NULL;
		$type			!= NULL ? $this->db->where('warehouse_type', $type) : NULL;
		$name			!= NULL ? $this->db->where('warehouse_name', $name) : NULL;
		$location		!= NULL ? $this->db->where('warehouse_location', $location) : NULL;
		$author			!= NULL ? $this->db->where('warehouse_author', $author) : NULL;
		$status			!= NULL ? $this->db->where('warehouse_status', $status) : NULL;
		$result = $this->db->get('warehouses');
		return $result->row();
	}
	function get_product_warehouses($variation_id, $vendor_id)
	{
		$this->db->select('warehouses.*');
		$this->db->join('product_warehouses', 'product_warehouses.warehouse_id = warehouses.warehouse_id');
		$this->db->join('objects', 'objects.object_id = product_warehouses.object_id');
		$this->db->where('warehouse_author', $vendor_id);
		$result = $this->db->get('warehouses');
		return $result->result();
	}
	function get_warehouse_with_stock($type, $warehouse_id, $object_id, $vendor_id, $status)
	{
		$this->db->join('warehouses', 'warehouses.warehouse_id = stock.warehouse_id');
		$type			!= NULL ? $this->db->where('warehouse_type', $type) : NULL;
		$warehouse_id	!= NULL ? $this->db->where('warehouses.warehouse_id', $warehouse_id) : NULL;
		$object_id		!= NULL ? $this->db->where('object_id', $object_id) : NULL;
		$vendor_id		!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$status			!= NULL ? $this->db->where('warehouse_status', $status) : NULL;
		$result = $this->db->get('stock');
		return $result->row();
	}
	function get_warehouses_with_stock($type, $warehouse_id, $object_id, $vendor_id, $status)
	{
		$this->db->join('warehouses', 'warehouses.warehouse_id = stock.warehouse_id');
		$type			!= NULL ? $this->db->where('warehouse_type', $type) : NULL;
		$warehouse_id	!= NULL ? $this->db->where('warehouses.warehouse_id', $warehouse_id) : NULL;
		$object_id		!= NULL ? $this->db->where('object_id', $object_id) : NULL;
		$vendor_id		!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$status			!= NULL ? $this->db->where('warehouse_status', $status) : NULL;
		$result = $this->db->get('stock');
		return $result->result();
	}
	function get_record($conditions, $table_name)
	{
		foreach($conditions as $column => $value){
			$value	!= NULL ? $this->db->where($column, $value) : NULL;
		}
		$result = $this->db->get($table_name);
		return $result->row();
	}
	function update_warehouse($warehouse_id, $data)
	{
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->update('warehouses',$data);
	}
	function update_warehouse_default($warehouse_id, $data)
	{
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->update('warehouses',['warehouse_type' => 'default']);
	}
	function delete_warehouse_by_id($warehouse_id)
	{
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->delete('warehouses');
	}
	function get_warehouse_by_id($warehouse_id)
	{
		$this->db->where('warehouse_id', $warehouse_id);
		$result = $this->db->get('warehouses');
		return $result->row_array();
	}
}
