<?php
	class Admin_model extends CI_Model {
		public function __construct() {
			parent::__construct();
			$this->load->database();
			$this->load->helper('date');
		}
		public function get_pages($data) {
			 $sqlQuery=" SELECT "
			                ."intID AS Id,"
			                ." Label AS label,"
			                ." varPageType AS PageType,"
			                ." varPageSlug AS PageSlug,"
			                ." varIcon AS icon,"
			                ." enumAdministrator AS Admin,"
			                ." enumStaff AS Staff,"
			                ." enumClient AS Client,"
			                ." enumOther AS Other,"
			                ." dtcreated AS dt"
			        ." FROM access_control";
			$result = $this->db->query($sqlQuery);
			if($result->num_rows()>0) {
				$response['status']="true";
				$response['pages']=$result->result();
				return $response;
			} else {
				$response['status']=false;
				$response['msg']='Pages does not exist';
				
				return $response;
			}
			
		}

        public function deletePage($id){
            $this->db->where('intID', $id);
            $this->db->delete('access_control');
            return true;
		}
		public function get_user_type($data) {
			 $sqlQuery=" SELECT "
			                ."intUserTypeId AS role_id,"
			                ." varUserTypeName AS role_name,"
			                ." enumAdministrator AS Admin,"
			                ." enumStaff AS Staff,"
			                ." enumClient AS Client,"
			                ." enumOther AS Other,"
			                ." dtcreated AS dt"
			        ." FROM user_type";
			$result = $this->db->query($sqlQuery);
			if($result->num_rows()>0) {
				$response['status']="true";
				$response['pages']=$result->result();
				return $response;
			} else {
				$response['status']=false;
				$response['msg']='Pages does not exist';
				
				return $response;
			}
			
		}
		public function insert_pages($data) {
			$dt = date('Y-m-d H:i:s');
			$pp = $data['parent_page'];
			$pl = $data['page_label'];
			$pi = $data['page_icon'];
			$pg = $data['page_add'];
			 $sqlQuery="INSERT INTO `access_control`(`inParentId`, `Label`, `varPageType`, `varPageSlug`, `varIcon`, `dtcreated`) VALUES($pp, '$pl', 'page','$pg', '$pi', '$dt')";
			$result = $this->db->query($sqlQuery);
				$response['status']="true";
				return $response;
		}
		public function insert_user_type($data) {
			$dt = date('Y-m-d H:i:s');
			$role = $data['user_type_add'];
			 $sqlQuery="INSERT INTO `user_type`(`varUserTypeName`, `dtcreated`) VALUES('$role', '$dt')";
			$result = $this->db->query($sqlQuery);

				$response['status']="true";
				return $response;
		}
		public function update_single_page($data)
		{
			$id = $data['update_page'];
			$dt = date('Y-m-d H:i:s');
			$pp = $data['parent_page'];
			$pl = $data['page_label'];
			$pi = $data['page_icon'];
			$pg = $data['page_add'];
			 $sqlQuery="UPDATE access_control SET inParentId = $pp, Label = '$pl', varPageSlug = '$pg', varIcon = '$pi', dtcreated = '$dt' WHERE intID=$id";
			$result = $this->db->query($sqlQuery);

				$response['status']="true";
				return $response;
			
		}
		public function update_single_user_type($data)
		{
			$id = $data['update_single_user_type'];
			$dt = date('Y-m-d H:i:s');
			$pg = $data['user_type_add'];
			 $sqlQuery="UPDATE user_type SET varUserTypeName = '$pg', dtcreated = '$dt' WHERE intUserTypeId=$id";
			$result = $this->db->query($sqlQuery);

				$response['status']="true";
				return $response;
			
		}
		public function update_pages($data) {
			
			$dt = date('Y-m-d H:i:s');
			$i = 0;
			foreach($data['id'] AS $id)
			{	
			$admin = 0;$staff = 0;$client = 0;$other = 0;
			if(isset($data['admin'][$id]))
			{
			if($data['admin'][$id] == 'on')
			$admin = 1;
			}
			if(isset($data['staff'][$id]))
			{
			if($data['staff'][$id] == 'on')
			$staff = 1;
			}
			if(isset($data['client'][$id]))
			{
			if($data['client'][$id] == 'on')
			$client = 1;
			}
			if(isset($data['other'][$id]))
			{
			if($data['other'][$id] == 'on')
			$other= 1;
			}
			$sqlQuery="UPDATE access_control SET enumAdministrator=$admin, enumStaff=$staff, enumClient=$client, enumOther=$other WHERE intID=$id";
			 $result = $this->db->query($sqlQuery);
             $i++;
			}

				return true;
		}
		public function update_user_type($data) {
			
			$dt = date('Y-m-d H:i:s');
			$i = 0;
			foreach($data['id'] AS $id)
			{	
			$admin = 0;$staff = 0;$client = 0;$other = 0;
			if(isset($data['admin'][$id]))
			{
			if($data['admin'][$id] == 'on')
			$admin = 1;
			}
			if(isset($data['staff'][$id]))
			{
			if($data['staff'][$id] == 'on')
			$staff = 1;
			}
			if(isset($data['client'][$id]))
			{
			if($data['client'][$id] == 'on')
			$client = 1;
			}
			if(isset($data['other'][$id]))
			{
			if($data['other'][$id] == 'on')
			$other= 1;
			}
			$sqlQuery="UPDATE user_type SET enumAdministrator=$admin, enumStaff=$staff, enumClient=$client, enumOther=$other WHERE intUserTypeId=$id";
			 $result = $this->db->query($sqlQuery);
             $i++;
			}

				return true;
		}
		

}