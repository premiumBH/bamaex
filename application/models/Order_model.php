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
        $this->deleteOrderCourierManRef($data['order_id']);
        $this->db->insert('order_courier_man_ref', $data);
        return $this->db->insert_id();
    }
    public function getOrderCourierManByOrderId($orderId){
        $this->db->where('order_id', $orderId);
        $query		= $this->db->get('order_courier_man_ref');
        $Result 	= $query->result();
        return $Result;
    }

    public function deleteOrderCourierManRef($orderId){
        $this->db->where('order_id',$orderId);
        $this->db->delete('order_courier_man_ref');
        return true;
    }

    public function updateOrderStatus($data){
        $this->db->where('order_id', $data['order_id']);
        $this->db->update('order_details',$data);
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


}