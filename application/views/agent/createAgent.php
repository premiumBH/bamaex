<?php

$this->load->view('layout/header');

$this->load->view('layout/container');
?>

        <!-- BEGIN PAGE BASE CONTENT -->

                   <div class="row">
                       <?php echo validation_errors('<div class="alert alert-success alert-dismissible">', '</div>'); ?>
                       <?php echo $this->session->flashdata('error')?>
				   <div class="col-md-12">

				  <h2>Agent Registration</h2>

                      </div>

				   </div>

                    <form action="<?=CTRL?>AjentOrders/createEditAgent/<?=isset($id)?$id:'';?>" method="POST" id="addUserForm" autocomplete="off">

    				   <div class="row">

                            <div class="col-md-6">

                            <div class="form-group">

                            <label>First Name</label>

                            <input name='first_name' required class="form-control spinner" type="text" placeholder="First Name" value="<?=isset($userTable)?$userTable->varFirstName:'';?>">

                            </div>

                            </div>

                            <div class="col-md-6">

                            <div class="form-group">

                            <label>Last Name</label>

                            <input class="form-control spinner" type="text" placeholder="Last Name" name='last_name' required value="<?=isset($userTable)?$userTable->varLastName:'';?>">

                            </div>

                            </div>

    					</div>

    					<div class="row">

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Email</label>

                        <input class="form-control spinner" type='email' name='email' required placeholder="Email" value="<?=isset($userTable)?$userTable->varEmailId:'';?>">

    					</div>

    					</div>

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Mobile</label>

                        <input class="form-control spinner" name='mobile' type="text" placeholder="Mobile" required value="<?=isset($userTable)?$userTable->varMobileNo:'';?>">

    					</div>

    					</div>

    					</div>

    					<div class="row">

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Password</label>

                        <input class="form-control spinner" name='password' type="password" placeholder="Password" value="">

    					</div>

    					</div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>Country</label>

                                    <select name='country' required class="form-control">
                                        <?php
                                        $sqlQuery = " SELECT * "
                                            ." FROM country_table";
                                        $result = $this->db->query($sqlQuery);

                                        if($result->num_rows()>0) {
                                            foreach($result->result() AS $result11) {?>
                                                <option value="<?=$result11->id;?>" <?php if(isset($userTable) && $userTable->country_id == $result11->id){echo "selected";}?> >
                                                    <?=$result11->country_name?>
                                                </option>
                                            <?php }
                                        }

                                        ?>

                                    </select>

                                </div>

                            </div>

    					</div>

                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>City</label>

                                    <input class="form-control spinner" name='city' type="text" placeholder="City" value="">

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>Company Name</label>

                                    <input class="form-control spinner" name='company_name' type="text" placeholder="Company Name" value="">

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>Address</label>

                                    <input class="form-control spinner" name='address' type="text" placeholder="Address" value="">

                                </div>

                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Domestic Rate</label>
                                    <input name='domestic_rate' required class="form-control spinner" type="text" placeholder="Domestic Rate" value="<?=isset($agentTable)?$agentTable->domestic_rates:'';?>">
                                </div>
                            </div>

                            <?php
                            $counter = 0;
                            foreach ($zones as $zone)
                            {?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $zone->zone_name; ?> Rate</label>
                                        <?php if(isset($agentRate)){
                                            foreach($agentRate as $agentRatesIn){
                                                if($agentRatesIn->zone_id == $zone->zone_id){
                                            ?>
                                            <input name='zone[<?php echo $zone->zone_id; ?>]' required class="form-control spinner" type="text" placeholder="<?php echo $zone->zone_name; ?> Rate"
                                                   value="<?=$agentRatesIn->zone_rate?>">
                                                <?php } ?>
                                                <?php }?>
                                        <?php }else{?>
                                            <input name='zone[<?php echo $zone->zone_id; ?>]' required class="form-control spinner" type="text" placeholder="<?php echo $zone->zone_name; ?> Rate"
                                               value="">
                                       <?php }?>
                                    </div>
                                </div>
                                <?php } ?>

                        </div>

    					<div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Weight Per Price</label>
                                    <input name='weight_per_price' required class="form-control spinner" type="text" placeholder="Weight Per Price" value="<?=isset($agentTable)?$agentTable->weight_per_price:'';?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Assign To</label>
                                    <select required class="form-control spinner" name="creater_id">
                                        <?php
                                        foreach ($users as $user)
                                        {?>
                                            <option value='<?=$user->intUserId;?>' <?php if(isset($agentTable) && $agentTable->assign_to == $user->intUserId){echo 'selected';}?>>
                                                <?=$user->varEmailId?>
                                            </option>
                                       <?php  }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-top: 25px;">
                                <input type='submit' name='action-update-user' value='<?=isset($id)?'Update':'Save';?>' class='btn green'>
                            </div>
                        </div>

                    </form>

<div class="row" style="margin-bottom: 80px;"></div>
<?php
$this->load->view('layout/footer');
?>

