<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'/libraries/PHPExcel/IOFactory.php';

class Dashboard extends CI_Controller {

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
        $this->load->model('Zone_model');
        $this->load->model('Country_model');
        $this->load->model('Client_model');
        $this->load->helper('cookie');



        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|xlsx';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);



    }

    public function index()
    {
        $this->load->view('admin/home');
    }
    public function logout(){

        $this->session->sess_destroy();
        session_destroy ();
        redirect(SITE.'backend');

    }
    public function user()
    {
        $data1['UserId'] = ($this->session->userdata['UserId']);
        if($data1['UserId'] != '')
        {
            $data['result1'] = $this->User_model->get_users($data1);
            $this->load->view('page/Dashboard/user', $data);
        }
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
        $data = '';
        if($this->input->post('submit') != null)
        {
            if($this->upload->do_upload('Template'))
            {

            }
            else
            {
                $data['message_error'] = 'Error in File Upload';
            }
            $data = array('upload_data' => $this->upload->data());

            $file = $data['upload_data']['full_path'];
            try
            {
                $inputFileType = PHPExcel_IOFactory::identify($file);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($file);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $flag = 1;
            //  Loop through each row of the worksheet in turn
            for ($row = 1; $row <= $highestRow; $row++){
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE);
                if($rowData[0][1] == '')
                    break;
                if($row == 1)
                {
                    $data['columns'] = $rowData[0];
                    //die(var_dump($data));
                }
                else
                {
                    $message = $this->validateOrder($rowData[0]);
                    if($message == 'done')
                    {
                        $data['rows'][] = $rowData[0];

                    }
                    else
                    {
                        $flag = 0;
                        $data['message'] = $message;//'invalid Order Format or Missing Values';
                        break;
                    }
                }

                if($flag == 1)
                {
                    $data['success'] = 'Validated';
                }

            }


            //print_r($data);



        }
        else if($this->input->post('orderData') != null)
        {
            var_dump($_POST);
            die('HERE');
            $itr = 0;
            //while(isset($this->input->post('name'.$itr)))
            if(isset($_POST['name'])) {
                while ($this->input->post('name' . $itr)) {
                    $row = $this->input->post('name' . $itr);

                    // Rowdata
                    $client_email = $row[0];
                    $address_line1 = $row[1];
                    $city1 = $row[2];
                    $country1 = $row[3];
                    $address1 = $row[4];
                    $postal_code1 = $row[5];
                    $email1 = $row[6];
                    $phone1 = $row[7];
                    $mobile1 = $row[8];
                    $address_line2 = $row[9];
                    $city2 = $row[10];
                    $country2 = $row[11];
                    $address2 = $row[12];
                    $postal_code2 = $row[13];
                    $email2 = $row[14];
                    $phone2 = $row[15];
                    $mobile2 = $row[16];
                    $title = $row[17];
                    $type = $row[18];
                    $weight = $row[19];
                    $height = $row[20];
                    $width = $row[21];
                    $breath = $row[22];
                    $packages = $row[23];
                    $payment_type = $row[24];
                    $contact_name = $row[25];
                    $contact_mobile = $row[26];
                    $date = $row[27];
                    $time = $row[28];

                    $client_data = $this->db->where('email', $client_email)->where('level_id', 3)->get('client_table')->result();
                    $client_id = '';
                    if (isset($client_data[0])) {
                        $client_id = $client_data[0]->client_id;
                    } else {
                        $data['message'] = 'invalid Client email ' . $client_email;
                        break;
                    }


                    $sender_data = $this->db->where('address_line', $address_line1)->where('client_id', $client_id)->get('order_sender')->result();
                    $sender_id = '';
                    if (isset($sender_data[0])) {
                        $sender_id = $sender_data[0]->id;
                    } else {
                        $sender['address_line'] = $address_line1;
                        $sender['reference_no'] = mt_rand;
                        $sender['city'] = $city1;
                        $sender['country_id'] = $this->db->where('country_name', $country1)->get('country_table')->result()[0]->id;
                        $sender['address'] = $address1;
                        $sender['postal_code'] = $postal_code1;
                        $sender['email'] = $email1;
                        $sender['phone_no'] = $phone1;
                        $sender['mobile'] = $mobile1;
                        $sender['client_id'] = $client_id;

                        $this->db->insert('order_sender', $sender);
                        $sender_id = $this->db->insert_id();

                    }


                    $receiver_data = $this->db->where('address_line', $address_line2)->where('client_id', $client_id)->get('order_receiver')->result();
                    $receiver_id = '';
                    if (isset($receiver_data[0])) {
                        $receiver_id = $receiver_data[0]->id;
                    } else {
                        $receiver['address_line'] = $address_line2;
                        $receiver['reference_no'] = mt_rand;
                        $receiver['city'] = $city2;
                        $receiver['country_id'] = $this->db->where('country_name', $country2)->get('country_table')->result()[0]->id;
                        $receiver['address'] = $address2;
                        $receiver['postal_code'] = $postal_code2;
                        $receiver['email'] = $email2;
                        $receiver['phone_no'] = $phone2;
                        $receiver['mobile'] = $mobile2;
                        $receiver['client_id'] = $client_id;

                        $this->db->insert('order_receiver', $receiver);
                        $receiver_id = $this->db->insert_id();

                    }


                    $consignment_data = $this->db->where('title', $title)->where('client_id', $client_id)->get('consignment_details')->result();
                    $consignment_id = '';
                    if (isset($consignment_data[0])) {
                        $consignment_id = $consignment_data[0]->id;
                    } else {
                        $consignment['title'] = $title;
                        $consignment['breath'] = $breath;
                        $consignment['height'] = $height;
                        $consignment['weight'] = $weight;
                        $consignment['type'] = $this->db->where('type', $type)->get('order_types')->result()[0]->id;// $type;
                        $consignment['no_of_packages'] = $packages;
                        $consignment['width'] = $width;
                        $consignment['client_id'] = $client_id;

                        $this->db->insert('consignment_details', $consignment);
                        $consignment_id = $this->db->insert_id();

                    }


                    $itr = $itr + 1;
                }
            }
        }


        $this->load->view('page/Dashboard/bulk-order-origanated' ,$data );
    }
    public function validateOrder($data)
    {
        // 30 columns
        $client_email = $data[0];
        $address_line1 = $data[1];
        $city1 = $data[2];
        $country1 = $data[3];
        $address1 = $data[4];
        $postal_code1 = $data[5];
        $email1 = $data[6];
        $phone1 = $data[7];
        $mobile1 = $data[8];
        $address_line2 = $data[9];
        $city2 = $data[10];
        $country2 = $data[11];
        $address2 = $data[12];
        $postal_code2 = $data[13];
        $email2 = $data[14];
        $phone2 = $data[15];
        $mobile2 = $data[16];
        $title = $data[17];
        $type = $data[18];
        $weight = $data[19];
        $height = $data[20];
        $width = $data[21];
        $breath = $data[22];
        $packages = $data[23];
        $payment_type = $data[24];
        $contact_name = $data[25];
        $contact_mobile = $data[26];
        $date = $data[27];
        $time = $data[28];

        return 'done';

    }
    public function deliveries_in_progress()
    {
        $this->load->view('page/Dashboard/deliveries-in-progress');
    }
    public function amount_due()
    {
        $this->load->view('page/Dashboard/amount-due');
    }
    public function download_template()
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=Template.xlsx');
        header('Pragma: no-cache');
        readfile(base_url()."template/BulkUpload.xlsx");
    }
    public function zone()
    {
        $zone['zones'] = $this->Zone_model->get_zones();
        $this->load->view('page/Dashboard/zone' , $zone);
    }
    public function country()
    {
        $zone['countries'] = $this->Country_model->get_countries();
        $this->load->view('page/Dashboard/country' , $zone);
    }
    public function blackListClients()
    {
        $data['clients'] = $this->Client_model->getClients();
        $this->load->view('page/Dashboard/ViewBlackListed',$data);
    }


}


?>