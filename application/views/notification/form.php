<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/7/2017
 * Time: 6:52 PM
 */?>
<?php
$this->load->view('layout/header');
$this->load->view('layout/container');
?>
<script src="<?php echo SITE?>theme/assets/global/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>


<div class="row">
    <div class="col-md-12">
        <h2>Notification</h2>
    </div>
</div>
<?php echo validation_errors('<div class="alert alert-danger alert-dismissible">', '</div>'); ?>
<form action="<?=CTRL?>notification/form/<?php echo isset($id)?$id:''?>" method="POST" autocomplete="off">
    <input type="hidden" name="id" value="<?php echo isset($notification)?$notification->id:''?>"/>
    <input type="hidden" name="type" value="email"/>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Name/Subject</label>
                <input type="text" name="name" required value="<?php echo isset($notification)?$notification->name:''?>" class="form-control"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Category</label>
                <select class="form-control" name="notifyCatId" id="notifyCatId" required>
                    <?php foreach ($notificationCats as $notificationCat){?>
                        <option value="<?php echo $notificationCat->id?>" <?php if(isset($notification) && $notificationCat->id == $notification->notify_cat_id){echo 'selected';}?>> <?php echo $notificationCat->cat_name?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>User Type</label>
                <select class="form-control" name="notifyUserType" id="notifyUserType" required>
                    <?php foreach ($notifyUserType as $notifyUserTypeIn){?>
                        <option value="<?php echo $notifyUserTypeIn->notification_users_type_id?>" <?php if(isset($notification) && $notifyUserTypeIn->notification_users_type_id == $notification->user_type){echo 'selected';}?>> <?php echo $notifyUserTypeIn->user_type?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Template</label>
                <p style="text-align: right;">
                    [order_tracking_id] ,<br/>
                    [receiver_name] , [receiver_email] , [receiver_mobile] ,<br/>
                    [sender_name] , [sender_email] , [sender_mobile] ,<br/>
                    [client_first_name] , [client_last_name] , [client_name] , [client_email] , [client_password] ,<br/>
                    [client_creator_name] , [client_creator_email] , [client_creator_mobile] ,<br/>
                    [order_creator_name] , [order_creator_email] , [order_creator_mobile] ,<br/>
                    [courier_name] , [courier_Email] , [courier_mobile] ,<br/>
                </p>
                <textarea class="ckeditor form-control" required name="template" rows="6" data-error-container="#editor2_error"><?php echo isset($notification)?$notification->template:''?></textarea>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Status</label><br/>
                <label class="mt-radio">
                    <input type="radio" name="status" id="optionsRadios22" value="1" <?php if( isset($notification)){if($notification->status == 1){echo 'checked';}}else{echo 'checked';}?> > Active
                    <span></span>
                </label>
                <label class="mt-radio">
                    <input type="radio" name="status" id="optionsRadios22" value="0" <?php if( isset($notification)){if($notification->status == 0){echo 'checked';}}?>> Inactive
                    <span></span>
                </label>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <input type="submit" name="submit" value="Save" class="btn green" >
            </div>
        </div>
    </div>
</form>




<?php
$this->load->view('layout/footer');
?>

<script>
    $(function () {

        $("#notifyCatId").change(function () {
            var notifyCatId = $("#notifyCatId").val();
            $.post( "<?php echo SITE?>notification/GetCategoryUserType", { notifyCatId: notifyCatId }, function( data ) {
                if(data.error == 0){
                    $("#notifyUserType").html();
                    $("#notifyUserType").html(data.html);
                }
            }, "json");
        })

    })
</script>
