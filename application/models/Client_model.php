<?php

	class Client_model extends CI_Model 
        {

		public function __construct() {

			parent::__construct();

			$this->load->database();

			$this->load->helper('date');

		}
                public function create($data)
                {
                    $client['company_name'] = $data['company_name'];
                    $client['company_website'] = $data['company_website'];
                    $client['email'] = $data['email'];
                    $client['address'] = $data['address'];
                    $client['city'] = $data['city'];
                    $client['country_id'] = $data['country_id'];
                    $client['level_id'] = $data['level_id'];
                    $client['phone_no'] = $data['phone_no'];
                    $client['created_on'] = date('Y-m-d H:i:s');
                    
                    
                    $this->db->insert('client_table',$client);
                    $lastid=$this->db->insert_id();
                }
                public function getClientDetailsByID($client_id)
                {
                    $this->db->select('client_table.*');
                    $this->db->from('client_table');
                    $this->db->where('client_id', $client_id);
                    
                    $query = $this->db->get();
                    return $query->result();
                }
                public function getActiveClients()
                {
                    $this->db->select('client_table.*');
                    $this->db->from('client_table');
                    $this->db->where('level_id', '3');
                    
                    $query = $this->db->get();
                    return $query->result();
                }
                public function update($data)
                {
                    $client['company_name'] = $data['company_name'];
                    $client['company_website'] = $data['company_website'];
                    $client['email'] = $data['email'];
                    $client['address'] = $data['address'];
                    $client['city'] = $data['city'];
                    $client['country_id'] = $data['country_id'];
                    $client['phone_no'] = $data['phone_no'];
                    $client['domestic_rates'] = $data['domestic_rates'];
                    $this->db->where('client_id',$data['client_id']);
                    $this->db->update('client_table',$client);
                }
                public function getClients()
                {
                    $this->db->select('client_table.*,client_level.*');
                    $this->db->from('client_table');
                    $this->db->join('client_level', 'client_table.level_id = client_level.level_id'); 
                    $query = $this->db->get();
                    return $query->result();
                }
                public function getClientDetails($id)
                {
                    $this->db->select('client_table.*,client_level.*');
                    $this->db->from('client_table , client_level');
                    $this->db->where('client_table.level_id = client_level.level_id');
                    $this->db->where('client_table.client_id',$id);
                    $query = $this->db->get();
                    return $query->result();
                }
                public function getClientDetailsByEmail($email)
                {
                    $this->db->select('client_table.*,client_level.*');
                    $this->db->from('client_table , client_level');
                    $this->db->where('client_table.level_id = client_level.level_id');
                    $this->db->where('client_table.email',$email);
                    $query = $this->db->get();
                    return $query->result();
                }
                public function getPrimaryUser($id)
                {
                    $this->db->where('client_id' , $id);
                    $query = $this->db->get('client_contact_primary');
                    return $query->result();
                    
                }
                public function insertPrimaryUser()
                {
                    $user['first_name'] = $this->input->post('first_name');
                    $user['last_name'] = $this->input->post('last_name');
                    $user['address'] = $this->input->post('address');
                    $user['email'] = $this->input->post('email');
                    $user['phone_no'] = $this->input->post('phone_no');
                    $user['client_id'] = $this->input->post('client_id');
                    
                    $this->db->insert('client_contact_primary' , $user);
                    
                }
                public function updateContact()
                {
                    $client['domestic_rates'] = $this->input->post('domestic_rate'); 
                    $client['level_id'] = '2';
                    $this->db->where('client_id',$this->input->post('client_id'));
                    $this->db->update('client_table',$client);
                    
                }
                public function suspend($client_id)
                {
                    $client['level_id'] = '4';
                    $this->db->where('client_id',$client_id);
                    $this->db->update('client_table',$client);
                }
                public function unSuspend($client_id)
                {
                    $client['level_id'] = '3';
                    $this->db->where('client_id',$client_id);
                    $this->db->update('client_table',$client);
                }
                
                
                public function updatePropect($client_id)
                {
                    $client['level_id'] = '3';
                    $this->db->where('client_id',$client_id);
                    $this->db->update('client_table',$client);
                }
                public function markWhitelist($client_id)
                {
                    $client['level_id'] = '3';
                    $this->db->where('client_id',$client_id);
                    $this->db->update('client_table',$client);
                }
                public function markBlackList($client_id)
                {
                    $client['level_id'] = '5';
                    $this->db->where('client_id',$client_id);
                    $this->db->update('client_table',$client);
                }
	}