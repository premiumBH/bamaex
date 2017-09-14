		        <?php

        $this->load->view('layout/header');

        $this->load->view('layout/container');

		$this->load->library('encrypt');

$flag = 0;

$ids = '';

						$fn = '';

						$ln = '';

						$em = '';

						$mn = '';

						$pass = '';

						$sid1 = '';



if(isset($_REQUEST['edit-id']))

{ 

  if($_REQUEST['edit-id'] != '')

  {

      $selectedUserType = '';

	  $id1 = $_REQUEST['edit-id'];

			$sqlQuery = "SELECT * FROM user LEFT JOIN user_type ON user_type.intUserTypeId=user.intUserTypeId where user.intUserId = $id1";

			$result = $this->db->query($sqlQuery);

			$userData = $result->result();

			

			if($result->num_rows()>0) {

				foreach($result->result() AS $result11)

				{					

				// print_r($result11);

						$flag = 1;

						$ids = $result11->intUserId;

						$fn = $result11->varFirstName;

						$ln = $result11->varLastName;

						$em = $result11->varEmailId;

						$mn = $result11->varMobileNo;

						$pass = $this->encrypt->decode($result11->varPassword);

					    $cn   = $result11->country_id;
                        $selectedUserType = $result11->intUserTypeId;

						$sid1 = '<option value="'.$result11->intUserTypeId.'">'.$result11->varUserTypeName.'</option>';

                 }

            } 

  }

}



                $allUserType                = $this->User_model->getAllUserType();
                $allowUserType              = array();

                if($this->session->all_userdata()['UserTypeCode'] == 1007){
                    $allowUserType = array();
                }
                else if($this->session->all_userdata()['UserTypeCode'] == 1004){
                    $allowUserType = array();
                }
                else if($this->session->all_userdata()['UserTypeCode'] == 1001){
                    foreach ($allUserType as $allUserTypeIn){
                        if($allUserTypeIn->intUserTypeId != 1 && $allUserTypeIn->intUserTypeId != 5){
                            $allowUserType[] = $allUserTypeIn;
                        }

                    }
                }
                else if($this->session->all_userdata()['UserTypeCode'] == 1002){
                    foreach ($allUserType as $allUserTypeIn){
                        if($allUserTypeIn->intUserTypeId != 1 && $allUserTypeIn->intUserTypeId != 5){
                            $allowUserType[] = $allUserTypeIn;
                        }
                    }
                }
                else if($this->session->all_userdata()['UserTypeCode'] == 1003){
                    foreach ($allUserType as $allUserTypeIn){
                        if($allUserTypeIn->intUserTypeId == 8){
                            $allowUserType[] = $allUserTypeIn;
                        }

                    }
                }
                else if($this->session->all_userdata()['UserTypeCode'] == 1006){
                    foreach ($allUserType as $allUserTypeIn){
                        if($allUserTypeIn->intUserTypeId != 1 && $allUserTypeIn->intUserTypeId != 2 && $allUserTypeIn->intUserTypeId != 7 && $allUserTypeIn->intUserTypeId != 5){
                            $allowUserType[] = $allUserTypeIn;
                        }

                    }
                }


		?>

        <!-- BEGIN PAGE BASE CONTENT -->

                   <div class="row">
                       <?php echo $this->session->flashdata('error')?>
				   <div class="col-md-12">

				  <h2>User Registration</h2>

                      </div>

				   </div>

                    <form action="<?=CTRL?>user/create" method="POST" id="addUserForm" autocomplete="off">

    				   <div class="row">

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>First Name</label>

                        <input name='first_name' required class="form-control spinner" type="text" placeholder="First Name" value="<?php echo $fn; ?>"> 

    					</div>

    					</div>

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Last Name</label>

                        <input class="form-control spinner" type="text" placeholder="Last Name" name='last_name' required value="<?php echo $ln; ?>"> 

    					</div>

    					</div>

    					</div>

    					<div class="row">

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Email</label>

                        <input class="form-control spinner" type='email' name='email' required placeholder="Email" value="<?php echo $em; ?>"> 

    					</div>

    					</div>

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Mobile</label>

                        <input class="form-control spinner" name='mobile' type="text" placeholder="Mobile" required value="<?php echo $mn; ?>"> 

    					</div>

    					</div>

    					</div>

    					<div class="row">

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Password</label>

                        <input class="form-control spinner" name='password' type="password" placeholder="Password" required value="<?php echo $pass; ?>"

                        > 

    					</div>

    					</div>

    					<div class="col-md-6">

    					<div class="form-group">

                                                    <label>User Level</label>

                                                    <select name="staff_level_id" required class="form-control">                 

                                                   <?php foreach($allowUserType as $allowUserTypeIn){?>
                                                       <option value="<?php echo $allowUserTypeIn->intUserTypeId?>"
                                                       <?php if(isset($_REQUEST['edit-id']) && $_REQUEST['edit-id'] != '' && $allowUserTypeIn->intUserTypeId == $userData[0]->intUserTypeId){echo 'selected';}?> >
                                                           <?php echo $allowUserTypeIn->varUserTypeName?>
                                                       </option>
                                                  <?php }?>


                                                </select>

                                                </div>

    					</div>

    					</div> 

    					<div class="row">

    					<div class="col-md-6">

    					<div class="form-group">

                        <label>Country</label>

                        <select name='country' required class="form-control">
<?php
                        $sqlQuery = " SELECT * "                          
                            ." FROM country_table";

                        $result = $this->db->query($sqlQuery);          

                        if($result->num_rows()>0) 
                        {

                            foreach($result->result() AS $result11)

                            {  
                                if(isset($_REQUEST['edit-id']) && $_REQUEST['edit-id'] != '' && $result11->id == $cn)

                                { 

                                    echo '<option value="'.$result11->id.'" selected>'.$result11->country_name.'</option>';
                                  
                                }
                                else
                                {
                                    echo '<option value="'.$result11->id.'">'.$result11->country_name.'</option>';
                                }


                                    

                            }

                        } 



?>
                            
                        </select>

                        </div>

    					</div>

    					<div class="col-md-6">

                            <?php

                            if($flag == 1)

							{

								

								echo '<input type="hidden" value="'.$id1.'" id="" name="update_user" />'."<input type='submit' name='action-update-user' value='Update' class='btn green'>";

							}

							else

							{

								echo ' <input type="hidden" value="update" id="" name="add_new_user" />'."<input type='submit' name='action-add-user' value='Register' class='btn green'>";

							}

							?>

                    </form>

					</div>

					</div>

                            </div>

				 

					

					</div>

					

					</div>

					

            </div>

                        </div>

                    </div>

                            <?php

        $this->load->view('layout/footer');   

        ?>
                <!--<script>
                    $(function(){
                        $('#addUserForm').submit(function () {
                            $.post( "test.php", { func: "getNameAndTime" }, function( data ) {

                            }, "json");
                        });
                    })
                </script>-->
