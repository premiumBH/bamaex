<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 9/20/2017
 * Time: 12:35 AM
 */?>
<?php

/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 9/7/2017
 * Time: 1:48 AM
 */
class Order_management_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    public function getUnscheduledOrder($data){
        $this->db->select('order_details.*, 
                            order_payments.*, 
                            order_airway_bill.*,
                            country_table.*,
                            order_pickup_status.*,
                            order_receiver.name as receiverName , 
                            order_details.order_status as order_status_id ,
                             country_table.id as countryId ,
                            order_sender.name as senderName ');
        $this->db->from('order_details');
        $this->db->join('order_payments', 'order_payments.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_airway_bill', 'order_airway_bill.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_receiver', 'order_receiver.id = order_details.receiver_id', 'INNER');
        $this->db->join('country_table', 'order_receiver.country_id = country_table.id', 'INNER');
        $this->db->join('order_sender', 'order_sender.id = order_details.sender_id', 'INNER');
        $this->db->join('order_pickup_status', 'order_pickup_status.id = order_details.order_status', 'INNER');
        //$this->db->where('order_details.order_delivery_status', 0);
        //$this->db->where('order_details.order_state', 0);
        //$this->db->where('order_details.order_status', $data['orderStatus1']);
        $this->db->where('order_details.order_pickup_date', '');
        $this->db->where('order_details.order_pickup_time', '');
        if(isset($data['orderStatus2'])){
            $this->db->or_where('order_details.order_status', $data['orderStatus2']);
        }
        $this->db->order_by("order_details.order_id", "desc");
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }

    public function getPickupStatusOrder($data){
        $this->db->select('order_details.*, 
                            order_payments.*, 
                            order_airway_bill.*,
                            country_table.*,
                            order_pickup_status.*,
                            order_receiver.name as receiverName , 
                            order_details.order_status as order_status_id ,
                             country_table.id as countryId ,
                            order_sender.name as senderName ');
        $this->db->from('order_details');
        $this->db->join('order_payments', 'order_payments.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_airway_bill', 'order_airway_bill.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_receiver', 'order_receiver.id = order_details.receiver_id', 'INNER');
        $this->db->join('country_table', 'order_receiver.country_id = country_table.id', 'INNER');
        $this->db->join('order_sender', 'order_sender.id = order_details.sender_id', 'INNER');
        $this->db->join('order_pickup_status', 'order_pickup_status.id = order_details.order_status', 'INNER');
        $this->db->where('order_details.order_delivery_status', 0);
        $this->db->where('order_details.order_state', 0);
        $this->db->where('order_details.order_status', $data['orderStatus1']);
        $this->db->where('order_details.order_pickup_date != ', '');
        $this->db->where('order_details.order_pickup_time != ', '');
        if(isset($data['orderStatus2'])){
            $this->db->or_where('order_details.order_status', $data['orderStatus2']);
        }
        if(isset($data['orderStatus3'])){
            $this->db->or_where('order_details.order_status', $data['orderStatus3']);
        }
        $this->db->order_by("order_details.order_id", "desc");
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }


    public function getDeliveryStatusOrder($data){

        if($data['OrderType'] == 'dom'){
            $statusTable = 'domestic_delivery_status';

        }else{
            $statusTable = 'express_delivery_status';
        }
        $this->db->select('order_details.*, 
                            order_payments.*, 
                            order_airway_bill.*,
                            country_table.*,
                            '.$statusTable.'.*,
                            order_receiver.name as receiverName , 
                            order_details.order_status as order_status_id , 
                            country_table.id as countryId ,
                            order_sender.name as senderName ');
        $this->db->from('order_details');
        $this->db->join('order_payments', 'order_payments.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_airway_bill', 'order_airway_bill.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_receiver', 'order_receiver.id = order_details.receiver_id', 'INNER');
        $this->db->join('country_table', 'order_receiver.country_id = country_table.id', 'INNER');
        $this->db->join('order_sender', 'order_sender.id = order_details.sender_id', 'INNER');
        $this->db->join($statusTable, ''.$statusTable.'.id = order_details.order_delivery_status', 'INNER');
        $this->db->where('order_details.order_delivery_status != ', 0);
        $this->db->where('order_details.order_delivery_status', $data['orderStatus1']);
        $this->db->where('order_details.order_pickup_date != ', '');
        $this->db->where('order_details.order_pickup_time != ', '');
        if($data['OrderType'] == 'dom'){
            $this->db->where('order_receiver.country_id', '15');
        }else{
            $this->db->where('order_receiver.country_id != ', '15');
        }
        //$this->db->where('order_details.order_state', 0);
        //$this->db->where('order_details.order_status', $data['orderStatus1']);
        if(isset($data['orderStatus2'])){
            $this->db->or_where('order_details.order_status', $data['orderStatus2']);
        }
        $this->db->order_by("order_details.order_id", "desc");
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }

    public function getAllOtherDeliveryStatusOrder($data){

        if($data['OrderType'] == 'dom'){
            $statusTable = 'domestic_delivery_status';
            $ignore = array(1,2,3,4,5,7,17);

        }else{
            $statusTable = 'express_delivery_status';
            $ignore = array(1,9,8,10,11,13,22);
        }
        $this->db->select('order_details.*, 
                            order_payments.*, 
                            order_airway_bill.*,
                            country_table.*,
                            '.$statusTable.'.*,
                            order_receiver.name as receiverName , 
                            order_details.order_status as order_status_id , 
                            country_table.id as countryId ,
                            order_sender.name as senderName ');
        $this->db->from('order_details');
        $this->db->join('order_payments', 'order_payments.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_airway_bill', 'order_airway_bill.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_receiver', 'order_receiver.id = order_details.receiver_id', 'INNER');
        $this->db->join('country_table', 'order_receiver.country_id = country_table.id', 'INNER');
        $this->db->join('order_sender', 'order_sender.id = order_details.sender_id', 'INNER');
        $this->db->join($statusTable, ''.$statusTable.'.id = order_details.order_delivery_status', 'INNER');

        $this->db->where_not_in('order_details.order_delivery_status', $ignore);
        $this->db->where('order_details.order_pickup_date != ', '');
        $this->db->where('order_details.order_pickup_time != ', '');
        //$this->db->where('order_details.order_delivery_status != ', 0);
        //$this->db->where('order_details.order_delivery_status', $data['orderStatus1']);
        if($data['OrderType'] == 'dom'){
            $this->db->where('order_receiver.country_id', '15');
        }else{
            $this->db->where('order_receiver.country_id != ', '15');
        }
        //$this->db->where('order_details.order_state', 0);
        //$this->db->where('order_details.order_status', $data['orderStatus1']);
        if(isset($data['orderStatus2'])){
            $this->db->or_where('order_details.order_status', $data['orderStatus2']);
        }
        $this->db->order_by("order_details.order_id", "desc");
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }

    public function orderRegion($data){
        $this->db->select('order_details.*, 
                            order_payments.*, 
                            order_airway_bill.*,
                            country_table.*,
                            order_pickup_status.*,
                            order_receiver.name as receiverName , 
                            order_details.order_status as order_status_id ,
                             country_table.id as countryId ,
                            order_sender.name as senderName ');
        $this->db->from('order_details');
        $this->db->join('order_payments', 'order_payments.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_airway_bill', 'order_airway_bill.order_id = order_details.order_id', 'INNER');
        $this->db->join('order_receiver', 'order_receiver.id = order_details.receiver_id', 'INNER');
        $this->db->join('country_table', 'order_receiver.country_id = country_table.id', 'INNER');
        $this->db->join('order_sender', 'order_sender.id = order_details.sender_id', 'INNER');
        $this->db->join('order_pickup_status', 'order_pickup_status.id = order_details.order_status', 'INNER');
        $this->db->where('order_details.order_id', $data['orderId']);
        $this->db->order_by("order_details.order_id", "desc");
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }

    public function insertOrderTracking($table, $data){
        $this->db->insert($table, $data);
        return $this->db->insert_id();

    }

    public function getStatusById($table, $id){
        $this->db->where('id', $id);
        $result = $this->db->get($table);
        $result = $result->result();
        return $result;
    }

}
