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
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require(dirname(__FILE__) . '/../../core/abre_version.php');

	//Settings
	if(admin()){

		echo "<form id='form-settings' method='post' enctype='multipart/form-data' action='modules/settings/updateintegrationsettings.php'>";
			echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
				echo "<div class='page'>";

					//Page Title
					echo "<div class='row'>";
						echo "<div class='input-field col s12' style='margin-top:0;'>";
							echo "<h4>Integrations</h4>";
							echo "<p>Connect Abre to third party services</p>";
						echo "</div>";
					echo "</div>";

					//Form Fields
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
							echo "<input placeholder='Enter VendorLink Host URL' value='".getSoftwareAnswersURL()."' id='softwareanswersurl' name='softwareanswersurl' type='text' autocomplete='off'>";
							echo "<label class='active' for='softwareanswersurl'>Software Answers VendorLink URL</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
							echo "<input placeholder='Enter VendorLink Identifier' value='".getSoftwareAnswersIdentifier()."' id='softwareanswersidentifier' name='softwareanswersidentifier' type='text' autocomplete='off'>";
							echo "<label class='active' for='softwareanswersidentifier'>Software Answers VendorLink Identifier</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						  echo "<input placeholder='Enter VendorLink Key' value='".getSoftwareAnswersKey()."' id='softwareanswerskey' name='softwareanswerskey' type='text' autocomplete='off'>";
							echo "<label class='active' for='softwareanswerskey'>Software Answers VendorLink Key</label>";
						echo "</div>";
					echo "</div>";

			//Save Button
			echo "<div class='row'>";
				echo "<div class='col s12'>";
					echo "<button type='submit' class='modal-action waves-effect btn-flat white-text' style='background-color: ".getSiteColor()."'>Save Changes</button>";

				echo "</div>";
			echo "</div>";

			echo "</div>";
			echo "</div>";
		echo "</form>";
	}

?>

<script>

	$(function(){

		//Save Settings
		var form = $('#form-settings');
		$(form).submit(function(event){
			event.preventDefault();
			var data = new FormData($(this)[0]);
			var url = $(form).attr('action');
			$.ajax({ type: 'POST', url: url, data: data, contentType: false, processData: false })
			//Show the notification
			.done(function(response){
				var notification = document.querySelector('.mdl-js-snackbar');
				var data = { message: response };
				notification.MaterialSnackbar.showSnackbar(data);
			})
		});

	});

</script>