<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 10/24/2017
 * Time: 5:12 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'/libraries/PHPExcel/IOFactory.php';
include APPPATH.'/libraries/Barcode/barcode.inc.php';
define('FPDF_FONTPATH',APPPATH.'libraries/fpdf/font/');
require_once(APPPATH.'libraries/fpdf/fpdf/fpdf.php');
require_once(APPPATH.'libraries/fpdf/fpdf/fpdi.php');
//require_once 'Order.php';

date_default_timezone_set('Asia/Bahrain');
class AjentOrders extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->database();

        //	$this->load->helper('dynmic-css-js');
        $this->load->model('User_model');
        $this->load->model('Admin_model');
        $this->load->model('Agent_model');
        $this->load->model('Zone_model');
        $this->load->model('Country_model');
        $this->load->model('Client_model');
        $this->load->model('Order_model');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->library('Notification_lib');

        $isLoggedIn = $this->session->userdata('logged_in');
        if(!$isLoggedIn){
            redirect(SITE.'backend');
        }



        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|xlsx';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);



    }

    public function index(){
        $data               = array();
        $data1['UserId']        = ($this->session->userdata['UserId']);
        if($data1['UserId'] != '')
        {
            $userData = $this->User_model->get_users($data1);
            if(isset($userData['users'])){
                $notAllowedIds = array(4);
                foreach ($userData['users'] as $key => $userDataIn) {
                    if(!in_array($userDataIn->intUserTypeId,$notAllowedIds)){
                        unset($userData['users'][$key]);
                    }

                }
                $data['result1'] = $userData;
            }
            $this->load->view('agent/agentList', $data);
        }
    }

    public function createEditAgent($id = false){

        if(isset($_POST['email'])){
            if($id){
                $_POST['id'] = $id;
                $isEmailExist = $this->isEmailExist();
                if($isEmailExist['error'] == 1){
                    $this->session->set_flashdata('error', '<div class="alert alert-success alert-dismissible">Email Already Exist</div>');
                    redirect(SITE.'AjentOrders/createEditAgent/'.$id);
                }
            }
            else{
                $isEmailExist = $this->isEmailExist();
                if($isEmailExist['error'] == 1){
                    $this->session->set_flashdata('error', '<div class="alert alert-success alert-dismissible">Email Already Exist</div>');
                    redirect(SITE.'AjentOrders/createEditAgent');
                }
            }
        }

        $data                               = array();
        if($id){
            $data['id']                     = $id;
            $agentInfo                      = $this->Agent_model->agentInfo($id);
            if(isset($agentInfo['userTable']) && !empty($agentInfo['userTable'])){
                $data['userTable'] = $agentInfo['userTable'];
            }
            if(isset($agentInfo['agentTable']) && !empty($agentInfo['agentTable'])){
                $data['agentTable'] = $agentInfo['agentTable'];
            }
            if(isset($agentInfo['agentRate']) && !empty($agentInfo['agentRate'])){
                $data['agentRate'] = $agentInfo['agentRate'];
            }

            //echo "<pre>"; print_r($agentInfo); exit;

        }


        $data['zones']                      = $this->Zone_model->get_zones();
        $data['users']                      = $this->Client_model->getUsersClientAssignment();

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if($id == false){
            $this->form_validation->set_rules('password', 'Password', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('agent/createAgent', $data);
        }
        else {

            if($id){

                $agentId                                = $id;
                $mData                                  = array();
                $mData['intUserId']                     = $id;
                $mData['varFirstName']                  = $_POST['first_name'];
                $mData['varLastName']                   = $_POST['last_name'];
                $mData['varEmailId']                    = $_POST['email'];
                $mData['varMobileNo']                   = $_POST['mobile'];
                if($_POST['password'] != ''){
                    $mData['varPassword']                   = $this->encrypt->encode($_POST['password']);
                }
                $mData['country_id']                    = $_POST['country'];
                $this->Agent_model->updateUser($mData);

                //
                $mData                                  = array();
                $mData['agent_id']                      = $agentId;
                $mData['domestic_rates']                = $_POST['domestic_rate'];
                $mData['weight_per_price']              = $_POST['weight_per_price'];
                $mData['company_name']                  = $_POST['company_name'];
                $mData['phone_no']                      = $_POST['mobile'];
                $mData['country_id']                    = $_POST['country'];
                $mData['city']                          = $_POST['city'];
                $mData['address']                       = $_POST['address'];
                $mData['assign_to']                     = $_POST['creater_id'];
                $isExist = $this->Agent_model->getAgentTableInfo($id);
                if(empty($isExist)){
                    $this->Agent_model->insertAgentTable($mData);
                }else{
                    $this->Agent_model->updateAgentTable($mData);
                }

                $this->Agent_model->deleteAgentRate($agentId);

                foreach ($_POST['zone'] as $zoneId => $val){
                    $mData                                  = array();
                    $mData['agent_id']                      = $agentId;
                    $mData['zone_id']                       = $zoneId;
                    $mData['zone_rate']                     = $val;
                    $this->Agent_model->insertAgentRate($mData);
                }

            }else{

                $mData                                  = array();
                $mData['varFirstName']                  = $_POST['first_name'];
                $mData['varLastName']                   = $_POST['last_name'];
                $mData['varEmailId']                    = $_POST['email'];
                $mData['varMobileNo']                   = $_POST['mobile'];
                $mData['varPassword']                   = $this->encrypt->encode($_POST['password']);
                $mData['country_id']                    = $_POST['country'];
                $mData['intUserTypeId']                 = '4';
                $mData['dtCreated']                     = date('Y-m-d H:i:s');
                $agentId                                = $this->Agent_model->insertUser($mData);

                $mData                                  = array();
                $mData['agent_id']                      = $agentId;
                $mData['domestic_rates']                = $_POST['domestic_rate'];
                $mData['weight_per_price']              = $_POST['weight_per_price'];
                $mData['company_name']                  = $_POST['company_name'];
                $mData['phone_no']                      = $_POST['mobile'];
                $mData['country_id']                    = $_POST['country'];
                $mData['city']                          = $_POST['city'];
                $mData['address']                       = $_POST['address'];
                $mData['assign_to']                     = $_POST['creater_id'];
                $this->Agent_model->insertAgentTable($mData);

                foreach ($_POST['zone'] as $zoneId => $val){
                    $mData                                  = array();
                    $mData['agent_id']                      = $agentId;
                    $mData['zone_id']                       = $zoneId;
                    $mData['zone_rate']                     = $val;
                    $this->Agent_model->insertAgentRate($mData);
                }
            }
            redirect(SITE.'AjentOrders');
        }


    }

    public function deleteAgent($id){
        $this->Agent_model->deleteAgent($id);
        redirect(CTRL.'AjentOrders');
    }

    public function isEmailExist(){
        $email      = $_POST['email'];
        $response   = array();
        if(isset($_POST['id']) && $_POST['id'] != ''){
            $id         = $_POST['id'];
        }else{
            $id         = false;
        }
        $isEmailExist = $this->User_model->isEmailExist($email, $id);
        if(!empty($isEmailExist)){
            $response['error'] = '1';
            $response['msg'] = 'Email Already Exist. Please Enter another';
        }else{
            $response['error'] = '0';
            $response['msg'] = '';
        }
        return $response;
        //echo json_encode($response); exit;
    }



    public function client()
    {
        $data['clients'] = $this->Client_model->getClients();
        //var_dump($data);
        //die('HERE');
        $this->load->view('page/Dashboard/client',$data);
    }
    public function package()
    {
        $this->load->view('page/Dashboard/package');
    }
    public function create_order_old()
    { echo "This old page in comming";
        $this->load->view('client/create-order-old');#crate Order Functions

    }

    public function create_order()
    {  $this->load->view('client/create-order');#crate Order Functions

    }
    public function new_create_order()
    {  $this->load->view('client/create-order');#crate Order Functions

    }

    public function service()
    {
        $this->load->view('page/Dashboard/service');
    }
    public function order_status()
    {
        $this->load->view('page/Dashboard/order_status');
    }
    public function total_prospects()
    {
        $this->load->view('page/Dashboard/total-prospects');
    }
    public function orders_in_progress()
    {
        $this->load->view('page/Dashboard/orders-in-progress');
    }
    public function orders_pending()
    {
        $this->load->view('page/Dashboard/orders-pending');
    }
    public function pending_payments()
    {
        $this->load->view('page/Dashboard/pending-payments');
    }
    public function order_origanated()
    {
        $this->load->view('page/Dashboard/order-origanated');
    }
    public function Airway_bill()
    {
        $this->load->view('page/Dashboard/airway-bill');
    }

    public function bulk_order_orignated()
    {
        $client_id = $this->session->userdata('UserId');
        if(empty($this->Agent_model->getAgentTableInfo($client_id))){
            echo "Agent is not updated, please contact the admin to update the agent first"; exit;
        }else if(empty($this->Agent_model->getAgentRate($client_id))){
            echo "Agent is not updated, please contact the admin to update the agent first"; exit;
        }

        $data['client_id']      = $client_id;
        if($this->input->post('submit') != null)
        {
            if($this->upload->do_upload('Template')) {
            }
            else {
                $data['message_error'] = 'Error in File Upload';
            }

            $data               = array('upload_data' => $this->upload->data());
            $file               = $data['upload_data']['full_path'];
            try {
                $inputFileType          = PHPExcel_IOFactory::identify($file);
                $objReader              = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel            = $objReader->load($file);
            }
            catch(Exception $e) {
                die('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            //  Get worksheet dimensions
            $sheet                      = $objPHPExcel->getSheet(0);
            $highestRow                 = $sheet->getHighestRow();
            $highestColumn              = $sheet->getHighestColumn();
            $flag                       = 1;

            //  Loop through each row of the worksheet in turn
            for ($row = 1; $row <= $highestRow; $row++){
                //  Read a row of data into an array
                $rowData                = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                    NULL,
                                                    TRUE,
                                                    FALSE);
                if($rowData[0][1] == '')
                    break;

                if($row == 1) {
                    $data['columns']    = $rowData[0];
                }
                else{
                    $message            = $this->validateOrder($rowData[0]);
                    $weight_per_price   = $this->db->where('agent_id' , $client_id)->get('agent_table')->result()[0]->weight_per_price;

                    if($message == 'done') {
                        if($rowData[0][3] != $rowData[0][13]) {
                            $zone_id                = $this->db->where('country_name' ,$rowData[0][13] )->get('country_table')->result()[0]->zone_id;
                            $zone_rate              = $this->db->where('agent_id' , $client_id)->where('zone_id' , $zone_id)->get('agent_rates')->result()[0]->zone_rate;
                            $rows_amount[]          = floatval($rowData[0][21]) * floatval($zone_rate) * 2;
                        }
                        else
                        {
                            $weight                 = '';
                            if($rowData[0][21] > $weight_per_price) {
                                $devide             = intVal($rowData[0][21] / $weight_per_price);
                                $rem                = $rowData[0][21] % $weight_per_price;
                                $devide             = $devide + 1;
                                $weight             = $devide * $weight_per_price;
                            }
                            else {
                                $weight             = $weight_per_price;
                            }


                            $rate                   = $this->db->where('agent_id' ,$client_id)->get('agent_table')->result()[0]->domestic_rates;
                            $rows_amount[]          = floatval($weight) * floatval($rate);
                        }
                        $data['rows'][]             = $rowData[0];

                    }
                    else {
                        $flag                       = 0;
                        $data['message']            = $message;
                        break;
                    }
                }
                if($flag == 1){
                    $data['success']                = 'Validated';
                }

            }
            $data['rows_amount']                    = $rows_amount;

            $data['client_id']                      = $client_id;
        }
        else if($this->input->post('orderData') != null) {

            // insert bulk_batch entry
            $batch['name']                      = date('Y-m-d').'_BATCH';
            $batch['updated_on']                = date('Y-m-d H:i:s');
            $batch['updated_by']                = 'admin';
            $batch['created_on']                = date('Y-m-d H:i:s');
            $batch['created_by']                = 'admin';
            $this->db->insert('bulk_batch',$batch);

            $batch_id                           = $this->db->insert_id();

            $itr                                = 0;

            $pdf                                = new FPDI();
            $pdf->AddPage();
            $pagecount                          = $pdf->setSourceFile(APPPATH.'/libraries/PDFTemplate/manifest_template.pdf');
            $tpl                                = $pdf->importPage(1);
            $pdf->useTemplate($tpl);

            $entry_y_index                      = 58;
            $line_seperater_y                   =  67.5;
            $horizontal_axis_y                  = 76;

            $total_amount                       = 0;

            while ($this->input->post('row_' . $itr) != null)
            {
                $row                            = $this->input->post('row_' . $itr);

                // Rowdata
                $address_line1                      = $row[0];
                $city1                              = $row[1];
                $state1                             = $row[2];
                $country1                           = $row[3];
                $address1                           = $row[4];
                $postal_code1                       = $row[5];
                $email                              = $row[6];
                $name1                              = $row[7];
                $mobile1                            = $row[8];
                $address_line2                      = $row[9];
                $company                            = $row[10];
                $state2                             = $row[11];
                $city2                              = $row[12];
                $country2                           = $row[13];
                $address2                           = $row[14];
                $postal_code2                       = $row[15];
                $email2                             = $row[16];
                $name2                              = $row[17];
                $mobile2                            = $row[18];
                $title                              = $row[19];
                $type                               = $row[20];
                $weight                             = $row[21];
                $height                             = $row[22];
                $width                              = $row[23];
                $breath                             = $row[24];
                $packages                           = $row[25];
                $description                        = $row[26];
                $sender_reference                   = $row[27];
                $receiver_reference                 = $row[28];
                $payment_type                       = $row[29];
                $billing_type                       = $row[30];
                $value                              = $row[31];
                $payment_to_collect                 = $row[32];
                $contact_name                       = $row[33];
                $contact_mobile                     = $row[34];
                $date                               = $row[35];
                $time                               = $row[36];
                $time_to                            = $row[37];
                $remarks                            = $row[38];
                $agentAirwayBil                     = $row[39];
                $agentTrackingId                    = $row[40];


                $sender_data                        = $this->db->where('address_line', $address_line1)->where('client_id', $client_id)->get('order_sender')->result();
                if (isset($sender_data[0])) {
                    $sender_id                      = $sender_data[0]->id;
                } else {
                    $sender['address_line']         = $address_line1;
                    $sender['account_no']           = mt_rand();
                    $sender['city']                 = $city1;
                    $sender['country_id']           = $this->db->where('country_name', $country1)->get('country_table')->result()[0]->id;
                    $sender['address']              = $address1;
                    $sender['postal_code']          = $postal_code1;
                    $sender['email']                = $email;
                    $sender['name']                 = $name1;
                    $sender['state']                = $state1;
                    $sender['mobile']               = $mobile1;
                    $sender['client_id']            = $client_id;
                    $this->db->insert('order_sender', $sender);
                    $sender_id                      = $this->db->insert_id();

                }


                $receiver_data                      = $this->db->where('address_line', $address_line2)->where('client_id', $client_id)->get('order_receiver')->result();
                if (isset($receiver_data[0])) {
                    $receiver_id                    = $receiver_data[0]->id;
                } else {
                    $receiver['address_line']       = $address_line2;
                    $receiver['account_no']         = mt_rand();
                    $receiver['city']               = $city2;
                    $receiver['country_id']         = $this->db->where('country_name', $country2)->get('country_table')->result()[0]->id;
                    $receiver['address']            = $address2;
                    $receiver['postal_code']        = $postal_code2;
                    $receiver['email']              = $email2;
                    $receiver['name']               = $name2;
                    $receiver['company_name']       = $company;
                    $receiver['state']              = $state2;
                    $receiver['mobile']             = $mobile2;
                    $receiver['client_id']          = $client_id;
                    $this->db->insert('order_receiver', $receiver);
                    $receiver_id                    = $this->db->insert_id();
                }


                $consignment_data                   = $this->db->where('title', $title)->where('client_id', $client_id)->get('consignment_details')->result();
                if (isset($consignment_data[0])) {
                    $consignment_id                 = $consignment_data[0]->id;
                } else {
                    $consignment['title']           = $title;
                    $consignment['breath']          = $breath;
                    $consignment['height']          = $height;
                    $consignment['weight']          = $weight;
                    $consignment['type']            = $this->db->where('type', $type)->get('order_types')->result()[0]->id;// $type;
                    $consignment['no_of_packages']  = $packages;
                    $consignment['width']           = $width;
                    $consignment['description']     = $description;
                    $consignment['client_id']       = $client_id;
                    $this->db->insert('consignment_details', $consignment);
                    $consignment_id                 = $this->db->insert_id();
                }

                $contact_person                     = $this->db->where('person_name' , $contact_name)->get('order_contact_person')->result();
                if(isset($contact_person[0])) {
                    $contact_person_id                  = $contact_person[0]->person_id;
                }
                else {
                    $contact['person_name']             = $contact_name;
                    $contact['person_mobile']           = $contact_mobile;
                    $contact['client_id']               = $client_id;

                    if($contact['person_name'] != '' && $contact['person_mobile'] != '' ){
                        $this->db->insert('order_contact_person', $contact);
                        $contact_person_id              = $this->db->insert_id();
                    }
                    else {
                        $contact_person_id              = 0;
                    }
                }

                $order['batch_id']                      = $batch_id;
                $order['client_id']                     = $client_id;
                $order['receiver_id']                   = $receiver_id;
                $order['sender_id']                     = $sender_id;
                $order['consignment_id']                = $consignment_id;
                $order['payment_type_id']               = $this->db->where('payment_type' , $payment_type)->get('payment_types')->result()[0]->id;
                $order['billing_type']                  = $this->db->where('billing_type' , $billing_type)->get('billing_types')->result()[0]->id;
                $order['contact_person_id']             = $contact_person_id;
                $order['created_by']                    ='admin';
                $order['updated_by']                    ='admin';
                $order['order_pickup_date']             = $date;
                $order['order_pickup_time']             = $time;
                $order['order_pickup_time_to']          = $time_to;
                $order['remarks']                       = $remarks;
                $order['value']                         = $value;
                $order['payment_to_collect']            = $payment_to_collect;
                $order['sender_reference']              = $sender_reference;
                $order['receiver_reference']            = $receiver_reference;
                $order['tax_payer_id']                  = '1';// need to be define//$this->db->where('tax_payer' , $tax_payer)->get('tax_payers')->result()[0]->id;
                $insured                                = 'Yes';

                if($insured == 'Yes') {
                    $order['is_insurred'] = '1';
                }
                else {
                    $order['is_insurred'] = '0';
                }

                if($country1 == $country2){
                    $order['service_id'] = 1;
                }
                else{
                    $order['service_id'] = 2;
                }

                if($this->session->userdata['UserType'] == 'Administrator' || $this->session->userdata['UserType'] == 'Admin' || $this->session->userdata['UserType'] == 'Manager' || $this->session->userdata['UserType'] == 'Team Member'){
                    $order['issuer_code'] = 'S';
                }
                else if($this->session->userdata['UserType'] == 'Agent'){
                    $order['issuer_code'] = 'A';
                }
                else if($this->session->userdata['UserType'] == 'Client'){
                    $order['issuer_code'] = 'C';
                }
                else if($this->session->userdata['UserType'] == 'Courier'){
                    $order['issuer_code'] = 'C';
                }
                else {
                    $order['issuer_code'] = 'S';
                }
                $max_serial                 = $this->db->select_max('serial_number')->get('order_details');

                if($max_serial->num_rows()>0){
                    $max_serial                     = $max_serial->result();
                    $order['serial_number']         = $max_serial[0]->serial_number;

                    if($max_serial[0]->serial_number == 0){
                        $this->db->select('site_config.*');
                        $this->db->where('key_name' , 'serial_number');
                        $max_serial                 = $this->db->get('site_config')->result();
                        $order['serial_number']     = $max_serial[0]->key_value;
                    }
                }

                $order['serial_number']             = $order['serial_number'] + 1;

                $order['created_on']                = date('Y-m-d H:i:s');
                $seed                               = 'JvKnrQWPsThuJteNQAuH';
                $hash                               = sha1(uniqid($seed . mt_rand(), true));
                # To get a shorter version of the hash, just use substr
                $hash                               = substr($hash, 0, 8);
                $order['order_tracking_id']         = $hash;
                $order['order_status']              = '1';


                $order['user_id']                   = $this->session->userdata['UserId'];
                $hash                               = sha1(uniqid($seed . mt_rand(), true));
                $hash                               = substr($hash, 0, 8);
                $order['pickup_reference']          = $hash;
                $order['agentTID']                  = $agentAirwayBil;
                $order['agentBID']                  = $agentTrackingId;
                $order['agentOAD']                  = 'test';
                $order['order_type']                = 'agent';

                $this->db->insert('order_details',$order);
                $order_id                           = $this->db->insert_id();

                $airway_bill = $order['issuer_code'] . $this->db->where('country_name', $country1)->get('country_table')->result()[0]->country_code . $this->db->where('country_name', $country2)->get('country_table')->result()[0]->country_code .$order['serial_number'].$order['service_id'].$order_id ;



                $airway['order_id']                     = $order_id;
                $airway['client_id']                    = $client_id;
                $airway['airway_bill']                  = $airway_bill;
                $this->db->insert('order_airway_bill',$airway);
                $order_payment['order_id']              = $order_id;

                $weight_per_price                       = $this->db->where('agent_id' , $client_id)->get('agent_table')->result()[0]->weight_per_price;

                if($country1 != $country2){
                    $zone_id                            = $this->db->where('country_name' ,$country2 )->get('country_table')->result()[0]->zone_id;
                    $zone_rate                          = $this->db->where('agent_id' , $client_id)->where('zone_id' , $zone_id)->get('agent_rates')->result()[0]->zone_rate;
                    $order_payment['payable_amount']    = floatval($weight) * floatval($zone_rate) * 2;
                }
                else{
                    if($weight > $weight_per_price){
                        $devide                         = intVal ( $weight / $weight_per_price );
                        $rem                            = $weight % $weight_per_price;
                        $devide                         = $devide + 1;
                        $weight                         = $devide * $weight_per_price;
                    }
                    else{
                        $weight                         = $weight_per_price;
                    }
                    $rate                               = $this->db->where('agent_id' ,$client_id)->get('agent_table')->result()[0]->domestic_rates;
                    $order_payment['payable_amount']    = floatval($weight) * floatval($rate);
                }

                $order_payment['chargeable_weight']     = $weight;
                $order_payment['order_status']          = 1;
                $this->db->insert('order_payments' , $order_payment);

                $code_number                            = $airway_bill;
                new barCodeGenrator($code_number,0,APPPATH.'../uploads/'.$airway_bill.'.gif', 250, 100, true);

                $this->generateAirwaybill($order['serial_number']);

                // Manifest generation start
                if($itr == 0){

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(28,58.5,28,67.5);
                    // two 1

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(57.4,58.5,57.4,67.5);

                    // three

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(83.9,58.5,83.9,67.5);

                    // five

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(136.6,58.5,136.6,67.5);

                    // Set Header

                    $pdf->SetFont('Arial','',8);

                    $pdf->SetY(5);
                    $pdf->SetX(1);

                    $client_details             = $this->db->where('agent_id', $client_id)->get('agent_table')->result();
                    $client_name                = $client_details[0]->company_name;
                    $client_mobile              = $client_details[0]->phone_no;
                    $client_country_code        = $this->db->where('id',$client_details[0]->country_id)->get('country_table')->result()[0]->country_code;
                    $client_city                = $client_details[0]->city;
                    $client_address             = $client_details[0]->address;
                    $client_country_name        = $this->db->where('id',$client_details[0]->country_id)->get('country_table')->result()[0]->country_name;
                    $address                    = $client_address;
                    $address2                   = $client_country_name;
                    $city                       = $client_city;
                    $mobile                     = $client_mobile;

                    $pdf->SetY(24.5);
                    $pdf->SetX(2);
                    $pdf->Cell(40, 10, $address, 0, 0, 'L');

                    $pdf->SetY(28);
                    $pdf->SetX(2);
                    $pdf->Cell(40, 10, $address2, 0, 0, 'L');

                    $pdf->SetY(31.5);
                    $pdf->SetX(2);
                    $pdf->Cell(40, 10, $city, 0, 0, 'L');

                    $pdf->SetY(35);
                    $pdf->SetX(2);
                    $pdf->Cell(40, 10, $mobile, 0, 0, 'L');

                    $pdf->SetY(29);
                    $pdf->SetX(128);
                    $pdf->MultiCell(35,4,$client_name,0,'L');

                    $pdf->SetY(29);
                    $pdf->SetX(159.5);
                    $pdf->Cell(40, 10, $client_mobile, 0, 0, 'L');

                    $pdf->SetY(25);
                    $pdf->SetX(181);
                    $pdf->Cell(40, 10, $client_country_code, 0, 0, 'L');

                    $pdf->SetY(29);
                    $pdf->SetX(180);
                    $pdf->Cell(40, 10, $client_city, 0, 0, 'L');


                    // Set First Row
                    $pdf->SetFont('Arial','',8);

                    $pdf->SetY($entry_y_index);

                    $so_id                      = $order_id;
                    $tracking_number            = $order['order_tracking_id'];
                    $order_ref                  = $order['serial_number'];
                    $description                = $description;
                    $consingee_name             = substr($title, 0 , 22) ;
                    $total                      = $order_payment['payable_amount'];
                    $consingee_name             = substr($consingee_name, 0 , 22);
                    $description                = explode(' ' , $description)[0];



                    $pdf->SetFont('Arial','',6.5);

                    $pdf->SetY(58);
                    $pdf->SetX(6);
                    $pdf->Cell(15, 10, $airway['airway_bill'], 0, 0, 'L');


                    $pdf->SetY(58);
                    $pdf->SetX(30);
                    $pdf->Cell(15, 10, $name1, 0, 0, 'L');


                    $pdf->SetY(58);
                    $pdf->SetX(60);
                    $pdf->Cell(15, 10, $name2, 0, 0, 'L');


                    $pdf->SetY(58);
                    $pdf->SetX(88);
                    $pdf->Cell(15, 10, $description, 0, 0, 'L');



                    $pdf->SetY(58);
                    $pdf->SetX(115);
                    $pdf->Cell(15, 10, $sender_reference, 0, 0, 'L');


                    $pdf->SetY(58);
                    $pdf->SetX(140);
                    $pdf->Cell(15, 10, $total, 0, 0, 'L');

                    $pdf->SetY(58);
                    $pdf->SetX(162);
                    $pdf->Cell(15, 10, $payment_type , 0, 0, 'L');


                    $pdf->SetY(58);
                    $pdf->SetX(185);
                    $pdf->Cell(15, 10, $payment_to_collect, 0, 0, 'L');


                }
                else {

                    // one
                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(4,$line_seperater_y,4,$line_seperater_y + 8.5);

                    // two

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(28,$line_seperater_y,28,$line_seperater_y + 8.5);
                    // two 1

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(57.4,$line_seperater_y,57.4,$line_seperater_y + 8.5);

                    // three

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(83.9,$line_seperater_y,83.9,$line_seperater_y + 8.5);

                    // foure

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(110.1,$line_seperater_y,110.1,$line_seperater_y + 8.5);


                    // five

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(136.6,$line_seperater_y,136.6,$line_seperater_y + 8.5);


                    // five-1

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(162.5,$line_seperater_y,162.5,$line_seperater_y + 8.5);

                    // six

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(182.3,$line_seperater_y,182.3,$line_seperater_y + 8.5);


                    // seven

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(53,54,55);

                    $pdf->Line(206,$line_seperater_y,206,$line_seperater_y + 8.5);


                    // draw Border



                    // Horizontal line

                    $pdf->SetLineWidth(0.1);
                    $pdf->SetDrawColor(179,179,179);

                    $pdf->Line(4,$horizontal_axis_y,206,$horizontal_axis_y);

                    $horizontal_axis_y = $horizontal_axis_y + 9;
                    $line_seperater_y = $line_seperater_y + 8.5;


                    $pdf->SetFont('Arial','',6.5);

                    $entry_y_index = $entry_y_index + 7;
                    // Set other Rows



                    $so_id                          = $order_id;
                    $tracking_number                = $order['order_tracking_id'];
                    $order_ref                      = $order['serial_number'];
                    $description                    = $description;
                    $consingee_name                 = $title;
                    $total                          = $order_payment['payable_amount'];
                    $consingee_name                 = substr($consingee_name, 0 , 22);
                    $description                    = explode(' ' , $description)[0];

                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(6);
                    $pdf->Cell(15, 10, $airway['airway_bill'], 0, 0, 'L');


                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(30);
                    $pdf->Cell(15, 10, $name1, 0, 0, 'L');


                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(60);
                    $pdf->Cell(15, 10, $name2, 0, 0, 'L');


                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(88);
                    $pdf->Cell(15, 10, $description, 0, 0, 'L');



                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(115);
                    $pdf->Cell(15, 10, $sender_reference, 0, 0, 'L');


                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(140);
                    $pdf->Cell(15, 10, $total, 0, 0, 'L');

                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(162);
                    $pdf->Cell(15, 10, $payment_type , 0, 0, 'L');


                    $pdf->SetY($entry_y_index);
                    $pdf->SetX(185);
                    $pdf->Cell(15, 10, $payment_to_collect, 0, 0, 'L');
                }

                $total_amount = $total_amount + $order_payment['payable_amount'];




                // end
                $this->SendNotification($order_id, 'NOOB');
                if($date != '' && $time != ''){
                    $this->SendNotification($order_id, 'CPP');
                }
                $itr = $itr + 1;
            }

            // Setting Footer
            // Set Footer

            $pdf->SetFont('Arial','B',8);

            $total_orders = $itr;
            $total_amount = $total_amount;

            $pdf->SetY(190);
            $pdf->SetX(185);
            $pdf->Cell(40, 10, $total_orders, 0, 0, 'L');


            $pdf->SetY(203);
            $pdf->SetX(185);
            $pdf->Cell(40, 10, $total_amount, 0, 0, 'L');

            $pdfPath = APPPATH.'../uploads/'.$batch_id.'_manifest.pdf';
            $pdf->Output($pdfPath, "F");


            redirect(base_url().'Order/ListOrders');
        }

        $this->load->view('agent/orderOriginate' ,$data );
    }
    public function generateAirwaybill($serial_number)
    {

        $order_details                      = $this->db->where('serial_number',$serial_number)->get('order_details')->result();
        $sender_details                     = $this->db->where('id',$order_details[0]->sender_id)->get('order_sender')->result();
        $receiver_details                   = $this->db->where('id',$order_details[0]->receiver_id)->get('order_receiver')->result();
        $contact_person_details             = $this->db->where('person_id',$order_details[0]->contact_person_id)->get('order_contact_person')->result();
        $consignment_details                = $this->db->where('id',$order_details[0]->consignment_id)->get('consignment_details')->result();
        $airway_bill_details                = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_airway_bill')->result();

        $client_id                          = $order_details[0]->client_id;
        $client_details                     = $this->db->where('agent_id' , $client_id)->get('agent_table')->result();

        $pdf                                = new FPDI();
        $sender_account_number              = $sender_details[0]->account_no;
        $sender_ref                         = $order_details[0]->sender_reference;
        $sender_name                        = $sender_details[0]->name;
        $sender_phone_no                    = $sender_details[0]->mobile;
        $sender_company                     = $client_details[0]->company_name ;
        $sender_address                     = $sender_details[0]->address ;
        $sender_city                        = $sender_details[0]->city;
        $sender_country                     = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result()[0]->country_name;
        $sender_province                    = $sender_details[0]->state;
        $sender_postal_code                 = $sender_details[0]->postal_code;

        $receiver_account_number            = $receiver_details[0]->account_no;
        $receiver_ref                       = $order_details[0]->receiver_reference;
        $receiver_name                      = $receiver_details[0]->name;
        $receiver_phone_no                  = $receiver_details[0]->mobile ;
        $receiver_company                   = $receiver_details[0]->company_name ;
        $receiver_address                   = $receiver_details[0]->address;
        $receiver_city                      = $receiver_details[0]->city;
        $receiver_country                   = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result()[0]->country_name;
        $receiver_province                  = $receiver_details[0]->state ;
        $receiver_postal_code               = $receiver_details[0]->postal_code;

        $send_creation_date                 = explode(' ',$order_details[0]->created_on)[0];
        $origin                             = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result()[0]->country_code ;
        $destination                        = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result()[0]->country_code;
        $tracking_number                    = $order_details[0]->order_tracking_id;
        $no_of_packages                     = $consignment_details[0]->no_of_packages;
        $waight                             = $consignment_details[0]->weight;
        $chargeAbleWeight                   = $this->db->where('order_id' ,$order_details[0]->order_id )->get('order_payments')->result()[0]->chargeable_weight;
        $size                               = floatval($consignment_details[0]->height) * floatval($consignment_details[0]->width) * floatval($consignment_details[0]->breath);
        $value                              = $order_details[0]->value;
        $receive_date                       = explode(' ',$order_details[0]->updated_on)[0];

        $pdf->AddPage();
        //Set the source PDF file
        $pagecount                          = $pdf->setSourceFile(APPPATH.'/libraries/PDFTemplate/doc1.pdf');
        //Import the first page of the file
        $tpl                                = $pdf->importPage(1);

        //Use this page as template
        $pdf->useTemplate($tpl);
        #Print Hello World at the bottom of the page
        //Go to 1.5 cm from bottom
        $pdf->SetY(36);
        $pdf->SetX(1);
        //Select Arial italic 8
        $pdf->SetFont('Arial','B',8);
        //Print centered cell with a text in it
        $pdf->Cell(30, 10, $sender_account_number, 0, 0, 'C');
        $pdf->Cell(90, 10, $sender_ref, 0, 0, 'C');

        $pdf->SetY(46);
        $pdf->Cell(25, 10, $sender_name, 0, 0, 'L');
        $pdf->Cell(90, 10, $sender_phone_no, 0, 0, 'C');

        $pdf->SetY(55);
        $pdf->Cell(15, 10, $sender_company, 0, 0, 'L');

        $pdf->SetY(68);
        $pdf->MultiCell(100, 4, $sender_address, 0, 'L' , false);

        $pdf->SetY(79);
        $pdf->Cell(23, 10, $sender_city, 0, 0, 'C');
        $pdf->Cell(119, 10, $sender_province, 0, 0, 'C');

        $pdf->SetY(88);
        $pdf->Cell(23, 10, $sender_country, 0, 0, 'C');
        $pdf->Cell(119, 10, $sender_postal_code, 0, 0, 'C');

        $pdf->SetY(104);
        //  $pdf->Cell(10, 10, $receiver_account_number, 0, 0, 'C');
        $pdf->Cell(105, 10, $receiver_ref, 0, 0, 'C');

        $pdf->SetY(114);
        $pdf->Cell(25, 10, $receiver_name, 0, 0, 'L');
        $pdf->Cell(70, 10, $receiver_phone_no, 0, 0, 'C');

        $pdf->SetY(122);
        $pdf->Cell(15, 10, $receiver_company, 0, 0, 'L');

        $pdf->SetY(135);
        //$pdf->Cell(11, 4, $receiver_address, 0, 0, 'L');
        $pdf->MultiCell(100, 4, $receiver_address, 0, 'L' , false);

        $pdf->SetY(144);
        $pdf->Cell(23, 10, $receiver_city, 0, 0, 'C');
        $pdf->Cell(119, 10, $receiver_province, 0, 0, 'C');

        $pdf->SetY(156);
        $pdf->Cell(23, 10, $receiver_country, 0, 0, 'C');
        $pdf->Cell(119, 10, $receiver_postal_code, 0, 0, 'C');

        $pdf->SetY(172);
        $pdf->Cell(150, 10, $send_creation_date, 0, 0, 'C');

        $pdf->SetY(31);
        $pdf->Cell(248, 10, $origin, 0, 0, 'C');


        $pdf->SetY(31);
        $pdf->Cell(344, 10, $destination, 0, 0, 'C');

        $pdf->SetY(39);
        $pdf->Cell(280, 10, $tracking_number, 0, 0, 'C');

        $pdf->SetY(64);
        $pdf->Cell(220, 10, $no_of_packages, 0, 0, 'C');

        $pdf->SetY(64);
        $pdf->Cell(300, 10, $waight." KG", 0, 0, 'C');

        $pdf->SetY(64);
        $pdf->Cell(350, 10, $chargeAbleWeight." KG", 0, 0, 'C');


        $pdf->SetY(94);
        $pdf->Cell(330, 10, $value." BHD", 0, 0, 'C');

        $pdf->SetY(173);

        $airway_bill                               = $airway_bill_details[0]->airway_bill;
        $pdf->Image(APPPATH.'../uploads/'.$airway_bill.'.gif',150,1,'GIF' );

        //$pdf->SetY(10);
        if($receiver_country === $sender_country)
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',163,48,3,5,'PNG');
        }
        else
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',136,48,3,5,'PNG');
        }


        if($consignment_details[0]->type == 1)
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',132,72,3,5,'PNG');
        }
        else
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',155,72,3,5,'PNG');
            $pdf->SetY(81);
            $pdf->Cell(265, 10, $consignment_details[0]->description, 0, 0, 'C');
        }

        if($order_details[0]->payment_type_id == 1)
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,112,3,5,'PNG');
        else if($order_details[0]->payment_type_id == 2)
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,121,3,5,'PNG');
        else if($order_details[0]->payment_type_id == 3)
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,131,3,5,'PNG');
        else if($order_details[0]->payment_type_id == 4)
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,141,3,5,'PNG');
        else
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,112,3,5,'PNG');

        // $pdf->SetX(150);
        $pdf->SetXY(110 , 95);
        $pdf->MultiCell(50, 4, substr($order_details[0]->remarks, 0 , 50) , 0, 'L' , false);


        //die('HERE');
        if($order_details[0]->tax_payer_id == 1)
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',150,112,3,5,'PNG');
        }
        else if($order_details[0]->tax_payer_id == 2)
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',150,121,3,5,'PNG');
        }
        else if($order_details[0]->tax_payer_id == 3)
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',150,130,3,5,'PNG');
        }
        else if($order_details[0]->tax_payer_id == 4)
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',150,139,3,5,'PNG');
        }

        if($order_details[0]->is_insurred == '1')
        {
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,156,3,5,'PNG');
        }


        $pdfPath = APPPATH.'../uploads/'.$airway_bill.'.pdf';
        $pdf->Output($pdfPath, "F");

    }
    public function validateOrder($row)
    {
        // 30 columns
        $address_line1 = $row[0];
        $city1 = $row[1];
        $state1 = $row[2];
        $country1 = $row[3];
        $address1 = $row[4];
        $postal_code1 = $row[5];
        $email = $row[6];
        $name1 = $row[7];
        $mobile1 = $row[8];
        $address_line2 = $row[9];
        $company = $row[10];
        $state2 = $row[11];
        $city2 = $row[12];
        $country2 = $row[13];
        $address2 = $row[14];
        $postal_code2 = $row[15];
        $email2 = $row[16];
        $name2 = $row[17];
        $mobile2 = $row[18];
        $title = $row[19];
        $type = $row[20];
        $weight = $row[21];
        $height = $row[22];
        $width = $row[23];
        $breath = $row[24];
        $packages = $row[25];
        $sender_reference = $row[26];
        $receiver_reference = $row[27];
        $payment_type = $row[28];
        $contact_name = $row[29];
        $contact_mobile = $row[30];
        $date = $row[31];
        $time = $row[32];
        $remarks = $row[33];
        $insured = $row[34];
        $tax_payer = $row[35];

        return 'done';

    }
    public function download_template()
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=Template.xlsx');
        header('Pragma: no-cache');
        readfile(base_url()."template/AgentBulkUpload.xlsx");
    }


    public function SendNotification($orderId, $code){


        $order_details                                  = $this->db->where('order_id',$orderId)->get('order_details')->result();
        $orderType                                      = 'client';
        if(!empty($order_details) && isset($order_details[0]->order_type)) {
            $orderType                                  = $order_details[0]->order_type;
        }

        $orderUserData                                  = $this->User_model->getOrderNotificationUsers($orderId, $orderType);
        $shortCodeArray                                 = array();

        $shortCodeArray['order_tracking_id']            = $orderUserData[0]->orderTrackingId;

        $shortCodeArray['receiver_name']                = $orderUserData[0]->receiverName;
        $shortCodeArray['receiver_email']               = $orderUserData[0]->receiverEmail;
        $shortCodeArray['receiver_mobile']              = $orderUserData[0]->receiverMobile;

        $shortCodeArray['sender_name']                = $orderUserData[0]->senderName;
        $shortCodeArray['sender_email']               = $orderUserData[0]->senderEmail;
        $shortCodeArray['sender_mobile']              = $orderUserData[0]->senderMobile;

        if($orderType == 'client'){
            $clientDetail                               = $this->Client_model->getPrimaryUser($orderUserData[0]->clientId);
            $shortCodeArray['client_first_name']        = $clientDetail[0]->first_name;
            $shortCodeArray['client_last_name']         = $clientDetail[0]->last_name;
            $shortCodeArray['client_name']              = $clientDetail[0]->first_name . ' ' . $clientDetail[0]->last_name;;
            $shortCodeArray['client_email']             = $orderUserData[0]->clientEmail;
            $shortCodeArray['client_mobile']            = $orderUserData[0]->clientMobile;

            $shortCodeArray['client_creator_name']      = $orderUserData[0]->clientCreatorName;
            $shortCodeArray['client_creator_email']     = $orderUserData[0]->clientCreatorEmail;
            $shortCodeArray['client_creator_mobile']    = $orderUserData[0]->clientCreatorMobile;
        }
        $shortCodeArray['order_creator_name']                = $orderUserData[0]->orderCreatorName;
        $shortCodeArray['order_creator_email']               = $orderUserData[0]->orderCreatorEmail;
        $shortCodeArray['order_creator_mobile']              = $orderUserData[0]->orderCreatorMobile;


        $notificationArray                                              = array();


        $userType                                                       = 'Receiver';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->receiverEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->receiverMobile);
        $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

        $userType                                                       = 'Sender';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->senderEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->senderMobile);
        $notificationArray[$userType]['shortCode']                      = $shortCodeArray;
        if($orderType == 'client'){
            $userType                                                       = 'Client';
            $notificationArray[$userType]                                   = array();
            $notificationArray[$userType]['email']                          = array($orderUserData[0]->clientEmail);
            $notificationArray[$userType]['number']                         = array($orderUserData[0]->clientMobile);
            $notificationArray[$userType]['shortCode']                      = $shortCodeArray;

            $userType                                                       = 'Client Creator';
            $notificationArray[$userType]                                   = array();
            $notificationArray[$userType]['email']                          = array($orderUserData[0]->clientCreatorEmail);
            $notificationArray[$userType]['number']                         = array($orderUserData[0]->clientCreatorMobile);
            $notificationArray[$userType]['shortCode']                      = $shortCodeArray;
        }

        $userType                                                       = 'Order Creator';
        $notificationArray[$userType]                                   = array();
        $notificationArray[$userType]['email']                          = array($orderUserData[0]->orderCreatorEmail);
        $notificationArray[$userType]['number']                         = array($orderUserData[0]->orderCreatorMobile);
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

        $this->notification_lib->orderStatusUpdateNotification($notificationArray, $code);
    }


}


?>