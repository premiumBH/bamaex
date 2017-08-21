<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'/libraries/PHPExcel/IOFactory.php';
include APPPATH.'/libraries/Barcode/barcode.inc.php';
define('FPDF_FONTPATH',APPPATH.'libraries/fpdf/font/');
require_once(APPPATH.'libraries/fpdf/fpdf/fpdf.php'); 
require_once(APPPATH.'libraries/fpdf/fpdf/fpdi.php'); 
//require_once 'Order.php';
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

        $this->load->database();

        //	$this->load->helper('dynmic-css-js');
        $this->load->model('User_model');
        $this->load->model('Admin_model');
        $this->load->model('Zone_model');
        $this->load->model('Country_model');
        $this->load->model('Client_model');
        $this->load->helper('cookie');
        $this->load->library('session');
        
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
	 
	public function index()
	{
        $this->load->view('admin/home');
	}
    public function logout(){

	    $redirect = SITE.'backend';
	    if($this->session->userdata('UserType') == 'Client'){
            $redirect = SITE.'client/login';
        }
        else if($this->session->userdata('UserType') == 'Agent'){
            $redirect = SITE.'agent/login';
        }
        else{
            $redirect = SITE.'admin/login';
        }

        $this->session->sess_destroy();
        session_destroy ();
        redirect($redirect);

    }
    public function user()
    {
        $data1['UserId'] = ($this->session->userdata['UserId']);
        if($data1['UserId'] != '')
        {
            $userData = $this->User_model->get_users($data1);
            if(isset($userData['users'])){
                $notAllowedIds = array(4,5);
                foreach ($userData['users'] as $key => $userDataIn) {
                    if(in_array($userDataIn->intUserTypeId,$notAllowedIds)){
                        unset($userData['users'][$key]);
                    }

                }
                $data['result1'] = $userData;
            }
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
        public function selectClient()
        {
//            die($this->session->userdata['UserType']);
            if($this->session->userdata['UserType'] == 'Client')
            {
                $client_id = $this->Client_model->getClientDetailsByEmail($this->session->userdata['email']);
                redirect(base_url().'dashboard/bulk_order_orignated?client_id='.$client_id[0]->client_id);
            }
            else
            {
                $data['clients'] = $this->Client_model->getActiveClients();
                $this->load->view('page/Dashboard/selectClient' , $data);
            }
            
            
        }
	public function bulk_order_orignated()
	{
       
           
           // var_dump($_POST);
            $client_id = '';
            if($this->input->post('client_id') != null)
            {
                $client_id = $this->input->post('client_id');
            }
            else
            {
                $client_id = $this->input->get('client_id');
            }
           
            $data['client_id'] = $client_id;
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
                           //$rowData[0][] = "Amount";
                            
                            $data['columns'] = $rowData[0];
                            //die(var_dump($data));
                        }
                        else
                        {
                            $message = $this->validateOrder($rowData[0]);
                            if($message == 'done')
                            {
                                //$data['rows_amount'][] = $rows_amount;
                                if($rowData[0][3] != $rowData[0][13])
                                {
                                    $zone_id = $this->db->where('id' ,$rowData[0][13] )->get('country_table')->result()[0]->zone_id;
                                    $zone_rate = $this->db->where('client_id' , $client_id)->where('zone_id' , $zone_id)->get('client_rates')->result()[0]->zone_rate; 
                                    $rows_amount[] = floatval($rowData[0][21]) * floatval($zone_rate);
                                }
                                else
                                {
                                    $rate = $this->db->where('client_id' ,$client_id)->get('client_table')->result()[0]->domestic_rates;
                                    $rows_amount[] = floatval($rowData[0][21]) * floatval($rate);               
                                }
                                //$rows_amount[] = "Amount";
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
                    $data['rows_amount'] = $rows_amount;
                    if($this->input->post('client_id') != null)
                    {
                        $client_id = $this->input->post('client_id');
                    }
                    else
                    {
                        $client_id = $this->input->get('client_id');
                    }

                    $data['client_id'] = $client_id;


                    //print_r($data);



            }
            else if($this->input->post('orderData') != null)
            {
                //var_dump($_POST);
                //die('HERE');
                $itr = 0;
                //while(isset($this->input->post('name'.$itr)))
                //if(isset($_POST['name'])) {
                    while ($this->input->post('row_' . $itr) != null) {
                        $row = $this->input->post('row_' . $itr);

                        // Rowdata
                        //$client_email = $row[0];
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
                        $description = $row[26];
                        $sender_reference = $row[27];
                        $receiver_reference = $row[28];
                        $payment_type = $row[29];
                        $billing_type = $row[30];
                        $value = $row[31];
                        $payment_to_collect = $row[32];
                        $contact_name = $row[33];
                        $contact_mobile = $row[34];
                        $date = $row[35];
                        $time = $row[36];
                        $remarks = $row[37];
                        $insured = $row[38];
                        $tax_payer = $row[39];

//                        $client_data = $this->db->where('email', $client_email)->where('level_id', 3)->get('client_table')->result();
//                        $client_id = '';
//                        if (isset($client_data[0])) {
//                            $client_id = $client_data[0]->client_id;
//                        } else {
//                            $data['message'] = 'invalid Client data ' . $client_email;
//                            break;
//                        }


                        $sender_data = $this->db->where('address_line', $address_line1)->where('client_id', $client_id)->get('order_sender')->result();
                        $sender_id = '';
                        if (isset($sender_data[0])) {
                            $sender_id = $sender_data[0]->id;
                        } else {
                            $sender['address_line'] = $address_line1;
                            $sender['account_no'] = mt_rand();
                            $sender['city'] = $city1;
                            $sender['country_id'] = $this->db->where('country_name', $country1)->get('country_table')->result()[0]->id;
                            $sender['address'] = $address1;
                            $sender['postal_code'] = $postal_code1;
                            $sender['email'] = $email;
                            $sender['name'] = $name1;
                            $sender['state'] = $state1;
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
                            $receiver['account_no'] = mt_rand();
                            $receiver['city'] = $city2;
                            $receiver['country_id'] = $this->db->where('country_name', $country2)->get('country_table')->result()[0]->id;
                            $receiver['address'] = $address2;
                            $receiver['postal_code'] = $postal_code2;
                            $receiver['email'] = $email2;
                            $receiver['name'] = $name2;
                            $receiver['company_name'] = $company;
                            $receiver['state'] = $state2;
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
                            $consignment['description'] = $description;
                            $consignment['client_id'] = $client_id;

                            $this->db->insert('consignment_details', $consignment);
                            $consignment_id = $this->db->insert_id();

                        }


                        $contact_person = $this->db->where('person_name' , $contact_name)->get('order_contact_person')->result();
                        $contact_person_id = '';
                        if(isset($contact_person[0]))
                        {
                            $contact_person_id = $contact_person[0]->person_id;
                        }
                        else
                        {
                            $contact['person_name'] = $contact_name;
                            $contact['person_mobile'] = $contact_mobile;
                            $contact['client_id'] = $client_id;

                            $this->db->insert('order_contact_person', $contact);
                            $contact_person_id = $this->db->insert_id();
                        }

                        $order['client_id'] = $client_id;
                        $order['receiver_id'] = $receiver_id;
                        $order['sender_id'] = $sender_id;
                        $order['consignment_id'] = $consignment_id;
                        $order['payment_type_id'] = $this->db->where('payment_type' , $payment_type)->get('payment_types')->result()[0]->id;
                        $order['billing_type'] = $this->db->where('billing_type' , $billing_type)->get('billing_types')->result()[0]->id;
                        $order['contact_person_id'] = $contact_person_id;
                        $order['created_by']='admin';
                        $order['updated_by']='admin';
                        $order['order_pickup_date'] = $date;
                        $order['order_pickup_time'] = $time;
                        $order['remarks'] = $remarks;
                        $order['value'] = $value;
                        $order['payment_to_collect'] = $payment_to_collect;
                        $order['sender_reference'] = $sender_reference;
                        $order['receiver_reference'] = $receiver_reference;
                        $order['tax_payer_id'] = $this->db->where('tax_payer' , $tax_payer)->get('tax_payers')->result()[0]->id;
                        if($insured == 'Yes')
                        {
                            $order['is_insurred'] = '1';
                        }
                        else
                        {
                            $order['is_insurred'] = '0';
                        }
                        

                        if($country1 == $country2)
                        {
                            $order['service_id'] = 1;
                        }
                        else
                        {
                            $order['service_id'] = 2;
                        }
                        //$order['service_id'] = $date['service_id'];
                        $order['issuer_code'] = substr($this->session->userdata['UserType'] , 0 , 1);
                        $max_serial = $this->db->select_max('serial_number')->get('order_details');
                        if($max_serial->num_rows()>0)
                        {
                            $max_serial = $max_serial->result();
                            $order['serial_number'] = $max_serial[0]->serial_number;
                            
                            if($max_serial[0]->serial_number == 0)
                            {
                                $this->db->select('site_config.*');
                                $this->db->where('key_name' , 'serial_number');
                                $max_serial = $this->db->get('site_config')->result();
                                $order['serial_number'] = $max_serial[0]->key_value;
                            }
                        }
                        
                        $order['serial_number'] = $order['serial_number'] + 1;

                        //$airway_bill = $order['service_id'] . $this->Country_model->getCountry()[0]->country_code . $order['serial_number'] . $this->Country_model->getCountry()[0]->country_code .'DUMMY ORDER NO'. $order['issuer_code'];

                        $order['created_on']=date('Y-m-d H:i:s');

                        $seed = 'JvKnrQWPsThuJteNQAuH';
                        $hash = sha1(uniqid($seed . mt_rand(), true));

                        # To get a shorter version of the hash, just use substr
                        $hash = substr($hash, 0, 8);
                        $order['order_tracking_id'] = $hash;
                        $order['order_status'] = '1';
                        //die(print_r($order));

                        $this->db->insert('order_details',$order);
                        $order_id = $this->db->insert_id();


                        $airway_bill = $order['service_id'] . $this->db->where('country_name', $country1)->get('country_table')->result()[0]->country_code . $order['serial_number'] . $this->db->where('country_name', $country2)->get('country_table')->result()[0]->country_code .$order_id. $order['issuer_code'];



                        $airway['order_id'] = $order_id;
                        $airway['client_id'] = $client_id;
                        $airway['airway_bill'] = $airway_bill;
                        $this->db->insert('order_airway_bill',$airway);

                        $order_payment['order_id'] = $order_id;


                        if($order['service_id'] == 2)
                        {
                            $zone_id = $this->db->where('id' ,$country2 )->get('country_table')->result()[0]->zone_id;
                            $zone_rate = $this->db->where('client_id' , $client_id)->where('zone_id' , $zone_id)->get('client_rates')->result()[0]->zone_rate; 
                            $order_payment['payable_amount'] = floatval($weight) * floatval($zone_rate);
                        }
                        else
                        {
                            $rate = $this->db->where('client_id' ,$client_id)->get('client_table')->result()[0]->domestic_rates;
                            $order_payment['payable_amount'] = floatval($weight) * floatval($rate);               
                        }

                        $order_payment['order_status'] = 1;
                        $this->db->insert('order_payments' , $order_payment);


                        $code_number = $airway_bill;

                        new barCodeGenrator($code_number,0,APPPATH.'../uploads/'.$airway_bill.'.gif', 190, 130, true);
        //                redirect(base_url().'order/generateAirwaybill');
    //                            $data['serial_number'] = $order['serial_number'];
    //                            $data['order_id'] = $order_id;
    //                            $data['client_id'] = $client_id;
    //
    //                            echo $this->load->view('Order/generatePDF' , $data , TRUE);
    //                            die();

    //                            $bill = new Order();
                        $this->generateAirwaybill($order['serial_number']);
    //                            

                        $itr = $itr + 1;


                    }
                    redirect(base_url().'Order/ListOrders');
                }
                   // }


	    //$this->load->view('Order/bulk-order-origanated' ,$data );
	    $this->load->view('page/Dashboard/bulk-order-origanated' ,$data );
	
        }
        public function generateAirwaybill($serial_number)
        {
            
           //$serial_number = '123457';//$this->input->get('ref-id');
           
           $order_details = $this->db->where('serial_number',$serial_number)->get('order_details')->result();
           //var_dump($order_details);die('HERE');
           $sender_details = $this->db->where('id',$order_details[0]->sender_id)->get('order_sender')->result();
           $receiver_details = $this->db->where('id',$order_details[0]->receiver_id)->get('order_receiver')->result();
           $contact_person_details = $this->db->where('person_id',$order_details[0]->contact_person_id)->get('order_contact_person')->result();
           $consignment_details = $this->db->where('id',$order_details[0]->consignment_id)->get('consignment_details')->result();
           $airway_bill_details = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_airway_bill')->result();
           
           $client_id = $order_details[0]->client_id;
           $client_details = $this->db->where('client_id' , $client_id)->get('client_table')->result();
                   
           $pdf = new FPDI();
           $pdf->AddPage();

            $sender_account_number =$sender_details[0]->account_no;
            $sender_ref = $order_details[0]->sender_reference;
            $sender_name = $sender_details[0]->name;
            $sender_phone_no = $sender_details[0]->mobile;
            $sender_company =$client_details[0]->company_name ;
            $sender_address = $sender_details[0]->address ;
            $sender_city = $sender_details[0]->city;
            $sender_country = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result()[0]->country_name; 
            $sender_province = $sender_details[0]->state;
            $sender_postal_code = $sender_details[0]->postal_code;

            $receiver_account_number = $receiver_details[0]->account_no;
            $receiver_ref = $order_details[0]->receiver_reference;
            $receiver_name = $receiver_details[0]->name;
            $receiver_phone_no =$receiver_details[0]->mobile ;
            $receiver_company = $receiver_details[0]->company_name ;
            $receiver_address = $receiver_details[0]->address;
            $receiver_city = $receiver_details[0]->city;
            $receiver_country = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result()[0]->country_name;
            $receiver_province = $receiver_details[0]->state ;
            $receiver_postal_code = $receiver_details[0]->postal_code;

            $send_creation_date = explode(' ',$order_details[0]->created_on)[0];
            $origin = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result()[0]->country_code ;
            $destination = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result()[0]->country_code;
            $tracking_number = $order_details[0]->serial_number;
            $no_of_packages = $consignment_details[0]->no_of_packages;
            $waight = $consignment_details[0]->weight;
//            echo $order_details[0]->order_id.'';
//            die(var_dump($this->db->where('order_id' , $order_details[0]->order_id)->get('order_payments')->result()));
            $value =   $this->db->where('order_id' , $order_details[0]->order_id)->get('order_payments')->result()[0]->payable_amount . ' BHD'; //'173.8 AED';
            $receive_date = explode(' ',$order_details[0]->updated_on)[0];

//            die('HERE');
            
             //Set the source PDF file
            $pagecount = $pdf->setSourceFile(APPPATH.'/libraries/PDFTemplate/doc1.pdf');
            //Import the first page of the file
            $tpl = $pdf->importPage(1);
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

            $pdf->SetY(77);
            $pdf->Cell(23, 10, $sender_city, 0, 0, 'C');
            $pdf->Cell(119, 10, $sender_province, 0, 0, 'C');

            $pdf->SetY(88);
            $pdf->Cell(23, 10, $sender_country, 0, 0, 'C');
            $pdf->Cell(119, 10, $sender_postal_code, 0, 0, 'C');

            $pdf->SetY(104);
            $pdf->Cell(10, 10, $receiver_account_number, 0, 0, 'C');
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

            $pdf->SetY(155);
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
            $pdf->Cell(350, 10, $waight, 0, 0, 'C');


            $pdf->SetY(94);
            $pdf->Cell(330, 10, $value, 0, 0, 'C');

            $pdf->SetY(173);
            $pdf->Cell(349, 10, $receive_date, 0, 0, 'C');

            
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',10,6,30,'PNG');
            $airway_bill = $airway_bill_details[0]->airway_bill;
            $pdf->Image(APPPATH.'../uploads/'.$airway_bill.'.gif',170,6,30,'GIF');

            //$pdf->SetY(10);
            if($receiver_country === $sender_company)
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
                $pdf->Cell(265, 10, $this->db->where('id' , $consignment_details[0]->type )->get('order_types')->result()[0]->type, 0, 0, 'C');
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
            
            if($order_details[0]->is_insurred == '1')
            {
                $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,156,3,5,'PNG');
            }
            

            $pdfPath = APPPATH.'../uploads/'.$airway_bill.'.pdf';
            $pdf->Output($pdfPath, "F");
            
            

//            $file_url = base_url().'uploads/'.$airway_bill.'.pdf';
//            header('Content-Type: application/pdf');
//            header("Content-Transfer-Encoding: Binary");
//            header("Content-disposition: attachment; filename=AirwayBill.pdf");
//            readfile($file_url);
////            
//            
//            redirect(base_url().'Order/create');


           
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