<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccessControl extends CI_Controller {

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
	}
	public function pages()
	{
	    $data['parent_page']=$this->input->post('parent_page');
	    $data['page_label']=$this->input->post('page_label');
	    $data['page_icon']=$this->input->post('page_icon');
	    $data['page_add']=$this->input->post('page_add');
		$data['add_new_page']=$this->input->post('add_new_page');
		$data['update_page']=$this->input->post('update_page');
		$data1['update_access_pages']=$this->input->post('update_access_pages');
	    //echo json_encode($data);
	    if(isset($data['add_new_page'])) {
	        
	        $result=$this->Admin_model->insert_pages($data);
	      //  echo json_encode($result);
	        if($result['status']==true) {

	        $data['result1'] = $this->Admin_model->get_pages($data);	
	         $this->load->view('page/Access Control/pages', $data);
	        } 
			else {
	        $data['result1'] = $this->Admin_model->get_pages($data);
	         $this->load->view('page/Access Control/pages', $data);
	        } 
	        
	    } 
	    else if(isset($data['update_page'])) {
	        
	        $result=$this->Admin_model->update_single_page($data);
	      //  echo json_encode($result);
	        if($result['status']==true) {

	        $data['result1'] = $this->Admin_model->get_pages($data);	
	         $this->load->view('page/Access Control/pages', $data);
	        } 
			else {
	        $data['result1'] = $this->Admin_model->get_pages($data);
	         $this->load->view('page/Access Control/pages', $data);
	        } 
	        
	    } 
		else if(isset($data1['update_access_pages']))
		{
			$data1['id'] = $this->input->post('id');
			$data1['admin'] = $this->input->post('admin');
			$data1['staff'] = $this->input->post('staff');
			$data1['client'] = $this->input->post('client');
			$data1['other'] = $this->input->post('other');

			
			$result=$this->Admin_model->update_pages($data1);
	      //  echo json_encode($result);
	        if($result['status']==true) {

	        $data1['result1'] = $this->Admin_model->get_pages($data1);	
	         $this->load->view('page/Access Control/pages', $data1);
	        } else {
	        $data1['result1'] = $this->Admin_model->get_pages($data1);	
	         $this->load->view('page/Access Control/pages', $data1);
	        } 
		}
	  else {
	        $data1['result1'] = $this->Admin_model->get_pages($data1);	
	         $this->load->view('page/Access Control/pages', $data1);
	    }
	}
    public function delete_page($pageId){
       $this->Admin_model->deletePage($pageId);
       redirect(SITE.'pages');
	}
//  update user roles start

	public function user_types()
	{
	    $data['user_type_add']=$this->input->post('user_type_add');
	    $data['add_new_user_type']=$this->input->post('add_new_user_type');
	    $data['update_single_user_type']=$this->input->post('update_single_user_type');
	    $data1['update_access_user_type']=$this->input->post('update_access_user_type');
	    if(isset($data['add_new_user_type'])) {
	        
	        $result=$this->Admin_model->insert_user_type($data);
	      //  echo json_encode($result);
	        if($result['status']==true) {

	        $data['result1'] = $this->Admin_model->get_user_type($data);	
	         $this->load->view('page/Access Control/user-type', $data);
	        } 
			else {
	        $data['result1'] = $this->Admin_model->get_user_type($data);
	         $this->load->view('page/Access Control/user-type', $data);
	        } 
	        
	    } 

	    else if(isset($data['update_single_user_type'])) {
	        
	        $result=$this->Admin_model->update_single_user_type($data);
	      //  echo json_encode($result);
	        if($result['status']==true) {
	        $data1['result1'] = $this->Admin_model->get_user_type($data1);	
	         $this->load->view('page/Access Control/user-type', $data1);
	        } 
			else {
	        $data1['result1'] = $this->Admin_model->get_user_type($data1);	
	        $this->load->view('page/Access Control/user-type', $data1);
	        } 
	        
	    } 
		else if(isset($data1['update_access_user_type']))
		{
			$data1['id'] = $this->input->post('id');
			$data1['admin'] = $this->input->post('admin');
			$data1['staff'] = $this->input->post('staff');
			$data1['client'] = $this->input->post('client');
			$data1['other'] = $this->input->post('other');

			
			$result=$this->Admin_model->update_user_type($data1);
	      //  echo json_encode($result);
	        if($result['status']==true) {

	        $data1['result1'] = $this->Admin_model->get_user_type($data1);	
	         $this->load->view('page/Access Control/user-type', $data1);
	        } else {
	        $data1['result1'] = $this->Admin_model->get_user_type($data1);	
	         $this->load->view('page/Access Control/user-type', $data1);
	        } 
		}
	  else {
	        $data1['result1'] = $this->Admin_model->get_user_type($data1);	
	         $this->load->view('page/Access Control/user-type', $data1);
	    }
		
// update user roles end
	       
	}
	public function create_page()
	{
		$this->load->view('page/Access Control/create-page');
	}


}
