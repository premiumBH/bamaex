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
        $this->Order_model->insertOrderCourierManRef($insert);
        $this->OrderStatusAssignToCourier($_POST['orderId']);
        $this->SendNotification($_POST['CMID']);
        redirect(CTRL.'OrderManagement/orderList');
    }

    public function OrderStatusAssignToCourier($orderId){
        $updateOrderData                    = array();
        $updateOrderData['order_id']        = $orderId;
        $updateOrderData['order_status']    = 7;
        $this->Order_model->updateOrderStatus($updateOrderData);
    }

    public function SendNotification($courierManId){

        $courierManData                                 = $this->User_model->getUserById($courierManId);
        $receiverData                                   = $this->Order_model->getOrderReceiverByOrderId($_POST['orderId']);

        $shortCodeArray                                 = array();
        $shortCodeArray['courier_email']                 = $courierManData[0]->varEmailId;
        $shortCodeArray['order_id']                      = $_POST['orderId'];
        $shortCodeArray['receiver_email']               = $receiverData[0]->email;
        $shortCodeArray['receiver_name']                = $receiverData[0]->name;


        $notificationArray                                              = array();
        $userType                                                       = 'Courier';
        $notificationArray[$userType]                                   = array();

        $notificationArray[$userType]['email']                          = array($courierManData[0]->varEmailId);
        $notificationArray[$userType]['number']                         = array($courierManData[0]->varMobileNo);
        $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

        $userType                                                       = 'Receiver';
        $notificationArray[$userType]                                   = array();

        $notificationArray[$userType]['email']                          = array($receiverData[0]->email);
        $notificationArray[$userType]['number']                         = array($receiverData[0]->mobile);
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

        $this->notification_lib->OrderAssignmentToCourierNotification($notificationArray);
    }

	public function single_consignment()
	{
	        $this->load->view('page/Order Management/Prepare Order/single-consignment');
	}
	public function multiple_consignment()
	{
	        $this->load->view('page/Order Management/Prepare Order/multiple-consignment');
	}
	public function pickup_request()
	{
	        $this->load->view('page/Order Management/Prepare Order/pickup-request');
	}
	public function track_order()
	{
	        $this->load->view('page/Order Management/track-order');
	}
	public function order_status()
	{
	        $this->load->view('page/Order Management/order-status');
	}
	public function order_list()
	{
	        $this->load->view('page/Order Management/order-list');
	}
	
}


?>