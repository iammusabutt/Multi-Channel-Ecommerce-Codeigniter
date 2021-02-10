<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Seo_model extends CI_Model
{
	function get_seo_by_page($page)
	{
		$this->db->where('page', $page);
		$result = $this->db->get('seo');
		return $result->row_array();
	}
	function get_seo_by_page_id($page, $destination_id)
	{
		$this->db->where('page', $page);
		$this->db->where('page_id', $destination_id);
		$result = $this->db->get('seo');
		return $result->row_array();
	}
	function add_update_seo($page, $data)
	{
		$this->db->where('page', $page);
		$res = $this->db->get('seo');

		if ( $res->num_rows() > 0 ) 
		{
			$this->db->where('page', $page);
            $result = $this->db->update('seo', $data);
			return $this->db->insert_id();
		} else {
            $result = $this->db->insert('seo', $data);
			return $this->db->insert_id();
		}
	}
	function add_update_seo_by_page_id($page, $destination_id, $data)
	{
		$this->db->where('page', $page);
		$this->db->where('page_id', $destination_id);
		$res = $this->db->get('seo');

		if ( $res->num_rows() > 0 ) 
		{
			$this->db->where('page', $page);
			$this->db->where('page_id', $destination_id);
            $result = $this->db->update('seo', $data);
			return $this->db->insert_id();
		} else {
            $result = $this->db->insert('seo', $data);
			return $this->db->insert_id();
		}
	}
}
