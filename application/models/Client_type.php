<?php

	class Client_type extends CI_Model 
        {

		public function __construct() {

			parent::__construct();

			$this->load->database();

			$this->load->helper('date');

		}
                public function getLevelid($level)
                {
                    if(isset($level))
                    {
                        $this->db->select('level_id');
                        $this->db->where('level_name' , $level);
                        $value = $this->db->get('client_level')->result();
                       
                        return $value[0]->level_id;
                    }
                    else
                        return "";
                }
                
        }