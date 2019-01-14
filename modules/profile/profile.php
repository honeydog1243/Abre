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
	require_once(dirname(__FILE__) . '/../../core/abre_google_login.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	$portal_root = getConfigPortalRoot();
	$siteColor = getSiteColor();

	$schoolCodeArray = getRestrictions();

	//Get profile information
	$sql = "SELECT startup, streams FROM profiles WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
	$dbreturn = databasequery($sql);
	foreach($dbreturn as $row){
		$setting_startup = htmlspecialchars($row['startup'], ENT_QUOTES);
		$setting_streams = htmlspecialchars($row['streams'], ENT_QUOTES);
	}

	//Get Contracted Days
	$sql = "SELECT contractdays, title FROM directory WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
	$dbreturn = databasequery($sql);
	foreach($dbreturn as $row){
		$contractdays = htmlspecialchars($row['contractdays'], ENT_QUOTES);
		if($contractdays != ""){
			$contractdays = decrypt($contractdays, "");
		}
	}

	//Profile form
	echo "<form id='form-profile' method='post' action='$portal_root/modules/profile/profile_update.php'>";

		//Bio
		echo "<div class='page_container page_container_limit'>";
			echo "<div class='row center-align' style='margin-top:30px;'>";
				echo "<img src='".$_SESSION['picture']."?sz=120' style='border-radius: 50%; width:120px; height:120px;'>";
				echo "<div class='col s12'><h4 class='truncate' style='margin:10px 0 10px 0;'>".$_SESSION['displayName']."</h4>";
					//Display Job Title
					foreach($dbreturn as $row){
						$job_title = htmlspecialchars($row['title'], ENT_QUOTES);
						$job_title = stripslashes($job_title);
						echo "<h6 class='truncate' style='margin:0;'>$job_title</h6>";
					}
				echo "</div>";
			echo "</div>";
		echo "</div>";

		//Streams
		echo "<div id='streamcontainer' class='page_container page_container_limit mdl-shadow--4dp'>";
		echo "<div class='page'>";
			echo "<div class='row'>";
				echo "<div class='col s12'><h3>Announcements & News</h3></div>";
				echo "<div class='col s12'><p>Personalize your announcements and news by choosing from the options below.</p></div>";
				echo "<div class='col s12'><div id='streamerror'></div></div>";
			echo "</div>";
			echo "<div class='row'>";

				$sql = "SELECT streams FROM profiles WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
				$result = $db->query($sql);
				$resultrow = $result->fetch_assoc();
				$streamValues = explode(',', $resultrow["streams"]);
				$streamValues = array_unique($streamValues, SORT_NUMERIC);

				$dcount = 0;
				$sql = "SELECT title, id, `group`, staff_building_restrictions, student_building_restrictions FROM streams WHERE `required` != 1 AND siteID = '".$_SESSION['siteID']."' ORDER BY type, title";
				$dbreturn = databasequery($sql);
				foreach($dbreturn as $row){
					if(strpos($row["group"], $_SESSION["usertype"]) !== false){
						$title = htmlspecialchars($row['title'], ENT_QUOTES);
						$id = htmlspecialchars($row['id'], ENT_QUOTES);

						if($_SESSION['usertype'] == "staff"){
							$restrictions = $row['staff_building_restrictions'];
							$restrictionsArray = explode(",", $restrictions);
						}
						if($_SESSION['usertype'] == "student"){
							$restrictions = $row['student_building_restrictions'];
							$restrictionsArray = explode(",", $restrictions);
						}


						$returncount = 0;
						if($restrictions == NULL || in_array("No Restrictions", $restrictionsArray)){
							foreach($streamValues as $value){
								if($value == $id){
									echo "<div class='col m4 s6'>";
										echo "<input type='checkbox' class='formclick filled-in streamtopic' id='checkbox_$dcount' name='checkbox_$dcount' value='$id' checked='checked' /><label for='checkbox_$dcount' style='color:#000;'>$title</label>";
									echo "</div>";
									$returncount = 1;
								}
							}
							if($returncount == 0){
								echo "<div class='col m4 s6'>";
									echo "<input type='checkbox' class='formclick filled-in streamtopic' id='checkbox_$dcount' name='checkbox_$dcount' value='$id' /><label for='checkbox_$dcount' style='color:#000;'>$title</label>";
								echo "</div>";
							}
							$dcount++;
						}else{
							if(!empty($schoolCodeArray)){
								foreach($schoolCodeArray as $code){
									if(in_array($code, $restrictionsArray)){
										foreach($streamValues as $value){
											if($value == $id){
												echo "<div class='col m4 s6'>";
													echo "<input type='checkbox' class='formclick filled-in streamtopic' id='checkbox_$dcount' name='checkbox_$dcount' value='$id' checked='checked' /><label for='checkbox_$dcount' style='color:#000;'>$title</label>";
												echo "</div>";
												$returncount = 1;
											}
										}
										if($returncount == 0){
											echo "<div class='col m4 s6'>";
												echo "<input type='checkbox' class='formclick filled-in streamtopic' id='checkbox_$dcount' name='checkbox_$dcount' value='$id' /><label for='checkbox_$dcount' style='color:#000;'>$title</label>";
											echo "</div>";
										}
										$dcount++;
										break;
									}
								}
							}
						}
					}
	    	}
	    	if($dcount == 0){ echo "<div class='col s12'>No available streams</div>"; }
				echo "<div class='col s12'>";
				echo "<input type='hidden' name='departmentcount' value='$dcount'><br>";
				echo "</div>";
			echo "</div>";
			if((admin() || isStreamHeadlineAdministrator()) && $_SESSION['usertype'] == 'staff'){
				echo "<div class='row'><div class='col s12'><a class='modal-editstreams waves-effect btn-flat white-text' href='#streameditor' style='background-color: "; echo $siteColor; echo "'>Manage</a></div></div>";
			}
		echo "</div>";
		echo "</div>";

		if(admin() || isStreamHeadlineAdministrator()){
			echo "<div id='startupcontainer' class='page_container page_container_limit mdl-shadow--4dp'>";
			echo "<div class='page'>";
				echo "<div class='row'>";
					echo "<div class='col s12'><h3>Headlines</h3></div>";
					echo "<div class='col s12'><p>Customize action-required information to show users upon login.</p></div>";
				echo "</div>";
				echo "<div class='row'>";
				echo "</div>";
				echo "<div class='row'><div class='col s12'><a class='modal-editheadlines waves-effect btn-flat white-text' href='#headlineseditor' style='background-color: "; echo $siteColor; echo "'>Manage</a></div></div>";
			echo "</div>";
			echo "</div>";
		}


		//Work Calendar
		if($_SESSION['usertype'] == "staff"){
			echo "<div class='row'>";
			echo "<div class='page_container page_container_limit'>";
			echo "<hr style='margin-bottom:20px;'>";

				//Card settings
				echo "<div class='col l6 s12' style='padding:20px;'>";
						echo "<h3 style='margin-top:5px;'>Work Calendar</h3><p>Review your work calendar for the upcoming school year. Your calendar will automatically be shared with Human Resources.<br><br></p>";
				echo "</div>";
				echo "<div class='page col l6 s12 mdl-shadow--4dp'>";
					echo "<div style='margin:30px 20px 20px 20px;'>";
						if(isset($contractdays) && $contractdays != NULL){
							echo "<p>According to Human Resources, you are contracted to work <b>$contractdays</b> days.</p>";
						}else{
							echo "<p>You can use the calendar below to choose your work schedule.</p>";
						}
						echo "<a href='#viewschedule' class='modal-viewschedule' style='line-height:40px; color:".$siteColor."'>SET CALENDAR</a><br><a href='#' class='printbutton' style='line-height:40px; color:".$siteColor."'>PRINT CALENDAR</a>";

					echo "</div>";
				echo "</div>";
			echo "</div>";
			echo "</div>";
		}

	echo "</form>";
?>

<script>

	$(function(){
		//Work Schedule Modal
		$('.modal-viewschedule').leanModal({ in_duration: 0, out_duration: 0 });
		$('.modal-editstreams').leanModal({
			in_duration: 0,
			out_duration: 0,
			ready: function(){
				$(".modal-content").scrollTop(0);
			}
		});

		$('.modal-editheadlines').unbind().click(function(event){
			event.preventDefault();
			$('#headlineseditor').openModal({
				in_duration: 0,
				out_duration: 0,
				ready: function(){
					$(".modal-content").scrollTop(0);
				}
			});
		});

		$(".formclick").click(function() {

		<?php if($_SESSION['usertype'] == "student" || $_SESSION['usertype'] == 'parent' || $_SESSION['usertype'] == 'staff'){ ?>
				$('.modal-viewapps').show();
				var formData = $('#form-profile').serialize();
				$.ajax({
					type: 'POST',
					url: $('#form-profile').attr('action'),
					data: formData
				})
				//Show the notification
				.done(function(response) {
					$('#streamerror').show();
					$('#streamerror').html("<h6 style='color: <?php echo $siteColor; ?>'>Great picks! Follow more topics or hit Done to see your Stream. <a href='#' class='waves-effect waves-light btn mdl-color-text--white' style='background-color: <?php echo $siteColor; ?>'>Done</a></h6>");
					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: 'Your changes have been saved.' };
					notification.MaterialSnackbar.showSnackbar(data);
				})
		<?php } ?>
		});

		//Print Spcific Div
		$(".printbutton").click(function(e){
			e.preventDefault();
			var win = window.open('','printwindow');
			win.document.write('<html><head><title>Print Work Calendar</title><link rel="stylesheet" type="text/css" href="modules/profile/css/calendar.css"><link href="https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,900,100" rel="stylesheet" type="text/css"></head><body>');
			win.document.write($("#workcalendardisplay").html());
			win.document.write('</body></html>');
		});
	});

</script>