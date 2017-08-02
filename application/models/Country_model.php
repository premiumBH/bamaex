<?php

	class Country_model extends CI_Model {

		

	

		public function __construct() {

			parent::__construct();

			$this->load->database();

			$this->load->helper('date');

			$this->load->library('encrypt');

		}

		
                public function get_countries()
                {

                    
                    $this->db->select('country_table.*,zone.*');
                    $this->db->from('country_table');
                    $this->db->join('zone', 'country_table.zone_id = zone.zone_id'); 
                    $this->db->order_by('id', 'ASC');
                    $query = $this->db->get();
                    return $query->result();
                    
                }
		

		public function create($data)

		{
                   
                   //$dt = date('Y-m-d H:i:s');
                    $zone['country_name'] = $data['country_name'];
                    $zone['country_code'] = $data['country_code'];
                    $zone['edt'] = date('Y-m-d H:i:s');
                    $zone['eip'] = '127.0.0.1';
                    $zone['zone_id'] = $data['zone_id'];
                    
                   // print_r($zone);
                    
                    $this->db->insert('country_table',$zone);
//                    $lastid=$this->db->insert_id();
//                    
//                   
//                    
//                    $zone_price['zone_id'] = $lastid;
//                    $zone_price['zone_price1'] = $data['zone_price1'];
//                    $zone_price['zone_price2'] = $data['zone_price2'];
//                    $zone_price['zone_price3'] = $data['zone_price3'];
//                    $zone_price['zone_price4'] = '0.0';
//                    $zone_price['zone_price5'] = '0.0';
//                    $zone_price['created_on'] = date('Y-m-d H:i:s');
//                    $zone_price['updated_on'] = date('Y-m-d H:i:s');
//                    $zone_price['created_by'] = 'admin';
//                    $zone_price['updated_by'] = 'admin';
//                    
//                    $this->db->insert('zone_price',$zone_price);
//                    

		}
                public function getCountry($id)
                {
//                    $this->db->select('zone.*,zone_price.*');;
//                    $this->db->from('zone , zone_price');
//                    
//                    $this->db->where('zone.zone_id = zone_price.zone_id');
                    $this->db->where('id',$id);

                    //$this->db->join('zone_price', 'zone.zone_id = zone_price.zone_id'); 
                    $query = $this->db->get('country_table');
                    return $query->result();
                }
		public function update($data)
		{
                   
                    


                    //$zone['zone_id'] = $this->input->get('edit-id');
                    $zone['country_name'] = $data['country_name'];
                   // $zone['created_on'] = date('Y-m-d H:i:s');
                    $zone['country_code'] = $data['country_code'];
                   // $zone['created_by'] = 'admin';
                    $zone['zone_id'] = $data['zone_id'];
                   
                    
                   
                    $this->db->where('id',$data['id']);
                    $this->db->update('country_table',$zone);
                    //die('HERE');
                    
                    
                   

		}

		

		public function login($data) {

			 $sqlQuery=" SELECT "

			                ."`intUserId` as UserId,"

			                ." `varFirstName` as FirstName,"

			                ." `varLastName` as LastName,"

			                ." `varEmailId` as EmailId,"

							."`varPassword` as UserPass, "

			                ." (SELECT `varUserTypeName` from user_type where user_type.intUserTypeId= user.`intUserTypeId`) as UserType,"

			                ." (SELECT `varUserTypeCode` from user_type where user_type.intUserTypeId= user.`intUserTypeId`) as UserTypeCode "

			        ."FROM `user` where `enumStatus`='1' AND `varEmailId`='".$data['emailId']."'";

			//$sqlQuery 	="SELECT * FROM user_regist WHERE varEmailId='".$emailId."'";

			$result = $this->db->query($sqlQuery);

		

			if($result->num_rows()>0) {

				$response['status']="true";

				$response['userinfo']=$result->result();

				if($this->encrypt->decode($response['userinfo'][0]->UserPass) == $data['password'])

				return $response;

			} else {

				$response['status']=false;

				$response['msg']='User does not exist';

				

				return $response;

			}

			//return json_encode($data);

			//return json_encode($result);

		}

		public function get_users($data) {

			$id = $data['UserId'];

			



			 $sqlQuery="SELECT * FROM user_owner WHERE intOwnerUserId = $id UNION SELECT * FROM user_owner WHERE intOwnerUserId IN (SELECT intUserId FROM user_owner WHERE intOwnerUserId = $id)";

			$result = $this->db->query($sqlQuery);

			if($result->num_rows()>0) {

				$rs = array();

				$response['users']=$result->result();

				foreach($response['users'] as $us)

				{

					$rs[] = $us->intUserId;

				

				}

				$ids = "(" . implode(",", $rs) . ")";

			 $sqlQuery="SELECT * FROM user LEFT JOIN user_type ON user_type.intUserTypeId=user.intUserTypeId where user.intUserId IN $ids AND user.intUserTypeId != 1";

			    $result = $this->db->query($sqlQuery);

				$response1['status']="true";

 				$response1['users']=$result->result();

				 // print_r($response1['users']);

				return $response1;

			} else {

				$response['status']=false;

				$response['msg']='Users does not exist';

				

				return $response;

			}

			

		}

		

}