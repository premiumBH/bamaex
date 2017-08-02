<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AddressBook extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
	//	$this->load->helper('dynmic-css-js');
		$this->load->model('User_model');
		$this->load->model('Admin_model');
		$this->load->library('session');
        $this->load->helper('cookie');			 

	}
	 
	public function index()
	{
$this->load->view('backend');
	//  
	}

	public function pickup_address()
	{
	        $this->load->view('page/Address Book/pickup-address');
	}
	public function delivery_address()
	{
	        $this->load->view('page/Address Book/delivery-address');
	}
	public function assign_pickup()
	{
	        $this->load->view('page/Address Book/Pickup Functions/assign-pickup-function');
	}
	public function track_order()
	{
	        $this->load->view('page/Address Book/Pickup Functions/due-deliveries');
	}
}


?>