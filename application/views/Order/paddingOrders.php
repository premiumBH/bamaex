<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->load->view('layout/header');
$this->load->view('layout/container');
//    echo '<pre>';
//    var_dump($order);
//    echo '</pre>';
//    die('HERE');

?>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-list font-dark"></i>
                    <span class="caption-subject bold uppercase">Active Orders</span>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="sample_1">
                    <thead>
                    <tr>
                        <th class="all">Tracking ID</th>
                        <th class="min-phone-l">Shipment Date </th>
                        <th class="min-tablet">Shipment Status</th>
                        <th class="min-tablet">Receiver Company</th>
                        <th class="min-tablet">Airway Bill</th>
                        <!--<th class="none">Age</th>
                        <th class="none">Start date</th>-->

                        <th class="all">Select Courier Man</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($order != null){
                        foreach ($order as $key=>$ordr){?>


                    <tr>
                        <td><?php echo $ordr->order_tracking_id; ?></td>
                        <td><?php echo explode(' ',$ordr->created_on)[0]; ?></td>
                        <?php
                        if($ordr->order_state == '' || $ordr->order_state == '0')
                        {
                            if($ordr->order_status == '' || $ordr->order_status == '1' || $ordr->order_status == '2' || $ordr->order_status == '3')
                            {
                                echo '<td class="numeric"> Pending Pickup </td>';
                            }
                            else if($ordr->order_status == '4' || $ordr->order_status == '5')
                            {
                                echo '<td class="numeric"> Delivery in Progress  </td>';
                            }
                        }
                        else if ($ordr->order_state == '1')
                        {
                            echo '<td class="numeric"> Order Delivered  </td>';
                        }
                        else
                        {
                            echo '<td class="numeric"> Closed By Team  </td>';
                        }
                        ?>
                        <td class="numeric"> <?php echo $ordr->company_name; ?> </td>
                        <td class="numeric"> <?php echo $ordr->airway_bill; ?> </td>
                        <!--<td>61</td>
                        <td>2011/04/25</td>
                        <td>$320,800</td>-->
                        <td>
                            <div class="btn-group pull-right" id="CMDID-<?php echo $ordr->order_id;?>" >
                                <?php
                                $buttonText = 'Select Courier Man';
                                foreach ($courierMen as $courierMan) {
                                    if ($courierMan->intUserId == $ordr->CMID) {
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
                    </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<?php
?>

<?php

$this->load->view('layout/footer');

?>
<script src="<?=THEME?>assets/pages/scripts/table-datatables-responsive.min.js" type="text/javascript"></script>

<script>
    $(function(){
        $("#sample_1").on( "click", '.courierMan', function() {
            var selectedCM      = $(this).text();
            var CMID            = $(this).attr('id');
            var CMDID           = $(this).parent().parent().attr('id');
            var OrderId         = CMDID.split('CMDID-');
            OrderId             = OrderId[1];
            $("#"+CMDID+" button").html(selectedCM+'<i class="fa fa-angle-down"></i>');
            $('<form action="<?=CTRL?>OrderManagement/assignCourierMan" method="post"><input type="hidden" name="orderId" value="'+OrderId+'"><input type="hidden" name="CMID" value="'+CMID+'"></form>').appendTo('body').submit();
        });
    })
</script>