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
        <h2>Airway Bill Generation</h2>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <h3>Order Has Been Generated Successfully.</h3>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <a href="<?php echo base_url().'Order/generateAirwaybill?ref-id='.$serial_number; ?>" class="btn btn-default-focus">Generate Airway Bill</a>
    </div>
</div>


