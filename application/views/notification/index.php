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

<div class="row">

    <div class="col-md-12">

        <a href="<?=CTRL?>notification/form" class="btn green"> Create Email Notification</a><br />
    </div>

</div>



<div class="clearfix">&nbsp;</div>
<?php echo $this->session->flashdata('success');?>
<?php echo validation_errors('<div class="alert alert-danger alert-dismissible">', '</div>'); ?>
<div class="row">

    <div class="col-md-12">

        <div class="portlet box red">

            <div class="portlet-title">

                <div class="caption">

                    <i class="fa fa-cogs"></i>Email Notification Data</div>

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
                            <td>Name/Subject</td>
                            <td>Category</td>
                            <td>User Type</td>
                            <td>Status</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($notifications as $notification){?>
                            <tr>
                                <td><?php echo $notification->name?></td>
                                <td><?php echo $notification->catName?></td>
                                <td><?php echo $notification->userType?></td>
                                <td><?php if($notification->status == 1){echo 'Active';}else{echo 'Inactive';} ?></td>
                                <td>
                                    <a href="<?php echo SITE.'notification/form/'.$notification->id?>"  class="btn red "> Edit</a>
                                    <a href="<?php echo SITE.'notification/delete/'.$notification->id?>" onclick="return confirm('Are you sure!')" class="btn red "> Delete</a>
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


<div class="row">

    <div class="col-md-12">


        <a href="<?=CTRL?>notification/sms_form" class="btn green"> Create SMS Notification</a><br />

    </div>

</div>
<div class="clearfix">&nbsp;</div>
<?php echo $this->session->flashdata('success');?>
<?php echo validation_errors('<div class="alert alert-danger alert-dismissible">', '</div>'); ?>
<div class="row">

    <div class="col-md-12">

        <div class="portlet box red">

            <div class="portlet-title">

                <div class="caption">

                    <i class="fa fa-cogs"></i>SMS Notification Data</div>

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
                            <td>Name/Subject</td>
                            <td>Category</td>
                            <td>User Type</td>
                            <td>Status</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($sms as $smsIn){?>
                            <tr>
                                <td><?php echo $smsIn->name?></td>
                                <td><?php echo $smsIn->catName?></td>
                                <td><?php echo $smsIn->userType?></td>
                                <td><?php if($smsIn->status == 1){echo 'Active';}else{echo 'Inactive';} ?></td>
                                <td>
                                    <a href="<?php echo SITE.'notification/sms_form/'.$smsIn->id?>"  class="btn red "> Edit</a>
                                    <a href="<?php echo SITE.'notification/delete/'.$smsIn->id?>" onclick="return confirm('Are you sure!')" class="btn red "> Delete</a>
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


