<?php

/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 9/7/2017
 * Time: 1:48 AM
 */
class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    public function insertOrderCourierManRef($data){
        $this->deleteOrderCourierManRef($data['order_id'], $data['type']);
        $this->db->insert('order_courier_man_ref', $data);
        return $this->db->insert_id();
    }
    public function getOrderCourierManByOrderId($orderId, $type){
        $this->db->where('order_id', $orderId);
        $this->db->where('type', $type);
        $query		= $this->db->get('order_courier_man_ref');
        $Result 	= $query->result();
        return $Result;
    }

    public function deleteOrderCourierManRef($orderId, $type){
        $this->db->where('order_id',$orderId);
        $this->db->where('type', $type);
        $this->db->delete('order_courier_man_ref');
        return true;
    }

    public function updateOrderStatus($data){
        $this->db->where('order_id', $data['order_id']);
        $this->db->update('order_details',$data);
        return true;
    }
    public function updateOrderStatusInPayments($data){
        $this->db->where('order_id', $data['order_id']);
        $this->db->update('order_payments',$data);
        return true;
    }
    public function getOrderReceiverByOrderId($orderId){
        $this->db->where('order_id', $orderId);
        $query		= $this->db->get('order_details');
        $Result 	= $query->result();
        if($Result){
            $receiverId = $Result[0]->receiver_id;
            $this->db->where('id', $receiverId);
            $query		= $this->db->get('order_receiver');
            $Result 	= $query->result();
            if(empty($Result)){
                echo "Order Receiver Not Exist";exit;
            }
        }else{
            echo "Order Not Exist";exit;
        }
        return $Result;
    }

    public function dashboardOrderPickup($data){
        if(isset($data['clientId'])){
            $this->db->where('client_id', $data['clientId']);
        }
        else if(isset($data['userId'])){
            $this->db->where('user_id', $data['userId']);
        }
        $this->db->where('order_delivery_status', '0');
        $this->db->where('order_status', '1');
        $this->db->or_where('order_status', '2');
        $this->db->or_where('order_status', '3');
        $this->db->or_where('order_status', '11');
        $this->db->or_where('order_status', '12');
        $this->db->or_where('order_status', '13');
        $query		= $this->db->get('order_details');
        $Result 	= $query->result();
        return count($Result);
    }

    public function dashboardOrderPendingDelivery($data){
        if(isset($data['clientId'])){
            $this->db->where('client_id', $data['clientId']);
        }
        else if(isset($data['userId'])){
            $this->db->where('user_id', $data['userId']);
        }
        $this->db->where('order_delivery_status != ', '0');
        $this->db->where('order_state', '0');
        $query		= $this->db->get('order_details');
        $Result 	= $query->result();
        return count($Result);
    }
    public function dashboardOrderDelivered($data){
        if(isset($data['clientId'])){
            $this->db->where('client_id', $data['clientId']);
        }
        else if(isset($data['userId'])){
            $this->db->where('user_id', $data['userId']);
        }
        $this->db->where('order_delivery_status', '7');
        $this->db->or_where('order_delivery_status', '13');
        $this->db->where('order_state', '1');
        $query		= $this->db->get('order_details');
        $Result 	= $query->result();
        return count($Result);
    }
    public function dashboardOrderDuePayment($data){
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->join('order_payments', 'order_details.order_id = order_payments.order_id', 'INNER');
        if(isset($data['clientId'])){
            $this->db->where('client_id', $data['clientId']);
        }
        else if(isset($data['userId'])){
            $this->db->where('user_id', $data['userId']);
        }
        $this->db->where('order_payments.payment_status', '0');
        $query		= $this->db->get();
        $Result 	= $query->result();

        $payableAmount = 0;
        foreach ($Result as $ResultIn){
            $payableAmount = $payableAmount+$ResultIn->payable_amount;
        }
        return $payableAmount;
    }
    /*public function dashboardOrderPickupForAgent($data){
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->join('order_courier_man_ref', 'order_details.order_id = order_courier_man_ref.order_id', 'INNER');
        $this->db->where('order_courier_man_ref.courier_man_id', $data['courierId']);
        $this->db->where('order_details.order_pickup_time', '');
        $this->db->where('order_details.order_delivery_status', '0');
        $this->db->where('order_details.order_status', '1');
        $query		= $this->db->get();
        $Result 	= $query->result();
        return count($Result);
    }*/


}