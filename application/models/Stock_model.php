<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_model extends CI_Model
{
	public function __construct()
    {
		parent::__construct();
	}

	function update_stock_on_order($object_id, $warehouse_id, $vendor_id, $quantity)
	{
		$this->db->where('object_id', $object_id);
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->where('vendor_id', $vendor_id);
		$this->db->set('stock_quantity', 'stock_quantity-'.$quantity, FALSE);
		$this->db->update('stock');
	
	}
	function update_stock_on_order_reverse($object_id, $warehouse_id, $vendor_id, $quantity)
	{
		$this->db->where('object_id', $object_id);
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->where('vendor_id', $vendor_id);
		$this->db->set('stock_quantity', 'stock_quantity+'.$quantity, FALSE);
		$this->db->update('stock');
	
	}
	
}
