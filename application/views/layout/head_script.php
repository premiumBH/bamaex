<?php 		
        if (isset($this->session->userdata['logged_in'])) 
        {			             
            $username = ($this->session->userdata['username']);             
            $email = ($this->session->userdata['email']);			 
            
        } 			
        else 
        {		        
            redirect(CTRL);               
            
        }	  
        $varPageSlug = $this->uri->uri_string();
        $UserTypeId = ($this->session->userdata['UserTypeId']);
        /*if($UserType == "Administrator" || $UserType == "Admin")
        {	
            $col = 'enumAdministrator';
            
        }
        else if($UserType == "Agent" || $UserType == "Staff")
        {	
            $col = 'enumStaff';
            
        }else if($UserType == "Client")
        {	
            $col = 'enumClient';
            
        }else
        {	$col = 'enumOther';
        
        }*/
        $sqlQuery           = " SELECT "
                                ." intID AS id,"
                                ." varPageSlug AS PageSlug"
                                ." FROM access_control where varPageSlug='" .$varPageSlug . "'";
        $result             = $this->db->query($sqlQuery);
        if($result->num_rows()>0){
            $pageData       = $result->result();
            $pageId         = $pageData[0]->id;
            $ci =&get_instance();
            $ci->load->model('Admin_model');
            $pageAccessData = $ci->Admin_model->getAccessControlUserTypeRef($pageId, $UserTypeId);
            if(empty($pageAccessData)){
                show_404(); //redirect(CTRL.'404');
            }
        }

                /*$sqlQuery = " SELECT "
                        ." intID AS id,"
                        ." varPageSlug AS PageSlug"
                        ." FROM access_control where $col = 0 and varPageSlug='" .$varPageSlug . "'";
                 = $this->db->query($sqlQuery);
                if($result->num_rows()>0) {
                    show_404(); //redirect(CTRL.'404');
                }		*/
        ?> 
<head>        
    <meta charset="utf-8" />        
    <title>Dashboard||BAMAEX </title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Preview page of Metronic Admin Theme #6 for statistics, charts, recent events and reports" name="description" />
    <meta content="" name="author" />        <!-- BEGIN LAYOUT FIRST STYLES -->
    <link href="//fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css" />
    <!-- END LAYOUT FIRST STYLES -->        <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="<?=THEME?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />        <link href="<?=THEME?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />        <link href="<?=THEME?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />        <link href="<?=THEME?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />        <!-- END GLOBAL MANDATORY STYLES -->        <!-- BEGIN PAGE LEVEL PLUGINS -->       <link href="<?=THEME?>assets/global/plugins/icheck/skins/minimal/_all.css" rel="stylesheet" type="text/css" />         <link href="<?=THEME?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />        <link href="<?=THEME?>assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />        <link href="<?=THEME?>assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />        <link href="<?=THEME?>assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />        <!-- END PAGE LEVEL PLUGINS -->        <!-- BEGIN THEME GLOBAL STYLES -->        <link href="<?=THEME?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />        <link href="<?=THEME?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />        <!-- END THEME GLOBAL STYLES -->        <!-- BEGIN THEME LAYOUT STYLES -->        <link href="<?=THEME?>assets/layouts/layout6/css/layout.css" rel="stylesheet" type="text/css" />        <link href="<?=THEME?>assets/layouts/layout6/css/custom.min.css" rel="stylesheet" type="text/css" />        <!-- END THEME LAYOUT STYLES -->        <link rel="shortcut icon" href="favicon.ico" />
    <script src="<?=THEME?>assets/global/plugins/bootbox/bootbox.min.js"  type="text/javascript"></script>
    <?php echo '<script> var MAIN_CTRL="'.CTRL.'";</script>'; ?>
</head>
            
    
    