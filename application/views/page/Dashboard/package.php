		        <?php
        $this->load->view('layout/header');
        $this->load->view('layout/container');
		?>
                    <!-- BEGIN PAGE BASE CONTENT -->
                   <div class="row">				  
					   <form action="admin_h.php" method="POST" autocomplete="off">
							<div class="col-md-6">
							<div class="form-group">
		                    <label>Package Type</label>
		                    <input class="form-control spinner" type='text' name='p_type' required size='35'>
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group">
							<br/>
							<input type='submit' name='action' value='Add Package' class="btn green">
							</div>
                            </div>
						</form>
				<div class="clearfix">&nbsp;</div>
                					  <div class="portlet box red">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Available Package</div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-responsive">
                                    <?php /*
                                    $packages=$bam->get_table_array('package_table');

									if(!empty($packages))
									{
										*/
										echo '<table class="table">';
										echo '<thead><tr><td>Serial No.</td><td>Package</td><td>Created On</td></tr></thead>';
										/*
										$i=1;

										foreach($packages AS $p)
										{
											echo '<tbody><tr>';

												echo '<td>'.$i.'</td>';
												echo '<td>'.$p['p_name'].'</td>';
												echo '<td>'.$p['edt'].'</td>';		
											echo '</tr></tbody>';
											$i++;
										} */
										echo '</table>';
									// }
									?>
                                    </div>
                                </div>
</div>
                    <!-- END PAGE BASE CONTENT -->
                            <?php
        $this->load->view('layout/footer');   
        ?>
