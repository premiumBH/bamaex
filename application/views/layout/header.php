<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <?php
        $this->load->view('layout/head_script');
    ?>
    <!-- <head>
        <meta charset="utf-8" />
        <title>Metronic Admin Theme #6 | Admin Dashboard</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #6 for statistics, charts, recent events and reports" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN LAYOUT FIRST STYLES -- >
        <link href="//fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css" />
        <!-- END LAYOUT FIRST STYLES -->
        <!-- BEGIN GLOBAL MANDATORY STYLES -- >
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -- >
        <!-- BEGIN PAGE LEVEL PLUGINS -- >
        <link href="<?=THEME?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -- >
        <link href="<?=THEME?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?=THEME?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?=THEME?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME LAYOUT STYLES -- >
        <link href="<?=THEME?>assets/layouts/layout6/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=THEME?>assets/layouts/layout6/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -- >
        <link rel="shortcut icon" href="favicon.ico" /> 
    </head> -->
    <!-- END HEAD -->

    <body class="">
        <!-- BEGIN HEADER -->
<header class="page-header">
            <nav class="navbar" role="navigation">
                <div class="container-fluid">
                    <div class="havbar-header">
                        <!-- BEGIN LOGO -->
                        <a id="index" class="navbar-brand" href="start.html">
                            <img src="<?=THEME?>assets/pages/img/bamaex_Logo_tex_red.png" alt="Logo"> </a>
                        <!-- END LOGO -->
                        <!-- BEGIN TOPBAR ACTIONS -->
                        <div class="topbar-actions">
                            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
                            <form class="search-form" action="extra_search.html" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search here" name="query">
                                    <span class="input-group-btn">
                                        <a href="javascript:;" class="btn md-skip submit">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </span>
                                </div>
                            </form>
                            <!-- END HEADER SEARCH BOX -->
                            <!-- BEGIN GROUP NOTIFICATION -->
                            <!-- <div class="btn-group-notification btn-group" id="header_notification_bar">
                                <button type="button" class="btn md-skip dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <span class="badge">9</span>
                                </button>
                                <ul class="dropdown-menu-v2">
                                    <li class="external">
                                        <h3>
                                            <span class="bold">12 pending</span> notifications</h3>
                                        <a href="#">view all</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 250px; padding: 0;" data-handle-color="#637283">
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-success md-skip">
                                                            <i class="fa fa-plus"></i>
                                                        </span> New user registered. </span>
                                                    <span class="time">just now</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger md-skip">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Server #12 overloaded. </span>
                                                    <span class="time">3 mins</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning md-skip">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Server #2 not responding. </span>
                                                    <span class="time">10 mins</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info md-skip">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> Application error. </span>
                                                    <span class="time">14 hrs</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger md-skip">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Database overloaded 68%. </span>
                                                    <span class="time">2 days</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger md-skip">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> A user IP blocked. </span>
                                                    <span class="time">3 days</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-warning md-skip">
                                                            <i class="fa fa-bell-o"></i>
                                                        </span> Storage Server #4 not responding dfdfdfd. </span>
                                                    <span class="time">4 days</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-info md-skip">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </span> System Error. </span>
                                                    <span class="time">5 days</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-danger md-skip">
                                                            <i class="fa fa-bolt"></i>
                                                        </span> Storage server failed. </span>
                                                    <span class="time">9 days</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div> -->
                            <!-- END GROUP NOTIFICATION -->
                            <!-- BEGIN USER PROFILE -->
                            <div class="btn-group-img btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img src="<?=THEME?>assets/layouts/layout5/img/avatar1.jpg" alt=""> </button>
                                <ul class="dropdown-menu-v2" role="menu">
                                    <li>
                                        <a href="page_user_profile_1.html">
                                            <?php
                                            $userType       = $this->session->userdata('UserType');
                                            if($userType != '' )
                                                echo $userType;
                                            ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="page_user_profile_1.html">
                                            <i class="icon-user"></i> My Profile
                                            <span class="badge badge-danger">1</span>
                                        </a>
                                    </li>
                                    <!--<li>
                                        <a href="app_calendar.html">
                                            <i class="icon-calendar"></i> My Calendar </a>
                                    </li>
                                    <li>
                                        <a href="app_inbox.html">
                                            <i class="icon-envelope-open"></i> My Inbox
                                            <span class="badge badge-danger"> 3 </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="app_todo_2.html">
                                            <i class="icon-rocket"></i> My Tasks
                                            <span class="badge badge-success"> 7 </span>
                                        </a>
                                    </li>-->
                                    <li class="divider"> </li>
                                    <!--<li>
                                        <a href="page_user_lock_1.html">
                                            <i class="icon-lock"></i> Lock Screen </a>
                                    </li>-->
                                    <li>
                                        <a href="<?=SITE?>/dashboard/logout">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- END USER PROFILE -->
                        </div>
                        <!-- END TOPBAR ACTIONS -->
                    </div>
                </div>
                <!--/container-->
            </nav>
        </header>