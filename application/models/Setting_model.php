<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/7/2017
 * Time: 7:39 PM
 */?>
<?php
class Setting_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    //payment type start
    public function insertPaymentType($data){
        $this->db->insert('payment_types', $data);
        return $this->db->insert_id();
    }
    public function updatePaymentType( $data){
        $this->db->where('id', $data['id']);
        $this->db->update('payment_types',$data);
    }

    //payment type end

    //service type start
    public function insertService($data){
        $this->db->insert('service_table', $data);
        return $this->db->insert_id();
    }
    public function updateService( $data){
        $this->db->where('id', $data['id']);
        $this->db->update('service_table',$data);
    }
    //service type end

    //config type start
    public function insertConfig($data){
        $this->db->insert('site_config', $data);
        return $this->db->insert_id();
    }
    public function updateConfig( $data){
        $this->db->where('id', $data['id']);
        $this->db->update('site_config',$data);
    }

    //config type end

    public function getMyData($tableName, $whereArray){

        foreach($whereArray as $where=>$val){
            $this->db->where($where, $val);
        }
        $query		= $this->db->get($tableName);
        $Result 	= $query->result();
        return $Result;
    }

    public function deleteMyData($tableName, $whereArray){

        foreach($whereArray as $where=>$val){
            $this->db->where($where, $val);
        }
        $this->db->delete($tableName);
    }
}
?>
