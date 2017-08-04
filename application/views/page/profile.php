<?php
    $this->load->view('layout/header');
    $this->load->view('layout/container');
?>
    <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-user font-red"></i>
                            <span class="caption-subject font-red bold uppercase"> User Profile
                            </span>
                        </div>
                    </div>
                    <?php echo validation_errors('<div class="alert alert-danger alert-dismissible">', '</div>'); ?>
                    <?php echo $this->session->flashdata('error');?>
                    <?php echo $this->session->flashdata('success');?>
                    <form action="<?php echo SITE.'user/profile'?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="userId" value="<?php echo (isset($userData->intUserId) && $userData->intUserId!= '')?$userData->intUserId:'';?>"/>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">First Name</label>
                                        <input type="text" name="firstName" value="<?php echo (isset($userData->varFirstName) && $userData->varFirstName!= '')?$userData->varFirstName:'';?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Last Name</label>
                                        <input type="text" name="lastName" value="<?php echo (isset($userData->varLastName) && $userData->varLastName!= '')?$userData->varLastName:'';?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-envelope"></i>
                                            </span>
                                            <input type="email" name="email" value="<?php echo (isset($userData->varEmailId) && $userData->varEmailId!= '')?$userData->varEmailId:'';?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Phone Number</label>
                                        <div class="input-group">
                                            <input type="text" name="phone" value="<?php echo (isset($userData->varMobileNo) && $userData->varMobileNo!= '')?$userData->varMobileNo:'';?>" class="form-control">
                                            <span class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">City</label>
                                        <input type="text" name="city" value="<?php echo (isset($userData->city) && $userData->city!= '')?$userData->city:'';?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Country</label>
                                        <select class="form-control" name="country" id="sel1">
                                            <?php foreach ($counties as $country){?>
                                            <option value="<?php echo $country->id?>" <?php if(isset($userData->country_id) && $userData->country_id == $country->id){echo 'selected';}?> >
                                                <?php echo $country->country_name?>
                                            </option>
                                            <?php }?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label">Profile Picture</label>
                                        </div>
                                    </div>                                    
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <?php if(isset($userData->profile_image) && $userData->profile_image != ''){?>
                                            <?php if(is_file(realpath('.').$userData->profile_image) && file_exists(realpath('.').$userData->profile_image)){?>
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="<?php echo SITE.$userData->profile_image?>" alt="" />
                                                </div>
                                            <?php }else{?>
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                                                </div>
                                            <?php }?>
                                        <?php }else{?>
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                                            </div>
                                        <?php }?>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="profileImage"> </span>
                                            <a href="javascript:void(0);" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label">Company Logo</label>
                                        </div>
                                    </div>                                    
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <?php if(isset($userData->company_logo) && $userData->company_logo != ''){?>
                                            <?php if(is_file(realpath('.').$userData->company_logo) && file_exists(realpath('.').$userData->company_logo)){?>
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="<?php echo SITE.$userData->company_logo?>" alt="" />
                                                </div>
                                            <?php }else{?>
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                                                </div>
                                            <?php }?>
                                        <?php }else{?>
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" />
                                            </div>
                                        <?php }?>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> Select image </span>
                                                <span class="fileinput-exists"> Change </span>
                                                <input type="file" name="companyLogo"> </span>
                                            <a href="javascript:void(0);" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions text-right">
                            <input type="submit"  name="submit" value="Submit" class="btn green">
                            <button type="button" class="btn default">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php $this->load->view('layout/footer'); ?>

