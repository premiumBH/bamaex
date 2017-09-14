                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed    
                    
                    operation="users" 
                    -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">

   <?php
$pages = array();   
   if (isset($this->session->userdata['logged_in'])) {
			
             $UserType = ($this->session->userdata['UserType']);
			 
if($UserType == "Administrator" || $UserType == "Admin")
{
	$col = 'enumAdministrator';
}
else if($UserType == "Agent" || $UserType == "Staff")
{
	$col = 'enumStaff';
}
else if($UserType == "Client")
{
	$col = 'enumClient';
}
else
{
	$col = 'enumOther';
}
    
			//$menu = get_menu_tree($col, 0);
			$menu = get_menu_tree($this->session->userdata['UserTypeId'], 0);
			echo $menu;

 }
?>
                        </ul>
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>