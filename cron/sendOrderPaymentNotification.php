<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 10/18/2017
 * Time: 6:28 PM
 */
include_once('dynamic_mydb.php');

define('DBName', 'bahraing_bamaexdc');
function orderPaymentNotification(){
    $database_name          = DBName;
    $orderDetail			= new DBN();
    $orderDetail->set_database($database_name);
    $query                  = "  select * from order_details , 
                                order_payments
                                where order_payments.order_id = order_details.order_id";
    $queryResponse          = $orderDetail->rs_array($query);

    foreach($queryResponse as $queryResponseIn){

        $orderId                = $queryResponseIn['order_id'];
        $clientId               = $queryResponseIn['client_id'];
        $clientDetail			= new DBN();
        $clientDetail->set_database($database_name);
        $query                  = " select * from client_table 
                                    where client_id = $clientId";
        $clientData             = $clientDetail->rs_array($query);

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

        SendNotification($orderId,'');
        echo "<pre>"; print_r($queryResponseIn); exit;


        if($status != '' && $status != 'Paid'){
            if($status == 'Over Due'){
                if(isNotificationSend($orderId, 'Over Due')){

                    insertPaymentNotification($orderId, 'Over Due');
                }
            }
            else if($day_sign == "+" && $daysBalance == 7) {
                if(isNotificationSend($orderId, '7')){

                    insertPaymentNotification($orderId, '7');

                }
            }
            else if($day_sign == "+" && $daysBalance == 30){
                if(isNotificationSend($orderId, '30')){

                    insertPaymentNotification($orderId, '30');
                }
            }

        }
        else if($status == 'Paid'){
            $orderPayNotDel			= new DBN();
            $orderPayNotDel->set_database($database_name);
            $query                  = " DELETE FROM order_payment_notification WHERE order_id = $orderId";
            $orderPayNotDel->rs_array($query);
        }
    }


    echo "<pre>ss"; print_r($queryResponse); exit;
}

function insertPaymentNotification($orderId, $type){
    $orderPayNot			= new DBN();
    $orderPayNot->set_database(DBName);
    $query                  = "INSERT INTO order_payment_notification (order_id, not_type) VALUES ($orderId, $type);";
    $orderPayNot->rs_array($query);
}

function isNotificationSend($orderId, $type){
    $getOrderPayNot			= new DBN();
    $getOrderPayNot->set_database(DBName);
    $query                  = " SELECT * FROM order_payment_notification 
                                WHERE order_id = $orderId AND not_type = $type";
    $response               = $getOrderPayNot->rs_array($query);
    if(empty($response)){
        return true;
    }else{
        return false;
    }

}


function getOrderNotificationUsers($orderId){
    $orderNotificationUsers			= new DBN();
    $orderNotificationUsers->set_database(DBName);

    $query                  = '
SELECT `order_details`.`order_tracking_id` as `orderTrackingId`, `order_receiver`.`name` as `receiverName`, `order_receiver`.`email` as `receiverEmail`, `order_receiver`.`mobile` as `receiverMobile`, `order_sender`.`name` as `senderName`, `order_sender`.`email` as `senderEmail`, `order_sender`.`mobile` as `senderMobile`, `client_table`.`client_id` as `clientId`, `client_table`.`company_name` as `clientName`, `client_table`.`email` as `clientEmail`, `client_table`.`phone_no` as `clientMobile`, CONCAT (user.varFirstName, " ", user.	varLastName) as clientCreatorName, `user`.`varEmailId` as `clientCreatorEmail`, `user`.`varMobileNo` as `clientCreatorMobile`, CONCAT (orderCreator.varFirstName, " ", orderCreator.	varLastName) as orderCreatorName, `orderCreator`.`varEmailId` as `orderCreatorEmail`, `orderCreator`.`varMobileNo` as `orderCreatorMobile`
FROM `order_details`
INNER JOIN `order_receiver` ON `order_receiver`.`id` = `order_details`.`receiver_id`
INNER JOIN `order_sender` ON `order_sender`.`id` = `order_details`.`sender_id`
INNER JOIN `client_table` ON `client_table`.`client_id` = `order_details`.`client_id`
INNER JOIN `user` ON `client_table`.`creater_id` = `user`.`intUserId`
INNER JOIN `user` as `orderCreator` ON `orderCreator`.`intUserId` = `order_details`.`user_id`
WHERE `order_details`.`order_id` = '.$orderId;

    $response               = $orderNotificationUsers->rs_array($query);
    if(!empty($response)){
        $response[0]        = (object)$response[0];
        return $response;
    }else{
        return array();
    }

}
function getUsersByUserType(){
    $getUser			= new DBN();
    $getUser->set_database(DBName);

    $query                  = 'SELECT *
FROM `user`
JOIN `user_type` ON `user`.`intUserTypeId` = `user_type`.`intUserTypeId`
WHERE `user_type`.`varUserTypeName` = "Admin"';

    $response               = $getUser->rs_array($query);
    if(!empty($response)){
        $response[0]        = (object)$response[0];
        return $response;
    }else{
        return array();
    }

}
function getPrimaryUser($clientId){
    $getUser			= new DBN();
    $getUser->set_database(DBName);
    $query                  = 'SELECT *
FROM `client_contact_primary`
WHERE client_id = '.$clientId;

    $response               = $getUser->rs_array($query);
    if(!empty($response)){
        $response[0]        = (object)$response[0];
        return $response;
    }else{
        return array();
    }

}

function SendNotification($orderId, $code){

    $orderUserData                                  = getOrderNotificationUsers($orderId);



    $shortCodeArray                                 = array();

    $shortCodeArray['order_tracking_id']            = $orderUserData[0]->orderTrackingId;

    $shortCodeArray['receiver_name']                = $orderUserData[0]->receiverName;
    $shortCodeArray['receiver_email']               = $orderUserData[0]->receiverEmail;
    $shortCodeArray['receiver_mobile']              = $orderUserData[0]->receiverMobile;

    $shortCodeArray['sender_name']                  = $orderUserData[0]->senderName;
    $shortCodeArray['sender_email']                 = $orderUserData[0]->senderEmail;
    $shortCodeArray['sender_mobile']                = $orderUserData[0]->senderMobile;

    $clientDetail                                   = getPrimaryUser($orderUserData[0]->clientId);
    $shortCodeArray['client_first_name']            = $clientDetail[0]->first_name;
    $shortCodeArray['client_last_name']             = $clientDetail[0]->last_name;
    $shortCodeArray['client_name']                  = $clientDetail[0]->first_name.' '.$clientDetail[0]->last_name;;
    $shortCodeArray['client_email']                 = $orderUserData[0]->clientEmail;
    $shortCodeArray['client_mobile']                = $orderUserData[0]->clientMobile;

    $shortCodeArray['client_creator_name']          = $orderUserData[0]->clientCreatorName;
    $shortCodeArray['client_creator_email']         = $orderUserData[0]->clientCreatorEmail;
    $shortCodeArray['client_creator_mobile']        = $orderUserData[0]->clientCreatorMobile;

    $shortCodeArray['order_creator_name']           = $orderUserData[0]->orderCreatorName;
    $shortCodeArray['order_creator_email']          = $orderUserData[0]->orderCreatorEmail;
    $shortCodeArray['order_creator_mobile']         = $orderUserData[0]->orderCreatorMobile;


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

    $allAdmins          = getUsersByUserType();
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
    error_reporting(-1);
    ini_set('display_errors', 1);
    require_once("../application/libraries/Notification_lib.php");

    $notification_lib = new Notification_lib();

    echo "<pre>"; print_r('var'); exit;

    $notification_lib->orderStatusUpdateNotification($notificationArray, $code);
}

orderPaymentNotification();