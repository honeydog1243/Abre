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

	$siteColor = getSiteColor();
?>

	<!-- Add module modal -->
	<div id="addmodule" class="modal modal-fixed-footer modal-mobile-full">
		<form class="col s12" id="form-addmodule" method="post" action="modules/<?php echo basename(__DIR__); ?>/githubmoduleadd_process.php">
		<div class="modal-content" style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Enter a Github Repository</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class="row">
					<div class="input-field col s6">
						<input id="repoaddress" name="repoaddress" type="text" placeholder="Example: https://github.com/abreio/Abre-Books" required>
						<label for="repoaddress" class="active">Github URL</label>
					</div>
				</div>
			</div>
    </div>
	  <div class="modal-footer">
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo $siteColor; ?>'>Add</button>
		</div>
		</form>
	</div>

<script>

	$(function(){

		//Add a module
		var formaddmodule = $('#form-addmodule');
		$(formaddmodule).submit(function(event) {
			event.preventDefault();
			$('button').html("Installing...");
			var formData = $(formaddmodule).serialize();
			$.ajax({
				type: 'POST',
				url: $(formaddmodule).attr('action'),
				data: formData
			})
			//Show the notification
			.done(function(response) {
				location.reload();
			});
		});

	});

</script>