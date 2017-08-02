<?php
  $this->load->view('layout/header');
  $this->load->view('layout/container');
$flag = 0;
$pp1 = '';
$pl1 = '';
$ps1 = '';
$pi1 = '';
$id1 = '';
if(isset($_REQUEST['edit-id']))
{ 
  if($_REQUEST['edit-id'] != '')
  {
	  $id1 = $_REQUEST['edit-id'];
			$sqlQuery = "select
    t.inParentId,
    t.Label,
    t.varPageSlug,
    t.varIcon,
    (select s.Label from access_control as s where s.intID = t.inParentId) as ParentName
from access_control as t where t.intID = $id1";
			$result = $this->db->query($sqlQuery);
			
			if($result->num_rows()>0) {
				foreach($result->result() AS $result11)
				{					
				// print_r($result11);
						$flag = 1;
						$pp1 = '<option value="'.$result11->inParentId.'"> '.$result11->ParentName.' </option>';
						$pl1 = $result11->Label;
						$ps1 = $result11->varPageSlug;
						$pi1 = '<option value="'.$result11->varIcon.'">'.$result11->varIcon.'</option>';
                 }
            } 
  }
}

?>

                    <!-- BEGIN PAGE BASE CONTENT -->
                   <div class="row">				  
					   <form action="<?=CTRL?>pages" method="POST" autocomplete="off">
							<div class="col-md-6">
							<div class="form-group">
		                    <label>Parent Page</label>
		                    <select class="form-control" name='parent_page'>
<?php
				echo $pp1.'<option value="0"> -- </option>';

			$sqlQuery = " SELECT "
			                ." intID AS id, "
			                ." Label AS label"
			        ." FROM access_control";
			$result = $this->db->query($sqlQuery);
			
			if($result->num_rows()>0) {
				foreach($result->result() AS $result11)
				{					
				echo '<option value="'.$result11->id.'">'.$result11->label.'</option>';
				}
            } 

?>
                                                   </select>
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group">
		                    <label>Label</label>
		                    <input class="form-control spinner" type='text' name='page_label' required size='35' value="<?php echo $pl1; ?>">
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group">
		                    <label>Page slug</label>
		                    <input class="form-control spinner" type='text' name='page_add' required size='35' value="<?php echo $ps1; ?>">
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group">
		                    <label>Icon</label>
		                    <select class="form-control" name='page_icon'>
                            <?php echo $pi1.'<option value=""> Select Icon </option>'; ?>
                                      <option value="icon-home">icon-home</option>
                                      <option value="icon-diamond">icon-diamond</option>
                                      <option value="icon-puzzle">icon-puzzle</option>
                                      <option value="icon-settings">icon-settings</option>
                                      <option value="icon-bulb">icon-bulb</option>
                                      <option value="icon-briefcase">icon-briefcase</option>
                                      <option value="icon-wallet">icon-wallet</option>
                                      <option value="icon-bar-chart">icon-bar-chart</option>
                                      <option value="icon-pointer">icon-pointer</option>
                                      <option value="icon-layers">icon-layers</option>
                                      <option value="icon-basket">icon-basket</option>
                                      <option value="icon-docs">icon-docs</option>
                                      <option value="icon-user">icon-user</option>
                                      <option value="icon-social-dribbble">icon-social-dribbble</option>
                                      <option value="icon-folder">icon-folder</option>
                         </select>
							</div>
							</div>
							<div class="col-md-6">
							<div class="form-group">
							<br/>
                            <?php
                            if($flag == 1)
							{
								
								echo '<input type="hidden" value="'.$id1.'" id="" name="update_page" />'."<input type='submit' name='action-update-page' value='Update Page' class='btn green'>";
							}
							else
							{
								echo ' <input type="hidden" value="update" id="" name="add_new_page" />'."<input type='submit' name='action-add-page' value='Add Page' class='btn green'>";
							}
							?>
							
							</div>
                            </div>
						</form>
                        </div>

				<div class="clearfix">&nbsp;</div>
