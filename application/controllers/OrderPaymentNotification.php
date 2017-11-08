<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 10/24/2017
 * Time: 1:57 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderPaymentNotification extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('User_model');
        $this->load->model('Zone_model');
        $this->load->model('Client_model');
        $this->load->model('Country_model');
        $this->load->model('Client_rates_model');
        $this->load->model('Client_type');
        $this->load->library('encrypt');
        $this->load->library('Notification_lib');
        $this->load->helper('cookie');
    }

    public function index(){


        $query                  = "select * from order_details , 
                                order_payments
                                where order_payments.order_id = order_details.order_id";
        $queryResponse          = $this->db->query($query);
        $queryResponse          = $queryResponse->result_array();

        foreach($queryResponse as $queryResponseIn){

            $orderId                = $queryResponseIn['order_id'];
            $clientId               = $queryResponseIn['client_id'];

            $query                  = " select * from client_table 
                                    where client_id = $clientId";
            $clientData             = $this->db->query($query);
            $clientData             = $clientData->result_array();

            $creditDays             = $clientData[0]['credit_days'];
            $paymentDueDate         = date('Y-m-d', strtotime('+'.$creditDays.' days '. $queryResponseIn['created_on'])) ;

            $date1                  = date_create(date('Y-m-d'));
            $date2                  = date_create($paymentDueDate);
            $diff                   = date_diff($date1,$date2);

            $day_sign               = $diff->format("%R");
            $days                   = $diff->format("%a");
            $daysBalance            = $diff->format("%R%a");

            $paidDate               = '';
            $status                 = '';

            if($queryResponseIn['paid_date'] != '') {
                $paidDate           = explode(' ', $queryResponseIn['paid_date'])[0];

            } else {
                $paidDate           = $queryResponseIn['paid_date'];
            }

            if ($queryResponseIn['paid_date'] == '') {
                if($day_sign == '+')
                    $status = 'Due';
                else
                    $status = 'Over Due';

                $paidDate = '';
            }
            else {
                $status = 'Paid';
            }

            if($status != '' && $status != 'Paid'){
                if($status == 'Over Due'){
                    if($this->isNotificationSend($orderId, 'Over Due')){
                        $this->SendNotification($orderId,'');
                        $this->insertPaymentNotification($orderId, 'Over Due');
                    }
                }
                else if($day_sign == "+" && $daysBalance == 7) {
                    if($this->isNotificationSend($orderId, '7')){
                        $this->SendNotification($orderId,'');
                        $this->insertPaymentNotification($orderId, '7');

                    }
                }
                else if($day_sign == "+" && $daysBalance == 30){
                    if($this->isNotificationSend($orderId, '30')){
                        $this->SendNotification($orderId,'');
                        $this->insertPaymentNotification($orderId, '30');
                    }
                }

            }
            else if($status == 'Paid'){
                $query                  = " DELETE FROM order_payment_notification WHERE order_id = $orderId";
                $this->db->query($query);
            }
        }
        echo "<pre>"; print_r('payment notification'); exit;
    }

    public function SendNotification($orderId, $code){

        $orderUserData                                  = $this->User_model->getOrderNotificationUsers($orderId);

        $shortCodeArray                                 = array();

        $shortCodeArray['order_tracking_id']            = $orderUserData[0]->orderTrackingId;

        $shortCodeArray['receiver_name']                = $orderUserData[0]->receiverName;
        $shortCodeArray['receiver_email']               = $orderUserData[0]->receiverEmail;
        $shortCodeArray['receiver_mobile']              = $orderUserData[0]->receiverMobile;

        $shortCodeArray['sender_name']                  = $orderUserData[0]->senderName;
        $shortCodeArray['sender_email']                 = $orderUserData[0]->senderEmail;
        $shortCodeArray['sender_mobile']                = $orderUserData[0]->senderMobile;

        $clientDetail                                   = $this->Client_model->getPrimaryUser($orderUserData[0]->clientId);
        $shortCodeArray['client_first_name']            = $clientDetail[0]->first_name;
        $shortCodeArray['client_last_name']             = $clientDetail[0]->last_name;
        $shortCodeArray['client_name']                  = $clientDetail[0]->first_name.' '.$clientDetail[0]->last_name;;
        $shortCodeArray['client_email']                 = $orderUserData[0]->clientEmail;
        $shortCodeArray['client_mobile']                = $orderUserData[0]->clientMobile;

        $shortCodeArray['client_creator_name']                = $orderUserData[0]->clientCreatorName;
        $shortCodeArray['client_creator_email']               = $orderUserData[0]->clientCreatorEmail;
        $shortCodeArray['client_creator_mobile']              = $orderUserData[0]->clientCreatorMobile;

        $shortCodeArray['order_creator_name']                = $orderUserData[0]->orderCreatorName;
        $shortCodeArray['order_creator_email']               = $orderUserData[0]->orderCreatorEmail;
        $shortCodeArray['order_creator_mobile']              = $orderUserData[0]->orderCreatorMobile;


        $notificationArray                                              = array();


        $userType                                                       = 'Receiver';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->receiverEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->receiverMobile);
        $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

        $userType                                                       = 'Sender';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->senderEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->senderMobile);
        $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

        $userType                                                       = 'Client';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->clientEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->clientMobile);
        $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

        $userType                                                       = 'Client Creator';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->clientCreatorEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->clientCreatorMobile);
        $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

        $userType                                                       = 'Order Creator';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->orderCreatorEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->orderCreatorMobile);
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

        $this->notification_lib->orderStatusUpdateNotification($notificationArray, $code);
    }

    function insertPaymentNotification($orderId, $type){
        $query                  = "INSERT INTO order_payment_notification (order_id, not_type) VALUES ($orderId, $type);";
        $this->db->query($query);
    }

    function isNotificationSend($orderId, $type){
        $query                  = " SELECT * FROM order_payment_notification 
                                WHERE order_id = $orderId AND not_type = $type";
        $response               = $this->db->query($query);
        $response               = $response->result_array();
        if(empty($response)){
            return true;
        }else{
            return false;
        }
    }
}
?>