<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ClientManagement extends CI_Controller {

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
        $this->load->model('Zone_model');
        $this->load->model('Client_model');
        $this->load->model('Country_model');
        $this->load->model('Client_rates_model');
        $this->load->model('Client_type');
        $this->load->library('Notification_lib');
        $this->load->library('encrypt');

        $this->load->helper('cookie');			 

	}
	 
	public function index()
	{
        $this->load->view('backend');
	}

    public  function create()
    {
        $data['client_id']=$this->input->post('client_id');
        $data['company_name']=$this->input->post('company_name');
        $data['company_website']=$this->input->post('company_website');
        $data['email']=$this->input->post('email');
        $data['address']=$this->input->post('address');
        $data['city']=$this->input->post('city');
        $data['phone_no']=$this->input->post('phone_no');
        $data['country_id']=$this->input->post('country_id');
        $data['update_client']=$this->input->post('update_client');
        $data['add_new_client']=$this->input->post('add_new_client');
        $data['countries']=$this->Country_model->get_countries();
        //$data['domestic_rate']=$this->input->post('domestic_rate');
        $data['level_id']=$this->Client_type->getLevelid($this->input->post('client_type'));

        //$data['level_id']='1';



//
        if(isset($data['add_new_client']) && $data['add_new_client'] == '1') {

            $this->Client_model->create($data);
            redirect(base_url().'dashboard/client');
        }
        else if(isset($data['update_client']) && $data['update_client'] == '1'){
            //die(print_r($_POST));
            if($this->input->post('client_type') != 'CONTACT')
            {
                $data['domestic_rates']=$this->input->post('domestic_rate');

                $zones=$this->input->post('zone');
                $zone_id=$this->input->post('zone_id');
                $counter = 0;
                foreach($zones as $zone)
                {
                    $client['client_id'] = $this->input->post('client_id');
                    $client['zone_id'] = $zone_id[$counter];
                    $client['zone_rate'] = $zone;

                    $this->Client_rates_model->update_rates($client);


                    $counter = $counter + 1;
                }
                $primary_user['first_name'] = $this->input->post('primary_first_name');
                $primary_user['last_name'] = $this->input->post('primary_last_name');
                $primary_user['address'] = $this->input->post('primary_address');
                $primary_user['email'] = $this->input->post('primary_email');
                $primary_user['phone_no'] = $this->input->post('primary_phone_no');
                $this->db->where('client_id' , $this->input->post('client_id'));
                $this->db->update('client_contact_primary' , $primary_user);

                if($this->input->post('client_type') != 'PROSPECT')
                {
                    $user['varFirstName'] = $this->input->post('company_name');
                    $user['varLastName'] = $this->input->post('');
                    $user['varMobileNo'] = $this->input->post('phone_no');
                    $user['country_id'] = $this->input->post('country_id');

                    $this->db->where('varEmailId' , $this->input->post('email'));
                    $this->db->update('user' , $user);
                }

            }
            $this->Client_model->update($data);
            redirect(base_url().'dashboard/client');

        }
        else {
            $data['level_id'] = 'CONTACT';
            $this->load->view('admin/create', $data);
        }
    }
    public function update()
    {
        $client_id = $this->input->get('edit-id');
        $result = $this->Client_model->getClientDetails($client_id);
        $data['company_name']=$result[0]->company_name;
        $data['company_website']=$result[0]->company_website;
        $data['email']=$result[0]->email;
        $data['phone_no']=$result[0]->phone_no;
        $data['address']=$result[0]->address;
        $data['city']=$result[0]->city;
        $data['country_id']=$result[0]->country_id;
        $data['level_id']=$result[0]->level_name;
        if($data['level_id'] != 'CONTACT')
        {
            $data['domestic_rate'] = $result[0]->domestic_rates;
            //die(var_dump($this->Client_rates_model->getClientRates($client_id)));
            $data['zone_rates'] = $this->Client_rates_model->getClientRates($client_id);
            $primary_user = $this->Client_model->getPrimaryUser($client_id);
            $data['primary_first_name'] = $primary_user[0]->first_name;
            $data['primary_last_name'] = $primary_user[0]->last_name;
            $data['primary_address'] = $primary_user[0]->address;
            $data['primary_email'] = $primary_user[0]->email;
            $data['primary_phone_no'] = $primary_user[0]->phone_no;
        }
        $data['countries']=$this->Country_model->get_countries();
        $data['client_id']=$client_id;
        $this->load->view('admin/create', $data);
    }

    public function deleteClient($clientId){
	    $clientData = $this->Client_model->getClientDetailsByID($clientId);
	    if($clientData && isset($clientData[0]->intUserId) && $clientData[0]->intUserId != ''){
	        $userId = $clientData[0]->intUserId;
        }
	    $this->Client_model->deleteClient($clientId, $userId);
        $this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible">Client has deleted successfully</div>');
	    redirect(SITE.'dashboard/client');
    }
    public function toProspect()
    {
        //print_r($_POST);
        if($this->input->get('edit-id') != null)
        {
            $data['client_id'] = $this->input->get('edit-id');
            $data['zones'] = $this->Zone_model->get_zones();
            $data['first_name'] = '';
            $data['last_name'] = '';
            $data['address'] = '';
            $data['email'] = '';
            $data['phone_no'] = '';
            $this->load->view('admin/prospect',$data);
        }
        else
        {
            $this->Client_rates_model->saveProspectRates();
            $this->Client_model->updateContact();
            $this->Client_model->insertPrimaryUser();
            redirect(base_url().'dashboard/client');

        }
    }
    public function suspend()
    {
        //die('HERE');
        $client_id = $this->input->get('edit-id');
        $this->Client_model->suspend($client_id);
        redirect(base_url().'dashboard/client');
    }
    public function UnSuspend()
    {
        $client_id = $this->input->get('edit-id');
        $this->Client_model->unSuspend($client_id);
        redirect(base_url().'dashboard/client');
    }
    public function markBlacklist()
    {
        $client_id = $this->input->get('edit-id');
        $this->Client_model->markBlackList($client_id);
        redirect(base_url().'dashboard/blackListClients');
    }
    public function markWhitelist()
    {
        $client_id = $this->input->get('edit-id');
        $this->Client_model->markWhitelist($client_id);
        redirect(base_url().'dashboard/blackListClients');
    }
    public function toClient()
    {
        $client_id = $this->input->get('edit-id');
        $client = $this->Client_model->getClientDetails($client_id);
        $client_email = $client[0]->email;

        $password = mt_rand(100000,999999);

        $user['varFirstName'] = $client[0]->company_name;
        $user['varLastName'] = '';//$client[0]->last_name;
        $user['varEmailId'] = $client[0]->email;
        $user['varMobileNo'] = $client[0]->phone_no;
        $user['varPassword'] = $this->encrypt->encode($password);
        $user['intUserTypeId'] = '5';
        $user['intOrgTypeId'] = '0';
        $user['dtCreated'] = date('Y-m-d H:i:s');
        $user['enumStatus'] = '1';
        $user['enumArchive'] = '0';
        $user['country_id'] = $client[0]->country_id;

        $this->db->insert('user' , $user);
        $user_id = $this->db->insert_id();
        $this->Client_model->updatePropect($client_id, $user_id);

        $emailTo                        = array($client_email);
        $smsTo                          = array($client[0]->phone_no);
        $shortCodeArray                 = array();
        $shortCodeArray['firstName']    = $client[0]->company_name;
        $shortCodeArray['lastName']     = '';
        $shortCodeArray['userEmail']    = $client[0]->email;
        $shortCodeArray['password']     = $password;

        $this->notification_lib->newClientEmailNotification($emailTo, $smsTo, $shortCodeArray);

        $owner_id = $this->session->userdata['UserId'];
        $owner['intUserId']= $user_id ;
        $owner['intOwnerUserId']= $owner_id ;

        $this->db->insert('user_owner',$owner);


        redirect(base_url().'dashboard/client');
    }

    public function sendNotification(){
        
    }

	/*public function order_list()
	{
	        $this->load->view('page/Client Management/Table of clients/client-order-list');
	}
	public function under_clients()
	{
	        $this->load->view('page/Client Management/Table of clients/users-under-clients');
	}
	public function add_client()
	{
	        $this->load->view('page/Client Management/add-client');
	}
	public function contact()
	{
	        $this->load->view('page/Client Management/Edit Client Status/contact');
	}
	public function prospect()
	{
	        $this->load->view('page/Client Management/Edit Client Status/prospect');
	}
	public function account()
	{
	        $this->load->view('page/Client Management/Edit Client Status/account');
	}
	public function suspended()
	{
	        $this->load->view('page/Client Management/Edit Client Status/suspended');
	}
	public function blacklisted()
	{
	        $this->load->view('page/Client Management/Edit Client Status/blacklisted');
	}*/
}


?>