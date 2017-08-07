<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/7/2017
 * Time: 7:39 PM
 */?>
<?php
class Notification_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    public function getNotificationCats($id = false){
        if($id){
            $this->db->where('id', $id);
        }
        $query		= $this->db->get('notification_category');
        $Result 	= $query->result();
        return $Result;
    }
    public function getNotification($id = false){
        if($id){
            $this->db->where('id', $id);
        }
        $query		= $this->db->get('notification');
        $Result 	= $query->result();
        return $Result;
    }
    public function insert($data){
        $this->db->insert('notification', $data);
        return $this->db->insert_id();
    }

    public function update( $data){
        $this->db->where('id', $data['id']);
        $this->db->update('notification',$data);
    }
    public function delete($id){
        $this->db->where('id',$id);
        $this->db->delete('notification');
        return true;
    }

    public function isNotificationExist($catId, $notifyId = false){
        if($notifyId != ''){
            $this->db->where('id !=', $notifyId);
        }
        $this->db->where('notify_cat_id', $catId);
        $this->db->where('status', 1);
        $query		= $this->db->get('notification');
        $Result 	= $query->result();
        return $Result;

    }
}
?>
