<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Sitemap_model extends CI_Model
{
	function get_destination_slugs()
	{
		$result = $this->db->get('destinations');
		return $result->result_array();
	}
}
