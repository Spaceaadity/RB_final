<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class User extends CI_model {
	public function reg_user($post){
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('alias', 'Username', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('pass_conf', 'Password confirmation', 'trim|required|matches[password]');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'required');
		if ($this->form_validation->run()===FALSE){
			$this->session->set_flashdata('errors', validation_errors());
		} else {
			$email = strtolower($post['email']);
			$query = "INSERT INTO users (name, alias, email, password, dob, created_at, update_at) VALUES (?,?,?,?,?,NOW(),NOW())";
			$values = array($post['name'], $post['alias'], $email, $post['password'], $post['dob']);
			$this->db->query($query, $values);
			$this->session->set_flashdata('success', 'You have successfully registered. Please login.');
		}
	}
	public function get_user_by_email($email){
		$query = "SELECT id, name, password FROM users WHERE email = ?";
		return $this->db->query($query, $email)->row_array();
	}
	public function log_user($post){
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		if($this->form_validation->run() === FALSE){
			$this->session->set_flashdata('errors', validation_errors());
			$data['logged_in'] = FALSE;
		} else {

			$get_user = $this->get_user_by_email($post['email']);
			if(!$get_user){
				$this->session->set_flashdata('errors', 'Entered email is not registered.');
				$data['logged_in'] = FALSE;
			} else {
				if($post['password'] === $get_user['password']){
					$data['logged_in'] = TRUE;
					$data['name'] = $get_user['name'];
					$data['id'] = $get_user['id'];
				} else {
					$this->session->set_flashdata('errors', "Entered password does not match registered email's password." );
					$data['logged_in'] = FALSE;
				}
			}
		}
		return $data;
	}
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
	public function destroy_item($product_id){
		$query1 = "DELETE FROM wishlists WHERE wishlists.products_id = ?";
		$this->db->query($query1, $product_id);
		$query2 = "DELETE FROM products WHERE id = ?";
		$this->db->query($query2, $product_id);
	}
	public function add_item($post){
		$current_user = $this->session->userdata('current_user');
		$id = $current_user['id'];
		$this->form_validation->set_rules('product', 'Product/item', 'trim|required|min_length[3]');
		if ($this->form_validation->run()===FALSE){
			$this->session->set_flashdata('errors', validation_errors());
			$data = false;
		} else {
			$query= "INSERT INTO products (product, created_at, updated_at, added_by_users_id) VALUES (?, NOW(), NOW(), ?)";
			$values = array($post['product'], $id);
			$this->db->query($query, $values);
			$data = true;
		}
		return $data;
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