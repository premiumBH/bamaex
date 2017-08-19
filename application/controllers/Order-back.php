<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set("include_path", '/home/bahraingq/php:' . ini_get("include_path") );

include APPPATH.'/libraries/Barcode/barcode.inc.php';
define('FPDF_FONTPATH',APPPATH.'libraries/fpdf/font/');
require_once(APPPATH.'libraries/fpdf/fpdf/fpdf.php'); 
require_once(APPPATH.'libraries/fpdf/fpdf/fpdi.php'); 


class Order extends CI_Controller {

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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('User_model');
		$this->load->model('Admin_model');
                $this->load->model('Zone_model');
                $this->load->model('Country_model');
                $this->load->model('Client_model');
		$this->load->library('session');
                $this->load->helper('cookie');	
        }
        public function getReceiver()
        {
            $client_id = $this->input->post('client_id');
            $receiver_id = $this->input->post('receiver_id');
            if($client_id != '' && $receiver_id != '')
            {
                $this->db->select('order_receiver.*');
                $this->db->from('order_receiver');
                $this->db->where('id' , $receiver_id);
                $this->db->where('client_id' , $client_id);
                $receiver =  $this->db->get()->result();
                echo json_encode($receiver);
                
            }
            else
            {
                echo 'No';
            }
        }
        public function getSender()
        {
            $client_id = $this->input->post('client_id');
            $receiver_id = $this->input->post('sender_id');
            if($client_id != '' && $receiver_id != '')
            {
                $this->db->select('order_sender.*');
                $this->db->from('order_sender');
                $this->db->where('id' , $receiver_id);
                $this->db->where('client_id' , $client_id);
                $receiver =  $this->db->get()->result();
                echo json_encode($receiver);
                
            }
            else
            {
                echo 'No';
            }
        }
        public function getConsignment()
        {
            $client_id = $this->input->post('client_id');
            $consignment_id = $this->input->post('consignment_id');
            if($client_id != '' && $consignment_id != '')
            {
                $this->db->select('consignment_details.*');
                $this->db->from('consignment_details');
                $this->db->where('id' , $consignment_id);
                $this->db->where('client_id' , $client_id);
                $receiver =  $this->db->get()->result();
                echo json_encode($receiver);
                
            }
            else
            {
                echo 'No';
            }
        }
        public function selectClient()
        {
//            die($this->session->userdata['UserType']);
            if($this->session->userdata['UserType'] == 'Client')
            {
                $client_id = $this->Client_model->getClientDetailsByEmail($this->session->userdata['email']);
                redirect(base_url().'order/create?client_id='.$client_id[0]->client_id);
            }
            else
            {
                $data['clients'] = $this->Client_model->getActiveClients();
                $this->load->view('Order/selectClient' , $data);
            }
            
            
        }
        public function getContactPerson()
        {
            $client_id = $this->input->post('client_id');
            $person_id = $this->input->post('person_id');
            if($client_id != '' && $person_id != '')
            {
                $this->db->select('order_contact_person.*');
                $this->db->from('order_contact_person');
                $this->db->where('person_id' , $person_id);
                $this->db->where('client_id' , $client_id);
                $receiver =  $this->db->get()->result();
                echo json_encode($receiver);
                
            }
            else
            {
                echo 'No';
            }
        }
        public function generateAirwaybill($serial_number)
        {
            
           //$serial_number = $this->input->get('ref-id');
           
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
//            header("Content-disposition: attachment; filename=".$airway_bill.".pdf");
//            readfile($file_url);
////            
//            
//            redirect(base_url().'Order/create');


           
        }
        public function create()
        {
            
            
            $date['client_id']=$this->input->post('client_id');
            $date['sender_id']=$this->input->post('sender_id');
            $date['sender_address_line']=$this->input->post('sender_address_line');
            $date['sender_reference_no']=$this->input->post('sender_reference_no');
            $date['city']=$this->input->post('sender_city');
            $date['state']=$this->input->post('sender_state');
            $date['country_id']=$this->input->post('sender_country_id');
            $date['address']=$this->input->post('sender_address');
            $date['postal_code']=$this->input->post('sender_postal_code');
            $date['email']=$this->input->post('sender_email');
            $date['name']=$this->input->post('sender_name');
            $date['mobile']=$this->input->post('sender_mobile');
            $date['new_sender']=$this->input->post('new_sender');
            $date['existing_sender']=$this->input->post('existing_sender');
            
            $date['receiver_id']=$this->input->post('receiver_id');
            $date['receiver_address_line']=$this->input->post('receiver_address_line');
            $date['receiver_reference_no']=$this->input->post('receiver_reference_no');
            $date['receiver_city']=$this->input->post('receiver_city');
            $date['receiver_state']=$this->input->post('receiver_state');
            $date['receiver_country_id']=$this->input->post('receiver_country_id');
            $date['receiver_address']=$this->input->post('receiver_address');
            $date['receiver_postal_code']=$this->input->post('receiver_postal_code');
            $date['receiver_email']=$this->input->post('receiver_email');
            $date['receiver_name']=$this->input->post('receiver_name');
            $date['receiver_company_name']=$this->input->post('receiver_company_name');
            $date['receiver_mobile']=$this->input->post('receiver_mobile');
            $date['new_receiver']=$this->input->post('new_receiver');
            $date['existing_receiver']=$this->input->post('existing_receiver');
            
            $date['consignment_id']=$this->input->post('consignment_id');
            $date['breath']=$this->input->post('breath');
            $date['height']=$this->input->post('height');
            $date['weight']=$this->input->post('weight');
            $date['width']=$this->input->post('width');
            $date['title']=$this->input->post('title');
            $date['type']=$this->input->post('type');
            $date['packages']=$this->input->post('packages');
            
            $date['new_consignment']=$this->input->post('new_consignment');
            $date['existing_consignment']=$this->input->post('existing_consignment');
            
            
            $date['new_contact_person']=$this->input->post('new_contact_person');
            $date['existing_contact_person']=$this->input->post('existing_contact_person');
            $date['contact_person_name']=$this->input->post('contact_person_name');
            $date['contact_person_mobile']=$this->input->post('contact_person_mobile');
            $date['person_id']=$this->input->post('person_id');
            
            $date['payment_id'] = $this->input->post('payment_id');

            $date['time']=$this->input->post('time');
            $date['date']=$this->input->post('date');
            $date['remarks']=$this->input->post('remarks');
            $date['payer_id']=$this->input->post('payer_id');
            $date['insured']=$this->input->post('insured');
            $date['sender_ref']=$this->input->post('sender_ref');
            $date['receiver_ref']=$this->input->post('receiver_ref');
            
            $date['service_id']=$this->input->post('service_id');
            
//            $date['new_pickup_address']=$this->input->post('new_pickup_address');
//            $date['existing_pickup_address']=$this->input->post('existing_pickup_address');
//            $date['pickup_address']=$this->input->post('pickup_address');
//            $date['pickup_id']=$this->input->post('pickup_id');
            
            $date['new_order'] = $this->input->post('new_order');
            $date['existing_order'] = $this->input->post('existing_order');
            
            if(isset($date['new_order']) && $date['new_order'] != '')
            {
                
               // die(print_r($_POST));
                $sender_id = '';
                if(isset($date['new_sender']) && $date['new_sender'] != 'existing_sender')
                {
                    $sender['address_line'] = $date['sender_address_line'];
                    $sender['account_no'] = mt_rand();
                    $sender['city'] = $date['city'];
                    $sender['country_id'] = $date['country_id'];
                    $sender['address'] = $date['address'];
                    $sender['postal_code'] = $date['postal_code'];
                    $sender['email'] = $date['email'];
                    $sender['name'] = $date['name'];
                    $sender['state'] = $date['state'];
                    $sender['mobile'] = $date['mobile'];
                    $sender['client_id'] = $date['client_id'];
                    
                    $this->db->insert('order_sender',$sender);
                    $sender_id=$this->db->insert_id();
                    //die($sender_id."HERE");
                    
                    
                }
                else
                {
                    $sender_id = $date['sender_id'];

                }
               // die('HERE');

                $receiver_id = '';
                if(isset($date['new_receiver']) && $date['new_receiver'] != 'existing_receiver')
                {
                    $receiver['address_line'] = $date['receiver_address_line'];
                    $receiver['account_no'] = mt_rand();
                    $receiver['city'] = $date['receiver_city'];
                    $receiver['country_id'] = $date['receiver_country_id'];
                    $receiver['address'] = $date['receiver_address'];
                    $receiver['postal_code'] = $date['receiver_postal_code'];
                    $receiver['email'] = $date['receiver_email'];
                    $receiver['name'] = $date['receiver_name'];
                    $receiver['company_name'] = $date['receiver_company_name'];
                    $receiver['state'] = $date['receiver_state'];
                    $receiver['mobile'] = $date['receiver_mobile'];
                    $receiver['client_id'] = $date['client_id'];
                    
                    $this->db->insert('order_receiver',$receiver);
                    $receiver_id=$this->db->insert_id();
                    //echo $receiver_id." ";
                    
                }
                else
                {
                    $receiver_id = $date['receiver_id'];

                }
                
                
                $consignment_id = '';
                if(isset($date['new_consignment']) && $date['new_consignment'] != 'existing_consignment')
                {
                   
                    $consignment['breath'] = $date['breath'];
                    $consignment['height'] = $date['height'];
                    $consignment['weight'] = $date['weight'];
                    $consignment['width'] = $date['width'];
                    $consignment['title'] = $date['title'];
                    $consignment['client_id'] = $date['client_id'];
                    $consignment['type'] = $date['type'];
                    $consignment['no_of_packages'] = $date['packages'];
                    
                    $this->db->insert('consignment_details',$consignment);
                    $consignment_id=$this->db->insert_id();
                    
                }
                else
                {
                    $consignment_id = $date['consignment_id'];
                }
                
                $contact_person_id = '';
                if(isset($date['new_contact_person']) && $date['new_contact_person'] != 'existing_contact_person')
                {
                    
                    $person['person_name'] = $date['contact_person_name'];
                    $person['person_mobile'] = $date['contact_person_mobile'];
                    $person['client_id'] = $date['client_id'];
                    
                    
                    $this->db->insert('order_contact_person',$person);
                    $contact_person_id=$this->db->insert_id();
                    
                    //echo $contact_person_id." ";
                    
                }
                else
                {
                    $contact_person_id = $date['person_id'];
                }
                
                
                $order['client_id'] = $date['client_id'];
                $order['receiver_id'] = $receiver_id;
                $order['sender_id'] = $sender_id;
                $order['consignment_id'] = $consignment_id;
                $order['payment_type_id'] = $date['payment_id'];
                $order['tax_payer_id'] = $date['payer_id'];
                $order['remarks'] = $date['remarks'];
                $order['sender_reference'] = $date['sender_ref'];
                $order['receiver_reference'] = $date['receiver_ref'];
                $order['order_status'] = '1';
                if(!isset($date['insured']))
                {
                    $order['is_insurred'] = 0;
                }
                else
                {
                    $order['is_insurred'] = 1;   
                }
                $order['contact_person_id'] = $contact_person_id;
                $order['created_by']='admin';
                $order['updated_by']='admin';
                /*
                $pickup_date = explode("-",$date['date']);
                $order['order_pickup_date'] = $pickup_date[2].'-'.$pickup_date[1].'-'.$pickup_date[0];
                //0000-00-00 00:00:00 
                $pickup_time = explode(":",$date['time']);
                $pickup_time2 = explode(" ",$pickup_time[1]);
                $order['order_pickup_time'] = $pickup_date[2].'-'.$pickup_date[1].'-'.$pickup_date[0].' '.$pickup_time[0].':'.$pickup_time2[0].':'.'00';
                */
                $order['order_pickup_date'] = $date['date'];
                $order['order_pickup_time'] = $date['time'];
                
                if($date['country_id'] == $date['receiver_country_id'])
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
                //die(print_r($order));
                
                $this->db->insert('order_details',$order);
                $order_id = $this->db->insert_id();
                
                
                $airway_bill = $order['service_id'] . $this->Country_model->getCountry($date['country_id'])[0]->country_code . $order['serial_number'] . $this->Country_model->getCountry($date['receiver_country_id'])[0]->country_code .$order_id. $order['issuer_code'];
                
                
                
                $airway['order_id'] = $order_id;
                $airway['client_id'] = $date['client_id'];
                $airway['airway_bill'] = $airway_bill;
                $this->db->insert('order_airway_bill',$airway);
                
                $order_payment['order_id'] = $order_id;
                
                
                if($order['service_id'] == 2)
                {
                    $zone_id = $this->db->where('id' ,$date['receiver_country_id'] )->get('country_table')->result()[0]->zone_id;
                    $zone_rate = $this->db->where('client_id' , $date['client_id'])->where('zone_id' , $zone_id)->get('client_rates')->result()[0]->zone_rate; 
                    $order_payment['payable_amount'] = floatval($date['weight']) * floatval($zone_rate);
                }
                else
                {
                    $rate = $this->db->where('client_id' ,$date['client_id'])->get('client_table')->result()[0]->domestic_rates;
                    $order_payment['payable_amount'] = floatval($date['weight']) * floatval($rate);               
                }
                
                $order_payment['order_status'] = 1;
                $this->db->insert('order_payments' , $order_payment);
                
                
                $code_number = $airway_bill;
                
                new barCodeGenrator($code_number,0,APPPATH.'../uploads/'.$airway_bill.'.gif', 190, 130, true);
//                redirect(base_url().'order/generateAirwaybill');
//                $data['serial_number'] = $order['serial_number'];
//                $data['order_id'] = $order_id;
//                $data['client_id'] = $date['client_id'];
//                
//                echo $this->load->view('Order/generatePDF' , $data , TRUE);
//                die();
                $this->generateAirwaybill($order['serial_number']);
                $this->ListOrders();
                
                
            }
            
            
                //$client_id = '4';
                if($this->session->userdata['UserType'] == 'Client')
                {
                    $order['client_id']=$this->input->get('client_id');
                }
                else {
                    $order['client_id']=$this->input->post('client_id');

                }
//                $client = $this->Client_model->getClientDetailsByID($client_id);
                
//                $order['company_name']=$client[0]->company_name;
//                $order['account_id']=$client[0]->account_number;
//                $order['city']=$client[0]->city;
//                $order['country_id']=$client[0]->country_id;
//                $order['address']=$client[0]->address;
//                $order['postal_code']=$client[0]->postal_code;
//                $order['email']=$client[0]->email;
//                $order['phone_no']=$client[0]->phone_no;
//                $order['mobile']=$client[0]->mobile;
                
                $order['sender_id']='';
                $order['sender_address_line']='';
                $order['sender_reference_no']='';
                $order['city']='';
                $order['country_id']='';
                $order['address']='';
                $order['postal_code']='';
                $order['email']='';
                $order['name1']='';
                $order['state']='';
                $order['mobile']='';
                
                $order['receiver_id']='';
                $order['receiver_address_line']='';
                $order['receiver_reference_no']='';
                $order['receiver_city']='';
                $order['receiver_country_id']='';
                $order['receiver_address']='';
                $order['receiver_postal_code']='';
                $order['receiver_email']='';
                $order['receiver_name']='';
                $order['receiver_company_name']='';
                $order['receiver_state']='';
                $order['receiver_mobile']='';

                $order['consignment_id']='';
                $order['breath']='';
                $order['height']='';
                $order['weight']='';
                $order['width']='';
                $order['title']='';
                $order['type']='';
                $order['packages']='';


                $order['contact_person_name']='';
                $order['contact_person_mobile']='';
                $order['person_id']='';

                $order['pickup_address']='';
                $order['pickup_id']='';
                $order['remarks']='';
                $order['insured']='';
                $order['sender_reference']='';
                $order['receiver_reference']='';
                
                // load View
                $this->db->where('client_id' , $order['client_id']);
                $order['senders'] = $this->db->get('order_sender')->result();
                
                $this->db->where('client_id' , $order['client_id']);
                $order['receivers'] = $this->db->get('order_receiver')->result();
                
                $this->db->where('client_id' , $order['client_id']);
                $order['pickup_addresses'] = $this->db->get('order_pickup')->result();
                
                $this->db->where('client_id' , $order['client_id']);
                $order['consignments'] = $this->db->get('consignment_details')->result();
                
                $this->db->where('client_id' , $order['client_id']);
                $order['contact_persons'] = $this->db->get('order_contact_person')->result();
                
                $order['countries'] = $this->Country_model->get_countries();
                
                $order['types'] = $this->db->get('order_types')->result();
                
                $order['services'] = $this->db->get('service_table')->result();
                $order['payment_types'] = $this->db->get('payment_types')->result();
                $order['payers'] = $this->db->get('tax_payers')->result();
                
                $this->load->view('page/Dashboard/order-origanated' , $order);
            
        }
        public function downloadAirway()
        {
            $airway_bill  = $this->input->get('ref-id');
            $file_url = base_url().'uploads/'.$airway_bill.'.pdf';
            header('Content-Type: application/pdf');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=".$airway_bill.".pdf");
            readfile($file_url);
            
        }
        public function getBill()
        {
            if(($this->input->post('sender_country') != null) && ($this->input->post('receiver_country') != null) && ($this->input->post('weight') != null))
            //if(isset($_POST['sender_country']) && isset($_POST['receiver_country']))
            {
                $sender_country = $this->input->post('sender_country');
                $receiver_country = $this->input->post('receiver_country');
                $weight = $this->input->post('weight');
                $client_id = $this->input->post('client_id');
                
                $bill = '';
                if($sender_country != $receiver_country)
                {
                    $zone_id = $this->db->where('id' ,$receiver_country )->get('country_table')->result()[0]->zone_id;
                    $zone_rate = $this->db->where('client_id' , $date['client_id'])->where('zone_id' , $zone_id)->get('client_rates')->result()[0]->zone_rate; 
                    $bill = floatval($weight) * floatval($zone_rate);
                }
                else
                {
                    $rate = $this->db->where('client_id' ,$client_id)->get('client_table')->result()[0]->domestic_rates;
                    $bill = floatval($weight) * floatval($rate);               
                }
                
                echo $bill;
                
            }
            else
            {
                echo 'NAN';
            }
        }
        public function view_order()
        {
            $tracking_id = $this->input->get('ref-id');
            $order_details = $this->db->where('order_tracking_id' , $tracking_id)->get('order_details')->result();
            $sender_details = $this->db->where('id' , $order_details[0]->sender_id)->get('order_sender')->result();
            $receiver_details = $this->db->where('id' , $order_details[0]->receiver_id)->get('order_receiver')->result();
            $consignment_detail = $this->db->where('id' , $order_details[0]->consignment_id)->get('consignment_details')->result();
            $contact_person = $this->db->where('person_id' , $order_details[0]->contact_person_id)->get('order_contact_person')->result();
            $airway_bill = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_airway_bill')->result();
            $order_payments = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_payments')->result();
            $order['order_details'] = $order_details;
            $order['order_sender'] = $sender_details;
            $order['order_receiver'] = $receiver_details;
            $order['order_consignment'] = $consignment_detail;
            $order['order_contact'] = $contact_person;
            $order['order_airway'] = $airway_bill;
            $order['order_payments'] = $order_payments;
            $order['payment_type'] = $this->db->where('id' , $order_details[0]->payment_type_id)->get('payment_types')->result();
            $order['receiver_country'] = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result();
            $order['sender_country'] = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result();
            $order['order_type'] = $this->db->where('id' , $consignment_detail[0]->type)->get('order_types')->result();
            $order['shipments'] = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_status_catalog')->result();
            $this->load->view('Order/view_order' , $order);
        }
        public function ListOrders()
        {
            //order_details.*,order_sender.*,order_receiver.*,order_contact_person.
//            $this->db->select('order_details.*');
//            $this->db->from('order_details');
//            $this->db->join('order_sender' , 'order_sender.id = order_details.sender_id');
//            $this->db->join('order_receiver' , 'order_receiver.id = order_details.receiver_id');
//            $this->db->order_by("order_details.order_id", "desc");
//            $order['order'] = $this->db->get()->result();
            $order['order'] = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id order by order_details.order_id desc')->result();
            
            $this->load->view('Order/listOrders' ,$order);
            
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */