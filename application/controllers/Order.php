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
        public function generateAirwaybill()
        {
           $serial_number = $this->input->get('ref-id');
           
           $order_details = $this->db->where('serial_number',$serial_number)->get('order_details')->result();
           //var_dump($order_details);die('HERE');
           $sender_details = $this->db->where('id',$order_details[0]->sender_id)->get('order_sender')->result();
           $receiver_details = $this->db->where('id',$order_details[0]->receiver_id)->get('order_receiver')->result();
           $contact_person_details = $this->db->where('person_id',$order_details[0]->contact_person_id)->get('order_contact_person')->result();
           $consignment_details = $this->db->where('id',$order_details[0]->consignment_id)->get('consignment_details')->result();
           $airway_bill_details = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_airway_bill')->result();
           
           
           $pdf = new FPDI();
           $pdf->AddPage();

           $sender_account_number =$sender_details[0]->account_id;
            $sender_ref = 'DUMMY';
            $sender_name = $contact_person_details[0]->person_name;
            $sender_phone_no = $sender_details[0]->phone_no;
            $sender_company =$sender_details[0]->company_name ;
            $sender_address = $sender_details[0]->address ;
            $sender_city = $sender_details[0]->city;
            $sender_country = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result()[0]->country_name; 
            $sender_province = '';
            $sender_postal_code = $sender_details[0]->postal_code;

            $receiver_account_number = $receiver_details[0]->account_id;
            $receiver_ref = 'DUMMY';
            $receiver_name = $receiver_details[0]->company_name;
            $receiver_phone_no =$receiver_details[0]->phone_no ;
            $receiver_company = $receiver_details[0]->company_name;
            $receiver_address = $receiver_details[0]->address;
            $receiver_city = $receiver_details[0]->city;
            $receiver_country = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result()[0]->country_name;
            $receiver_province = '';
            $receiver_postal_code = $receiver_details[0]->postal_code;

            $send_creation_date = explode(' ',$order_details[0]->created_on)[0];
            $origin = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result()[0]->country_code ;
            $destination = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result()[0]->country_code;
            $tracking_number = $order_details[0]->serial_number;
            $no_of_packages = '';
            $value = '173.8 AED';
            $receive_date = explode(' ',$order_details[0]->updated_on)[0];



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
            $pdf->Cell(5, 10, $sender_name, 0, 0, 'C');
            $pdf->Cell(130, 10, $sender_phone_no, 0, 0, 'C');

            $pdf->SetY(55);
            $pdf->Cell(15, 10, $sender_company, 0, 0, 'C');

            $pdf->SetY(65);
            $pdf->Cell(11, 10, $sender_address, 0, 0, 'C');

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
            $pdf->Cell(6, 10, $receiver_name, 0, 0, 'C');
            $pdf->Cell(110, 10, $receiver_phone_no, 0, 0, 'C');

            $pdf->SetY(122);
            $pdf->Cell(15, 10, $receiver_company, 0, 0, 'C');

            $pdf->SetY(132);
            $pdf->Cell(11, 10, $receiver_address, 0, 0, 'C');

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

            $pdf->SetY(63);
            $pdf->Cell(220, 10, $no_of_packages, 0, 0, 'C');


            $pdf->SetY(94);
            $pdf->Cell(330, 10, $value, 0, 0, 'C');

            $pdf->SetY(173);
            $pdf->Cell(349, 10, $receive_date, 0, 0, 'C');

            
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',10,6,30,'PNG');
            $airway_bill = $airway_bill_details[0]->airway_bill;
            $pdf->Image(APPPATH.'../uploads/'.$airway_bill.'.gif',170,6,30,'GIF');

            //$pdf->SetY(10);
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',136,48,3,5,'PNG');
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',163,48,3,5,'PNG');

            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',132,72,3,5,'PNG');
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',155,72,3,5,'PNG');

            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,112,3,5,'PNG');
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',150,112,3,5,'PNG');

            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,121,3,5,'PNG');
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',150,121,3,5,'PNG');

            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,131,3,5,'PNG');
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',150,130,3,5,'PNG');

            $pdf->Image(APPPATH.'/libraries/PDFTemplate/confirm.png',111,141,3,5,'PNG');


            $pdfPath = APPPATH.'../uploads/'.$airway_bill.'.pdf';
            $pdf->Output($pdfPath, "F");
            
            

            $file_url = base_url().'uploads/'.$airway_bill.'.pdf';
            header('Content-Type: application/pdf');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=AirwayBill.pdf");
            readfile($file_url);
            
            
            redirect(base_url().'Order/create');


           
        }
        public function create()
        {
            var_dump($_POST);
            
            $date['client_id']=$this->input->post('client_id');
            $date['sender_id']=$this->input->post('sender_id');
            $date['sender_address_line']=$this->input->post('sender_address_line');
            $date['sender_reference_no']=$this->input->post('sender_reference_no');
            $date['city']=$this->input->post('sender_city');
            $date['country_id']=$this->input->post('sender_country_id');
            $date['address']=$this->input->post('sender_address');
            $date['postal_code']=$this->input->post('sender_postal_code');
            $date['email']=$this->input->post('sender_email');
            $date['phone_no']=$this->input->post('sender_phone_no');
            $date['mobile']=$this->input->post('sender_mobile');
            $date['new_sender']=$this->input->post('new_sender');
            $date['existing_sender']=$this->input->post('existing_sender');
            
            $date['receiver_id']=$this->input->post('receiver_id');
            $date['receiver_address_line']=$this->input->post('receiver_address_line');
            $date['receiver_reference_no']=$this->input->post('receiver_reference_no');
            $date['receiver_city']=$this->input->post('receiver_city');
            $date['receiver_country_id']=$this->input->post('receiver_country_id');
            $date['receiver_address']=$this->input->post('receiver_address');
            $date['receiver_postal_code']=$this->input->post('receiver_postal_code');
            $date['receiver_email']=$this->input->post('receiver_email');
            $date['receiver_phone_no']=$this->input->post('receiver_phone_no');
            $date['receiver_mobile']=$this->input->post('receiver_mobile');
            $date['new_receiver']=$this->input->post('new_receiver');
            $date['existing_receiver']=$this->input->post('existing_receiver');
            
            $date['consignment_id']=$this->input->post('consignment_id');
            $date['breath']=$this->input->post('breath');
            $date['height']=$this->input->post('height');
            $date['weight']=$this->input->post('weight');
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
            
            $date['service_id']=$this->input->post('service_id');
            
//            $date['new_pickup_address']=$this->input->post('new_pickup_address');
//            $date['existing_pickup_address']=$this->input->post('existing_pickup_address');
//            $date['pickup_address']=$this->input->post('pickup_address');
//            $date['pickup_id']=$this->input->post('pickup_id');
            
            $date['new_order'] = $this->input->post('new_order');
            $date['existing_order'] = $this->input->post('existing_order');
            
            if(isset($date['new_order']) && $date['new_order'] != '')
            {
                
                //die(print_r($_POST));
                $sender_id = '';
                if(isset($date['sender_address_line']) && $date['sender_address_line'] != '')
                {
                    $sender['address_line'] = $date['sender_address_line'];
                    $sender['reference_no'] = $date['sender_reference_no'];
                    $sender['city'] = $date['city'];
                    $sender['country_id'] = $date['country_id'];
                    $sender['address'] = $date['address'];
                    $sender['postal_code'] = $date['postal_code'];
                    $sender['email'] = $date['email'];
                    $sender['phone_no'] = $date['phone_no'];
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
                if(isset($date['receiver_address_line']) && $date['receiver_address_line'] != '')
                {
                    $receiver['address_line'] = $date['receiver_address_line'];
                    $receiver['reference_no'] = $date['receiver_reference_no'];
                    $receiver['city'] = $date['receiver_city'];
                    $receiver['country_id'] = $date['receiver_country_id'];
                    $receiver['address'] = $date['receiver_address'];
                    $receiver['postal_code'] = $date['receiver_postal_code'];
                    $receiver['email'] = $date['receiver_email'];
                    $receiver['phone_no'] = $date['receiver_phone_no'];
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
                if(isset($date['title']) && $date['title'] != '')
                {
                   
                    $consignment['breath'] = $date['breath'];
                    $consignment['height'] = $date['height'];
                    $consignment['weight'] = $date['weight'];
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
                if(isset($date['contact_person_name']) && $date['contact_person_name'] != '')
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
                $order['payment_type_id'] = $$date['payment_id'];
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
                
                
                $order['service_id'] = $date['service_id'];
                $order['issuer_code'] = substr($this->session->userdata['UserType'] , 0 , 1);
                $max_serial = $this->db->get('order_details');
                if($max_serial->num_rows()>0)
                {
                    $max_serial = $max_serial->result();
                    $order['serial_number'] = $max_serial[0]->serial_number;
                }
                else
                {
                    $this->db->select('site_config.*');
                    $this->db->where('key_name' , 'serial_number');
                    $max_serial = $this->db->get('site_config')->result();
                    $order['serial_number'] = $max_serial[0]->key_value;
                }
                $order['serial_number'] = $order['serial_number'] + 1;
                
                //$airway_bill = $order['service_id'] . $this->Country_model->getCountry()[0]->country_code . $order['serial_number'] . $this->Country_model->getCountry()[0]->country_code .'DUMMY ORDER NO'. $order['issuer_code'];

                $order['created_on']=date('Y-m-d H:i:s');
                
                //die(print_r($order));
                
                $this->db->insert('order_details',$order);
                $order_id = $this->db->insert_id();
                
                
                $airway_bill = $order['service_id'] . $this->Country_model->getCountry($date['country_id'])[0]->country_code . $order['serial_number'] . $this->Country_model->getCountry($date['receiver_country_id'])[0]->country_code .$order_id. $order['issuer_code'];
                
                
                
                $airway['order_id'] = $order_id;
                $airway['client_id'] = $date['client_id'];
                $airway['airway_bill'] = $airway_bill;
                $this->db->insert('order_airway_bill',$airway);
                
                $code_number = $airway_bill;
                
                new barCodeGenrator($code_number,0,APPPATH.'../uploads/'.$airway_bill.'.gif', 190, 130, true);
//                redirect(base_url().'order/generateAirwaybill');
                $data['serial_number'] = $order['serial_number'];
                $data['order_id'] = $order_id;
                $data['client_id'] = $date['client_id'];
                
                echo $this->load->view('Order/generatePDF' , $data , TRUE);
                die();
                
                
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
                $order['phone_no']='';
                $order['mobile']='';
                
                $order['receiver_id']='';
                $order['receiver_address_line']='';
                $order['receiver_reference_no']='';
                $order['receiver_city']='';
                $order['receiver_country_id']='';
                $order['receiver_address']='';
                $order['receiver_postal_code']='';
                $order['receiver_email']='';
                $order['receiver_phone_no']='';
                $order['receiver_mobile']='';

                $order['consignment_id']='';
                $order['breath']='';
                $order['height']='';
                $order['weight']='';
                $order['title']='';
                $order['type']='';
                $order['packages']='';


                $order['contact_person_name']='';
                $order['contact_person_mobile']='';
                $order['person_id']='';

                $order['pickup_address']='';
                $order['pickup_id']='';
                
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
                
                $order['services'] = $this->db->get('service_table')->result();
                $order['payment_types'] = $this->db->get('payment_types')->result();
                
                $this->load->view('page/Dashboard/order-origanated' , $order);
            
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */