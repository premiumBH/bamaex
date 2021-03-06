<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {

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

	//	$this->load->helper('dynmic-css-js');
		$this->load->model('User_model');
		$this->load->model('Staff_model');

        $this->load->helper('cookie');		
		 
			
	}
	 
	public function index()
	{
$this->load->view('backend');
	//  
	}
	public function login() {
	    $data['emailId']=$this->input->post('emailId');
	    $data['password']=$this->input->post('password');
	    //echo json_encode($data);
	    if(isset($data['emailId']) && isset($data['password'])) {
	        
	        $result=$this->User_model->login($data);
	      //  echo json_encode($result);
	        if(($result['status']==true)) {
     $newdata = array(
         'UserId' => $result['userinfo'][0]->UserId,
         'username' => $result['userinfo'][0]->FirstName . ' ' . $result['userinfo'][0]->FirstName,
         'email' => $result['userinfo'][0]->EmailId,
         'UserType' => $result['userinfo'][0]->UserType,
         'UserTypeCode'=>$result['userinfo'][0]->UserTypeCode,
         'UserTypeId'=>$result['userinfo'][0]->UserTypeId,
         'logged_in' => TRUE
);

           $this->session->set_userdata($newdata);
	            $this->onLoginSuccess();
	        } else {
		        $this->loadView('staff/login', $result);
	        } 
	        
	    } else {
	        $dataset['msg']="Emailid Password should not balnk";
	        $this->loadView('staff/login',$dataset);
	    }
	}
	public function loadView($view,$sendData){
	    $this->load->view($view, $sendData);
	}
	public function onLoginSuccess(){
		
	    $this->load->view('staff/home');
	}
}
