<?php

$this->load->view('layout/header');

$this->load->view('layout/container');

?>

            <div class="row">
                <div class="col-md-12">
                    <h2>Prospect Management</h2>
                </div>
	   </div>
            <form action="<?=CTRL?>ClientManagement/toProspect" method="POST" autocomplete="off">
               
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Domestic Rate</label>
                             <input name='domestic_rate' required class="form-control spinner" type="text" placeholder="Domestic Rate" value=""> 
                         </div>
                     </div>
                <?php
                     $counter = 0;
                     foreach ($zones as $zone)
                     {
                ?>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label><?php echo $zone->zone_name; ?> Rate</label>
                             <input name='zone[]' required class="form-control spinner" type="text" placeholder="<?php echo $zone->zone_name; ?> Rate" value=""> 
                             <input type='hidden' name='zone_id[]' required class="form-control spinner" value="<?php echo $zone->zone_id; ?>"> 
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
                        <label>First Name</label>
                        <input name='first_name' required class="form-control spinner" type="text" placeholder="First Name" value="<?php echo $first_name;?>"> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input name='last_name' required class="form-control spinner" type="text" placeholder="Last Name" value="<?php echo $last_name;?>"> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address</label>
                        <input name='address' required class="form-control spinner" type="text" placeholder="address" value="<?php echo $address;?>"> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Phone No</label>
                        <input name='phone_no' required class="form-control spinner" type="text" placeholder="Phone No" value="<?php echo $phone_no;?>"> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input name='email' required class="form-control spinner" type="text" placeholder="Email" value="<?php echo $email;?>"> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        
                    <?php
                                    //var_dump($client_id);
//                       if($flag == 1)
//                       {
//                           echo '<input type="submit" value="Update" class="btn green"/>';
//                       }
//                       else
//                       {
                           echo '<input type="hidden" name="client_id" value="'.$client_id.'" />';
                           echo '<input type="submit" value="Save" class="btn green"/>';

                    //   }
                    ?>

                    </div>
               </div>
                
            </form>
















<?php

       $this->load->view('layout/footer');   

?>