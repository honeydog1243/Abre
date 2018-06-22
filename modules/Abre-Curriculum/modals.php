<?php

	/*
	* Copyright (C) 2016-2018 Abre.io Inc.
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
	require_once(dirname(__FILE__) . '/../../core/abre_google_authentication.php');
?>

	<!-- Create Course -->
	<div id="curriculumcourse" class="modal modal-fixed-footer modal-mobile-full">
		<form class="col s12" id="form-addcourse" method="post" action="modules/<?php echo basename(__DIR__); ?>/course_process.php">
		<div class="modal-content" style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Course</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
        <div class="row">
          <div class="input-field col s12">
            <input id="course_title" name="course_title" placeholder="Title of the Course" autocomplete="off" type="text" required>
            <label class="active" id="course_title">Course Title</label>
          </div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<textarea class="materialize-textarea" id="course_description" name="course_description" placeholder="Description of the Course (limit 240 char)" maxlength="240"></textarea>
						<label class="active" id="course_description">Course Description</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input id="course_tags" name="course_tags" placeholder="Course Categories/Tags (Separated by Commas)" type="text">
						<label class="active" id="course_tags">Course Tags</label>
					</div>
				</div>
				<div class="row">
					<div class="col s6">
						<label class="active">Grade Level</label>
						<select name='course_grade[]' id='course_grade' class="browser-default" style='height: 100px;' required='required' multiple>
							<option value='staff'>Staff</option>
							<option value='Pre-K'>Pre-K</option>
							<option value='K'>K</option>
						  <option value='1'>1</option>
						  <option value='2'>2</option>
						  <option value='3'>3</option>
						  <option value='4'>4</option>
							<option value='5'>5</option>
						  <option value='6'>6</option>
						  <option value='7'>7</option>
						  <option value='8'>8</option>
						  <option value='9'>9</option>
						  <option value='10'>10</option>
						  <option value='11'>11</option>
						  <option value='12'>12</option>
					  </select>
					</div>
					<div class="col s6">
						<label class="active">Subject</label>
						<select name='course_subject' id='course_subject' class="browser-default" required>
							<option value=''></option>
							<option value='Arts'>Arts</option>
							<option value='English Language Arts'>English Language Arts</option>
							<option value='Health & Physical Education'>Health & Physical Education</option>
							<option value='Mathematics'>Mathematics</option>
							<option value='Professional Development'>Professional Development</option>
						  <option value='Science'>Science</option>
						  <option value='Social Studies'>Social Studies</option>
							<option value='Special Education'>Special Education</option>
						  <option value='Technology'>Technology</option>
						  <option value='Miscellaneous'>Miscellaneous</option>
					  </select>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input id="course_editors" name="course_editors" placeholder="Course Editors (Emails Separated by Commas)" autocomplete="off" type="text">
						<label class="active" for="course_editors">Course Editors</label>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<input type="checkbox" class="filled-in" id="course_hidden" name="course_hidden" value="1" />
						<label for="course_hidden">Hide Course</label>
					</div>
				</div>
				<?php if(isAppActive("Abre-Learn")){ ?>
					<div class="row">
						<div class="col s12">
							<input type="checkbox" class="filled-in" id="learn_course" name="learn_course" value="1" />
							<label for="learn_course">Make course available in the Learn app</label>
							<br><br>
							<div id='learnRestrictionsDiv'>
								<label>Make course available to</label>
								<select name="learnRestrictions[]" id="learnRestrictions" multiple>
									<option value='' disabled>Choose a role</option>
									<option value='staff'>Staff</option>
									<option value='student'>Students</option>
									<option value='parent'>Parents</option>
								</select>
								<input type="checkbox" class="filled-in" id="learn_sequential" name="learn_sequential" value="1" />
								<label for="learn_sequential">Require sequential completion</label>
							</div>
						</div>
					</div>
				<?php } ?>

				<div class='row'>
					<div class='col s12'>
						<img id='curriculum_image_holder' style='max-width: 100%; max-height:200px; display:none;' alt='Post Image' src=''>
							<div style='padding-top: 15px;'><button class='customCurriculumImage pointer modal-action waves-effect btn-flat white-text' style='background-color:<?php echo getSiteColor(); ?>; '>Click to choose image</button></div>
						<input type='hidden' name='curriculumImageExisting' value=''>
						<input type='file' name='curriculumImage' id='curriculumImage' style='display:none;'>
					</div>
				</div>

				<input type="hidden" name="course_id" id="course_id">
			</div>
  	</div>
    <div class="modal-footer">
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='margin-left:5px; background-color: <?php echo getSiteColor(); ?>'>Save</button>
			<a class="modal-close waves-effect btn-flat white-text"  style='background-color: <?php echo getSiteColor(); ?>'>Cancel</a>
		</div>
		</form>
	</div>

	<!-- Create Topic -->
	<div id="curriculumtopic" class="fullmodal modal modal-fixed-footer modal-mobile-full" style="max-width: 800px;">
		<form class="col s12" id="form-addtopic" method="post" action="modules/<?php echo basename(__DIR__); ?>/topic_process.php">
		<div class="modal-content" style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Topic</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class="row">
					<div class="input-field col s12">
						<input id="topic_title" name="topic_title" type="text" placeholder="Enter a title" autocomplete="off" required>
						<label class="active" for="topic_title">Topic Title</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input id="topic_theme" name="topic_theme" type="text" placeholder="Enter a theme" autocomplete="off" required>
						<label class="active" for="topic_theme">Topic Theme</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6">
					    <input type="date" name="topic_start_time" id="topic_start_time" class="topic_starttime">
							<label class="active" for="topic_start_time">Topic Start Time</label>
					</div>
					<div class="input-field col s6">
						<input type="number" name="topic_estimated_days" placeholder="Enter Duration of Topic" autocomplete="off" id="topic_estimated_days">
						<label class="active" for="topic_estimated_days">Estimated Days</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<div id="topicLoader" class="mdl-progress mdl-js-progress mdl-progress__indeterminate" style="width:100%"></div>
						<div id="topicFiles"></div>
					</div>
				</div>
				<input type='hidden' name="topicID" id="topicID">
				<input type="hidden" name="courseID" id="courseID">
			</div>
    </div>
	  <div class="modal-footer">
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='margin-left:5px; background-color: <?php echo getSiteColor(); ?>'>Save</button>
		  <a class="modal-close waves-effect btn-flat white-text" style='background-color: <?php echo getSiteColor(); ?>'>Close</a>
			<div style="margin-left: 20px; margin-top: 5px;">
				<a class="mdl-button mdl-js-button mdl-button--icon modal-linktopic" href='#linktotopic'><i class="material-icons">link</i></a>
				<a class="mdl-button mdl-js-button mdl-button--icon google-drive" href='#'><img class="material-icons" src='../../core/images/abre/google-drive-dark.png'></a>
				<a class="mdl-button mdl-js-button mdl-button--icon modal-standardtopic" href='#standardtotopic'><i class="material-icons">trending_up</i></a>
				<a class="mdl-button mdl-js-button mdl-button--icon modal-texttopic" href='#texttotopic'><i class="material-icons">subject</i></a>
				<a class="mdl-button mdl-js-button mdl-button--icon modal-lessontopic" data-new='1' href='#lessontotopic'><i class="material-icons">school</i></a>
			</div>
		</div>
		</form>
	</div>

	<!-- Add link to topic -->
	<div id="linktotopic" class="modal modal-fixed-footer" style="max-width: 600px;">
		<form class="col s12" id="form-addlinktotopic" method="post" action="modules/<?php echo basename(__DIR__); ?>/topic_link_process.php">
		<div class="modal-content" style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Add Link</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class="row">
					<div class="col s12">
						<label class="active">Choose a link category</label>
						<select name='topic_link_category' id='topic_link_category' class="browser-default" required>
							<option value=''></option>
							<option value='Resource'>Resource</option>
						  <option value='Assessment'>Assessment</option>
						  <option value='Lesson'>Lesson</option>
					  </select>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input id="topic_link_title" name="topic_link_title" placeholder="Enter the title of the link" autocomplete="off" type="text" required>
						<label class="active" for="topic_link_title">Link Title</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input id="topic_link_url" name="topic_link_url" placeholder="Enter or paste a link" autocomplete="off" type="url" required>
						<label class="active" for="topic_link_url">Link URL</label>
					</div>
				</div>
				<input type='hidden' name="topicID" id="topicID">
				<input type="hidden" name="courseID" id="courseID">
			</div>
    </div>
		<div class="modal-footer">
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo getSiteColor(); ?>'>Add Link</button>
		</div>
		</form>
	</div>

	<!-- Add note to topic -->
	<div id="texttotopic" class="modal modal-fixed-footer" style="max-width: 600px;">
		<form class="col s12" id="form-addtexttotopic" method="post" action="modules/<?php echo basename(__DIR__); ?>/topic_text_process.php">
		<div class="modal-content" style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Add Note</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class="row">
					<div class="col s12">
						<label class="active">Choose a category</label>
						<select name='topic_text_category' id='topic_text_category' class="browser-default" required>
							<option value=''></option>
							<option value='Resource'>Resource</option>
						  <option value='Assessment'>Assessment</option>
						  <option value='Lesson'>Lesson</option>
					  </select>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<input id="topic_text_title" name="topic_text_title" autocomplete="off" type="text" placeholder="Enter a title" required>
						<label class="active" for="topic_text_title">Note Title</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<textarea id="topic_text_content" name="topic_text_content" class="materialize-textarea" autocomplete="off" placeholder="Enter body content" required></textarea>
						<label class="active" for="topic_text_content">Note Content</label>
					</div>
				</div>
				<input type='hidden' name="topicID" id="topicID">
				<input type="hidden" name="courseID" id="courseID">
				<input type="hidden" name="resourceID" id="resourceID">
			</div>
    </div>
	  <div class="modal-footer">
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo getSiteColor(); ?>'>Add Note</button>
		</div>
		</form>
	</div>

	<!-- Add lesson to topic -->
	<div id="lessontotopic" class="fullmodal modal modal-fixed-footer modal-mobile-full" style="width: 95%">
		<form class="col s12" id="form-addlessontotopic" method="post" action="modules/<?php echo basename(__DIR__); ?>/topic_lesson_process.php">
		<div class="modal-content" id='lessonmodalcontentholder' style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Add Lesson</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class="row">
					<div class="input-field col s12">
						<input id="topic_lesson_title" name="topic_lesson_title" type="text" placeholder="Enter a title for the lesson" autocomplete="off" required>
						<label class='active' for="topic_lesson_title">Lesson Title</label>
					</div>
					<div class="col s12">
						<p class='black-text' style="font-weight: 500;">Lesson Body</p>
						<textarea class='wysiwyg' id="wysiwyg_body" name="wysiwyg_body"></textarea>
					</div>
				</div>
				<div class="row" id="advancedlessonoptions" style="display:none;">
					<div class="col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Standards</p>
						<textarea class='wysiwyg' id="wysiwyg_standards" name="wysiwyg_standards"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Resources/Materials</p>
						<textarea class='wysiwyg' id="wysiwyg_resources" name="wysiwyg_resources"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Anticipatory Set</p>
						<textarea class='wysiwyg' id="wysiwyg_anticipatory" name="wysiwyg_anticipatory"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Learning Objectives/Goals</p>
						<textarea class='wysiwyg' id="wysiwyg_objectives" name="wysiwyg_objectives"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Direct Instruction</p>
						<textarea class='wysiwyg' id="wysiwyg_directinstruction" name="wysiwyg_directinstruction"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Guided Practice</p>
						<textarea class='wysiwyg' id="wysiwyg_guidedpractice" name="wysiwyg_guidedpractice"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Independent Practice</p>
						<textarea class='wysiwyg' id="wysiwyg_independentpractice" name="wysiwyg_independentpractice"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Formative Assessment(s)</p>
						<textarea class='wysiwyg' id="wysiwyg_formativeassessment" name="wysiwyg_formativeassessment"></textarea>
					</div>
					<div class="input-field col s12">
						<br>
						<p class='black-text' style="font-weight: 500;">Closure</p>
						<textarea class='wysiwyg' id="wysiwyg_closure" name="wysiwyg_closure"></textarea>
					</div>
				</div>
				<div class='row'>
					<div class="input-field col s12 center-align">
						<a href='#' class="waves-effect btn-flat white-text" style='background-color: <?php echo getSiteColor(); ?>;' id='advancedlessonoptionsbutton'>Show Advanced Option</a>
					</div>
				</div>
			</div>
			<input type='hidden' name="topicID" id="topicID">
			<input type="hidden" name="courseID" id="courseID">
			<input type="hidden" name="resourceID" id="resourceID">
  	</div>
	  <div class="modal-footer">
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo getSiteColor(); ?>'>Save Lesson</button>
		</div>
		</form>
	</div>

	<!-- Add standard to topic -->
	<div id="standardtotopic" class="modal modal-fixed-footer modal-mobile-full" style="max-width: 600px;">
		<form class="col s12" id="form-standardtotopic" method="post" action="modules/<?php echo basename(__DIR__); ?>/topic_standard_process.php">
		<div class="modal-content" style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Add Standard</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class="row">
					<div class="col s12">
						<label class='active'>Choose a Collection</label>
						<select name='topic_standard_jurisdiction' id='topic_standard_jurisdiction' class="browser-default" required>
							<option value=''></option>
							<?php

							//Check if table exists
							$table = $db->query('SELECT title FROM `Abre_Standards_Jurisdictions` LIMIT 1');
							if($table !== FALSE)
							{

								//Query the table for available Jurisdictions
								$sqllogin = "SELECT title, type, returnId FROM `Abre_Standards_Jurisdictions` ORDER BY title";
								$resultlogin = $db->query($sqllogin);
								while($rowlogin = $resultlogin->fetch_assoc())
								{
									$standardSets_title=htmlspecialchars($rowlogin["title"], ENT_QUOTES);
									$standardSets_type=htmlspecialchars($rowlogin["type"], ENT_QUOTES);
									if($standardSets_type=="state"){ $standardSets_title = "State Standards"; }
									$standardSets_returnId=htmlspecialchars($rowlogin["returnId"], ENT_QUOTES);
									echo "<option value='$standardSets_returnId'>$standardSets_title</option>";
								}

							}

							?>
					    </select>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<label class='active'>Choose a Subject</label>
						<select name='topic_standard_subject' id='topic_standard_subject' class="browser-default" required>
							<option></option>
					   </select>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<label class='active'>Choose a Bank</label>
						<select name='topic_standard_bank' id='topic_standard_bank' class="browser-default" required>
							<option></option>
					  </select>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<label class='active'>Choose a Grade</label>
						<select name='topic_standard_grade' id='topic_standard_grade' class="browser-default" required>
							<option></option>
					  </select>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<label class='active'>Choose a Standard</label>
						<select name='topic_standard_standard' id='topic_standard_standard' class="browser-default" required>
							<option></option>
					  </select>
					</div>
				</div>
				<input type='hidden' name="topicID" id="topicID">
				<input type="hidden" name="courseID" id="courseID">
			</div>
    </div>
	  <div class="modal-footer">
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo getSiteColor(); ?>'>Add Standard</button>
		</div>
		</form>
	</div>

<script src='core/tinymce/js/tinymce/tinymce.min.js'></script>

<script>

	$(function()
	{

		//Material Select
		$('select').material_select();

		//Start TinyMCE
		tinymce.init({
			selector: '.wysiwyg', branding: false, height:300, menubar:false, resize: false, statusbar: false, autoresize_min_height: 300, autoresize_max_height: 1000,
			content_css : "/core/css/tinymce.0.0.6.css?" + new Date().getTime(),
			oninit : "setPlainText",
			plugins: 'paste print preview fullpage autolink fullscreen image link media template codesample charmap hr nonbreaking toc insertdatetime advlist lists textcolor imagetools contextmenu textpattern autoresize',
			toolbar: 'bold italic underline link | alignleft aligncenter alignright | numlist bullist | media | removeformat print fullscreen',
			image_advtab: true });

		//Provide image upload on icon click
		$(".customCurriculumImage").unbind().click(function(event){
			event.preventDefault();
			$("#curriculumImage").click();
		});

		//Submit form if image if changed
		$("#curriculumImage").change(function (){
			if (this.files && this.files[0]){
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#curriculum_image_holder').show();
					$('#curriculum_image_holder').attr('src', e.target.result);
				}
				reader.readAsDataURL(this.files[0]);
			}
		});

		$("#learn_course").change(function(){
			if($(this).is(':checked')){
				$("#learnRestrictionsDiv").show();
				$("#sequentialDiv").show();
				$('.modal-content').animate({
   				scrollTop: $("#learnRestrictionsDiv").offset().top }, {duration: 2000}
				);
			}else{
				$("#learnRestrictionsDiv").hide();
				$("#sequentialDiv").hide();
			}
		});

		//Date Picker
		$('.topic_starttime').pickadate({ container: 'body', selectMonths: true, selectYears: 15 });

		//Modals
		$('.modal-linktopic').leanModal({ in_duration: 0, out_duration: 0, ready: function() { } });
		$('.modal-standardtopic').leanModal({ in_duration: 0, out_duration: 0, ready: function() { } });

		//Learn Course Dropdown
		$("#learn_course").change(function(){
			if($(this).is(':checked')){
				$("#learnRestrictionsDiv").show();
			}else{
				$("#learnRestrictionsDiv").hide();
			}
		});

		//Advanced Lesson Options
		$("#advancedlessonoptionsbutton").off().on("click", function(event){
			event.preventDefault();
			$("#advancedlessonoptions").toggle();

			$(this).text($(this).text() == 'Hide Advanced Options' ? 'Show Advanced Options' : 'Hide Advanced Options');

		});

		//Google Drive
		$(".google-drive").off().on("click", function(event){
			event.preventDefault();
			var developerKey = "<?php echo CONSTANT('GOOGLE_API_KEY') ?>";
			var clientId = "<?php echo CONSTANT('GOOGLE_CLIENT_ID') ?>";


			var pickerApiLoaded = false;
			<?php $client->refreshToken($_SESSION['access_token']['refresh_token']); ?>
			var oauthToken = "<?php echo $client->getAccessToken()['access_token'] ?>";

			$.getScript("https://apis.google.com/js/api.js?onload=onApiLoad", function(data, textStatus, jqxhr){
				// Use the API Loader script to load google.picker
				function onApiLoad() {
					gapi.load('picker', onPickerApiLoad);
				}

				function onPickerApiLoad() {
					pickerApiLoaded = true;
					createPicker();
				}

				// Create and render a Picker object for picking user Photos.
				function createPicker() {
					<?php $client->refreshToken($_SESSION['access_token']['refresh_token']); ?>
					oauthToken = "<?php echo $client->getAccessToken()['access_token'] ?>";
					if (pickerApiLoaded && oauthToken) {
						var view = new google.picker.DocsView(google.picker.ViewId.DOCS)
											.setIncludeFolders(true)
											.setOwnedByMe(true);
						var view2 = new google.picker.DocsView(google.picker.ViewId.DOCS)
											.setIncludeFolders(true)
											.setEnableTeamDrives(true);
						view.setMode(google.picker.DocsViewMode.LIST);
						var picker = new google.picker.PickerBuilder().
								addView(view).
								addView(view2).
								enableFeature(google.picker.Feature.SUPPORT_TEAM_DRIVES).
								setOAuthToken(oauthToken).
								setDeveloperKey(developerKey).
								setCallback(pickerCallback).
								build();
						picker.setVisible(true);
					}
					var elements= document.getElementsByClassName('picker-dialog');
					for(var i=0;i<elements.length;i++)
					{
						elements[i].style.zIndex = "2000";
					}
				}

				// A simple callback implementation.
				function pickerCallback(data) {
					if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
						var doc = data[google.picker.Response.DOCUMENTS][0];
						var url = doc[google.picker.Document.URL];
						var title = doc[google.picker.Document.NAME];
						var courseId = $("#courseID").val();
						var topicId = $("#topicID").val();

						$.ajax({
							type: 'POST',
							url: "modules/<?php echo basename(__DIR__); ?>/drive_link_process.php",
							data: {topicID: topicId, courseID: courseId, drive_link_title: title, drive_link_url: url}
						})

						//Show the notification
						.done(function(response) {

							$("#content_holder").load( "modules/<?php echo basename(__DIR__); ?>/pacingguide.php?id="+response.courseId+"&topicid="+response.topicId, function(){

								//Fill in files
								$( "#topicFiles" ).load( "modules/<?php echo basename(__DIR__); ?>/topic_list_resources.php?topicid="+response.topicId, function() {
									$("#topicLoader").hide();
								});

								$(".modal-content #courseID").val(response.courseId);
								$(".modal-content #topicID").val(response.topicId);

								mdlregister();

								var notification = document.querySelector('.mdl-js-snackbar');
								var data = { message: response.message };
								notification.MaterialSnackbar.showSnackbar(data);

							});
						})
					}
				}
				onApiLoad();
			});
		});

		//Fill in Lesson Topic Data
		$("body").off().on("click", ".modal-lessontopic", function ()
		{

			//Scroll to top of div
			$("#lessonmodalcontentholder").scrollTop(0);
			var New = $(this).data('new');

			//Clear out Lesson content
			$("#wysiwyg_body").html('');
			tinymce.get("wysiwyg_body").setContent('');


			//Fill Lesson Content
			if(New!=1){

				var Resource_ID = $(this).data('resourceid');
				$(".modal-content #resourceID").val(Resource_ID);
				var Lesson_Title = $(this).data('title');
				$(".modal-content #topic_lesson_title").val(Lesson_Title);
				var Lesson_Number = $(this).data('number');
				$(".modal-content #lesson_number_text_content").val(Lesson_Number);

				var WYSIWYG_Body = $(this).data('body');
				var WYSIWYG_Body = atob(WYSIWYG_Body);
				$('.wysiwyg_body').html(WYSIWYG_Body);
				tinymce.get("wysiwyg_body").setContent(WYSIWYG_Body);

				var WYSIWYG_Standards = $(this).data('standards');
				var WYSIWYG_Standards = atob(WYSIWYG_Standards);
				$('.wysiwyg_standards').html(WYSIWYG_Standards);
				tinymce.get("wysiwyg_standards").setContent(WYSIWYG_Standards);

				var WYSIWYG_Resources = $(this).data('resources');
				var WYSIWYG_Resources = atob(WYSIWYG_Resources);
				$('.wysiwyg_resources').html(WYSIWYG_Resources);
				tinymce.get("wysiwyg_resources").setContent(WYSIWYG_Resources);

				var WYSIWYG_Anticipatory = $(this).data('anticipatory');
				var WYSIWYG_Anticipatory = atob(WYSIWYG_Anticipatory);
				$('.wysiwyg_anticipatory').html(WYSIWYG_Anticipatory);
				tinymce.get("wysiwyg_anticipatory").setContent(WYSIWYG_Anticipatory);

				var WYSIWYG_Objectives = $(this).data('objectives');
				var WYSIWYG_Objectives = atob(WYSIWYG_Objectives);
				$('.wysiwyg_objectives').html(WYSIWYG_Objectives);
				tinymce.get("wysiwyg_objectives").setContent(WYSIWYG_Objectives);

				var WYSIWYG_DirectInstruction = $(this).data('directinstruction');
				var WYSIWYG_DirectInstruction = atob(WYSIWYG_DirectInstruction);
				$('.wysiwyg_directinstruction').html(WYSIWYG_DirectInstruction);
				tinymce.get("wysiwyg_directinstruction").setContent(WYSIWYG_DirectInstruction);

				var WYSIWYG_GuidedPractice = $(this).data('guidedpractice');
				var WYSIWYG_GuidedPractice = atob(WYSIWYG_GuidedPractice);
				$('.wysiwyg_guidedpractice').html(WYSIWYG_GuidedPractice);
				tinymce.get("wysiwyg_guidedpractice").setContent(WYSIWYG_GuidedPractice);

				var WYSIWYG_IndependentPractice = $(this).data('independentpractice');
				var WYSIWYG_IndependentPractice = atob(WYSIWYG_IndependentPractice);
				$('.wysiwyg_independentpractice').html(WYSIWYG_IndependentPractice);
				tinymce.get("wysiwyg_independentpractice").setContent(WYSIWYG_IndependentPractice);

				var WYSIWYG_FormativeAssessment = $(this).data('formativeassessment');
				var WYSIWYG_FormativeAssessment = atob(WYSIWYG_FormativeAssessment);
				$('.wysiwyg_formativeassessment').html(WYSIWYG_FormativeAssessment);
				tinymce.get("wysiwyg_formativeassessment").setContent(WYSIWYG_FormativeAssessment);

				var WYSIWYG_Closure = $(this).data('closure');
				var WYSIWYG_Closure = atob(WYSIWYG_Closure);
				$('.wysiwyg_closure').html(WYSIWYG_Closure);
				tinymce.get("wysiwyg_closure").setContent(WYSIWYG_Closure);

			}
			else {

				$(".modal-content #resourceID").val('');
				$(".modal-content #topic_lesson_title").val('');
				$(".modal-content #lesson_number_text_content").val('');
				tinymce.get("wysiwyg_standards").setContent('');
				tinymce.get("wysiwyg_resources").setContent('');
				tinymce.get("wysiwyg_anticipatory").setContent('');
				tinymce.get("wysiwyg_objectives").setContent('');
				tinymce.get("wysiwyg_directinstruction").setContent('');
				tinymce.get("wysiwyg_guidedpractice").setContent('');
				tinymce.get("wysiwyg_independentpractice").setContent('');
				tinymce.get("wysiwyg_formativeassessment").setContent('');
				tinymce.get("wysiwyg_closure").setContent('');
			}

		});

		//Jurisdiction change behavior
  	$('#topic_standard_jurisdiction').change(function()
  	{
    	var jurisdiction = $(this).val();

    	$.ajax({
	    	type: 'POST',
	    	url: 'modules/<?php echo basename(__DIR__); ?>/topic_subject_fill.php?jurisdiction='+jurisdiction
    	})
			.done(function(html){
				$('#topic_standard_subject').html(html);
				$('#topic_standard_bank').html('');
				$('#topic_standard_grade').html('');
				$('#topic_standard_standard').html('');
			})
  	});

  	//Standard change behavior
  	$('#topic_standard_subject').change(function()
  	{
    	var subject = $(this).val();

    	$.ajax({
	    	type: 'POST',
	    	url: 'modules/<?php echo basename(__DIR__); ?>/topic_bank_fill.php?subject='+subject
    	})
		.done(function(html){
			$('#topic_standard_bank').html(html);
			$('#topic_standard_grade').html('');
			$('#topic_standard_standard').html('');
		})
  	});

		//Bank change behavior
  	$('#topic_standard_bank').change(function()
  	{

			var subject = $('#topic_standard_subject').val();
    	var bank = $(this).val();

    	$.ajax({
	    	type: 'POST',
	    	url: 'modules/<?php echo basename(__DIR__); ?>/topic_grade_fill.php?subject='+subject+'&bank='+bank
    	})
		.done(function(html){
			$('#topic_standard_grade').html(html);
			$('#topic_standard_standard').html('');
		})
  	});

		//Grade change behavior
  	$('#topic_standard_grade').change(function()
  	{

			var subject = $('#topic_standard_subject').val();
			var bank = $('#topic_standard_bank').val();
			var grade = $('#topic_standard_grade').val();

    	$.ajax({
	    	type: 'POST',
	    	url: 'modules/<?php echo basename(__DIR__); ?>/topic_standard_fill.php?subject='+subject+'&bank='+bank+'&grade='+grade
    	})
		.done(function(html){
			$('#topic_standard_standard').html(html);
		})
  	});

		//Save new topic
		$('#form-addtopic').submit(function(event) {
			event.preventDefault();

			var form = $('#form-addtopic');
			var formMessages = $('#form-messages');

			$('#curriculumtopic').closeModal({ in_duration: 0, out_duration: 0, });

			var formData = $(form).serialize();
			$.ajax({
				type: 'POST',
				url: $(form).attr('action'),
				data: formData
			})
			.done(function(response) {
				$("input").val('');
				$("#content_holder").load( "modules/<?php echo basename(__DIR__); ?>/pacingguide.php?id="+response.courseid+"&topicid="+response.topicid, function(){
					mdlregister();
					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: response.message };
					notification.MaterialSnackbar.showSnackbar(data);

				});
			})
		});


		//Add/Edit a Course
		$('#form-addcourse').submit(function(event){
			event.preventDefault();

			var form = $('#form-addcourse');
			var formMessages = $('#form-messages');

			$('#curriculumcourse').closeModal({
				in_duration: 0,
				out_duration: 0,
			});
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

				$("#content_holder").load( "modules/<?php echo basename(__DIR__); ?>/courses_display.php", function(){

					mdlregister();

					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: response };
					notification.MaterialSnackbar.showSnackbar(data);

				});

			})
		});


		//Add Link to Topic
		$('#form-addlinktotopic').submit(function(event){
			event.preventDefault();

			var form = $('#form-addlinktotopic');
			var formMessages = $('#form-messages');

			$("#topicLoader").show();
			$('#linktotopic').closeModal({
				in_duration: 0,
				out_duration: 0,
			});
			var formData = $(form).serialize();
			$.ajax({
				type: 'POST',
				url: $(form).attr('action'),
				data: formData
			})

			//Show the notification
			.done(function(response) {
				$("#topic_link_title").val('');
				$("#topic_link_url").val('');

				$("#content_holder").load( "modules/<?php echo basename(__DIR__); ?>/pacingguide.php?id="+response.courseid+"&topicid="+response.topicid, function(){

					//Fill in files
					$( "#topicFiles" ).load( "modules/<?php echo basename(__DIR__); ?>/topic_list_resources.php?topicid="+response.topicid, function() {
						$("#topicLoader").hide();
					});

					$(".modal-content #courseID").val(response.courseid);
					$(".modal-content #topicID").val(response.topicid);

					$("#topic_link_category option[value='']").prop('selected',true);

					mdlregister();

					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: response.message };
					notification.MaterialSnackbar.showSnackbar(data);

				});

			})
		});



		//Add Note to Topic
		$('#form-addtexttotopic').submit(function(event){
			event.preventDefault();

			var form = $('#form-addtexttotopic');
			var formMessages = $('#form-messages');

			$("#topicLoader").show();
			$('#texttotopic').closeModal({
				in_duration: 0,
				out_duration: 0,
			});
			var formData = $(form).serialize();
			$.ajax({
				type: 'POST',
				url: $(form).attr('action'),
				data: formData
			})

			//Show the notification
			.done(function(response) {
				$( "#topicFiles" ).show();
				$("#topic_text_title").val('');
				$("#topic_text_content").val('');

				$("#content_holder").load( "modules/<?php echo basename(__DIR__); ?>/pacingguide.php?id="+response.courseid+"&topicid="+response.topicid, function(){

					//Fill in files
					$( "#topicFiles" ).load( "modules/<?php echo basename(__DIR__); ?>/topic_list_resources.php?topicid="+response.topicid, function() {
						$("#topicLoader").hide();
					});

					$(".modal-content #courseID").val(response.courseid);
					$(".modal-content #topicID").val(response.topicid);

					$("#topic_link_category option[value='']").prop('selected',true);

					mdlregister();

						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: response.message };
						notification.MaterialSnackbar.showSnackbar(data);

				});

			})
		});



		//Add Lesson to Topic
		$('#form-addlessontotopic').submit(function(event){
			event.preventDefault();

			tinyMCE.triggerSave();

			var form = $('#form-addlessontotopic');
			var formMessages = $('#form-messages');

			$("#topicLoader").show();
			$('#lessontotopic').closeModal({ in_duration: 0, out_duration: 0 });

			var formData = $(form).serialize();
			$.ajax({ type: 'POST', url: $(form).attr('action'), data: formData })

			//Show the notification
			.done(function(response) {
				$( "#topicFiles" ).show();

				$("#content_holder").load( "modules/<?php echo basename(__DIR__); ?>/pacingguide.php?id="+response.courseid+"&topicid="+response.topicid, function(){

					//Fill in files
					$( "#topicFiles" ).load( "modules/<?php echo basename(__DIR__); ?>/topic_list_resources.php?topicid="+response.topicid, function() {
						$("#topicLoader").hide();
					});

					$(".modal-content #courseID").val(response.courseid);
					$(".modal-content #topicID").val(response.topicid);

					$("#topic_link_category option[value='']").prop('selected',true);

					mdlregister();

						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: response.message };
						notification.MaterialSnackbar.showSnackbar(data);

				});
			})
		});



		//Add Standard to Topic
		$('#form-standardtotopic').submit(function(event){
			event.preventDefault();

			var form = $('#form-standardtotopic');
			var formMessages = $('#form-messages');

			$("#topicLoader").show();
			$('#standardtotopic').closeModal({
				in_duration: 0,
				out_duration: 0,
			});
			var formData = $(form).serialize();
			$.ajax({
				type: 'POST',
				url: $(form).attr('action'),
				data: formData
			})

			//Show the notification
			.done(function(response) {
				$("#topic_link_title").val('');
				$( "#topicFiles" ).show();
				$("#topic_link_url").val('');

				$("#content_holder").load( "modules/<?php echo basename(__DIR__); ?>/pacingguide.php?id="+response.courseid+"&topicid="+response.topicid, function(){

					//Fill in files
					$( "#topicFiles" ).load( "modules/<?php echo basename(__DIR__); ?>/topic_list_resources.php?topicid="+response.topicid, function() {
						$("#topicLoader").hide();
					});

					$(".modal-content #courseID").val(response.courseid);
					$(".modal-content #topicID").val(response.topicid);

					$("#topic_link_category option[value='']").prop('selected',true);

					mdlregister();

						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: response.message };
						notification.MaterialSnackbar.showSnackbar(data);

				});

			})
		});

	//End Document Ready
	});


</script>
