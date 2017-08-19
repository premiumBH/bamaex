<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/18/2017
 * Time: 4:45 PM
 */?>
<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/7/2017
 * Time: 6:51 PM
 */?>
<?php

$this->load->view('layout/header');

$this->load->view('layout/container');

?>

<!-- BEGIN PAGE BASE CONTENT -->



<div class="clearfix">&nbsp;</div>
<?php echo $this->session->flashdata('success');?>
<?php echo validation_errors('<div class="alert alert-danger alert-dismissible">', '</div>'); ?>
<div class="row">

    <div class="col-md-12">

        <div class="portlet box red">

            <div class="portlet-title">

                <div class="caption">

                    <i class="fa fa-cogs"></i>Notification Categories</div>

                <div class="tools">

                    <a href="javascript:void(0);" class="collapse" data-original-title="" title=""> </a>

                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>

                    <a href="javascript:void(0);" class="reload" data-original-title="" title=""> </a>

                    <a href="javascript:void(0);" class="remove" data-original-title="" title=""> </a>

                </div>

            </div>

            <div class="portlet-body">

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>Notification</td>
                            <!--<td>Status</td>-->
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($notificationCats as $notificationCat){?>
                            <tr>
                                <td><?php echo $notificationCat->cat_name?></td>
                                <!--<td><?php /*if($notificationCat->status == 1){echo 'Active';}else{echo 'Inactive';} */?></td>-->
                                <td>
                                    <a href="<?php echo SITE.'notification/notification_control/'.$notificationCat->id?>"  class="btn red "> Manage</a>
                                </td>
                            </tr>
                        <?php }?>

                        </tbody>
                    </table>


                </div>

            </div>

        </div>

    </div>
</div>


<!-- END PAGE BASE CONTENT -->



<?php

$this->load->view('layout/footer');

?>



