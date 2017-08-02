<?php
  $this->load->view('layout/header');
  $this->load->view('layout/container');

?>
                    <!-- BEGIN PAGE BASE CONTENT -->
                   <div class="row">				  
                <div class="col-md-12">
                <a href="<?=CTRL?>page/create"><input value="Create User" class="btn green" type="button"></a><br /><br />
                					  <div class="portlet box red">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Pages Permission</div>
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
                                      
                                        <form action="<?=CTRL?>pages" method="POST" autocomplete="off">
                                        <input type="hidden" value="update" id="" name="update_access_pages" />
                                        <?php
										echo '<table class="table">';
										echo '<thead><tr><td>Serial No.</td><td>Menu Label</td><td>Page slug</td><td>Menu Icon</td><td>Admin</td><td>Staff</td><td>Client</td><td>Other</td><td>Created On</td><td>Edit Page</td></tr></thead><tbody>';

										foreach($result1['pages'] AS $result)
										{
											$admin = ''; $staff = ''; $client = ''; $other = '';
											if($result->Admin == 1) { $admin = 'checked="checked"'; }
											if($result->Staff == 1) { $staff = 'checked="checked"'; }
											if($result->Client == 1) { $client = 'checked="checked"'; }
											if($result->Other == 1) { $other = 'checked="checked"'; }
											echo '<tr>'
												.'<td><input type="hidden" value="'.$result->Id.'" name="id[]" />'.$result->Id.'</td>'
												.'<td>'.$result->label.'</td>'
												.'<td>'.$result->PageSlug.'</td>'
												.'<td>'.$result->icon.'</td>'
												.'<td><input class="icheck" name="admin['.$result->Id.']" '.$admin.' type="checkbox"></td>'
												.'<td><input class="icheck" name="staff['.$result->Id.']" '.$staff.' type="checkbox"></td>'
												.'<td><input class="icheck" name="client['.$result->Id.']" '.$client.' type="checkbox"></td>'
												.'<td><input class="icheck" name="other['.$result->Id.']" '.$other.' type="checkbox"></td>'
												.'<td>'.$result->dt.'</td>'
												.'<td><a href="'.CTRL.'page/create?edit-id='.$result->Id.'"><input value="Edit" class="btn red" type="button"></a></td>'
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
