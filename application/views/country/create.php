<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $this->load->view('layout/header');

    $this->load->view('layout/container');
    
    if(isset($country_name) && $country_name != '')
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
                    <h2>Country Management</h2>
                </div>
	   </div>
            <form action="<?=CTRL?>country/create" method="POST" autocomplete="off">
                <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Country Name</label>
                             <input name='country_name' required class="form-control spinner" type="text" placeholder="country Name" value="<?php echo $country_name; ?>"> 
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Country Code</label>
                             <input name='country_code' required class="form-control spinner" type="text" placeholder="Country Code" value="<?php echo $country_code; ?>"> 
                         </div>
                     </div>
                </div>
                <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Zone</label>
                             <select name="zone_id" required class="form-control spinner">
                             <?php
                             
                             foreach($zones as $zone)
                             {
                                 if($zone->zone_id == $zone_id)
                                 {
                            ?>
                                 <option value="<?php echo $zone->zone_id;?>" selected><?php echo $zone->zone_name?></option>
                            <?php
                                     
                                 }
                                 else
                                 {
                            ?>
                                 <option value="<?php echo $zone->zone_id;?>"><?php echo $zone->zone_name?></option>

                            <?php
                                 }
                                 
                             }
                             ?>
                             </select>
                         </div>
                     </div>
                    <div class="col-md-6">
                         <div class="form-group">
                <?php
                    if($flag == 1)
                    {
                        // edit
                        
                       echo '<input type="hidden" value="1" id="" name="update_country" />';
                    }
                    else
                    {
                        // create
                        echo '<input type="hidden" value="1" id="" name="add_new_country" />';
                    }
                ?>
                             <input type="hidden" value="<?php echo $id;?>" name="id" />
                     </div>
                    </div>
                     
                </div>
                <div class="row">
                    
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
                    
                </div>
            </form>


<?php

     $this->load->view('layout/footer');   

?>



