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
        //$this->SendNotification($_POST['CMID']);
        redirect($_SERVER['HTTP_REFERER']);
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

    public function order_originated(){
        $viewData                   = array();
        $viewData['title']                          = 'Order Originated';
        $viewData['orderStatus']                    = 1;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('1','' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');
        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function courier_assignment(){
        $viewData                   = array();
        $viewData['title']                          = 'Pick Up Assignment';
        $viewData['orderStatus']                    = 1;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('1','' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        //echo "<pre>"; print_r($viewData); exit;
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function collections_progress(){
        $viewData                                   = array();
        $viewData['title']                          = 'Collection in Prograss ';
        $viewData['orderStatus']                    = 7;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('7', '' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();

        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function reschedule_pickup(){
        $viewData                   = array();
        $viewData['title']                          = 'Pick Up Reschedule';
        $viewData['orderStatus']                    = 2;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('2', '' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function rescheduled_pickup(){
        $viewData                   = array();
        $viewData['title']                          = 'Rescheduled Pick Up';
        $viewData['orderStatus']                    = 3;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('3', '' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function collected_consignments(){
        $viewData                   = array();
        $viewData['title']                          = 'Collected Consignments';
        $viewData['orderStatus']                    = 5;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('5', '' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function delivery_scheduled(){
        $viewData                   = array();
        $viewData['title']                          = 'Delivery Scheduled';
        $viewData['orderStatus']                    = 8;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('8', '' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function call_delivery_rescheduled(){
        $viewData                   = array();
        $viewData['title']                          = 'Call Delivery Rescheduled';
        $viewData['orderStatus']                    = 9;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('9', '' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function delivery_rescheduled(){
        $viewData                   = array();
        $viewData['title']                          = 'Delivery Rescheduled';
        $viewData['orderStatus']                    = 10;
        $orders                     = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id and order_details.order_status = 1 order by order_details.order_id desc')->result();

        $viewData['orders']             = $orders;
        $orders                         = $this->getDetails('10', '' , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');

        foreach ($orders as $key=>$order){

            $orderId                = $order['order_id'];
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]['CMID']     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]['CMID']     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $viewData['courierMen']     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $viewData['orderStatuses']  = $this->db->query('select * from order_pickup_status')->result();
        $this->load->view('orderManagament/collectionProgress', $viewData);
    }

    public function changeOrderStatus(){
        $orderId                            = $_POST['orderId'];
        $orderStatus                        = $_POST['OrderStatusId'];
        $updateOrderData                    = array();
        $updateOrderData['order_id']        = $orderId;
        $updateOrderData['order_status']    = $orderStatus;
        $this->Order_model->updateOrderStatus($updateOrderData);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function orderSearch(){
        $orderStatusId = $_POST['orderStatusId'];
        if(isset($_POST['orderByRegion']) != ''){
            $orderByRegion = $_POST['orderByRegion'];
            if($orderByRegion == 'Domestic'){
                $RegionalWhere              = "and order_receiver.country_id = 15";
            }
            else if($orderByRegion == 'International'){
                $RegionalWhere              = " and order_receiver.country_id != 15";
            }
            else{
                $RegionalWhere              = "";
            }
            $query                   = 'select *
                                        from order_details , order_payments , order_airway_bill, order_receiver 
                                        where order_payments.order_id = order_details.order_id 
                                        and order_airway_bill.order_id = order_details.order_id
                                        and order_details.receiver_id = order_receiver.id
                                        and order_details.order_status = "'.$orderStatusId.'"';
            $query                   .= $RegionalWhere;
            $query                   .= ' order by order_details.order_id desc';
            /*$query                      = ' SELECT *
                                            FROM order_details
                                            JOIN order_receiver
                                            ON order_details.receiver_id = order_receiver.id
                                            JOIN order_payments
                                            ON order_payments.order_id = order_details.order_id
                                            JOIN order_airway_bill
                                            ON order_details.order_id = order_airway_bill.order_id';
            $query                      .= ' WHERE ProductSubcategoryID = 15';
            $query                      .= $RegionalWhere;
            $query                      .= ' order by order_details.order_id desc';*/
            $orders                     = $this->db->query($query)->result();
            $orderHtml                  = $this->generateHtml($orders);

            $response                   = array();
            $response['error']                      = '0';
            $response['msg']                        = '';
            $response['response']                   = $orderHtml;

            echo json_encode($response); exit;

        }

    }

    public function generateHtml($orders){
        $viewData                   = array();
        foreach ($orders as $key=>$order){

            $orderId                = $order->order_id;
            $OrderCourierManRef     = $this->Order_model->getOrderCourierManByOrderId($orderId);
            if(!empty($OrderCourierManRef)){
                $orders[$key]->CMID     = $OrderCourierManRef[0]->courier_man_id;
            }else{
                $orders[$key]->CMID     = 0;
            }

        }
        //echo "<pre>"; print_r($orders); exit;
        $viewData['orders']             = $orders;
        $courierMen                     = $this->db->query('select * from user,user_type where user.intUserTypeId = user_type.intUserTypeId and user.intUserTypeId = 8')->result();
        $orderStatuses                  = $this->db->query('select * from order_pickup_status')->result();

        $returnHtml = array();
        if(!empty($orders)){
            foreach($orders as $order){
                $country_id = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->country_id;

                $temp = "<tr>
                        <td></td>
                        <td><a href='".CTRL."Order/view_order?ref-id=".$order->order_tracking_id."'>".$order->airway_bill."</a></td>
                        <td>".$order->created_on."</td>
                        <td>".$this->db->where('id',$country_id)->get('country_table')->result()[0]->country_name."</td>
                        <td>".$this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->name."</td>
                        <td>".$this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->name."</td>";

                $orderStatusText = '';
                foreach ($orderStatuses as $orderStatus) {
                    if ($orderStatus->id == $order->order_status) {
                        $orderStatusText = $orderStatus->status;
                        break;
                    }
                }
                $temp .= "<td>".$orderStatusText."</td>";




                $temp .=  '<td>';
                //courier data start
                $temp .=   '    <small style="float: left !important;"><b>Assign courier</b></small>';
                $temp .= '      <div class="btn-group pull-right" id="CMDID-'.$order->order_id.'" style="float: left !important;" >';

                                    $buttonText = 'Select Courier Man';
                                    foreach ($courierMen as $courierMan) {
                                        if ($courierMan->intUserId == $order->CMID) {
                                            $buttonText = $courierMan->varEmailId;
                                            break;
                                        }
                                    }

                $temp .= '          <button class="btn green btn-xs btn-outline dropdown-toggle" data-toggle="dropdown">'. $buttonText.'
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    
                                    <ul class="dropdown-menu pull-right">';
                                        foreach ($courierMen as $courierMan){
                                            if($courierMan->intUserId){
                                                $temp .= '<li class="courierMan" id="'.$courierMan->intUserId.'">
                                                    <a href="javascript:;" >
                                                        '.$courierMan->varEmailId.' </a>
                                                </li>';
                                             }
                                        }
                $temp .= '           </ul>
                               </div>
                                    <br/>';
                //courier data end

                $temp .= '      <small style="float: left !important;"><b>Update status</b></small>
               
                                <div class="btn-group pull-right" id="OrderStatus-'.$order->order_id.'" style="float: left !important;" >';

                                    $buttonText = 'Select Order Status';
                                    foreach ($orderStatuses as $orderStatus) {
                                        if ($orderStatus->id == $order->order_status) {
                                            $buttonText = $orderStatus->status;
                                            break;
                                        }
                                    }
                $temp .= '     <button class="btn green btn-xs btn-outline dropdown-toggle" data-toggle="dropdown">'.$buttonText.'
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right">';
                                        foreach ($orderStatuses as $orderStatus){
                                            if($orderStatus->id){
                                                $temp .= '            <li class="OrderStatus" id="'.$orderStatus->id.'">
                                                    <a href="javascript:;" >
                                                        '.$orderStatus->status.' </a>
                                                </li>';
                                             }
                                        }
                $temp .= '          </ul>
                                </div>';

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

    public function getDetails($orderStatus, $client_id , $AWB , $country , $sender_name , $receiver_name , $billAmountFrom , $billAmountTo , $days_balance_from , $days_balance_to ,  $order_date_from , $order_date_to , $payment_due_from , $payment_due_to , $paid_date_from , $paid_date_to , $order_status , $status)
    {

        if($client_id != ''){
            $result = $this->db->where('client_id',$client_id)->get('client_table')->result();

            $client_id = $result[0]->client_id;

            $credit_days = $result[0]->credit_days;
            //$orders['orders'] = $this->db->where('client_id',$result[0]->client_id)->get('order_details')->result();
            $orders = $this->db->query('select * from order_details , order_payments , order_airway_bill where order_payments.order_id = order_details.order_id and order_airway_bill.order_id = order_details.order_id and order_details.client_id = '.$result[0]->client_id)->result();
        }else{
            $credit_days = 1;
            $orders = $this->db->query('select * from order_details , order_payments , order_airway_bill where order_payments.order_id = order_details.order_id and order_airway_bill.order_id = order_details.order_id and order_details.order_status = "'.$orderStatus.'" order by order_details.order_id desc')->result();
        }

        //echo "<pre>"; print_r($orders); exit;

        $formatted_orders = array();

        foreach($orders as $order)
        {
            $view_items['order_AWB'] = $order->airway_bill;
            $view_items['order_status'] = $order->order_status;
            $view_items['order_tracking_id'] = $order->order_tracking_id;
            $view_items['order_id'] = $order->order_id;
            $view_items['order_date'] = $order->created_on;
            $country_id = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->country_id;
            $country_id2 = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->country_id;
            $view_items['receiver_country'] = $this->db->where('id',$country_id)->get('country_table')->result()[0]->country_name;
            $view_items['sender_country'] = $this->db->where('id',$country_id2)->get('country_table')->result()[0]->country_name;
            $view_items['sender_name'] = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->name;
            $view_items['receiver_name'] = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->name;
            $view_items['bill_amount'] = $order->payable_amount;
            $view_items['payment_due_date'] = date('Y-m-d', strtotime('+'.$credit_days.' days '. $order->created_on)) ;

            $date1=date_create(date('Y-m-d'));
            $date2=date_create($view_items['payment_due_date']);
            $diff=date_diff($date1,$date2);

            $day_sign =  $diff->format("%R");
            $days =  $diff->format("%a");
            $view_items['days_balance'] =  $diff->format("%R%a");

            if($order->paid_date != '')
            {
                $view_items['paid_date'] = explode(' ', $order->paid_date)[0];
            }
            else
            {
                $view_items['paid_date'] = $order->paid_date;
            }
            if ($order->paid_date == '')
            {
                if($day_sign == '+')
                    $view_items['status'] = 'Due';
                else
                    $view_items['status'] = 'Over Due';

                $view_items['paid_date'] = '';
            }
            else
            {
                $view_items['status'] = 'Paid';
            }

            //die(var_dump($view_items));
            if($AWB != '' && $AWB == $order->airway_bill)
            {
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($order_status != '' && $order_status == $view_items['status'])
            {
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if(($billAmountFrom != '' || $billAmountTo != ''))
            {
                if($billAmountFrom == '')
                    $billAmountFrom = 0;
                else if($billAmountTo == '')
                    $billAmountTo = 10000000;

                if($view_items['bill_amount'] <= $billAmountTo && $view_items['bill_amount'] >= $billAmountFrom)
                {
                    if($status != '' )
                    {
                        if($status == 2)
                        {
                            if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 3)
                        {
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 4)
                        {
                            if($order->order_status == 5)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 5)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status == 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 6)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status != 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status != 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
            }

            if(($days_balance_from != '' || $days_balance_to != ''))
            {
                if($days_balance_from == '')
                    $days_balance_from = 0;
                else if($days_balance_to == '')
                    $days_balance_to = 10000000;

                if($days <= $days_balance_to && $days >= $days_balance_from)
                {
                    if($status != '' )
                    {
                        if($status == 2)
                        {
                            if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 3)
                        {
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 4)
                        {
                            if($order->order_status == 5)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 5)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status == 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 6)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status != 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status != 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                // $formatted_orders[] = $view_items;
            }
            //else if($country != '' && $country == $view_items['receiver_country'])
            if($country != '' && strpos (strtolower ( $view_items['receiver_country']), ($country))  !== false )
            {
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($receiver_name != '' && strpos (strtolower($view_items['receiver_name']), ($receiver_name))  !== false )
            {
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($sender_name != '' && strpos (strtolower ( $view_items['sender_name']),strtolower($sender_name))  !== false )
            {
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($order_date_from != '' || $order_date_to != '')
            {


                if($order_date_from == '')
                {
                    if(new DateTime(explode(' ', $order->created_on)[0])  <= new DateTime($order_date_to) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                }
                else if($order_date_to == '')
                {
                    if(new DateTime(explode(' ', $order->created_on)[0]) >= new DateTime($order_date_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                }
                else
                {
                    if(new DateTime(explode(' ', $order->created_on)[0]) <= new DateTime($order_date_to) && new DateTime(explode(' ', $order->created_on)[0]) >= new DateTime($order_date_from) )
                    {
                        //$formatted_orders[] = $view_items;
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                }
            }

            if($payment_due_from != '' || $payment_due_to != '')
            {


                if($payment_due_from == '')
                {
                    if(new DateTime(explode(' ', $view_items['payment_due_date'])[0])  <= new DateTime($payment_due_to) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else if($payment_due_to == '')
                {
                    if(new DateTime(explode(' ', $view_items['payment_due_date'])[0]) >= new DateTime($payment_due_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else
                {
                    if(new DateTime(explode(' ', $view_items['payment_due_date'])[0]) <= new DateTime($payment_due_to) && new DateTime(explode(' ', $view_items['payment_due_date'])[0]) >= new DateTime($payment_due_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
            }
            else if(($paid_date_from != '' || $paid_date_to != '' ) && $view_items['paid_date'] != '' )
            {


                if($paid_date_from == '')
                {
                    if(new DateTime(explode(' ', $view_items['paid_date'])[0])  <= new DateTime($paid_date_to) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else if($paid_date_to == '')
                {
                    if(new DateTime(explode(' ', $view_items['paid_date'])[0]) >= new DateTime($paid_date_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else
                {
                    if(new DateTime(explode(' ', $view_items['paid_date'])[0]) <= new DateTime($paid_date_to) && new DateTime(explode(' ', $view_items['paid_date'])[0]) >= new DateTime($paid_date_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
            }
            // payment_due_from
            if($AWB == '' && $country == '' && $receiver_name == '' && $days_balance_from == '' && $days_balance_to == '' && $billAmountTo == '' && $billAmountFrom == '' && $order_date_to == '' && $order_date_from == '' && $paid_date_from == '' && $paid_date_to == '' && $payment_due_to == '' && $payment_due_from == '' && $order_status == '')
            {
                if($status != '')
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 1)
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

        }

        return $formatted_orders;
    }

	/*public function single_consignment()
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
	}*/
	
}


?>