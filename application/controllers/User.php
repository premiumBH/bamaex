<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class User extends CI_Controller {



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



	//	$this->load->helper('dynmic-css-js');

		$this->load->model('User_model');
		$this->load->model('Country_model');

		$this->load->model('Admin_model');



        $this->load->helper('cookie');		

		 

			

	}

	 

	public function index()

	{

      $this->load->view('backend');

	}

	public function create() {

	    $data['first_name']=$this->input->post('first_name');

	    $data['last_name']=$this->input->post('last_name');

	    $data['email']=$this->input->post('email');

	    $data['mobile']=$this->input->post('mobile');

            $data['staff_level_id']=$this->input->post('staff_level_id');

            $data['password']=$this->input->post('password');

            $data['add_new_user']=$this->input->post('add_new_user');

            $data['update_user']=$this->input->post('update_user');

            $data['country'] = $this->input->post('country');

	    //echo json_encode($data);

	    if(isset($data['add_new_user'])) {

	        $result=$this->User_model->create($data);

	      //  echo json_encode($result);

	        if($result['status']==true) {

	         $this->load->view('page/Dashboard/user', $result);

	        } 

			else {

	         $this->load->view('page/create-user', $result);

	        } 

	        

	    }  

		else if(isset($data['update_user']))

		{

			$result=$this->User_model->update($data);

	      //  echo json_encode($result);

	        if($result['status']==true) {



	          $this->load->view('page/Dashboard/user', $result);

	        } else {

	         $this->load->view('page/create-user', $result);

	        } 

		}

	  else {

	        $result['msg'] = 'Please inter correct values';	

	        $this->load->view('page/create-user', $result);

	    }

		

	}

	public function settings() {

		$this->load->view('page/settings');

	}

	public function profile() {
        $loggedInUserId         = $this->session->userdata('UserId');
        $loggedInUserData       = $this->User_model->getUserById($loggedInUserId);
        $loggedInUserAddress    = $this->User_model->getUserAddressByUserId($loggedInUserId);
        if($loggedInUserAddress){
            $mergeArray = array_merge((array)$loggedInUserData[0], (array)$loggedInUserAddress[0]);
            $loggedInUserData[0] = (object)$mergeArray;
        }
        $viewData                           = array();
        $viewData['userData']              = $loggedInUserData[0];
        $viewData['counties']              = $this->Country_model->get_countries();

        $this->form_validation->set_rules('submit', 'Submit', 'required');
        $this->form_validation->set_rules('email', 'Email', 'callback_emailExist');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('page/profile',$viewData);
        }else{
            $userId                     = $this->input->post('userId');
            $firstName                  = $this->input->post('firstName');
            $lastName                   = $this->input->post('lastName');
            $email                      = $this->input->post('email');
            $phone                      = $this->input->post('phone');
            $country                    = $this->input->post('country');
            $city                       = $this->input->post('city');


            $insert                                 = array();
            $insert['intUserId']                    = $userId;
            $insert['varFirstName']                 = $firstName;
            $insert['varLastName']                  = $lastName;
            $insert['varEmailId']                   = $email;
            $insert['varMobileNo']                  = $phone;
            $insert['country_id']                   = $country;

            $this->User_model->updateUser($insert);

            $insert                         = array();
            $insert['city']                 = $city;
            if($_FILES['profileImage']['name']){
                $profileImage                  = $this->uploadImage('profileImage');
                $this->session->set_userdata('profileImage', $profileImage);
                $insert['profile_image']        = $profileImage;
            }
            if($_FILES['companyLogo']['name']){
                $companyLogo                    = $this->uploadImage('companyLogo');
                $insert['company_logo']         = $companyLogo;
            }

            if($loggedInUserAddress){
                //deleting previous files
                if($_FILES['profileImage']['name']) {
                    $preProfileImage = $loggedInUserData[0]->profile_image;
                    if ($preProfileImage != '') {
                        if (is_file(realpath('.') . $preProfileImage) && file_exists(realpath('.') . $preProfileImage)) {
                            unlink(realpath('.') . $preProfileImage);
                        }
                    }
                }
                if($_FILES['companyLogo']['name']) {
                    $preCompany_logo = $loggedInUserData[0]->company_logo;
                    if ($preCompany_logo != '') {
                        if (is_file(realpath('.') . $preCompany_logo) && file_exists(realpath('.') . $preCompany_logo)) {
                            unlink(realpath('.') . $preCompany_logo);
                        }
                    }
                }

                //updating data
                $insert['address_id']    = $loggedInUserData[0]->address_id;
                $this->User_model->updateUserAddress($insert);
            }else{
                $insert['user_id']    = $userId;
                $this->User_model->insertUserAddress($insert);
            }
            $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Profile has updated</div>');
            redirect(SITE.'user/profile');
        }
	}

    public function emailExist($email){
        $userId = $this->input->post('userId');
        $isEmailExist = $this->User_model->userEmailExistExceptId($userId , $email);
        if ($isEmailExist) {
            $this->form_validation->set_message('emailExist', 'The {field} already exist');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    public function uploadImage($fileName){


        $path 	                        = realpath('.').'/theme/assets/userProfile';
        if(!is_dir($path)) {
            mkdir($path,0777);
        }
        $config['upload_path']          = $path;
        $config['allowed_types']        = 'gif|jpg|png';
        $config['allowed_types']        = '*';
        $config['max_size']             = '51200';
        $config['max_width']            = '5000';
        $config['max_height']           = '5000';
        $config['remove_spaces']        = TRUE;
        $config['encrypt_name']         = TRUE;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($fileName)) {
            $error = array('error' => $this->upload->display_errors());
            echo "<pre>"; print_r($error); exit;
        }
        else{
            $data = array('upload_data' => $this->upload->data());
            $filePath   = $data['upload_data']['full_path'];
            $filePath   = '/theme/'.explode('theme/', $filePath)['1'];
            return $filePath;
        }
    }

	public function account() {

		$this->load->view('page/account');

	}

	public function logout() {

		$this->session->sess_destroy();

		redirect(CTRL);

	}



   public function is_logged_in()

	{

				        $data["msg"]="initial";

	    	//	$this->loadView('admin/login', $data);

				

		if (isset($this->session->userdata['logged_in'])) {

			

             $username = ($this->session->userdata['username']);

             $email = ($this->session->userdata['email']);

			 // echo $username;

            } else {

		        redirect(CTRL);

               }

			   

	}

}

