		        <?php
        $this->load->view('layout/header');
        $this->load->view('layout/container');
		?>

                    <!-- BEGIN PAGE BASE CONTENT -->
                   <div class="row">
				   <div class="col-md-12">
				  <h2>Add Service Type</h2>
                      </div>
				   </div>
				   <div class="row">
                   <form action="admin_h.php" method="POST" autocomplete="off">
    					<div class="col-md-6">
    					<div class="form-group">
                        <label>Service Type</label>
                        <input class="form-control spinner"  type='text' name='s_type' required placeholder="Service Type"> 
    					</div>
    					</div>
    					<div class="col-md-6">
    					<div class="form-group">
    					<br/>
    					<input type='submit' name='action' value='Add Service' class="btn green">
    					</div>
    					</div>
                    </form>
					</div>
				<div class="clearfix">&nbsp;</div>
					<div class="row">
					<div class="col-md-12">
					  <div class="portlet box red">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-cogs"></i>Available Services</div>
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
                                            $services=$bam->get_table_array('service_table');

                                            if(!empty($services))
                                            {
												
												*/
                                                echo '<table class="table">';
                                                echo '<thead><tr><td>Serial No.</td><td>Service</td><td>Created On</td></tr></thead>';
                                                /* $i=1;

                                                foreach($services AS $s)
                                                {
                                                    echo '<tr>';
                                                        echo '<td>'.$i.'</td>';
                                                        echo '<td>'.$s['service_name'].'</td>';
                                                        echo '<td>'.$s['edt'].'</td>';      
                                                    echo '</tr>';

                                                    $i++;
                                                } */
                                                echo '</table>';
                                            // }
                                            ?>
                                    </div>
                                </div>
                            </div>
				 
					
					</div>
					
					</div>
					
            </div>
                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->
                            <?php
        $this->load->view('layout/footer');   
        ?>
