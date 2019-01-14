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

	$siteColor = getSiteColor();

	//Check for installation
	if(admin()){ require('installer.php'); }

	$pageview = 1;
	$drawerhidden = 1;
	$pageorder = 6;
	$pagetitle = "Profile";
	$pageicon = "account_circle";
	$pagepath = "profile";
	$pagerestrictions = "";

?>

	<!--Profile modal-->
	<div id='viewprofile_arrow' class='hide-on-small-only'></div>
	<div id="viewprofile" class="modal apps_modal modal-mobile-full">
		<div class="modal-content">
			<a class="modal-close black-text hide-on-med-and-up" style='position:absolute; right:20px; top:25px;'><i class='material-icons'>clear</i></a>
			<?php
				echo "<div class='row' style='margin-bottom:0;'>";
					echo "<p style='text-align:center; font-weight:600; margin-bottom:0;' class='truncate'>".$_SESSION['displayName']."</p>";
					echo "<p style='text-align:center;' class='truncate'>".$_SESSION['useremail']."</p>";
					echo "<p style='text-align:center; font-weight:600;' class='truncate'><img src='".$_SESSION['picture']."?sz=100' style='width:100px; height:100px;' class='circle'></p>";
					echo "<hr style='margin-bottom:20px;'>";
					echo "<p style='text-align:center;'><a class='waves-effect btn-flat white-text myprofilebutton' href='#profile' style='margin-right:5px; background-color:"; echo $siteColor; echo "'>My Profile</a>";
					echo "<a class='waves-effect btn-flat white-text' href='/core/abre_signout.php' style='background-color:"; echo $siteColor; echo "'>Sign Out</a></p>";
				echo "</div>";
			?>
    	</div>
	</div>

	<div id="headlineOverlayScreen" style="position:fixed; width:100%; top:0; bottom:0; left:0; right:0; height:100%; background:#000; opacity:.7; z-index:999; display:none;"></div>
	<div id='headlinedisplay' class='modal modal-fixed-footer modal-mobile-full'>
		<form id='headlinedisplayform' method="post" action='#'>
		<div class='modal-content' style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" id='headlinedisplaytitle' style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;"></span></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class="row" id="headlineContentDiv">
					<div class='input-field col s12'>
						<p id="headlineContentDisplay" name="headlineContentDisplay" style="font-size:16px; line-height:1.8em"></p>
					</div>
				</div>
				<div class="row" id="headlineDisplayFormDiv" style="display:none;">
					<div class='col s12'>
						<!-- insert form here  -->
						<div class='fb-render-headline'></div>
					</div>
				</div>
				<div class="row" id="headlineDisplayVideoDiv" style="display:none;">
					<div class='input-field col s12'>
						<!-- insert video here -->
						<iframe id="headlineVideoIframe" type="text/html"
    					width="100%"
    					height="500"
    					src=""
    					frameborder="0">
						</iframe>
					</div>
				</div>
				<div class='row' id="confirmationDisplayDiv" style="display:none;">
					<div class='col s12'>
							<input type="checkbox" class="filled-in" name="headline_confirmation" id="headline_confirmation" value="confirmed">
							<label for="headline_confirmation">By checking this box, I verify I have read or watched and understand the above information.</label>
					</div>
				</div>
				<input id="headline_id" name="headline_id" type="hidden">
				<input id="headline_purpose" name="headline_purpose" type="hidden">
				<input id="headline_formID" name="headline_formID" type="hidden">
			</div>
		</div>
		<div class='modal-footer'>
			<button type="submit" class='modal-action waves-effect btn-flat white-text' id='saveheadlineresponse' style='background-color: <?php echo $siteColor; ?>;'>Finish</button>
			<p id="headlineErrorMessage" style="display:none; float:right; color:red; margin:6px 0; padding-right:10px;"></p>
		</div>
		</form>
	</div>

<?php
	//get headlines that have not been completed by the user.
	$today = date("Y-m-d");
	$usertype = $_SESSION['usertype'];
	$email = $_SESSION['escapedemail'];
	$requiredHeadlines = array();
	$sql = "SELECT id, title, content, form_id, video_id, purpose, required FROM headlines WHERE (start_date <= '$today' AND end_date >= '$today' OR date_restriction = '0') AND groups LIKE '%$usertype%' AND siteID = '".$_SESSION['siteID']."'";
	$resultArray = databasequery($sql);
	$resultSize = sizeof($resultArray);
	if($resultSize > 0){
		foreach($resultArray as $startup){
			$id = $startup['id'];
			$sql2 = "SELECT response_id FROM headline_responses WHERE email = '$email' AND headline_id = '$id' AND siteID ='".$_SESSION['siteID']."'";
			$responseArray = databasequery($sql2);
			$responseSize = sizeof($responseArray);
			if($responseSize == 0){
				if($startup['purpose'] == "form"){
					$formID = $startup['form_id'];
					if(isAppActive("Abre-Forms")){
						$sql = "SELECT FormFields FROM forms WHERE ID = '$formID' AND siteID ='".$_SESSION['siteID']."'";
						$row = $db->query($sql);
						$result = $row->fetch_assoc();
						$formfields = $result['FormFields'];
						$startup['FormFields'] = $formfields;
					}
				}
				array_push($requiredHeadlines, $startup);
			}
		}
	}

	$requiredHeadlinesJSON = json_encode($requiredHeadlines);

	if(isAppActive("Abre-Forms")){
?>
<script src="/modules/Abre-Forms/js/form-render.min.js"></script>
<?php } ?>
<script>

	$(function(){

		function getNextHeadline(array, size, start){
			if(array.length > 0){
				$("#headlineOverlayScreen").show();
				switch(array[0]['purpose']){
					case "text":
						$("#headlineDisplayFormDiv").hide();
						$("#headlineDisplayVideoDiv").hide();
						$('.fb-render-headline').empty();
						$("#headlineVideoIframe").attr("src", "");
						if(size == 1){
							$("#saveheadlineresponse").text('Finish');
						}else{
							$("#saveheadlineresponse").text('Proceed');
						}
						if(array[0]['required'] == "1"){
							$("#confirmationDisplayDiv").show();
							$("#headline_confirmation").prop('checked', false);
							$("#headline_confirmation").prop('required', true);
						}else{
							$("#confirmationDisplayDiv").hide();
							$("#headline_confirmation").prop('checked', false);
							$("#headline_confirmation").prop('required', false);
						}
						$("#headlineErrorMessage").hide();
						break;
					case "form":
						$("#headlineDisplayFormDiv").show();
						$("#headlineDisplayVideoDiv").hide();
						$("#headlineVideoIframe").attr("src", "");
						$("#confirmationDisplayDiv").hide();
						$("#headline_confirmation").prop('required', false);
						if(size == 1){
							$("#saveheadlineresponse").text('Submit Form and Finish');
						}else{
							$("#saveheadlineresponse").text('Submit Form and Proceed');
						}
						//Disable Auto-Complete
						$("form").attr('autocomplete', 'off');
						//Render Form
						$('.fb-render-headline').formRender({ dataType: 'json', formData: array[0]['FormFields'], notify: { success: function(message){
							mdlregister();
							$('input:checkbox').removeAttr('checked');
							$('select').material_select();
						}} });
						$("#headlineErrorMessage").hide();
						break;
					case "video":
						$("#headlineDisplayFormDiv").hide();
						$("#headlineDisplayVideoDiv").show();
						$('.fb-render-headline').empty();
						$("#headlineVideoIframe").attr("src", "https://www.youtube.com/embed/"+array[0]['video_id']);
						if(size == 1){
							$("#saveheadlineresponse").text('Finish');
						}else{
							$("#saveheadlineresponse").text('Proceed');
						}

						if(array[0]['required'] == "1"){
							$("#confirmationDisplayDiv").show();
							$("#headline_confirmation").prop('checked', false);
							$("#headline_confirmation").prop('required', true);
						}else{
							$("#confirmationDisplayDiv").hide();
							$("#headline_confirmation").prop('checked', false);
							$("#headline_confirmation").prop('required', false);
						}
						$("#headlineErrorMessage").hide();
				}
				if(start == 1){
					$("#headlinedisplay").openModal({
						in_duration: 0,
						out_duration: 0,
						opacity: 0,
						dismissable: false,
						ready: function(){
							$("#headlinedisplaytitle").text(array[0]['title']);
							$("#headlineContentDisplay").html(array[0]['content']);
							$('input[name=headline_id]').val(array[0]['id']);
							$('input[name=headline_purpose]').val(array[0]['purpose']);
							$('input[name=headline_formID]').val(array[0]['form_id']);
						}
					});
				}else{
					$("#headlinedisplaytitle").text(array[0]['title']);
					$("#headlineContentDisplay").html(array[0]['content']);
					$('input[name=headline_id]').val(array[0]['id']);
					$('input[name=headline_purpose]').val(array[0]['purpose']);
					$('input[name=headline_formID]').val(array[0]['form_id']);
					$('.modal-content').scrollTop(0);
				}
			}else{
				$("#headlinedisplay").closeModal({
					in_duration: 0,
					out_duration: 0,
				});
				$("#headlineOverlayScreen").hide();
			}
		}

		var headlineArray = <?php echo $requiredHeadlinesJSON ?>;
		var headlineArraySize = headlineArray.length;

		getNextHeadline(headlineArray, headlineArraySize, 1);

		$("#headlinedisplayform").submit(function(event){
			event.preventDefault();

			var output = {};
			var formArray = $("#headlinedisplayform :input").serializeArray();
			$.each(formArray, function () {
				if (output[this.name] !== undefined) {
					if (!output[this.name].push) {
						output[this.name] = [output[this.name]];
					}
					output[this.name].push(this.value || '');
				} else {
					output[this.name] = this.value || '';
				}
			});

			if($("#headline_purpose").val() == "form"){
				var formData = new FormData(this);
				formData.append('json', JSON.stringify(output));

				$.ajax({
					url: '/modules/Abre-Forms/action_saveresponse.php',
					type:"POST",
					enctype: 'multipart/form-data',
					processData:false,
					contentType: false,
					data: formData,
				})
				.done(function (response){

				});
			}

			$.ajax({
				type: 'POST',
				url: 'modules/profile/save_headline_reponse.php',
				data: { json: JSON.stringify(output) }
			})
			.done(function(response){
				if(response.status == "Success"){
					headlineArray.shift();
					headlineArraySize = headlineArray.length;
					getNextHeadline(headlineArray, headlineArraySize, 0);
				}else if(response.status == "Error"){
					$("#headlineErrorMessage").html(response.message);
					$("#headlineErrorMessage").show();
				}
			});
		});

    $('.modal-viewprofile').leanModal({
			in_duration: 0,
			out_duration: 0,
			opacity: 0,
    	ready: function() {
	    	$("#viewprofile_arrow").show();
	    	$("#viewprofile").scrollTop(0);
	    	$('#viewapps').closeModal({
		    	in_duration: 0,
					out_duration: 0,
		   	});
	    	$("#viewapps_arrow").hide();
	    },
    	complete: function() { $("#viewprofile_arrow").hide(); }
   	});

	  	//Make the Profile Icon Clickable/Closeable
		$(".myprofilebutton").unbind().click(function(){
			//Close the app modal
			$("#viewprofile_arrow").hide();
			$('#viewprofile').closeModal({
				in_duration: 0,
				out_duration: 0,
		  });
		});

	});

</script>