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

	$siteColor = getSiteColor();

?>

<!--Stream Editor-->
	<?php
	if(admin()){
	?>
	<div id='googleAuthModal' class='modal modal-fixed-footer modal-mobile-full' style="width:90%">
		<form id='googleAuthOptionsForm' method="post" action='#'>
		<div class='modal-content' style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Google Authentication Options</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class='row'>
					<div class='input-field col s12 l6'>
						<input placeholder="Enter Client ID" id="googleclientid" value="<?php echo getSiteGoogleClientId() ?>" name="googleclientid" type="text" autocomplete="off">
						<label for="googleclientid" class="active">Google Client ID</label>
					</div>
					<div class='input-field col s12 l6'>
						<input placeholder="Enter Client Secret" id="googleclientsecret" value="<?php echo getSiteGoogleClientSecret() ?>" name="googleclientsecret" type="text" autocomplete="off">
						<label for="googleclientsecret" class="active">Google Client Secret</label>
					</div>
				</div>
				<div class='row'>
					<div class='col m4 s12'>
							<input type="checkbox" class="filled-in" name="google_staff" id="google_staff" value="staff" <?php echo getSiteGoogleSignInGroups('staff') ?>>
							<label for="google_staff">Staff</label>
					</div>
					<div class='col m4 s12'>
						<input type="checkbox" class="filled-in" name="google_students" id="google_students" value="students" <?php echo getSiteGoogleSignInGroups('students') ?>>
						<label for="google_students">Students</label>
					</div>
					<div class='col m4 s12'>
						<input type="checkbox" class="filled-in" name="google_parents" id="google_parents" value="parents" <?php echo getSiteGoogleSignInGroups('parents') ?>>
						<label for="google_parents">Parents</label>
					</div>
				</div>
			</div>
		</div>
		<div class='modal-footer'>
			<button type="submit" class='modal-action waves-effect btn-flat white-text' id='savegoogleauth' style='background-color: <?php echo $siteColor; ?>; font-weight:500;'>Save</button>
			<a class='modal-action modal-close waves-effect btn-flat white-text' style='background-color: <?php echo $siteColor; ?>; margin-right:5px;'>Cancel</a>
		</div>
		</form>
	</div>

  <div id='microsoftAuthModal' class='modal modal-fixed-footer modal-mobile-full' style="width:90%">
    <form id='microsoftAuthOptionsForm' method="post" action='#'>
    <div class='modal-content' style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Microsoft Authentication Options</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
	      <div class='row'>
	        <div class='input-field col s12 l6'>
	          <input placeholder="Enter Application ID" id="microsoftclientid" value="<?php echo getSiteMicrosoftClientId() ?>" name="microsoftclientid" type="text" autocomplete="off">
	          <label for="microsoftclientid" class="active">Microsoft Application ID</label>
	        </div>
	        <div class='input-field col s12 l6'>
	          <input placeholder="Enter Application Secret" id="microsoftclientsecret" value="<?php echo getSiteMicrosoftClientSecret() ?>" name="microsoftclientsecret" type="text" autocomplete="off">
	          <label for="microsoftclientsecret" class="active">Microsoft Application Secret</label>
	        </div>
	      </div>
	      <div class='row'>
	        <div class='col m4 s12'>
	            <input type="checkbox" class="filled-in" name="microsoft_staff" id="microsoft_staff" value="staff" <?php echo getSiteMicrosoftSignInGroups('staff') ?>>
	            <label for="microsoft_staff">Staff</label>
	        </div>
	        <div class='col m4 s12'>
	          <input type="checkbox" class="filled-in" name="microsoft_students" id="microsoft_students" value="students" <?php echo getSiteMicrosoftSignInGroups('students') ?>>
	          <label for="microsoft_students">Students</label>
	        </div>
	        <div class='col m4 s12'>
	          <input type="checkbox" class="filled-in" name="microsoft_parents" id="microsoft_parents" value="parents" <?php echo getSiteMicrosoftSignInGroups('parents') ?>>
	          <label for="microsoft_parents">Parents</label>
	        </div>
	      </div>
	    </div>
		</div>
    <div class='modal-footer'>
      <button type="submit" class='modal-action waves-effect btn-flat white-text' id='savemicrosoftauth' style='background-color: <?php echo $siteColor; ?>; font-weight:500;'>Save</button>
      <a class='modal-action modal-close waves-effect btn-flat white-text' style='background-color: <?php echo $siteColor; ?>; margin-right:5px;'>Cancel</a>
    </div>
    </form>
  </div>

  <div id='facebookAuthModal' class='modal modal-fixed-footer modal-mobile-full' style="width:90%">
    <form id='facebookAuthOptionsForm' method="post" action='#'>
    <div class='modal-content' style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Facebook Authentication Options</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
	      <div class='row'>
	        <div class='input-field col s12 l6'>
	          <input placeholder="Enter App ID" id="facebookclientid" value="<?php echo getSiteFacebookClientId() ?>" name="facebookclientid" type="text" autocomplete="off">
	          <label for="facebookclientid" class="active">Facebook App ID</label>
	        </div>
	        <div class='input-field col s12 l6'>
	          <input placeholder="Enter App Secret" id="facebookclientsecret" value="<?php echo getSiteFacebookClientSecret() ?>" name="facebookclientsecret" type="text" autocomplete="off">
	          <label for="facebookclientsecret" class="active">Facebook App Secret</label>
	        </div>
	      </div>
	      <div class='row'>
	        <div class='col m4 s12'>
	          <input type="checkbox" class="filled-in" name="facebook_parents" id="facebook_parents" value="parents" <?php echo getSiteFacebookSignInGroups('parents') ?>>
	          <label for="facebook_parents">Parents</label>
	        </div>
	      </div>
			</div>
    </div>
    <div class='modal-footer'>
      <button type="submit" class='modal-action waves-effect btn-flat white-text' id='savefacebookauth' style='background-color: <?php echo $siteColor; ?>; font-weight:500;'>Save</button>
      <a class='modal-action modal-close waves-effect btn-flat white-text' style='background-color: <?php echo $siteColor; ?>; margin-right:5px;'>Cancel</a>
    </div>
    </form>
  </div>
	<?php
 	}
 	?>

<script>

	$(function(){

	  $('select').material_select();

		//Save/Update Google options
		$('#googleAuthOptionsForm').submit(function(event){
			event.preventDefault();

			var clientid = $('#googleclientid').val();
      var clientsecret = $('#googleclientsecret').val();
			var groupArray = [];
			if($('input[id="google_staff"]').is(':checked')){
				groupArray.push("staff");
			}
			if($('input[id="google_students"]').is(':checked')){
				groupArray.push("students");
			}
			if($('input[id="google_parents"]').is(':checked')){
				groupArray.push("parents");
			}

			//Make the post request
			$.ajax({
				type: 'POST',
				url: 'modules/settings/updateauthenticationsettings.php',
				data: { service: 'google', clientid: clientid, clientsecret: clientsecret, groupArray: groupArray }
			})
			.done(function(response){
				$('#googleAuthModal').closeModal({ in_duration: 0, out_duration: 0 });
        mdlregister();
        var notification = document.querySelector('.mdl-js-snackbar');
        var data = { message: response };
        notification.MaterialSnackbar.showSnackbar(data);
			});
		});

    //Save/Update Microsoft options
    $('#microsoftAuthOptionsForm').submit(function(event){
      event.preventDefault();

      var clientid = $('#microsoftclientid').val();
      var clientsecret = $('#microsoftclientsecret').val();
      var groupArray = [];
      if($('input[id="microsoft_staff"]').is(':checked')){
        groupArray.push("staff");
      }
      if($('input[id="microsoft_students"]').is(':checked')){
        groupArray.push("students");
      }
      if($('input[id="microsoft_parents"]').is(':checked')){
        groupArray.push("parents");
      }

      //Make the post request
      $.ajax({
        type: 'POST',
        url: 'modules/settings/updateauthenticationsettings.php',
        data: { service: 'microsoft', clientid: clientid, clientsecret: clientsecret, groupArray: groupArray }
      })
      .done(function(response){
        $('#microsoftAuthModal').closeModal({ in_duration: 0, out_duration: 0 });
        mdlregister();
        var notification = document.querySelector('.mdl-js-snackbar');
        var data = { message: response };
        notification.MaterialSnackbar.showSnackbar(data);
      });
    });

    //Save/Update Facebook options
    $('#facebookAuthOptionsForm').submit(function(event){
      event.preventDefault();

      var clientid = $('#facebookclientid').val();
      var clientsecret = $('#facebookclientsecret').val();
      var groupArray = [];
      if($('input[id="facebook_staff"]').is(':checked')){
        groupArray.push("staff");
      }
      if($('input[id="facebook_students"]').is(':checked')){
        groupArray.push("students");
      }
      if($('input[id="facebook_parents"]').is(':checked')){
        groupArray.push("parents");
      }

      //Make the post request
      $.ajax({
        type: 'POST',
        url: 'modules/settings/updateauthenticationsettings.php',
        data: { service: 'facebook', clientid: clientid, clientsecret: clientsecret, groupArray: groupArray }
      })
      .done(function(response){
        $('#facebookAuthModal').closeModal({ in_duration: 0, out_duration: 0 });
        mdlregister();
        var notification = document.querySelector('.mdl-js-snackbar');
        var data = { message: response };
        notification.MaterialSnackbar.showSnackbar(data);
      });
    });

	});
</script>