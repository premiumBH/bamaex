<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set("include_path", '/home/bahraingq/php:' . ini_get("include_path") );

include APPPATH.'/libraries/Barcode/barcode.inc.php';
define('FPDF_FONTPATH',APPPATH.'libraries/fpdf/font/');
require_once(APPPATH.'libraries/fpdf/fpdf/fpdf.php');
require_once(APPPATH.'libraries/fpdf/fpdf/fpdi.php');

date_default_timezone_set('Asia/Bahrain');



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
        $this->load->library('Notification_lib');
        $this->load->library('Excel');

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

    public function selectCourier()
    {
        $courier_id = $this->input->post('courier_id');
        if(isset($courier_id))
        {
            //die($courier_id);



            $pdf = new FPDI();
            $pdf->AddPage();

            // $pagecount = $pdf->setSourceFile("runsheet_template.pdf");
            $pagecount = $pdf->setSourceFile(APPPATH.'/libraries/PDFTemplate/runsheet_template.pdf');
            $tpl = $pdf->importPage(1);
            $pdf->useTemplate($tpl);
            //$pdf->Image('logo.png',10,6,30,'PNG');
//                $pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',10,6,30,'PNG');
            $pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',5,5,40,'PNG');

            $order_details = $this->db->query('select order_details.* , order_sender.* , order_airway_bill.* , consignment_details.* , order_contact_person.* , order_receiver.name as receiver_name ,order_receiver.city as receiver_city , order_receiver.address as receiver_address ,order_payments.* from order_details ,order_airway_bill ,consignment_details , order_contact_person , order_sender , order_payments , order_receiver where order_sender.id = order_details.sender_id and order_receiver.id = order_details.receiver_id and order_contact_person.person_id = order_details.contact_person_id and order_payments.order_id = order_details.order_id and order_airway_bill.order_id = order_details.order_id and consignment_details.id = order_details.consignment_id and order_details.order_status in (4,5) and order_details.order_state = 0 and order_details.order_id in (select order_id from order_courier_man_ref where courier_man_id = '.$courier_id.')')->result();
            $counter = 1;

            $y_axis = 49.5;
            $page_counter = 1;
            $flag = 0;

            foreach($order_details as $order)
            {
                $sender_name = $order->name;
                $sender_ref = $order->sender_reference ;
                $receiver_name = $order->receiver_name;
                $receiver_ref = $order->receiver_reference;
                $receiver_person = $order->person_name;
                $receiver_person_phone = $order->person_mobile;
                $receiver_city = $order->receiver_city;
                $receiver_address = $order->receiver_address;
                $no_of_piece = $order->no_of_packages;
                $charge_able_weight = $order->chargeable_weight;
                $description = $order->description;
                $payment_to_collect = $order->payment_to_collect;
                $payment_type = $this->db->where('id' , $order->billing_type)->get('billing_types')->result()[0]->billing_type; // $order->billing_type;
                $schedule_date = $order->order_pickup_date;
                $schedule_time = $order->order_pickup_time;
                $remarks = $order->remarks;
                $airway_bill = $order->airway_bill;


                $description = explode(' ', $description)[0];
                $receiver_address = explode(' ', $receiver_address)[0];


                if($counter  == 4)
                {

                    $pdf->AddPage();
                    $pagecount = $pdf->setSourceFile(APPPATH.'/libraries/PDFTemplate/runsheet_template.pdf');
                    $tpl = $pdf->importPage(1);
                    $pdf->useTemplate($tpl);

                    $pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',5,5,40,'PNG');

                    $y_axis = 49.5;
                    $counter = 1;
                }


                $pdf->SetFont('Arial','B',8);

                // First row


                $pdf->SetY($y_axis);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $sender_name, 0, 0, 'L');

                $pdf->SetY($y_axis + 7);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $sender_ref, 0, 0, 'L');

                $pdf->SetY($y_axis + 14);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $receiver_name, 0, 0, 'L');

                $pdf->SetY($y_axis + 21);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $receiver_ref, 0, 0, 'L');


                $pdf->SetY($y_axis + 28);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $receiver_person, 0, 0, 'L');


                $pdf->SetY($y_axis + 35);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $receiver_person_phone, 0, 0, 'L');


                $pdf->SetY($y_axis + 42);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $receiver_city, 0, 0, 'L');


                $pdf->SetY($y_axis + 49);
                $pdf->SetX(32);
                $pdf->Cell(15, 10, $receiver_address, 0, 0, 'L');


                //2nd Row


                $pdf->SetY($y_axis);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $no_of_piece, 0, 0, 'L');

                $pdf->SetY($y_axis + 7);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $charge_able_weight, 0, 0, 'L');

                $pdf->SetY($y_axis + 14);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $description, 0, 0, 'L');

                $pdf->SetY($y_axis + 21);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $payment_to_collect, 0, 0, 'L');


                $pdf->SetY($y_axis + 28);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $payment_type, 0, 0, 'L');


                $pdf->SetY($y_axis + 35);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $schedule_date, 0, 0, 'L');


                $pdf->SetY($y_axis + 42);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $schedule_time, 0, 0, 'L');


                $pdf->SetY($y_axis + 49);
                $pdf->SetX(97);
                $pdf->Cell(15, 10, $remarks, 0, 0, 'L');


                // $pdf->Image('image.gif',150,$y_axis - 15.5,'GIF' );
                $pdf->Image(APPPATH.'../uploads/'.$airway_bill.'.gif',150,$y_axis - 15.5,50,'GIF' );
                //$pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',10,6,30,'PNG');


                $y_axis = $y_axis + 80.5;

                $counter = $counter + 1;
                $flag = 1;

            }
            if($flag == 1)
            {
                $pdf->Output("manifest.pdf", "I");
            }

            //$this->load->view('Order/selectCourier' , $data);
        }

        $data['couriers'] = $this->db->where('intUserTypeId' , '8')->get('user')->result();
        $this->load->view('Order/selectCourier' , $data);



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
        $tracking_number = $order_details[0]->order_tracking_id;
        $no_of_packages = $consignment_details[0]->no_of_packages;
        $waight = $consignment_details[0]->weight;
        $chargeAbleWeight = $this->db->where('order_id' ,$order_details[0]->order_id )->get('order_payments')->result()[0]->chargeable_weight;

        $size = floatval($consignment_details[0]->height) * floatval($consignment_details[0]->width) * floatval($consignment_details[0]->breath);
//            echo $order_details[0]->order_id.'';
//            die(var_dump($this->db->where('order_id' , $order_details[0]->order_id)->get('order_payments')->result()));
        //$value =   $this->db->where('order_id' , $order_details[0]->order_id)->get('order_payments')->result()[0]->payable_amount . ' BHD'; //'173.8 AED';
        $value = $order_details[0]->value;
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

        $pdf->SetY(79);
        $pdf->Cell(23, 10, $sender_city, 0, 0, 'C');
        $pdf->Cell(119, 10, $sender_province, 0, 0, 'C');

        $pdf->SetY(88);
        $pdf->Cell(23, 10, $sender_country, 0, 0, 'C');
        $pdf->Cell(119, 10, $sender_postal_code, 0, 0, 'C');

        $pdf->SetY(104);
        // $pdf->Cell(10, 10, $receiver_account_number, 0, 0, 'C');
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
        //    $pdf->Cell(349, 10, $receive_date, 0, 0, 'C');


        // $pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',10,6,30,'PNG');
        $airway_bill = $airway_bill_details[0]->airway_bill;
        $pdf->Image(APPPATH.'../uploads/'.$airway_bill.'.gif',150,1,'GIF' );

        //$pdf->SetY(10);
        if($receiver_country == $sender_country || $receiver_details[0]->country_id == 15)
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
        $date['description']=$this->input->post('description');

        $date['new_consignment']=$this->input->post('new_consignment');
        $date['existing_consignment']=$this->input->post('existing_consignment');


        $date['new_contact_person']=$this->input->post('new_contact_person');
        $date['existing_contact_person']=$this->input->post('existing_contact_person');
        $date['contact_person_name']=$this->input->post('contact_person_name');
        $date['contact_person_mobile']=$this->input->post('contact_person_mobile');
        $date['person_id']=$this->input->post('person_id');

        $date['payment_id'] = $this->input->post('payment_id');
        $date['billing_id'] = $this->input->post('billing_id');
        $date['value'] = $this->input->post('value');
        $date['payment_to_collect'] = $this->input->post('payment_to_collect');

        $date['time']=$this->input->post('time');
        $date['timeto']=$this->input->post('timeto');
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
                $consignment['description'] = $date['description'];

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

                if($person['person_name'] != '' && $date['contact_person_mobile'] != '' )
                {


                    $this->db->insert('order_contact_person',$person);
                    $contact_person_id=$this->db->insert_id();
                }
                else
                {
                    $contact_person_id = 0;
                }

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
            $order['billing_type'] = $date['billing_id'];
            $order['value'] = $date['value'];
            $order['payment_to_collect'] = $date['payment_to_collect'];
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
            $order['order_pickup_time_to'] = $date['timeto'];


            if($date['country_id'] == $date['receiver_country_id'] || $date['receiver_country_id'] == 15)
            {
                $order['service_id'] = 1;
            }
            else
            {
                $order['service_id'] = 2;
            }
            //$order['service_id'] = $date['service_id'];
            //$order['issuer_code'] = substr($this->session->userdata['UserType'] , 0 , 1);
            if($this->session->userdata['UserType'] == 'Administrator' || $this->session->userdata['UserType'] == 'Admin' || $this->session->userdata['UserType'] == 'Manager' || $this->session->userdata['UserType'] == 'Team Member')
            {
                $order['issuer_code'] = 'S';
            }
            else if($this->session->userdata['UserType'] == 'Agent')
            {
                $order['issuer_code'] = 'A';
            }
            else if($this->session->userdata['UserType'] == 'Client')
            {
                $order['issuer_code'] = 'C';
            }
            else if($this->session->userdata['UserType'] == 'Courier')
            {
                $order['issuer_code'] = 'C';
            }
            else
            {
                $order['issuer_code'] = 'S';
            }

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
            $order['user_id'] = $this->session->userdata['UserId'];
            $hash = sha1(uniqid($seed . mt_rand(), true));
            $hash = substr($hash, 0, 8);
            $order['pickup_reference'] = $hash;

            $this->db->insert('order_details',$order);
            $order_id = $this->db->insert_id();


            $airway_bill = $order['issuer_code'] . $this->Country_model->getCountry($date['country_id'])[0]->country_code . $this->Country_model->getCountry($date['receiver_country_id'])[0]->country_code .$order['serial_number'].$order['service_id'].$order_id ;
            //$airway_bill = $order['issuer_code'] . $this->db->where('country_name', $country1)->get('country_table')->result()[0]->country_code.$this->db->where('country_name', $country2)->get('country_table')->result()[0]->country_code . $order['serial_number'] . $order['service_id'] .$order_id ;


            $airway['order_id'] = $order_id;
            $airway['client_id'] = $date['client_id'];
            $airway['airway_bill'] = $airway_bill;
            $this->db->insert('order_airway_bill',$airway);

            $order_payment['order_id'] = $order_id;

            $weight_per_price = $this->db->where('client_id' , $date['client_id'])->get('client_table')->result()[0]->weight_per_price;


            if($order['service_id'] == 2)
            {


                $zone_id = $this->db->where('id' ,$date['receiver_country_id'] )->get('country_table')->result()[0]->zone_id;
                $zone_rate = $this->db->where('client_id' , $date['client_id'])->where('zone_id' , $zone_id)->get('client_rates')->result()[0]->zone_rate;
                $order_payment['payable_amount'] = floatval($date['weight']) * floatval($zone_rate);
                // as weight is per half KG
                $order_payment['payable_amount'] = $order_payment['payable_amount'] * 2;
            }
            else
            {
                if($date['weight'] > $weight_per_price)
                {
                    $devide = intVal($date['weight'] / $weight_per_price);
                    $rem = $date['weight'] % $weight_per_price;

                    $devide = $devide + 1;

                    $date['weight'] = $devide * $weight_per_price;
                }
                else
                {
                    $date['weight'] = $weight_per_price;
                }



                $rate = $this->db->where('client_id' ,$date['client_id'])->get('client_table')->result()[0]->domestic_rates;
                $order_payment['payable_amount'] = floatval($date['weight']) * floatval($rate);
            }

            $order_payment['chargeable_weight'] = $date['weight'];

            $order_payment['order_status'] = 1;
            $this->db->insert('order_payments' , $order_payment);


            $code_number = $airway_bill;

            new barCodeGenrator($code_number,0,APPPATH.'../uploads/'.$airway_bill.'.gif', 250, 100, true);
//                redirect(base_url().'order/generateAirwaybill');
//                $data['serial_number'] = $order['serial_number'];
//                $data['order_id'] = $order_id;
//                $data['client_id'] = $date['client_id'];
//
//                echo $this->load->view('Order/generatePDF' , $data , TRUE);
//                die();
            $this->generateAirwaybill($order['serial_number']);

            // Generate Manifest

            $pdf = new FPDI();
            $pdf->AddPage();
            $pagecount = $pdf->setSourceFile(APPPATH.'/libraries/PDFTemplate/manifest_template.pdf');
            $tpl = $pdf->importPage(1);
            $pdf->useTemplate($tpl);


            $pdf->SetFont('Arial','',8);

//                $pdf->SetY(5);
//                $pdf->SetX(1);
//                $pdf->Image(APPPATH.'/libraries/PDFTemplate/logo.png',3,4,30,'PNG');



            $client_details = $this->db->where('client_id', $date['client_id'])->get('client_table')->result();

            $client_name =  $client_details[0]->company_name;//"Address Line Address 123 356 356 kghl ";
            $client_mobile = $client_details[0]->phone_no; //"0322 9815923";
            $client_country_code = $this->db->where('id',$client_details[0]->country_id)->get('country_table')->result()[0]->country_code;
            $client_country_name = $this->db->where('id',$client_details[0]->country_id)->get('country_table')->result()[0]->country_name;
            $client_city = $client_details[0]->city;
            $client_address = $client_details[0]->address;

            // $address_line = $date['sender_address_line'];
            $address = $client_address;
            $address2 = $client_country_name;
            $city = $client_city;
            $mobile = $client_mobile;

            //$client_city = explode(' ', $client_city)[0];

//                $pdf->SetY(21);
//                $pdf->SetX(2);
//                $pdf->Cell(40, 10, $address_line, 0, 0, 'L');

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



            // Set Border

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


            //



            // Set First Row
            $pdf->SetFont('Arial','',6.5);

            // $pdf->SetY(58);

            $so_id = $order_id;
            $tracking_number = $order['order_tracking_id'];
            $order_ref = $order['serial_number'];
            $description = $date['description'];
            $consingee_name = substr($date['title'], 0 , 22) ;
            $total = $order_payment['payable_amount'];

            $consingee_name = substr($consingee_name, 0 , 22);
            $description = explode(' ' , $description)[0];

//                $pdf->Cell(30, 10, $so_id, 0, 0, 'L');
//                $pdf->Cell(50, 10, $tracking_number, 0, 0, 'L');
//                $pdf->Cell(30, 10, $order_ref, 0, 0, 'L');
//                $pdf->Cell(30, 10, $description, 0, 0, 'L');
//                $pdf->Cell(40, 10, $consingee_name, 0, 0, 'L');
//                $pdf->Cell(30, 10, $total, 0, 0, 'L');


            $pdf->SetY(58);
            $pdf->SetX(6);
            $pdf->Cell(15, 10, $airway['airway_bill'], 0, 0, 'L');


            $pdf->SetY(58);
            $pdf->SetX(30);
            $pdf->Cell(15, 10, $date['name'], 0, 0, 'L');


            $pdf->SetY(58);
            $pdf->SetX(60);
            $pdf->Cell(15, 10, $date['receiver_name'], 0, 0, 'L');


            $pdf->SetY(58);
            $pdf->SetX(88);
            $pdf->Cell(15, 10, $description, 0, 0, 'L');



            $pdf->SetY(58);
            $pdf->SetX(115);
            $pdf->Cell(15, 10, $date['sender_ref'], 0, 0, 'L');


            $pdf->SetY(58);
            $pdf->SetX(140);
            $pdf->Cell(15, 10, $total, 0, 0, 'L');

            $pdf->SetY(58);
            $pdf->SetX(162);
            $pdf->Cell(15, 10, $this->db->where('id' , $date['payment_id'])->get('payment_types')->result()[0]->payment_type , 0, 0, 'L');


            $pdf->SetY(58);
            $pdf->SetX(185);
            $pdf->Cell(15, 10, $date['payment_to_collect'], 0, 0, 'L');





            $pdf->SetFont('Arial','B',8);

            $total_orders = '1';
            $total_amount = $order_payment['payable_amount'];

            $pdf->SetY(190);
            $pdf->SetX(185);
            $pdf->Cell(40, 10, $total_orders, 0, 0, 'L');


            $pdf->SetY(203);
            $pdf->SetX(185);
            $pdf->Cell(40, 10, $total_amount, 0, 0, 'L');

            $pdfPath = APPPATH.'../uploads/'.$airway_bill.'_manifest.pdf';
            $pdf->Output($pdfPath, "F");





            // End
            $this->SendNotification($order_id, 'NOOS');
            if($this->input->post('date') != '' && $this->input->post('time') != ''){
            $this->SendNotification($order_id, 'CPP');
        }
            $this->ListOrders();

            return;


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
        $order['description']='';


        $order['contact_person_name']='';
        $order['contact_person_mobile']='';
        $order['person_id']='';
        $order['value']='';
        $order['payment_to_collect']='';

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
        $order['billing_types'] = $this->db->get('billing_types')->result();
        $order['payers'] = $this->db->get('tax_payers')->result();

        $this->load->view('page/Dashboard/order-origanated' , $order);

    }

    public function SendNotification($orderId, $code){

        $orderUserData                                  = $this->User_model->getOrderNotificationUsers($orderId);

        $shortCodeArray                                 = array();

        $shortCodeArray['order_tracking_id']            = $orderUserData[0]->orderTrackingId;

        $shortCodeArray['receiver_name']                = $orderUserData[0]->receiverName;
        $shortCodeArray['receiver_email']               = $orderUserData[0]->receiverEmail;
        $shortCodeArray['receiver_mobile']              = $orderUserData[0]->receiverMobile;

        $shortCodeArray['sender_name']                = $orderUserData[0]->senderName;
        $shortCodeArray['sender_email']               = $orderUserData[0]->senderEmail;
        $shortCodeArray['sender_mobile']              = $orderUserData[0]->senderMobile;

        $clientDetail                                   = $this->Client_model->getPrimaryUser($orderUserData[0]->clientId);
        $shortCodeArray['client_first_name']            = $clientDetail[0]->first_name;
        $shortCodeArray['client_last_name']             = $clientDetail[0]->last_name;
        $shortCodeArray['client_name']                  = $clientDetail[0]->first_name.' '.$clientDetail[0]->last_name;;
        $shortCodeArray['client_email']                 = $orderUserData[0]->clientEmail;
        $shortCodeArray['client_mobile']                = $orderUserData[0]->clientMobile;

        $shortCodeArray['client_creator_name']                = $orderUserData[0]->clientCreatorName;
        $shortCodeArray['client_creator_email']               = $orderUserData[0]->clientCreatorEmail;
        $shortCodeArray['client_creator_mobile']              = $orderUserData[0]->clientCreatorMobile;

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


    public function downloadAirway()
    {
        $airway_bill  = $this->input->get('ref-id');
        $file_url = base_url().'uploads/'.$airway_bill.'.pdf';
        header('Content-Type: application/pdf');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=".$airway_bill.".pdf");
        readfile($file_url);

    }
    public function downloadManifest()
    {
        $airway_bill  = $this->input->get('ref-id');
        $order_id = $this->db->where('airway_bill', $airway_bill)->get('order_airway_bill')->result()[0]->order_id;
        $order_details = $this->db->where('order_id',$order_id)->get('order_details')->result();
        if($order_details[0]->batch_id != '')
        {
            $file_url = base_url().'uploads/'.$order_details[0]->batch_id.'_manifest.pdf';
            header('Content-Type: application/pdf');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=".$airway_bill."_manifest.pdf");
            readfile($file_url);
        }
        else
        {
            $file_url = base_url().'uploads/'.$airway_bill.'_manifest.pdf';
            header('Content-Type: application/pdf');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=".$airway_bill."_manifest.pdf");
            readfile($file_url);
        }

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
                $zone_rate = $this->db->where('client_id' , $client_id)->where('zone_id' , $zone_id)->get('client_rates')->result()[0]->zone_rate;
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
    public function search()
    {
        $search_input = $this->input->post('query');
        $order = null;


        $record = null;
        if(isset($this->db->query('select order_details.* , order_sender.* ,order_airway_bill.* , order_receiver.name as receiver_name , order_receiver.mobile as receiver_mobile from order_details,order_sender,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id  and order_airway_bill.airway_bill = "'.$search_input.'" order by order_details.order_id desc')->result()[0]))
        {
            //echo "1 ";
            // print_r($this->db->query('select order_details.* , order_sender.* ,order_airway_bill.* , order_receiver.name as receiver_name , order_receiver.mobile as receiver_mobile from order_details,order_sender,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id  and order_airway_bill.airway_bill like "'.$search_input.'" order by order_details.order_id desc')->result());
            $record = $this->db->query('select order_details.* , order_sender.* ,order_airway_bill.* , order_receiver.name as receiver_name , order_receiver.mobile as receiver_mobile from order_details,order_sender,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id  and order_airway_bill.airway_bill like "'.$search_input.'" order by order_details.order_id desc')->result();
        }
        else if(isset($this->db->query('select order_details.* , order_sender.* ,order_airway_bill.* , order_receiver.name as receiver_name , order_receiver.mobile as receiver_mobile from order_details,order_sender,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and  order_details.order_id = order_airway_bill.order_id and( order_sender.mobile = "'.$search_input.'" or order_details.order_id in (SELECT order_id from order_details where receiver_id in (select id from order_receiver where mobile = "'.$search_input.'")))  order by order_details.order_id desc')->result()[0]))
        {
            //echo "2";
            $record = $this->db->query('select order_details.* , order_sender.* ,order_airway_bill.* , order_receiver.name as receiver_name , order_receiver.mobile as receiver_mobile from order_details,order_sender,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and  order_details.order_id = order_airway_bill.order_id and( order_sender.mobile = "'.$search_input.'" or order_details.order_id in (SELECT order_id from order_details where receiver_id in (select id from order_receiver where mobile = "'.$search_input.'")))  order by order_details.order_id desc')->result();
        }
        else if(isset($this->db->query('select order_details.* , order_sender.* ,order_airway_bill.* , order_receiver.name as receiver_name , order_receiver.mobile as receiver_mobile from order_details,order_sender,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and  order_details.order_id = order_airway_bill.order_id and ( order_sender.name = "'.$search_input.'" or order_details.order_id in (SELECT order_id from order_details where receiver_id in (select id from order_receiver where name = "'.$search_input.'")))  order by order_details.order_id desc')->result()[0]))
        {
            //echo "3";
            $record = $this->db->query('select order_details.* , order_sender.* ,order_airway_bill.* , order_receiver.name as receiver_name , order_receiver.mobile as receiver_mobile from order_details,order_sender,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and  order_details.order_id = order_airway_bill.order_id and ( order_sender.name = "'.$search_input.'" or order_details.order_id in (SELECT order_id from order_details where receiver_id in (select id from order_receiver where name = "'.$search_input.'")))  order by order_details.order_id desc')->result();
        }
        else
        {
            // echo "4 ";
            //$order['order'] = null;
            $record = null;
        }
        //$order_details = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id  and order_airway_bill.airway_bill = '.$search_input.' order by order_details.order_id desc')->result();
        //$order['order'] = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id  and order_airway_bill.airway_bill = '.$search_input.' order by order_details.order_id desc')->result();
        // echo $search_input;
        // die(print_r($record));

        if($record == null)
        {
            $data['order'] = null;
            //$order['order']
            $this->load->view('Order/search_result' ,$data);
            return;
        }

        $orders = $this->removeDuplicate($record);
        $orders_list = array();
        foreach($orders as $order)
        {
            $temp = array();
            $temp['order_tracking_id'] = $order->order_tracking_id;
            $temp['name'] = $order->name;
            $temp['mobile'] = $order->mobile;
            $temp['receiver_name'] = $order->receiver_name;
            $temp['receiver_mobile'] = $order->receiver_mobile;
            $temp['airway_bill'] = $order->airway_bill;

            $receiver_country_id = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->country_id  ;
            $sender_country_id = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->country_id ;


            if($receiver_country_id == $sender_country_id)
            {
                if($order->order_delivery_status == 0)
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_delivery_status)->get('domestic_delivery_status')->result()[0]->status;
                }
            }
            else
            {
                if($order->order_delivery_status == 0)
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_delivery_status)->get('express_delivery_status')->result()[0]->status;
                }
            }
            $orders_list[] = $temp;
        }
        $data['order'] = $orders_list;
        //$order['order']
        $this->load->view('Order/search_result' ,$data);






    }
    public function removeDuplicate($records)
    {
        $temp_array = array();
        $record_array = array();
        if($records == null)
            return null;
        $i = 0;
        foreach ($records as $record)
        {
            if(!in_array($record->order_id, $temp_array))
            {
                $temp_array[$i] = $record->order_id;
                $record_array[$i] = $record;

                $i = $i + 1;
            }
        }
        //die(print_r($record_array));
        return $record_array;
    }
    public function updateDeliverySchedule()
    {
        $time_from = $this->input->post('time_from');
        $date = $this->input->post('date');
        $time_to = $this->input->post('time_to');
        $name = $this->input->post('name');
        $mobile = $this->input->post('mobile');
        $order_id = $this->input->post('order_id');

        if($date != '' || $time_from != '' || $time_to != '' || $name != '' || $mobile != '')
        {

            $order_details = $this->db->where('order_id' , $order_id)->get('order_details')->result();
            $sender_details = $this->db->where('id' , $order_details[0]->sender_id)->get('order_sender')->result();
            $receiver_details = $this->db->where('id' , $order_details[0]->receiver_id)->get('order_receiver')->result();



            $delivery_schedule = array(); //order_delivery_schedule
            if($date != '')
                $delivery_schedule['delivery_date'] = $date;
            if($time_from != '')
                $delivery_schedule['delivery_time'] = $time_from;
            if($time_to != '')
                $delivery_schedule['delivery_time_to'] = $time_to;
            if($name != '')
                $delivery_schedule['contact_person_name'] = $name;
            if($mobile != '')
                $delivery_schedule['contact_person_mobile'] = $mobile;

            $order_update = array();

            $schedule = $this->db->where('order_id' , $order_id)->get('order_delivery_schedule')->result();
            if(isset($schedule[0]->order_id))
            {
                $this->db->where('order_id' , $order_id )->update('order_delivery_schedule' , $delivery_schedule );
                if($sender_details[0]->country_id == $receiver_details[0]->country_id || $receiver_details[0]->country_id == 15)
                {
                    $order_update['order_delivery_status'] = 5;

                    $tracking = array();
                    $tracking['order_id'] = $order_id;
                    $tracking['catalog_subject'] = 'Order has been Rescheduled for Delivery';//'Rescheduled Delivery';
                    $tracking['catalog_info'] = 'Rescheduled Delivery';
                    $tracking['shipper_id'] = $this->session->userdata['UserId'];
                    $tracking['updated_on'] = date('Y-m-d H:i:s');
                    $tracking['created_on'] = date('Y-m-d H:i:s');
                    $tracking['updated_by'] = 'admin';
                    $tracking['created_by'] = 'admin';

                    $this->db->insert('domestic_order_catalog',$tracking);



                }
                else
                {
                    $order_update['order_delivery_status'] = 11;


                    $tracking = array();
                    $tracking['order_id'] = $order_id;
                    $tracking['catalog_subject'] = 'Order has been Rescheduled for Delivery';//'Rescheduled Delivery';
                    $tracking['catalog_info'] = 'Rescheduled Delivery';
                    $tracking['shipper_id'] = $this->session->userdata['UserId'];
                    $tracking['updated_on'] = date('Y-m-d H:i:s');
                    $tracking['created_on'] = date('Y-m-d H:i:s');
                    $tracking['updated_by'] = 'admin';
                    $tracking['created_by'] = 'admin';

                    $this->db->insert('international_order_catalog',$tracking);
                }
            }
            else
            {


                if($sender_details[0]->country_id == $receiver_details[0]->country_id || $receiver_details[0]->country_id == 15)
                {
                    $order_update['order_delivery_status'] = 3;


                    $tracking = array();
                    $tracking['order_id'] = $order_id;
                    $tracking['catalog_subject'] = 'Order has been Scheduled for Delivery';//'';
                    $tracking['catalog_info'] = 'Scheduled Delivery';
                    $tracking['shipper_id'] = $this->session->userdata['UserId'];
                    $tracking['updated_on'] = date('Y-m-d H:i:s');
                    $tracking['created_on'] = date('Y-m-d H:i:s');
                    $tracking['updated_by'] = 'admin';
                    $tracking['created_by'] = 'admin';

                    $this->db->insert('domestic_order_catalog',$tracking);
                }
                else
                {
                    $order_update['order_delivery_status'] = 9;

                    $tracking = array();
                    $tracking['order_id'] = $order_id;
                    $tracking['catalog_subject'] = 'Order has been Scheduled for Delivery';//'';
                    $tracking['catalog_info'] = 'Scheduled Delivery';
                    $tracking['shipper_id'] = $this->session->userdata['UserId'];
                    $tracking['updated_on'] = date('Y-m-d H:i:s');
                    $tracking['created_on'] = date('Y-m-d H:i:s');
                    $tracking['updated_by'] = 'admin';
                    $tracking['created_by'] = 'admin';

                    $this->db->insert('international_order_catalog',$tracking);
                }


                $delivery_schedule['order_id'] = $order_id;

                $seed = 'JvKnrQWPsThuJteNQAuH';
                $hash = sha1(uniqid($seed . mt_rand(), true));

                # To get a shorter version of the hash, just use substr
                $hash = substr($hash, 0, 8);

                $delivery_schedule['delivery_schedule_ref'] = $hash;
                $this->db->insert('order_delivery_schedule', $delivery_schedule);
            }

            $this->db->where('order_id' , $order_id )->update('order_details' , $order_update );
        }




    }
    public function updatePickupSchedule()
    {

        $time_from = $this->input->post('time_from');
        $date = $this->input->post('date');
        $time_to = $this->input->post('time_to');
        $name = $this->input->post('name');
        $mobile = $this->input->post('mobile');
        $order_id = $this->input->post('order_id');

        $order_detail = array();
        if($date != '')
            $order_detail['order_pickup_date'] = $date;
        if($time_from != '')
            $order_detail['order_pickup_time'] = $time_from;
        if($time_to != '')
            $order_detail['order_pickup_time_to'] = $time_to;

        $contact_person = array();

        $contact_person['person_name'] = $name;
        $contact_person['person_mobile'] = $mobile;

        $order_details = $this->db->where('order_id' , $order_id)->get('order_details')->result();
        $sender_details = $this->db->where('id' , $order_details[0]->sender_id)->get('order_sender')->result();
        $receiver_details = $this->db->where('id' , $order_details[0]->receiver_id)->get('order_receiver')->result();



        $order = $this->db->where('order_id' , $order_id)->get('order_details')->result();
        $person_id = $order[0]->contact_person_id;
        $client_id = $order[0]->client_id;
        if($person_id == 0 && $name != '' && $mobile != '')
        {
            //$contact_person['order_id'] = $order_id;
            $contact_person['client_id'] = $client_id;

            $this->db->insert('order_contact_person', $contact_person);
            $person_id = $this->db->insert_id();

            $order_detail['order_status'] = 13;


            if($sender_details[0]->country_id == $receiver_details[0]->country_id || $receiver_details[0]->country_id == 15)
            {


                $tracking = array();
                $tracking['order_id'] = $order_id;
                $tracking['catalog_subject'] =  'Order has been Scheduled for Pickup';//'Scheduled Pickup';
                $tracking['catalog_info'] = 'Scheduled Pickup';
                $tracking['shipper_id'] = $this->session->userdata['UserId'];
                $tracking['updated_on'] = date('Y-m-d H:i:s');
                $tracking['created_on'] = date('Y-m-d H:i:s');
                $tracking['updated_by'] = 'admin';
                $tracking['created_by'] = 'admin';

                $this->db->insert('domestic_order_catalog',$tracking);
            }
            else
            {

                $tracking = array();
                $tracking['order_id'] = $order_id;
                $tracking['catalog_subject'] =  'Order has been Scheduled for Pickup';//'Scheduled Pickup';
                $tracking['catalog_info'] = 'Scheduled Pickup';
                $tracking['shipper_id'] = $this->session->userdata['UserId'];
                $tracking['updated_on'] = date('Y-m-d H:i:s');
                $tracking['created_on'] = date('Y-m-d H:i:s');
                $tracking['updated_by'] = 'admin';
                $tracking['created_by'] = 'admin';

                $this->db->insert('international_order_catalog',$tracking);
            }







        }
        else
        {
            $this->db->where('person_id' , $person_id )->update('order_contact_person' , $contact_person );
            $order_detail['order_status'] = 3;


            if($sender_details[0]->country_id == $receiver_details[0]->country_id || $receiver_details[0]->country_id == 15)
            {


                $tracking = array();
                $tracking['order_id'] = $order_id;
                $tracking['catalog_subject'] = 'Order has been Rescheduled for Pickup';//'Rescheduled Pickup';
                $tracking['catalog_info'] = 'Rescheduled Pickup';
                $tracking['shipper_id'] = $this->session->userdata['UserId'];
                $tracking['updated_on'] = date('Y-m-d H:i:s');
                $tracking['created_on'] = date('Y-m-d H:i:s');
                $tracking['updated_by'] = 'admin';
                $tracking['created_by'] = 'admin';

                $this->db->insert('domestic_order_catalog',$tracking);
            }
            else
            {

                $tracking = array();
                $tracking['order_id'] = $order_id;
                $tracking['catalog_subject'] = 'Order has been Rescheduled for Pickup';//'Rescheduled Pickup';
                $tracking['catalog_info'] = 'Rescheduled Pickup';
                $tracking['shipper_id'] = $this->session->userdata['UserId'];
                $tracking['updated_on'] = date('Y-m-d H:i:s');
                $tracking['created_on'] = date('Y-m-d H:i:s');
                $tracking['updated_by'] = 'admin';
                $tracking['created_by'] = 'admin';

                $this->db->insert('international_order_catalog',$tracking);
            }
        }

        $order_detail['contact_person_id'] = $person_id;


        $this->db->where('order_id' , $order_id )->update('order_details' , $order_detail );
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
        $order_delivery_schedule = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_delivery_schedule')->result();
        $order['order_details'] = $order_details;
        $order['order_sender'] = $sender_details;
        $order['value'] = $order_details[0]->value;
        $order['billing_type'] = $this->db->where('id',$order_details[0]->billing_type)->get('billing_types')->result()[0]->billing_type; //;
        $order['payment_to_collect'] = $order_details[0]->payment_to_collect;
        $order['order_receiver'] = $receiver_details;
        $order['order_consignment'] = $consignment_detail;
        $order['order_contact'] = $contact_person;
        $order['order_airway'] = $airway_bill;
        if($order_details[0]->order_type  == 'agent' && $order_details[0]->order_status > 199){
            $order['order_pickup_status'] = $this->db->where('id' , $order_details[0]->order_status)->get('order_agent_status')->result()[0]->status;
        }else{
            $order['order_pickup_status'] = $this->db->where('id' , $order_details[0]->order_status)->get('order_pickup_status')->result()[0]->status;
        }

        $order['order_payments'] = $order_payments;
        $order['payment_type'] = $this->db->where('id' , $order_details[0]->payment_type_id)->get('payment_types')->result();
        $order['receiver_country'] = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result();
        $order['sender_country'] = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result();
        $order['receiver_country'] = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result();
        $order['order_type'] = $this->db->where('id' , $consignment_detail[0]->type)->get('order_types')->result();
        $order['comments'] = $this->db->query('SELECT * FROM order_comments , user where order_comments.user_id = user.intUserId and order_comments.order_id = '.$order_details[0]->order_id.' order by order_comments.id desc')->result();
        $order['order_delivery_schedule'] = $order_delivery_schedule;
//              echo $sender_details[0]->country_id;
//            echo 'hi';
//            echo $receiver_details[0]->country_id;
//
        //die($receiver_details[0]->country_id.'');
        if($sender_details[0]->country_id == $receiver_details[0]->country_id || $receiver_details[0]->country_id == 15)
        {
//               $this->db->select('*');
//                $this->db->from('domestic_order_catalog');
//                $this->db->join('user', 'domestic_order_catalog.shipper_id = user.intUserId');
//                $this->db->join('user_address', 'domestic_order_catalog.shipper_id = user_address.user_id');
//                $this->db->where('domestic_order_catalog.order_id',$order_details[0]->order_id);
//                $this->db->order_by("domestic_order_catalog.id", "desc");
//                $query = $this->db->get()->result();
//               $order['shipments'] = $query;

            $query = $this->db->query('select * from domestic_order_catalog
                inner join user on user.intUserId  = domestic_order_catalog.shipper_id
                LEFT JOIN user_address on user_address.user_id = domestic_order_catalog.shipper_id
                where domestic_order_catalog.order_id = '.$order_details[0]->order_id.' order by domestic_order_catalog.id desc')->result();
            $order['shipments'] = $query;

            if($order_details[0]->order_delivery_status == 0)
            {
                if($order_details[0]->order_type  == 'agent' && $order_details[0]->order_status > 199){
                    $order['order_status'] = $this->db->where('id',$order_details[0]->order_status)->get('order_agent_status')->result()[0]->status;
                }else{
                    $order['order_status'] = $this->db->where('id',$order_details[0]->order_status)->get('order_pickup_status')->result()[0]->status;
                }

            }
            else
            {
                $order['order_status'] = $this->db->where('id',$order_details[0]->order_delivery_status)->get('domestic_delivery_status')->result()[0]->status;
            }


        }
        else if ($order_details[0]->order_type  == 'agent' && $order_details[0]->order_status > 199){

            $query = $this->db->query('select * from domestic_order_catalog
                inner join user on user.intUserId  = domestic_order_catalog.shipper_id
                LEFT JOIN user_address on user_address.user_id = domestic_order_catalog.shipper_id
                where domestic_order_catalog.order_id = '.$order_details[0]->order_id.' order by domestic_order_catalog.id desc')->result();
            $order['shipments'] = $query;

            if($order_details[0]->order_delivery_status == 0)
            {
                $order['order_status'] = $this->db->where('id',$order_details[0]->order_status)->get('order_agent_status')->result()[0]->status;
            }
            else
            {
                $order['order_status'] = $this->db->where('id',$order_details[0]->order_delivery_status)->get('domestic_delivery_status')->result()[0]->status;
            }
        }
        else
        {
//                $this->db->select('*');
//                $this->db->from('international_order_catalog');
//                $this->db->join('user', 'international_order_catalog.shipper_id = user.intUserId');
//                $this->db->join('user_address', 'international_order_catalog.shipper_id = user_address.user_id');
//                $this->db->where('international_order_catalog.order_id',$order_details[0]->order_id);
//                $this->db->order_by("international_order_catalog.id", "desc");
//                $query = $this->db->get()->result();
//                die(var_dump($query));
            $query = $this->db->query('select * from international_order_catalog
                inner join user on user.intUserId  = international_order_catalog.shipper_id
                LEFT JOIN user_address on user_address.user_id = international_order_catalog.shipper_id
                where international_order_catalog.order_id = '.$order_details[0]->order_id.' order by international_order_catalog.id desc')->result();
            $order['shipments'] = $query;
            //$order['shipments'] = $this->db->where('order_id' , $order_details[0]->order_id)->get('international_order_catalog')->result();

            if($order_details[0]->order_delivery_status == 0)
            {
                $order['order_status'] = $this->db->where('id',$order_details[0]->order_status)->get('order_pickup_status')->result()[0]->status;
            }
            else
            {
                $order['order_status'] = $this->db->where('id',$order_details[0]->order_delivery_status)->get('express_delivery_status')->result()[0]->status;
            }
        }

        //die(var_dump($order));
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
        //$order['order'] = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id order by order_details.order_id desc')->result();
        $orders = $this->db->query('select * from order_details,order_receiver,order_airway_bill where order_details.receiver_id = order_receiver.id and order_details.order_id = order_airway_bill.order_id order by order_details.order_id desc')->result();
        $orders_list = array();
        foreach($orders as $order)
        {
            $temp = array();
            $temp['order_tracking_id'] = $order->order_tracking_id;
            $temp['created_on'] = $order->created_on;
            $temp['company_name'] = $order->company_name;
            $temp['airway_bill'] = $order->airway_bill;

            $receiver_country_id = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->country_id  ;
            $sender_country_id = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->country_id ;


            if($receiver_country_id == $sender_country_id || $receiver_country_id == 15)
            {
                if($order->order_delivery_status == 0)
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_delivery_status)->get('domestic_delivery_status')->result()[0]->status;
                }
            }
            else
            {
                if($order->order_delivery_status == 0)
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_delivery_status)->get('express_delivery_status')->result()[0]->status;
                }
            }
            $orders_list[] = $temp;
        }
        $data['order'] = $orders_list;
        // die(var_dump($orders_list));
        $this->load->view('Order/listOrders' ,$data);


    }
    public function updateReceiverAddress()
    {
        $receiver_id = $this->input->post('receiver_id');
        $address = $this->input->post('address');
        $city = $this->input->post('city');
        $mobile = $this->input->post('mobile');

        if($receiver_id != '')
        {
            $receiver = '';
            if($address != '')
                $receiver['address'] = $address;
            if($city != '')
                $receiver['city'] = $city;
            if($mobile != '')
                $receiver['mobile'] = $mobile;

            $this->db->where('id',$receiver_id);
            $this->db->update('order_receiver',$receiver);
        }

        //echo $this->session->userdata['UserType'];
    }
    public function UpdateSender()
    {
        $receiver_account = $this->input->get('edit-id');
        $client_id = $this->input->get('client_id');

        $receiver_details = $this->db->where('account_no' , $receiver_account)->get('order_sender')->result();
        $data['id'] = $receiver_details[0]->id;
        $data['address_line'] = $receiver_details[0]->address_line;
        $data['city'] = $receiver_details[0]->city;
        $data['country_id'] = $receiver_details[0]->country_id;
        $data['address'] = $receiver_details[0]->address;
        $data['postal_code'] = $receiver_details[0]->postal_code;
        $data['email'] = $receiver_details[0]->email;
        $data['name'] = $receiver_details[0]->name;
        $data['mobile'] = $receiver_details[0]->mobile;
        $data['state'] = $receiver_details[0]->state;
        $data['client_id'] = $receiver_details[0]->client_id;


        $data['countries'] = $this->db->get('country_table')->result();
        $this->load->view('Order/create_sender', $data);
    }
    public function UpdateReceiver()
    {
        $receiver_account = $this->input->get('edit-id');
        $client_id = $this->input->get('client_id');

        $receiver_details = $this->db->where('account_no' , $receiver_account)->get('order_receiver')->result();
        $data['id'] = $receiver_details[0]->id;
        $data['address_line'] = $receiver_details[0]->address_line;
        $data['city'] = $receiver_details[0]->city;
        $data['country_id'] = $receiver_details[0]->country_id;
        $data['address'] = $receiver_details[0]->address;
        $data['postal_code'] = $receiver_details[0]->postal_code;
        $data['email'] = $receiver_details[0]->email;
        $data['name'] = $receiver_details[0]->name;
        $data['mobile'] = $receiver_details[0]->mobile;
        $data['company_name'] = $receiver_details[0]->company_name;
        $data['state'] = $receiver_details[0]->state;
        $data['client_id'] = $receiver_details[0]->client_id;


        $data['countries'] = $this->db->get('country_table')->result();
        $this->load->view('Order/create_receiver', $data);
    }
    public function CreateReceiver()
    {
        $data['id'] = $this->input->post('id');
        $data['address_line'] = $this->input->post('address_line');
        $data['city'] = $this->input->post('city');
        $data['country_id'] = $this->input->post('country_id');
        $data['address'] = $this->input->post('address');
        $data['postal_code'] = $this->input->post('postal_code');
        $data['email'] = $this->input->post('email');
        $data['name'] = $this->input->post('name');
        $data['mobile'] = $this->input->post('mobile');
        $data['company_name'] = $this->input->post('company_name');
        $data['state'] = $this->input->post('state');
        $data['add_new_receiver']=$this->input->post('add_new_receiver');
        $data['update_receiver']=$this->input->post('update_receiver');

        $data['client_id'] = $this->input->post('client_id');

        if(isset($data['add_new_receiver']) && $data['add_new_receiver'] == '1') {

            //$this->Zone_model->create($data);
            $receiver['address_line'] = $data['address_line'];
            $receiver['account_no'] = mt_rand();
            $receiver['city'] = $data['city'];
            $receiver['country_id'] = $data['country_id'];
            $receiver['address'] = $data['address'];
            $receiver['postal_code'] = $data['postal_code'];
            $receiver['email'] = $data['email'];
            $receiver['name'] = $data['name'];
            $receiver['company_name'] = $data['company_name'];
            $receiver['state'] = $data['state'];
            $receiver['mobile'] = $data['mobile'];
            $receiver['client_id'] = $data['client_id'];
            //die(var_dump($receiver));
            $this->db->insert('order_receiver',$receiver);
            redirect(base_url().'Order/client?item=receiver');
        }
        else if(isset($data['update_receiver']) && $data['update_receiver'] == '1'){
            //die('HERE');
            //$this->Zone_model->update($data);
            $receiver['address_line'] = $data['address_line'];
            $receiver['city'] = $data['city'];
            $receiver['country_id'] = $data['country_id'];
            $receiver['address'] = $data['address'];
            $receiver['postal_code'] = $data['postal_code'];
            $receiver['email'] = $data['email'];
            $receiver['name'] = $data['name'];
            $receiver['company_name'] = $data['company_name'];
            $receiver['state'] = $data['state'];
            $receiver['mobile'] = $data['mobile'];

            $this->db->where('id',$data['id']);
            $this->db->update('order_receiver',$receiver);

            redirect(base_url().'Order/client?item=receiver');

        }
        else {
            $data['countries'] = $this->db->get('country_table')->result();
            $this->load->view('Order/create_receiver', $data);
        }


    }
    public function CreateSender()
    {
        $data['id'] = $this->input->post('id');
        $data['address_line'] = $this->input->post('address_line');
        $data['city'] = $this->input->post('city');
        $data['country_id'] = $this->input->post('country_id');
        $data['address'] = $this->input->post('address');
        $data['postal_code'] = $this->input->post('postal_code');
        $data['email'] = $this->input->post('email');
        $data['name'] = $this->input->post('name');
        $data['mobile'] = $this->input->post('mobile');
        $data['state'] = $this->input->post('state');
        $data['add_new_receiver']=$this->input->post('add_new_receiver');
        $data['update_receiver']=$this->input->post('update_receiver');

        $data['client_id'] = $this->input->post('client_id');

        if(isset($data['add_new_receiver']) && $data['add_new_receiver'] == '1') {

            //$this->Zone_model->create($data);
            $receiver['address_line'] = $data['address_line'];
            $receiver['account_no'] = mt_rand();
            $receiver['city'] = $data['city'];
            $receiver['country_id'] = $data['country_id'];
            $receiver['address'] = $data['address'];
            $receiver['postal_code'] = $data['postal_code'];
            $receiver['email'] = $data['email'];
            $receiver['name'] = $data['name'];
            $receiver['state'] = $data['state'];
            $receiver['mobile'] = $data['mobile'];
            $receiver['client_id'] = $data['client_id'];
            //die(var_dump($receiver));
            $this->db->insert('order_sender',$receiver);
            redirect(base_url().'Order/client?item=sender');
        }
        else if(isset($data['update_receiver']) && $data['update_receiver'] == '1'){
            //die('HERE');
            //$this->Zone_model->update($data);
            $receiver['address_line'] = $data['address_line'];
            $receiver['city'] = $data['city'];
            $receiver['country_id'] = $data['country_id'];
            $receiver['address'] = $data['address'];
            $receiver['postal_code'] = $data['postal_code'];
            $receiver['email'] = $data['email'];
            $receiver['name'] = $data['name'];
            $receiver['state'] = $data['state'];
            $receiver['mobile'] = $data['mobile'];

            $this->db->where('id',$data['id']);
            $this->db->update('order_sender',$receiver);

            redirect(base_url().'Order/client?item=sender');

        }
        else {
            $data['countries'] = $this->db->get('country_table')->result();
            $this->load->view('Order/create_sender', $data);
        }


    }
    public function sender()
    {
        $client_id = $this->input->post('client_id');
        if($client_id == '')
            $client_id = $this->input->get('client_id');


        if(isset($client_id))
        {
            $data['senders'] = $this->db->where('client_id',$client_id)->get('order_sender')->result();
//                echo $receivers;
            $data['client_id'] = $client_id;
            $this->load->view('Order/view_sender' , $data);
        }
    }
    public function receiver()
    {

        $client_id = $this->input->post('client_id');
        if($client_id == '')
            $client_id = $this->input->get('client_id');
        if(isset($client_id))
        {
            $data['receivers'] = $this->db->where('client_id',$client_id)->get('order_receiver')->result();
//                echo $receivers;
            $data['client_id'] = $client_id;
            $this->load->view('Order/view_receiver' , $data);
        }
    }
    public function client()
    {
        $item = $this->input->get('item');
        if($this->session->userdata['UserType'] == 'Client')
        {
            $client_id = $this->Client_model->getClientDetailsByEmail($this->session->userdata['email']);
            if($item == 'sender')
            {
                redirect(base_url().'order/sender?client_id='.$client_id[0]->client_id);
            }
            else
            {
                redirect(base_url().'order/receiver?client_id='.$client_id[0]->client_id);
            }
        }
        else
        {

            if($item == 'sender')
            {
                $data['action'] = 'order/sender';
                $data['text'] = 'Sender Managment';

            }
            else
            {
                $data['action'] = 'order/receiver';
                $data['text'] = 'Sender Managment';
            }
            $data['clients'] = $this->Client_model->getActiveClients();
            $this->load->view('Order/view_selectClient' , $data);
        }


    }
    public function orderHistory()
    {

        $awb = $this->input->post('AWB_id');
        $tracking_num = $this->input->post('tracking_number');
        $sender_name = $this->input->post('sender_name');
        $receiver_name  = $this->input->post('receiver_name');
        $status = $this->input->post('status');


        //echo $awb;
//        die('HERE');

        $orders = $this->db->query('select order_details.* ,order_receiver.* ,order_airway_bill.* ,order_sender.name as sender_name from order_details,order_receiver,order_airway_bill,order_sender where order_details.receiver_id = order_receiver.id and order_details.sender_id = order_sender.id and order_details.order_id = order_airway_bill.order_id order by order_details.order_id desc ')->result();
        $orders_list = array();
        foreach($orders as $order)
        {
            $temp = array();
            $temp['order_tracking_id'] = $order->order_tracking_id;
            $temp['created_on'] = $order->created_on;
            $temp['company_name'] = $order->company_name;
            $temp['airway_bill'] = $order->airway_bill;
            $temp['receiver_name'] = $order->name;
            $temp['sender_name'] = $order->sender_name;

            $receiver_country_id = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->country_id  ;
            $sender_country_id = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->country_id ;


            if($receiver_country_id == $sender_country_id || $receiver_country_id == 15)
            {
                if($order->order_delivery_status == 0)
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_delivery_status)->get('domestic_delivery_status')->result()[0]->status;
                }
            }
            else
            {
                if($order->order_delivery_status == 0)
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $temp['order_status'] = $this->db->where('id',$order->order_delivery_status)->get('express_delivery_status')->result()[0]->status;
                }
            }
            //$orders_list[] = $temp;

            if($awb != '' && $awb == $temp['airway_bill'])
            {
                //      echo '1';
                $orders_list[] = $temp;
            }
            else if($tracking_num != '' && $tracking_num == $temp['order_tracking_id'])
            {
                $orders_list[] = $temp;
            }
            else if($sender_name != '' && $sender_name == $temp['sender_name'])
            {
                $orders_list[] = $temp;
            }
            else if($receiver_name != '' && $receiver_name == $temp['receiver_name'])
            {
                $orders_list[] = $temp;
            }
            else if($status != '' && $status == $temp['order_status'])
            {
                $orders_list[] = $temp;
            }
            else if($awb == '' && $tracking_num == '' && $sender_name == '' && $receiver_name == '' && $status == '')
            {
                $orders_list[] = $temp;
            }





        }
        $data['orders'] = $orders_list;
        //die(var_dump($orders_list));
        // $this->load->view('Order/listOrders' ,$data);
        $this->load->view('Order/order_history_view', $data);
    }
    public function generateHistoryReport()
    {
        define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        //var_dump($_POST);
        /*        $itr = 0;
                $workbook = new Spreadsheet_Excel_Writer();

                $workbook = new Spreadsheet_Excel_Writer(APPPATH.'../uploads/'.'HistoryReport.xls');

                $worksheet = $workbook->addWorksheet('Order History Report');

                $worksheet->write(0, 0, 'Order #');
                $worksheet->write(0, 1, 'Tracking Number');
                $worksheet->write(0, 2, 'Sender Name');
                $worksheet->write(0, 3, 'Receiver Name');
                $worksheet->write(0, 4, 'Status');
                $worksheet->write(0, 5, 'Order Details');
         */

        $objPHPExcel = new PHPExcel();
        $x = 1;
        $y = 0;




        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getActiveSheet()->setTitle('Simple');

        $objPHPExcel->setActiveSheetIndex(0);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','Order #');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','Tracking Number');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','Sender Name');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','Receiver Name');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','Status');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','Order Details');




        while ($this->input->post('row_' . $itr) != null)
        {
            $row = $this->input->post('row_' . $itr);

            $splited_data = explode(':', $row[0]);

            $airway_bil = $splited_data[0] ;
            $tracking_id =  $splited_data[1] ;
            $sender_name =  $splited_data[2] ;
            $receiver_name =  $splited_data[3] ;
            $status =  $splited_data[4] ;

//            die($tracking_id);
//
//
//            $tracking_id = $this->input->get('ref-id');
            $order_details = $this->db->where('order_tracking_id' , $tracking_id)->get('order_details')->result();
            $sender_details = $this->db->where('id' , $order_details[0]->sender_id)->get('order_sender')->result();
            $receiver_details = $this->db->where('id' , $order_details[0]->receiver_id)->get('order_receiver')->result();
            $consignment_detail = $this->db->where('id' , $order_details[0]->consignment_id)->get('consignment_details')->result();
            $contact_person = $this->db->where('person_id' , $order_details[0]->contact_person_id)->get('order_contact_person')->result();
            $airway_bill = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_airway_bill')->result();
            $order_payments = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_payments')->result();
            $order_delivery_schedule = $this->db->where('order_id' , $order_details[0]->order_id)->get('order_delivery_schedule')->result();
            $order['order_details'] = $order_details;
            $order['order_sender'] = $sender_details;
            $order['value'] = $order_details[0]->value;
            $order['billing_type'] = $this->db->where('id',$order_details[0]->billing_type)->get('billing_types')->result()[0]->billing_type; //;
            $order['payment_to_collect'] = $order_details[0]->payment_to_collect;
            $order['order_receiver'] = $receiver_details;
            $order['order_consignment'] = $consignment_detail;
            $order['order_contact'] = $contact_person;
            $order['order_airway'] = $airway_bill;
            $order['order_pickup_status'] = $this->db->where('id' , $order_details[0]->order_status)->get('order_pickup_status')->result()[0]->status;
            $order['order_payments'] = $order_payments;
            $order['payment_type'] = $this->db->where('id' , $order_details[0]->payment_type_id)->get('payment_types')->result();
            $order['receiver_country'] = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result();
            $order['sender_country'] = $this->db->where('id' , $sender_details[0]->country_id)->get('country_table')->result();
            $order['receiver_country'] = $this->db->where('id' , $receiver_details[0]->country_id)->get('country_table')->result();
            $order['order_type'] = $this->db->where('id' , $consignment_detail[0]->type)->get('order_types')->result();
            $order['comments'] = $this->db->query('SELECT * FROM order_comments , user where order_comments.user_id = user.intUserId and order_comments.order_id = '.$order_details[0]->order_id.' order by order_comments.id desc')->result();
            $order['order_delivery_schedule'] = $order_delivery_schedule;
            //              echo $sender_details[0]->country_id;
            //            echo 'hi';
            //            echo $receiver_details[0]->country_id;
            //
            //die($receiver_details[0]->country_id.'');
            if($sender_details[0]->country_id == $receiver_details[0]->country_id || $receiver_details[0]->country_id == 15)
            {
                $query = $this->db->query('select * from domestic_order_catalog
                    inner join user on user.intUserId  = domestic_order_catalog.shipper_id
                    LEFT JOIN user_address on user_address.user_id = domestic_order_catalog.shipper_id
                    where domestic_order_catalog.order_id = '.$order_details[0]->order_id.' order by domestic_order_catalog.id desc')->result();
                $order['shipments'] = $query;

                if($order_details[0]->order_delivery_status == 0)
                {
                    $order['order_status'] = $this->db->where('id',$order_details[0]->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $order['order_status'] = $this->db->where('id',$order_details[0]->order_delivery_status)->get('domestic_delivery_status')->result()[0]->status;
                }


            }
            else
            {
                $query = $this->db->query('select * from international_order_catalog
                    inner join user on user.intUserId  = international_order_catalog.shipper_id
                    LEFT JOIN user_address on user_address.user_id = international_order_catalog.shipper_id
                    where international_order_catalog.order_id = '.$order_details[0]->order_id.' order by international_order_catalog.id desc')->result();
                $order['shipments'] = $query;
                //$order['shipments'] = $this->db->where('order_id' , $order_details[0]->order_id)->get('international_order_catalog')->result();

                if($order_details[0]->order_delivery_status == 0)
                {
                    $order['order_status'] = $this->db->where('id',$order_details[0]->order_status)->get('order_pickup_status')->result()[0]->status;
                }
                else
                {
                    $order['order_status'] = $this->db->where('id',$order_details[0]->order_delivery_status)->get('express_delivery_status')->result()[0]->status;
                }
            }

            // We give the path to our file here
            $order_detail = $order_detail . '';
            $order_detail = $order_detail . 'Sender Details';
            $order_detail = $order_detail . "\r\n";
            $order_detail = $order_detail . 'Name' .' : '.$sender_details[0]->name . "\r\n";
            $order_detail = $order_detail . 'Email' .' : '.$sender_details[0]->email . "\r\n";
            $order_detail = $order_detail . 'Mobile' .' : '.$sender_details[0]->mobile . "\r\n";
            $order_detail = $order_detail . 'Address' .' : '.$sender_details[0]->address . "\r\n";
            $order_detail = $order_detail . 'Country' .' : '.$order['sender_country'][0]->country_name . "\r\n";

            $order_detail = $order_detail . 'Receiver Details';
            $order_detail = $order_detail . "\r\n";
            $order_detail = $order_detail . 'Name' .' : '.$receiver_details[0]->name . "\r\n";
            $order_detail = $order_detail . 'Company' .' : '.$receiver_details[0]->company_name . "\r\n";
            $order_detail = $order_detail . 'Email' .' : '.$receiver_details[0]->email . "\r\n";
            $order_detail = $order_detail . 'Mobile' .' : '.$receiver_details[0]->mobile . "\r\n";
            $order_detail = $order_detail . 'Address' .' : '.$receiver_details[0]->address . "\r\n";
            $order_detail = $order_detail . 'Country' .' : '.$order['receiver_country'][0]->country_name . "\r\n";
            $order_detail = $order_detail . 'Country' .' : '.$order['receiver_country'][0]->country_name . "\r\n";

            $order_detail = $order_detail . 'Consignment Details';
            $order_detail = $order_detail . "\r\n";
            $order_detail = $order_detail . 'Consignment title' .' : '.$consignment_detail[0]->title . "\r\n";
            $order_detail = $order_detail . 'Consignment description' .' : '.$consignment_detail[0]->description . "\r\n";
            $order_detail = $order_detail . 'Packages' .' : '.$consignment_detail[0]->no_of_packages . "\r\n";
            $order_detail = $order_detail . 'Weigth' .' : '.$consignment_detail[0]->weight . "\r\n";

            //echo $order_detail;


            //        $worksheet->write($x, $y, $airway_bil);
            //        $worksheet->write($x, $y + 1, $tracking_id);
            //        $worksheet->write($x, $y + 2, $sender_name);
            //        $worksheet->write($x, $y + 3, $receiver_name);
            //        $worksheet->write($x, $y + 4, $status);
            //        $worksheet->write($x, $y + 5, $order_detail);
//
            $x = $x + 1;
            // We still need to explicitly close the workbook




            $itr = $itr + 1;
        }
        // Save Excel 2007 file
        echo date('H:i:s') , " Write to Excel2007 format" , EOL;
        $callStartTime = microtime(true);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
        $callEndTime = microtime(true);
        $callTime = $callEndTime - $callStartTime;

        echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
        echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
// Echo memory usage
        echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;

        //    $workbook->close();
//
//        ob_start();
//        ob_get_clean();

        // Write the Excel file to filename some_excel_file.xlsx in the current directory
        //  $objWriter->save(APPPATH."../uploads/HistoryReport.xls");

//        header( "Content-Type: application/vnd.ms-excel" );
//        header( "Content-disposition: attachment; filename=spreadsheet.xlsx" );
//        readfile(APPPATH."../uploads/HistoryReport.xlsx");
        //ob_clean();


        #echo date('H:i:s') . " Write to Excel2007 format\n";



//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="01simple.xlsx"');
//header('Cache-Control: max-age=0');
//       // header('Content-Length: ' . filesize(APPPATH."../uploads/HistoryReport.xls"));
//        //header('Content-Transfer-Encoding: binary');
//    //    header('Cache-Control: must-revalidate');
//      //  header('Pragma: public');
//        //readfile(APPPATH."../uploads/HistoryReport.xls");
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//        ob_end_clean();
//        $objWriter->save('php://output');
//        ob_end_clean();
// We'll be outputting an excel file
        /* $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
         //ob_end_flush();
         header('Content-type: application/vnd.ms-excel');
         header('Content-Disposition: attachment; filename="payroll.xlsx"');
         $objWriter->save('php://output');
         //

         exit ;
         */



    }
    public function getPayments()
    {
        $client_id = $this->input->post('client_id');
        $AWB_id = $this->input->post('AWB_id');
        $country = $this->input->post('country');
        $sender_name = $this->input->post('sender_name');
        $receiver_name = $this->input->post('receiver_name');
        $order_price_from = $this->input->post('order_price_from');
        $order_price_to = $this->input->post('order_price_to');
        $days_balance_to = $this->input->post('days_balance_to');
        $days_balance_from = $this->input->post('days_balance_from');
        $order_date_to = $this->input->post('order_date_to');
        $order_date_from = $this->input->post('order_date_from');
        $payment_due_from = $this->input->post('payment_due_from');
        $payment_due_to = $this->input->post('payment_due_to');
        $paid_date_from = $this->input->post('paid_date_from');
        $paid_date_to = $this->input->post('paid_date_to');
        $order_status = $this->input->post('order_status');
        $status = $this->input->post('status');

        $response = $this->getDetails($client_id, $AWB_id , $country , $sender_name , $receiver_name , $order_price_from , $order_price_to , $days_balance_from , $days_balance_to , $order_date_from , $order_date_to , $payment_due_from , $payment_due_to , $paid_date_from , $paid_date_to , $order_status , $status);


        $html_body = '';
        foreach($response as $res)
        {
            $html_body = $html_body . '<tr><td></td>';
            $html_body = $html_body . '<td>'.$res['order_AWB'].'</td>';
            $html_body = $html_body . '<td>'.$res['order_date'].'</td>';
            $html_body = $html_body . '<td>'.$res['receiver_country'].'</td>';
            $html_body = $html_body . '<td>'.$res['sender_name'].'</td>';
            $html_body = $html_body . '<td>'.$res['receiver_name'].'</td>';
            $html_body = $html_body . '<td>'.$res['bill_amount'].'</td>';
            $html_body = $html_body . '<td>'.$res['payment_due_date'].'</td>';
            if($res['status'] != 'Paid')
            {
                $html_body = $html_body . '<td>'.$res['days_balance'].'</td>';
            }
            else
            {
                $html_body = $html_body . '<td></td>';
            }

            $html_body = $html_body . '<td>'.$res['paid_date'].'</td>';
            if($res['status'] == 'Due')
            {
                $html_body = $html_body . '<td><span class="label label-sm label-primary">Due</span></td>';
            }
            else if($res['status'] == 'Over Due')
            {
                $html_body = $html_body . '<td><span class="label label-sm label-danger">Over Due</span></td>';
            }
            else if($res['status'] == 'Paid')
            {
                $html_body = $html_body . '<td><span class="label label-sm label-success">Paid</span></td>';
            }
            if($this->session->userdata['UserType'] != 'Client')
            {
                $html_body = $html_body . '<td><input class="btn purple btn-outline btn-block mark_paid" value="Mark Paid" type="button"></td>';
            }
            $html_body = $html_body . '</tr>';
        }


        //echo json_encode($html_body);
        echo $html_body;
    }
    public function InsertComment()
    {
        $coment = $this->input->post('comment');
        $order_id = $this->input->post('order_id');
        $user_email = $this->session->userdata['email'];

        if($coment != '' && $order_id != '' && $user_email != '')
        {
            $comment['user_id'] = $this->db->where('varEmailId' ,$user_email )->get('user')->result()[0]->intUserId;
            $comment['created_on'] = date('Y-m-d H:i:s');
            $comment['comment'] = $coment;
            $comment['order_id'] = $this->db->where('airway_bill' , $order_id)->get('order_airway_bill')->result()[0]->order_id;


            $this->db->insert('order_comments' ,$comment );


            $comments_details = $this->db->query('SELECT * FROM order_comments , user where order_comments.user_id = user.intUserId and order_comments.order_id = '.$comment['order_id'].' order by order_comments.id desc')->result();
            echo json_encode($comments_details);

        }
    }
    public function markPaid()
    {
        $airwayBill = $this->input->post('airwayBill');
        if(isset($airwayBill))
        {
            $order_id = $this->db->where('airway_bill' , $airwayBill)->get('order_airway_bill')->result()[0]->order_id;
            $order_payments = $this->db->where('order_id' , $order_id)->get('order_payments')->result();
            if($order_payments[0]->paid_date == '')
            {
                $payment['paid_date'] = date('Y-m-d');
                $payment['payment_status'] = '1';
                $this->db->where('order_id',$order_id);
                $this->db->update('order_payments',$payment);

                $response['paid_date'] = $payment['paid_date'];
                $response['status'] = 1;

                echo json_encode($response);

            }
        }
    }
    public function payments()
    {
        //die('HERE');

        $search = $this->input->post('search');

        if(isset($search))
        {

            $result = $this->db->query('SELECT * from client_table where company_name like  "%'.$search.'%" or account_number like "%'.$search.'%"')->result();
            //die(var_dump($result));
            if(isset($result[0]->client_id))
            {

                //die(var_dump($formatted_orders));
                //$view_orders['orders'] = $formatted_orders;
                $view_orders['orders'] = $this->getDetails($result[0]->client_id , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');
                $view_orders['client_id'] = $result[0]->client_id;

                $this->load->view('Order/viewPaymentTable',$view_orders);
            }
        }
        else if($this->session->userdata['UserType'] == 'Client')
        {
            $client_id = $this->Client_model->getClientDetailsByEmail($this->session->userdata['email']);

            $view_orders['orders'] = $this->getDetails($client_id[0]->client_id , '' ,'' , '' ,  '' , '' , '','' , '', '' , '' , '' , '' , '' , '' , '' , '');
            $view_orders['client_id'] = $client_id[0]->client_id;

            $this->load->view('Order/viewPaymentTable',$view_orders);
        }
        else
        {

            $this->load->view('Order/viewPaymentTable');
        }

    }
    public function getDetailsUpdated($client_id , $AWB , $country , $sender_name , $receiver_name , $billAmountFrom , $billAmountTo , $days_balance_from , $days_balance_to ,  $order_date_from , $order_date_to , $payment_due_from , $payment_due_to , $paid_date_from , $paid_date_to , $order_status , $status)
    {
        $result = $this->db->where('client_id',$client_id)->get('client_table')->result();

        $client_id = $result[0]->client_id;

        $credit_days = $result[0]->credit_days;
        //$orders['orders'] = $this->db->where('client_id',$result[0]->client_id)->get('order_details')->result();
        $orders = $this->db->query('select * from order_details , order_payments , order_airway_bill where order_payments.order_id = order_details.order_id and order_airway_bill.order_id = order_details.order_id and order_details.client_id = '.$result[0]->client_id)->result();

        $formatted_orders = array();

        foreach($orders as $order)
        {
            $view_items['order_AWB'] = $order->airway_bill;
            $view_items['order_date'] = $order->created_on;
            $country_id = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->country_id;
            $country_id2 = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->country_id;
            $view_items['receiver_country'] = $this->db->where('id',$country_id)->get('country_table')->result()[0]->country_name;
            $view_items['sender_country'] = $this->db->where('id',$country_id2)->get('country_table')->result()[0]->country_name;
            $view_items['sender_name'] = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->name;
            $view_items['receiver_name'] = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->name;
            $view_items['bill_amount'] = $order->payable_amount;
            $view_items['payment_due_date'] = date('Y-m-d', strtotime('+'.$credit_days.' days '. $order->created_on)) ;

            $date1=date_create(date('Y-m-d'));
            $date2=date_create($view_items['payment_due_date']);
            $diff=date_diff($date1,$date2);

            $day_sign =  $diff->format("%R");
            $days =  $diff->format("%a");
            $view_items['days_balance'] =  $diff->format("%R%a");

            if($order->paid_date != '')
            {
                $view_items['paid_date'] = explode(' ', $order->paid_date)[0];
            }
            else
            {
                $view_items['paid_date'] = $order->paid_date;
            }
            if ($order->paid_date == '')
            {
                if($day_sign == '+')
                    $view_items['status'] = 'Due';
                else
                    $view_items['status'] = 'Over Due';

                $view_items['paid_date'] = '';
            }
            else
            {
                $view_items['status'] = 'Paid';
            }

            if($AWB != '' && $AWB == $order->airway_bill)
            {
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            $formatted_orders[] = $view_items;

        }
        return $formatted_orders;
    }
    public function getDetails($client_id , $AWB , $country , $sender_name , $receiver_name , $billAmountFrom , $billAmountTo , $days_balance_from , $days_balance_to ,  $order_date_from , $order_date_to , $payment_due_from , $payment_due_to , $paid_date_from , $paid_date_to , $order_status , $status)
    {
        $result = $this->db->where('client_id',$client_id)->get('client_table')->result();

        $client_id = $result[0]->client_id;

        $credit_days = $result[0]->credit_days;
        //$orders['orders'] = $this->db->where('client_id',$result[0]->client_id)->get('order_details')->result();
        $orders = $this->db->query('select * from order_details , order_payments , order_airway_bill where order_payments.order_id = order_details.order_id and order_airway_bill.order_id = order_details.order_id and order_details.client_id = '.$result[0]->client_id)->result();

        $formatted_orders = array();

        //die(var_dump($orders));

        foreach($orders as $order)
        {
            $view_items['order_AWB'] = $order->airway_bill;
            $view_items['order_date'] = $order->created_on;
            $country_id = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->country_id;
            $country_id2 = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->country_id;
            //echo $country_id.' HRE  ';

            $view_items['receiver_country'] = $this->db->where('id',$country_id)->get('country_table')->result()[0]->country_name;
            $view_items['sender_country'] = $this->db->where('id',$country_id2)->get('country_table')->result()[0]->country_name;

            $view_items['sender_name'] = $this->db->where('id',$order->sender_id)->get('order_sender')->result()[0]->name;
            $view_items['receiver_name'] = $this->db->where('id',$order->receiver_id)->get('order_receiver')->result()[0]->name;
            $view_items['bill_amount'] = $order->payable_amount;
            $view_items['payment_due_date'] = date('Y-m-d', strtotime('+'.$credit_days.' days '. $order->created_on)) ;
            $view_items['order_tracking_id'] = $order->order_tracking_id;
            // die(var_dump($order));


            $date1=date_create(date('Y-m-d'));
            $date2=date_create($view_items['payment_due_date']);
            $diff=date_diff($date1,$date2);

            $day_sign =  $diff->format("%R");
            $days =  $diff->format("%a");
            $view_items['days_balance'] =  $diff->format("%R%a");


            if($order->paid_date != '')
            {
                $view_items['paid_date'] = explode(' ', $order->paid_date)[0];
            }
            else
            {
                $view_items['paid_date'] = $order->paid_date;
            }
            if ($order->paid_date == '')
            {
                if($day_sign == '+')
                    $view_items['status'] = 'Due';
                else
                    $view_items['status'] = 'Over Due';

                $view_items['paid_date'] = '';
            }
            else
            {
                $view_items['status'] = 'Paid';
            }

            //die(var_dump($view_items));
            if($AWB != '' && $AWB == $order->airway_bill)
            {
                // die('HERE');
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($order_status != '' && $order_status == $view_items['status'])
            {
                //  die('HERE2');
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if(($billAmountFrom != '' || $billAmountTo != ''))
            {
                //   die('HERE3');
                if($billAmountFrom == '')
                    $billAmountFrom = 0;
                else if($billAmountTo == '')
                    $billAmountTo = 10000000;

                if($view_items['bill_amount'] <= $billAmountTo && $view_items['bill_amount'] >= $billAmountFrom)
                {
                    if($status != '' )
                    {
                        if($status == 2)
                        {
                            if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 3)
                        {
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 4)
                        {
                            if($order->order_status == 5)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 5)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status == 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 6)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status != 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status != 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
            }

            if(($days_balance_from != '' || $days_balance_to != ''))
            {
                //     die('HERE4');
                if($days_balance_from == '')
                    $days_balance_from = 0;
                else if($days_balance_to == '')
                    $days_balance_to = 10000000;

                if($days <= $days_balance_to && $days >= $days_balance_from)
                {
                    if($status != '' )
                    {
                        if($status == 2)
                        {
                            if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 3)
                        {
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 4)
                        {
                            if($order->order_status == 5)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else if($status == 5)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status == 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status == 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else if($status == 6)
                        {
                            // delivered
                            if($view_items['receiver_country'] != $view_items['sender_country'])
                            {
                                if($order->order_delivery_status != 13)
                                {
                                    $formatted_orders[] = $view_items;
                                }

                            }
                            else
                            {
                                if($order->order_delivery_status != 7)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                // $formatted_orders[] = $view_items;
            }
            //else if($country != '' && $country == $view_items['receiver_country'])
            if($country != '' && strpos (strtolower ( $view_items['receiver_country']), strtolower($country))  !== false )
            {
                //    die('HERE5');
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($receiver_name != '' && strpos (strtolower($view_items['receiver_name']), strtolower($receiver_name))  !== false )
            {
                //   die('HERE6');
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($sender_name != '' && strpos (strtolower ( $view_items['sender_name']),strtolower($sender_name))  !== false )
            {

                //die(var_dump($formatted_orders));
                if($status != '' )
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 1)
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

            if($order_date_from != '' || $order_date_to != '')
            {

                //   die('HERE7');
                if($order_date_from == '')
                {
                    if(new DateTime(explode(' ', $order->created_on)[0])  <= new DateTime($order_date_to) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                }
                else if($order_date_to == '')
                {
                    if(new DateTime(explode(' ', $order->created_on)[0]) >= new DateTime($order_date_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                }
                else
                {
                    if(new DateTime(explode(' ', $order->created_on)[0]) <= new DateTime($order_date_to) && new DateTime(explode(' ', $order->created_on)[0]) >= new DateTime($order_date_from) )
                    {
                        //$formatted_orders[] = $view_items;
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                }
            }

            if($payment_due_from != '' || $payment_due_to != '')
            {


                if($payment_due_from == '')
                {
                    if(new DateTime(explode(' ', $view_items['payment_due_date'])[0])  <= new DateTime($payment_due_to) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else if($payment_due_to == '')
                {
                    if(new DateTime(explode(' ', $view_items['payment_due_date'])[0]) >= new DateTime($payment_due_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else
                {
                    if(new DateTime(explode(' ', $view_items['payment_due_date'])[0]) <= new DateTime($payment_due_to) && new DateTime(explode(' ', $view_items['payment_due_date'])[0]) >= new DateTime($payment_due_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
            }
            else if(($paid_date_from != '' || $paid_date_to != '' ) && $view_items['paid_date'] != '' )
            {


                if($paid_date_from == '')
                {
                    if(new DateTime(explode(' ', $view_items['paid_date'])[0])  <= new DateTime($paid_date_to) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else if($paid_date_to == '')
                {
                    if(new DateTime(explode(' ', $view_items['paid_date'])[0]) >= new DateTime($paid_date_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
                else
                {
                    if(new DateTime(explode(' ', $view_items['paid_date'])[0]) <= new DateTime($paid_date_to) && new DateTime(explode(' ', $view_items['paid_date'])[0]) >= new DateTime($paid_date_from) )
                    {
                        if($status != '' )
                        {
                            if($status == 2)
                            {
                                if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 3)
                            {
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 3)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 4)
                            {
                                if($order->order_status == 5)
                                {
                                    $formatted_orders[] = $view_items;
                                }
                            }
                            else if($status == 5)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status == 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status == 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 6)
                            {
                                // delivered
                                if($view_items['receiver_country'] != $view_items['sender_country'])
                                {
                                    if($order->order_delivery_status != 13)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }

                                }
                                else
                                {
                                    if($order->order_delivery_status != 7)
                                    {
                                        $formatted_orders[] = $view_items;
                                    }
                                }
                            }
                            else if($status == 1)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                        else
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    //   $formatted_orders[] = $view_items;
                }
            }
            // payment_due_from
            if($AWB == '' && $country == '' && $sender_name == '' && $receiver_name == '' && $days_balance_from == '' && $days_balance_to == '' && $billAmountTo == '' && $billAmountFrom == '' && $order_date_to == '' && $order_date_from == '' && $paid_date_from == '' && $paid_date_to == '' && $payment_due_to == '' && $payment_due_from == '' && $order_status == '')
            {
                //die('HERE9');
                if($status != '')
                {
                    if($status == 2)
                    {
                        if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 3)
                    {
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 3)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 4)
                    {
                        if($order->order_status == 5)
                        {
                            $formatted_orders[] = $view_items;
                        }
                    }
                    else if($status == 5)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status == 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status == 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 6)
                    {
                        // delivered
                        if($view_items['receiver_country'] != $view_items['sender_country'])
                        {
                            if($order->order_delivery_status != 13)
                            {
                                $formatted_orders[] = $view_items;
                            }

                        }
                        else
                        {
                            if($order->order_delivery_status != 7)
                            {
                                $formatted_orders[] = $view_items;
                            }
                        }
                    }
                    else if($status == 1)
                    {
                        $formatted_orders[] = $view_items;
                    }
                }
                else
                {
                    $formatted_orders[] = $view_items;
                }
            }

        }

        return $formatted_orders;
    }

    public function uploadOrderProof(){

        $orderId                    = $_POST['orderId'];
        $airway_bill                = $this->db->where('order_id' , $orderId)->get('order_airway_bill')->result();
        $fileNewName                = '00000'.substr($airway_bill[0]->airway_bill, 5);
        if(isset($_FILES['orderProof']) && !empty($_FILES['orderProof'])){
            $fileName                           = $_FILES['orderProof']['name'];
            $fileExtension                      = explode('.',$fileName);
            $_FILES['orderProof']['name']       = $fileNewName.'.'.end($fileExtension);
        }
        $config['upload_path']          = realpath('.').'/OrderPictres';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1000;
        $config['max_width']            = 2000;
        $config['max_height']           = 2000;
        $config['overwrite']            = TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('orderProof')) {
            //echo "<pre>"; print_r(array('error' => $this->upload->display_errors())); exit;
            echo "File Not Upload <a href='".CTRL."Order/view_order?ref-id=".$_POST['orderTrackingId']."'> Go back </a>";
        }
        else {
            redirect(CTRL.'Order/view_order?ref-id='.$_POST['orderTrackingId']);
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */