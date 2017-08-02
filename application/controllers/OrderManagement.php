<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrderManagement extends CI_Controller {

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
        $isLoggedIn = $this->session->userdata('logged_in');
        if(!$isLoggedIn){
            redirect(SITE.'backend');
        }
		$this->load->database();

	//	$this->load->helper('dynmic-css-js');
		$this->load->model('User_model');
		$this->load->model('Admin_model');

        $this->load->helper('cookie');			 

	}
	 
	public function index()
	{
$this->load->view('backend');
	//  
	}

	public function single_consignment()
	{
	        $this->load->view('page/Order Management/Prepare Order/single-consignment');
	}
	public function multiple_consignment()
	{
	        $this->load->view('page/Order Management/Prepare Order/multiple-consignment');
	}
	public function pickup_request()
	{
	        $this->load->view('page/Order Management/Prepare Order/pickup-request');
	}
	public function track_order()
	{
	        $this->load->view('page/Order Management/track-order');
	}
	public function order_status()
	{
	        $this->load->view('page/Order Management/order-status');
	}
	public function order_list()
	{
	        $this->load->view('page/Order Management/order-list');
	}
}


?>