<?php

	class Client_rates_model extends CI_Model {

		

	

		public function __construct() {

                    parent::__construct();
                    $this->load->database();
                    $this->load->helper('date');
                    $this->load->library('encrypt');
                    $this->load->model('Zone_model');
		}
                
                public function saveProspectRates()
                {
                    //die(var_dump($_POST));
                    $client_id = $this->input->post('client_id');
                    $zone_rates = $this->input->post('zone');
                    $zone_ids = $this->input->post('zone_id');
                    $counter = 0;
                    foreach($zone_rates as $zone_rate)
                    {
                        $rate['client_id'] = $client_id;
                        $zone = $zone_rate ; 
                        $rate['zone_id'] = $zone_ids[$counter];
                        $rate['zone_rate'] = $zone_rate;
                        $rate['updated_by'] = 'admin';
                        $rate['created_by'] = 'admin';
                        $rate['created_on'] = date('Y-m-d H:i:s');
                        $rate['updated_on'] = date('Y-m-d H:i:s');
                        
                        $this->db->insert('client_rates',$rate);
                        
                        $counter = $counter + 1;
                        
                    }
//                    var_dump($zone_rates);
//                    die('HERE');
                }
                public function update_rates($client)
                {
                    
                        
                    $this->db->where('client_id' ,$client['client_id'] );
                    $this->db->where('zone_id' ,$client['zone_id'] );
                    $this->db->update('client_rates' , $client);
                }
                public function checkZoneDifference($client_id)
                {
//                    $this->db->select('zone.*');
//                    $this->db->from('zone , client_rates');
//                    
//                    $this->db->where('zone.zone_id != client_rates.zone_id');
                    $query = $this->db->query('select * from zone where zone_id not in (select zone_id from client_rates where client_id = '.$client_id.')');
                    $zones = $query->result();
                    foreach($zones as $zone)
                    {
                        $client_rate['client_id'] = $client_id;
                        $client_rate['zone_id'] = $zone->zone_id;
                        $client_rate['zone_rate'] = '0';
                        $client_rate['updated_by'] = 'admin';
                        $client_rate['created_by'] = 'admin';
                        $client_rate['created_on'] = date('Y-m-d H:i:s');
                        $this->db->insert('client_rates' , $client_rate);
                    }
                }
                public function getClientRates($client_id)
                {
                    $this->checkZoneDifference($client_id);
                    
                    $this->db->select('zone.*,client_rates.*');
                    $this->db->from('zone , client_rates');
                    
                    $this->db->where('zone.zone_id = client_rates.zone_id');
                    $this->db->where('client_rates.client_id',$client_id);

                    //$this->db->join('zone_price', 'zone.zone_id = zone_price.zone_id'); 
                    $query = $this->db->get();
                    return $query->result();
                    
                    
                    
                }
        }