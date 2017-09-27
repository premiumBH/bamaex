<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 9/16/2017
 * Time: 3:19 AM
 */?>
<?php
$this->load->view('layout/header');
$this->load->view('layout/container');
?>


    <div class="portlet light portlet-fit portlet-datatable bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject font-dark sbold uppercase">Payment history and status report</span>
                <!--<input type="hidden" value="<?php /*echo $client_id;*/?>" name='client_id' >-->
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
            <div class="table-container" style="overflow-x: scroll">
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
                <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
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
                        <th width="10%"> Courier</th>
                        <th width="10%"> Order Status</th>
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
                                <option value="1">Due</option>
                                <option value="2">Over Due</option>
                                <option value="3">Paid</option>
                            </select>
                        </td>

                        <td>
                            <input type="text" class="form-control form-filter input-sm" name="courier_name"> </td>

                        <td>
                            <input type="text" class="form-control form-filter input-sm" name="order_status"> </td>
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
                            <td><?php echo $order['order_AWB']; ?></td>
                            <td><?php echo $order['order_date'];?></td>
                            <td><?php echo $order['receiver_country'];?></td>
                            <td><?php echo $order['sender_name'];?></td>
                            <td><?php echo $order['receiver_name'];?></td>
                            <td><?php echo $order['bill_amount'];?></td>
                            <td><?php echo $order['payment_due_date'];?></td>
                            <td><?php echo $order['days_balance'];?></td>
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
                            ?>
                            <td>
                                <div class="btn-group pull-right" id="CMDID-<?php echo $order['order_id'];?>" >
                                    <?php
                                    $buttonText = 'Select Courier Man';
                                    foreach ($courierMen as $courierMan) {
                                        if ($courierMan->intUserId == $order['CMID']) {
                                            $buttonText = $courierMan->varEmailId;
                                            break;
                                        }
                                    }
                                    ?>

                                    <button class="btn green btn-xs btn-outline dropdown-toggle" data-toggle="dropdown"><?php echo $buttonText?>
                                        <i class="fa fa-angle-down"></i>
                                    </button>

                                    <ul class="dropdown-menu pull-right">
                                        <?php foreach ($courierMen as $courierMan){
                                            if($courierMan->intUserId){
                                                ?>
                                                <li class="courierMan" id="<?php echo $courierMan->intUserId?>">
                                                    <a href="javascript:;" >
                                                        <?php echo $courierMan->varEmailId?> </a>
                                                </li>
                                            <?php } }?>

                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group pull-right" id="OrderStatus-<?php echo $order['order_id'];?>" >
                                    <?php
                                    $buttonText = 'Select Order Status';
                                    foreach ($orderStatuses as $orderStatus) {
                                        if ($orderStatus->id == $order['order_status']) {
                                            $buttonText = $orderStatus->status;
                                            break;
                                        }
                                    }
                                    ?>

                                    <button class="btn green btn-xs btn-outline dropdown-toggle" data-toggle="dropdown"><?php echo $buttonText?>
                                        <i class="fa fa-angle-down"></i>
                                    </button>

                                    <!--<ul class="dropdown-menu pull-right">
                                        <?php /*foreach ($orderStatuses as $orderStatus){
                                            if($orderStatus->id){
                                                */?>
                                                <li class="OrderStatus" id="<?php /*echo $orderStatus->id*/?>">
                                                    <a href="javascript:;" >
                                                        <?php /*echo $orderStatus->status*/?> </a>
                                                </li>
                                            <?php /*} }*/?>

                                    </ul>-->
                                </div>
                            </td>
                            <td><input class="btn purple btn-outline btn-block mark_paid" value="Mark Paid" type="button"></td>
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

$this->load->view('layout/footer');

?>
<script>
    $(function(){
        $(".portlet-datatable").on( "click", '.courierMan', function() {
            var selectedCM      = $(this).text();
            var CMID            = $(this).attr('id');
            var CMDID           = $(this).parent().parent().attr('id');
            var OrderId         = CMDID.split('CMDID-');
            OrderId             = OrderId[1];
            $("#"+CMDID+" button").html(selectedCM+'<i class="fa fa-angle-down"></i>');
            $('<form action="<?=CTRL?>OrderManagement/assignCourierMan" method="post"><input type="hidden" name="orderId" value="'+OrderId+'"><input type="hidden" name="CMID" value="'+CMID+'"></form>').appendTo('body').submit();
        });

        /*$(".portlet-datatable").on( "click", '.OrderStatus', function() {
            var selectedCM      = $(this).text();
            var OrderStatus            = $(this).attr('id');
            var OrderStatus           = $(this).parent().parent().attr('id');
            var OrderId         = OrderStatus.split('OrderStatus-');
            OrderId             = OrderId[1];
            $("#"+OrderStatus+" button").html(selectedCM+'<i class="fa fa-angle-down"></i>');
            $('<form action="OrderManagement/assignCourierMan" method="post"><input type="hidden" name="orderId" value="'+OrderId+'"><input type="hidden" name="OrderStatusId" value="'+OrderStatus+'"></form>').appendTo('body').submit();
        });*/
    })
</script>
<script>
    $(function () {
        $("div.actions").show();
    })
</script>


