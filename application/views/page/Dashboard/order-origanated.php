<?php
$this->load->view('layout/header');
$this->load->view('layout/container');
?>
<!-- BEGIN PAGE BASE CONTENT -->
<div class="col-md-12">
    <div class="portlet light bordered" id="form_wizard_1">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-red"></i>
                <span class="caption-subject font-red bold uppercase"> SINGLE ORDER ORIGINATION -
                    <span class="step-title"> Step 1 of 4 </span>
                </span>
            </div>
            <div class="actions">
                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                    <i class="icon-cloud-upload"></i>
                </a>
                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                    <i class="icon-wrench"></i>
                </a>
                <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                    <i class="icon-trash"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" id="form1" action="<?=CTRL?>order/create" id="submit_form" method="POST">
                <div class="form-wizard">
                    <div class="form-body edit">
                        <ul class="nav nav-pills nav-justified steps">
                            <li title="Sender Details">
                                <a href="#tab1" data-toggle="tab" class="step one">
                                    <span class="number"> 1 </span>
                                    <span class="desc">
                                        <i class="fa fa-check"></i> Sender Details </span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-toggle="tab" class="step two">
                                    <span class="number"> 2 </span>
                                    <span class="desc">
                                        <i class="fa fa-check"></i>  Receiver Detail </span>
                                </a>
                            </li>
                            <li title="Consignment details ">
                                <a href="#tab3" data-toggle="tab" class="step three">
                                    <span class="number"> 3 </span>
                                    <span class="desc">
                                        <i class="fa fa-check"></i> Consignment details  </span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab4" data-toggle="tab" class="step four">
                                    <span class="number"> 4 </span>
                                    <span class="desc">
                                        <i class="fa fa-check"></i> Pickup detail   </span>
                                </a>
                            </li>
                        </ul>
                        <div id="bar" class="progress progress-striped" role="progressbar">
                            <div class="progress-bar progress-bar-success"> </div>
                        </div>
                        <div class="tab-content">

                            <div class="alert alert-danger display-none">
                                <button class="close" data-dismiss="alert"></button> You have some form errors. Please check below. 
                            </div>
                            <div class="alert alert-success display-none">
                                <button class="close" data-dismiss="alert"></button> Your form validation is successful! 
                            </div>

                            <div class="tab-pane active" id="tab1">
                                <h3 class="block">Sender Details:Your Address Info </h3>
                                <div class="form-group form-md-line-input has-success">
                                    <div class="col-md-9">
                                        <label class="error" for="form_control_1">Please fill the mandatory fields.</label>
                                    </div>
                                </div>
                                <div class="form-body">
                                    <div class="form-group form-md-line-input has-success">
                                        <label class="col-md-3 control-label" for="form_control_1">Address Names</label>
                                        <div class="col-md-9">
                                            <select id="addSenderfield" class="form-control" name="sender_id" >
                                                <option value="" selected>Select Address Name</option>
                                                <?php
                                                foreach($senders as $sender)
                                                {
                                                ?>
                                                <option value="<?php echo $sender->id; ?>"><?php echo $sender->address_line; ?></option>
                                                <?php
                                                }
                                                ?>
                                                <option value="addSenderCompany">Add New Address Name</option>
                                                
                                            </select>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Some help goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input hiddeen-div0" id="addSenderCompany">
                                        <label class="col-md-3 control-label" for="form_control_1">Sender Address Name</label>
                                        <div class="col-md-9">
                                            <input name="sender_address_line" value="<?php echo $sender_address_line; ?>"  type="text" class="form-control" placeholder="" required>
                                            <?php
                                            
                                            ?>
                                            <input name="new_sender" value=""  type="hidden" class="form-control" placeholder="" required>
                                            <?php
                                            ?>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Company Name goes here...</span>
                                        </div>
                                    </div>
                                <div id="sender">
                                    <div class="form-group form-md-line-input" style="display: none">
                                        <label class="col-md-3 control-label" for="form_control_1">Reference No</label>
                                        <div class="col-md-9">
                                           <input name="client_id" value="<?php echo $client_id; ?>"  type="hidden" class="form-control" placeholder="" required>
                                            <input name="sender_reference_no" value="<?php echo $sender_reference_no; ?>" type="text" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Account Number goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">City*</label>
                                        <div class="col-md-9">
                                            <input type="text" name="sender_city" value="<?php echo $city; ?>" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">City Name goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input has-success">
                                        <label class="col-md-3 control-label" for="form_control_1">Country*</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="sender_country_id">
                                            <?php
                                                foreach($countries as $country)
                                                {
                                                    if($country->id == $receiver_country_id)
                                                    {
                                                ?>        
                                                        <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                <?php
                                                    }
                                                }
                                             ?>
                                            </select>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Some help goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">State / Province</label>
                                        <div class="col-md-9">
                                            <input name="sender_state" value="<?php echo $state;?>" type="text" class="form-control" placeholder="State">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Receiver Company Name Type here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input has-error">
                                        <label class="col-md-3 control-label" for="form_control_1">Address*</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="sender_address" value="" required rows="3"><?php echo $address; ?></textarea>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Enter your Address...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">Postcode Code</label>
                                        <div class="col-md-9">
                                            <input type="text" name="sender_postal_code" value="<?php echo $postal_code; ?>" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Postcode goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input has-success">
                                        <label class="col-md-3 control-label" for="form_control_1">Email*</label>
                                        <div class="col-md-9">
                                            <div class="input-group has-success">
                                                <input type="text" name="sender_email" value="<?php echo $email; ?>" class="form-control" placeholder="Email Address">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                    </div>                   
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">Name</label>
                                        <div class="col-md-9">
                                            <input type="text" name="sender_name" value="<?php echo $name1; ?>" class="form-control" placeholder="Sender Name">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Phone Number Type here...</span>
                                        </div>
                                    </div>                    
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">Mobile Number*</label>
                                        <div class="col-md-9">
                                            <input type="text" name="sender_mobile" value="<?php echo $mobile; ?>" class="form-control" placeholder="Mobile Number">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Mobile Number Type here...</span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                           
                            <div class="tab-pane" id="tab2">
                                <h3 class="block" >Receiver details</h3>
                                <div class="form-body">
                                    <div class="form-group form-md-line-input has-success">
                                        <label class="col-md-3 control-label" for="form_control_1">Address Names</label>
                                        <div class="col-md-9">
                                            <select id="addNewfield" class="form-control" name="receiver_id" >
                                                <option value="" selected>Select Address Name</option>
                                                <?php
                                                foreach($receivers as $receiver)
                                                {
                                                ?>
                                                <option value="<?php echo $receiver->id; ?>"><?php echo $receiver->address_line; ?></option>
                                                <?php
                                                }
                                                ?>
                                                <option value="addCompany">Add New Address Name</option>
                                                
                                            </select>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Some help goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input hiddeen-div" id="addCompany">
                                        <label class="col-md-3 control-label" for="form_control_1">Add New Address Name</label>
                                        <div class="col-md-9">
                                            <input type="text" name="receiver_address_line" value="<?php echo $receiver_address_line;?>" class="form-control" placeholder="">
                                            <input type="hidden" name="new_receiver" value="" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Address Line goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input" style="display: none">
                                        <label class="col-md-3 control-label" for="form_control_1">Reference Number</label>
                                        <div class="col-md-9">
                                            <input type="text" name="receiver_refence_no" value="<?php echo $receiver_reference_no;?>" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Account Number goes here...</span>
                                        </div>
                                    </div>
                                <div id="receiver">
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">Company Name</label>
                                        <div class="col-md-9">
                                            <input name="receiver_company_name" value="<?php echo $receiver_company_name;?>" type="text" class="form-control" placeholder="Company Name">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Receiver Company Name Type here...</span>
                                        </div>
                                    </div>   
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">City*</label>
                                        <div class="col-md-9">
                                            <input type="text" name="receiver_city" value="<?php echo $receiver_city;?>" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">City name goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input has-success">
                                        <label class="col-md-3 control-label" for="form_control_1">Country*</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="receiver_country_id" >
                                                <?php
                                                foreach($countries as $country)
                                                {
                                                    if($country->id == $receiver_country_id)
                                                    {
                                                ?>        
                                                        <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                <?php
                                                    }
                                                }
                                               ?>
                                            </select>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Some help goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">State / Province</label>
                                        <div class="col-md-9">
                                            <input name="receiver_state" value="<?php echo $receiver_state;?>" type="text" class="form-control" placeholder="State">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Receiver Company Name Type here...</span>
                                        </div>
                                    </div>   
                                    <div class="form-group form-md-line-input has-error">
                                        <label class="col-md-3 control-label" for="form_control_1">Address*</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" name="receiver_address" value="<?php echo $receiver_address;?>" required rows="3"></textarea>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Enter your Address...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">Postcode Code</label>
                                        <div class="col-md-9">
                                            <input type="text" name="receiver_postal_code" value="<?php echo $receiver_postal_code;?>" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Postcode goes here...</span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input has-success">
                                        <label class="col-md-3 control-label" for="form_control_1">Email*</label>
                                        <div class="col-md-9">
                                            <div class="input-group has-success">
                                                <input type="text" class="form-control" name="receiver_email" value="<?php echo $receiver_email;?>" placeholder="Email Address">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                    </div>                   
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">Name</label>
                                        <div class="col-md-9">
                                            <input name="receiver_name" value="<?php echo $receiver_name;?>" type="text" class="form-control" placeholder="Phone Person">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Phone Number Type here...</span>
                                        </div>
                                    </div>                    
                                    <div class="form-group form-md-line-input">
                                        <label class="col-md-3 control-label" for="form_control_1">Mobile Number*</label>
                                        <div class="col-md-9">
                                            <input type="text" name="receiver_mobile" value="<?php echo $receiver_mobile;?>" class="form-control" placeholder="Mobile Number">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Mobile Number Type here...</span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab3">
                                <h3 class="block">Consignment details</h3>
                                <div class="form-group form-md-line-input has-success">
                                    <label class="col-md-3 control-label" for="form_control_1">Title</label>
                                    <div class="col-md-9">
                                        <select id="addNewConsignment" class="form-control" name="consignment_id" >
                                            <option value="" selected>Select Title</option>
                                            <?php
                                            foreach($consignments as $consignment)
                                            {
                                            ?>
                                            <option value="<?php echo $consignment->id ;?>"><?php echo $consignment->title; ?></option>
                                            <?php
                                            }
                                            ?>
                                            
                                            <option value="addConsignment">Add New Consignment</option>
                                        </select>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Some help goes here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input hiddeen-div1" id="addConsignment">
                                        <label class="col-md-3 control-label" for="form_control_1">Consignment Title</label>
                                        <div class="col-md-9">
                                            <input type="text" name="title" value="<?php echo $title;?>" class="form-control" placeholder="">
                                            <input type="hidden" name="new_consignment" value="" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Title goes here...</span>
                                        </div>
                                </div>
                            <div id="package">
                                
                                    <div class="form-group form-md-line-input has-success">
                                        <label class="col-md-3 control-label" for="form_control_1">Type*</label>
                                        <div class="col-md-9">
                                            <select class="form-control" name="type" >
                                                <?php
                                                foreach($types as $type)
                                                {
                                                ?>
                                                    <option value="<?php echo $type->id; ?>"><?php echo $type->type; ?></option>
                                                <?php
                                                  
                                                }
                                               ?>
                                            </select>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Some help goes here...</span>
                                        </div>
                                    </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Weight* (KG)</label>
                                    <div class="col-md-9">
                                        <input type="text" name="weight" value="<?php echo $weight;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Weight value here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Height (cm)</label>
                                    <div class="col-md-9">
                                        <input type="text" name="height" value="<?php echo $height;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Height value here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Breath (cm)</label>
                                    <div class="col-md-9">
                                        <input type="text" name="breath" value="<?php echo $breath;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">breath value here...</span>
                                    </div>
                                </div>
                                 <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Width (cm)</label>
                                    <div class="col-md-9">
                                        <input type="text" name="width" value="<?php echo $width;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Width value here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Packages*</label>
                                    <div class="col-md-9">
                                        <input type="text" name="packages" value="<?php echo $packages;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">No of Packages value here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Sender Reference*</label>
                                    <div class="col-md-9">
                                        <input type="text" name="sender_ref" value="<?php echo $sender_reference;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Sender Reference here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Receiver Reference*</label>
                                    <div class="col-md-9">
                                        <input type="text" name="receiver_ref" value="<?php echo $receiver_reference;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Sender Reference here...</span>
                                    </div>
                                </div>
                                
                                
                                <div class="form-group form-md-line-input" style="display: none">
                                    <label class="col-md-3 control-label" for="form_control_1">Service Type*</label>
                                    <div class="col-md-9">
                                        <select name="service_id" class="form-control">
                                         <?php
                                         foreach($services as $service)
                                         {
                                         ?>
                                            <option value="<?php echo $service->id; ?>"><?php echo $service->service_name; ?></option>
                                         <?php
                                         }
                                         ?>
                                        </select>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">breath value here...</span>
                                    </div>
                                </div>
                            </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Payment Type*</label>
                                    <div class="col-md-9">
                                        <select name="payment_id" class="form-control">
                                         <?php
                                         foreach($payment_types as $payment_type)
                                         {
                                         ?>
                                            <option value="<?php echo $payment_type->id; ?>"><?php echo $payment_type->payment_type; ?></option>
                                         <?php
                                         }
                                         ?>
                                        </select>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Payment type value here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Tax Payer*</label>
                                    <div class="col-md-9">
                                        <select name="payer_id" class="form-control">
                                         <?php
                                         foreach($payers as $payer)
                                         {
                                         ?>
                                            <option value="<?php echo $payer->id; ?>"><?php echo $payer->tax_payer; ?></option>
                                         <?php
                                         }
                                         ?>
                                        </select>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Tax Payer value here...</span>
                                    </div>
                                </div>
                            </div>
                            <!--  4 th tabl-->
                            <div class="tab-pane" id="tab4">
                                <h3 class="block">Confirm your Order>>Pickup Details</h3>
                                <div class="form-group form-md-line-input has-success">
                                    <label class="col-md-3 control-label" for="form_control_1">Contact Person Name</label>
                                    <div class="col-md-9">
                                        <select id="addContact" class="form-control" name="person_id" >
                                            <option value="" selected>Select Pickup Address</option>
                                        <?php
                                        foreach($contact_persons as $contact_person)
                                        {
                                        ?>
                                            <option value="<?php echo $contact_person->person_id;?>"><?php echo $contact_person->person_name ; ?></option>
                                        <?php
                                        }
                                        ?>
                                            <option value="addNewContact">Add New Contact Person</option>
                                        </select>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Some help goes here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input hiddeen-div2" id="addNewContact">
                                        <label class="col-md-3 control-label" for="form_control_1">Contact Person Name</label>
                                        <div class="col-md-9">
                                            <input type="text" name="contact_person_name" value="<?php echo $contact_person_name;?>" class="form-control" placeholder="">
                                            <input type="hidden" name="new_contact_person" value="" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Title goes here...</span>
                                        </div>
                                </div>
                            <div id="person"> 
                               <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Contact Person Mobile*</label>
                                    <div class="col-md-9">
                                        <input type="text" name="contact_person_mobile" value="<?php echo $contact_person_mobile;?>" class="form-control" placeholder="Phone Number">
                                        <input type="hidden" name="new_order" value="new_order" class="form-control" placeholder="">

                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Contact Person Mobile Type here...</span>
                                    </div>
                                </div>
                            </div>
                                
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Order Remarks*</label>
                                    <div class="col-md-9">
                                        <input type="text" name="remarks" value="<?php echo $remarks;?>" class="form-control" placeholder="" required>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Sender Reference here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">Insured*</label>
                                    <div class="col-md-9">
                                        <div class="clearfix">
                                            <div class="btn-group" data-toggle="buttons">
                                                    <input class="toggle" name="insured" type="checkbox" value="insured">
                                              
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Pickup Date*</label>
                                    <div class="col-md-3">
                                        <div class="input-group input-medium date date-picker" id="datetimepicker" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
                                            <input name="date" type="text" class="form-control" readonly>
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <!-- /input-group -->
                                        <span class="help-block"> Select Date </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                   <label class="control-label col-md-3">Pickup Time*</label>
                                   <div class="col-md-3">
                                       <div class="input-group">
                                           <input type="text" name="time" class="form-control timepicker timepicker-no-seconds">
                                           <span class="input-group-btn">
                                               <button class="btn default" type="button">
                                                   <i class="fa fa-clock-o"></i>
                                               </button>
                                           </span>
                                       </div>
                                   </div>
                               </div>
<!--                                <div class="form-group form-md-line-input has-success">
                                    <label class="col-md-3 control-label" for="form_control_1">Pickup address</label>
                                    <div class="col-md-9">
                                        <select id="addPickup" class="form-control" name="pickup_id" >
                                            <option value="addNewPickup" selected>--Pickup Address--</option>
                                        <?php
//                                        foreach($pickup_addresses as $pickup_addresses)
//                                        {
                                        ?>
                                            <option value="<?php //echo $pickup_addresses->pickup_id;?>"><?php// echo $pickup_addresses->pickup_address ; ?></option>
                                        <?php
                                      //  }
                                        ?>
                                            <option value="addNewPickup">Add New Pickup Address</option>
                                        </select>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">Some help goes here...</span>
                                    </div>
                                </div>
                                <div class="form-group form-md-line-input hiddeen-div3" id="addNewPickup">
                                        <label class="col-md-3 control-label" for="form_control_1">Enter Pickup Address</label>
                                        <div class="col-md-9">
                                            <input type="text" name="pickup_address" value="<?php //echo $pickup_address;?>" class="form-control" placeholder="">
                                            <input type="hidden" name="new_pickup_address" value="new_pickup_address" class="form-control" placeholder="">
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">Title goes here...</span>
                                        </div>
                                </div>-->
                            </div>
                            <!-- 4th atbl-->
                        </div>
                    </div>
                    <div class="form-body view">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Sender Details</h2>
                            </div>
                       </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender Address Line</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="company1"></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender City</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="city1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender Country</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="country1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender state</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="state1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="address1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender PostCode</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="postcode1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="email1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="name1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Sender Mobile</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="mobile1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Receiver Details</h2>
                            </div>
                       </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver Address Line</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="company2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver Company Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="receiver_company"></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver City</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="city2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver Country</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="country2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver State</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="state2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="address2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver PostCode</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="postcode2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="email2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver Phone No</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="phone2"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Receiver Mobile</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="mobile2"></label>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <h2>Consignment Details</h2>
                            </div>
                       </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Consignment Title</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="title"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Consignment Type</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="type"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Consignment Weight</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="weight"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Consignment Width</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="width"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Consignment Height</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="height"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Consignment Breath</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="breath"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Packages</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="packages"></label>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <h2>Contact Person Details</h2>
                            </div>
                       </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Contact Person Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="name"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Contact Person Mobile</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="mobile"></label>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <h2>Pickup Details</h2>
                            </div>
                       </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Pickup Date</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="date"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Pickup Time</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="time"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Tax Payer</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="payer"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Remarks</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="remarks"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="font-weight:bold">Total Bill</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="order_bill"></label>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <a href="javascript:void(0);" class="btn default button-previous">
                                    <i class="fa fa-angle-left"></i> Back </a>
                                <a href="javascript:void(0);" class="btn btn-outline green button-next"> Continue
                                    <i class="fa fa-angle-right"></i>
                                </a>
<!--                                <a href="javascript:;" class="btn green button-submit" onclick="document.getElementById('form1').submit();"> Submit
                                    <i class="fa fa-check"></i>
                                </a>-->
                                <a href="javascript:void(0);" class="btn green button-submit" onclick=""> View Summary
                                    <i class="fa fa-check"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$this->load->view('layout/footer');   
?>
