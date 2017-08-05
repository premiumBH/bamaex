<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $this->load->view('layout/header');

    $this->load->view('layout/container');
    
    if(isset($client_id) && $client_id != '')
    {
        $flag = 1;
    }
    else
    {
        $flag = 0;
    }

?>

            <div class="row">
                <div class="col-md-12">
                    <h2>Client Management</h2>
                </div>
	   </div>
            <form action="<?=CTRL?>ClientManagement/create" method="POST" autocomplete="off">
                <!--<div class="row">-->
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Company Name</label>
                             <input name='company_name' required class="form-control spinner" type="text" placeholder="Company Name" value="<?php echo $company_name; ?>"> 
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Company Website</label>
                             <input name='company_website' required class="form-control spinner" type="text" placeholder="Company Website" value="<?php echo $company_website; ?>"> 
                         </div>
                     </div>
                <!--</div>-->
                <!--<div class="row">-->
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Email</label>
                             <?php 
                             if($flag == 1)
                             {
                                ?>
                                <input readonly name='email' required class="form-control spinner" type="text" placeholder="Email" value="<?php echo $email; ?>"> 
                                <?php
                             }
                             else
                            {
                                 ?>
                                <input name='email' required class="form-control spinner" type="text" placeholder="Email" value="<?php echo $email; ?>"> 
                                <?php
                            }
                             ?>
                             
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Phone No</label>
                             <input name='phone_no' required class="form-control spinner" type="text" placeholder="Phone No" value="<?php echo $phone_no; ?>"> 
                         </div>
                     </div>
                <!--</div>-->
                <!--<div class="row">-->
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Address</label>
                             <input name='address' required class="form-control spinner" type="text" placeholder="address" value="<?php echo $address; ?>"> 
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>City</label>
                             <input name='city' required class="form-control spinner" type="text" placeholder="City" value="<?php echo $city; ?>"> 
                         </div>
                     </div>
                <!--</div>-->
                <!--<div class="row">-->
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Country</label>
                             <!--<input name='address' required class="form-control spinner" type="text" placeholder="address" value="<?php echo $address; ?>">-->                                                           
                             <select required class="form-control spinner" name="country_id">
                                 <?php
                                 foreach ($countries as $country)
                                 {
                                     if($country_id == $country->id)
                                        echo '<option value='.$country->id.' selected>'.$country->country_name.'</option>';
                                     else
                                        echo '<option value='.$country->id.'>'.$country->country_name.'</option>';
 
                                     
                                 }
                                 ?>
                             </select>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Client Type</label>
                             <input name='client_type' required class="form-control spinner" type="text" placeholder="Client Type" value="<?php echo $level_id; ?>" readonly> 
                         </div>
                     </div>
                <!--</div>-->
                <?php
                                 
                    if(isset($level_id) && $level_id != '' && $level_id != 'CONTACT')
                    {
                       
                ?>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Domestic Rate</label>
                             <input name='domestic_rate' required class="form-control spinner" type="text" placeholder="Domestic Rate" value="<?php echo $domestic_rate; ?>" > 
                         </div>
                     </div>
                
                <?php
                        foreach($zone_rates as $zone)
                        {
                            //die(var_dump($zone));
                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $zone->zone_name ;?> Rate</label>
                                    <input name='zone_id[]' required class="form-control spinner" type="hidden" placeholder="" value="<?php echo $zone->zone_id; ?>" > 
                                    <input name='zone[]' required class="form-control spinner" type="text" placeholder="" value="<?php echo $zone->zone_rate ;?>" > 
                                </div>
                            </div> 
                            <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Primary Contact Person Details</h3>
                            </div>
                       </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Person First Name</label>
                                <input name='primary_first_name' required class="form-control spinner" type="text" placeholder="First Name" value="<?php echo $primary_first_name;?>"> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Person Last Name</label>
                                <input name='primary_last_name' required class="form-control spinner" type="text" placeholder="Last Name" value="<?php echo $primary_last_name;?>"> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Person Address</label>
                                <input name='primary_address' required class="form-control spinner" type="text" placeholder="address" value="<?php echo $primary_address;?>"> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Person Phone No</label>
                                <input name='primary_phone_no' required class="form-control spinner" type="text" placeholder="Phone No" value="<?php echo $primary_phone_no;?>"> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact Person Email</label>
                                <input name='primary_email' required class="form-control spinner" type="text" placeholder="Email" value="<?php echo $primary_email;?>"> 
                            </div>
                        </div>
                        <?php
                    }
                ?>
                
                
                <!--<div class="row">-->
                    <div class="col-md-6">
                         <div class="form-group">
                <?php
                    if($flag == 1)
                    {
                        // edit
                        
                       echo '<input type="hidden" value="1" id="" name="update_client" />';
                       echo '<input type="hidden" value="'.$client_id.'" name="client_id" />';
                    }
                    else
                    {
                        // create
                        echo '<input type="hidden" value="1" id="" name="add_new_client" />';
                        echo '<input type="hidden" value="'.$client_id.'" name="client_id" />';
                    }
                ?>
                             
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                         <?php
                            if($flag == 1)
                            {
                                echo '<input type="submit" value="Update" class="btn green"/>';
                            }
                            else
                            {
                                echo '<input type="submit" value="Save" class="btn green"/>';

                            }
                         ?>
                             
                             </div>
                    </div>
                    
                <!--</div>-->
            </form>


<?php

     $this->load->view('layout/footer');   

?>



