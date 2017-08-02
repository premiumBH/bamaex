		        <?php

        $this->load->view('layout/header');

        $this->load->view('layout/container');

		?>

                    <!-- BEGIN PAGE BASE CONTENT -->

                                <div class="row">

				   <div class="col-md-12">

				  <a href="<?=CTRL?>Country/create"><input value="Create Country" class="btn green" type="button"></a><br /><br />

                                    </div>

				</div>

				 

					<div class="clearfix">&nbsp;</div>

					<div class="row">

					<div class="col-md-12">

					  <div class="portlet box red">

                                <div class="portlet-title">

                                    <div class="caption">

                                        <i class="fa fa-cogs"></i>Country Data</div>

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

                                            echo '<td>Country ID</td>';

                                            echo '<td>Country Name</td>';

                                            echo '<td>Country Code</td>';
                                            echo '<td>Country Zone</td>';

                                            echo '<td></td>';

                                            echo '</tr></thead>';

                                            echo '<tbody>';
                                           // print_r($countries); 
                                            foreach($countries as $country)
                                            {
                                                echo '<tr>';
                                                echo '<td>'.$country->id.'</td>';
                                                echo '<td>'.$country->country_name.'</td>';
                                                echo '<td>'.$country->country_code.'</td>';
                                                echo '<td>'.$country->zone_name.'</td>';
                                                echo '<td><a href="'.CTRL.'country/update?edit-id='.$country->id.'"><input value="Edit" class="btn red" type="button"></a></td>';
                                                echo '</tr>';
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

