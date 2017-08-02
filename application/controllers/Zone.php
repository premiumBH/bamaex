<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Zone extends CI_Controller {

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
		$this->load->model('User_model');
		$this->load->model('Admin_model');
                $this->load->model('Zone_model');
                $this->load->model('Country_model');
		$this->load->library('session');
                $this->load->helper('cookie');	
        }
        public function create()
        {
       
            $data['zone_id']=$this->input->post('zone_id');
	    $data['zone_name']=$this->input->post('zone_name');
	    $data['zone_price1']=$this->input->post('zone_price1');
	    $data['zone_price2']=$this->input->post('zone_price2');
            $data['zone_price3']=$this->input->post('zone_price3');
            $data['add_new_zone']=$this->input->post('add_new_zone');
            $data['update_zone']=$this->input->post('update_zone');
            
//            
            if(isset($data['add_new_zone']) && $data['add_new_zone'] == '1') {
                
                $this->Zone_model->create($data);
                redirect(base_url().'dashboard/zone');
            }
            else if(isset($data['update_zone']) && $data['update_zone'] == '1'){
                //die('HERE');
                $this->Zone_model->update($data);
                redirect(base_url().'dashboard/zone');
               
            }
            else {
                 $this->load->view('Zone/create', $data);
            }
                
            
        }
        public function update()
        {
            $zone_id = $this->input->get('edit-id');
            $result = $this->Zone_model->getZonePrice($zone_id);
            //var_dump($result[0]->zone_id);
            $data['zone_id']=$result[0]->zone_id;
	    $data['zone_name']=$result[0]->zone_name;
	    $data['zone_price1']=$result[0]->zone_price1;
	    $data['zone_price2']=$result[0]->zone_price2;
            $data['zone_price3']=$result[0]->zone_price3;
            $this->load->view('Zone/create', $data);
        }
}