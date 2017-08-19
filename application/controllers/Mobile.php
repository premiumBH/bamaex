<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mobile extends CI_Controller {
        public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('User_model');
		$this->load->model('Admin_model');
                $this->load->model('Zone_model');
                $this->load->model('Country_model');
                $this->load->model('Client_model');
		$this->load->library('session');
                $this->load->helper('cookie');	
                $this->load->library('encrypt');
        }
        public function mobileLogin()
        {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            
            if(isset($email) && isset($password))
            {
                //$password = $this->encrypt->encode($password);
                //echo $password;
                $user = $this->db->where('email' , $email)->get('courier')->result();
                if(isset($user[0]))
                {
                    $id = $user[0]->id;
                    $passwd = $user[0]->password;
                    $passwd = $this->encrypt->decode($passwd);
                    if($passwd === $password)
                    {
                        $data['login'] = '1';
                        $data['message'] = 'Successfull';
                        $data['user_id'] = $id;
                        
                    }
                    else
                    {
                        $data['login'] = '0';
                        $data['message'] = 'UnSuccessfull';
                    }
                }
                else
                {
                    $data['login'] = '0';
                    $data['message'] = 'UnSuccessfull';
                }
            }
            else
            {
                $data['login'] = '0';
                $data['message'] = 'UnSuccessfull';
            }
            
            
            
            echo json_encode($data);
        }
        public function getOrders()
        {
            $id = $this->input->post('user_id');
            $data = $this->db->query('select order_details.* , client_order_assignment.* from order_details , client_order_assignment where order_details.order_id = client_order_assignment.order_id and client_order_assignment.courier_id = '.$id)->result();
            
            echo json_encode($data);
        }
        public function updateTracking()
        {
            $airway_bill = $this->input->post('bill');
            $courier_id  = $this->input->post('courier_id');
            $subject = $this->input->post('subject');
            $info = $this->input->post('info');
            $ordr_status = $this->input->post('status');
            
            $airway_bill = ltrim($airway_bill, '0');
            
            if($ordr_status == "Pending")
            {
                $catalog['order_id'] = $this->db->query('SELECT * FROM order_airway_bill WHERE airway_bill like "%'.$airway_bill.'"' )->result()[0]->order_id;
                $catalog['shipper_id'] = $courier_id;
                $catalog['catalog_subject'] = $subject;
                $catalog['catalog_info'] = $info;
                $catalog['updated_by'] = "Admin";
                $catalog['created_by'] = "Admin";
                $catalog['created_on'] = date('Y-m-d H:i:s');
                
               $this->db->insert('order_status_catalog' , $catalog);
            }
            else
            {
                $catalog['order_id'] = $this->db->query('SELECT * FROM order_airway_bill WHERE airway_bill like "%'.$airway_bill.'"' )->result()[0]->order_id;
                $catalog['shipper_id'] = $courier_id;
                $catalog['catalog_subject'] = $subject;
                $catalog['catalog_info'] = $info;
                $catalog['updated_by'] = "Admin";
                $catalog['created_by'] = "Admin";
                $catalog['created_on'] = date('Y-m-d H:i:s');
                
                $this->db->insert('order_status_catalog' , $catalog);
                
                $order_details['order_status'] = '2';
                $order_id = $this->db->query('SELECT * FROM order_airway_bill WHERE airway_bill like "%'.$airway_bill.'"' )->result()[0]->order_id;
                $this->db->where('order_id' , $order_id )->update('order_details' , $order_details );
                
            }
                  
            
            $data['status'] = '0';
            echo json_encode($data);
        }
}