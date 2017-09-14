<?php

	class User_model extends CI_Model {

		

	

		public function __construct() {

			parent::__construct();

			$this->load->database();

			$this->load->helper('date');

			$this->load->library('encrypt');

		}

		

		

		public function create($data)

		{

			$dt = date('Y-m-d H:i:s');

			$fn = $data['first_name'];

			$ln = $data['last_name'];

			$em = $data['email'];

			$mb = $data['mobile'];

			$co = $data['country'];

			$pass = $this->encrypt->encode($data['password']);

			$sl = $data['staff_level_id'];

			  $sqlQuery="INSERT INTO `user`(`varFirstName`, `varLastName`, `varEmailId`, `varMobileNo`, `varPassword`, `intUserTypeId`, `dtCreated` , `country_id`) VALUES('$fn', '$ln', '$em','$mb', '$pass', $sl, '$dt' , '$co')";

			$result = $this->db->query($sqlQuery);

			$insert_id = $this->db->insert_id();

			$owid = $this->session->userdata['UserId'];

			  $sqlQuery="INSERT INTO `user_owner`(`intUserId`, `intOwnerUserId`) VALUES($insert_id, $owid)";

			$result = $this->db->query($sqlQuery);



				return true;

			

		}

		public function update($data)

		{

			$dt = date('Y-m-d H:i:s');

			$id = $data['update_user'];

			$fn = $data['first_name'];

			$ln = $data['last_name'];

			$em = $data['email'];

			$mb = $data['mobile'];

			$co = $data['country'];

			$pass = $this->encrypt->encode($data['password']);

			$sl = $data['staff_level_id'];

			  $sqlQuery="UPDATE `user` SET varFirstName = '$fn', varLastName = '$ln', varEmailId = '$em', varMobileNo = '$mb', varPassword = '$pass', intUserTypeId = $sl, dtCreated = '$dt', country_id = '$co' where intUserId = $id";

			$result = $this->db->query($sqlQuery);

			

				return true;

		}

		

		public function login($data) {

			 $sqlQuery=" SELECT "

			                ."`intUserId` as UserId,"

			                ." `varFirstName` as FirstName,"

			                ." `varLastName` as LastName,"

			                ." `varEmailId` as EmailId,"

							."`varPassword` as UserPass, "

			                ." (SELECT `varUserTypeName` from user_type where user_type.intUserTypeId= user.`intUserTypeId`) as UserType,"

			                ." (SELECT `varUserTypeCode` from user_type where user_type.intUserTypeId= user.`intUserTypeId`) as UserTypeCode, "

                            ." (SELECT `intUserTypeId` from user_type where user_type.intUserTypeId= user.`intUserTypeId`) as UserTypeId "

			        ."FROM `user` where `enumStatus`='1' AND `varEmailId`='".$data['emailId']."'";

			//$sqlQuery 	="SELECT * FROM user_regist WHERE varEmailId='".$emailId."'";
			$result = $this->db->query($sqlQuery);

		

			if($result->num_rows()>0) {

				$response['status']="true";

				$response['userinfo']=$result->result();

				if($this->encrypt->decode($response['userinfo'][0]->UserPass) == $data['password'])
                                
                                //die(print_r($response));
                                    
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

			 $sqlQuery="SELECT *, user.enumStatus AS userStatus FROM user LEFT JOIN user_type ON user_type.intUserTypeId=user.intUserTypeId where user.intUserId IN $ids AND user.intUserTypeId != 1";

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

        public function getUserById($id){
            $this->db->where('intUserId', $id);
            $query		= $this->db->get('user');
            $Result 	= $query->result();
            return $Result;
            
		}
        public function getUserAddressByUserId($id){
            $this->db->where('user_id', $id);
            $query		= $this->db->get('user_address');
            $Result 	= $query->result();
            return $Result;
        }

        public function insertUserAddress($data){
            $this->db->insert('user_address', $data);
            return $this->db->insert_id();
        }

        public function updateUserAddress( $data){

            $this->db->where('address_id', $data['address_id']);
            $this->db->update('user_address',$data);
        }

        public function userEmailExistExceptId($id = false,$email){
            $this->db->where('varEmailId', $email);
            if($id){
                $this->db->where('intUserId !=', $id);
            }
            $query		= $this->db->get('user');
            $Result 	= $query->result();
            return $Result;
        }

        public function updateUser( $data){
            $this->db->where('intUserId', $data['intUserId']);
            $this->db->update('user',$data);
        }

        public function deleteUser( $userId){
            $this->db->where('intUserId',$userId);
            $this->db->delete('user');
        }

        public function getUsersByUserType($userType){
            $this->db->select('*');
            $this->db->from('user');
            $this->db->join('user_type', 'user.intUserTypeId = user_type.intUserTypeId');
            $this->db->where('user_type.varUserTypeName', $userType);
            $query		= $this->db->get();
            $Result 	= $query->result();
            return $Result;
        }

        public function getUserType(){
            $this->db->where('varUserTypeName != ','Administrator');
            $query		= $this->db->get('user_type');
            $Result 	= $query->result();
            return $Result;
        }
        public function getAllUserType(){
            $query		= $this->db->get('user_type');
            $Result 	= $query->result();
            return $Result;
        }

        public function isEmailExist($email, $id = false){
            $this->db->where('varEmailId', $email);
            if($id){
                $this->db->where('intUserId != ', $id);
            }
            $query		= $this->db->get('user');
            $Result 	= $query->result();
            return $Result;
        }
}