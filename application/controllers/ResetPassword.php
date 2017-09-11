<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/6/2017
 * Time: 3:48 PM
 */?>
<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class ResetPassword extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $isLoggedIn = $this->session->userdata('logged_in');
        if($isLoggedIn){
            redirect(SITE.'dashboard');
        }
        $this->load->database();

        //	$this->load->helper('dynmic-css-js');
        $this->load->model('User_model');
        $this->load->model('Admin_model');
        $this->load->model('Client_model');

        $this->load->library('Notification_lib');
        $this->load->helper('cookie');

    }

    public function index()
    {
        $this->form_validation->set_rules('submit', 'Submit', 'required');
        $this->form_validation->set_rules('email', 'Email', 'callback_emailExist');
        if ($this->form_validation->run() == FALSE) {
            //$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Profile has updated</div>');
            $this->load->view('resetPassword/index');
        }else{
            //bZCfN0yX3Dxrt2cEFQMn3gMtQDBdHAxHUt7Jl6Yjy/UhqkD0zz9lOnKwVlRM9Di99GwubbUp5Z58VUMQvDFzfA==  -- 112233
            $password       = mt_rand(100000,999999);

            $email                          = $_POST['email'];
            $userId                         = false;
            $data                           = $this->User_model->userEmailExistExceptId($userId ,$email);

            if($data[0]->intUserTypeId == '5'){
                $clientDetail               = $this->Client_model->getClientDetailsByEmail($email);
                $client_id                  = $clientDetail[0]->client_id;
                $clientDetail               = $this->Client_model->getPrimaryUser($client_id);
                $data[0]->varFirstName      = $clientDetail[0]->first_name;
                $data[0]->varLastName       = $clientDetail[0]->last_name;
            }

            $id                             = $data[0]->intUserId;

            $insert                         = array();
            $insert['intUserId']            = $id;
            $insert['varPassword']          = $this->encrypt->encode($password);
            $this->User_model->updateUser($insert);

            $shortCodeArray                             = array();
            $shortCodeArray['client_first_name']        = $data[0]->varFirstName;
            $shortCodeArray['client_last_name']         = $data[0]->varLastName;
            $shortCodeArray['client_email']             = $data[0]->varEmailId;
            $shortCodeArray['client_password']          = $password;

            $notificationArray                                              = array();
            $userType                                                       = 'Client';
            $notificationArray[$userType]                                   = array();

            $notificationArray[$userType]['email']                          = array($email);
            $notificationArray[$userType]['number']                         = array($data[0]->varMobileNoaq);
            $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

            $userType                                                       = 'Admin';
            $notificationArray[$userType]                                   = array();

            $allAdmins          = $this->User_model->getUsersByUserType('Admin');
            $adminEmails        = array();
            $adminNumbers       = array();
            if($allAdmins){
                foreach ($allAdmins as $Admin){
                    $adminEmails[] = $Admin->varEmailId;
                    $adminNumbers[] = $Admin->varMobileNo;
                }
            }

            $notificationArray[$userType]['email']                          = $adminEmails;
            $notificationArray[$userType]['number']                         = $adminNumbers;
            $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

            $this->notification_lib->resetPasswordNotification($notificationArray);

            $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Password Reset Please Check Your Email</div>');
            redirect(SITE.'ResetPassword');
        }

    }
    public function emailExist($email){
        $userId = false;
        $isEmailExist = $this->User_model->userEmailExistExceptId($userId , $email);
        if (empty($isEmailExist)) {
            $this->form_validation->set_message('emailExist', '{field} not exist');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
}
?>