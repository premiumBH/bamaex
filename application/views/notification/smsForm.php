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
<form action="<?=CTRL?>notification/sms_form/<?php echo isset($id)?$id:''?>" method="POST" autocomplete="off">
    <input type="hidden" name="id" value="<?php echo isset($notification)?$notification->id:''?>"/>
    <input type="hidden" name="type" value="sms"/>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Name/Subject</label>
                <input type="text" name="name" value="<?php echo isset($notification)?$notification->name:''?>" class="form-control"/>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Category</label>
                <select class="form-control" name="notifyCatId" required>
                    <?php foreach ($notificationCats as $notificationCat){?>
                        <option value="<?php echo $notificationCat->id?>" <?php if(isset($notification) && $notificationCat->id == $notification->notify_cat_id){echo 'selected';}?>> <?php echo $notificationCat->cat_name?></option>
                    <?}?>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Template</label>
                <textarea class="ckeditor form-control" name="template" rows="6" data-error-container="#editor2_error"><?php echo isset($notification)?$notification->template:''?></textarea>
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
