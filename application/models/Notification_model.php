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
    public function getNotification($id = false, $type = false){
        if($id){
            $this->db->where('id', $id);
        }
        if($type){
            $this->db->where('type', $type);
        }
        $query		= $this->db->get('NotificationLib');
        $Result 	= $query->result();
        return $Result;
    }
    public function insert($data){
        $this->db->insert('NotificationLib', $data);
        return $this->db->insert_id();
    }

    public function update( $data){
        $this->db->where('id', $data['id']);
        $this->db->update('NotificationLib',$data);
    }
    public function delete($id){
        $this->db->where('id',$id);
        $this->db->delete('NotificationLib');
        return true;
    }

    public function isNotificationExist($catId, $notifyId = false, $type = false, $userType = false){
        if($notifyId != ''){
            $this->db->where('id !=', $notifyId);
        }
        $this->db->where('type', $type);
        $this->db->where('notify_cat_id', $catId);
        $this->db->where('user_type', $userType);
        $this->db->where('status', 1);
        $query		= $this->db->get('NotificationLib');
        $Result 	= $query->result();
        return $Result;

    }

    public function GetCategoryUserType($NotifyCatId = false){
        if($NotifyCatId){
            $this->db->where('notification_category_id', $NotifyCatId);
        }
        $query		= $this->db->get('notification_users_type');
        $Result 	= $query->result();
        return $Result;
    }

    public function GetCategoryUserTypeById($NotifyUserTypeId){
        $this->db->where('notification_users_type_id', $NotifyUserTypeId);
        $query		= $this->db->get('notification_users_type');
        $Result 	= $query->result();
        return $Result;
    }


    public function getNotificationControlRecords($NotifyCatId){
        $this->db->where('notification_category_id', $NotifyCatId);
        $query		= $this->db->get('notification_control');
        $Result 	= $query->result();
        return $Result;
    }

    public function getNotificationControlUserType($NotifyControlId){
        $this->db->where('notification_control_id', $NotifyControlId);
        $query		= $this->db->get(' notification_control_user_type');
        $Result 	= $query->result();
        return $Result;
    }

    public function insertControl($data){
        $this->db->insert('notification_control', $data);
        return $this->db->insert_id();
    }

    public function updateControl( $data){
        $this->db->where('notification_control_id', $data['notification_control_id']);
        $this->db->update('notification_control',$data);
    }

    public function insertControlUserType($data){
        $this->db->insert('notification_control_user_type', $data);
        return $this->db->insert_id();
    }

    public function deleteControlUserTypeByCatId($NotifyControlId){
        $this->db->where('notification_control_id',$NotifyControlId);
        $this->db->delete('notification_control_user_type');
        return true;
    }
}
?>
