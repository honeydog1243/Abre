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

	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	$portal_root = getConfigPortalRoot();
	$portal_path_root = getConfigPortalPathRoot();
	$siteColor = getSiteColor();

	//Check for installation
	if(admin()){ require('installer.php'); }

	$pageview = 1;
	$drawerhidden = 1;
	$pagetitle = "Apps";
	$pagepath = "apps";
	$restrictions = "parent";

	//used for routing after verifying a student.
	$url = $portal_root .'/#mystudents';

?>

	<!--Apps modal-->
	<div id='viewapps_arrow' class='hide-on-small-only'></div>
	<div id="viewapps" class="modal apps_modal modal-mobile-full">
		<div class="modal-content">
			<a class="modal-close black-text hide-on-med-and-up" style='position:absolute; right:20px; top:25px;'><i class='material-icons'>clear</i></a>
			<div id='loadapps'></div>
			<div id='viewappsloader' style='display:none;'><div class='progress'><div class='indeterminate'></div></div></div>
    </div>
	</div>

	<!--Admin Editor Options-->
	<?php
	if(admin()){
	?>

		<link rel="stylesheet" href='core/css/image-picker.0.3.0.css'>
		<script src='core/js/image-picker.0.3.0.min.js'></script>

		<!-- Apps Editor -->
		<div id='appeditor' class='modal modal-fixed-footer modal-mobile-full'>
			<div class='modal-content' style="padding: 0px !important;">
				<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
					<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">App Editor</span></div>
					<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
				</div>
				<div style='padding: 0px 24px 0px 24px;'>
					<div class='row'>
						<div class='col s12'>
							<div id='loadappeditor'></div>
							<div id='viewloadappeditorloader' style='display:none;'><div class='progress'><div class='indeterminate'></div></div></div>
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<a class='modal-action waves-effect btn-flat white-text modal-addeditapp' href='#addeditapp' data-apptitle='Add New App' style='background-color: <?php echo $siteColor; ?>'>Add</a>
			</div>
		</div>

		<!-- App Editor/Creator -->
		<div id='addeditapp' class='modal modal-fixed-footer modal-mobile-full' style="width: 90%">
			<form id='addeditappform' method="post" action='#'>
				<div class='modal-content' style="padding: 0px !important;">
					<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
						<div class='col s11'><span class="truncate" id='editmodaltitle' style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;"></span></div>
						<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
					</div>
					<div style='padding: 0px 24px 0px 24px;'>
						<div id='loadaddeditapp'></div>
						<div id='viewloadaddeditapploader' style='display:none;'><div class='progress'><div class='indeterminate'></div></div></div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="submit" class='modal-action waves-effect btn-flat white-text' id='saveupdateapp' style='background-color: <?php echo $siteColor; ?>'>Save</button>
					<a class='modal-action modal-close waves-effect btn-flat white-text' style='background-color: <?php echo $siteColor; ?>; margin-right:5px;'>Cancel</a>
				</div>
			</form>
		</div>

	<?php
	}
	if($_SESSION['usertype'] == 'parent'){
	?>
		<!-- Student Token Modal -->
		<div id="verifystudent" class="modal modal-fixed-footer modal-mobile-full">
			<form class="col s12" id="form-verifystudent" method="post" action="<?php echo basename(__DIR__); ?>/../../core/abre_verifystudent_process.php">
				<div class="modal-content" style="padding: 0px !important;">
					<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
						<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Student Access</span></div>
						<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
					</div>
					<div style='padding: 0px 24px 0px 24px;'>
						<div class="row">
							<div class="input-field col s6">
								<input id="studenttoken" name="studenttoken" type="text" maxlength="20" placeholder="Enter your student token" autocomplete="off" required>
								<label for="studenttoken" class="active">Enter Student Access Code</label>
							</div>
						</div>
						<div class="row">
							<div class="col s6">
								<div class='s12 l12' id="errormessage" style="color:#F44336"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo $siteColor; ?>'>Verify</button>
				</div>
			</form>
		</div>
	<?php } ?>

<script>

	$(function(){

		$('select').material_select();

		//Student Token Modal
		$('.modal-verifystudent').leanModal({
			in_duration: 0,
			out_duration: 0,
			ready: function() {
				$("#studenttoken").val('');
				$('#errormessage').text('');
				$("#studenttoken").focus();
			}
		});

		var formverifystudent = $('#form-verifystudent');
		$(formverifystudent).submit(function(event) {
			event.preventDefault();
			var formData = $(formverifystudent).serialize();
			$.ajax({
				type: 'POST',
				url: $(formverifystudent).attr('action'),
				data: formData
			})
			//Show the notification
			.done(function(response) {
				var notification = document.querySelector('.mdl-js-snackbar');
				var status = response.status;
				if(status == "Success"){
					<?php isVerified(); ?>
					$("#studenttoken").val('');
					$('#verifystudent').closeModal({
						in_duration: 0,
						out_duration: 0,
					});
					var url;
					<?php if(isAppActive("Abre-Students")){ ?>
						url = "<?php echo $url ?>";
					<?php }else{ ?>
						url = "<?php echo $portal_root ?>";
					<?php } ?>
					if(window.location.href == url){
						if(url == "<?php echo $portal_root ?>"){

						}else{
							location.reload();
						}
					 }else{
						window.location.replace(url);
						var data = { message: response.message };
						notification.MaterialSnackbar.showSnackbar(data);
					}
				}
				if(status == "Error"){
					$('#errormessage').text(response.message);
				}
			});
		});

		//Apps Modal
    $('.modal-viewapps').leanModal({
			in_duration: 0,
			out_duration: 0,
			opacity: 0,
	    ready: function() {
				$("#viewappsloader").show();
				$("#viewapps_arrow").show();
		    $("#viewapps").scrollTop(0);
		    $('#viewprofile').closeModal({
			    in_duration: 0,
					out_duration: 0,
			   });
		    $("#viewprofile_arrow").hide();

				//Load Apps
				$('#loadapps').load('modules/apps/apps.php', function(){
					$("#viewappsloader").hide();
				});

		  },
	    complete: function() { $("#viewapps_arrow").hide(); }
		});

	  <?php
		if(admin()){
		?>

			//Call ImagePicker
			$("select").imagepicker();

			//Add/Edit App
			$('.modal-addeditapp').leanModal({
				in_duration: 0,
				out_duration: 0,
				ready: function(){

					$("#editmodaltitle").text('Add New App');
					$("#viewloadaddeditapploader").show();
					$("#loadaddeditapp").hide();
					$('#loadaddeditapp').load('modules/apps/app_addapp_content.php', function(){

						$("#viewloadaddeditapploader").hide();
						$("#loadaddeditapp").show();

						$('.modal-content').scrollTop(0);
						$("#app_name").val('');
						$("#app_link").val('');
						$("#app_id").val('');
						$('#app_staff').prop('checked', false);
						$('#app_students').prop('checked', false);
						$('#app_parents').prop('checked', false);
						$('[name=app_icon]').val('');
						$("select").imagepicker();
						$("#appsStudentRestrictions").val('No Restrictions');
						$("#appsStaffRestrictions").val('No Restrictions');
						$("#appsStudentRestrictionsDiv").hide();
						$("#appsStaffRestrictionsDiv").hide();

					});

				}
			});

			//Save/Update App
			$('#addeditappform').submit(function(event){
				event.preventDefault();

				var appname = $('#app_name').val();
				var applink = $('#app_link').val();
				var appicon = $('#app_icon').val();
				if($('#app_staff').is(':checked')){ var appstaff = 1; }else{ var appstaff = 0; }
				if($('#app_students').is(':checked')){ var appstudents = 1; }else{ var appstudents = 0; }
				if($('#app_parents').is(':checked')){ var appparents = 1; }else{ var appparents = 0; }
				var staffRestrictions = $("#appsStaffRestrictions").val();
				var studentRestrictions = $("#appsStudentRestrictions").val();

				var appid = $('#app_id').val();
				$("select").imagepicker();

				//Make the post request
				$.ajax({
					type: 'POST',
					url: 'modules/apps/update_app.php',
					data: { name: appname, link: applink, icon: appicon, id: appid, staff: appstaff, students: appstudents, parents: appparents, staffRestrictions: staffRestrictions, studentRestrictions: studentRestrictions }
				})
				.done(function(){
					$('#addeditapp').closeModal({ in_duration: 0, out_duration: 0 });
					$('#appsort').load('modules/apps/app_editor_content.php');
					$('#loadapps').load('modules/apps/apps.php');
					if(typeof loadOtherCardsApps == 'function'){
						loadOtherCardsApps();
					}
				});
			});

		<?php
		}
		?>
	});

</script>
