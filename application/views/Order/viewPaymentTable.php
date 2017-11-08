<?php
	$this->load->view('layout/header');
	$this->load->view('layout/container');
?>





            <div class="row">
                <div class="col-md-12">
                    <h2>Payment Management</h2>
                </div>
	   </div>    
           <?php
                if($this->session->userdata['UserType'] != 'Client')
                {
           ?>
           <form action="" method="POST" autocomplete="off">
               <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <input placeholder="Search By Client Company Name | Client Account" class="form-control input" name="search" required>
                         </div>
                     </div>
                      <div class="col-md-6">
                         <div class="form-group">
                             <input type="submit" value="Select" class="btn green" >
                         </div>
                     </div>
               </div>
                    
           </form>
           <?php
                }
           ?>
        <?php
        if(isset($orders))
        {
        ?>
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">Payment history and status report</span>
                    <input type="hidden" value="<?php echo $client_id;?>" name='client_id' >
                </div>
                <div class="tools"> </div>
                
                <div class="row">
                    <div class="actions">

                        <div class="btn-group">
                            <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                <i class="fa fa-share"></i>
                                <span class="hidden-xs"> Tools </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" id="datatable_ajax_tools">
                                <li>
                                    <a href="javascript:;" data-action="0" class="tool-action">
                                        <i class="icon-printer"></i> Print</a>
                                </li>

                                <li>
                                    <a href="javascript:;" data-action="2" class="tool-action">
                                        <i class="icon-doc"></i> Export</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                     
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="status" class="form-control form-filter input-sm">
                            
                            <option value="1">All</option>
                            <option value="2">Pending</option>
                            <option value="3">In Transit</option>
                            <option value="4">Warehoused</option>
                            <option value="5">Delivered</option>
                            <option value="6">Undelivered</option>

                        </select>
                    </div>
                </div>
                
            </div>
            <div class="portlet-body">
                <div class="table-container" style="overflow-x: scroll; transform : rotateX(180deg)">
                    <div class="table-actions-wrapper">
                        <span> </span>
                        <select class="table-group-action-input form-control input-inline input-small input-sm">
                            <option value="">Select...</option>
                            <option value="Cancel">Cancel</option>
                            <option value="Cancel">Hold</option>
                            <option value="Cancel">On Hold</option>
                            <option value="Close">Close</option>
                        </select>
                        <button class="btn btn-sm green table-group-action-submit">
                            <i class="fa fa-check"></i> Submit</button>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax" style="transform : rotateX(180deg)">
                        <thead>
                            <tr role="row" class="heading">
                                <th width="2%">
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>
                                <th width="5%"> AWB&nbsp;# </th>
                                <th width="15%"> Order date </th>
                                <th width="200"> Destination Country</th>
                                <th width="10%"> Sender&nbsp;Name </th>
                                <th width="10%"> Receiver&nbsp;Name </th>
                                <th width="10%"> Bill Amount </th>
                                <th width="10%"> Payment Due Date</th>
                                <th width="10%"> Days Balance </th>
                                <th width="10%"> Paid Date </th>
                                <th width="10%"> Payment Status </th>
                                <th width="10%"> Actions </th>
                            </tr>
                            <tr role="row" class="filter">
                                <td> </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="AWB_id"> </td>
                                <td>
                                    <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly name="order_date_to" placeholder="To">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="country"> </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="sender_name"> </td>
                                <td>
                                    <input type="text" class="form-control form-filter input-sm" name="receiver_name"> </td>
                                <td>
                                    <div class="margin-bottom-5">
                                        <input type="text" class="form-control form-filter input-sm" name="order_price_from" placeholder="From" /> </div>
                                    <input type="text" class="form-control form-filter input-sm" name="order_price_to" placeholder="To" /> 
                                </td>
                                 <td>
                                   <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly name="order_due_from" placeholder="From">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly name="order_due_to" placeholder="To">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                 </td>
                                
                                
                                <td>
                                    <div class="margin-bottom-5">
                                    <input type="text" class="form-control form-filter input-sm margin-bottom-5 clearfix" name="days_balance_from" placeholder="From" /> </div>
                                    <input type="text" class="form-control form-filter input-sm" name="days_balance_to" placeholder="To" /> 
                                </td>
                                
                                <td>
                                   <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly name="paid_date_from" placeholder="From">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                    <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control form-filter input-sm" readonly name="paid_date_to" placeholder="To">
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                 </td>
                                
                                <td>
                                    <select name="order_status" class="form-control form-filter input-sm">
                                        <option value="">Select...</option>
                                        <option value="1">Due</option>
                                        <option value="2">Over Due</option>
                                        <option value="3">Paid</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="margin-bottom-5">
                                        <button class="btn btn-sm green btn-outline filter-submit margin-bottom search">
                                            <i class="fa fa-search"></i> Search</button>
                                    </div>
                                    <button class="btn btn-sm red btn-outline filter-cancel reset">
                                        <i class="fa fa-times"></i> Reset</button>
                                </td>
                            </tr>
                        </thead>
                        <tbody> 
                            
                                
                                <?php
                                $i = 1;
                                foreach($orders as $order)
                                {
                                ?>
                                <tr>
                                <td></td>
                                <td><a href="<?php echo base_url().'Order/view_order?ref-id='.$order['order_tracking_id'];?>"><?php echo $order['order_AWB']; ?></a></td>
                                <td><?php echo $order['order_date'];?></td>
                                <td><?php echo $order['receiver_country'];?></td>
                                <td><?php echo $order['sender_name'];?></td>
                                <td><?php echo $order['receiver_name'];?></td>
                                <td><?php echo $order['bill_amount'];?></td>
                                <td><?php echo $order['payment_due_date'];?></td>
                                <td><?php 
                                if($order['status'] != 'Paid')
                                {
                                    //echo $order['payment_due_date'];
                                    echo $order['days_balance'];
                                }
                                
                                ?></td>
                                
                                <td><?php echo $order['paid_date'];?></td>
                                <?php
                                if($order['status'] == 'Due')
                                {
                                    echo '<td><span class="label label-sm label-primary">Due</span></td>';
                                }
                                else if($order['status'] == 'Paid')
                                {
                                    echo '<td><span class="label label-sm label-success">Paid</span></td>';
                                }
                                else if($order['status'] == 'Over Due')
                                {
                                    echo '<td><span class="label label-sm label-danger">Over Due</span></td>';
                                }
                                
                                if($this->session->userdata['UserType'] != 'Client')
                                {
                                ?>
                               <td><input class="btn purple btn-outline btn-block mark_paid" value="Mark Paid" type="button"></td>
                               <?php
                                }
                               ?>
                                </tr>
                                
                                <?php
                                    $i = $i + 1;
                                }
                                ?>
                                
                            
                        
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
        
        }
        
        ?>



<?php

     $this->load->view('layout/footer');   

?>

