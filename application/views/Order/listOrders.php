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
    <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Shipment 
                </div>                
            </div>
            <div class="portlet-body flip-scroll">
                <table class="table table-bordered table-striped table-condensed flip-content">
                    <thead class="flip-content">
                        <tr>
                            <th width="20%">Tracking ID</th>
                            <th> Shipment Date </th>
                            <th class="numeric"> Shipment Status </th>
                            <th class="numeric"> Receiver Company</th>
                            <th class="numeric"> Airway Bill </th>
                            <th class="numeric text-center"> Download Airway Bill </th>
                            <th class="numeric text-center"> View </th>
                            <th class="numeric text-center"> Manifest </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($order as $ordr)
                    {
                    ?>
                        <tr>
                            <td> <?php echo $ordr->order_tracking_id; ?> </td>
                            <td> <?php echo explode(' ',$ordr->created_on)[0]; ?> </td>
                            <?php
                            if($ordr->order_status == 1)
                            {
                                echo '<td class="numeric"> Pending Pickup </td>';
                            }
                            else
                            {
                                echo '<td class="numeric"> Status Not  </td>';
                            ?>
                            <?php
                            }
                            ?>
                            <td class="numeric"> <?php echo $ordr->company_name; ?> </td>
                            <td class="numeric"> <?php echo $ordr->airway_bill; ?> </td>
                            <td class="numeric text-center"> 
                                <a href="<?php echo base_url().'Order\downloadAirway?ref-id='.$ordr->airway_bill;?>" class="btn btn-outline btn-circle purple">
                                    <i class="fa fa-file-pdf-o"></i> PDF 
                                </a>
                            </td>
                            <td class="numeric text-center"> 
                                <a href="<?php echo base_url().'Order\view_order?ref-id='.$ordr->order_tracking_id;?>" class="btn btn-outline btn-circle blue">
                                    <i class="fa fa-eye"></i> View 
                                </a>
                            </td>
                            <td class="numeric text-center"> 
                                <a href="" class="btn btn-outline btn-circle purple">
                                    <i class="fa fa-file-pdf-o"></i> PDF 
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
<?php
?>















    
    
    
<?php

     $this->load->view('layout/footer');   

?>