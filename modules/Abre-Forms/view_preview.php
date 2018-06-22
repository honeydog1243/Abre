<?php

	/*
	* Copyright (C) 2016-2017 Abre.io LLC
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
	require(dirname(__FILE__) . '/../../configuration.php');
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require_once(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once('functions.php');

	//Get Variables on Page
	if(isset($_GET["id"])){ $id=htmlspecialchars($_GET["id"], ENT_QUOTES); }else{ $id=""; }
	$useremail = $_SESSION['useremail'];

	//Get form restrictions
	$restrictions = getFormsSettingsLimitUsers($id);
	if($restrictions != ""){
		if (strpos($restrictions, $_SESSION['usertype']) === false){ $pagerestrictions="1"; }
	}

	if(($_SESSION['usertype'] == "staff" || admin() || isFormsAdministrator()) && hasEditAccess($id))
	{

		echo "<div class='page_container page_container_limit mdl-color--white mdl-shadow--2dp'>";
		echo "<div class='page'>";

			//Display the form
			echo "<div id='formpreview'>";

				$sql = "SELECT Name, FormFields, Settings, Owner FROM forms WHERE ID = '$id' LIMIT 1";
				$result = $db->query($sql);
				while($row = $result->fetch_assoc())
				{
					$Name = htmlspecialchars($row["Name"], ENT_QUOTES);
					$FormFields = $row["FormFields"];
					$FormSettings = $row["Settings"];
					$formOwner = $row['Owner'];
					if($FormFields == ''){
						$FormFields = "[]";
					}
				}

				//Settings Values
				$FormSettingsReturn = json_decode($FormSettings, true);
				$FormLimit = $FormSettingsReturn['limit'];
				$FormConfirmation = $FormSettingsReturn['confirmation'];
				$formNotifications = $FormSettingsReturn['emailNotifications'];
				$userresponse = 0;
				if($FormLimit == 'checked'){

					//See if user already filled out form
					$sql = "SELECT count(*) FROM forms_responses WHERE Submitter = '$useremail' AND FormID = '$id'";
					$result = $db->query($sql);
					$row = $result->fetch_assoc();
					$userresponse = $row["count(*)"];
				}

				//Form Title
				echo "<h3 class='center-align' style='font-weight:700'>$Name</h3>";

				//Display the rendered form if allowed
				if($userresponse == 0){

					echo "<form id='my-form'>";

						echo "<div class='fb-render'></div>";

						echo "<input type='hidden' name='formid' value='$id'>";
						echo "<input type='hidden' name='formsubmitter' value='$useremail'>";
						echo "<input type='hidden' name='formowner' value='$formOwner'>";
						echo "<input type='hidden' name='formEmailNotifications' value='$formNotifications'>";
						echo "<input type='hidden' name='formName' value='$Name'>";

						if($FormFields != '[]'){
							echo "<br><br><button class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored' style='background-color: ".getSiteColor()."' type='submit'>Submit</button>";
						}else{

							echo "<div class='row center-align'>";
								echo "<div style='padding:30px; width:100%;'><p style='font-size: 22px; font-weight:700; text-align:center;'>This form is empty</p></div>";
								echo "<a class='mdl-button mdl-js-button mdl-js-ripple-effect' style='background-color:".getSiteColor()."; color:#fff;' href='#forms/builder/$id'>Build the Form</a>";
							echo "</div>";
						}

					echo "</form>";

				}else{
					echo "<div class='row center-align'>";
						echo "<div style='padding:30px; text-align:center; width:100%;'><p style='font-size:16px;'>You have already taken this form.</p></div>";
					echo "</div>";
				}

			echo "</div>";

			//Form Confirmation Message
			echo "<div id='formmessage' style='display:none;'>";

				echo "<h3 class='center-align' style='font-weight:700'>$Name</h3>";

				echo "<div class='row center-align'>";
					echo "<div style='padding:30px; text-align:center; width:100%;'><p style='font-size:16px;'>";
						if($FormConfirmation == ''){
							echo "Your response has been recorded.";
						}else{
							echo $FormConfirmation;
						}
					echo "</p></div>";

					if($FormLimit != 'checked'){
						echo "<a class='mdl-button mdl-js-button mdl-js-ripple-effect' style='background-color:".getSiteColor()."; color:#fff;' href='#' id='submitanotherentry'>Submit Another Entry</a>";
					}

				echo "</div>";

			echo "</div>";

		echo "</div>";
		echo "</div>";

	}else{

		echo "<div class='row' style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>You Do Not Have Edit Access</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Please request access from the form owner.</p></div>";

	}

?>

<script src="/modules/Abre-Forms/js/form-render.min.js"></script>

<script>

	$(function()
	{

		//Disable Auto-Complete
		$("form").attr('autocomplete', 'off');

		//Render Form
		$('.fb-render').formRender({ dataType: 'json', formData: <?php echo $FormFields ?>, notify: { success: function(message){
			mdlregister();
			$('input:checkbox').removeAttr('checked');
			$('select').material_select();
		}} });

		//Submit Form
		$('#my-form').on('submit', function(event){

			event.preventDefault();

			var output = {};
			var formArray = $("#my-form :input").serializeArray();
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

			var formData = new FormData(this);
			formData.append('json', JSON.stringify(output));

			$.ajax({
				url: '/modules/Abre-Forms/action_saveresponse.php',
				type:"POST",
				enctype: 'multipart/form-data',
				processData:false,
				contentType: false,
				data: formData
			})
			.done(function (response) {

				//Update Page
				$("#formpreview").hide();
				$("#formmessage").show();

				//Confirmation Message
				var notification = document.querySelector('.mdl-js-snackbar');
				var data = { message: "Form Submitted" };
				notification.MaterialSnackbar.showSnackbar(data);

				var id = <?php echo $id ?>;
				$("#responseCount").load("modules/<?php echo basename(__DIR__); ?>/response_count.php", {formid: id}, function(){ });
			})
		});

		//Submit another entry
		$("#submitanotherentry").unbind().click(function(event){

			event.preventDefault();
			$("#my-form").trigger("reset");
			$("#formpreview").show();
			$("#formmessage").hide();

		});


	});

</script>