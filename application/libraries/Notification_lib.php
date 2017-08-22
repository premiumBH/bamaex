<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/2/2017
 * Time: 2:42 PM
 */
require_once realpath('.').'/system/CLX_sms_integration/vendor/autoload.php';
class Notification_lib{

    private $Obj;

    public $newClientEmailCode              = 'NClientN';
    public $resetPasswordEmailCode          = 'RPN';


    public $typeEmail                       = 'email';
    public $typeSms                         = 'sms';



    public $notificationCategoryTable       = 'notification_category';
    public $notificationTable               = 'notification';
    public $notificationControlTable        = 'notification_control';
    public $notificationControlUserType     = 'notification_control_user_type';
    public $notificationUsersType           = 'notification_users_type';

    public $emailFrom                       = 'admin@bamaex.net';


    public $servicePlanId                   = '';
    public $token                           = '';
    public $senderNum                       = '';


    public function __construct(){
        $this->Obj =& get_instance();
        $this->servicePlanId    = 'mycon22';
        $this->token            = 'e308e13792394aee9521ee9867b8350c';
        $this->senderNum        = '12345';
    }



    public function newClientEmailNotification($emailTo, $smsTo, $shortCodeArray){
        $code                       = $this->newClientEmailCode;
        $typeEmail                  = $this->typeEmail;
        $typeSms                    = $this->typeSms;

        $NotificationControl        = $this->getNotificationControl($code);

        foreach ($NotificationControl as $NotificationControlIn){

            if($NotificationControlIn->user_type == 'Client'){

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    $templateEmailData          = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateEmailData){
                        $template       = $templateEmailData[0]->template;
                        $subject        = $templateEmailData[0]->name;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template   = '<h3>Login Detail</h3><br/> <p>Email: '.$shortCodeArray['userEmail'].'</p> <br/><p> Password: '.$shortCodeArray['password'].'</p>';
                        $subject    = 'New Client Email';
                    }
                    $from           = $this->emailFrom;
                    $subject        = $subject;
                    $message        = $template;
                    foreach ($emailTo as $emailToIn){
                        $this->send_email($from, $emailToIn, $subject, $message);
                    }
                    // email Section End

                }

                else if($NotificationControlIn->notification_type == 'sms'){

                    //sms Section Start
                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);
                    $templateSmsData       = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateSmsData){
                        $template       = $templateSmsData[0]->template;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template       = 'New Client Email
                        Email: '.$shortCodeArray['userEmail'].' 
                        Password: '.$shortCodeArray['password'].'';
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    $templateEmailData          = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateEmailData){
                        $template       = $templateEmailData[0]->template;
                        $subject        = $templateEmailData[0]->name;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template   = '<h3>Login Detail</h3><br/> <p>Email: '.$shortCodeArray['userEmail'].'</p> <br/><p> Password: '.$shortCodeArray['password'].'</p>';
                        $subject    = 'New Client Email';
                    }
                    $from           = $this->emailFrom;
                    $subject        = $subject;
                    $message        = $template;
                    foreach ($emailTo as $emailToIn){
                        $this->send_email($from, $emailToIn, $subject, $message);
                    }
                    // email Section End

                    //------------------------------------

                    //sms Section Start
                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);
                    $templateSmsData       = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateSmsData){
                        $template       = $templateSmsData[0]->template;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template       = 'New Client Email
                        Email: '.$shortCodeArray['userEmail'].' 
                        Password: '.$shortCodeArray['password'].'';
                    }

                    $this->sendSms($emailTo, $template);
                    //sms Section end
                }

            }
            else if($NotificationControlIn->user_type == 'Admin'){
                //if admin is in option

            }
        }
        return true;
    }


    public function resetPasswordNotification($emailTo, $smsTo, $shortCodeArray){
        $code                       = $this->resetPasswordEmailCode;
        $typeEmail                  = $this->typeEmail;
        $typeSms                    = $this->typeSms;

        $NotificationControl        = $this->getNotificationControl($code);

        foreach ($NotificationControl as $NotificationControlIn){

            if($NotificationControlIn->user_type == 'Client'){

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    $templateEmailData          = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateEmailData){
                        $template       = $templateEmailData[0]->template;
                        $subject        = $templateEmailData[0]->name;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template   = '<b>Your new password is '.$shortCodeArray['password'].'</b>';
                        $subject    = 'Reset Password';
                    }
                    $from           = $this->emailFrom;
                    $subject        = $subject;
                    $message        = $template;
                    foreach ($emailTo as $emailToIn){
                        $this->send_email($from, $emailToIn, $subject, $message);
                    }
                    // email Section End

                }

                else if($NotificationControlIn->notification_type == 'sms'){

                    //sms Section Start
                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);
                    $templateSmsData       = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateSmsData){
                        $template       = $templateSmsData[0]->template;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template       = 'Reset Password
                        Password: '.$shortCodeArray['password'].'';
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    $templateEmailData          = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateEmailData){
                        $template       = $templateEmailData[0]->template;
                        $subject        = $templateEmailData[0]->name;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template   = '<b>Your new password is '.$shortCodeArray['password'].'</b>';
                        $subject    = 'Reset Password';
                    }
                    $from           = $this->emailFrom;
                    $subject        = $subject;
                    $message        = $template;
                    foreach ($emailTo as $emailToIn){
                        $this->send_email($from, $emailToIn, $subject, $message);
                    }
                    // email Section End

                    //------------------------------------

                    //sms Section Start
                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);
                    $templateSmsData       = $this->getUserTypeTemp($catAllTemp,'Client');
                    if($templateSmsData){
                        $template       = $templateSmsData[0]->template;
                        $template       = $this->shortCodeReplace($template, $shortCodeArray);
                    }else{
                        $template       = 'Reset Password
                        Password: '.$shortCodeArray['password'].'';
                    }

                    $this->sendSms($emailTo, $template);
                    //sms Section end
                }

            }
            else if($NotificationControlIn->user_type == 'Admin'){
                //if admin is in option

            }
        }
        return true;

    }

    public function sendSms($RecipientsArray, $msgBody){
        $client = new Clx\Xms\Client($this->servicePlanId, $this->token);
        try {
            $batchParams    = new Clx\Xms\Api\MtBatchTextSmsCreate();
            $batchParams->setSender($this->senderNum);
            //$batchParams->setRecipients(['+923224209199', '+923224783530', '+923074203020']);
            //$batchParams->setBody('Asalam-u-alikum, qasim');
            $batchParams->setRecipients($RecipientsArray);
            $batchParams->setBody($msgBody);

            $batch          = $client->createTextBatch($batchParams);
            return $batch;
            echo('The batch was given ID ' . $batch->getBatchId() . "\n");
        } catch (Exception $ex) {
            echo('Error creating batch: ' . $ex->getMessage() . "\n"); exit;
        }
        return true;
    }

    public function send_email($from, $to, $subject, $message){
        $this->Obj->email->set_mailtype("html");
        $this->Obj->email->from($from);
        $this->Obj->email->to($to);
        $this->Obj->email->subject($subject);
        $this->Obj->email->message($message);
        $this->Obj->email->send();
        return true;
    }

    public function getEmailTemplate($code, $type){
        $this->Obj->db->select('NT.*, NCT.* , NUTT.*');
        $this->Obj->db->from($this->notificationTable.' as NT');
        $this->Obj->db->join($this->notificationCategoryTable.' as NCT', 'NT.notify_cat_id = NCT.id');
        $this->Obj->db->join($this->notificationUsersType.' as NUTT', 'NT.user_type = NUTT.notification_users_type_id');
        $this->Obj->db->where('NCT.code', $code);
        $this->Obj->db->where('NT.type', $type);
        $this->Obj->db->where('NT.status', '1');
        $response       = $this->Obj->db->get();
        if($response->num_rows() > 0){
            $returnData             = $response->result();
        }else{
            $returnData             = false;
        }
        return $returnData;
    }

    public function getNotificationControl($code){

        $this->Obj->db->select('*');
        $this->Obj->db->from($this->notificationCategoryTable);
        $this->Obj->db->where('code', $code);
        $response       = $this->Obj->db->get();

        if($response->num_rows() > 0){
            $notifyCatData      = $response->result();
            $notifyCatId        = $notifyCatData[0]->id;
            $this->Obj->db->select('*');
            $this->Obj->db->from($this->notificationControlTable.' as NConT');
            $this->Obj->db->join($this->notificationControlUserType.' as NConUTT', 'NConUTT.notification_control_id = NConT.notification_control_id');
            $this->Obj->db->join($this->notificationUsersType.' as NUTT', 'NUTT.notification_users_type_id = NConUTT.notification_users_type_id');
            $this->Obj->db->where('NConT.notification_category_id', $notifyCatId);
            $response           = $this->Obj->db->get();

            if($response->num_rows() > 0){
                $returnData             = $response->result();
            }else{
                $returnData             = false;
            }
            return $returnData;
        }
        return false;
    }

    public function getUserTypeTemp($tempArray, $userType){
        $returnArray = array();
        foreach ($tempArray as $tempArrayIn){
            if($tempArrayIn->user_type == $userType){
                $returnArray[] = $tempArrayIn;
                break;
            }
        }
        return $returnArray;
    }

    public function shortCodeReplace($template, $data){

        if(isset($data['firstName'])){
            $template = str_replace('[user_first_name]',$data['firstName'],$template);

        }
        if(isset($data['lastName'])){
            $template = str_replace('[user_last_name]',$data['lastName'],$template);
        }
        if(isset($data['userEmail'])){
            $template = str_replace('[user_email]',$data['userEmail'],$template);

        }
        if(isset($data['password'])){
            $template = str_replace('[user_password]',$data['password'],$template);
        }

        return $template;

    }

}