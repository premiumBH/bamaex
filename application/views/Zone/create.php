<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $this->load->view('layout/header');

    $this->load->view('layout/container');
    
    if(isset($zone_name) && $zone_name != '')
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
                    <h2>Zone Management</h2>
                </div>
	   </div>
            <form action="<?=CTRL?>zone/create" method="POST" autocomplete="off">
                <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Zone Name</label>
                             <input name='zone_name' required class="form-control spinner" type="text" placeholder="Zone Name" value="<?php echo $zone_name; ?>"> 
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Price 1</label>
                             <input name='zone_price1' required class="form-control spinner" type="text" placeholder="Price 1" value="<?php echo $zone_price1; ?>"> 
                         </div>
                     </div>
                </div>
                <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Price 2</label>
                             <input name='zone_price2' required class="form-control spinner" type="text" placeholder="Price 2" value="<?php echo $zone_price2; ?>"> 
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Price 3</label>
                             <input name='zone_price3' required class="form-control spinner" type="text" placeholder="Price 3" value="<?php echo $zone_price3; ?>"> 
                         </div>
                     </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group">
                <?php
                    if($flag == 1)
                    {
                        // edit
                        
                       echo '<input type="hidden" value="1" id="" name="update_zone" />';
                    }
                    else
                    {
                        // create
                        echo '<input type="hidden" value="1" id="" name="add_new_zone" />';
                    }
                ?>
                             <input type="hidden" value="<?php echo $zone_id;?>" name="zone_id" />
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
                    
                </div>
            </form>


<?php

     $this->load->view('layout/footer');   

?>



