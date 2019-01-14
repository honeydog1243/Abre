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

	$siteColor = getSiteColor();

	//Settings
	if(admin()){

		echo "<form id='form-settings' method='post' enctype='multipart/form-data' action='modules/settings/updategeneralsettings.php'>";
			echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
				echo "<div class='page'>";

				if(superadmin()){
					//Update Checker
					$opts = ['http' => ['method' => 'GET','header' => ['User-Agent: PHP']]];
					$context = stream_context_create($opts);
					$content = file_get_contents("https://api.github.com/repos/abreio/Abre/releases/latest", false, $context);
					$json = json_decode($content, true);
					$currentversion = $json['name'];

					if($abre_version < $currentversion){
						$currentlink = "https://github.com/abreio/Abre/archive/".$currentversion.".zip";
						echo "<div class='row'>";
							echo "<div class='col s12'>";
								echo "<div id='updateabre' data-version='$currentlink' class='card white-text pointer' style='background-color:".$siteColor."; padding:20px;'>A new version of Abre is available. <u>Click here to update to $currentversion.</u></div>";
							echo "</div>";
						echo "</div>";
					}
				}

					//General Settings
					echo "<div class='row'>";
						echo "<div class='input-field col s12' style='margin-top:0;'>";
							echo "<h4>General Settings</h4>";
							echo "<p>Adjust Abre site settings and preferences. You can also manage colors and icons.</p>";
						echo "</div>";
					echo "</div>";

					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						    echo "<input placeholder='Enter a Site Title' value='".getSiteTitle()."' id='sitetitle' name='sitetitle' type='text' autocomplete='off'>";
							echo "<label class='active' for='sitetitle'>Site Title</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						    echo "<input placeholder='Enter a Site Description' value='".getSiteDescription()."' id='sitedescription' name='sitedescription' type='text' autocomplete='off'>";
							echo "<label class='active' for='sitedescription'>Site Description</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						    echo "<input placeholder='Enter the Site Login Text' value='".getSiteLoginText()."' id='sitelogintext' name='sitelogintext' type='text' autocomplete='off'>";
							echo "<label class='active' for='sitedescription'>Site Login Text</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						    echo "<input placeholder='Enter a Site Color' value='".$siteColor."' id='sitecolor' name='sitecolor' type='text' autocomplete='off'>";
							echo "<label class='active' for='sitecolor'>Site Color</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col s6'>";
						    echo "<input placeholder='Enter the Google Analytics ID' value='".getSiteAnalytics()."' id='siteanalytics' name='siteanalytics' type='text' autocomplete='off'>";
							echo "<label class='active' for='siteanalytics'>Google Analytics ID</label>";
						echo "</div>";
						echo "<div class='input-field col s6'>";
								echo "<input placeholder='Enter the Google Analytics View ID' value='".getSiteAnalyticsViewId()."' id='analyticsViewId' name='analyticsViewId' type='text' autocomplete='off'>";
							echo "<label class='active' for='analyticsViewId'>Google Analytics View ID</label>";
						echo "</div>";
					echo "</div>";
					echo "<div class='row'>";
						echo "<div class='input-field col s12'>";
						    echo "<input placeholder='Enter the Site Administrator Email' value='".getSiteAdminEmail()."' id='siteadminemail' name='siteadminemail' type='text' autocomplete='off'>";
							echo "<label class='active' for='siteadminemail'>Site Administrator Email</label>";
						echo "</div>";
					echo "</div>";

					//Staff and Student Domains
					echo "<div class='row'>";

						echo "<div class='col s12'>";
							echo "<h4 style='margin-top:0;'>Staff and Student Domains</h4>";
						    echo "<input type='checkbox' class='filled-in' id = 'staffandstudentdomainssame' name='staffandstudentdomainssame' value='checked' ".getStaffStudentMatch()."/>";
							echo "<label for='staffandstudentdomainssame' style = 'color:#000; margin-bottom:30px;'> Staff and Student domains use the same domain and naming convention.</label>";
						echo "</div>";
						echo "<div id='staffandstudentdomainssame_information'>";
							echo "<div class='input-field col s6'>";
							    echo "<input placeholder='Enter Student Domain' value='".getSiteStudentDomain()."' id='studentdomain' name='studentdomain' type='text' autocomplete='off'>";
								echo "<label class='active' for='studentdomain'>Student Domain</label>";
							echo "</div>";
							echo "<div class='input-field col s6'>";
							    echo "<input placeholder='Enter Required Characters' value='".getSiteStudentDomainRequired()."' id='studentdomainrequired' name='studentdomainrequired' type='text' autocomplete='off'>";
								echo "<label class='active' for='studentdomainrequired'>Student Domain Required Characters</label>";
							echo "</div>";
						echo "</div>";

					echo "</div>";

					//Site Icon
					echo "<div class='row'>";
						echo "<div class='col s12'>";
							echo "<h4 style='margin-top:0;'>Site Icon</h4>";
							echo "<p>Your site icon is used for the site login and favicon, where it’s useful in helping your users quickly identify your school. We recommend a square image with a resolution of 200px by 200px. The image should have a transparent or white background.</p>";
							$sitelogoexisting = getSiteLogo();
							if($sitelogoexisting != ""){
								echo "<img class='sitelogobutton pointer' alt='Site Logo' src='$sitelogoexisting' width='125px' height='125px' style='margin-bottom:33px;'>";
							    echo "<input type='hidden' name='sitelogoexisting' value='$sitelogoexisting'>";
							    echo "<input type='file' name='sitelogo' id='sitelogo' style='display:none;'>";
							}
							echo "<div><button class='sitelogobutton modal-action waves-effect btn-flat white-text' style='background-color: ".$siteColor."'>Update Site Icon</button></div>";
						echo "</div>";
					echo "</div>";

					//Abre Community
					echo "<div class='row'>";
						echo "<div class='col s12'>";
							echo "<h4>Abre Community</h4>";
							echo "<input type='checkbox' class='formclick filled-in' id = 'abre_community' name='abre_community' value='checked' ".getSiteAbreCommunity()."/>";
							echo "<label for='abre_community' style = 'color:#000; margin-bottom:30px;'> Join the Abre Community.<a href='https://abre.io/community/' style='color:".$siteColor.";' target='_blank'> Learn more</a></label>";
						echo "</div>";
						echo "<div id='community_information'>";
							echo "<div class='row'>";
								echo "<div class='input-field col s6'>";
									echo "<input placeholder='Enter a First Name' value='".getSiteCommunityFirstName()."' id='community_first_name' name='community_first_name' type='text' autocomplete='off'>";
									echo "<label class='active' for='community_first_name'>First Name</label>";
								echo "</div>";
								echo "<div class='input-field col s6'>";
									echo "<input placeholder='Enter a Last Name' id='community_last_name' value='".getSiteCommunityLastName()."' name='community_last_name' type='text' autocomplete='off'>";
									echo "<label class='active' for='community_last_name'>Last Name</label>";
								echo "</div>";
							echo "</div>";
							echo "<div class='row'>";
								echo "<div class='input-field col s6'>";
									echo "<input placeholder='Enter an Email' id='community_email' value='".getSiteCommunityEmail()."' name='community_email' type='text' autocomplete='off'>";
									echo "<label class='active' for='community_email'>Community Email Address</label>";
								echo "</div>";
								echo "<div class='input-field col s6'>";
									echo "<input placeholder='Enter Number of Users' id='community_users' value='".getSiteCommunityUsers()."' name='community_users' type='text' autocomplete='off'>";
									echo "<label class='active' for='community_users'>Number of System Users</label>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";



					//Save Button
					echo "<div class='row'>";
						echo "<div class='col s12'>";
							echo "<button type='submit' class='modal-action waves-effect btn-flat white-text' style='background-color: ".$siteColor."'>Save Changes</button>";
						echo "</div>";
					echo "</div>";

					//Show Current Version
					echo "<div class='row'>";
						echo "<div class='col s12'><p style='color:#999'>Version $abre_version</p></div>";
					echo "</div>";

				echo "</div>";
			echo "</div>";
		echo "</form>";
	}

?>

<script>

	$(function(){

		//Update Abre
		$("#updateabre").unbind().click(function(event){
			event.preventDefault();
			var Link = $(this).data('version');
			$(this).html("Updating...");
			$.post("modules/settings/update.php", { link: Link }, function(){ })
			.done(function() {
				$.post("/core/abre_ping.php",
				{community_first_name: $('#community_first_name').val(), community_last_name: $('#community_last_name').val(), community_email: $('#community_email').val(), community_users: $('#community_users').val()},
				function(){ location.reload(); })
	  		})
	  	});

		//Provide image upload on icon click
		$(".sitelogobutton").unbind().click(function(event){
			event.preventDefault();
			$("#sitelogo").click();
	  	});

		//Submit form if image if changed
		$("#sitelogo").change(function (){
			if (this.files && this.files[0]){
				var reader = new FileReader();
				reader.onload = function (e) {
					$('.sitelogobutton').attr('src', e.target.result);
				}
				reader.readAsDataURL(this.files[0]);
			}
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
				location.reload();
			})
		});

		//Abre Staff/Student Domains
		if($("#staffandstudentdomainssame").prop("checked")){
			$("#staffandstudentdomainssame_information").hide();
		}else{
			$("#staffandstudentdomainssame_information").show();
		}

		$("#staffandstudentdomainssame").click(function() {
			if($("#staffandstudentdomainssame").prop("checked")){
				$("#staffandstudentdomainssame_information").hide();
			}else{
				$("#staffandstudentdomainssame_information").show();
			}
		});

		//Abre Community
		if($("#abre_community").prop("checked")){
			$("#community_information").show();
		}else{
			$("#community_information").hide();
		}

		$("#abre_community").click(function() {
			if($("#abre_community").prop("checked")){
				$("#community_information").show();
			}else{
				$("#community_information").hide();
			}
		});

	});

</script>