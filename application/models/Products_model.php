<?php
/**
 * Name:    Terminals Model
 * Author:  DrCodeX Technologies
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Products_model extends CI_Model
{
	public function __construct()
    {
		parent::__construct();
	}
	function get_objects($type, $parent, $author, $status)
	{
		$this->db->select('objects.*, users.company AS object_author');
		$this->db->join('users', 'users.id = objects.object_author', 'left');
		$this->db->where('object_type', $type);
		if ($parent != NULL)
        {
			$this->db->where('object_parent', $parent);
        }
		if ($author != NULL)
        {
			$this->db->where('object_author', $author);
        }
		if ($status != NULL)
        {
			$this->db->where('object_status', $status);
        }
		$result = $this->db->get('objects');
		return $result->result_array();
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

	function add_object($data)
	{
		$this->db->insert('objects', $data);
		return $this->db->insert_id();
	}
	function update_object($object_id, $data)
	{
		$this->db->where('object_id', $object_id);
		$this->db->update('objects', $data);
	}
	
	function delete_object($object_id)
	{
		$this->db->where('object_id', $object_id);
		$this->db->delete('objects');
	}
	function delete_stock_row($object_id)
	{
		$this->db->where('object_id', $object_id);
		$this->db->delete('stock');
	}
	function add_objectmeta($objectmeta)
	{
		$this->db->insert_batch('objectmeta', $objectmeta);
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
	
	function delete_objectmeta($ometa_id)
	{
		$this->db->where('ometa_id', $ometa_id);
		$result = $this->db->delete('objectmeta');
		return $result;
	}
	function delete_objectmeta_by_object_id($object_id)
	{
		$this->db->where('object_id', $object_id);
		$result = $this->db->delete('objectmeta');
		return $result;
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

	function add_attribute($data)
	{
		$result = $this->db->insert('attributes',$data);
		return $this->db->insert_id();
	}
	function get_attribute_by_id($attribute_id)
	{
		$this->db->where('attribute_id', $attribute_id);
		$result = $this->db->get('attributes');
		return $result->row_array();
	}
	function get_attribute_term_by_attribute_id($attribute_id)
	{
		$this->db->join('terms', 'terms.term_id = attribute_terms.term_id', 'left');
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id', 'left');
		$this->db->where('attribute_id', $attribute_id);
		$result = $this->db->get('attribute_terms');
		return $result->result_array();
	}
	function get_attributes()
	{
		$result = $this->db->get('attributes');
		return $result->result();
	}
	function get_attribute_with_values($attribute_id)
	{
		$this->db->join('attribute_terms', 'attribute_terms.attribute_id = attributes.attribute_id', 'left');
		$this->db->join('terms', 'terms.term_id = attribute_terms.term_id', 'left');
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id', 'left');
		$this->db->where('attributes.attribute_id', $attribute_id);
		$result = $this->db->get('attributes');
		return $result->result_array();
	}
	function get_attribute_with_value($attribute_id)
	{
		$this->db->join('attribute_terms', 'attribute_terms.attribute_id = attributes.attribute_id', 'left');
		$this->db->join('terms', 'terms.term_id = attribute_terms.term_id', 'left');
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id', 'left');
		$this->db->where('attributes.attribute_id', $attribute_id);
		$result = $this->db->get('attributes');
		return $result->row_array();
	}
	function get_attribute_by_slug($taxonomy)
	{
		$this->db->where('attribute_slug', $taxonomy);
		$result = $this->db->get('attributes');
		return $result->row_array();
	}
	function add_attribute_terms($additional_data)
	{
		$this->db->insert('attribute_terms',$additional_data);
	}
	function delete_attribute_term($term_id)
	{
		$this->db->where('term_id', $term_id);
		$this->db->delete('attribute_terms');
	}
	function delete_attribute($attribute_id)
	{
		$this->db->where('attribute_id', $attribute_id);
		$this->db->delete('attributes');
	}
	function add_term($data)
	{
		$result = $this->db->insert('terms',$data);
		return $this->db->insert_id();
	}
	function update_term($term_id, $data)
	{
		$this->db->where('term_id', $term_id);
		$this->db->update('terms',$data);
	}
	function add_term_taxonomy($data)
	{
		$this->db->insert('term_taxonomy',$data);
	}
	function get_stock($stock_id, $warehouse_id, $object_id, $vendor_id)
	{
		$stock_id		!= NULL ? $this->db->where('stock_id', $stock_id) : NULL;
		$warehouse_id	!= NULL ? $this->db->where('warehouse_id', $warehouse_id) : NULL;
		$object_id		!= NULL ? $this->db->where('object_id', $object_id) : NULL;
		$vendor_id		!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$result = $this->db->get('stock');
		return $result->row_array();
	}
	function get_stocks($warehouse_id, $object_id, $vendor_id)
	{
		$warehouse_id	!= NULL ? $this->db->where('warehouse_id', $warehouse_id) : NULL;
		$object_id		!= NULL ? $this->db->where('object_id', $object_id) : NULL;
		$vendor_id		!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$result = $this->db->get('stock');
		return $result->result_array();
	}
	function add_stock_row($data)
	{
		$this->db->insert('stock', $data);
	}
	function update_stock_row($stock_id, $data)
	{
		$this->db->where('stock_id', $stock_id);
		$this->db->update('stock', $data);
	}
	function update_stock_batch($stock)
	{
		$this->db->update_batch('stock', $stock, 'stock_id');
	}
	function add_warehouse_to_variation($data)
	{
		$this->db->insert('stock', $data);
		return $this->db->insert_id();
	}
	function delete_warehouse_variation($object_id, $warehouse_id, $vendor_id)
	{
		$this->db->where('object_id', $object_id);
		$this->db->where('warehouse_id', $warehouse_id);
		$this->db->where('vendor_id', $vendor_id);
		$this->db->delete('stock');
	}
	function add_term_relationships($data)
	{
		$result = $this->db->insert('terms', $data);
		return $this->db->insert_id();
	}
	
	function add_term_relationship($term_taxonomy_id, $data)
	{
		$this->db->where('term_taxonomy_id', $term_taxonomy_id);
		$res = $this->db->get('term_relationships');
		if ( $res->num_rows() > 0 ) 
		{
			$this->db->where('term_taxonomy_id', $term_taxonomy_id);
			$this->db->update('term_relationships', $data);
		} else {
            $this->db->insert('term_relationships', $data);
		}
	}
	function get_terms()
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id', 'inner');
		$result = $this->db->get('terms');
		return $result->result();
	}
	
	function get_terms_by_taxonomy($taxonomy)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id', 'inner');
		$this->db->where('taxonomy', $taxonomy);
		$this->db->order_by('term_taxonomy_id', 'ASC');
		$result = $this->db->get('terms');
		return $result->result_array();
	}
	function get_term_by_slug($slug)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id', 'inner');
		$this->db->where('slug', $slug);
		$this->db->order_by('term_taxonomy_id', 'ASC');
		$result = $this->db->get('terms');
		return $result->row_array();
	}
	function get_terms_by_slug($slug)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id', 'inner');
		$this->db->where('slug', $slug);
		$this->db->order_by('term_taxonomy_id', 'ASC');
		$result = $this->db->get('terms');
		return $result->result_array();
	}

	function get_taxonomy_with_term($taxonomy)
	{
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'inner');
		$this->db->where('taxonomy', $taxonomy);
		$this->db->order_by('term_taxonomy_id', 'ASC');
		$result = $this->db->get('term_taxonomy');
		return $result->result_array();
	}
	function get_taxonomy_with_relationship($object_id, $taxonomy)
	{
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'left');
		$this->db->join('term_relationships', 'term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id', 'left');
		$this->db->where('term_relationships.object_id', $object_id);
		$this->db->where('term_taxonomy.taxonomy', $taxonomy);
		$this->db->order_by('term_taxonomy.term_taxonomy_id', 'ASC');
		$result = $this->db->get('term_taxonomy');
		return $result->row_array();
	}
	function get_taxonomy_with_relationships($object_id, $taxonomy)
	{
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'left');
		$this->db->join('term_relationships', 'term_relationships.term_taxonomy_id = term_taxonomy.term_taxonomy_id', 'left');
		if($object_id != NULL)
		{
			$this->db->where('term_relationships.object_id', $object_id);
		}
		$this->db->where('term_taxonomy.taxonomy', $taxonomy);
		$this->db->order_by('term_taxonomy.term_taxonomy_id', 'ASC');
		$result = $this->db->get('term_taxonomy');
		return $result->result_array();
	}
	function get_terms_relationships()
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id', 'left');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id', 'left');
		$result = $this->db->get('term_relationships');
		return $result->result_array();
	}

	function get_terms_relations($id, $value)
	{
		$this->db->where('object_id', $id);
		$this->db->where('term_taxonomy_id', $value);
		$result = $this->db->get('term_relationships');
		return $result->row_array();
	}
	function get_terms_relations_by_taxonomy($object_id, $taxonomy)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id');
		$this->db->where('term_relationships.object_id', $object_id);
		$this->db->where('term_taxonomy.taxonomy', $taxonomy);
		$result = $this->db->get('term_relationships');
		return $result->result_array();
	}
	function get_terms_relation_by_taxonomy($object_id, $taxonomy)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id');
		$this->db->where('term_relationships.object_id', $object_id);
		$this->db->where('term_taxonomy.taxonomy', $taxonomy);
		$result = $this->db->get('term_relationships');
		return $result->row_array();
	}
	function get_descriptive_term_relation($object_id, $term_taxonomy_id)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id');
		$this->db->where('term_relationships.object_id', $object_id);
		$this->db->where('term_relationships.term_taxonomy_id', $term_taxonomy_id);
		$result = $this->db->get('term_relationships');
		return $result->row_array();
	}
	function get_term_by_id($term_id)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id');
		$this->db->where('terms.term_id', $term_id);
		$result = $this->db->get('terms');
		return $result->row_array();
	}
	function get_term_by_taxonomy_id($term_id)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = terms.term_id');
		$this->db->where('term_taxonomy_id', $term_id);
		$result = $this->db->get('terms');
		return $result->row();
	}

	function delete_term($term_id)
	{
		$this->db->where('term_id', $term_id);
		$this->db->delete('terms');
	}

	function delete_term_taxonomy($term_id)
	{
		$this->db->where('term_taxonomy_id', $term_id);
		$this->db->delete('term_taxonomy');
	}
	function delete_term_taxonomy_by_term_id($term_id)
	{
		$this->db->where('term_id', $term_id);
		$this->db->delete('term_taxonomy');
	}

	function get_category_last_id($last_id)
	{
		$this->db->where('term_id', $last_id);
		$result = $this->db->get('terms');
		return $result->row();
	}
	
	function delete_term_relationships_by_term_taxonomy_id($term_id)
	{
		$this->db->where_in('term_taxonomy_id', $term_id);
		$this->db->delete('term_relationships');
	}
	
	function get_product_image_by_product_id($product_id)
	{
		$this->db->where('product_id', $product_id);
		$result = $this->db->get('product_images');
		if ( $result->num_rows() > 0 ) 
		{
			return $result->row();
		} else {
			return '';
		}
	}
	function upload_product_image($product_id, $data)
	{
		$this->db->where('product_id', $product_id);
		$this->db->where('product_id is NOT NULL', NULL, FALSE);
		$res = $this->db->get('product_images');
		if ( $res->num_rows() > 0 ) 
		{
			//echo '<pre>'; print_r('update'); echo '</pre>'; die();
			$this->db->where('product_id', $product_id);
			$this->db->update('product_images', $data);
			return $this->db->get('product_images')->row()->id;
		} else {
			//echo '<pre>'; print_r('insert'); echo '</pre>'; die();
            $this->db->insert('product_images', $data);
			return $this->db->insert_id();
		}
	}
	function update_product_image($image_id, $data)
	{
		$this->db->where('id', $image_id);
		$this->db->update('product_images', $data);
		return $this->db->get('product_images')->row()->id;
	}
	function add_product_image($data)
	{
		$this->db->insert('product_images', $data);
		return $this->db->insert_id();
	}
	function assign_product_gallery_image($data)
	{
		$this->db->insert('product_gallery', $data);
	}
	
	function delete_product_image($product_id, $path, $thumb)
	{
		$imagepath = FCPATH.$path;
		$imagethumb = FCPATH .$thumb;
		$this->db->where('id', $product_id);
		if(file_exists($imagepath))
		{
			unlink($path);
		}
		if(file_exists($imagethumb))
		{
			unlink($thumb);
		}
		$this->db->delete('product_images');
	}

	function get_product_image_by_last_id($last_id)
	{
		$this->db->where('id', $last_id);
		$result = $this->db->get('product_images');
		return $result->row();
	}
	function get_product_gallery($object_id)
	{
		$this->db->join('product_images', 'product_images.id = product_gallery.product_image_id');
		$this->db->where('object_id', $object_id);
		$this->db->order_by('product_images.id', 'DESC');
		$result = $this->db->get('product_gallery');
		return $result->result();
	}
	function get_product_images($object_id)
	{
		$this->db->where('product_id', $object_id);
		$this->db->order_by('id', 'ASC');
		$result = $this->db->get('product_images');
		return $result->result();
	}
	function get_product_image_by_id($id)
	{
		$this->db->where('id', $id);
		$result = $this->db->get('product_images');
		return $result->row();
	}
	function update_featured_image_product_id($featured_image_id, $other_data)
	{
		$check = $this->db->where('id', $featured_image_id);
		$this->db->update('product_images', $other_data);
	}
	
	function check_if_term_id_exist_by_id($term_taxonomy_id, $object_id, $taxonomy)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id', 'left');
		$this->db->where('term_relationships.term_taxonomy_id', $term_taxonomy_id);
		$this->db->where('object_id', $object_id);
		$this->db->where('taxonomy', $taxonomy);
		$result = $this->db->get('term_relationships');
		if ($result->num_rows() > 0){
			return $result->row_array();
		}
		else
		{
			return false;
		}
	}
	
	function check_if_term_id_not_exist_by_id($term_taxonomy_id, $object_id, $taxonomy)
	{
		$this->db->join('term_taxonomy', 'term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id', 'left');
		$this->db->where('term_relationships.term_taxonomy_id !=', $term_taxonomy_id);
		$this->db->where('object_id', $object_id);
		$this->db->where('taxonomy', $taxonomy);
		$result = $this->db->get('term_relationships');
		return $result->result_array();
	}
	
	function delete_term_relationships($object_id)
	{
		$this->db->where_in('object_id', $object_id);
		$this->db->delete('term_relationships');
	}
	function delete_relationships($object_id)
	{
		$this->db->where_in('object_id', $object_id);
		$this->db->delete('term_relationships');
	}
	
	function delete_term_relationships_id($term_taxonomy_id, $object_id)
	{
		$this->db->where_in('term_taxonomy_id', $term_taxonomy_id);
		$this->db->where_in('object_id', $object_id);
		$this->db->delete('term_relationships');
	}
	
	function delete_term_relationship_by_object_id($id)
	{
		$this->db->where('object_id', $id);
		$result = $this->db->delete('term_relationships');
		return $result;
	}
	function fetch_data($query)
	{
		return $this->get_product_variations_by_search($query);
		//echo '<pre>'; print_r($result); echo '</pre>'; die();
	}
	function get_product_variations_by_search($query)
	{
		if($query != '')
		{
			$this->db->like('object_title', $query);
		}
		$this->db->where('object_type', 'product_variation');
		$result = $this->db->get('objects');
		return $result->result();
	}
	function check_product_type($object_id)
	{
		$this->db->select('terms.name AS product_type');
		$this->db->join('term_relationships', 'term_relationships.object_id = objects.object_id');
		$this->db->join('term_taxonomy', 'term_taxonomy.term_id = term_relationships.term_taxonomy_id');
		$this->db->join('terms', 'terms.term_id = term_taxonomy.term_id');
		$this->db->where_in('objects.object_id', $object_id);
		$this->db->where_in('taxonomy', 'product_type');
		$result = $this->db->get('objects');
		return $result->row_array();
	}
	
	function column_relation($left_column, $right_column, $table)
	{
		$left_column	!= NULL ? $this->db->where('object_id', $left_column) : NULL;
		$right_column	!= NULL ? $this->db->where('warehouse_id', $right_column) : NULL;
		$result = $this->db->get($table);
		//echo '<pre>'; print_r($result->row_array()); echo '</pre>'; die();
		return $result->row_array();
	}
	function get_cart_items($type, $user_id, $vendor_id)
	{
		$this->db->join('objects', 'objects.object_id = cart.object_id');
		$type	!= NULL ? $this->db->where('object_type', $type) : NULL;
		$user_id	!= NULL ? $this->db->where('user_id', $user_id) : NULL;
		$vendor_id	!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$result = $this->db->get('cart');
		return $result->result_array();
	}
	
	function add_to_cart($data)
	{
		$this->db->insert('cart', $data);
		return $this->db->insert_id();
	}

	function update_cart($object_id, $user_id, $vendor_id, $data)
	{
		$this->db->where('object_id', $object_id);
		$this->db->where('user_id', $user_id);
		$this->db->where('vendor_id', $vendor_id);
		$this->db->update('cart', $data);
	}
	
	function delete_cart_item($object_id, $user_id, $vendor_id)
	{
		$object_id	!= NULL ? $this->db->where('object_id', $object_id) : NULL;
		$user_id	!= NULL ? $this->db->where('user_id', $user_id) : NULL;
		$vendor_id	!= NULL ? $this->db->where('vendor_id', $vendor_id) : NULL;
		$this->db->delete('cart');
	}
}
