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

  //Include required files
  require_once('abre_verification.php');
  require_once('abre_functions.php');
  $siteColor = getSiteColor();

	//Display send email for staff
	if($_SESSION['usertype'] == "staff"){
?>
		<!--Send Email-->
		<div id='send-email' class='modal modal-fixed-footer modal-mobile-full'>
			<form class='col s12' id='form-send-email' method='post' action='core/abre_send_email_submit.php'>
				<div class='modal-content' style="padding: 0px !important;">
          <div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
            <div class='col s11'><span class="truncate" id="email-title" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;"></span></div>
            <div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
          </div>
          <div style='padding: 0px 24px 0px 24px;'>
  					<div class='row'>
  						<div class='input-field col s12'>
                <p class='black-text' id="email-helptext" style="font-weight: 500;"></p>
  							<textarea id='email-body' name='email-body' class='email_wysiwyg'></textarea>
                <input type="hidden" id="email-to" name="email-to" value="">
                <input type="hidden" id="email-subject" name="email-subject" value="">
  					  </div>
  					</div>
          </div>
			  </div>
			  <div class='modal-footer'>
					<button type='submit' class='modal-action waves-effect btn-flat white-text' style='background-color: <?php echo $siteColor; ?>'>Submit</button>
				</div>
			</form>
		</div>

<?php
	}
?>

<script>

	$(function(){

    //Start TinyMCE
    tinymce.remove('.email_wysiwyg');
		tinymce.init({
			selector: '.email_wysiwyg', branding: false, height:200, menubar:false, resize: false, statusbar: false, autoresize_min_height: 200, autoresize_max_height: 400,
			content_css : "/core/css/tinymce.0.0.11.css",
			oninit : "setPlainText",
			plugins: 'paste autolink image link lists imagetools autoresize',
			toolbar: 'bold italic underline link | numlist bullist | image | removeformat',
			image_advtab: true,
			file_picker_callback: filePickerCallback,
			file_picker_types: "image",
			images_upload_url: '/core/abre_tiny_upload.php',
			automatic_uploads: true,
			default_link_target: "_blank"
		});

		//Send Email Form
		$('#form-send-email').submit(function(event){

			event.preventDefault();

      //Clear TinyMCE Content
      tinyMCE.activeEditor.setContent('');

      //Close Modal
		  $('#send-email').closeModal({
				in_duration: 0,
				out_duration: 0,
		  });

      //Process Form Data
      var data = new FormData($(this)[0]);
			$.ajax({ type: 'POST', url: $(this).attr('action'), data: data, contentType: false, processData: false })
			.done(function(response){
				var notification = document.querySelector('.mdl-js-snackbar');
				var data = { message: response };
				notification.MaterialSnackbar.showSnackbar(data);
			})

		});

	});

</script>
