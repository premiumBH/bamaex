<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backend extends CI_Controller {

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
	public function login()
	{
$this->load->view('backend');
	//  
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('http://localhost/bahrain');
	}
}
