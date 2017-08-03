<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'/libraries/PHPExcel/IOFactory.php';

class Dashboard extends CI_Controller {

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
        $this->load->model('Zone_model');
        $this->load->model('Country_model');
        $this->load->model('Client_model');
        $this->load->helper('cookie');

      

		$config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|xlsx';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);

         

	}
	 
	public function index()
	{
        $this->load->view('admin/home');
	}

    public function logout(){

        $this->session->sess_destroy();
        session_destroy ();
	    redirect(SITE.'backend');

	}
	public function user()
	{
            $data1['UserId'] = ($this->session->userdata['UserId']);
            if($data1['UserId'] != '')
            {
              $data['result1'] = $this->User_model->get_users($data1);	
              $this->load->view('page/Dashboard/user', $data);
            }
	}

	public function client()
	{
            $data['clients'] = $this->Client_model->getClients();
            //var_dump($data);
            //die('HERE');
	    $this->load->view('page/Dashboard/client',$data);
	}
	public function package()
	{
	        $this->load->view('page/Dashboard/package');
	}
	public function create_order_old()
	{ echo "This old page in comming";
	    $this->load->view('client/create-order-old');#crate Order Functions
	
	}
	
	public function create_order()
	{  $this->load->view('client/create-order');#crate Order Functions
	       
	}
	public function new_create_order()
	{  $this->load->view('client/create-order');#crate Order Functions
	        
	}
	
	public function service()
	{
	        $this->load->view('page/Dashboard/service');
	}
	public function order_status()
	{
	        $this->load->view('page/Dashboard/order_status');
	}
	public function total_prospects()
	{
	        $this->load->view('page/Dashboard/total-prospects');
	}
	public function orders_in_progress()
	{
	        $this->load->view('page/Dashboard/orders-in-progress');
	}
	public function orders_pending()
	{
	        $this->load->view('page/Dashboard/orders-pending');
	}
	public function pending_payments()
	{
	        $this->load->view('page/Dashboard/pending-payments');
	}
	public function order_origanated()
	{
	        $this->load->view('page/Dashboard/order-origanated');
	}
	public function Airway_bill()
	{
	        $this->load->view('page/Dashboard/airway-bill');
	}
	public function bulk_order_orignated()
	{
		if($this->input->post('submit') != null)
		{
			if($this->upload->do_upload('Template'))
			{
			    echo "file upload success";
			}
			else
			{
			   echo "file upload failed";
			}
			$data = array('upload_data' => $this->upload->data()); 

			$file = $data['upload_data']['full_path'];
			try 
			{
			    $inputFileType = PHPExcel_IOFactory::identify($file);
			    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
			    $objPHPExcel = $objReader->load($file);
			} catch(Exception $e) {
			    die('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
	    	
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();

			//  Loop through each row of the worksheet in turn
			for ($row = 1; $row <= $highestRow; $row++){ 
			    //  Read a row of data into an array
			    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
			                                    NULL,
			                                    TRUE,
			                                    FALSE);
			    echo $rowData[0][1].'\n';
			    
			}
			die('EHRE');
			
		}

	    $this->load->view('page/Dashboard/bulk-order-origanated');
	}
	
	public function deliveries_in_progress()
	{
	        $this->load->view('page/Dashboard/deliveries-in-progress');
	}
	public function amount_due()
	{
	        $this->load->view('page/Dashboard/amount-due');
	}
	public function download_template()
	{
                header('Content-Type: application/csv');
                header('Content-Disposition: attachment; filename=Template.xlsx');
                header('Pragma: no-cache');
                readfile(base_url()."template/BulkUpload.xlsx");	
	}
        public function zone()
        {
                $zone['zones'] = $this->Zone_model->get_zones();
                $this->load->view('page/Dashboard/zone' , $zone);
        }
        public function country()
        {
                $zone['countries'] = $this->Country_model->get_countries();
                $this->load->view('page/Dashboard/country' , $zone);
        }
        public function blackListClients()
        {
            $data['clients'] = $this->Client_model->getClients();
            $this->load->view('page/Dashboard/ViewBlackListed',$data);
        }


}


?>