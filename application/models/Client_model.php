<?php

class Client_model extends CI_Model
{

    public function __construct() {

        parent::__construct();

        $this->load->database();

        $this->load->helper('date');

    }
    public function create($data)
    {
        $client['company_name'] = $data['company_name'];
        $client['company_website'] = $data['company_website'];
        $client['email'] = $data['email'];
        $client['address'] = $data['address'];
        $client['city'] = $data['city'];
        $client['country_id'] = $data['country_id'];
        $client['level_id'] = $data['level_id'];
        $client['phone_no'] = $data['phone_no'];
        $client['created_on'] = date('Y-m-d H:i:s');
        $client['creater_id'] = $data['creater_id'];


        $this->db->insert('client_table',$client);
        $lastid=$this->db->insert_id();
    }
    public function getClientDetailsByID($client_id)
    {
        $this->db->select('client_table.*');
        $this->db->from('client_table');
        $this->db->where('client_id', $client_id);

        $query = $this->db->get();
        return $query->result();
    }
    public function getActiveClients()
    {
        $this->db->select('client_table.*');
        $this->db->from('client_table');
        $this->db->where('level_id', '3');

        $query = $this->db->get();
        return $query->result();
    }
    public function update($data)
    {


        $client['company_name'] = $data['company_name'];
        $client['company_website'] = $data['company_website'];
        $client['email'] = $data['email'];
        $client['address'] = $data['address'];
        $client['city'] = $data['city'];
        $client['country_id'] = $data['country_id'];
        $client['phone_no'] = $data['phone_no'];
        $client['domestic_rates'] = $data['domestic_rates'];
        $client['weight_per_price'] = $data['weight_per_price'];
        $client['credit_days'] = $data['credit_days'];
        $client['creater_id'] = $data['creater_id'];
        $this->db->where('client_id',$data['client_id']);
        $this->db->update('client_table',$client);
    }
    public function getClients()
    {
        $this->db->select('client_table.*,client_level.*');
        $this->db->from('client_table');
        $this->db->join('client_level', 'client_table.level_id = client_level.level_id');
        $query = $this->db->get();
        return $query->result();
    }
    public function getClientDetails($id)
    {
        $this->db->select('client_table.*,client_level.*');
        $this->db->from('client_table , client_level');
        $this->db->where('client_table.level_id = client_level.level_id');
        $this->db->where('client_table.client_id',$id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getClientDetailsByEmail($email)
    {
        $this->db->select('client_table.*,client_level.*');
        $this->db->from('client_table , client_level');
        $this->db->where('client_table.level_id = client_level.level_id');
        $this->db->where('client_table.email',$email);
        $query = $this->db->get();
        return $query->result();
    }
    public function getPrimaryUser($id)
    {
        $this->db->where('client_id' , $id);
        $query = $this->db->get('client_contact_primary');
        return $query->result();

    }
    public function insertPrimaryUser()
    {
        $user['first_name'] = $this->input->post('first_name');
        $user['last_name'] = $this->input->post('last_name');
        $user['address'] = $this->input->post('address');
        $user['email'] = $this->input->post('email');
        $user['phone_no'] = $this->input->post('phone_no');
        $user['client_id'] = $this->input->post('client_id');

        $this->db->insert('client_contact_primary' , $user);

    }
    public function updateContact()
    {
        $client['domestic_rates'] = $this->input->post('domestic_rate');
        $client['weight_per_price'] = $this->input->post('weight_per_price');
        $client['credit_days'] = $this->input->post('credit_days');
        $client['account_number'] = date('dmYis').$this->input->post('client_id');
        $client['level_id'] = '2';
        $this->db->where('client_id',$this->input->post('client_id'));
        $this->db->update('client_table',$client);

    }
    public function suspend($client_id)
    {
        $client['level_id'] = '4';
        $this->db->where('client_id',$client_id);
        $this->db->update('client_table',$client);
    }
    public function unSuspend($client_id)
    {
        $client['level_id'] = '3';
        $this->db->where('client_id',$client_id);
        $this->db->update('client_table',$client);
    }


    public function updatePropect($client_id,$userId = false)
    {
        if($userId){
            $client['intUserId'] = $userId;
        }
        $client['level_id'] = '3';
        $this->db->where('client_id',$client_id);
        $this->db->update('client_table',$client);
    }
    public function markWhitelist($client_id)
    {
        $client['level_id'] = '3';
        $this->db->where('client_id',$client_id);
        $this->db->update('client_table',$client);
    }
    public function markBlackList($client_id)
    {
        $client['level_id'] = '5';
        $this->db->where('client_id',$client_id);
        $this->db->update('client_table',$client);
    }

    public function deleteClient($id, $userId){

        $this->db->where('intUserId',$userId);
        $this->db->delete('user');

        $this->db->where('client_id',$id);
        $this->db->delete('client_table');

        $this->db->where('client_id',$id);
        $this->db->delete('client_rates');

        $this->db->where('client_id',$id);
        $this->db->delete('client_contact_secondry');

        $this->db->where('client_id',$id);
        $this->db->delete('client_contact_primary');

        return true;
    }
    public function getUsersClientAssignment(){
        $ignore = array('1001','1005','1007');
        $this->db->select('*');
        $this->db->from('user');
        $this->db->join('user_type', 'user.intUserTypeId = user_type.intUserTypeId');
        $this->db->where_not_in('user_type.	varUserTypeCode', $ignore);
        $query		= $this->db->get();
        $Result 	= $query->result();
        return $Result;
    }
}
