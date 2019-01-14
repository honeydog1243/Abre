<?php

	/*
	* Copyright (C) 2016-2019 Abre.io Inc.
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

	//Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	$portal_root = getConfigPortalRoot();
	$portal_path_root = getConfigPortalPathRoot();
	$siteColor = getSiteColor();

	if(admin()){

		$schoolResults = getAllSchoolCodesAndNames();

?>

		<div class='row'>
			<div class='input-field col s12'>
				<input placeholder="Enter App Name" id="app_name" name="app_name" type="text" autocomplete="off" required>
				<label class="active" for="app_name">Name</label>
			</div>
		</div>
		<div class='row'>
			<div class='input-field col s12'>
				<input placeholder="Enter App Link" id="app_link" name="app_link" type="text" autocomplete="off" required>
				<label class="active" for="app_link">Link</label>
			</div>
		</div>
		<div class='row'>
			<div class='col m3 s12'>
				<input type="checkbox" id="app_staff" class="filled-in" value="1" />
				<label for="app_staff">Available for staff</label>
				<br><br>
				<div id='appsStaffRestrictionsDiv'>
					<label>Staff Building Restrictions</label>
					<select name="staffRestriction[]" id="appsStaffRestrictions" multiple>
						<option value="No Restrictions">All Buildings</option>
						<?php
						foreach($schoolResults as $code=>$school){
							echo "<option value='$code'>".ucwords(strtolower($school))."</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class='col m3 s12'>
				<input type="checkbox" id="app_students" class="filled-in" value="1" />
				<label for="app_students">Available for students</label>
				<br><br>
				<div id='appsStudentRestrictionsDiv'>
					<label>Student Building Restrictions</label>
					<select name="studentRestriction[]" id="appsStudentRestrictions" multiple>
						<option value="No Restrictions">All Buildings</option>
						<?php
						$lastSchoolCode = "";
						foreach($schoolResults as $code=>$school){
							echo "<option value='$code'>".ucwords(strtolower($school))."</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class='col m3 s12'>
				<input type="checkbox" id="app_parents" class="filled-in" value="1" />
				<label for="app_parents">Available for parents</label>
			</div>
		</div>
		<div class='row'>
			<div class='col s12'>
				<label>Select an Icon</label>
				<select id="app_icon" name="app_icon" class="image-picker browser-default" required>
				<?php
					$icons = scandir("$portal_path_root/core/images/apps/");
					foreach($icons as $iconimage){
						if(strpos($iconimage, ".png") !== false ){
							echo "<option data-img-src='/core/images/apps/$iconimage' data-img-class='appiconsholderpicker' value='$iconimage'></option>";
						}
					}
				?>
				</select>
			</div>
			<input id="app_id" name="app_id" type="hidden">
		</div>

<?php

	}
?>

<script src='core/js/image-picker.0.3.0.min.js'></script>
<script>

	$(function(){

		$("#app_staff").change(function(){
			if($(this).is(':checked')){
				$("#appsStaffRestrictionsDiv").show();
			}else{
				$("#appsStaffRestrictionsDiv").hide();
			}
		});

		$("#app_students").change(function(){
			if($(this).is(':checked')){
				$("#appsStudentRestrictionsDiv").show();
			}else{
				$("#appsStudentRestrictionsDiv").hide();
			}
		});

		//Call ImagePicker
		$('select').material_select();
		$('select').imagepicker();

	});

</script>
