<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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
	public function agents()
	{
	        $this->load->view('page/Reports/agents');
	}
	public function clients()
	{
	        $this->load->view('page/Reports/clients');
	}
	public function deliveries()
	{
	        $this->load->view('page/Reports/deliveries');
	}
	public function export()
	{
	        $this->load->view('page/Reports/export');
	}
	public function express_deliveries()
	{
	        $this->load->view('page/Reports/express-deliveries');
	}
	public function orders()
	{
	        $this->load->view('page/Reports/orders');
	}
	public function payments()
	{
	        $this->load->view('page/Reports/payments');
	}
	public function pickup_schedules()
	{
	        $this->load->view('page/Reports/pickup-schedules');
	}
	public function search_filter()
	{
	        $this->load->view('page/Reports/search-filter');
	}
	public function users()
	{
	        $this->load->view('page/Reports/users');
	}
}


?>