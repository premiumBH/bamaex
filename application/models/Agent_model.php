<?php
/**
* Created by PhpStorm.
* User: QasimRafique
* Date: 10/28/2017
* Time: 2:00 PM
*/

class Agent_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    public function getAgent($id){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('intUserId', $id);
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }

    public function getAgentTableInfo($id){
        $this->db->select('*');
        $this->db->from('agent_table');
        $this->db->where('agent_id', $id);
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }

    public function getAgentRate($id){
        $this->db->select('*');
        $this->db->from('agent_rates');
        $this->db->where('agent_id', $id);
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }

    public function agentInfo($id){

        $response                       = array();
        $response['userTable']          = array();
        $response['agentTable']         = array();
        $response['agentRate']          = array();
        $userTable                      = $this->getAgent($id);
        $agentTable                     = $this->getAgentTableInfo($id);
        $agentRate                      = $this->getAgentRate($id);
        if(!empty($userTable)){
            $response['userTable']              = $userTable[0];
        }
        if(!empty($agentTable)){
            $response['agentTable']             = $agentTable[0];
        }
        if(!empty($agentRate)){
            $response['agentRate']              = $agentRate;
        }
        return $response;
    }

    public function insertAgentTable($data){
        $this->db->insert('agent_table', $data);
    }
    public function insertAgentRate($data){
        $this->db->insert('agent_rates', $data);
    }
    public function insertUser($data){
        $this->db->insert('user', $data);
        $agentId            = $this->db->insert_id();
        $owid               = $this->session->userdata['UserId'];
        $sqlQuery           = "INSERT INTO `user_owner`(`intUserId`, `intOwnerUserId`) VALUES($agentId, $owid)";
        $this->db->query($sqlQuery);

        return $agentId;
    }

    public function updateAgentTable($data){
        $this->db->where('agent_id', $data['agent_id']);
        $this->db->update('agent_table',$data);
    }
    public function updateAgentRate($data){
        $this->db->insert('agent_rates', $data);
        $this->db->where('agent_id', $data['agent_id']);
        $this->db->where('zone_id', $data['zone_id']);
        $this->db->update('agent_rates',$data);
    }
    public function updateUser($data){
        $this->db->where('intUserId', $data['intUserId']);
        $this->db->update('user',$data);
    }

    public function deleteAgent($id){
        $this->db->where('agent_id',$id);
        $this->db->delete('agent_table');

        $this->deleteAgentRate($id);

        $this->db->where('intUserId',$id);
        $this->db->delete('user');
    }

    public function deleteAgentRate($id){
        $this->db->where('agent_id',$id);
        $this->db->delete('agent_rates');
    }

}