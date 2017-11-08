<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $isLoggedIn = $this->session->userdata('logged_in');
        if ($isLoggedIn) {
            redirect(SITE . 'dashboard');
        }
        $this->load->database();
        $this->load->model('User_model');
        $this->load->model('Staff_model');
        $this->load->helper('cookie');
    }

    public function index()
    {
        $this->load->view('backend');
        //
    }

    public function login()
    {
        $data['emailId'] = $this->input->post('emailId');
        $data['password'] = $this->input->post('password');

        if (isset($data['emailId']) && isset($data['password'])) {

            $result = $this->User_model->login($data);

            if (($result['status'] == true) && $result['userinfo'][0]->UserType == 'Agent') {

                $address = $this->User_model->getUserAddressByUserId($result['userinfo'][0]->UserId);
                if (!empty($address)) {
                    $userProfileImage = $address[0]->profile_image;
                } else {
                    $userProfileImage = '';
                }

                $newdata = array(
                    'UserId' => $result['userinfo'][0]->UserId,
                    'username' => $result['userinfo'][0]->FirstName . ' ' . $result['userinfo'][0]->FirstName,
                    'email' => $result['userinfo'][0]->EmailId,
                    'UserType' => $result['userinfo'][0]->UserType,
                    'UserTypeCode'=>$result['userinfo'][0]->UserTypeCode,
                    'UserTypeId'=>$result['userinfo'][0]->UserTypeId,
                    'profileImage' => $userProfileImage,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($newdata);
                redirect(SITE . 'dashboard');

            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible">Invalid Email Or Password</div>');
                redirect(SITE . 'agent/login');
            }

        } else {
            $dataset['msg'] = "Emailid Password should not balnk";
            $this->load->view('agent/login', $dataset);
        }
    }
}
