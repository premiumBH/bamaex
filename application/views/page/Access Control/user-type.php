<?php
  $this->load->view('layout/header');
  $this->load->view('layout/container');
$flag = 0;
$ps1 = '';
if(isset($_REQUEST['edit-id']))
{ 
  if($_REQUEST['edit-id'] != '')
  {
	  $id1 = $_REQUEST['edit-id'];
			$sqlQuery = "select
    intUserTypeId,
    varUserTypeName from user_type where intUserTypeId = $id1";
			$result = $this->db->query($sqlQuery);
			
			if($result->num_rows()>0) {
				foreach($result->result() AS $result11)
				{					
				// print_r($result11);
						$flag = 1;
						$pl1 = $result11->intUserTypeId;
						$ps1 = $result11->varUserTypeName;
                 }
            } 
  }
}
?>
                    <!-- BEGIN PAGE BASE CONTENT -->
                   <div class="row">				  
                <div class="col-md-12">
               					   <form action="<?=CTRL?>user-type" method="POST" autocomplete="off">
							<div class="col-md-6">
							<div class="form-group">
		                    <label>User Type</label>
		                    <input class="form-control spinner" type='text' name='user_type_add' required size='35' value="<?php echo $ps1; ?>">
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group">
							<br/>
                          
                            <?php
                            if($flag == 1)
							{
								
								echo '<input type="hidden" value="'.$id1.'" id="" name="update_single_user_type" />'."<input type='submit' name='action-update-page' value='Update User Type' class='btn green'>";
							}
							else
							{
								echo '<input type="hidden" value="update" id="" name="add_new_user_type" />'."<input type='submit' name='action-add-user-type' value='Add User Type' class='btn green'>";
							}
							?>
							
							</div>
                            </div>
						</form>
<br /><br />
</div>
</div>
                   <div class="row">				  
                <div class="col-md-12">

                					  <div class="portlet box red">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Permission for Create/Update User Roles</div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <!-- 
                                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    --></div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive">
                                    <?php

									if($result1['status'] = 'true')
									{ 
							
										?>
                                      
                                        <form action="<?=CTRL?>user-type" method="POST" autocomplete="off">
                                        <input type="hidden" value="update" id="" name="update_access_user_type" />
                                        <?php
										echo '<table class="table">';
										echo '<thead><tr><td>Serial No.</td><td>User Type</td><td>Admin</td><td>Staff</td><td>Client</td><td>Other</td><td>Edit Page</td></tr></thead><tbody>';

										foreach($result1['pages'] AS $result)
										{
											$admin = ''; $staff = ''; $client = ''; $other = '';
											if($result->Admin == 1) { $admin = 'checked="checked"'; }
											if($result->Staff == 1) { $staff = 'checked="checked"'; }
											if($result->Client == 1) { $client = 'checked="checked"'; }
											if($result->Other == 1) { $other = 'checked="checked"'; }
											echo '<tr>'
												.'<td><input type="hidden" value="'.$result->role_id.'" name="id[]" />'.$result->role_id.'</td>'
												.'<td>'.$result->role_name.' Can create/update</td>'
												.'<td><input class="icheck" name="admin['.$result->role_id.']" '.$admin.' type="checkbox"></td>'
												.'<td><input class="icheck" name="staff['.$result->role_id.']" '.$staff.' type="checkbox"></td>'
												.'<td><input class="icheck" name="client['.$result->role_id.']" '.$client.' type="checkbox"></td>'
												.'<td><input class="icheck" name="other['.$result->role_id.']" '.$other.' type="checkbox"></td>'
												.'<td><a href="'.CTRL.'user-type?edit-id='.$result->role_id.'"><input value="Edit" class="btn red" type="button"></a></td>'
											    .'</tr>';
										} 
										echo '</tbody></table>'."<input type='submit' name='action' value='Update Permission' class='btn green'>";
										?>
                                        </form>
										<?php
									 }
									?>
                                    </div>
                                </div>
</div>
</div>
</div>
                    <!-- END PAGE BASE CONTENT -->


<?php
  $this->load->view('layout/footer');   
?>
