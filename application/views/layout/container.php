<?php
?>
<div class="">
            <div class="page-content page-content-popup no-margin-top">
                <div class="page-content-fixed-header">
                    <!-- BEGIN BREADCRUMBS -->
                    <ul class="page-breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                    </ul>
                    <!-- END BREADCRUMBS -->
                    <?php if($this->session->userdata('UserType') == 'Admin'){?>
                    <div class="content-header-menu">
                        <!-- BEGIN DROPDOWN AJAX MENU -->
                        <div class="dropdown-ajax-menu btn-group">
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <i class="fa fa-circle"></i>
                                <i class="fa fa-circle"></i>
                                <i class="fa fa-circle"></i>
                            </button>
                            <ul class="dropdown-menu-v2">
                                <li>
                                    <a href="<?php echo SITE.'custom_setting'?>">Settings</a>
                                </li>
                                <li>
                                    <a href="<?php echo SITE. 'notification' ?>">Notifications</a>
                                </li>
                                <li>
                                    <a href="<?php echo SITE.'notification/notification_control_list'?>">Notifications Control</a>
                                </li>
                                <li>
                                    <a href="start.html">Administration</a>
                                </li>
                            </ul>
                        </div>
                        <!-- END DROPDOWN AJAX MENU -->
                        <!-- BEGIN MENU TOGGLER -->
                        <button type="button" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="toggle-icon">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </span>
                        </button>
                        <!-- END MENU TOGGLER -->
                    </div>
                    <?php } ?>
                </div>
    <?php
        $this->load->view('layout/sidebar');
    ?>        
            <div class="page-fixed-main-content" id="main">
                    <!-- BEGIN PAGE BASE CONTENT -->
