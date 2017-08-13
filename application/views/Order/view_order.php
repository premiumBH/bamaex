<?php
	$this->load->view('layout/header');
	$this->load->view('layout/container');
?>

<!-- BEGIN PAGE BASE CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> Order #<?php echo $order_details[0]->order_tracking_id; ?>
                        <span class="hidden-xs">| Dec 27, 2013 7:16:25 </span>
                    </span>
                </div>
                <div class="actions">
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
                            <a href="#tab_2" data-toggle="tab"> Order Memos </a>
                        </li>
                        <li>
                            <a href="#tab_3" data-toggle="tab"> Tracking </a>
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
                                            <div class="actions">
                                                <a href="javascript:;" class="btn btn-default btn-sm">
                                                    <i class="fa fa-pencil"></i> Edit </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row static-info">
                                                <div class="col-md-5 name"> Order #: </div>
                                                <div class="col-md-7 value"> <?php echo $order_details[0]->order_tracking_id; ?>
                                                    <span class="label label-info label-sm"> Email confirmation was sent </span>
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"> Order Date & Time: </div>
                                                <div class="col-md-7 value"> <?php echo $order_details[0]->created_on; ?> </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"> Order Status: </div>
                                                <div class="col-md-7 value">
                                                <?php
                                                if($order_details[0]->order_status == 1)
                                                {
                                                ?>
                                                    <span class="label label-success"> Pending </span>
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                    <span class="label label-success"> Delivered </span>
                                                <?php
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"> Grand Total: </div>
                                                <div class="col-md-7 value"><?php echo $order_payments[0]->payable_amount.' BHD'; ?> </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"> Payment Information: </div>
                                                <div class="col-md-7 value"> <?php echo $payment_type[0]->payment_type; ?> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-cogs"></i>Receiver Information </div>
                                            <div class="actions">
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
                                                <div class="col-md-5 name"> Receiver Company: </div>
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
                                                <i class="fa fa-cogs"></i>Receiver Address </div>
                                            <div class="actions">
                                                <a href="javascript:;" class="btn btn-default btn-sm">
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
                                            <div class="actions">
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
                                            <div class="actions">
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
                                                            <th> Width </th>
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
                                            if($order_details[0]->order_status == 1)
                                            {
                                            ?>
                                           <td>Pending</td>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                           <td>Delivered</td>
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
                                        <li class="mt-item">
                                            <div class="mt-timeline-icon bg-red bg-font-red border-grey-steel">
                                                <i class="icon-home"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container">
                                                    <div class="mt-title">
                                                        <h3 class="mt-content-title">Sender Place</h3>
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
                                                            <img src="<?=THEME?>assets/pages/media/users/avatar80_3.jpg" />
                                                        </div>
                                                        <div class="mt-author-name">
                                                            <a href="javascript:;" class="font-blue-madison"><?php echo $shipment->created_by;?></a>
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
<!--                                        <li class="mt-item">
                                            <div class="mt-timeline-icon bg-green-turquoise bg-font-green-turquoise border-grey-steel">
                                                <i class="icon-calendar"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container">
                                                    <div class="mt-title">
                                                        <h3 class="mt-content-title">Timeline Received</h3>
                                                    </div>
                                                    <div class="mt-author">
                                                        <div class="mt-avatar">
                                                            <img src="<?=THEME?>assets/pages/media/users/avatar80_2.jpg" />
                                                        </div>
                                                        <div class="mt-author-name">
                                                            <a href="javascript:;" class="font-blue-madison">Andres Iniesta</a>
                                                        </div>
                                                        <div class="mt-author-notes font-grey-mint">12 March 2016 : 12:20 PM</div>
                                                    </div>
                                                    <div class="mt-content border-grey-salt">
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui
                                                            ut.</p>
                                                        <a href="javascript:;" class="btn btn-circle green-turquoise">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mt-item">
                                            <div class="mt-timeline-icon bg-purple-medium bg-font-purple-medium border-grey-steel">
                                                <i class="icon-heart"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container bg-white border-grey-steel">
                                                    <div class="mt-title">
                                                        <h3 class="mt-content-title">Event Success</h3>
                                                    </div>
                                                    <div class="mt-author">
                                                        <div class="mt-avatar">
                                                            <img src="<?=THEME?>assets/pages/media/users/avatar80_1.jpg" />
                                                        </div>
                                                        <div class="mt-author-name">
                                                            <a href="javascript:;" class="font-blue-madison">Matt Goldman</a>
                                                        </div>
                                                        <div class="mt-author-notes font-grey-mint">14 March 2016 : 8:15 PM</div>
                                                    </div>
                                                    <div class="mt-content border-grey-steel">
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde.</p>
                                                        <a href="javascript:;" class="btn btn-circle btn-outline purple-medium">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mt-item">
                                            <div class="mt-timeline-icon bg-blue-steel bg-font-blue-steel border-grey-steel">
                                                <i class="icon-call-in"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container">
                                                    <div class="mt-title">
                                                        <h3 class="mt-content-title">Conference Call</h3>
                                                    </div>
                                                    <div class="mt-author">
                                                        <div class="mt-avatar">
                                                            <img src="<?=THEME?>assets/pages/media/users/avatar80_1.jpg" />
                                                        </div>
                                                        <div class="mt-author-name">
                                                            <a href="javascript:;" class="font-blue-madison">Rory Matthew</a>
                                                        </div>
                                                        <div class="mt-author-notes font-grey-mint">14 March 2016 : 5:45 PM</div>
                                                    </div>
                                                    <div class="mt-content border-grey-salt">
                                                        <img class="timeline-body-img pull-left" src="<?=THEME?>assets/pages/media/blog/5.jpg" alt="">
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui
                                                            ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui
                                                            ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui
                                                            ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                                        <a href="javascript:;" class="btn btn-circle red">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mt-item">
                                            <div class="mt-timeline-icon bg-green-jungle bg-font-green-jungle border-grey-steel">
                                                <i class="icon-call-out"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container bg-white border-grey-steel">
                                                    <div class="mt-title">
                                                        <h3 class="mt-content-title">Conference Decision</h3>
                                                    </div>
                                                    <div class="mt-author">
                                                        <div class="mt-avatar">
                                                            <img src="<?=THEME?>assets/pages/media/users/avatar80_5.jpg" />
                                                        </div>
                                                        <div class="mt-author-name">
                                                            <a href="javascript:;" class="font-blue-madison">Jessica Wolf</a>
                                                        </div>
                                                        <div class="mt-author-notes font-grey-mint">14 March 2016 : 8:30 PM</div>
                                                    </div>
                                                    <div class="mt-content border-grey-steel">
                                                        <img class="timeline-body-img pull-right" src="<?=THEME?>assets/pages/media/blog/6.jpg" alt="">
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui
                                                            ut.</p>
                                                        <a href="javascript:;" class="btn btn-circle green-sharp">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mt-item">
                                            <div class="mt-timeline-icon bg-blue-chambray bg-font-blue-chambray border-grey-steel">
                                                <i class="icon-bubbles"></i>
                                            </div>
                                            <div class="mt-timeline-content">
                                                <div class="mt-content-container">
                                                    <div class="mt-title">
                                                        <h3 class="mt-content-title">Conference Post Mortem</h3>
                                                    </div>
                                                    <div class="mt-author">
                                                        <div class="mt-avatar">
                                                            <img src="<?=THEME?>assets/pages/media/users/avatar80_1.jpg" />
                                                        </div>
                                                        <div class="mt-author-name">
                                                            <a href="javascript:;" class="font-blue-madison">Rory Matthew</a>
                                                        </div>
                                                        <div class="mt-author-notes font-grey-mint">15 March 2016 : 10:45 PM</div>
                                                    </div>
                                                    <div class="mt-content border-grey-salt">
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui
                                                            ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui
                                                            ut. laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut. </p>
                                                        <a href="javascript:;" class="btn btn-circle red">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>-->
                                    </ul>
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