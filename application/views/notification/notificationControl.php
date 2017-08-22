<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 8/19/2017
 * Time: 1:20 AM
 */?>
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
        <h2><?php echo $notificationCats[0]->cat_name?></h2>
    </div>
</div>
<?php echo validation_errors('<div class="alert alert-danger alert-dismissible">', '</div>'); ?>
<form action="<?=CTRL?>notification/notification_control/<?php echo isset($NotifyCatId)?$NotifyCatId:''?>" method="POST" autocomplete="off">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Notification Type</label>
                <select class="form-control" name="notificationType" id="notificationType" required>
                    <option value=""> Select Type</option>
                    <option value="email" <?php if(isset($notifyControlRecord) && $notifyControlRecord->notification_type == 'email'){echo 'selected';}?>> Email</option>
                    <option value="sms" <?php if(isset($notifyControlRecord) && $notifyControlRecord->notification_type == 'sms'){echo 'selected';}?>> SMS</option>
                    <option value="email&sms" <?php if(isset($notifyControlRecord) && $notifyControlRecord->notification_type == 'email&sms'){echo 'selected';}?>> Email&SMS</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>User Type</label>
                <select class="form-control" name="userType[]" id="userType" multiple required style="height: 140px;">
                    <?php foreach ($notifyCatUserType as $notifyCatUserTypeIN){?>
                        <option value="<?php echo $notifyCatUserTypeIN->notification_users_type_id?>" <?php if(isset($notifyControlRecord) && in_array($notifyCatUserTypeIN->notification_users_type_id, $notifyControlSelectedUserType)){echo 'selected';}?>> <?php echo $notifyCatUserTypeIN->user_type?></option>
                    <?}?>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Status</label><br/>
                <label class="mt-radio">
                    <input type="radio" name="status" id="optionsRadios22" value="1" <?php if( isset($notifyControlRecord)){if($notifyControlRecord->status == 1){echo 'checked';}}else{echo 'checked';}?> > Active
                    <span></span>
                </label>
                <label class="mt-radio">
                    <input type="radio" name="status" id="optionsRadios22" value="0" <?php if( isset($notifyControlRecord)){if($notifyControlRecord->status == 0){echo 'checked';}}?>> Inactive
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
