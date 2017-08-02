<?php
	class Staff_model extends CI_Model {
		public function __construct() {
			parent::__construct();
			$this->load->database();
			$this->load->helper('date');
		}
		
	}
	?>