		        <?php
        $this->load->view('layout/header');
        $this->load->view('layout/container');
		?>
                     <!-- BEGIN PAGE BASE CONTENT -->
                   <div class="row">
				   <div class="col-md-12">
				  <h2>Add Order Status</h2>
                      </div>
				   </div>
				   <div class="row">
                   <form action="admin_h.php" method="POST" autocomplete="off">
    					<div class="col-md-6">
    					<div class="form-group">
                        <label>Order Status</label>
                        <input class="form-control spinner"  type='text' name='o_status' required size='35' placeholder="Order Status"> 
    					</div>
    					</div>
    					<div class="col-md-6">
    					<div class="form-group">
    					<br/>
    					<input type='submit' name='action' value='Add Order Status' class="btn green">
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
                                        <i class="fa fa-cogs"></i>Order Status List</div>
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
                                            $orders=$bam->get_table_array('order_status_table');
                                            $i=1;
                                            if(!empty($orders))
                                            {
												*/
                                                echo '<table class="table">';
                                                echo '<thead><tr><td>Id</td><td>Order Status</td><td>Created On</td></tr></thead>';  
												/*
                                                foreach($orders AS $p)
                                                {
                                                    echo '<tbody></tbody><tr>';
                                                        echo '<td>'.$i.'</td>';
                                                        echo '<td>'.$p['o_status'].'</td>';
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
				 
					
					</div>
					
					</div>
					
            </div>
                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->
                           <?php
        $this->load->view('layout/footer');   
        ?>
