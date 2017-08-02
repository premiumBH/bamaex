<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    $this->load->view('layout/header');
    $this->load->view('layout/container');
?>


    
           <div class="row">
                <div class="col-md-12">
                    <h2>Order Management</h2>
                </div>
	   </div>    

           <form action="<?=CTRL?>Order/create" method="POST" autocomplete="off">
               <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>Company Name</label>
                             <select required class="form-control spinner" name="client_id">
                             <?php
                             foreach ($clients as $client)
                             {
                             ?>
                                 <option value="<?php echo $client->client_id ;?>"><?php echo $client->company_name;?></option>
                             <?php
                             }
                             ?>
                             </select>
                         </div>
                     </div>
               </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <input type="submit" value="Select" class="btn green" >
                         </div>
                     </div>
           </form>
    
    
    
    
    
    
    
    
    
    
    
    
    
<?php

     $this->load->view('layout/footer');   

?>