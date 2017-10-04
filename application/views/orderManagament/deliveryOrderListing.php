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
                <span class="caption-subject font-dark sbold uppercase"><?php echo $title?></span>
                <!--<input type="hidden" value="<?php /*echo $client_id;*/?>" name='client_id' >-->
            </div>

            <div class="row">
                <div class="actions">

                    <div class="btn-group" style="float: right">
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
                                    <i class="icon-doc"></i> PDF</a>
                            </li>

                            <li>
                                <a href="javascript:;" data-action="4" class="tool-action">
                                    <i class="icon-cloud-upload"></i> CSV</a>
                            </li>

                        </ul>
                    </div>
                </div>

                <?php if(isset($isShowRegionFilter)){?>
                <div class="form-group">
                    <select name="regionStatus" id="regionStatus" class="form-control form-filter input-sm">
                        <option value="All">All</option>
                        <option value="Domestic">Domestic</option>
                        <option value="International">International</option>
                    </select>
                </div>
                <?php }?>
                <input type="hidden" name="orderStatusId" id="orderStatusId" value="<?=$orderStatus?>">
            </div>
        </div>

        <div class="portlet-body">
            <div class="table-container" >
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
                        <th width="10%"> Destination Country</th>
                        <th width="10%"> Sender&nbsp;Name </th>
                        <th width="10%"> Receiver&nbsp;Name </th>
                        <th width="10%"> Order Status</th>
                        <?php if(isset($showCourierAssignedName)){?>
                            <th><?php echo $showCourierAssignedNameTitle;?></th>
                        <?php }?>

                        <th width="10%"> Actions </th>
                    </tr>
                    <tr role="row" class="filter">
                        <td> </td>
                        <td>
                            <input type="text" class="form-control form-filter input-sm" name="AWB_id" id="AWB_id"> </td>
                        <td>
                            <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control form-filter input-sm" readonly name="order_date_from" id="order_date_from" placeholder="From">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                            </div>
                            <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                <input type="text" class="form-control form-filter input-sm" readonly name="order_date_to" id="order_date_to" placeholder="To">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm default" type="button">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control form-filter input-sm" name="country" id="country"> </td>
                        <td>
                            <input type="text" class="form-control form-filter input-sm" name="sender_name" id="sender_name"> </td>
                        <td>
                            <input type="text" class="form-control form-filter input-sm" name="receiver_name" id="receiver_name"> </td>



                        <td>-----
                           <!-- <input type="text" class="form-control form-filter input-sm" name="order_status">--> </td>

                        <?php if(isset($showCourierAssignedName)){?>
                            <td>----- </td>
                        <?php }?>

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
                    <tbody id="orderData">


                    <?php
                    $i = 1;
                    foreach($orders as $order)
                    {
                        ?>
                        <tr>
                            <td></td>
                            <td><a href="<?=CTRL?>Order/view_order?ref-id=<?=$order->order_tracking_id?>"><?php echo $order->airway_bill; ?></a></td>
                            <td><?php echo $order->created_on;?></td>
                            <td><?php echo $order->country_name;?></td>
                            <td><?php echo $order->senderName;?></td>
                            <td><?php echo $order->receiverName;?></td>
                            <td><?php echo $order->status;?></td>
                            <?php if(isset($showCourierAssignedName) &&  isset($order->CMName)){?>
                                <td><?php echo $order->CMName;?></td>
                            <?php }?>

                            <td>
                                <?php
                                $show = true;
                                $userTypeId = $this->session->userdata('UserTypeId');
                                if($userTypeId == 4 || $userTypeId == 5){
                                    if($userTypeId == 5){
                                        $show = false;
                                    }else if($userTypeId == 4){
                                        if($order->countryId != 15){
                                            $show = false;
                                        }
                                    }
                                }

                                if($show){?>
                                <?if(!isset($noAction)){?>
                                <?php if(isset($courierMen)){?>
                                    <div class="form-group">
                                        <label for="sel1">Assign courier:</label>
                                        <select class="form-control courierMan" id="CMDID-<?php echo $order->order_id;?>">
                                            <option value="">Select Status </option>
                                            <?php foreach ($courierMen as $courierMenIn){
                                                if($courierMenIn->intUserId){?>
                                                    <option value="<?php echo $courierMenIn->intUserId?>"
                                                        <?php if($courierMenIn->intUserId == $order->CMID){echo "selected";}?>>
                                                        <?php echo $courierMenIn->varEmailId;?></option>
                                                <?php } }?>

                                        </select>
                                    </div>
                                <?php }?>


                                <?php if($order->countryId == 15){?>

                                    <div class="form-group">
                                        <label for="sel1">Update status:</label>
                                        <select class="form-control OrderStatus" id="OrderStatus-<?php echo $order->order_id;?>">
                                            <option value="">Select Status </option>
                                            <?php foreach ($domesticDeliveryStatus as $domesticDeliveryStatusIn){
                                                if($domesticDeliveryStatusIn->id){?>
                                                    <option value="<?php echo $domesticDeliveryStatusIn->id?>"
                                                        <?php if(isset($preFillStatus) && $domesticDeliveryStatusIn->id == $order->order_delivery_status){echo "selected";}?>>
                                                        <?php echo $domesticDeliveryStatusIn->status;?></option>
                                                <?php } }?>

                                        </select>
                                    </div>
                                <?php }else{?>

                                    <div class="form-group">
                                        <label for="sel1">Update status:</label>
                                        <select class="form-control OrderStatus" id="OrderStatus-<?php echo $order->order_id;?>">
                                            <option value="">Select Status </option>
                                            <?php foreach ($expressDeliveryStatus as $expressDeliveryStatusIn){
                                                if($expressDeliveryStatusIn->id){?>
                                                    <option value="<?php echo $expressDeliveryStatusIn->id?>"
                                                        <?php if(isset($preFillStatus) && $expressDeliveryStatusIn->id == $order->order_delivery_status){echo "selected";}?>>
                                                        <?php echo $expressDeliveryStatusIn->status;?></option>
                                                <?php } }?>

                                        </select>
                                    </div>
                                <?php }?>

                                <?php }else{?>
                                    -----
                                <?php }?>
                                <?php }?>

                            </td>
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
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<!--<script>
    var ajaxURL = '<?php /*echo $actual_link*/?>';
</script>
<script src="<?/*=THEME*/?>assets/global/scripts/datatable.js" type="text/javascript"></script>
<script src="<?/*=THEME*/?>assets/pages/scripts/table-datatables-buttons.js" type="text/javascript"></script>-->
<script>
    $(function(){

        <?php if(isset($courierMen)){?>
        $(".portlet-datatable").on( "change", '.courierMan', function() {
            var CMID            = $(this).val();
            var CMDID            = $(this).attr('id');
            var OrderId         = CMDID.split('CMDID-');
            OrderId             = OrderId[1];
            $('<form action="<?=CTRL?>OrderManagement/assignCourierMan" method="post"><input type="hidden" name="orderId" value="'+OrderId+'"><input type="hidden" name="CMID" value="'+CMID+'"><input type="hidden" name="type" value="delivery"></form>').appendTo('body').submit();
        });
        <?php }?>

        $(".portlet-datatable").on( "change", '.OrderStatus', function() {
            var OrderStatus                 = $(this).val();
            var OrderStatusDiv              = $(this).attr('id');
            var OrderId                     = OrderStatusDiv.split('OrderStatus-');
            OrderId                         = OrderId[1];
            $('<form action="<?=CTRL?>OrderManagement/changeOrderStatusDelivery" method="post"><input type="hidden" name="orderId" value="'+OrderId+'"><input type="hidden" name="OrderStatusId" value="'+OrderStatus+'"></form>').appendTo('body').submit();
        });
    })
</script>
<script>
    $(function () {
        $("div.actions").show();
    })
</script>

<script>
    $(function () {
        $("#regionStatus").change(function () {
            var regionStatus        = $(this).val();
            var orderStatusId       = $("#orderStatusId").val();
            var orderQuery          = "<?php echo $orderQuery;?>";
            var IsShowCourierCol    = "<?php echo (isset($IsShowCourierCol))?'yes':'no';?>";
            var preFillStatus    = "<?php echo (isset($preFillStatus))?'yes':'no';?>";
            $.post( "<?=CTRL?>OrderManagement/orderSearchInWareHouse", { orderByRegion: regionStatus, orderStatusId : orderStatusId, orderQuery: orderQuery, IsShowCourierCol:IsShowCourierCol, preFillStatus:preFillStatus }, function( data ) {

                var orderDataTBody      = $("#orderData");
                var OrderData           = data.response;
                console.log(data);
                if(data.error == 0){
                    append =  [];
                    if(OrderData != 0){
                        orderDataTBody.html('');
                        orderDataTBody.html(data.response);
                    }else{
                        var append = '<tr> <td>No Record Found</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>';
                        orderDataTBody.html('');
                        orderDataTBody.html(append);
                    }
                }
            }, "json");
        })
    })

    $(function () {
        $("button.search").click(function () {

            var AWB                                 = $("#AWB_id").val();
            var orderDateFrom                       = $("#order_date_from").val();
            var orderDateTo                         = $("#order_date_to").val();
            var country                             = $("#country").val();
            var senderName                          = $("#sender_name").val();
            var receiverName                        = $("#receiver_name").val();
            var startDate                           = $("#order_date_from").val();
            var endDate                             = $("#order_date_to").val();
            var regionStatus            = $(this).val();
            var orderStatusId           = $("#orderStatusId").val();
            var orderQuery              = "<?php echo $orderQuery;?>";
            var IsShowCourierCol        = "<?php echo (isset($IsShowCourierCol))?'yes':'no';?>";
            var hideUpdateCourier        = "<?php echo (isset($hideUpdateCourier))?'yes':'no';?>";
            var IsOrderOriginated       = "<?php echo (isset($IsOrderOriginated))?'yes':'no';?>";
            var IsPUAssignCour       = "<?php echo (isset($IsPUAssignCour))?'yes':'no';?>";
            var preFillStatus    = "<?php echo (isset($preFillStatus))?'yes':'no';?>";
            $.post( "<?=CTRL?>OrderManagement/orderSearchByInput", {
                    AWB:AWB,
                    orderDateFrom:orderDateFrom,
                    orderDateTo:orderDateTo,
                    country:country,
                    senderName:senderName,
                    receiverName:receiverName,
                    startDate:startDate,
                    endDate:endDate,
                    orderStatusId : orderStatusId,
                    IsShowCourierCol:IsShowCourierCol,
                    hideUpdateCourier:hideUpdateCourier,
                    orderQuery: orderQuery,
                    IsOrderOriginated:IsOrderOriginated,
                    IsPUAssignCour:IsPUAssignCour,
                    preFillStatus:preFillStatus},
                function( data ) {

                    var orderDataTBody      = $("#orderData");
                    var OrderData           = data.response;
                    console.log(data);
                    if(data.error == 0){
                        append =  [];
                        if(OrderData != 0){
                            orderDataTBody.html('');
                            orderDataTBody.html(data.response);
                        }else{
                            var append = '<tr> <td>No Record Found</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td> </tr>';
                            orderDataTBody.html('');
                            orderDataTBody.html(append);
                        }
                    }
                }, "json");
        })
    })

</script>


