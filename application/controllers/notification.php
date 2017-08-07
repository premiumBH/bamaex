<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/7/2017
 * Time: 6:48 PM
 */
class notification extends CI_Controller{
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
        $notifications               = $this->Notification_model->getNotification();
        foreach ($notifications as $key => $notification){
            $catId = $notification->notify_cat_id;
            $notificationCat               = $this->Notification_model->getNotificationCats($catId);
            $notifications[$key]->catName = $notificationCat[0]->cat_name;
        }
        $viewData['notifications']   = $notifications;
        $this->load->view('notification/index',$viewData);

    }
    public function form($id=false)
    {
        $viewData = array();
        $notificationCats = $this->Notification_model->getNotificationCats();
        $viewData['notificationCats'] = $notificationCats;
        if ($id != '') {
            $viewData['id'] = $id;
            $notification = $this->Notification_model->getNotification($id);
            if (!empty($notification)) {
                $viewData['notification'] = $notification[0];
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
            $status                         = $this->input->post('status');

            $insert                                 = array();
            $insert['name']                         = $name;
            $insert['template']                     = $template;
            $insert['notify_cat_id']                = $notifyCatId;
            $insert['status']                       = $status;
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
        $notifyId = $this->input->post('id');
        $isEmailExist = $this->Notification_model->isNotificationExist($catId, $notifyId);
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

}
