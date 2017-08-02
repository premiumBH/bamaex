<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ClientManagement extends CI_Controller {

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

	public function order_list()
	{
	        $this->load->view('page/Client Management/Table of clients/client-order-list');
	}
	public function under_clients()
	{
	        $this->load->view('page/Client Management/Table of clients/users-under-clients');
	}
	public function add_client()
	{
	        $this->load->view('page/Client Management/add-client');
	}
	public function contact()
	{
	        $this->load->view('page/Client Management/Edit Client Status/contact');
	}
	public function prospect()
	{
	        $this->load->view('page/Client Management/Edit Client Status/prospect');
	}
	public function account()
	{
	        $this->load->view('page/Client Management/Edit Client Status/account');
	}
	public function suspended()
	{
	        $this->load->view('page/Client Management/Edit Client Status/suspended');
	}
	public function blacklisted()
	{
	        $this->load->view('page/Client Management/Edit Client Status/blacklisted');
	}
}


?>