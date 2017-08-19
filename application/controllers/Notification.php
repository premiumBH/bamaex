<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/7/2017
 * Time: 6:48 PM
 */
class Notification extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $isLoggedIn = $this->session->userdata('logged_in');
        if(!$isLoggedIn){
            redirect(SITE.'backend');
        }
        $this->load->model('Notification_model');
    }

    public function index(){
        $viewData                   = array();
        $notifications               = $this->Notification_model->getNotification(false, 'email');
        foreach ($notifications as $key => $notification){
            $catId = $notification->notify_cat_id;
            $notificationCat               = $this->Notification_model->getNotificationCats($catId);
            if($notificationCat){
                $notifications[$key]->catName = $notificationCat[0]->cat_name;
            }

            $notifyUserTypeId       = $notification->user_type;
            $userType               = $this->Notification_model->GetCategoryUserTypeById($notifyUserTypeId);
            if($userType){
                $notifications[$key]->userType = $userType[0]->user_type;
            }
        }

        $sms                        = $this->Notification_model->getNotification(false, 'sms');
        foreach ($sms as $key => $smsIn){
            $catId                  = $smsIn->notify_cat_id;
            $smsCat                 = $this->Notification_model->getNotificationCats($catId);
            if($smsCat){
                $sms[$key]->catName = $smsCat[0]->cat_name;
            }

            $notifyUserTypeId       = $smsIn->user_type;
            $userType               = $this->Notification_model->GetCategoryUserTypeById($notifyUserTypeId);
            if($userType){
                $sms[$key]->userType = $userType[0]->user_type;
            }
        }

        $viewData['notifications']      = $notifications;
        $viewData['sms']                = $sms;
        $this->load->view('notification/index',$viewData);

    }

    public function GetCategoryUserType(){
        $NotifyCatId                        = $_POST['notifyCatId'];
        $notifyUserType                     = $this->Notification_model->GetCategoryUserType($NotifyCatId);
        $html                               = '';
        foreach ($notifyUserType as $notifyUserTypeIn) {
            $html .= '<option value="'.$notifyUserTypeIn->notification_users_type_id.'">'.$notifyUserTypeIn->user_type.'</option>';
        }
        $response               = array();
        $response['error']      = 0;
        $response['html']       = $html;
        echo json_encode($response); exit;
    }
    public function form($id=false){

        $viewData                           = array();
        $notificationCats                   = $this->Notification_model->getNotificationCats(false);
        $viewData['notificationCats']       = $notificationCats;
        $firstNotifyCat                     = $notificationCats[0]->id;
        $notifyUserType                     = $this->Notification_model->GetCategoryUserType($firstNotifyCat);
        $viewData['notifyUserType']         = $notifyUserType;

        if ($id != '') {
            $viewData['id'] = $id;
            $notification = $this->Notification_model->getNotification($id, 'email');
            if (!empty($notification)) {
                $viewData['notification'] = $notification[0];
                $selectedNotifyCat                     = $notification[0]->notify_cat_id;
                $notifyUserType                     = $this->Notification_model->GetCategoryUserType($selectedNotifyCat);
                if(!empty($notifyUserType)){
                    $viewData['notifyUserType']         = $notifyUserType;
                }
            }
        }

        $this->form_validation->set_rules('submit', 'Submit', 'required');
        $this->form_validation->set_rules('notifyCatId', 'Category', 'callback_isNotificationExist');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('notification/form',$viewData);
        }else{

            $name                           = $this->input->post('name');
            $template                       = $this->input->post('template');
            $notifyCatId                    = $this->input->post('notifyCatId');
            $notifyUserTypeId               = $this->input->post('notifyUserType');
            $status                         = $this->input->post('status');

            $insert                                 = array();
            $insert['name']                         = $name;
            $insert['template']                     = $template;
            $insert['notify_cat_id']                = $notifyCatId;
            $insert['user_type']                    = $notifyUserTypeId;
            $insert['status']                       = $status;
            $insert['type']                         = 'email';
            if($id != ''){
                $insert['id']                       = $id;
                $this->Notification_model->update($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Update Successfully</div>');
            }else{
                $this->Notification_model->insert($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Insert Successfully</div>');
            }

            redirect(SITE.'notification');
        }
    }

    public function sms_form($id=false)
    {
        $viewData = array();
        $notificationCats = $this->Notification_model->getNotificationCats(false, 'sms');
        $viewData['notificationCats'] = $notificationCats;

        $firstNotifyCat                     = $notificationCats[0]->id;
        $notifyUserType                     = $this->Notification_model->GetCategoryUserType($firstNotifyCat);
        $viewData['notifyUserType']         = $notifyUserType;

        if ($id != '') {
            $viewData['id'] = $id;
            $notification = $this->Notification_model->getNotification($id, 'sms');
            if (!empty($notification)) {
                $viewData['notification'] = $notification[0];
                $selectedNotifyCat                     = $notification[0]->notify_cat_id;
                $notifyUserType                     = $this->Notification_model->GetCategoryUserType($selectedNotifyCat);
                if(!empty($notifyUserType)){
                    $viewData['notifyUserType']         = $notifyUserType;
                }
            }
        }
        $this->form_validation->set_rules('submit', 'Submit', 'required');
        $this->form_validation->set_rules('notifyCatId', 'Category', 'callback_isNotificationExist');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('notification/smsForm',$viewData);
        }else{

            $name                           = $this->input->post('name');
            $template                       = $this->input->post('template');
            $notifyCatId                    = $this->input->post('notifyCatId');
            $notifyUserTypeId               = $this->input->post('notifyUserType');
            $status                         = $this->input->post('status');

            $insert                                 = array();
            $insert['name']                         = $name;
            $insert['template']                     = $template;
            $insert['notify_cat_id']                = $notifyCatId;
            $insert['user_type']                    = $notifyUserTypeId;
            $insert['status']                       = $status;
            $insert['type']                         = 'sms';
            if($id != ''){
                $insert['id']                       = $id;
                $this->Notification_model->update($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Update Successfully</div>');
            }else{
                $this->Notification_model->insert($insert);
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Insert Successfully</div>');
            }

            redirect(SITE.'notification');
        }
    }

    public function isNotificationExist($catId){
        $notifyId       = $this->input->post('id');
        $type           = $this->input->post('type');
        $notifyUserType = $this->input->post('notifyUserType');
        $isEmailExist = $this->Notification_model->isNotificationExist($catId, $notifyId, $type, $notifyUserType);
        if ($isEmailExist) {
            $this->form_validation->set_message('isNotificationExist', 'This category type of notification already exist if you want to create new one please inactive previous');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    public function delete($id){
        $this->Notification_model->delete($id);
        $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Deleted Successfully</div>');
        redirect(SITE.'notification');
    }


    public function notification_control_list(){
        $viewData                               = array();
        $notificationCats                       = $this->Notification_model->getNotificationCats();
        $viewData['notificationCats']           = $notificationCats;
        $this->load->view('notification/notificationControlList',$viewData);
    }

    public function notification_control($NotifyCatId){
        $viewData                               = array();
        $viewData['NotifyCatId']                = $NotifyCatId;
        $notificationCats                       = $this->Notification_model->getNotificationCats($NotifyCatId);
        $viewData['notificationCats']           = $notificationCats;
        $notifyCatUserType                      = $this->Notification_model->GetCategoryUserType($NotifyCatId);
        $viewData['notifyCatUserType']          = $notifyCatUserType;

        $notifyControlRecord                      = $this->Notification_model->getNotificationControlRecords($NotifyCatId);

        if(!empty($notifyControlRecord)){

            $viewData['notifyControlRecord']            = $notifyControlRecord[0];
            $notifyControlId                            = $notifyControlRecord[0]->notification_control_id;

            $notifyControlUserTypeRecord = $this->Notification_model->getNotificationControlUserType($notifyControlId);

            $notifyControlSelectedUserType =  array();
            foreach ($notifyControlUserTypeRecord as $notifyControlUserTypeRecordIn) {
                $notifyControlSelectedUserType[] = $notifyControlUserTypeRecordIn->notification_users_type_id;
                
            }
            $viewData['notifyControlSelectedUserType']            = $notifyControlSelectedUserType;
        }
        $this->form_validation->set_rules('submit', 'Submit', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('notification/notificationControl',$viewData);
        }else{

            $notificationType               = $this->input->post('notificationType');
            $userType                       = $this->input->post('userType');
            $status                         = $this->input->post('status');

            $insert                             = array();
            $insert['notification_type']        = $notificationType;
            $insert['notification_category_id'] = $NotifyCatId;
            $insert['status']                   = $status;
            if(!empty($notifyControlRecord)){

                $insert['notification_control_id']                       = $notifyControlId;
                $this->Notification_model->updateControl($insert);


                $this->Notification_model->deleteControlUserTypeByCatId($notifyControlId);

                $insert                                         = array();
                $insert['notification_control_id']              = $notifyControlId;
                foreach ( $userType as $userTypeIn) {
                    $insert['notification_users_type_id']    = $userTypeIn;
                    $this->Notification_model->insertControlUserType($insert);
                }
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Update Successfully</div>');
            }else{
                $NotifyControlId = $this->Notification_model->insertControl($insert);

                $insert                                         = array();
                $insert['notification_control_id']              = $NotifyControlId;
                foreach ( $userType as $userTypeIn) {
                    $insert['notification_users_type_id']    = $userTypeIn;
                    $this->Notification_model->insertControlUserType($insert);
                }
                $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Insert Successfully</div>');

            }

            redirect(SITE.'notification/notification_control_list');
        }
    }
}
