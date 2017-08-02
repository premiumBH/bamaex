<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Country extends CI_Controller {

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

		$this->load->model('User_model');
		$this->load->model('Admin_model');
                $this->load->model('Zone_model');
                $this->load->model('Country_model');

                $this->load->helper('cookie');	
                
        }
        public function create()
        {
           
            $data['id']=$this->input->post('id');
	    $data['country_code']=$this->input->post('country_code');
	    $data['country_name']=$this->input->post('country_name');
	    $data['zone_id']=$this->input->post('zone_id');
            $data['add_new_country']=$this->input->post('add_new_country');
            $data['update_country']=$this->input->post('update_country');
            $data['zones'] = $this->Zone_model->get_zones();
            //var_dump($data);
//            
            if(isset($data['add_new_country']) && $data['add_new_country'] == '1') {
                
//                print_r($data);
//                die('HERE');
                
                $this->Country_model->create($data);
                redirect(base_url().'dashboard/country');
            }
            else if(isset($data['update_country']) && $data['update_country'] == '1'){
                //die('HERE');
                $this->Country_model->update($data);
                redirect(base_url().'dashboard/country');
               
            }
            else {
                 $this->load->view('country/create', $data);
            }
                
            
        }
        public function update()
        {
            $zone_id = $this->input->get('edit-id');
            $result = $this->Country_model->getCountry($zone_id);
            //var_dump($result[0]->zone_id);
            $data['id']=$result[0]->id;
	    $data['country_name']=$result[0]->country_name;
	    $data['country_code']=$result[0]->country_code;
	    $data['zone_id']=$result[0]->zone_id;
            $data['zones'] = $this->Zone_model->get_zones();
            $this->load->view('country/create', $data);
        }
}