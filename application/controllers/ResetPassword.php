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

        $this->load->library('Custom_email');
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
            //Admin@gmail.com
            $password       = mt_rand(100000,999999);

            $email          = $_POST['email'];
            $userId         = false;
            $data           = $this->User_model->userEmailExistExceptId($userId ,$email);
            $id             = $data[0]->intUserId;

            $insert                         = array();
            $insert['intUserId']            = $id;
            $insert['varPassword']          = $this->encrypt->encode($password);
            $this->User_model->updateUser($insert);
            $from           = 'admin@bamaex.net';
            $to             = $email;
            $subject        = 'Reset Password';
            $message        = '<b>Your new password is '.$password.'</b>';
            //echo "<pre>"; print_r($email); exit;
            $this->custom_email->send_email($from, $to, $subject, $message);
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