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
	$portal_root = getConfigPortalRoot();
	$siteColor = getSiteColor();

	//Settings
	if(admin()){

			echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
				echo "<div class='page'>";

					//Page Title
					echo "<div class='row'>";
						echo "<div class='input-field col s12' style='margin-top:0;'>";
							echo "<h4>Authentication</h4>";
							echo "<p>Control single sign-on (SSO) services for staff, students, and parents.</p>";
						echo "</div>";
					echo "</div>";

					echo "<div class='row' id='googleAuthService'>";
						echo "<div class='col l1 m2 s2 center-align'>";
								echo "<img class='circle' src='../../core/images/integrations/google.png' style='width:40px;height:40px;background-color:white;color:white;'>";
						echo "</div>";
						echo "<div class='col l9 m10 s10'>";
								echo "<h5 style='padding-top:0px; margin:0px;'>Google Apps Authentication</h5><p>Let your district sign in with a Google account.</p>";
						echo "</div>";
						echo "<div class='col l2 m12 s12'>";
								echo "<a id='exportkeys' href='#googleAuthModal' class='modal-action waves-effect btn-flat white-text googleAuthModal' style='background-color: ".$siteColor."'>Configure</a>";
						echo "</div>";
					echo "</div>";

					echo "<div class='row' id='microsoftAuthService'>";
						echo "<div class='col l1 m2 s2 center-align'>";
								echo "<img class='circle' src='../../core/images/integrations/microsoft.png' style='width:40px;height:40px;background-color:white;color:white;'>";
						echo "</div>";
						echo "<div class='col l9 m10 s10'>";
								echo "<h5 style='padding-top:0px; margin:0px;'>Microsoft Authentication</h5><p>Let your district sign in with a Microsoft account.</p>";
						echo "</div>";
						echo "<div class='col l2 m12 s12'>";
								echo "<a id='exportkeys' href='#microsoftAuthModal' class='modal-action waves-effect btn-flat white-text microsoftAuthModal' style='background-color: ".$siteColor."'>Configure</a>";
						echo "</div>";
					echo "</div>";

					echo "<div class='row' id='facebookAuthService'>";
						echo "<div class='col l1 m2 s2 center-align'>";
								echo "<img class='circle' src='../../core/images/integrations/facebook.png' style='width:40px;height:40px;background-color:white;color:white;'>";
						echo "</div>";
						echo "<div class='col l9 m10 s10'>";
								echo "<h5 style='padding-top:0px; margin:0px;'>Facebook Authentication</h5><p>Let your parents sign in with a Facebook account.</p>";
						echo "</div>";
						echo "<div class='col l2 m12 s12'>";
								echo "<a id='exportkeys' href='#facebookAuthModal' class='modal-action waves-effect btn-flat white-text facebookAuthModal' style='background-color: ".$siteColor."'>Configure</a>";
						echo "</div>";
					echo "</div>";
					if($db->query("SELECT * FROM Abre_Students LIMIT 1") && isAppActive("settings") && admin()){
						echo "<div class='row'>";
							echo "<div class='input-field col l12 s12' style='margin-top:0;'>";
								echo "<h4>Student Key Management</h4>";
							echo "</div>";
							echo "<div class='input-field col l12 s12'>";
								echo "<a id='generateallkeys' class='modal-action waves-effect btn-flat white-text' style='background-color: ".$siteColor."'>Generate Keys for All Students</a>";
							echo "</div>";
							echo "<div class='input-field col l12 s12'>";
								echo "<a id='exportkeys' href='$portal_root/modules/settings/exportkeysfile.php'class='modal-action waves-effect btn-flat white-text' style='background-color: ".$siteColor."'>Download All Keys</a>";
							echo "</div>";
						echo "</div>";
					}
				echo "</div>";
			echo "</div>";
	}

?>

<script>

	$(function(){

		//Open Google Oauth Modal
		$(".googleAuthModal").unbind().click(function(event) {
			event.preventDefault();

			$('#googleAuthModal').openModal({
				in_duration: 0,
				out_duration: 0,
				ready: function() {}
			});
		});

		//Open Microsoft Oauth Modal
		$(".microsoftAuthModal").unbind().click(function(event) {
			event.preventDefault();

			$('#microsoftAuthModal').openModal({
				in_duration: 0,
				out_duration: 0,
				ready: function() {}
			});
		});

		//Open Facebook Oauth Modal
		$(".facebookAuthModal").unbind().click(function(event) {
			event.preventDefault();

			$('#facebookAuthModal').openModal({
				in_duration: 0,
				out_duration: 0,
				ready: function() {}
			});
		});

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

		//Generate Keys for Parents
		$("#generateallkeys").unbind().click(function(event){
			event.preventDefault();
			var result = confirm('Generating new keys for all student will invalidate all existing keys. Generating a new key for an individual student can be done via the profile tab in the Students App. Are you sure you want to proceed?');
			if(result){
				$("#generateallkeys").html("Generating Keys...");
				$.ajax({ type: 'POST', url: '/modules/settings/generate_all_keys.php'})
				.done(function(){
					location.reload();
				})
			}
		});

	});

</script>