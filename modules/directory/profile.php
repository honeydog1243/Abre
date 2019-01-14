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
	require_once('permissions.php');
	$portal_root = getConfigPortalRoot();

	//Display User Profile Information
	if($pageaccess == 1 or $pageaccess == 2){
		$id = htmlspecialchars($_GET["id"], ENT_QUOTES);

		if($id != "new"){
			$sql = "SELECT * FROM directory WHERE id = $id AND archived = 0 AND siteID = '".$_SESSION['siteID']."'";
			$result = $db->query($sql);
			while($row = $result->fetch_assoc()){
				$firstname = htmlspecialchars($row["firstname"], ENT_QUOTES);
				$firstname = stripslashes($firstname);
				$middlename = htmlspecialchars($row["middlename"], ENT_QUOTES);
				$middlename = stripslashes($middlename);
				$lastname = htmlspecialchars($row["lastname"], ENT_QUOTES);
				$lastname = stripslashes($lastname);
				$title = htmlspecialchars($row["title"], ENT_QUOTES);
				$title = stripslashes($title);
				$contract = htmlspecialchars($row["contract"], ENT_QUOTES);
				$contract = stripslashes(htmlspecialchars(decrypt($contract, ""), ENT_QUOTES));
				$address = htmlspecialchars($row["address"], ENT_QUOTES);
				$address = stripslashes(htmlspecialchars(decrypt($address, ""), ENT_QUOTES));
				$city = htmlspecialchars($row["city"], ENT_QUOTES);
				$city = stripslashes(htmlspecialchars(decrypt($city, ""), ENT_QUOTES));
				$state = htmlspecialchars($row["state"], ENT_QUOTES);
				$state = stripslashes(htmlspecialchars(decrypt($state, ""), ENT_QUOTES));
				$zip = htmlspecialchars($row["zip"], ENT_QUOTES);
				$zip = stripslashes(htmlspecialchars(decrypt($zip, ""), ENT_QUOTES));
				$phone = htmlspecialchars($row["phone"], ENT_QUOTES);
				$phone = stripslashes(htmlspecialchars(decrypt($phone, ""), ENT_QUOTES));
				$extension = htmlspecialchars($row["extension"], ENT_QUOTES);
				$extension = stripslashes($extension);
				$cellphone = htmlspecialchars($row["cellphone"], ENT_QUOTES);
				$cellphone = stripslashes(htmlspecialchars(decrypt($cellphone, ""), ENT_QUOTES));
				$email = $row["email"];
				$emailSearch = mysqli_real_escape_string($db, $email);
				$email = htmlspecialchars($email, ENT_QUOTES);
				$ss = htmlspecialchars($row["ss"], ENT_QUOTES);
				$ss = stripslashes(htmlspecialchars(decrypt($ss, ""), ENT_QUOTES));
				$dob = htmlspecialchars($row["dob"], ENT_QUOTES);
				$dob = stripslashes(htmlspecialchars(decrypt($dob, ""), ENT_QUOTES));
				$gender = htmlspecialchars($row["gender"], ENT_QUOTES);
				$gender = stripslashes(htmlspecialchars(decrypt($gender, ""), ENT_QUOTES));
				$ethnicity = htmlspecialchars($row["ethnicity"], ENT_QUOTES);
				$ethnicity = stripslashes(htmlspecialchars(decrypt($ethnicity, ""), ENT_QUOTES));
				$classification = htmlspecialchars($row["classification"], ENT_QUOTES);
				$classification = stripslashes($classification);
				$location = htmlspecialchars($row["location"], ENT_QUOTES);
				$location = stripslashes($location);
				$grade = htmlspecialchars($row["grade"], ENT_QUOTES);
				$grade = stripslashes($grade);
				$subject = htmlspecialchars($row["subject"], ENT_QUOTES);
				$subject = stripslashes($subject);
				$doh = htmlspecialchars($row["doh"], ENT_QUOTES);
				$doh = stripslashes(htmlspecialchars(decrypt($doh, ""), ENT_QUOTES));
				$sd = htmlspecialchars($row["senioritydate"], ENT_QUOTES);
				$sd = stripslashes(htmlspecialchars(decrypt($sd, ""), ENT_QUOTES));
				$ed = htmlspecialchars($row["effectivedate"], ENT_QUOTES);
				$ed = stripslashes(htmlspecialchars(decrypt($ed, ""), ENT_QUOTES));
				$rategroup = htmlspecialchars($row["rategroup"], ENT_QUOTES);
				$rategroup = stripslashes(htmlspecialchars(decrypt($rategroup, ""), ENT_QUOTES));
				$step = htmlspecialchars($row["step"], ENT_QUOTES);
				$step = stripslashes(htmlspecialchars(decrypt($step, ""), ENT_QUOTES));
				$educationlevel = htmlspecialchars($row["educationlevel"], ENT_QUOTES);
				$educationlevel = stripslashes(htmlspecialchars(decrypt($educationlevel, ""), ENT_QUOTES));
				$salary = htmlspecialchars($row["salary"], ENT_QUOTES);
				$salary = stripslashes(htmlspecialchars(decrypt($salary, ""), ENT_QUOTES));
				$hours = htmlspecialchars($row["hours"], ENT_QUOTES);
				$hours = stripslashes(htmlspecialchars(decrypt($hours, ""), ENT_QUOTES));
				$probationreportdate = htmlspecialchars($row["probationreportdate"], ENT_QUOTES);
				$probationreportdate = stripslashes(htmlspecialchars(decrypt($probationreportdate, ""), ENT_QUOTES));
				$statebackgroundcheck = htmlspecialchars($row["statebackgroundcheck"], ENT_QUOTES);
				$statebackgroundcheck = stripslashes(htmlspecialchars(decrypt($statebackgroundcheck, ""), ENT_QUOTES));
				$federalbackgroundcheck = htmlspecialchars($row["federalbackgroundcheck"], ENT_QUOTES);
				$federalbackgroundcheck = stripslashes(htmlspecialchars(decrypt($federalbackgroundcheck, ""), ENT_QUOTES));
				$picture = htmlspecialchars($row["picture"], ENT_QUOTES);
				$stateeducatorid = htmlspecialchars($row["stateeducatorid"], ENT_QUOTES);
				$stateeducatorid = stripslashes(htmlspecialchars(decrypt($stateeducatorid, ""), ENT_QUOTES));

				$licensetype1 = htmlspecialchars($row["licensetype1"], ENT_QUOTES);
				$licensetype1 = stripslashes(htmlspecialchars(decrypt($licensetype1, ""), ENT_QUOTES));
				$licenseissuedate1 = htmlspecialchars($row["licenseissuedate1"], ENT_QUOTES);
				$licenseissuedate1 = stripslashes(htmlspecialchars(decrypt($licenseissuedate1, ""), ENT_QUOTES));
				$licenseexpirationdate1 = htmlspecialchars($row["licenseexpirationdate1"], ENT_QUOTES);
				$licenseexpirationdate1 = stripslashes(htmlspecialchars(decrypt($licenseexpirationdate1, ""), ENT_QUOTES));
				$licenseterm1 = htmlspecialchars($row["licenseterm1"], ENT_QUOTES);
				$licenseterm1 = stripslashes(htmlspecialchars(decrypt($licenseterm1, ""), ENT_QUOTES));

				$licensetype2 = htmlspecialchars($row["licensetype2"], ENT_QUOTES);
				$licensetype2 = stripslashes(htmlspecialchars(decrypt($licensetype2, ""), ENT_QUOTES));
				$licenseissuedate2 = htmlspecialchars($row["licenseissuedate2"], ENT_QUOTES);
				$licenseissuedate2 = stripslashes(htmlspecialchars(decrypt($licenseissuedate2, ""), ENT_QUOTES));
				$licenseexpirationdate2 = htmlspecialchars($row["licenseexpirationdate2"], ENT_QUOTES);
				$licenseexpirationdate2 = stripslashes(htmlspecialchars(decrypt($licenseexpirationdate2, ""), ENT_QUOTES));
				$licenseterm2 = htmlspecialchars($row["licenseterm2"], ENT_QUOTES);
				$licenseterm2 = stripslashes(htmlspecialchars(decrypt($licenseterm2, ""), ENT_QUOTES));

				$licensetype3 = htmlspecialchars($row["licensetype3"], ENT_QUOTES);
				$licensetype3 = stripslashes(htmlspecialchars(decrypt($licensetype3, ""), ENT_QUOTES));
				$licenseissuedate3 = htmlspecialchars($row["licenseissuedate3"], ENT_QUOTES);
				$licenseissuedate3 = stripslashes(htmlspecialchars(decrypt($licenseissuedate3, ""), ENT_QUOTES));
				$licenseexpirationdate3 = htmlspecialchars($row["licenseexpirationdate3"], ENT_QUOTES);
				$licenseexpirationdate3 = stripslashes(htmlspecialchars(decrypt($licenseexpirationdate3, ""), ENT_QUOTES));
				$licenseterm3 = htmlspecialchars($row["licenseterm3"], ENT_QUOTES);
				$licenseterm3 = stripslashes(htmlspecialchars(decrypt($licenseterm3, ""), ENT_QUOTES));

				$licensetype4 = htmlspecialchars($row["licensetype4"], ENT_QUOTES);
				$licensetype4 = stripslashes(htmlspecialchars(decrypt($licensetype4, ""), ENT_QUOTES));
				$licenseissuedate4 = htmlspecialchars($row["licenseissuedate4"], ENT_QUOTES);
				$licenseissuedate4 = stripslashes(htmlspecialchars(decrypt($licenseissuedate4, ""), ENT_QUOTES));
				$licenseexpirationdate4 = htmlspecialchars($row["licenseexpirationdate4"], ENT_QUOTES);
				$licenseexpirationdate4 = stripslashes(htmlspecialchars(decrypt($licenseexpirationdate4, ""), ENT_QUOTES));
				$licenseterm4 = htmlspecialchars($row["licenseterm4"], ENT_QUOTES);
				$licenseterm4 = stripslashes(htmlspecialchars(decrypt($licenseterm4, ""), ENT_QUOTES));

				$licensetype5 = htmlspecialchars($row["licensetype5"], ENT_QUOTES);
				$licensetype5 = stripslashes(htmlspecialchars(decrypt($licensetype5, ""), ENT_QUOTES));
				$licenseissuedate5 = htmlspecialchars($row["licenseissuedate5"], ENT_QUOTES);
				$licenseissuedate5 = stripslashes(htmlspecialchars(decrypt($licenseissuedate5, ""), ENT_QUOTES));
				$licenseexpirationdate5 = htmlspecialchars($row["licenseexpirationdate5"], ENT_QUOTES);
				$licenseexpirationdate5 = stripslashes(htmlspecialchars(decrypt($licenseexpirationdate5, ""), ENT_QUOTES));
				$licenseterm5 = htmlspecialchars($row["licenseterm5"], ENT_QUOTES);
				$licenseterm5 = stripslashes(htmlspecialchars(decrypt($licenseterm5, ""), ENT_QUOTES));

				$licensetype6 = htmlspecialchars($row["licensetype6"], ENT_QUOTES);
				$licensetype6 = stripslashes(htmlspecialchars(decrypt($licensetype6, ""), ENT_QUOTES));
				$licenseissuedate6 = htmlspecialchars($row["licenseissuedate6"], ENT_QUOTES);
				$licenseissuedate6 = stripslashes(htmlspecialchars(decrypt($licenseissuedate6, ""), ENT_QUOTES));
				$licenseexpirationdate6 = htmlspecialchars($row["licenseexpirationdate6"], ENT_QUOTES);
				$licenseexpirationdate6 = stripslashes(htmlspecialchars(decrypt($licenseexpirationdate6, ""), ENT_QUOTES));
				$licenseterm6 = htmlspecialchars($row["licenseterm6"], ENT_QUOTES);
				$licenseterm6 = stripslashes(htmlspecialchars(decrypt($licenseterm6, ""), ENT_QUOTES));

				$permissions = htmlspecialchars($row["permissions"], ENT_QUOTES);
				$permissions = stripslashes(htmlspecialchars(decrypt($permissions, ""), ENT_QUOTES));

				$role = htmlspecialchars($row["role"], ENT_QUOTES);
				$role = stripslashes(htmlspecialchars(decrypt($role, ""), ENT_QUOTES));

				$contractdays = htmlspecialchars($row["contractdays"], ENT_QUOTES);
				$contractdays = stripslashes(htmlspecialchars(decrypt($contractdays, ""), ENT_QUOTES));

				$id = htmlspecialchars($row["id"], ENT_QUOTES);

				echo "<div id='workcalendardisplay' style='display:none;'>Calendar for $firstname $lastname</div>";

			}
			$sql = "SELECT admin FROM users WHERE email = '$emailSearch' AND siteID = '".$_SESSION['siteID']."'";
			$result = $db->query($sql);
			while($row = $result->fetch_assoc()){
				$sysadmin = $row["admin"];
			}
		}else{

				$firstname = "";
				$middlename = "";
				$lastname = "";
				$title = "";
				$contract = "";
				$address = "";
				$city = "";
				$state = "";
				$zip = "";
				$phone = "";
				$extension = "";
				$cellphone = "";
				$email = "";
				$ss = "";
				$dob = "";
				$gender = "";
				$ethnicity = "";
				$classification = "";
				$location = "";
				$grade = "";
				$subject = "";
				$doh = "";
				$sd = "";
				$ed = "";
				$rategroup = "";
				$step = "";
				$educationlevel = "";
				$salary = "";
				$hours = "";
				$probationreportdate = "";
				$statebackgroundcheck = "";
				$federalbackgroundcheck = "";
				$picture = "";
				$stateeducatorid = "";

				$licensetype1 = "";
				$licenseissuedate1 = "";
				$licenseexpirationdate1 = "";
				$licenseterm1 = "";

				$licensetype2 = "";
				$licenseissuedate2 = "";
				$licenseexpirationdate2 = "";
				$licenseterm2 = "";

				$licensetype3 = "";
				$licenseissuedate3 = "";
				$licenseexpirationdate3 = "";
				$licenseterm3 = "";

				$licensetype4 = "";
				$licenseissuedate4 = "";
				$licenseexpirationdate4 = "";
				$licenseterm4 = "";

				$licensetype5 = "";
				$licenseissuedate5 = "";
				$licenseexpirationdate5 = "";
				$licenseterm5 = "";

				$licensetype6 = "";
				$licenseissuedate6 = "";
				$licenseexpirationdate6 = "";
				$licenseterm6 = "";

				$permissions = "";
				$role = "";
				$contractdays = "";
				$sysadmin = 0;

			}

			if (strpos($picture, 'http') === false) {

				if($picture == ""){
					$pictureserver = $portal_root."/modules/directory/images/user.png";
				}else{
					$pictureserver = $portal_root."/modules/directory/serveimage.php?file=$picture";
				}

			}
			else
			{
				$pictureserver=$picture;
			}

			echo "<div class='row'><div class='col s12'>";
				echo "<img src='$pictureserver' class='profile-avatar demoimage' alt='Profile Picture' style='display: block; margin: 20px auto;'>";
				echo "<h4 class='center-align demotext_dark'>$firstname $lastname</h4>";
			echo "</div></div>";

			echo "<div class='row'>";
				echo "<div class='col s12'><h5>Contact</h5></div>";
				echo "<div class='input-field col l4 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a First Name' value='$firstname' id='firstname' name='firstname' type='text' class='demotext_dark' autocomplete='off' required>";
					echo "<label class='active' for='firstname'>First Name</label>";
				echo "</div>";
				echo "<div class='input-field col l4 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Middle Name' value='$middlename' id='middlename' name='middlename' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='middlename'>Middle Name</label>";
				echo "</div>";
				echo "<div class='input-field col l4 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Last Name' value='$lastname' id='lastname' name='lastname' type='text' class='demotext_dark' autocomplete='off' required>";
					echo "<label class='active' for='lastname'>Last Name</label>";
				echo "</div>";
			echo "</div>";

			echo "<div class='row'>";
				echo "<div class='input-field col l4 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Address' value='$address' id='address' name='address' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='address'>Address</label>";
				echo "</div>";
				echo "<div class='input-field col l4 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a City' value='$city' id='city' name='city' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='city'>City</label>";
				echo "</div>";
				echo "<div class='input-field col l2 s12'>";
					echo "<select "; if($pageaccess == 2){ echo "disabled "; } echo "name='state'>";
						include "states.php";
					echo "</select>";
					echo "<label>State</label>";
				echo "</div>";
				echo "<div class='input-field col l2 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Zipcode' value='$zip' id='zip' name='zip' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='zip'>Zipcode</label>";
				echo "</div>";
			echo "</div>";

			echo "<div class='row'>";
				echo "<div class='input-field col l2 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Phone Number' value='$phone' id='phone' name='phone' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='phone'>Phone Number</label>";
				echo "</div>";
				echo "<div class='input-field col l2 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Phone Extension' value='$extension' id='extension' name='extension' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='extension'>Phone Extension</label>";
				echo "</div>";
				echo "<div class='input-field col l4 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Cell Number' value='$cellphone' id='cellphone' name='cellphone' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='cellphone'>Cell Number</label>";
				echo "</div>";
				echo "<div class='input-field col l4 s12'>";
					echo "<input "; if($pageaccess == 2){ echo "disabled "; } echo "placeholder='Enter a Email' value='$email' id='email' name='email' type='text' class='demotext_dark' autocomplete='off'>";
					echo "<label class='active' for='email'>Email</label>";
				echo "</div>";
			echo "</div>";

			if($pageaccess != 2){
				echo "<div class='row'>";
					echo "<div class='input-field col l4 s12'>";
						echo "<input placeholder='Enter a Social Security Number' value='$ss' id='ss' name='ss' type='text' class='demotext_dark' autocomplete='off'>";
						echo "<label class='active' for='middlename'>Social Security Number</label>";
					echo "</div>";
					echo "<div class='input-field col l4 s12'>";
						echo "<input placeholder='Enter a Date' type='text' value='$dob' name='dob' class='datepicker'>";
						echo "<label class='active' for='dob'>Date of Birth</label>";
					echo "</div>";
				echo "</div>";


				echo "<div class='row'>";
					echo "<div class='col s12'><h5>Profile Picture</h5></div>";
					echo "<div class='input-field col l4 s12'>";
						if($picture != "" xor $picture == 'user.png'){ echo "<input type='hidden' name='currentpicture' value='$picture'>"; }else{ echo "<input type='hidden' name='currentpicture' value=''>"; }
						echo "<input type='file' name='picture' />";
					echo "</div>";
				echo "</div>";

				echo "<div class='row'>";
					echo "<div class='col s12'><h5>Demographics</h5></div>";
					echo "<div class='input-field col l4 s12'>";
						echo "<select name='ethnicity'>";
							include "ethnicity.php";
						echo "</select>";
						echo "<label>Ethnicity</label>";
					echo "</div>";
					echo "<div class='input-field col l4 s12'>";
						echo "<select name='gender'>";
						include "gender.php";
						echo "</select>";
						echo "<label>Gender</label>";
					echo "</div>";
				echo "</div>";


				if($id != "new"){
					echo "<div class='row'>";
						echo "<div class='col s12'><h5>Discipline Reports</h5></div>";
						echo "<div id='disciplinediv'>"; include "profile_disciplinereport.php"; echo "</div>";
						echo "<div class='input-field col s12'>";
							echo "<input type='file' name='discipline' />";
						echo "</div>";
					echo "</div>";
				}
			}

			//Employment
			echo "<div class='row'>";
				echo "<div class='col s12'><h5>Classification</h5></div>";
					echo "<div class='input-field col l9 s12'>";
						echo "<select name='title'>";
							include "jobtitles.php";
						echo "</select>";
						echo "<label>Current Job Title</label>";
					echo "</div>";

					if($pageaccess == 2){
						echo "<div class='input-field col l3 s12'>";
							echo "<select name='location' disabled>";
								include "locations.php";
							echo "</select>";
							echo "<label>Home Building</label>";
						echo "</div>";
					}

					if($pageaccess != 2){
						echo "<div class='input-field col l3 s12'>";
							echo "<select name='contract'>";
								include "contract.php";
							echo "</select>";
						echo "<label>Contract</label>";
						echo "</div>";
						echo "</div>";

						echo "<div class='row'>";
							 echo "<div class='input-field col l3 s12'>";
								echo "<select name='classification'>";
								include "classification.php";
								echo "</select>";
								echo "<label>Classification Type</label>";
							 echo "</div>";
								echo "<div class='input-field col l3 s12'>";
									echo "<select name='location'>";
									include "locations.php";
									echo "</select>";
									echo "<label>Home Building</label>";
								echo "</div>";
								echo "<div class='input-field col l3 s12'>";
									echo "<select name='grade'>";
									include "grade.php";
									echo "</select>";
									echo "<label>Grade</label>";
								echo "</div>";
								echo "<div class='input-field col l3 s12'>";
									echo "<select name='subject'>";
									include "subject.php";
									echo "</select>";
									echo "<label>Subject</label>";
								echo "</div>";
							echo "</div>";
						echo "</div>";

							echo "<div class='row'>";
								echo "<div class='input-field col l3 s12'>";
									echo "<select name='rategroup'>";
									include "rategroup.php";
									echo "</select>";
									echo "<label>Choose a Group Rate</label>";
								echo "</div>";
								echo "<div class='input-field col l3 s12'>";
								  echo "<select name='step'>";
									include "steps.php";
									echo "</select>";
									echo "<label>Choose a Step</label>";
								echo "</div>";
								echo "<div class='input-field col l3 s12'>";
								  echo "<select name='educationlevel'>";
									include "educationlevel.php";
									echo "</select>";
									echo "<label>Level of Education</label>";
								echo "</div>";
								echo "<div class='input-field col l3 s12'>";
								    echo "<input placeholder='Enter a Salary' value='$salary' id='salary' name='salary' type='text' class='demotext_dark' autocomplete='off'>";
								    echo "<label class='active' for='salary'>Salary</label>";
								echo "</div>";
							echo "</div>";

							echo "<div class='row'>";
								echo "<div class='input-field col l3 s12'>";
									echo "<input placeholder='Enter Number of Hours' value='$hours' id='hours' name='hours' type='text' class='demotext_dark' autocomplete='off'>";
									echo "<label class='active' for='hours'>Hours</label>";
								echo "</div>";
								echo "<div class='input-field col l3 s12'>";
									echo "<input placeholder='Enter Number of Contracted Days' value='$contractdays' id='contractdays' name='contractdays' type='number' min='1' max='365' autocomplete='off'>";
									echo "<label class='active' for='contractdays'>Contract Days</label>";
								echo "</div>";
							echo "</div>";


							echo "<div class='row'>";
								echo "<div class='col s12'><h5>Dates</h5></div>";
									echo "<div class='input-field col l3 s12'>";
										echo "<input placeholder='Enter a Date' type='text' value='$doh' name='doh' class='datepicker'>";
									  echo "<label class='active' for='doh'>Date of Hire</label>";
									echo "</div>";
									echo "<div class='input-field col l3 s12'>";
									    echo "<input placeholder='Enter a Date' type='text' value='$sd' name='sd' class='datepicker'>";
									    echo "<label class='active' for='sd'>Seniority Date</label>";
									echo "</div>";
									echo "<div class='input-field col l3 s12'>";
									    echo "<input placeholder='Enter a Date' type='text' value='$ed' name='ed' class='datepicker'>";
									    echo "<label class='active' for='ed'>Effective Date</label>";
									echo "</div>";
					}
									echo "<div class='input-field col l3 s12'>";
									echo "<a class='printbutton mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored' style='background-color:"; echo getSiteColor(); echo "'><i class='material-icons'>date_range</i></a>";
									echo "</div>";
								echo "</div>";

							if($pageaccess != 2){
								echo "<div class='row'>";
									echo "<div class='input-field col l3 s12'>";
										echo "<input placeholder='Enter a Date' type='text' value='$probationreportdate' name='probationreportdate' class='datepicker'>";
										echo "<label class='active' for='probationreportdate'>Probationary Report Date</label>";
									echo "</div>";
									echo "<div class='input-field col l3 s12'>";
									    echo "<input placeholder='Enter a Date' type='text' value='$statebackgroundcheck' name='statebackgroundcheck' class='datepicker'>";
									    echo "<label class='active' for='statebackgroundcheck'>State Background Check</label>";
									echo "</div>";
									echo "<div class='input-field col l3 s12'>";
									    echo "<input placeholder='Enter a Date' type='text' value='$federalbackgroundcheck' name='federalbackgroundcheck' class='datepicker'>";
									    echo "<label class='active' for='federalbackgroundcheck'>Federal Background Check</label>";
									echo "</div>";
								echo "</div>";
							}

						echo "</div>";

						if($pageaccess != 2){

							include "profile_licenses.php";

							//Permissions
							if(admin()){

								echo "<div class='row'><div class='col l12'><h5>Permissions</h5></div></div>";
								echo "<div class='row'>";
									include "rolelist.php";
								echo "</div>";
								echo "<div class='row'>";
									echo "<div class='col l6 s12'>";
										 if($sysadmin == 1){
											 echo "<input type='checkbox' id='sysadmin' name='sysadmin' class='filled-in' value='1' checked/>";
										 }else{
											 echo "<input type='checkbox' id='sysadmin' name='sysadmin' class='filled-in' value='1'/>";
										 }
										 echo "<label for='sysadmin' style='color:#000;'>System Admin Privileges</label>";
									echo "</div>";
								echo "</div>";
							}else{
								echo "<input type='hidden' id='sysadmin' name='sysadmin' value='$sysadmin'/>";
							}
							echo "<input type='hidden' name='id' value='$id' id='userid'><br>";
						}
?>

<script>

		//Work Schedule Modal
		$('.modal-viewschedule').leanModal({ in_duration: 0, out_duration: 0 });

		//Process the profile form
		$(function() {

			//Select Dropdown
    	$('select').material_select();

    	//Date Picture
    	$('.datepicker').pickadate({
				selectMonths: true,
				selectYears: 160
			});

			//Delete Discipline Record
			$('#disciplinediv').on('click','.deletedisciplinerecord',function(event){
				event.preventDefault();
				var fileid = $(this).find("a").attr("href");
			    $.ajax({
				    type: 'POST',
				    url: 'modules/directory/disciplinereport_delete.php',
				    data: { id : fileid }
				})
				//Show the notification
				.done(function(response) {
					<?php echo "$( '#disciplinediv' ).load( 'modules/directory/profile_disciplinereport.php?userid=$id' );"; ?>
						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: response };
						notification.MaterialSnackbar.showSnackbar(data);
				})
			});

			//Work Days for Employee
			//same logic found in modules/profile/modals.php. Please reference
			//this file for documentation on the logic. Only difference here is that
			//it uses the email of the staff member being inspected and uses
			//the year of the current day.
			var date = new Date();
			var y = date.getFullYear();
			if(date.getMonth() <= 6){
				y = y - 1;
			}
			var defaultDate = '8/1/'+y;
			var currYear = y;
			var email = "<?php echo $email ?>";

			$.ajax({
				type: 'POST',
				url: '/modules/profile/load_dates.php',
				data: { year : y, email: email },
			})
			.done(function(response) {
				var dateArray = response.addDates;
				var json = response.jsonDates;

				$('#workcalendardisplay').multiDatesPicker({
					addDates: dateArray,
					numberOfMonths: [6,2],
					defaultDate: defaultDate,
					altField: '#saveddates',
					dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
					onSelect: function (date) {

						var dates = $('#workcalendardisplay').multiDatesPicker('getDates').length;
						$("#selecteddays").text(dates + " Days Selected");

						var datestosave = $( "#saveddates" ).val();

						$.ajax({
							type: 'POST',
							url: '/modules/profile/calendar_update.php',
							data: { year: y, calendardaystosave : datestosave, jsonDates: json, email: email },
						})
					}
				});

				var dates = $('#workcalendardisplay').multiDatesPicker('getDates').length;
				$("#selecteddays").text(dates + " Days Selected");
			})

			//Print Spcific Div
			$(".printbutton").click(function(e) {
				e.preventDefault();
				var win = window.open('','printwindow');
				win.document.write('<title>Print Work Calendar</title><link rel="stylesheet" type="text/css" href="modules/profile/css/calendar.css">');
				win.document.write($("#workcalendardisplay").html());
			});
		});

 <?php
	}
?>