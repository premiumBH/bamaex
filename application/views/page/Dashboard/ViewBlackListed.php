		        <?php

        $this->load->view('layout/header');

        $this->load->view('layout/container');

		?>

                    <!-- BEGIN PAGE BASE CONTENT -->

                                <div class="row">

				   <div class="col-md-12">

				  <a href="<?=CTRL?>Client/create"><input value="Create Client" class="btn green" type="button"></a><br /><br />

                                    </div>

				</div>

				 

					<div class="clearfix">&nbsp;</div>

					<div class="row">

					<div class="col-md-12">

					  <div class="portlet box red">

                                <div class="portlet-title">

                                    <div class="caption">

                                        <i class="fa fa-cogs"></i>Client Data</div>

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

                                            echo '<td>Company Name</td>';

                                            echo '<td>Company Website</td>';

                                            echo '<td>Email </td>';
                                            echo '<td>Country</td>';

                                            echo '<td>Level</td>';
                                            echo '<td></td>';

                                            echo '</tr></thead>';

                                            echo '<tbody>';
                                            echo '';                                    
                                            foreach($clients as $client)
                                            {
                                                if($client->level_name != 'CONTACT' || $client->level_name != 'PROSPECT')
                                                {
                                                    echo '<tr>';
                                                    echo '<td>'.$client->company_name.'</td>';
                                                    echo '<td>'.$client->company_website.'</td>';
                                                    echo '<td>'.$client->email.'</td>';
                                                    echo '<td>'.$client->phone_no.'</td>';
                                                    echo '<td>'.$client->level_name.'</td>';
                                                    if($client->level_name == 'CLIENT' || $client->level_name == 'SUSPENDED')
                                                        echo '<td><a href="'.CTRL.'Client/markBlacklist?edit-id='.$client->client_id.'"><input value="Mark BlackList" class="btn red" type="button"></a></td>';
                                                    else if($client->level_name == 'BLACKLISTED')
                                                        echo '<td><a href="'.CTRL.'Client/markWhitelist?edit-id='.$client->client_id.'"><input value="Mark WhiteList" class="btn red" type="button"></a></td>';

                                                    echo '</tr>';
                                                }
                                            }
                                           ?>
                                        <!--<tr><td>Country ID</td><td>Country ID</td><td>Country ID</td><td>Country ID</td><td>Country ID</td></tr>-->
                                        <?php
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

