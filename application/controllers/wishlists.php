<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlists extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->output->enable_profiler();
		$this->load->model('User');
		$this->load->model('Wishlist');
	}
	public function update($id){
		$this->Wishlist->add_to_list($id);
		redirect ('/dashboard');
	}
	public function remove($id){
		$this->Wishlist->remove_from_list($id);
		redirect ('/dashboard');
	}
	public function show($id){
		$data['product'] = $this->Wishlist->product_data($id);
		$data['product_users'] = $this->Wishlist->product_users($id);
		$this->load->view('show', $data);
	}
}
?>