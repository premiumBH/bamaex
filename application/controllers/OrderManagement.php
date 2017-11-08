<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrderManagement extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct() {
        parent::__construct();
        $isLoggedIn = $this->session->userdata('logged_in');
        if(!$isLoggedIn){
            redirect(SITE.'backend');
        }
        $this->load->database();

        //	$this->load->helper('dynmic-css-js');
        $this->load->model('User_model');
        $this->load->model('Admin_model');
        $this->load->model('Order_model');
        $this->load->model('Order_management_model');
        $this->load->model('Client_model');
        $this->load->library('Notification_lib');

        $this->load->helper('cookie');

    }

    public function index()
    {
        $this->load->view('backend');
        //
    }

    public function orderList(){
        $viewData                   = array();
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        foreach ($orders as $key=>$order){
            $orderId                = $order->order_id;
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]->CMID     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]->CMID     = 0;
            }

        }
        $viewData['order']          = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $this->load->view('Order/paddingOrders', $viewData);
    }

    public function assignCourierMan(){

        $insert                         = array();
        $insert['order_id']             = $_POST['orderId'];
        $insert['courier_man_id']       = $_POST['CMID'];
        $insert['type']                 = 'pickup';
        if(isset($_POST['type'])){
            $insert['type']                 = 'delivery';
        }

        $this->Order_model->insertOrderCourierManRef($insert);
        if(!isset($_POST['type'])){
            $this->OrderStatusAssignToCourier($_POST['orderId']);
        }else{
            $_POST['orderId']               = $_POST['orderId'];
            $_POST['OrderStatusId']         = isset($_POST['orderStatus'])?$_POST['orderStatus']:'';
            $customStatus                  = array('Delivery Courier Assigned','Delivery Agent Assigned');
            $this->orderTracking('delivery', $customStatus);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    public function OrderStatusAssignToCourier($orderId){
        $updateOrderData                    = array();
        $updateOrderData['order_id']        = $orderId;
        $updateOrderData['order_status']    = $_POST['orderStatus'];
        $this->Order_model->updateOrderStatus($updateOrderData);
        $_POST['orderId']               = $orderId;
        $_POST['OrderStatusId']         = $_POST['orderStatus'];
        $this->orderTracking('pickup');
    }

    public function order_originated(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                              = 'Order Originated (unscheduled)';
        }
        $viewData['orderStatus']                            = 1;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getUnscheduledOrder($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        foreach ($orders as $key=>$order){
            $orders[$key]->order_status_id          = '';
            $orders[$key]->status                   = 'Order Originated';
        }
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $viewData['IsOrderOriginated']               = true;
        $this->load->view('orderManagament/orderListing', $viewData);
    }

    public function schedule_pickup(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                              = 'Schedule Pickup';
        }
        $viewData['orderStatus']                            = 1;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/orderListing', $viewData);
    }

    public function incoming_to_bahrain(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                              = 'Incoming to Bahrain';
        }
        $viewData['orderStatus']                            = 200;
        $mData                                              = array();
        $mData['orderStatus1']                              = array('200', '201', '202', '203', '204');//;$viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getAgentOrderStatus($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        $viewData['orderStatuses']                          = $this->db->query('select * from order_agent_status')->result();
        $viewData['orderStatuses'][5] = (object)array('id'=>'5', 'status'=>'Received in warehouse');
        $this->load->view('orderManagament/agentOrderListing', $viewData);
    }

    public function pending_pickup(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                                  = 'Order Originated';
        }

        $viewData['orderStatus']                            = 1;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/orderListing', $viewData);
    }

    public function assign_to_courier(){
        $viewData                                   = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                          = 'Assign To Courier';
        }

        $viewData['orderStatus']                    = 1;
        $mData                                      = array();
        $mData['orderStatus1']                      = $viewData['orderStatus'];
        $mData['orderStatus2']                      = 11;
        $mData['orderStatus3']                      = 13;
        $orders                                     = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orderQuery']                     = str_replace("\n"," ",$this->db->last_query());
        foreach ($orders as $key=>$order){

            $orderId                                = $order->order_id;
            $OrderCourierManRef                     = $this->Order_model->getOrderCourierManByOrderId($orderId, 'pickup');
            if(!empty($OrderCourierManRef)){
                $orders[$key]->CMID                 = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]->CMID                 = 0;
            }
        }
        $viewData['orders']                         = $orders;
        $viewData['courierMen']                     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']                  = $this->db->query('select * from order_pickup_status')->result();
        $viewData['IsShowCourierCol']               = true;
        $viewData['IsPUAssignCour']                 = true;
        $this->load->view('orderManagament/orderListing', $viewData);
    }

    public function collections_progress(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                                  = 'Collections In Progress';
        }

        $viewData['orderStatus']                            = 12;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        foreach ($orders as $key=>$order){
            $orderId                                = $order->order_id;
            $OrderCourierManRef                     = $this->Order_model->getOrderCourierManByOrderId($orderId, 'pickup');
            if(!empty($OrderCourierManRef)){
                $userData = $this->db->query('select * from user where 	intUserId = '.$OrderCourierManRef[0]->courier_man_id.'')->result();
                if(!empty($userData)){
                    $orders[$key]->CMName                 = $userData[0]->varEmailId;
                }else{
                    $orders[$key]->CMName                 = 'Courier Not Exist';
                }

            }else{
                $orders[$key]->CMName                 = 'Not Assign';
            }
        }
        $viewData['orders']                                 = $orders;
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/orderListing', $viewData);
    }

    public function reschedule_pickup(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                                  = 'Pick Up Reschedule';
        }

        $viewData['orderStatus']                            = 2;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/orderListing', $viewData);
    }

    public function rescheduled_pickup(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                                  = 'Rescheduled Pick Up';
        }

        $viewData['orderStatus']                            = 3;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        foreach ($orders as $key=>$order){
            $orderId                                = $order->order_id;
            $OrderCourierManRef                     = $this->Order_model->getOrderCourierManByOrderId($orderId, 'pickup');
            if(!empty($OrderCourierManRef)){
                $orders[$key]->CMID                 = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]->CMID                 = 0;
            }
        }

        $viewData['orders']                                 = $orders;

        $viewData['courierMen']                             = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/orderListing', $viewData);
    }
    public function consignement_picked_uup(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                                  = 'Consignment picked up';
        }

        $viewData['orderStatus']                            = 4;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/orderListing', $viewData);
    }



    public function collected_consignments(){
        $viewData                                           = array();
        $mData                                              = array();
        $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
        $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
        if(!empty($menuInfo)){
            $viewData['title']                              = $menuInfo[0]->Label;
        }else{
            $viewData['title']                                  = 'Received in warehouse';
        }

        $viewData['orderStatus']                            = 5;
        $mData                                              = array();
        $mData['orderStatus1']                              = $viewData['orderStatus'];
        $orders                                             = $this->Order_management_model->getPickupStatusOrder($mData);
        $viewData['orders']                                 = $orders;
        $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
        $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
        $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
        $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
        $viewData['isShowRegionFilter']                     = true;
        $this->load->view('orderManagament/deliveryOrderListing', $viewData);
    }



    //delivery Statuses

    public function assign_delivery_courier($type = 'dom'){

        if($type == 'dom'){

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Assign delivery to Courier';
            }

            $viewData['orderStatus']                            = 1;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            foreach ($orders as $key=>$order){

                $orderId                                = $order->order_id;
                $OrderCourierManRef                     = $this->Order_model->getOrderCourierManByOrderId($orderId, 'delivery');
                if(!empty($OrderCourierManRef)){
                    $orders[$key]->CMID                 = $OrderCourierManRef[0]->courier_man_id;
                }else{
                    $orders[$key]->CMID                 = 0;
                }
                if(!empty($OrderCourierManRef)){
                    $userData = $this->db->query('select * from user where 	intUserId = '.$OrderCourierManRef[0]->courier_man_id.'')->result();
                    if(!empty($userData)){
                        $orders[$key]->CMName                 = $userData[0]->varEmailId;
                    }else{
                        $orders[$key]->CMName                 = 'Courier Not Exist';
                    }

                }else{
                    $orders[$key]->CMName                 = 'Not Assign';
                }
            }

            $viewData['courierMen']                             = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['IsShowCourierCol']                       = true;
            $viewData['showCourierAssignedName']                = true;
            $viewData['showCourierAssignedNameTitle']           = 'Delivery Agent Assigned';
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }else {
            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__."/exp" ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Assign delivery to Agent';
            }

            $viewData['orderStatus']                            = 1;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            foreach ($orders as $key=>$order){

                $orderId                                = $order->order_id;
                $OrderCourierManRef                     = $this->Order_model->getOrderCourierManByOrderId($orderId, 'delivery');
                if(!empty($OrderCourierManRef)){
                    $orders[$key]->CMID                 = $OrderCourierManRef[0]->courier_man_id;

                }else{
                    $orders[$key]->CMID                 = 0;
                }
                if(!empty($OrderCourierManRef)){
                    $userData = $this->db->query('select * from user where 	intUserId = '.$OrderCourierManRef[0]->courier_man_id.'')->result();
                    if(!empty($userData)){
                        $orders[$key]->CMName                 = $userData[0]->varEmailId;
                    }else{
                        $orders[$key]->CMName                 = 'Courier Not Exist';
                    }

                }else{
                    $orders[$key]->CMName                 = 'Not Assign';
                }
            }
            $viewData['courierMen']                             = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 4')->result();
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['IsShowCourierCol']                       = true;
            $viewData['showCourierAssignedName']                = true;
            $viewData['showCourierAssignedNameTitle']           = 'Delivery Agent Assigned';
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }
    }

    public function delivery_scheduled($type = 'dom'){
        if($type == 'dom'){

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Delivery scheduled';
            }

            $viewData['orderStatus']                            = 3;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }else {

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__."/exp" ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Delivery scheduled';
            }

            $viewData['orderStatus']                            = 9;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }
    }

    public function reschedule_delivery($type = 'dom'){
        if($type == 'dom'){

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Call Delivery Reschedule';
            }

            $viewData['orderStatus']                            = 4;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['orderStatus2']                              = 2;
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }else {

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__."/exp" ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Call Delivery Reschedule';
            }

            $viewData['orderStatus']                            = 10;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['orderStatus2']                              = 8;
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }
    }

    public function delivery_rescheduled($type = 'dom'){
        if($type == 'dom'){

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Delivery Rescheduled';
            }

            $viewData['orderStatus']                            = 5;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }else {

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__."/exp" ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Delivery Rescheduled';
            }

            $viewData['orderStatus']                            = 11;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }
    }

    public function delivered_order($type = 'dom'){
        if($type == 'dom'){

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Delivered Order';
            }

            $viewData['orderStatus']                            = 7;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['noAction']                               = true;

            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }else {

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__."/exp" ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Delivered Order';
            }

            $viewData['orderStatus']                            = 13;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['noAction']                               = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }
    }

    public function returned_order($type = 'dom'){
        if($type == 'dom'){

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Returned Order';
            }

            $viewData['orderStatus']                            = 17;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['noAction']                               = true;

            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }else {

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__."/exp" ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'Returned Order';
            }

            $viewData['orderStatus']                            = 22;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['noAction']                               = true;
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }
    }

    public function allOtherDeliveryStatus($type = 'dom'){
        if($type == 'dom'){

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__ ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'All Other Delivery Status';
            }

            $viewData['orderStatus']                            = 5;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getAllOtherDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            foreach ($orders as $key=>$order){

                $orderId                                = $order->order_id;
                $OrderCourierManRef                     = $this->Order_model->getOrderCourierManByOrderId($orderId, 'delivery');
                if(!empty($OrderCourierManRef)){
                    $orders[$key]->CMID                 = $OrderCourierManRef[0]->courier_man_id;
                }else{
                    $orders[$key]->CMID                 = 0;
                }
                if(!empty($OrderCourierManRef)){
                    $userData = $this->db->query('select * from user where 	intUserId = '.$OrderCourierManRef[0]->courier_man_id.'')->result();
                    if(!empty($userData)){
                        $orders[$key]->CMName                 = $userData[0]->varEmailId;
                    }else{
                        $orders[$key]->CMName                 = 'Courier Not Exist';
                    }

                }else{
                    $orders[$key]->CMName                 = 'Not Assign';
                }
            }
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['IsShowCourierCol']                       = true;
            $viewData['hideUpdateCourier']                      = true;
            $viewData['showCourierAssignedName']                = true;
            $viewData['showCourierAssignedNameTitle']           = 'Delivery Courier Assigned';
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }else {

            $viewData                                           = array();
            $mData                                              = array();
            $mData['varPageSlug']                               = 'orderManagement/'.__FUNCTION__."/exp" ;
            $menuInfo                                           = $this->Order_management_model->getMyData('access_control', $mData);
            if(!empty($menuInfo)){
                $viewData['title']                              = $menuInfo[0]->Label;
            }else{
                $viewData['title']                                  = 'All Other Delivery Status';
            }

            $viewData['orderStatus']                            = 11;
            $mData                                              = array();
            $mData['orderStatus1']                              = $viewData['orderStatus'];
            $mData['OrderType']                                 = $type;
            $orders                                             = $this->Order_management_model->getAllOtherDeliveryStatusOrder($mData);
            $viewData['orderQuery']                             = str_replace("\n"," ",$this->db->last_query());
            $viewData['orders']                                 = $orders;
            foreach ($orders as $key=>$order){

                $orderId                                = $order->order_id;
                $OrderCourierManRef                     = $this->Order_model->getOrderCourierManByOrderId($orderId, 'delivery');
                if(!empty($OrderCourierManRef)){
                    $orders[$key]->CMID                 = $OrderCourierManRef[0]->courier_man_id;
                }else{
                    $orders[$key]->CMID                 = 0;
                }
                if(!empty($OrderCourierManRef)){
                    $userData = $this->db->query('select * from user where 	intUserId = '.$OrderCourierManRef[0]->courier_man_id.'')->result();
                    if(!empty($userData)){
                        $orders[$key]->CMName                 = $userData[0]->varEmailId;
                    }else{
                        $orders[$key]->CMName                 = 'Courier Not Exist';
                    }

                }else{
                    $orders[$key]->CMName                 = 'Not Assign';
                }
            }
            $viewData['orderStatuses']                          = $this->db->query('select * from order_pickup_status')->result();
            $viewData['domesticDeliveryStatus']                 = $this->db->query('select * from domestic_delivery_status')->result();
            $viewData['expressDeliveryStatus']                  = $this->db->query('select * from express_delivery_status')->result();
            $viewData['preFillStatus']                          = true;
            $viewData['IsShowCourierCol']                       = true;
            $viewData['hideUpdateCourier']                      = true;
            $viewData['showCourierAssignedName']                = true;
            $viewData['showCourierAssignedNameTitle']           = 'Delivery Agent Assigned';
            $this->load->view('orderManagament/deliveryOrderListing', $viewData);

        }
    }

    public function changeOrderStatusPickUp(){
        $orderId                            = $_POST['orderId'];
        $orderStatus                        = $_POST['OrderStatusId'];
        $isAgentOrder                       = isset($_POST['AgentOrder'])?true:false;
        if($isAgentOrder){
            $this->orderTracking('agentPickup');
        }else{
            $this->orderTracking('pickup');
        }

        $updateOrderData                    = array();
        $updateOrderData['order_id']        = $orderId;
        $updateOrderData['order_status']    = $orderStatus;
        $updateOrderData['order_delivery_status']    = '';
        $updateOrderData['order_state']    = '';
        if($_POST['IsOrderOriginated'] == 'yes'){
            $updateOrderData['order_pickup_date']    = date('d-m-Y ');;
            $updateOrderData['order_pickup_time']    = date('g:i a');;
        }
        $this->Order_model->updateOrderStatus($updateOrderData);
        $updateOrderData                    = array();
        $updateOrderData['order_id']        = $orderId;
        $updateOrderData['order_status']    = $orderStatus;
        $this->Order_model->updateOrderStatusInPayments($updateOrderData);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function changeOrderStatusDelivery(){
        $orderId                            = $_POST['orderId'];
        $orderStatus                        = $_POST['OrderStatusId'];
        $this->orderTracking('delivery');
        $updateOrderData                    = array();
        $updateOrderData['order_id']        = $orderId;
        $updateOrderData['order_delivery_status']    = $orderStatus;

        $mData                                              = array();
        $mData['orderId']                                   = $orderId;
        $orderData                                          = $this->Order_management_model->orderRegion($mData);
        if(isset($orderData[0]->countryId) && $orderData[0]->countryId == 15){
            if($_POST['OrderStatusId'] == '7' || $_POST['OrderStatusId'] == '17'){
                $updateOrderData['order_state']    = '1';
            }
        }else if(isset($orderData[0]->countryId) && $orderData[0]->countryId != 15){
            if($_POST['OrderStatusId'] == '13' || $_POST['OrderStatusId'] == '22'){
                $updateOrderData['order_state']    = '1';
            }
        }

        //$updateOrderData['order_status']    = $orderStatus;

        $this->Order_model->updateOrderStatus($updateOrderData);
        $updateOrderData                    = array();
        $updateOrderData['order_id']        = $orderId;
        $updateOrderData['order_status']    = $orderStatus;
        $this->Order_model->updateOrderStatusInPayments($updateOrderData);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function orderTracking($type, $customStatus = array()){


        $orderId                                            = $_POST['orderId'];

        $orderStatusId                                      = isset($_POST['OrderStatusId'])?$_POST['OrderStatusId']:'';
        $mData                                              = array();
        $mData['orderId']                                   = $orderId;
        if($type == 'agentPickup'){
            $orderData                                          = $this->Order_management_model->agentOrderRegion($mData);
        }else{
            $orderData                                          = $this->Order_management_model->orderRegion($mData);
        }

        $customStatusFilter                                 = '';
        if(!empty($orderData)){

            if($type == 'pickup'){
                $orderStatusData                        = $this->Order_management_model->getStatusById('order_pickup_status',$orderStatusId);
                if(empty($customStatus)){
                    $customStatusFilter                     = $orderStatusData[0]->status;
                }else{
                    $customStatusFilter                     = $customStatus[0];
                }

                $this->SendNotification($orderId, $orderStatusId, 'pickup');
            }else if($type == 'agentPickup'){

                $orderStatusData                        = $this->Order_management_model->getStatusById('order_agent_status',$orderStatusId);
                if(empty($customStatus)){
                    $customStatusFilter                     = $orderStatusData[0]->status;
                }else{
                    $customStatusFilter                     = $customStatus[0];
                }

            }else if($type == 'delivery'){

                if(isset($orderData[0]->countryId) && $orderData[0]->countryId == 15){

                    $orderStatusData                    = $this->Order_management_model->getStatusById('domestic_delivery_status',$orderStatusId);
                    if(empty($customStatus)){
                        $customStatusFilter                     = $orderStatusData[0]->status;
                        $this->SendNotification($orderId, $orderStatusId, 'DDelivery');
                    }else{
                        $customStatusFilter                     = $customStatus[0];
                        $this->SendNotification($orderId, '', 'DDelivery');
                    }

                }else{

                    $orderStatusData                    = $this->Order_management_model->getStatusById('express_delivery_status',$orderStatusId);
                    if(empty($customStatus)){
                        $customStatusFilter                     = $orderStatusData[0]->status;
                        $this->SendNotification($orderId, $orderStatusId, 'EDelivery');
                    }else{
                        $customStatusFilter                     = $customStatus[1];
                        $this->SendNotification($orderId, '', 'EDelivery');
                    }

                }
            }

            $insert                                 = array();
            $insert['order_id']                     = $orderId;
            //$insert['order_status_id']              = $orderStatusId;
            if(!empty($orderStatusData)){
                $insert['catalog_subject']          = $orderStatusData[0]->status;
                $insert['catalog_info']             = $orderStatusData[0]->status;
            }else{
                $insert['catalog_subject']          = 'Order Status Not Exist';
                $insert['catalog_info']             = 'Order Status Not Exist';
            }
            if($customStatusFilter != ''){
                $insert['catalog_subject']          = $customStatusFilter;
                $insert['catalog_info']             = $customStatusFilter;
            }

            $userTypeId                             = $this->session->userdata('UserTypeId');

            $insert['updated_by']                   = $this->session->userdata('UserType');
            $insert['created_by']                   = $this->session->userdata('UserType');
            $insert['shipper_id']                   = $userTypeId;

            if(isset($orderData[0]->countryId) && $orderData[0]->countryId == 15){
                $this->Order_management_model->insertOrderTracking('domestic_order_catalog', $insert);
            }else {
                $this->Order_management_model->insertOrderTracking('international_order_catalog', $insert);
            }

        }

    }


    public function SendNotification($orderId, $orderStatusId, $type){

        $code = '';
        if($type == 'pickup' && $orderStatusId == '1'){
            $code = 'CPP';
        }
        else if($type == 'pickup' && $orderStatusId == '2'){
            $code = 'CRP';
        }
        else if($type == 'pickup' && $orderStatusId == '3'){
            $code = 'CPURED';
        }
        else if($type == 'pickup' && $orderStatusId == '5'){
            $code = 'CRIWH';
        }
        else if($type == 'pickup' && $orderStatusId == '11'){
            $code = 'CPUATC';
        }
        else if($type == 'pickup' && $orderStatusId == '12'){
            $code = 'CCIP';
        }
        else if($type == 'pickup' && $orderStatusId == '13'){
            $code = 'CPUSED';
        }

        else if($type == 'DDelivery' && $orderStatusId == '1'){
            $code = 'DCADTC';
        }
        else if($type == 'DDelivery' && $orderStatusId == '2'){
            $code = 'DCAFC';
        }
        else if($type == 'DDelivery' && $orderStatusId == '3'){
            $code = 'DCDS';
        }
        else if($type == 'DDelivery' && $orderStatusId == '4'){
            $code = 'DCRD';
        }
        else if($type == 'DDelivery' && $orderStatusId == '5'){
            $code = 'DCDR';
        }
        else if($type == 'DDelivery' && $orderStatusId == '6'){
            $code = 'DCOFD';
        }
        else if($type == 'DDelivery' && $orderStatusId == '7'){
            $code = 'DCD';
        }
        else if($type == 'DDelivery' && $orderStatusId == '8'){
            $code = 'DCUCNA';
        }
        else if($type == 'DDelivery' && $orderStatusId == '9'){
            $code = 'DCUCRS';
        }
        else if($type == 'DDelivery' && $orderStatusId == '10'){
            $code = 'DCUIF';
        }
        else if($type == 'DDelivery' && $orderStatusId == '11'){
            $code = 'DCUCR';
        }
        else if($type == 'DDelivery' && $orderStatusId == '12'){
            $code = 'DCURTS';
        }
        else if($type == 'DDelivery' && $orderStatusId == '13'){
            $code = 'DCUIAORWPN';
        }
        else if($type == 'DDelivery' && $orderStatusId == '14'){
            $code = 'DCUNAFC';
        }
        else if($type == 'DDelivery' && $orderStatusId == '15'){
            $code = 'DCUNOAGA';
        }
        else if($type == 'DDelivery' && $orderStatusId == '17'){
            $code = 'DCR';
        }
        else if($type == 'DDelivery' && $orderStatusId == ''){
            $code = 'DCAEDDTC';
        }


        else if($type == 'EDelivery' && $orderStatusId == '1'){
            $code = 'ECADTA';
        }
        else if($type == 'EDelivery' && $orderStatusId == '2'){
            $code = 'ECDFCOO';
        }
        else if($type == 'EDelivery' && $orderStatusId == '3'){
            $code = 'ECIT';
        }
        else if($type == 'EDelivery' && $orderStatusId == '4'){
            $code = 'ECAACOD';
        }
        else if($type == 'EDelivery' && $orderStatusId == '5'){
            $code = 'ECPCC';
        }
        else if($type == 'EDelivery' && $orderStatusId == '6'){
            $code = 'ECCBDA';
        }
        else if($type == 'EDelivery' && $orderStatusId == '7'){
            $code = 'ECIA';
        }
        else if($type == 'EDelivery' && $orderStatusId == '8'){
            $code = 'ECAFC';
        }
        else if($type == 'EDelivery' && $orderStatusId == '9'){
            $code = 'ECDS';
        }
        else if($type == 'EDelivery' && $orderStatusId == '10'){
            $code = 'ECRD';
        }
        else if($type == 'EDelivery' && $orderStatusId == '11'){
            $code = 'ECDR';
        }
        else if($type == 'EDelivery' && $orderStatusId == '12'){
            $code = 'ECOFD';
        }
        else if($type == 'EDelivery' && $orderStatusId == '13'){
            $code = 'ECD';
        }
        else if($type == 'EDelivery' && $orderStatusId == '14'){
            $code = 'ECUCNA';
        }
        else if($type == 'EDelivery' && $orderStatusId == '15'){
            $code = 'ECUCRS';
        }
        else if($type == 'EDelivery' && $orderStatusId == '16'){
            $code = 'ECUIF';
        }
        else if($type == 'EDelivery' && $orderStatusId == '17'){
            $code = 'ECUCR';
        }
        else if($type == 'EDelivery' && $orderStatusId == '18'){
            $code = 'ECURTS';
        }
        else if($type == 'EDelivery' && $orderStatusId == '19'){
            $code = 'ECUIAOWPN';
        }
        else if($type == 'EDelivery' && $orderStatusId == '20'){
            $code = 'ECUNAFC';
        }
        else if($type == 'EDelivery' && $orderStatusId == '21'){
            $code = 'ECUNOAGA';
        }
        else if($type == 'EDelivery' && $orderStatusId == '22'){
            $code = 'ECR';
        }
        else if($type == 'EDelivery' && $orderStatusId == ''){
            $code = 'ECAEDDTA';
        }



        $orderUserData                                  = $this->User_model->getOrderNotificationUsers($orderId);

        $shortCodeArray                                 = array();

        $shortCodeArray['order_tracking_id']            = $orderUserData[0]->orderTrackingId;

        $shortCodeArray['receiver_name']                = $orderUserData[0]->receiverName;
        $shortCodeArray['receiver_email']               = $orderUserData[0]->receiverEmail;
        $shortCodeArray['receiver_mobile']              = $orderUserData[0]->receiverMobile;

        $shortCodeArray['sender_name']                = $orderUserData[0]->senderName;
        $shortCodeArray['sender_email']               = $orderUserData[0]->senderEmail;
        $shortCodeArray['sender_mobile']              = $orderUserData[0]->senderMobile;

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

        if(($type == 'DDelivery' && $orderStatusId == '') ||
            ($type == 'EDelivery' && $orderStatusId == '') ||
            ($type == 'pickup' && $orderStatusId == '11')){
            $OrderCourierManRef = $this->Order_model->getOrderCourierManByOrderId($orderId, 'delivery');
            if(!empty($OrderCourierManRef)){
                $OrderCourierManData = $this->User_model->getUserById($OrderCourierManRef[0]->courier_man_id);
                if(!empty($OrderCourierManData)){
                    $shortCodeArray['courier_name']                = $OrderCourierManData[0]->varFirstName.' '.$OrderCourierManData[0]->varLastName;
                    $shortCodeArray['courier_email']               = $OrderCourierManData[0]->varEmailId;
                    $shortCodeArray['courier_mobile']              = $OrderCourierManData[0]->varMobileNo;
                }
            }
        }


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

        if($type == 'pickup' && $orderStatusId == '11'){
            $OrderCourierManRef = $this->Order_model->getOrderCourierManByOrderId($orderId, 'pickup');
            if(!empty($OrderCourierManRef)){
                $OrderCourierManData = $this->User_model->getUserById($OrderCourierManRef[0]->courier_man_id);
                if(!empty($OrderCourierManData)){
                    $userType                                                       = 'Courier';
                    $notificationArray[$userType]                                   = array();
                    $notificationArray[$userType]['email']                          = array($OrderCourierManData[0]->varEmailId);
                    $notificationArray[$userType]['number']                         = array($OrderCourierManData[0]->varMobileNo);
                    $notificationArray[$userType]['shortCode']                      = $shortCodeArray;
                }

            }

        }

        if($type == 'EDelivery' && $orderStatusId == ''){
            $OrderCourierManRef = $this->Order_model->getOrderCourierManByOrderId($orderId, 'delivery');
            if(!empty($OrderCourierManRef)){
                $OrderCourierManData = $this->User_model->getUserById($OrderCourierManRef[0]->courier_man_id);
                if(!empty($OrderCourierManData)){
                    $userType                                                       = 'Courier';
                    $notificationArray[$userType]                                   = array();
                    $notificationArray[$userType]['email']                          = array($OrderCourierManData[0]->varEmailId);
                    $notificationArray[$userType]['number']                         = array($OrderCourierManData[0]->varMobileNo);
                    $notificationArray[$userType]['shortCode']                      = $shortCodeArray;
                }

            }
        }

        else if($type == 'DDelivery' && $orderStatusId == ''){
            $OrderCourierManRef = $this->Order_model->getOrderCourierManByOrderId($orderId, 'delivery');
            if(!empty($OrderCourierManRef)){
                $OrderCourierManData = $this->User_model->getUserById($OrderCourierManRef[0]->courier_man_id);
                if(!empty($OrderCourierManData)){
                    $userType                                                       = 'Courier';
                    $notificationArray[$userType]                                   = array();
                    $notificationArray[$userType]['email']                          = array($OrderCourierManData[0]->varEmailId);
                    $notificationArray[$userType]['number']                         = array($OrderCourierManData[0]->varMobileNo);
                    $notificationArray[$userType]['shortCode']                      = $shortCodeArray;
                }

            }
        }


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

        if($code != ''){
            $this->notification_lib->orderStatusUpdateNotification($notificationArray, $code);
        }

    }











    //Search function start
    public function orderSearch(){
        $orderStatusId = $_POST['orderStatusId'];
        if(isset($_POST['orderByRegion']) != ''){

            $orderByRegion              = $_POST['orderByRegion'];
            $filteredOrders             = array();
            $query                      = $_POST['orderQuery'];
            $orders                     = $this->db->query($query)->result();

            foreach ($orders as $ordersIn){

                if($orderByRegion == 'Domestic'){
                    if($ordersIn->countryId == 15){
                        $filteredOrders[] = $ordersIn;
                    }
                }
                else if($orderByRegion == 'International'){
                    if($ordersIn->countryId != 15){
                        $filteredOrders[] = $ordersIn;
                    }
                }
                else{
                    $filteredOrders[] = $ordersIn;
                }

            }
            $orderHtml                  = $this->generateHtml($filteredOrders);

            $response                               = array();
            $response['error']                      = '0';
            $response['msg']                        = '';
            $response['response']                   = $orderHtml;

            echo json_encode($response); exit;

        }else{
            $this->orderSearchByInput('pickup');
        }

    }

    function like_match($pattern, $subject)
    {
        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }

    function check_in_range($start_date, $end_date, $date_from_user)
    {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $user_ts = strtotime($date_from_user);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    public function generateHtml($orders){

        $viewData                           = array();
        if($_POST['IsOrderOriginated'] == 'yes'){
            foreach ($orders as $key=>$order){
                $orders[$key]->order_status_id          = '';
                $orders[$key]->status                   = 'Order Originated';
            }
        }

        foreach ($orders as $key=>$order){
            $orderId                        = $order->order_id;
            $OrderCourierManRef             = $this->Order_model->getOrderCourierManByOrderId($orderId,'pickup');
            if(!empty($OrderCourierManRef)){
                $orders[$key]->CMID         = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]->CMID         = 0;
            }
        }
        $viewData['orders']             = $orders;
        $courierMen                     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $orderStatuses                  = $this->db->query('select * from order_pickup_status')->result();

        $returnHtml = array();
        if(!empty($orders)){
            foreach($orders as $order){

                $temp = "<tr>
                        <td></td>
                        <td><a href='".CTRL."Order/view_order?ref-id=".$order->order_tracking_id."'>".$order->airway_bill."</a></td>
                        <td>".$order->created_on."</td>
                        <td>".$order->country_name."</td>
                        <td>".$order->senderName."</td>
                        <td>".$order->receiverName."</td>";
                $temp .= "<td>".$order->status."</td>";
                if($_POST['orderStatusId'] == 12){
                    if($order->CMID != 0){
                        $userData = $this->db->query('select * from user where 	intUserId = '.$order->CMID.'')->result();
                        if(!empty($userData)){
                            $temp .= "<td>".$userData[0]->varEmailId."</td>";
                        }else{
                            $temp .= "<td>Courier Not Exist</td>";
                        }
                    }else{
                        $temp .= "<td>Not Assign</td>";
                    }


                }

                if($_POST['IsShowCourierCol'] == 'yes'){
                    if($order->CMID == 0){
                        $temp .= "<td></td>";
                    }else{
                        $temp .= "<td>Courier Assigned</td>";

                    }

                }

                $show = true;
                $userTypeId = $this->session->userdata('UserTypeId');
                if($userTypeId == 4 || $userTypeId == 5){
                    $show = false;
                }

                $temp .=  '<td>';
                if($show) {
                    //courier data start
                    if ($_POST['IsShowCourierCol'] == 'yes' || $_POST['orderStatusId'] == 3) {

                        $temp .= '   <div class="form-group">
                                    <label for="sel1">Assign courier:</label>
                                    <select class="form-control courierMan" id="CMDID-' . $order->order_id . '">
                                        <option value="">Select Status </option>';
                        foreach ($courierMen as $courierMenIn) {
                            if ($courierMenIn->intUserId) {
                                $temp .= '<option value="' . $courierMenIn->intUserId . '"';
                                if ($courierMenIn->intUserId == $order->CMID) {
                                    $temp .= "selected";
                                }
                                $temp .= '>';
                                $temp .= $courierMenIn->varEmailId;
                                $temp .= '</option>';
                            }
                        }
                        $temp .= '</select>
                                </div>';
                    }
                    //courier data end

                    if ($_POST['orderStatusId'] != 3) {
                        $temp .= '<div class="form-group">
                <label for="sel1">Update status:</label>
                <select class="form-control OrderStatus" id="OrderStatus-' . $order->order_id . '">
                <option value="">Select Status </option>';
                        foreach ($orderStatuses as $orderStatusIn) {
                            if ($orderStatusIn->id) {
                                $temp .= '<option value="' . $orderStatusIn->id . '"';
                                if ($orderStatusIn->id == $order->order_status_id) {
                                    $temp .= "selected";
                                }
                                $temp .= '>';
                                $temp .= $orderStatusIn->status;
                                $temp .= '</option>';
                            }
                        }
                        $temp .= '    </select>
                </div>';
                    }
                }else{
                    $temp .= '------';
                }
                $temp .= '   </td>';
                $temp .=    " </tr>";
                $returnHtml[] = $temp;
            }
            return implode(' ',$returnHtml);$returnHtml;
        }
        else{
            return 0;
        }
    }

    public function orderSearchInWareHouse(){
        $orderStatusId = $_POST['orderStatusId'];
        if(isset($_POST['orderByRegion']) != ''){

            $orderByRegion              = $_POST['orderByRegion'];
            $filteredOrders             = array();
            $query                      = $_POST['orderQuery'];
            $orders                     = $this->db->query($query)->result();

            foreach ($orders as $ordersIn){

                if($orderByRegion == 'Domestic'){
                    if($ordersIn->countryId == 15){
                        $filteredOrders[] = $ordersIn;
                    }
                }
                else if($orderByRegion == 'International'){
                    if($ordersIn->countryId != 15){
                        $filteredOrders[] = $ordersIn;
                    }
                }
                else{
                    $filteredOrders[] = $ordersIn;
                }

            }

            $orderHtml                  = $this->generateHtmlForDelivery($filteredOrders);

            $response                               = array();
            $response['error']                      = '0';
            $response['msg']                        = '';
            $response['response']                   = $orderHtml;

            echo json_encode($response); exit;

        }

    }

    public function orderSearchByInput($HtmlType = 'delivery'){
        $filteredOrders =  array();
        $query                      = $_POST['orderQuery'];
        $orders                     = $this->db->query($query)->result();
        foreach($orders as $key => $order){
            $doAdd                      = array();
            if($_POST['AWB'] != ''){
                if($this->like_match('%'.$_POST['AWB'].'%', $order->airway_bill)){
                    $doAdd[] = 'true';
                }else{
                    $doAdd[] = 'false';
                }
            }
            if($_POST['receiverName'] != ''){
                if($this->like_match('%'.$_POST['receiverName'].'%', $order->receiverName)){
                    $doAdd[] = 'true';
                }else{
                    $doAdd[] = 'false';
                }
            }
            if($_POST['startDate'] != '' && $_POST['endDate'] != ''){
                if($this->check_in_range($_POST['startDate'], $_POST['endDate'], $order->created_on)){
                    $doAdd[] = 'true';
                }else{
                    $doAdd[] = 'false';
                }
            }
            if($_POST['country'] != ''){
                if($this->like_match('%'.$_POST['country'].'%', $order->country_name)){
                    $doAdd[] = 'true';
                }else{
                    $doAdd[] = 'false';
                }
            }
            if($_POST['senderName'] != ''){
                if($this->like_match('%'.$_POST['senderName'].'%', $order->senderName)){
                    $doAdd[] = 'true';
                }else{
                    $doAdd[] = 'false';
                }
            }
            if($_POST['receiverName'] != ''){
                if($this->like_match('%'.$_POST['receiverName'].'%', $order->receiverName)){
                    $doAdd[] = 'true';
                }else{
                    $doAdd[] = 'false';
                }
            }


            if(!empty($doAdd) && !in_array('false', $doAdd)){
                $filteredOrders[] = $order;
            }

            /*if(!empty($doAdd) && in_array('true', $doAdd)){
                $filteredOrders[] = $order;
            }*/
        }

        if($HtmlType == 'pickup'){
            $orderHtml = $this->generateHtml($filteredOrders);
        }else if($HtmlType == 'delivery'){
            $orderHtml                              = $this->generateHtmlForDelivery($filteredOrders);
        }


        $response                               = array();
        $response['error']                      = '0';
        $response['msg']                        = '';
        $response['response']                   = $orderHtml;
        echo json_encode($response); exit;

    }

    public function generateHtmlForDelivery($orders){

        $viewData                           = array();


        foreach ($orders as $key=>$order){
            $orderId                        = $order->order_id;
            $OrderCourierManRef             = $this->Order_model->getOrderCourierManByOrderId($orderId,'delivery');
            if(!empty($OrderCourierManRef)){
                $orders[$key]->CMID         = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]->CMID         = 0;
            }
            if(!empty($OrderCourierManRef)){
                $userData = $this->db->query('select * from user where 	intUserId = '.$OrderCourierManRef[0]->courier_man_id.'')->result();
                if(!empty($userData)){
                    $orders[$key]->CMName                 = $userData[0]->varEmailId;
                }else{
                    $orders[$key]->CMName                 = 'Courier Not Exist';
                }

            }else{
                $orders[$key]->CMName                 = 'Not Assign';
            }
        }
        $viewData['orders']             = $orders;
        $courierMen                     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $orderStatuses                  = $this->db->query('select * from order_pickup_status')->result();
        $domesticDeliveryStatus         = $this->db->query('select * from domestic_delivery_status')->result();
        $expressDeliveryStatus          = $this->db->query('select * from express_delivery_status')->result();

        $returnHtml = array();
        if(!empty($orders)){
            foreach($orders as $order){

                $temp = "<tr>
                        <td></td>
                        <td><a href='".CTRL."Order/view_order?ref-id=".$order->order_tracking_id."'>".$order->airway_bill."</a></td>
                        <td>".$order->created_on."</td>
                        <td>".$order->country_name."</td>
                        <td>".$order->senderName."</td>
                        <td>".$order->receiverName."</td>";
                $temp .= "<td>".$order->status."</td>";
                if($_POST['IsShowCourierCol'] == 'yes'){
                    if($order->CMID == 0){
                        $temp .= "<td></td>";
                    }else{
                        if(isset($order->CMName) && $order->CMName != ''){
                            $temp .= "<td>".$order->CMName."</td>";
                        }else{
                            $temp .= "<td></td>";
                        }
                    }

                }
                $temp .=  '<td>';

                $show = true;
                $userTypeId = $this->session->userdata('UserTypeId');
                if($userTypeId == 4 || $userTypeId == 5){
                    if($userTypeId == 5){
                        $show = false;
                    }else if($userTypeId == 4){
                        if($order->countryId != 15){
                            $show = false;
                        }
                    }
                }

                if($show) {

                    //courier data start
                    if ($_POST['IsShowCourierCol'] == 'yes' && $_POST['hideUpdateCourier'] == 'no') {
                        if ($order->countryId == 15) {
                            $courierMen = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
                        } else if ($order->countryId != 15) {
                            $courierMen = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 4')->result();
                        }

                        $temp .= '   <div class="form-group">
                                    <label for="sel1">Assign courier:</label>
                                    <select class="form-control courierMan" id="CMDID-' . $order->order_id . '">
                                        <option value="">Select Status </option>';
                        foreach ($courierMen as $courierMenIn) {
                            if ($courierMenIn->intUserId) {
                                $temp .= '<option value="' . $courierMenIn->intUserId . '"';
                                if ($courierMenIn->intUserId == $order->CMID) {
                                    $temp .= "selected";
                                }
                                $temp .= '>';
                                $temp .= $courierMenIn->varEmailId;
                                $temp .= '</option>';
                            }
                        }
                        $temp .= '</select>
                                </div>';
                    }
                    //courier data end

                    if (true) {
                        if ($order->countryId == 15) {
                            $temp .= '<div class="form-group">
                                    <label for="sel1">Update status:</label>
                                    <select class="form-control OrderStatus" id="OrderStatus-' . $order->order_id . '">
                                    <option value="">Select Status </option>';
                            foreach ($domesticDeliveryStatus as $domesticDeliveryStatusIn) {
                                if ($domesticDeliveryStatusIn->id) {
                                    $temp .= '<option value="' . $domesticDeliveryStatusIn->id . '"';
                                    if ($_POST['preFillStatus'] == 'yes' && $domesticDeliveryStatusIn->id == $order->order_delivery_status) {
                                        $temp .= "selected";
                                    }
                                    $temp .= '>';
                                    $temp .= $domesticDeliveryStatusIn->status;
                                    $temp .= '</option>';
                                }
                            }
                            $temp .= '    </select>
                                    </div>';
                        } else {
                            $temp .= '<div class="form-group">
                                    <label for="sel1">Update status:</label>
                                    <select class="form-control OrderStatus" id="OrderStatus-' . $order->order_id . '">
                                    <option value="">Select Status </option>';
                            foreach ($expressDeliveryStatus as $expressDeliveryStatusIn) {
                                if ($expressDeliveryStatusIn->id) {
                                    $temp .= '<option value="' . $expressDeliveryStatusIn->id . '"';
                                    if ($_POST['preFillStatus'] == 'yes' && $expressDeliveryStatusIn->id == $order->order_delivery_status) {
                                        $temp .= "selected";
                                    }
                                    $temp .= '>';
                                    $temp .= $expressDeliveryStatusIn->status;
                                    $temp .= '</option>';
                                }
                            }
                            $temp .= '    </select>
                                    </div>';
                        }


                    }
                }else{
                    $temp .= '------';
                }
                $temp .= '   </td>';
                $temp .=    " </tr>";
                $returnHtml[] = $temp;
            }
            return implode(' ',$returnHtml);$returnHtml;
        }
        else{
            return 0;
        }
    }
    //Search function End

}


?>