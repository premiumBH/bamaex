<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/2/2017
 * Time: 2:42 PM
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/system/CLX_sms_integration/vendor/autoload.php';
class Notification_lib{

    private $Obj;

    public $newClientEmailCode                              = 'NClientN';
    public $assignOrderToCourierNotification                = 'AOTCN';
    public $resetPasswordEmailCode                          = 'RPN';


    public $typeEmail                       = 'email';
    public $typeSms                         = 'sms';

    public $userType                        = array('Admin', 'Client', 'Receiver', 'Manager',
                                                    'Supervisor', 'Agent' , 'Courier', 'Order Creator',
                                                    'Client Creator', 'Sender');

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

    public function notificationTypeEmail($data){
        $code               = $data['code'];
        $userType           = $data['userType'];
        $shortCodeArray     = $data['shortCodeArray'];
        $emailTo            = $data['emailTo'];

        $catAllTemp                 = $this->getEmailTemplate($code, $this->typeEmail);

        if($catAllTemp) {
            $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

            if ($templateEmailData) {
                $template = $templateEmailData[0]->template;
                $subject = $templateEmailData[0]->name;
                $template = $this->shortCodeReplace($template, $shortCodeArray);
                //
                $from           = $this->emailFrom;
                $subject        = $subject;
                $message        = $template;
                foreach ($emailTo as $emailToIn){
                    $this->send_email($from, $emailToIn, $subject, $message);
                }
            }
        }

    }

    public function notificationTypeSms($data)
    {
        $code               = $data['code'];
        $userType           = $data['userType'];
        $shortCodeArray     = $data['shortCodeArray'];
        $smsTo              = $data['smsTo'];
        //sms Section Start

        $catAllTemp         = $this->getEmailTemplate($code, $this->typeSms);

        if($catAllTemp){
            $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
            if($templateSmsData){
                $template       = $templateSmsData[0]->template;
                $template       = $this->shortCodeReplace($template, $shortCodeArray);
                //
                $this->sendSms($smsTo, $template);
            }
        }
        //sms Section end
    }

    public function notificationTypeSmsAndEmail($data)
    {
        $this->notificationTypeEmail($data);
        $this->notificationTypeSms($data);
    }


    public function orderStatusUpdateNotification($data, $code){

        $NotificationControl        = $this->getNotificationControl($code);
        if(!empty($NotificationControl)) {
            foreach ($NotificationControl as $NotificationControlIn) {
                if (in_array($NotificationControlIn->user_type, $this->userType)) {

                    $userType = $NotificationControlIn->user_type;
                    $shortCodeArray = $data[$userType]['shortCode'];
                    $emailTo = $data[$userType]['email'];
                    $smsTo = $data[$userType]['number'];

                    $FPData = array();
                    $FPData['code'] = $code;
                    $FPData['userType'] = $userType;
                    $FPData['shortCodeArray'] = $shortCodeArray;
                    $FPData['emailTo'] = $emailTo;
                    $FPData['smsTo'] = $smsTo;

                    if ($NotificationControlIn->notification_type == 'email') {
                        $this->notificationTypeEmail($FPData);
                    } else if ($NotificationControlIn->notification_type == 'sms') {
                        $this->notificationTypeSms($FPData);
                    } else if ($NotificationControlIn->notification_type == 'email&sms') {
                        $this->notificationTypeSmsAndEmail($FPData);
                    }
                }
            }
        }
        return true;
    }



    public function newClientEmailNotification($data){
        $code                       = $this->newClientEmailCode;
        $typeEmail                  = $this->typeEmail;
        $typeSms                    = $this->typeSms;

        $NotificationControl        = $this->getNotificationControl($code);

        foreach ($NotificationControl as $NotificationControlIn){

            if($NotificationControlIn->user_type == 'Client'){

                $userType                           = 'Client';
                $shortCodeArray                     = $data[$userType]['shortCode'];
                $emailTo                            = $data[$userType]['email'];
                $smsTo                              = $data[$userType]['number'];

                $defaultEmailTemp               = '<h3>Login Detail</h3><br/> <p>Email: '.$shortCodeArray['client_email'].'</p> <br/><p> Password: '.$shortCodeArray['client_password'].'</p>';


                $defaultSmsTemp                 = 'New Client Email
                        Email: '.$shortCodeArray['client_email'].' 
                        Password: '.$shortCodeArray['client_password'].'';

                $defaultEmailTempSub            = 'New Client Email';

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end
                }

            }

            else if($NotificationControlIn->user_type == 'Admin'){

                $userType               = 'Admin';
                $shortCodeArray                     = $data[$userType]['shortCode'];
                $emailTo                            = $data[$userType]['email'];
                $smsTo                              = $data[$userType]['number'];

                $defaultEmailTemp               = '<h3>Login Detail</h3><br/> <p>Email: '.$shortCodeArray['client_email'].'</p> <br/><p> Password: '.$shortCodeArray['client_password'].'</p>';

                $defaultSmsTemp                 = 'New Client Email
                        Email: '.$shortCodeArray['client_email'].' 
                        Password: '.$shortCodeArray['client_password'].'';

                $defaultEmailTempSub            = 'New Client Email';

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template           = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end
                }

            }
        }
        return true;
    }


    public function resetPasswordNotification($data){
        $code                       = $this->resetPasswordEmailCode;
        $typeEmail                  = $this->typeEmail;
        $typeSms                    = $this->typeSms;

        $NotificationControl        = $this->getNotificationControl($code);

        foreach ($NotificationControl as $NotificationControlIn){

            if($NotificationControlIn->user_type == 'Client'){

                $userType                           = 'Client';
                $shortCodeArray                     = $data[$userType]['shortCode'];
                $emailTo                            = $data[$userType]['email'];
                $smsTo                              = $data[$userType]['number'];

                $defaultEmailTemp               = '<b>Your new password is '.$shortCodeArray['client_password'].'</b>';

                $defaultSmsTemp                 = 'Reset Password
                        Password: '.$shortCodeArray['client_password'].'';

                $defaultEmailTempSub            = 'Reset Password';

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end
                }

            }

            else if($NotificationControlIn->user_type == 'Admin'){

                $userType               = 'Admin';
                $shortCodeArray                     = $data[$userType]['shortCode'];
                $emailTo                            = $data[$userType]['email'];
                $smsTo                              = $data[$userType]['number'];

                $defaultEmailTemp               = '<b>Your new password is '.$shortCodeArray['client_password'].'</b>';

                $defaultSmsTemp                 = 'Reset Password
                        Password: '.$shortCodeArray['client_password'].'';

                $defaultEmailTempSub            = 'Reset Password';

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end
                }

            }
        }
        return true;

    }


    public function OrderAssignmentToCourierNotification($data){
        $code                       = $this->assignOrderToCourierNotification;
        $typeEmail                  = $this->typeEmail;
        $typeSms                    = $this->typeSms;


        $NotificationControl        = $this->getNotificationControl($code);

        foreach ($NotificationControl as $NotificationControlIn){

            if($NotificationControlIn->user_type == 'Courier'){

                $userType                           = 'Courier';
                $shortCodeArray                     = $data[$userType]['shortCode'];
                $emailTo                            = $data[$userType]['email'];
                $smsTo                              = $data[$userType]['number'];

                $defaultEmailTemp               = '<b>New Order Assigned. Order ID is  '.$shortCodeArray['order_id'].'</b>';

                $defaultSmsTemp                 = 'New Order Assigned. Order ID is  '.$shortCodeArray['order_id'].'';

                $defaultEmailTempSub            = 'New Order Assigned';

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end
                }

            }

            else if($NotificationControlIn->user_type == 'Receiver'){

                $userType                           = 'Receiver';
                $shortCodeArray                     = $data[$userType]['shortCode'];
                $emailTo                            = $data[$userType]['email'];
                $smsTo                              = $data[$userType]['number'];

                $defaultEmailTemp               = '<b>Your Order ID '.$shortCodeArray['order_id'].' is Assigned to Courier.</b>';

                $defaultSmsTemp                 = 'Your Order ID '.$shortCodeArray['order_id'].' is Assigned to Courier.';

                $defaultEmailTempSub            = 'Order Assigned to Courier';

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end
                }

            }

            else if($NotificationControlIn->user_type == 'Admin'){

                $userType               = 'Admin';
                $shortCodeArray                     = $data[$userType]['shortCode'];
                $emailTo                            = $data[$userType]['email'];
                $smsTo                              = $data[$userType]['number'];

                $defaultEmailTemp               = '<b>New Order Assigned Courier. Order ID is  '.$shortCodeArray['order_id'].' And Courier Email is '.$shortCodeArray['courier_email'].'</b>';

                $defaultSmsTemp                 = 'New Order Assigned Courier. Order ID is  '.$shortCodeArray['order_id'].' And Courier Email is '.$shortCodeArray['courier_email'].'';

                $defaultEmailTempSub            = 'New Order Assigned';

                if($NotificationControlIn->notification_type == 'email'){

                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end

                }

                else if($NotificationControlIn->notification_type == 'email&sms'){
                    // email Section Start
                    $template   = $defaultEmailTemp;
                    $subject    = $defaultEmailTempSub;

                    $catAllTemp                 = $this->getEmailTemplate($code, $typeEmail);
                    if($catAllTemp) {
                        $templateEmailData      = $this->getUserTypeTemp($catAllTemp, $userType);

                        if ($templateEmailData) {
                            $template = $templateEmailData[0]->template;
                            $subject = $templateEmailData[0]->name;
                            $template = $this->shortCodeReplace($template, $shortCodeArray);
                        }
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
                    $template       = $defaultSmsTemp;

                    $catAllTemp         = $this->getEmailTemplate($code, $typeSms);

                    if($catAllTemp){
                        $templateSmsData       = $this->getUserTypeTemp($catAllTemp,$userType);
                        if($templateSmsData){
                            $template       = $templateSmsData[0]->template;
                            $template       = $this->shortCodeReplace($template, $shortCodeArray);
                        }
                    }

                    $this->sendSms($smsTo, $template);
                    //sms Section end
                }

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

        if(isset($data['order_tracking_id'])){
            $template = str_replace('[order_tracking_id]',$data['order_tracking_id'],$template);
        }
        if(isset($data['receiver_name'])){
            $template = str_replace('[receiver_name]',$data['receiver_name'],$template);
        }
        if(isset($data['receiver_email'])){
            $template = str_replace('[receiver_email]',$data['receiver_email'],$template);
        }
        if(isset($data['receiver_mobile'])){
            $template = str_replace('[receiver_mobile]',$data['receiver_mobile'],$template);
        }

        if(isset($data['sender_name'])){
            $template = str_replace('[sender_name]',$data['sender_name'],$template);
        }
        if(isset($data['sender_email'])){
            $template = str_replace('[sender_email]',$data['sender_email'],$template);
        }
        if(isset($data['sender_mobile'])){
            $template = str_replace('[sender_mobile]',$data['sender_mobile'],$template);
        }


        if(isset($data['client_first_name'])){
            $template = str_replace('[client_first_name]',$data['client_first_name'],$template);

        }
        if(isset($data['client_last_name'])){
            $template = str_replace('[client_last_name]',$data['client_last_name'],$template);
        }
        if(isset($data['client_name'])){
            $template = str_replace('[client_name]',$data['client_name'],$template);

        }
        if(isset($data['client_email'])){
            $template = str_replace('[client_email]',$data['client_email'],$template);

        }
        if(isset($data['client_password'])){
            $template = str_replace('[client_password]',$data['client_password'],$template);
        }

        if(isset($data['client_creator_name'])){
            $template = str_replace('[client_creator_name]',$data['client_creator_name'],$template);
        }
        if(isset($data['client_creator_email'])){
            $template = str_replace('[client_creator_email]',$data['client_creator_email'],$template);
        }
        if(isset($data['client_creator_mobile'])){
            $template = str_replace('[client_creator_mobile]',$data['client_creator_mobile'],$template);
        }

        if(isset($data['order_creator_name'])){
            $template = str_replace('[order_creator_name]',$data['order_creator_name'],$template);
        }
        if(isset($data['order_creator_email'])){
            $template = str_replace('[order_creator_email]',$data['order_creator_email'],$template);
        }
        if(isset($data['order_creator_mobile'])){
            $template = str_replace('[order_creator_mobile]',$data['order_creator_mobile'],$template);
        }

        if(isset($data['courier_name'])){
            $template = str_replace('[courier_name]',$data['courier_name'],$template);
        }
        if(isset($data['courier_email'])){
            $template = str_replace('[courier_email]',$data['courier_email'],$template);
        }
        if(isset($data['courier_mobile'])){
            $template = str_replace('[courier_mobile]',$data['courier_mobile'],$template);
        }

        return $template;
    }

}