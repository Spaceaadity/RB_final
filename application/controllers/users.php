<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->output->enable_profiler();
		$this->load->model('User');
		$this->load->model('Wishlist');
	}
	// loads login page
	public function index(){
		$this->load->view('main');
	}
	//deals with registering (ie 'creating' a user)
	public function create(){
		// var_dump($this->input->post());
		// die('create');
		$this->User->reg_user($this->input->post());
		redirect ('/');
	}
	//deals with login and validation of a user - model assings flashMSGs
	public function login(){
		// var_dump($this->input->post());
		// die('login issue?');
		$data = $this->User->log_user($this->input->post());
		// var_dump($data);
		// die('login - data');
		if($data['logged_in']===FALSE){
			redirect ('/');
		} else {
			$this->session->set_userdata('current_user', $data);
			// die('waiting for dash to be ready!');
			redirect ('/dashboard');
		}
	}
	//loads the user's dashboard - makes calls to wishlist model
	public function dashboard(){
		$data['user_products'] = $this->Wishlist->user_list();
		$data['other_products'] = $this->Wishlist->other_list();
		$data['userdata'] = $this->session->userdata('current_user');
		$this->load->view('dashboard', $data);
	}
	//takes you to the add/new product form page
	public function add(){
		$this->load->view('new');
	}
	//adds an item to the products database
	public function create_item(){
		$data = $this->User->add_item($this->input->post());
		if($data===false){
			redirect ('wish_items/create');
		}else{
			redirect ('/dashboard');
		}
	}
	//removes item from all wishlists and then removes from products DB
	//kept create and delete in users controller because it belongs dirctly to the one who made it - made sense 
	public function delete($id){
		// var_dump($id);
		// die('hi delete!');
		$this->User->destroy_item($id);
		redirect ('/dashboard');
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect('/');
	}
}
