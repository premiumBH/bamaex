<?php $this->load->view('layout/header');
$this->load->view('layout/container'); ?>        <!-- BEGIN PAGE BASE CONTENT -->
<div class="row">
    <?php echo $this->session->flashdata('success')?>
    <div class="col-md-12"><a href="<?= CTRL ?>AjentOrders/createEditAgent"><input value="Create Agent" class="btn green" type="button"></a><br/><br/>
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-cogs"></i>Agent</div>
                <div class="tools"><a href="javascript:;" class="collapse" data-original-title="" title=""> </a> <a
                            href="#portlet-config" data-toggle="modal" class="config" data-original-title=""
                            title=""> </a> <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                    <a href="javascript:;" class="remove" data-original-title="" title=""> </a></div>
            </div>
        </div>
        <div class="portlet-body">
            <div class="table-responsive">                                                                        <? ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Full Name</th>
                        <th> Email</th>
                        <th> Mobile</th>
                        <th> User Level</th>
                        <th> Created On</th>
                    </tr>
                    </thead> <?php if ($result1['status'] = 'true') {
                        if (isset($result1['users'])) {
                            foreach ($result1['users'] AS $result) {?>
                                <tr>
                                    <td>
                                        <input type="hidden" value="<?php echo  $result->intUserId; ?>" name="id[]" />
                                        <?php echo  $result->intUserId; ?>
                                    </td>
                                    <td>
                                        <?php echo  $result->varFirstName . ' ' . $result->varLastName; ?>
                                    </td>
                                    <td>
                                        <?php echo  $result->varEmailId; ?>
                                    </td>
                                    <td>
                                        <?php echo  $result->varMobileNo; ?>
                                    </td>
                                    <td>
                                        <?php echo  $result->varUserTypeName; ?>
                                    </td>
                                    <td>
                                        <?php echo  $result->dtCreated ; ?>
                                    </td>
                                    <td>
                                    <a href="<?php echo   CTRL; ?>AjentOrders/createEditAgent/<?php echo $result->intUserId; ?>" class="btn red">Edit</a>
                                  <!--      <?php /*if($result->userStatus == 1){*/?>
                                    <a href=" <?php /*echo   CTRL ; */?>user/changeStatus/<?php /*echo $result->intUserId; */?>/0" class="btn red">Suspend </a>
                                     <?php /*}else{*/?>
                                    <a href=" <?php /*echo   CTRL ; */?>user/changeStatus/<?php /*echo $result->intUserId; */?>/1" class="btn red">Unsuspend </a>
                                     --><?php /*}*/?>
                                    <a href=" <?php echo   CTRL ; ?>AjentOrders/deleteAgent/<?php echo $result->intUserId; ?>" class="btn red">Delete</a>
                                    </td>
                                </tr>

                            <?php }
                        }
                    }
                    echo '</table>'; ?>
            </div>
        </div>
    </div>
</div>                                                                    <?php $this->load->view('layout/footer'); ?>