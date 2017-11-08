<?php
$this->load->view('layout/header');
$this->load->view('layout/container');
?>
    <style>
        .ui-timepicker-container.ui-timepicker-no-scrollbar.ui-timepicker-standard {
            z-index: 10000000 !important;
        }
    </style>
    <!-- BEGIN PAGE BASE CONTENT -->
    <div class="row">
        <div class="col-md-12">

            <!-- Begin: life time stats -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase"> Order #<?php echo $order_airway[0]->airway_bill; ?>
                            <span class="hidden-xs">| <?php echo explode(' ', $order_details[0]->created_on)[0]; ?> </span>
                    </span>
                    </div>
                    <div class="actions" style="display: none">
                        <div class="btn-group btn-group-devided" data-toggle="buttons">
                            <label class="btn btn-transparent green btn-outline btn-circle btn-sm active">
                                <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                            <label class="btn btn-transparent blue btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                        </div>
                        <div class="btn-group">
                            <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                <i class="fa fa-share"></i>
                                <span class="hidden-xs"> Tools </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="javascript:;"> Export to Excel </a>
                                </li>
                                <li>
                                    <a href="javascript:;"> Export to CSV </a>
                                </li>
                                <li>
                                    <a href="javascript:;"> Export to XML </a>
                                </li>
                                <li class="divider"> </li>
                                <li>
                                    <a href="javascript:;"> Print Invoices </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs nav-tabs-lg">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab"> Details </a>
                            </li>
                            <li>
                                <a href="#tab_2" data-toggle="tab"> Payments </a>
                            </li>
                            <li>
                                <a href="#tab_3" data-toggle="tab"> Tracking </a>
                            </li>
                            <li>
                                <a href="#tab_4" data-toggle="tab"> Proof of Delivery </a>
                            </li>
                            <?php
                            if($this->session->userdata['UserType'] != 'Client')
                            {
                                ?>

                                <li>
                                    <a href="#tab_5" data-toggle="tab"> Comments </a>
                                </li>
                                <?php
                            }
                            ?>
                            <li>
                                <a href="#tab_6" data-toggle="tab"> Schedule </a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet yellow-crusta box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Order Details </div>
                                                <div class="actions" style="display: none">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Order #: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_airway[0]->airway_bill; ?>
                                                        <!--<span class="label label-info label-sm"> Email confirmation was sent </span>-->
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Order Date & Time: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_details[0]->created_on; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Pickup Status: </div>
                                                    <div class="col-md-7 value">

                                                    <span class="label label-success"><?php //echo $order_pickup_status;?>
                                                        <?php
                                                        echo $order_status;
                                                        //                                                            if($order_details[0]->order_state == '' || $order_details[0]->order_state == '0')
                                                        //                                                            {
                                                        //                                                                if($order_details[0]->order_status == '' || $order_details[0]->order_status == '1' )
                                                        //                                                                {
                                                        //                                                                    echo 'Pending Pickup';
                                                        //                                                                }
                                                        //                                                                else if($order_details[0]->order_status == '2')
                                                        //                                                                {
                                                        //                                                                    echo 'Schedule pickup';
                                                        //                                                                }
                                                        //                                                                else if($order_details[0]->order_status == '3')
                                                        //                                                                {
                                                        //                                                                    echo 'Pickup rescheduled';
                                                        //                                                                }
                                                        //                                                                else if($order_details[0]->order_status == '4')
                                                        //                                                                {
                                                        //                                                                    echo 'Consignement picked up';
                                                        //                                                                }
                                                        //                                                                else if($order_details[0]->order_status == '5')
                                                        //                                                                {
                                                        //                                                                    echo 'Received in warehouse';
                                                        //                                                                }
                                                        //                                                                else if($order_details[0]->order_status == '11'){
                                                        //                                                                    echo 'Assigned to Courier';
                                                        //                                                                }
                                                        //                                                                else if($order_details[0]->order_status == '12'){
                                                        //                                                                    echo 'Collections In Progress';
                                                        //                                                                }
                                                        //                                                            }
                                                        //                                                            else if ($order_details[0]->order_state == '1')
                                                        //                                                            {
                                                        //                                                                echo 'Order Delivered';
                                                        //                                                            }
                                                        //                                                            else
                                                        //                                                            {
                                                        //                                                                echo 'Closed By Team';
                                                        //                                                            }
                                                        ?>
                                                    </span>

                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Payment on Delivery: </div>
                                                    <div class="col-md-7 value"><?php echo $payment_to_collect.' BHD'; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Payment Type: </div>
                                                    <div class="col-md-7 value"> <?php echo $payment_type[0]->payment_type; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Billing Type: </div>
                                                    <div class="col-md-7 value"> <?php echo $billing_type; ?> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Receiver Information </div>
                                                <div class="actions" style="display: none">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Receiver Company: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_receiver[0]->company_name; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Receiver Name: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_receiver[0]->name; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Email: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_receiver[0]->email; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> State: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_receiver[0]->state; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Country: </div>
                                                    <div class="col-md-7 value"> <?php echo $receiver_country[0]->country_name; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Phone Number: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_receiver[0]->mobile; ?> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet green-meadow box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Receiver Address
                                                </div>
                                                <div class="actions">
                                                    <a href="javascript:void(0);" id="<?php echo $order_receiver[0]->id; ?>" class="btn btn-default btn-sm  receiver_edit">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-12 value"> <?php echo $order_receiver[0]->address_line; ?>
                                                        <br> <?php echo $order_receiver[0]->address; ?>
                                                        <br> <?php echo $order_receiver[0]->city; ?>
                                                        <br> <?php echo $receiver_country[0]->country_name; ?>
                                                        <br> Postal Code: <?php echo $order_receiver[0]->postal_code; ?>
                                                        <br> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet red-sunglo box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Sender Address </div>
                                                <div class="actions" style="display: none">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-12 value"> <?php echo $order_sender[0]->address_line; ?>
                                                        <br> <?php echo $order_sender[0]->address; ?>
                                                        <br> <?php echo $order_sender[0]->city; ?>
                                                        <br> <?php echo $sender_country[0]->country_name; ?>
                                                        <br> Postal Code: <?php echo $order_sender[0]->postal_code; ?>
                                                        <br> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet grey-cascade box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Consignment Details</div>
                                                <div class="actions" style="display: none">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th> Title </th>
                                                            <th> Breath </th>
                                                            <th> Height </th>
                                                            <th> Length </th>
                                                            <th> Weight </th>
                                                            <th> Declared Value </th>
                                                            <th> No of Packages </th>
                                                            <th> Order Type </th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <a href="javascript:void(0);"> <?php echo $order_consignment[0]->title; ?> </a>
                                                            </td>
                                                            <td> <?php echo $order_consignment[0]->breath; ?> </td>
                                                            <td> <?php echo $order_consignment[0]->height; ?> </td>
                                                            <td> <?php echo $order_consignment[0]->width; ?> </td>
                                                            <td> <?php echo $order_consignment[0]->weight; ?> </td>
                                                            <td> <?php echo $value; ?> </td>
                                                            <td> <?php echo $order_consignment[0]->no_of_packages; ?> </td>
                                                            <td> <?php echo $order_type[0]->type; ?> </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"> </div>
                                    <div class="col-md-6">
                                        <div class="well">
                                            <div class="row static-info align-reverse">
                                                <div class="col-md-8 name"> Total Payment: </div>
                                                <div class="col-md-3 value"> <?php echo $order_payments[0]->payable_amount; ?> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                <div class="table-container">
                                    <table class="table table-striped table-bordered table-hover" id="datatable_credit_memos">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%"> Airway Bill &nbsp;# </th>
                                            <th width="15%"> Created Date </th>
                                            <th width="10%"> Bill To</th>
                                            <th width="10%"> Status </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><?php echo $order_airway[0]->airway_bill; ?></td>
                                            <td><?php echo $order_details[0]->created_on; ?></td>
                                            <td><?php echo $order_receiver[0]->name; ?></td>
                                            <?php
                                            if($order_payments[0]->payment_status == 0)
                                            {
                                                ?>
                                                <td>Pending</td>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <td>Payment Received</td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        </tbody>
                                        <tbody> </tbody>
                                    </table>
                                </div>
                            </div>


                            <div class="tab-pane" id="tab_3">
                                <div class="portlet-body">
                                    <div class="mt-timeline-2">
                                        <div class="mt-timeline-line border-grey-steel"></div>
                                        <ul class="mt-container">

                                            <?php
                                            foreach($shipments as $shipment)
                                            {
                                                ?>
                                                <li class="mt-item">
                                                    <div class="mt-timeline-icon bg-blue bg-font-blue border-grey-steel">
                                                        <i class="icon-plane"></i>
                                                    </div>
                                                    <div class="mt-timeline-content">
                                                        <div class="mt-content-container bg-white border-grey-steel">
                                                            <div class="mt-title">
                                                                <h3 class="mt-content-title"><?php echo $shipment->catalog_subject;?></h3>
                                                            </div>
                                                            <div class="mt-author">
                                                                <div class="mt-avatar">
                                                                    <?php
                                                                    //                                                        if(isset($shipment->profile_image) && $shipment->profile_image != '')
                                                                    if(is_file(realpath('.').$shipment->profile_image) && file_exists(realpath('.').$shipment->profile_image))
                                                                    {
                                                                        ?>
                                                                        <img src="<?php echo SITE.$shipment->profile_image;?>" />
                                                                        <?php
                                                                    }else
                                                                    {
                                                                        ?>
                                                                        <img src="<?=THEME?>assets/pages/media/users/avatar80_3.jpg" />
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div class="mt-author-name">
                                                                    <a href="javascript:;" class="font-blue-madison"><?php echo $shipment->varFirstName;?></a>
                                                                </div>
                                                                <div class="mt-author-notes font-grey-mint"><?php echo $shipment->created_on;?></div>
                                                            </div>
                                                            <div class="mt-content border-grey-steel">
                                                                <p><?php echo $shipment->catalog_info;?>.</p>
                                                                <!--<a href="javascript:;" class="btn btn-circle red">Read More</a>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                            <li class="mt-item">
                                                <div class="mt-timeline-icon bg-red bg-font-red border-grey-steel">
                                                    <i class="icon-home"></i>
                                                </div>
                                                <div class="mt-timeline-content">
                                                    <div class="mt-content-container">
                                                        <div class="mt-title">
                                                            <h3 class="mt-content-title">Waiting for Pickup</h3>
                                                        </div>
                                                        <div class="mt-author">
                                                            <div class="mt-avatar">
                                                                <img src="<?=THEME?>assets/pages/media/users/avatar80_2.jpg" />
                                                            </div>
                                                            <!--                                                        <div class="mt-author-name">
                                                                                                                        <a href="javascript:;" class="font-blue-madison">Andres Iniesta</a>
                                                                                                                    </div>-->
                                                            <div class="mt-author-notes font-grey-mint"><?php echo $order_details[0]->created_on; ?></div>
                                                        </div>
                                                        <div class="mt-content border-grey-salt">
                                                            <p>Order is just confirmed and waiting for the Pickup.</p>
                                                            <!--<a href="javascript:;" class="btn btn-circle red">Read More</a>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_4">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">

                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Order Proof </div>
                                                <div class="actions" style="display: none">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Order #: </div>
                                                    <div class="col-md-7 value"> <?php echo $order_airway[0]->airway_bill; ?>
                                                        <!--<span class="label label-info label-sm"> Email confirmation was sent </span>-->
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Captured Date & Time: </div>
                                                    <div class="col-md-7 value"> <?php //echo $order_details[0]->updated_on; ?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Proof of Delivery: <?php echo $order_details[0]->order_state;?> </div>
                                                    <?php
                                                    if($order_details[0]->order_state == '' || $order_details[0]->order_state == 0)
                                                    {
                                                        echo '<div class="col-md-5 name"> Unavailable (Order is in Open State) </div>';
                                                    }
                                                    else
                                                    {
                                                        $image_name = '00000'.substr($order_airway[0]->airway_bill, 5);
                                                        ?>
                                                        <div class="col-md-7 value"> <img style=" width: 101%;" src="<?php echo base_url().'OrderPictres/'.$image_name.'.jpg'; ?>"></img> </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">

                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Upload Order Proof </div>

                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <form action="<?=CTRL?>Order/uploadOrderProof" enctype="multipart/form-data" method="post">
                                                        <div class="form-body">
                                                            <div class="form-group">
                                                                <div class="col-md-6">
                                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                        <div class="input-group input-large">
                                                                            <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                                                <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                                                <span class="fileinput-filename"> </span>
                                                                            </div>
                                                                            <span class="input-group-addon btn default btn-file">
                                                                <span class="fileinput-new"> Select file </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                <input type="file" name="orderProof"> </span>
                                                                            <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="hidden" name="orderTrackingId" value="<?=$order_details[0]->order_tracking_id?>">
                                                                    <input type="hidden" name="orderId" value="<?=$order_details[0]->order_id?>">
                                                                    <input type="submit" class="btn green" value="Upload" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="tab_5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet light portlet-fit bordered">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="icon-microphone font-green"></i>
                                                    <span class="caption-subject bold font-green uppercase">Order Timeline Comments</span>
                                                    <!--                                                            <span class="caption-helper">default option...</span>-->
                                                </div>
                                                <div class="actions">
                                                    <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                        <label class="btn red btn-outline btn-circle btn-sm active addComment" >
                                                            <input name="options" class="toggle" id="option1" type="radio">Add Comment</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="timeline">
                                                    <?php
                                                    if(isset($comments))
                                                    {
                                                        foreach($comments as $comment)
                                                        {
                                                            ?>
                                                            <!-- TIMELINE ITEM -->
                                                            <div class="timeline-item">
                                                                <div class="timeline-badge">
                                                                    <img class="timeline-badge-userpic" src="<?=THEME?>assets/pages/media/users/avatar80_3.jpg"> </div>
                                                                <div class="timeline-body">
                                                                    <div class="timeline-body-arrow"> </div>
                                                                    <div class="timeline-body-head">
                                                                        <div class="timeline-body-head-caption">
                                                                            <a href="javascript:void(0);" class="timeline-body-title font-blue-madison"><?php echo $comment->varFirstName; ?></a>
                                                                            <span class="timeline-body-time font-grey-cascade"><?php echo $comment->created_on; ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-body-content">
                                                                        <span class="font-grey-cascade"><?php echo $comment->comment; ?>. </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="tab_6">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet grey-cascade box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Schedule</div>
                                                <div class="actions" style="display: none">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th> Schedule Type </th>
                                                            <th> Schedule Ref </th>
                                                            <th> Date </th>
                                                            <th> From Time </th>
                                                            <th> To Time </th>
                                                            <th> Contact Person </th>
                                                            <th> Contact Mobile </th>
                                                            <th> Action </th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                Pickup
                                                            </td>
                                                            <td> <?php echo $order_details[0]->pickup_reference; ?> </td>
                                                            <td> <?php echo $order_details[0]->order_pickup_date; ?> </td>
                                                            <td> <?php echo $order_details[0]->order_pickup_time; ?> </td>
                                                            <td> <?php echo $order_details[0]->order_pickup_time_to; ?> </td>
                                                            <td> <?php echo $order_contact[0]->person_name; ?> </td>
                                                            <td> <?php echo $order_contact[0]->person_mobile; ?> </td>
                                                            <?php
                                                            if($order_status != 'Consignement picked up' && $order_status != 'Collections In Progress' && $order_status != 'Received in warehouse' && $order_status != 'Delivered' && $order_status != 'Undelivered - Return to sender' && $order_status != 'Out for delivery' && $order_status != 'Received in warehouse')
                                                            {
                                                                ?>
                                                                <td> <a class="reschedule_pickup" href="javascript:void(0);" code="<?php echo $order_details[0]->order_id; ?>">Edit</a> </td>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                        <?php
                                                        if(isset($order_delivery_schedule[0]))
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    Delivery
                                                                </td>
                                                                <td> <?php echo $order_delivery_schedule[0]->delivery_schedule_ref ; ?> </td>
                                                                <td> <?php echo $order_delivery_schedule[0]->delivery_date; ?> </td>
                                                                <td> <?php echo $order_delivery_schedule[0]->delivery_time; ?> </td>
                                                                <td> <?php echo $order_delivery_schedule[0]->delivery_time_to; ?> </td>
                                                                <td> <?php echo $order_delivery_schedule[0]->contact_person_name; ?> </td>
                                                                <td> <?php echo $order_delivery_schedule[0]->contact_person_mobile; ?> </td>
                                                                <?php
                                                                if($this->session->userdata['UserType'] != 'Client')
                                                                {
                                                                    ?>
                                                                    <?php
                                                                    if( $order_status != 'Delivered' && $order_status != 'Undelivered - Return to sender' && $order_status != 'Out for delivery' )
                                                                    {
                                                                        ?>
                                                                        <td> <a class="reschedule_delivery" href="javascript:void(0);" code="<?php echo $order_details[0]->order_id; ?>">Edit</a> </td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    Delivery
                                                                </td>
                                                                <td>  </td>
                                                                <td>  </td>
                                                                <td> </td>
                                                                <td>  </td>
                                                                <td>  </td>
                                                                <td>  </td>
                                                                <?php
                                                                if($this->session->userdata['UserType'] != 'Client')
                                                                {
                                                                    ?>
                                                                    <?php
                                                                    if( $order_status != 'Delivered' && $order_status != 'Undelivered - Return to sender' && $order_status != 'Out for delivery' )
                                                                    {
                                                                        ?>
                                                                        <td> <a class="reschedule_delivery" href="javascript:void(0);" code="<?php echo $order_details[0]->order_id; ?>">Edit</a> </td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
    </div>
    <!-- END PAGE BASE CONTENT -->
<?php $this->load->view('layout/footer'); ?>