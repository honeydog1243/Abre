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
	require_once('permissions.php');
	$portal_root = getConfigPortalRoot();

	if($pageaccess == 1){

		$color = getSiteColor();
		echo "<div class='row'><div class='col s12'>";
			echo "<div class='row'>";
				echo "<div class='col s12'>";
					echo "<h5>Downloads</h5>";
					echo "<table class='highlight'><thead><tr><th>Report</th></tr></thead><tbody>";
					echo "<tr data-link='$portal_root/modules/directory/csvexportfile.php' class='reportdownload pointer'><td><strong>All Active Staff</strong></td></tr>";
					echo "<tr data-link='$portal_root/modules/directory/csvexportfile_licenseexpiring.php' class='reportdownload pointer'><td><strong>Expiring License</strong></td></tr>";
					echo "<tr data-link='$portal_root/modules/directory/csvexportfile_workcalendars.php' class='reportdownload pointer'><td><strong>Work Calendars</strong></td></tr>";
					echo "<tr data-link='$portal_root/modules/directory/directoryTemplate.csv' class='reportdownload pointer'><td><strong>Data Import Template</strong></td></tr>";
					echo "</tbody></table>";
				echo "</div>";
				if(admin()){
					echo "<div class='col s12'>";
						echo "<h5>Data Import</h5>";
						echo "<p>After downloading and filling in the data import template, choose the file and click 'Import' to add new data.</p>";
						echo "<form action='modules/directory/csvimportfile.php' method='post' enctype='multipart/form-data' name='form-upload' id='form-upload'>";
		        echo "<input name='csv_data' type='file' id='csv_data' />";
		        echo "<br><br><input type='submit' class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored' style='background-color:$color' name='Submit' value='Import' />";
		        echo "</form>";
					echo "</div>";
				}
				if(superadmin()){
					echo "<div class='col s12'>";
						echo "<h5>Purge Data</h5>";
						echo "<p>To permanently remove all directory data click 'Purge Data'. This action can not be undone.</p>";
		        echo "<button class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored purge' style='background-color:$color'>Purge Data</button>";
					echo "</div>";
	      }
		 echo "</div>";
		echo "</div>";
?>

<script>

		//Process the Form
		$(function() {

			//Make Reports clickable
			$(".reportdownload").unbind().click(function() {
				 window.open($(this).data('link'), '_blank');
			});

			//Make Purge clickable
			$(".purge").unbind().click(function() {
				event.preventDefault();
				var result = confirm("Are you sure you want to purge all data? This action can not be undone.");
				if (result) {

					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: 'Purging Data...' };
					notification.MaterialSnackbar.showSnackbar(data);

					//Make the post request
					$.ajax({
						type: 'POST',
						url: 'modules/directory/purge.php',
						data: '',
					})

					//Show the notification
					.done(function(){

						window.location.href = "#directory";

						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: 'The data has been purged' };
						notification.MaterialSnackbar.showSnackbar(data);

					})
				}

			});

			var form = $('#form-upload');
			$(form).submit(function(event) {

				event.preventDefault();
				var notification = document.querySelector('.mdl-js-snackbar');
				var data = { message: 'Uploading...' };
				notification.MaterialSnackbar.showSnackbar(data);

		    var file_data = $('#csv_data').prop('files')[0];
		    var form_data = new FormData();
		    form_data.append('file', file_data)
		    $.ajax({
					url: $(form).attr('action'),
					dataType: 'text',
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,
					type: 'post'
		    })
				.done(function(form_data) {
					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: form_data };
					notification.MaterialSnackbar.showSnackbar(data);
				})
			});

		});

</script>

<?php
	}
?>
