<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/9/2017
 * Time: 11:13 AM
 */?>
<?php

$this->load->view('layout/header');

$this->load->view('layout/container');

?>

<!-- BEGIN PAGE BASE CONTENT -->
<?php echo $this->session->flashdata('success');?>
<?php echo $this->session->flashdata('error');?>

<div class="row">

    <div class="col-md-6">

        <div class="portlet box red">

            <div class="portlet-title">

                <div class="caption">

                    <i class="fa fa-cogs"></i>Payment Type</div>

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
                            <td>Payment Type</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($paymentTypes as $paymentType){?>
                            <tr>
                                <td id="pType-<?php echo $paymentType->id?>"><?php echo $paymentType->payment_type?></td>
                                <td>
                                    <a href="javascript:void(0);"  class="btn red paymentTypeUpdate" id="<?php echo $paymentType->id?>" > Edit</a>
                                    <a href="<?php echo SITE.'custom_setting/deletePaymentType/'.$paymentType->id?>" onclick="return confirm('Are you sure!')" class="btn red "> Delete</a>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                        <tfoot>
                        <form action="<?=CTRL?>custom_setting/payment_type_form/" method="POST" autocomplete="off">
                            <input type="hidden" name="id" id="paymentTypeId" value="">
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-6" style="padding: 0px;">
                                        <div class="form-group">
                                            <label>Payment Type</label>
                                            <input type="text" name="paymentType" id="paymentType" value="" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><div class="row">
                                    <div class="col-md-6" style="margin-top: 10%;">
                                        <div class="form-group">
                                            <input type="submit" name="submit" id="paymentTypeSubmit" value="Save" class="btn green" >
                                        </div>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        </form>
                        </tfoot>
                    </table>

                </div>

            </div>

        </div>

    </div>


    <div class="col-md-6">

        <div class="portlet box red">

            <div class="portlet-title">

                <div class="caption">

                    <i class="fa fa-cogs"></i>Service</div>

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
                            <td>Name</td>
                            <td>Active</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($services as $service){?>
                            <tr>
                                <td id="service-<?php echo $service->id?>"><?php echo $service->service_name?></td>
                                <td>
                                    <a href="javascript:void(0);"  class="btn red serviceUpdate" id="<?php echo $service->id?>" > Edit</a>
                                    <a href="<?php echo SITE.'custom_setting/deleteService/'.$service->id?>" onclick="return confirm('Are you sure!')" class="btn red "> Delete</a>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                        <tfoot>
                        <form action="<?=CTRL?>custom_setting/service_form/" method="POST" autocomplete="off">
                            <input type="hidden" name="id" id="serviceId" value="">
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6" style="padding: 0px;">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="service" id="service" value="" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><div class="row">
                                        <div class="col-md-6" style="margin-top: 10%;">
                                            <div class="form-group">
                                                <input type="submit" name="submit" id="serviceSubmit" value="Save" class="btn green" >
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        </form>
                        </tfoot>
                    </table>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-12">

        <div class="portlet box red">

            <div class="portlet-title">

                <div class="caption">

                    <i class="fa fa-cogs"></i>Config</div>

                <div class="tools">

                    <a href="javascript:void(0);" class="collapse" data-original-title="" title=""> </a>

                    <a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>

                    <a href="javascript:void(0);" class="reload" data-original-title="" title=""> </a>

                    <a href="javascript:void(0);" class="remove" data-original-title="" title=""> </a>

                </div>

            </div>

            <div class="portlet-body">

                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                        <tr>
                            <td>Key Name</td>
                            <td>Key Value</td>
                            <td>Active</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($config as $configIn){?>
                            <tr>
                                <td id="configName-<?php echo $configIn->id?>"><?php echo $configIn->key_name?></td>
                                <td id="configVal-<?php echo $configIn->id?>"><?php echo $configIn->key_value?></td>
                                <td>
                                    <a href="javascript:void(0);"  class="btn red configUpdate" id="<?php echo $configIn->id?>" > Edit</a>
                                    <a href="<?php echo SITE.'custom_setting/deleteConfig/'.$configIn->id?>" onclick="return confirm('Are you sure!')" class="btn red "> Delete</a>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                        <tfoot>
                        <form action="<?=CTRL?>custom_setting/config_form/" method="POST" autocomplete="off">
                            <input type="hidden" name="id" id="configId" value="">
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6" style="padding: 0px;">
                                            <div class="form-group">
                                                <label>Key Name</label>
                                                <input type="text" name="keyName" id="keyName" value="" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-md-6" style="padding: 0px;">
                                            <div class="form-group">
                                                <label>Key Value</label>
                                                <input type="text" name="keyVal" id="keyVal" value="" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><div class="row">
                                        <div class="col-md-6" id="submitDiv" style="">
                                            <div class="mt-checkbox-list" id="isDeletableDiv">
                                                <lable>
                                                    Is deletable
                                                <input type="checkbox" name="isDeletable" id="" value="yes" checked >
                                                </lable>
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" name="submit" id="configSubmit" value="Save" class="btn green" >
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        </form>
                        </tfoot>
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
<script>
    $(function() {
        $('.paymentTypeUpdate').on('click',function () {
            var paymentTypeId = $(this).attr('id');
            var pTypeById = $("#pType-"+paymentTypeId).text();
            $("#paymentTypeId").val(paymentTypeId);
            $("#paymentType").val(pTypeById);
            $("#paymentTypeSubmit").val('Update');

        })
    });

    $(function() {
        $('.serviceUpdate').on('click',function () {
            var serviceId = $(this).attr('id');
            var serviceById = $("#service-"+serviceId).text();
            $("#serviceId").val(serviceId);
            $("#service").val(serviceById);
            $("#serviceSubmit").val('Update');


        })
    });

    $(function() {
        $('.configUpdate').on('click',function () {
            $("#isDeletableDiv").hide();
            $("#submitDiv").css('margin-top',"10%");
            var configId = $(this).attr('id');
            var configNameById = $("#configName-"+configId).text();
            var configValById = $("#configVal-"+configId).text();
            $("#configId").val(configId);
            $("#keyName").val(configNameById);
            $("#keyVal").val(configValById);
            $("#configSubmit").val('Update');


        })
    });
</script>



