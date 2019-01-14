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

	//Check to make sure no configuration file exists
	if (!file_exists('configuration.php') && getenv("USE_GOOGLE_CLOUD") != "true"){
		//Load header
		require_once('core/abre_header.php');
		require_once('core/abre_version.php');
?>

		<!--Abre Setup Screen-->
		<div class='row' style='margin-top:50px;'>
			<img src='core/images/abre/abre_logo.png' alt='Abre' style='display:block; margin-left:auto; margin-right:auto; width:200px; height:63px;'>
		</div>
		<div style='margin-top:50px;'>
			<div class='page_container page_container_limit mdl-shadow--4dp'>
				<div class='page'>
					<div class='row'>
						<div class='col s12'><h3>Welcome</h3></div>
						<div class='col s12'><p>Welcome to the 5 minute Abre installation process! You may want to browse the documentation available at <a href='https://abre.io/documentation' target='_blank' class='deep-orange-text text-darken-3'>abre.io/documentation</a>. Otherwise, just fill in the information below and you'll be on your way to using the Abre Platform. <?php echo "($abre_version)"; ?></p></div>
							<form id='install'>
								<div class='col s12 deep-orange darken-3 white-text alert' style='display:none; padding:10px; margin-bottom:20px; border-radius:3px;'></div>
								<div class='col s12'><h6>Basic Information</h6></div>
								<div class='input-field col l6 s12'>
									<input placeholder='@example.org' id='domain_name' name='domain_name' type='text' autocomplete='off' required>
									<label class='active' for='domain_name'>Primary Google Apps Domain Name</label>
								</div>
								<div class='input-field col l6 s12'>
									<input placeholder='private_html' id='abre_private_root' name='abre_private_root' autocomplete='off' type='text' required>
									<label class='active' for='abre_private_root'>Abre Private Directory</label>
								</div>
								<div class='col s12'><h6>Database Credentials</h6></div>
								<div class='input-field col l3 s12'>
									<input placeholder='Enter Database Host' id='db_host' name='db_host' type='text' autocomplete='off' required>
									<label class='active' for='db_host'>Database Host</label>
								</div>
								<div class='input-field col l3 s12'>
									<input placeholder='Enter Database Name' id='db_name' name='db_name' type='text' autocomplete='off' required>
									<label class='active' for='db_name'>Database Name</label>
								</div>
								<div class='input-field col l3 s12'>
									<input placeholder='Enter Database User' id='db_user' name='db_user' type='text' autocomplete='off' required>
									<label class='active' for='db_user'>Database User</label>
								</div>
								<div class='input-field col l3 s12'>
									<input placeholder='Enter Database Password' id='db_password' name='db_password' type='text' autocomplete='off' required>
									<label class='active' for='db_password'>Database Password</label>
								</div>
								<div class='col s12'><h6>Google Console Settings</h6></div>
								<div class='input-field col l4 s12'>
									<input placeholder='Enter Google Client ID' id='google_client_id' name='google_client_id' type='text' autocomplete='off' required>
									<label class='active' for='google_client_id'>Client ID</label>
								</div>
								<div class='input-field col l4 s12'>
									<input placeholder='Enter Google Client Secret' id='google_client_secret' name='google_client_secret' type='text' autocomplete='off' required>
									<label class='active' for='google_client_secret'>Client Secret</label>
								</div>
								<div class='input-field col l4 s12'>
									<input placeholder='Enter Google API Key' id='db_api_key' name='google_api_key' type='text' autocomplete='off' required>
									<label class='active' for='google_api_key'>API Key</label>
								</div>
								<!-- Code for subscribing to the Abre Community -->
								<div class='col l12' style='margin-top:15px;'>
									<input type='checkbox' class='formclick filled-in' id='abre_community' name='abre_community' value='checked'/>
									<label for='abre_community' style='color:#000; margin-bottom:30px;'> Join the Abre Community.  <a href='https://abre.io/' target='_blank' class='deep-orange-text text-darken-3'>Learn more</a></label>
								</div>
								<div id='community_information' class='col l12 m12'>
									<div class='input-field col s6'>
										<input placeholder='Enter a First Name' id='community_first_name' name='community_first_name' type='text' autocomplete='off'>
										<label class='active' for='community_first_name'>First Name</label>
									</div>
									<div class='input-field col s6'>
										<input placeholder='Enter a Last Name' id='community_last_name' name='community_last_name' type='text' autocomplete='off'>
										<label class='active' for='community_last_name'>Last Name</label>
									</div>
									<div class='input-field col s6'>
										<input placeholder='Enter an Email' id='community_email' name='community_email' type='text' autocomplete='off'>
										<label class='active' for='community_email'>Community Email Address</label>
									</div>
									<div class='input-field col s6'>
										<input placeholder='Enter Number of Users' id='community_users' name='community_users' type='text' autocomplete='off'>
										<label class='active' for='community_users'>Number of System Users</label>
									</div>
								</div>
								<div class='col s12'>
									<br><button class='btn waves-effect btn-flat deep-orange darken-3 white-text' type='submit'>Install Abre</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
<?php
	}
?>

<script>

	$(function(){

		if($("#abre_community").prop("checked")){
			$("#community_information").show();
		}else{
			$("#community_information").hide();
		}

		$("#abre_community").click(function(){
			if($("#abre_community").prop("checked")){
				$("#community_information").show();
			}else{
				$("#community_information").hide();
			}
		});

		$('#install').submit(function(event){
			event.preventDefault();
			$('.alert').hide();
			var data = new FormData($(this)[0]);
			$.ajax({
				type: 'POST',
				url: '/core/abre_installer_process.php',
				data: data, contentType: false,
				processData: false
			})
			.done(function(response){
				$('.alert').html(response);
				if(response === "Redirect"){
					$.post("/core/abre_ping.php", function(){ location.reload(); })
				}else{
					$('html, body').animate({scrollTop:0},0);
					$('.alert').show();
				}
			})
		});
	});

</script>