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

	if(admin()){

		echo "<table class='bordered' id='appsort'>";
			$query = "SELECT id, title, link, icon, staff, student, parent, staff_building_restrictions, student_building_restrictions FROM apps WHERE siteID = '".$_SESSION['siteID']."' ORDER BY sort";
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value){
				$id = $value['id'];
				$title = $value['title'];
				$titleencoded = base64_encode($title);
				$link = $value['link'];
				$linkencoded = base64_encode($link);
				$icon = $value['icon'];
				$staff = $value['staff'];
				$student = $value['student'];
				$parent = $value['parent'];
				$permissions = array();
				if($staff){
					array_push($permissions, 'Staff');
				}
				if($student == 1){
					array_push($permissions, 'Students');
				}
				if($parent == 1){
					array_push($permissions, 'Parents');
				}
				$permissionsList = implode(", ", $permissions);
				$staffRestrictions = $value['staff_building_restrictions'];
				$studentRestrictions = $value['student_building_restrictions'];
				echo "<tr id='item-$id' style='background-color:#f9f9f9'>";
					echo "<td style='width:60px'><img src='$portal_root/core/images/apps/$icon' style='width:35px; height:35px;'></td>";
					if($permissionsList == ""){
						echo "<td><b>$title</b><td>";
					}else{
						echo "<td><b>$title</b> (".$permissionsList.")<td>";
					}
					echo "<td style='width:30px'><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 passappdata' data-apptitle='$titleencoded' data-applink='$linkencoded' data-appicon='$icon' data-appid='$id' data-appstaff='$staff' data-appstudents='$student' data-appparents='$parent' data-staffrestrictions='$staffRestrictions' data-studentRestrictions='$studentRestrictions'><i class='material-icons'>mode_edit</i></button></td>";
					echo "<td style='width:30px'><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 deleteapp' data-appid='$id'><i class='material-icons'>delete</i></button></td>";
					echo "<td style='width:30px'><div class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 handle'><i class='material-icons'>reorder</i></div></td>";
				echo "</tr>";
			}
		echo "</table>";
	}
?>

<script>

		$(function(){

		   	<?php
			if(admin()){
			?>
				//Save Default App Order
				var fixHelper = function(e, ui) {
					ui.children().each(function() {
						$(this).width($(this).width());
					});
					return ui;
				};

			  $( "#appsort tbody" ).sortable({
					axis: 'y',
					handle: '.handle',
					helper: fixHelper,
					update: function(event, ui){
						//Sent Form Data
						var data = $(this).sortable('serialize');
						$.ajax({
							data: data,
			        type: 'POST',
			        url: '/modules/<?php echo basename(__DIR__); ?>/apps_save_default_order.php'
						})
					  .done(function(){
							$('#loadapps').load('modules/<?php echo basename(__DIR__); ?>/apps.php');
							if(typeof loadOtherCardsApps == 'function'){
								loadOtherCardsApps();
							}
						});
					}
				});

				//Delete assessment
				$(".deleteapp").unbind().click(function() {
					event.preventDefault();
					var result = confirm("Are you sure you want to delete this app?");
					if(result){

						$(this).closest("tr").hide();
						var appid = $(this).data('appid');

						//Make the post request
						$.ajax({
							type: 'POST',
							url: 'modules/apps/app_delete.php?id='+appid,
							data: '',
						})
						.done(function(){
							$('#loadapps').load('modules/apps/apps.php');
						});
					}
				});

				//Get App Data
				$(".passappdata").unbind().click(function() {

					$("#viewloadaddeditapploader").show();
					$("#loadaddeditapp").hide();

					var apptitle = atob($(this).data('apptitle'));
					$("#editmodaltitle").text(apptitle);
					var applink = atob($(this).data('applink'));
					var appicon = $(this).data('appicon');
					var appid = $(this).data('appid');
					var appstaff = $(this).data('appstaff');
					var appstudents = $(this).data('appstudents');
					var appparents = $(this).data('appparents');
					var staffRestrictions = $(this).data('staffrestrictions');
					var studentRestrictions = $(this).data('studentrestrictions');

					$('#addeditapp').openModal({
						in_duration: 0,
						out_duration: 0,
						ready: function() {

							$('select').material_select();
							$('.modal-content').scrollTop(0);

							$('#loadaddeditapp').load('modules/apps/app_addapp_content.php', function(){

								$("#viewloadaddeditapploader").hide();
								$("#loadaddeditapp").show();

								//Fill Modal with Data
								$("#app_name").val(apptitle);
								$("#app_link").val(applink);
								$('[name=app_icon]').val(appicon);
								$("select").imagepicker();
								$("#app_id").val(appid);

								if(appstaff == 1){
									$('#app_staff').prop('checked', true);
								}else{
									$('#app_staff').prop('checked', false);
								}

								if(appstudents == 1){
									$('#app_students').prop('checked', true);
								}else{
									$('#app_students').prop('checked', false);
								}

								if(appparents == 1){
									$('#app_parents').prop('checked', true);
								}else{
									$('#app_parents').prop('checked', false);
								}

								if($("#app_staff").is(':checked')){
									$("#appsStaffRestrictionsDiv").show();
								}else{
									$("#appsStaffRestrictionsDiv").hide();
								}

								if($("#app_students").is(':checked')){
									$("#appsStudentRestrictionsDiv").show();
								}else{
									$("#appsStudentRestrictionsDiv").hide();
								}

								staffRestrictions = String(staffRestrictions);
								if(staffRestrictions != ""){
									if(staffRestrictions.indexOf(',') >= 0){
										var dataarray = staffRestrictions.split(",");
										$("#appsStaffRestrictions").val(dataarray);
									}else{
										$("#appsStaffRestrictions").val(staffRestrictions);
									}
								}else{
									$("#appsStaffRestrictions").val('No Restrictions');
								}

								studentRestrictions = String(studentRestrictions);
								if(studentRestrictions != ""){
									if(studentRestrictions.indexOf(',') >= 0){
										var dataarray = studentRestrictions.split(",");
										for(var i = 0; i < dataarray.length; i++){
											dataarray[i] = dataarray[i].trim();
										}
										$("#appsStudentRestrictions").val(dataarray);
									}else{
										$("#appsStudentRestrictions").val(studentRestrictions);
									}
								}else{
									$("#appsStudentRestrictions").val('No Restrictions');
								}
							});
						},

					});
				});

			<?php
			}
			?>
		});

</script>
