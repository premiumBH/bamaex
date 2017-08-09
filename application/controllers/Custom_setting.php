<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/9/2e: 11:10 AM
 */
class Custom_setting extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $isLoggedIn = $this->session->userdata('logged_in');
        if(!$isLoggedIn){
            redirect(SITE.'backend');
        }
        $this->load->model('Setting_model');
    }

    public function index(){
        $viewData                                       = array();
        $viewData['paymentTypes']                       = $this->Setting_model->getMyData('payment_types', array());
        $viewData['services']                           = $this->Setting_model->getMyData('service_table', array());
        $viewData['config']                             = $this->Setting_model->getMyData('site_config', array());
        $this->load->view('setting/index',$viewData);
    }

    public function payment_type_form(){
        $this->form_validation->set_rules('submit', 'Submit', 'required');
        $this->form_validation->set_rules('paymentType', 'Payment Type', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible">Please enter type</div>');
           redirect(SITE.'custom_setting');
        }else{

            $id                                     = $this->input->post('id');
            $type                                   = $this->input->post('paymentType');
            $insert                                 = array();
            $insert['payment_type']                 = $type;
            if($id != ''){
                $insert['id']                       = $id;
                $this->Setting_model->updatePaymentType($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Update Successfully</div>');
            }else{
                $this->Setting_model->insertPaymentType($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Insert Successfully</div>');
            }
            redirect(SITE.'custom_setting');
        }
    }

    public function deletePaymentType($id){
        $where              = array();
        $where['id']        = $id;
        $this->Setting_model->deleteMyData('payment_types', $where);
        $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Deleted Successfully</div>');
        redirect(SITE.'custom_setting');
    }


    public function service_form(){
        $this->form_validation->set_rules('submit', 'Submit', 'required');
        $this->form_validation->set_rules('service', 'Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible">Please enter service name</div>');
            redirect(SITE.'custom_setting');
        }else{

            $id                                     = $this->input->post('id');
            $type                                   = $this->input->post('service');
            $insert                                 = array();
            $insert['service_name']                 = $type;
            if($id != ''){
                $insert['id']                       = $id;
                $this->Setting_model->updateService($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Update Successfully</div>');
            }else{
                $this->Setting_model->insertService($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Insert Successfully</div>');
            }
            redirect(SITE.'custom_setting');
        }
    }

    public function deleteService($id){
        $where              = array();
        $where['id']        = $id;
        $this->Setting_model->deleteMyData('payment_types', $where);
        $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Deleted Successfully</div>');
        redirect(SITE.'custom_setting');
    }

    public function config_form(){
        $this->form_validation->set_rules('submit', 'Submit', 'required');
        $this->form_validation->set_rules('keyName', 'Key Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible">Please enter key name</div>');
            redirect(SITE.'custom_setting');
        }else{

            $id                                     = $this->input->post('id');
            $keyName                                = $this->input->post('keyName');
            $keyVal                                 = $this->input->post('keyVal');
            $insert                                 = array();

            $insert['key_name']                     = $keyName;
            $insert['key_value']                    = $keyVal;
            if($id != ''){

                $where          = array();
                $where['id']    = $id;
                $configData     = $this->Setting_model->getMyData('site_config',$where);
                if(!empty($configData) && $configData[0]->is_deleteable == 0){
                   unset($insert['key_name']);
                }
                $insert['id']                       = $id;
                $this->Setting_model->updateConfig($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Update Successfully</div>');
            }else{
                $insert['is_deleteable'] = isset($_POST['isDeletable'])?'1':'0';
                $this->Setting_model->insertConfig($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Insert Successfully</div>');
            }
            redirect(SITE.'custom_setting');
        }
    }


    public function deleteConfig($id){
        $where              = array();
        $where['id']        = $id;
        $configData         = $this->Setting_model->getMyData('site_config',$where);
        if(!empty($configData) && $configData[0]->is_deleteable == 0){
            $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">This record is not deletable</div>');
            redirect(SITE.'custom_setting');
        }
        $where              = array();
        $where['id']        = $id;
        $this->Setting_model->deleteMyData('site_config', $where);
        $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Deleted Successfully</div>');
        redirect(SITE.'custom_setting');
    }


}

