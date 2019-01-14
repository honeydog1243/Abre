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
	require_once('permissions.php');

	//Show the Search and Last 10 Modified Users
	if($pageaccess == 1){

		$dropdownArray = array();
		$sql = "SELECT dropdownID, options FROM directory_settings WHERE siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		while($row = $result->fetch_assoc()){
			$key = $row['dropdownID'];
			$dropdownArray[$key] = $row['options'];
		}
		echo "<div id='updateHolder'>";
		echo "<div class='page_container mdl-shadow--4dp'>";
			echo "<div class='page'>";

				echo "<div class ='col s12'>";
				echo "<form id='directory-settings-form' method='post' enctype='multipart/form-data' action='modules/directory/updatesettings.php'>";
					echo "<div class='row'><div class='col s12'>";
						echo "<h5>Dropdown Options</h5>";
					echo "</div></div>";
					echo "<div class='row'>";
						echo "<div class='input-field col l6 s12'>";
							if(!$dropdownArray['jobTitles'] == "" && isset($dropdownArray['jobTitles'])){
								echo "<textarea class='materialize-textarea' placeholder='Enter Job Titles Separated by New Line' id='jobTitles' name='jobTitles'>".$dropdownArray['jobTitles']."</textarea>";
							}else{
								echo "<textarea class='materialize-textarea' placeholder='Enter Job Titles Separated by New Line' id='jobTitles' name='jobTitles'></textarea>";
							}
							echo "<label for='jobTitles' class='active'>Job Titles</label>";
						echo "</div>";
						echo "<div class='input-field col l6 s12'>";
							if(!$dropdownArray['contractOptions'] == "" && isset($dropdownArray['contractOptions'])){
								echo "<textarea class='materialize-textarea' placeholder='Enter Contract Options Separated by New Line' id='contractOptions' name='contractOptions'>".$dropdownArray['contractOptions']."</textarea>";
							}
							else{
								echo "<textarea class='materialize-textarea' placeholder='Enter Contract Options Separated by New Line' id='contractOptions' name='contractOptions'></textarea>";
							}
							echo "<label for='jobOptions' class='active'>Contract Options</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col l6 s12'>";
							if(!$dropdownArray['classificationTypes'] == "" && isset($dropdownArray['classificationTypes'])){
								echo "<textarea class='materialize-textarea' placeholder='Enter Classificaton Types Separated by New Line' id='classificationTypes' name='classificationTypes'>".$dropdownArray['classificationTypes']."</textarea>";
							}else{
								echo "<textarea class='materialize-textarea' placeholder='Enter Classificaton Types Separated by New Line' id='classificationTypes' name='classificationTypes'></textarea>";
							}
							echo "<label for='classificationTypes' class='active'>Classification Types</label>";
						echo "</div>";
						echo "<div class='input-field col l6 s12'>";
							if(!$dropdownArray['homeBuildings'] == "" && isset($dropdownArray['homeBuildings'])){
								echo "<textarea class='materialize-textarea' placeholder='Enter Buildings Separated by New Line' id='homeBuildings' name='homeBuildings'>".$dropdownArray['homeBuildings']."</textarea>";
							}else{
								echo "<textarea class='materialize-textarea' placeholder='Enter Buildings Separated by New Line' id='homeBuildings' name='homeBuildings'></textarea>";
							}
							echo "<label for='homeBuildings' class='active'>Home Buildings</label>";
						echo "</div>";
			  	echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col l6 s12'>";
							if(!$dropdownArray['subjects'] == "" && isset($dropdownArray['subjects'])){
								echo "<textarea class='materialize-textarea' placeholder='Enter Subjects Separated by New Line' id='subjects' name='subjects'>".$dropdownArray['subjects']."</textarea>";
							}else{
								echo "<textarea class='materialize-textarea' placeholder='Enter Subjects Separated by New Line' id='subjects' name='subjects'></textarea>";
							}
							echo "<label for='subjects' class='active'>Subjects</label>";
						echo "</div>";
						echo "<div class='input-field col l6 s12'>";
							if(!$dropdownArray['educationLevel'] == "" && isset($dropdownArray['educationLevel'])){
								echo "<textarea class='materialize-textarea' placeholder='Enter Levels of Education Separated by New Line' id='educationLevel' name='educationLevel' type='text'>".$dropdownArray['educationLevel']."</textarea>";
							}else{
								echo "<textarea class='materialize-textarea' placeholder='Enter Levels of Education Separated by New Line' id='educationLevel' name='educationLevel' type='text'></textarea>";
							}
							echo "<label for='educationLevel' class='active'>Level of Education</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col l6 s12'>";
							if(!$dropdownArray['supportTicket'] == "" && isset($dropdownArray['supportTicket'])){
								echo "<input type='text' class='materialize-input' placeholder='Enter Support Ticket Email Address' id='suportTicketEmail' name='supportTicket' value ='".$dropdownArray['supportTicket']."'>";
							}else{
								echo "<input type='text' class='materialize-input' placeholder='Enter Support Ticket Email Address' id='suportTicketEmail' name='supportTicket'>";
							}
							echo "<label for='supportTicket' class='active'>Support Ticket Email</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='col s12'>";
							echo "<button type='submit' class='modal-action waves-effect btn-flat white-text' id='saveSettings' style='background-color: ".getSiteColor()."'>Save Changes</button>";
						echo "</div>";
					echo "</div>";
				echo "</form>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		echo "</div>";

?>

<script>

		$(function() {

			var form = $('#directory-settings-form');
			$(form).submit(function(event) {
				event.preventDefault();
				var formData = new FormData($(this)[0]);
				$.ajax({
				    type: 'POST',
				    url: $(form).attr('action'),
				    data: formData,
				    contentType: false,
					processData: false
				})
				//Show the notification
				.done(function(response) {
					$( "#updateHolder" ).load( "modules/directory/settings.php", function() {
						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: response };
						notification.MaterialSnackbar.showSnackbar(data);
					});
				})
			});

		});
</script>
<?php
	}
?>