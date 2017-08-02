<?php

$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

				
				
        $this->load->view('layout/header');
        $this->load->view('layout/container');
		$this->load->library('encrypt');
$flag = 0;
$ids = '';
						$fn = '';
						$ln = '';
						$em = '';
						$mn = '';
						$pass = '';
						$sid1 = '';

if(isset($_REQUEST['edit-id']))
{ 
  if($_REQUEST['edit-id'] != '')
  {
	  $id1 = $_REQUEST['edit-id'];
			$sqlQuery = "SELECT * FROM user LEFT JOIN user_type ON user_type.intUserTypeId=user.intUserTypeId where user.intUserId = $id1";
			$result = $this->db->query($sqlQuery);
			
			if($result->num_rows()>0) {
				foreach($result->result() AS $result11)
				{					
				// print_r($result11);
						$flag = 1;
						$ids = $result11->intUserId;
						$fn = $result11->varFirstName;
						$ln = $result11->varLastName;
						$em = $result11->varEmailId;
						$mn = $result11->varMobileNo;
						$pass = $this->encrypt->decode($result11->varPassword);
					
						$sid1 = '<option value="'.$result11->intUserTypeId.'">'.$result11->varUserTypeName.'</option>';
                 }
            } 
  }
}

		?>
        <!-- BEGIN PAGE BASE CONTENT -->
		<div class="row">
		        <!--form part 1:Sender Form-->
                        <div class="col-md-6">
                            <!-- BEGIN VALIDATION STATES-->
                            <div class="portlet light portlet-fit portlet-form bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class=" icon-layers font-green"></i>
                                        <span class="caption-subject font-green sbold uppercase">Sender Details::</span>
                                    </div>
                                    <div class="actions">
                                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                            <i class="icon-cloud-upload"></i>
                                        </a>
                                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                            <i class="icon-wrench"></i>
                                        </a>
                                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <!-- BEGIN FORM-->
                                    <form action="#" class="form-horizontal">
                                        <div class="form-body">
										
										
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Account/Company</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Account/Company goes here...</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <label class="col-md-3 control-label" for="form_control_1">Countary</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="select_countary">
													<option value="-1">--Country--</option>
													<? foreach($countries as $code){?>
													
                                                        <option value="<?=$code?>"><?=$code?></option>
														<? }?>
                                                        
                                                        
                                                    </select>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Some help goes here...</span>
                                                </div>
                                            </div>
                                            
                                            
                                           
                                           
                                            
                                            <div class="form-group form-md-line-input has-error">
                                                <label class="col-md-3 control-label" for="form_control_1">Address</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="" rows="3"></textarea>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Enter your Address...</span>
                                                </div>
                                            </div>
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Address2</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Address2 goes here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Address3</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Address3 goes here...</span>
                                                </div>
                                            </div>
											
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Postcode/Town</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Postcode/Town goes here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Province</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Province goes here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Contact Person</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="Contact Person">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Contact Person Type here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Phone Number</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Phone Number Type here...</span>
                                                </div>
                                            </div>
											
											
											<div class="form-group form-md-line-input has-success">
                                                <label class="col-md-3 control-label" for="form_control_1">Email*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group has-success">
                                                        <input type="text" class="form-control" placeholder="Email Address">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>
                                                        <div class="form-control-focus"> </div>
                                                    </div>
                                                </div>
                                            </div>
											
                                          <!-- checkbox data-->
                                            <!--<div class="form-group form-md-checkboxes">
                                                <label class="col-md-3 control-label" for="form_control_1">Checkboxes</label>
                                                <div class="col-md-9">
                                                    <div class="md-checkbox-list">
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" id="checkbox1_1" class="md-check">
                                                            <label for="checkbox1_1">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 1 </label>
                                                        </div>
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" id="checkbox1_2" class="md-check">
                                                            <label for="checkbox1_2">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 2 </label>
                                                        </div>
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" id="checkbox1_211" class="md-check">
                                                            <label for="checkbox1_211">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 3 </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                                            <!--<div class="form-group form-md-checkboxes has-error">
                                                <label class="col-md-3 control-label" for="form_control_1">Checkboxes(error)</label>
                                                <div class="col-md-9">
                                                    <div class="md-checkbox-inline">
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" id="checkbox1_3" class="md-check">
                                                            <label for="checkbox1_3">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 1 </label>
                                                        </div>
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" id="checkbox1_4" class="md-check" checked="">
                                                            <label for="checkbox1_4">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 2 </label>
                                                        </div>
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" id="checkbox1_5" class="md-check">
                                                            <label for="checkbox1_5">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 3 </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                                           
										    <!--<div class="form-group form-md-radios has-success">
                                                <label class="col-md-3 control-label" for="form_control_1">Radios(success)</label>
                                                <div class="col-md-9">
                                                    <div class="md-radio-list">
                                                        <div class="md-radio">
                                                            <input type="radio" id="checkbox1_6" name="radio211" class="md-radiobtn">
                                                            <label for="checkbox1_6">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 1 </label>
                                                        </div>
                                                        <div class="md-radio">
                                                            <input type="radio" id="checkbox1_7" name="radio211" class="md-radiobtn" checked="">
                                                            <label for="checkbox1_6">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 2 </label>
                                                        </div>
                                                        <div class="md-radio">
                                                            <input type="radio" id="checkbox1_611" name="radio211" class="md-radiobtn" checked="">
                                                            <label for="checkbox1_611">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Option 3 </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                                            
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <a href="javascript:;" class="btn green">Submit</a>
                                                    <a href="javascript:;" class="btn default">RESET</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- END FORM-->
                                </div>
                            </div>
                            <!-- END VALIDATION STATES-->
                        </div>
                        
						<!--2nd part of form -->
						<div class="col-md-6">
                            <!-- BEGIN VALIDATION STATES-->
                            <div class="portlet light portlet-fit portlet-form bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class=" icon-layers font-green"></i>
                                        <span class="caption-subject font-green sbold uppercase">Receiver Details:</span>
                                    </div>
                                    <div class="actions">
                                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                            <i class="icon-cloud-upload"></i>
                                        </a>
                                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                            <i class="icon-wrench"></i>
                                        </a>
                                        <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <!-- BEGIN FORM-->
                                    <form action="#" class="form-horizontal">
                                        <div class="form-body">
										
										
                                            <div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Account/Company</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Account/Company goes here...</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group form-md-line-input has-success">
                                                <label class="col-md-3 control-label" for="form_control_1">Countary</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="select_countary">
													<option value="-1">--Country--</option>
													<? foreach($countries as $code){?>
													
                                                        <option value="<?=$code?>"><?=$code?></option>
														<? }?>
                                                        
                                                        
                                                    </select>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Some help goes here...</span>
                                                </div>
                                            </div>
                                            
                                            
                                           
                                           
                                            
                                            <div class="form-group form-md-line-input has-error">
                                                <label class="col-md-3 control-label" for="form_control_1">Address</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="" rows="3"></textarea>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Enter your Address...</span>
                                                </div>
                                            </div>
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Address2</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Address2 goes here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Address3</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Address3 goes here...</span>
                                                </div>
                                            </div>
											
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Postcode/Town</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Postcode/Town goes here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Province</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Province goes here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Contact Person</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="Contact Person">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Contact Person Type here...</span>
                                                </div>
                                            </div>
											
											<div class="form-group form-md-line-input">
                                                <label class="col-md-3 control-label" for="form_control_1">Phone Number</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">Phone Number Type here...</span>
                                                </div>
                                            </div>
											
											
											<div class="form-group form-md-line-input has-success">
                                                <label class="col-md-3 control-label" for="form_control_1">Email*</label>
                                                <div class="col-md-9">
                                                    <div class="input-group has-success">
                                                        <input type="text" class="form-control" placeholder="Email Address">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>
                                                        <div class="form-control-focus"> </div>
                                                    </div>
                                                </div>
                                            </div>
											
                                          <!-- checkbox data-->
                                         
                                            
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <a href="javascript:;" class="btn green">Send</a>
                                                    <a href="javascript:;" class="btn default">RESET</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- END FORM-->
                                </div>
                            </div>
                            <!-- END VALIDATION STATES-->
                        </div>
						
						<!--2nd part of form -->
						
                    </div>
		
		<!--gfffffffffffffffffffff-->
                   <div class="row">
				   <!--<div class="col-md-12">
				  <h2>Create Order ::--</h2>
                      </div>-->
				   </div>
                    
					
					
					</div>
					</div>
                            </div>
				 
					
					</div>
					
					</div>
					
            </div>
                        </div>
                    </div>
                            <?php
        $this->load->view('layout/footer');   
        ?>
