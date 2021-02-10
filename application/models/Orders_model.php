<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders_model extends CI_Model
{
	public function __construct()
    {
		parent::__construct();
	}
	
	function get_objects($type)
	{
		$this->db->where('object_type', $type);
		$result = $this->db->get('objects');
		return $result->result_array();
	}
     function get_product_images($object_id)
	{
		$this->db->where('product_id', $object_id);
		$this->db->order_by('id', 'ASC');
		$result = $this->db->get('product_images');
		return $result->result();
	}
	
	function add_object($data)
	{
		$this->db->insert('objects', $data);
		return $this->db->insert_id();
	}
	function get_object($object_id, $type = NULL, $parent = NULL, $author = NULL, $slug = NULL, $status = NULL)
	{
		$object_id	!= NULL ? $this->db->where('object_id', $object_id) : NULL;
		$type	 	!= NULL ? $this->db->where('object_type', $type) : NULL;
		$parent		!= NULL ? $this->db->where('object_parent', $parent) : NULL;
		$author		!= NULL ? $this->db->where('object_author', $author) : NULL;
		$slug		!= NULL ? $this->db->where('object_slug', $slug) : NULL;
		$status		!= NULL ? $this->db->where('object_status', $status) : NULL;
		$result = $this->db->get('objects');
		return $result->row_array();
	}
	function update_object($object_id, $data)
	{
		$this->db->where('object_id', $object_id);
		$this->db->update('objects', $data);
	}
	
	function delete_object($object_id)
	{
		$this->db->where('object_id', $object_id);
		$result = $this->db->delete('objects');
		return $result;
	}
	function add_objectmeta($objectmeta)
	{
		$this->db->insert_batch('objectmeta', $objectmeta);
	}
	
	function delete_order($object_id)
	{
		$this->db->where('object_id', $object_id);
		$result = $this->db->delete('orders');
		return $result;
	}
	
	function get_objectmeta($object_id)
	{
		$this->db->where('object_id', $object_id);
		$result = $this->db->get('objectmeta');
		return $result->result_array();
	}
	
	function get_objectmeta_by_key($meta_key, $object_id)
	{
		$this->db->where('meta_key', $meta_key);
		$this->db->where('object_id', $object_id);
		$result = $this->db->get('objectmeta');
		return $result->row_array();
	}
	function update_objectmeta($object_id, $objectmeta)
	{
		$this->db->where('object_id', $object_id);
		$this->db->update_batch('objectmeta', $objectmeta, 'ometa_id');
	}
	
	function delete_ordermeta($order_meta_id)
	{
		$this->db->where('order_meta_id', $order_meta_id);
		$this->db->delete('ordermeta');
	}
	
	function get_objectmeta_single($object_id)
	{
		$this->db->where('object_id', $object_id);
		$result = $this->db->get('objectmeta');
		foreach($result->result() as $row) {
			$options[$row->meta_key] = $row->meta_value;
		}
		if(!empty($options)){
			return $options;
		}
	}
	function get_ordermeta_single($order_id)
	{
		$this->db->where('order_id', $order_id);
		$result = $this->db->get('ordermeta');
		foreach($result->result() as $row) {
			$options[$row->meta_key] = $row->meta_value;
		}
		if(!empty($options)){
			return $options;
		}
	}
	function get_orders($type, $author)
	{
		if ($type != NULL)
        {
			$this->db->where('object_type', $type);
        }
		if ($author != NULL)
        {
			$this->db->where('object_author', $author);
        }
		$result = $this->db->get('objects');
		return $result->result_array();
	}
	function get_orders_by_date_range($type, $author, $from_date, $to_date)
	{
		if ($type != NULL)
        {
			$this->db->where('object_type', $type);
        }
		if ($author != NULL)
        {
			$this->db->where('object_author', $author);
		}
		$this->db->where('object_date >', $from_date);
		$this->db->where('object_date <', $to_date);
		$result = $this->db->get('objects');
		return $result->result_array();
	}
	function get_order($type, $object_id)
	{
		//$this->db->join('orders', 'orders.object_id = objects.object_id');
		$this->db->where('objects.object_type', $type);
		$this->db->where('objects.object_id', $object_id);
		$result = $this->db->get('objects');
		return $result->row_array();
	}
	
	function get_order_items($type, $vendor_id, $object_id)
	{
		//$this->db->join('orders', 'orders.object_id = objects.object_id');
		$type		!= NULL ? $this->db->where('orders.order_type', $type) : NULL;
		$vendor_id	!= NULL ? $this->db->where('orders.vendor_id', $vendor_id) : NULL;
		$object_id	!= NULL ? $this->db->where('orders.object_id', $object_id) : NULL;
		$result = $this->db->get('orders');
		return $result->result_array();
	}

	function add_order($additional_data)
	{
		$this->db->insert('orders', $additional_data);
		return $this->db->insert_id();
	}
	
	function get_ordermeta($order_id)
	{
		$this->db->where('order_id', $order_id);
		$result = $this->db->get('ordermeta');
		return $result->result_array();
	}
	
	function get_ordermeta_by_key($meta_key, $order_id)
	{
		$this->db->where('meta_key', $meta_key);
		$this->db->where('order_id', $order_id);
		$result = $this->db->get('ordermeta');
		return $result->row_array();
	}
	function add_ordermeta($ordermeta)
	{
		$this->db->insert_batch('ordermeta', $ordermeta);
	}
	function update_ordermeta($order_id, $ordermeta)
	{
		$this->db->where('order_id', $order_id);
		$this->db->update_batch('ordermeta', $ordermeta, 'order_meta_id');
	}
	function update_ordermeta_by_key($order_id, $key, $ordermeta)
	{
		$this->db->where('order_id', $order_id);
		$this->db->where('meta_key', $key);
		$this->db->update('ordermeta', $ordermeta);
	}
	function get_datatable_orders($postData=null, $type, $author, $vendor_id, $from_date, $to_date)
	{
		$response = array();
  
		## Read value
		$draw = $postData['draw'];
		$start = $postData['start'];
		$rowperpage = $postData['length']; // Rows display per page
		$columnIndex = $postData['order'][0]['column']; // Column index
		$columnName = $postData['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $postData['order'][0]['dir']; // asc or desc
		$searchValue = $postData['search']['value']; // Search value
  
		## Search 
		$searchQuery = "";
		if($searchValue != ''){
			$searchQuery = " (
				objects.object_status like '%".$searchValue."%' or 
				objects.object_title like '%".$searchValue."%' or 
				objects.object_id like '%".$searchValue."%' or 
				objects.object_content like'%".$searchValue."%' ) ";
		}
  
		## Total number of records without filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('orders', 'orders.object_id = objects.object_id', 'inner');
		$type		!= NULL ? $this->db->where('object_type', $type) : NULL;
		$author		!= NULL ? $this->db->where('object_author', $author) : NULL;
		$vendor_id	!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$this->db->where('object_date >', $from_date);
		$this->db->where('object_date <', $to_date);
		$records = $this->db->get('objects')->result();
		$totalRecords = $records[0]->allcount;
  
		## Total number of record with filtering
		$this->db->select('count(*) as allcount');
		$this->db->join('orders', 'orders.object_id = objects.object_id', 'inner');
		$type		!= NULL ? $this->db->where('object_type', $type) : NULL;
		$author		!= NULL ? $this->db->where('object_author', $author) : NULL;
		$vendor_id	!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$this->db->where('objects.object_date >', $from_date);
		$this->db->where('objects.object_date <', $to_date);
		if($searchQuery != '')
		$this->db->where($searchQuery);
		$records = $this->db->get('objects')->result();
		$totalRecordwithFilter = $records[0]->allcount;
		
		## Fetch records
		if($totalRecords >= 1){
			$type		!= NULL ? $this->db->where('object_type', $type) : NULL;
			$author		!= NULL ? $this->db->where('object_author', $author) : NULL;
			$this->db->where('object_date >', $from_date);
			$this->db->where('object_date <', $to_date);
			if($searchQuery != '')
			$this->db->where($searchQuery);
			$this->db->order_by('objects.'.$columnName, $columnSortOrder);
			$this->db->limit($rowperpage, $start);
			$order_objects = $this->db->get('objects')->result_array();
		} else {
			$order_objects = '';
		}
		//echo '<pre>'; print_r($order_objects); echo '</pre>'; die();
		if(!empty($order_objects)){
			foreach ($order_objects as $k => $v) {
				$order_meta_data = $this->get_objectmeta_single($v['object_id']);
				if(!empty($order_meta_data['country_id']))
				{
					$country = $this->locations_model->get_country_by_id($order_meta_data['country_id']);
					$order_meta_data['country_name'] = $country->country_name;
					$order_meta_data['continent_name'] = $country->continent_name;
				}
				if(!empty($order_meta_data['shipper_id']))
				{
					$shipper = $this->ion_auth->user($order_meta_data['shipper_id'])->row();
					$order_meta_data['shipper_name'] = $shipper->company;
				}
				$courier = $this->couriers_model->get_courier($order_meta_data['courier_id']);
				$order_meta_data['courier_name'] = !empty($courier->courier_id) ? $courier->courier_name : '';
				$results[] = array_merge($v, $order_meta_data);
				
			}
			//echo '<pre>'; print_r($results); echo '</pre>'; die();
			$data = array();
			foreach($results as $record ){
				$data[] = array( 
					"object_id"=> $record['object_id'],
					"order_prefix"=> $record['order_prefix'],
					"order_serial"=> $record['order_serial'],
					"object_date"=> date('M, d Y', $record['object_date']),
					"object_status"=> $record['object_status'],
					"shipper"=> $record['shipper_name'],
					"courier"=> $record['courier_name'],
					"tracking_number"=> $record['tracking_number'],
					"customer_name"=> $record['customer_name'],
					"customer_phone"=> $record['customer_phone'],
					"shipping_address"=> $record['shipping_address'],
				); 
			}
			## Response
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $totalRecords,
				"iTotalDisplayRecords" => $totalRecordwithFilter,
				"aaData" => $data
			);
			return $response; 
		}
		else
		{
			$data = array();
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $totalRecords,
				"iTotalDisplayRecords" => $totalRecordwithFilter,
				"aaData" => $data
			);
			return $response; 
		}
	}
	function fetch_records(){

	}
}
