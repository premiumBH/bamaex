<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/2/2017
 * Time: 2:42 PM
 */
class Custom_email{

    private $Obj;

    public $newClientEmailCode              = 'NCENE';
    public $resetPasswordEmailCode          = 'RPNE';
    public $newConsignmentEmailCode         = 'NCNE';
    public $orderPickupToCourierEmailCode   = 'OPNTCE';
    public $deliveryEmailCode               = 'DNE';

    public $typeEmail                       = 'email';

    public $notificationCategoryTable       = 'notification_category';
    public $notificationTable               = 'notification';

    public $emailFrom                       = 'admin@bamaex.net';

    public function __construct(){
        $this->Obj =& get_instance();
    }

    public function send_email($from, $to, $subject, $message){
        $this->Obj->email->from($from);
        $this->Obj->email->to($to);
        $this->Obj->email->subject($subject);
        $this->Obj->email->message($message);
        $this->Obj->email->send();
        return true;
    }

    public function getEmailTemplate($code, $type){
        $this->Obj->db->select('*');
        $this->Obj->db->from($this->notificationTable.' as NT');
        $this->Obj->db->join($this->notificationCategoryTable.' as NCT', 'NT.notify_cat_id = NCT.	id');
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

    public function newClientEmailNotification($emailTo, $shortCodeArray){
        $code       = $this->newClientEmailCode;
        $type       = $this->typeEmail;
        $template   = $this->getEmailTemplate($code, $type);

        if($template){
            $template       = $template[0]->template;
            $subject        = $template[0]->name;
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
    }

    public function resetPasswordNotification($emailTo, $shortCodeArray){
        $code       = $this->resetPasswordEmailCode;
        $type       = $this->typeEmail;
        $template   = $this->getEmailTemplate($code, $type);

        if($template){
            $template       = $template[0]->template;
            $subject        = $template[0]->name;
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

        return true;

    }

    public function newConsignmentNotification($emailTo, $shortCodeArray){
        $code       = $this->newConsignmentEmailCode;
        $type       = $this->typeEmail;
        $template   = $this->getEmailTemplate($code, $type);

        if($template){
            $template       = $template[0]->template;
            $subject        = $template[0]->name;
            $template       = $this->shortCodeReplace($template, $shortCodeArray);
        }else{
            $template   = '';
            $subject    = 'New Consignment';
        }

        $from           = $this->emailFrom;
        $subject        = $subject;
        $message        = $template;

        foreach ($emailTo as $emailToIn){
            $this->send_email($from, $emailToIn, $subject, $message);
        }

    }

    public function orderPickupNotificationToCourier($emailTo, $shortCodeArray){
        $code       = $this->orderPickupToCourierEmailCode;
        $type       = $this->typeEmail;
        $template   = $this->getEmailTemplate($code, $type);

        if($template){
            $template       = $template[0]->template;
            $subject        = $template[0]->name;
            $template       = $this->shortCodeReplace($template, $shortCodeArray);
        }else{
            $template   = '';
            $subject    = 'Order Pickup Notification';
        }

        $from           = $this->emailFrom;
        $subject        = $subject;
        $message        = $template;

        foreach ($emailTo as $emailToIn){
            $this->send_email($from, $emailToIn, $subject, $message);
        }

    }

    public function deliveryNotification($emailTo, $shortCodeArray){
        $code       = $this->deliveryEmailCode;
        $type       = $this->typeEmail;
        $template   = $this->getEmailTemplate($code, $type);

        if($template){
            $template       = $template[0]->template;
            $subject        = $template[0]->name;
            $template       = $this->shortCodeReplace($template, $shortCodeArray);
        }else{
            $template   = '';
            $subject    = 'Delivery Notification';
        }

        $from           = $this->emailFrom;
        $subject        = $subject;
        $message        = $template;

        foreach ($emailTo as $emailToIn){
            $this->send_email($from, $emailToIn, $subject, $message);
        }
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