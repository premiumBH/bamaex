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
if($UserType == "Agent" || $UserType == "Staff")
{
	$col = 'enumStaff';
}
if($UserType == "Client")
{
	$col = 'enumClient';
}
if($UserType == "Other")
{
	$col = 'enumOther';
}

			$sqlQuery = " SELECT "
			                ." varPageSlug AS PageSlug"
			        ." FROM access_control where $col = 1";
			$result = $this->db->query($sqlQuery);

			if($result->num_rows()>0) {
				foreach($result->result() AS $result1)
				{
				$pages[] = $result1->PageSlug;
				}
            }
   }
   ?>
                            <li class="nav-item start active open">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Dashboard</span>
                                    <span class="selected"></span>
                                    <span class="arrow open"></span>
                                </a>
                                <ul class="sub-menu">
                               <?php if(in_array('user', $pages)) { ?>
                                    <li class="nav-item start open <?php if($this->uri->segment(1)=="user"){echo "active";}?>">
                                        <a  class="nav-link sideMenuClk" href="<?php echo CTRL; ?>user">
                                            <i class="icon-layers" ></i>
                                            <span class="title ">Users</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                               <?php } ?>
                               <?php if(in_array('client', $pages)) { ?>
                                    <li class="nav-item start <?php if($this->uri->segment(1)=="client"){echo "active";}?>">
                                        <a  class="nav-link " href="<?php echo CTRL; ?>client">
                                            <i class="icon-layers"></i>
                                            <span class="title">Clients</span>
                                            <span class="badge badge-success">1</span>
                                        </a>
                                    </li>
                               <?php } ?>
                               <?php if(in_array('package', $pages)) { ?>
                                    <li class="nav-item start <?php if($this->uri->segment(1)=="package"){echo "active";}?>">
                                        <a  class="nav-link " href="<?php echo CTRL; ?>package">
                                            <i class="icon-layers"></i>
                                            <span class="title">Packages</span>
                                            <span class="badge badge-danger">5</span>
                                        </a>
                                    </li>
                               <?php } ?>
                               <?php if(in_array('service', $pages)) { ?>
                                    <li class="nav-item start <?php if($this->uri->segment(1)=="service"){echo "active";}?>">
                                        <a  class="nav-link " href="<?php echo CTRL; ?>service">
                                            <i class="icon-layers"></i>
                                            <span class="title">Services</span>
                                            <span class="badge badge-danger">5</span>
                                        </a>
                                    </li>
                               <?php } ?>
                               <?php if(in_array('order_status', $pages)) { ?>
                                 <li class="nav-item start <?php if($this->uri->segment(1)=="order_status"){echo "active";}?>">
                                        <a  class="nav-link " href="<?php echo CTRL; ?>order_status">
                                            <i class="icon-layers"></i>
                                            <span class="title">Order Status</span>
                                            <span class="badge badge-danger">5</span>
                                        </a>
                                    </li>
                               <?php } ?>
                                </ul>
                            </li>


                            <li class="nav-item start active open">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Track Order</span>
                                    <span class="arrow open"></span>
                                </a>

                            </li>
                            <li class="nav-item start active open">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Address Bookr</span>
                                    <span class="arrow open"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item start active open">
                                        <a href="" class="nav-link ">
                                            <i class="icon-layers"></i>
                                            <span class="title">Pickup Address</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item start ">
                                        <a href="" class="nav-link ">
                                            <i class="icon-layers"></i>
                                            <span class="title">Delivery Address</span>
                                            <span class="badge badge-success">1</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item start active open">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="icon-home"></i>
                                    <span class="title">Access Control</span>
                                    <span class="selected"></span>
                                    <span class="arrow open"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item start open <?php if($this->uri->segment(1)=="user"){echo "active";}?>">
                                        <a  class="nav-link sideMenuClk" href="<?php echo CTRL; ?>pages">
                                            <i class="icon-layers" ></i>
                                            <span class="title " >Pages</span>
                                            <span class="selected"></span>
                                        </a>
                                    </li>
                                 </ul>
                           </li>
                        </ul>
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>