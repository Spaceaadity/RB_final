<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Wishlist extends CI_model {

	public function user_list(){
		$current_user = $this->session->userdata('current_user');
		$id = $current_user['id'];
		$query = "SELECT products.added_by_users_id AS 'added_by_id', products.product AS 'name', products.created_at AS 'date_added', users.alias AS 'added_by', products.id AS 'id' FROM products LEFT JOIN users ON products.added_by_users_id = users.id WHERE products.id IN (SELECT wishlists.products_id FROM wishlists WHERE wishlists.users_id = ?) OR products.added_by_users_id = ?";
		$values = array($id, $id);
		return $this->db->query($query, $values)->result_array();
	}
	public function other_list(){
		$current_user = $this->session->userdata('current_user');
		$id = $current_user['id'];
		// $query = "SELECT products.product AS 'name', products.created_at AS 'date_added', users.alias AS 'added_by', products.id AS 'id' FROM products LEFT JOIN users ON products.added_by_users_id = users.id WHERE products.added_by_users_id != ? OR products.id IN (SELECT wishlists.users_id FROM wishlists JOIN products ON wishlists.products_id WHERE wishlists.users_id != ?) GROUP BY products.product";
		$query = "SELECT products.product AS 'name', products.created_at AS 'date_added', users.alias AS 'added_by', products.id AS 'id' FROM wishlists JOIN products ON wishlists.products_id = products_id JOIN users ON products.added_by_users_id = users.id WHERE products.added_by_users_id !=? AND wishlists.users_id != 2 AND products.id NOT IN (SELECT wishlists.products_id FROM wishlists WHERE wishlists.users_id = ?) group by product";
		$values = array($id, $id);
		return $this->db->query($query, $values)->result_array();
	}
	public function add_to_list($product_id){
		$current_user = $this->session->userdata('current_user');
		$id = $current_user['id'];
		$query = "INSERT INTO wishlists (created_at, updated_at, wishlists.users_id, wishlists.products_id) VALUES (NOW(), NOW(), ?, ?)";
		$values = array($id, $product_id);
		$this->db->query($query, $values);
	}
	public function remove_from_list($product_id){
		$current_user = $this->session->userdata('current_user');
		$id = $current_user['id'];
		$query = "DELETE FROM wishlists WHERE wishlists.users_id = ? AND wishlists.products_id = ?";
		$values = array($id, $product_id);
		$this->db->query($query, $values);
	}
	public function product_data($id){
		$query = "SELECT products.product AS 'name' FROM products WHERE products.id = ?";
		return $this->db->query($query, $id)->row_array();
	}
	public function product_users($id){
		$query = "SELECT users.alias AS 'name' FROM wishlists JOIN users ON wishlists.users_id = users.id WHERE wishlists.products_id = ?";
		return $this->db->query($query, $id)->result_array();
	}
}
?>