		        <?php

        $this->load->view('layout/header');

        $this->load->view('layout/container');

		?>

                    <!-- BEGIN PAGE BASE CONTENT -->

                                <div class="row">

				   <div class="col-md-12">

				  <a href="<?=CTRL?>zone/create"><input value="Create Zone" class="btn green" type="button"></a><br /><br />

                                    </div>

				</div>

				 

					<div class="clearfix">&nbsp;</div>

					<div class="row">

					<div class="col-md-12">

					  <div class="portlet box red">

                                <div class="portlet-title">

                                    <div class="caption">

                                        <i class="fa fa-cogs"></i>Zone Data</div>

                                    <div class="tools">

                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>

                                        <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>

                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>

                                        <a href="javascript:;" class="remove" data-original-title="" title=""> </a>

                                    </div>

                                </div>

                                <div class="portlet-body">

                                    <div class="table-responsive">

                                    <?php

									/*

                                        $clients=$bam->get_table_array('client_table');



                                        if(!empty($clients))

                                        {

											*/

                                            echo '<table class="table">';

                                            echo '<thead><tr>';

                                            echo '<td>Zone ID</td>';

                                            echo '<td>Zone Name</td>';

                                            echo '<td>Zone Price1</td>';

                                            echo '<td>Zone Price2</td>';

                                            echo '<td>Zone Price3</td>';


                                            echo '<td></td>';

                                            echo '</tr></thead>';

                                            echo '<tbody>';
                                            //print_r($zones);
                                            $counter = 0;
                                            foreach($zones as $zone)
                                            {
                                                echo '<tr>';
                                                echo '<td>'.$counter.'</td>';
                                                echo '<td>'.$zone->zone_name.'</td>';
                                                echo '<td>'.$zone->zone_price1.'</td>';
                                                echo '<td>'.$zone->zone_price2.'</td>';
                                                echo '<td>'.$zone->zone_price3.'</td>';
                                                echo '<td><a href="'.CTRL.'zone/update?edit-id='.$zone->zone_id.'"><input value="Edit" class="btn red" type="button"></a></td>';
                                                echo '</tr>';
                                                
                                                $counter = $counter + 1;
                                            }
                                            
                                            echo '</tbody>';

                                            echo '</table>';

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

