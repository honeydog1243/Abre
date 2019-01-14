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
	require_once('permissions.php');

	//Show the search and last 10 updates
	if($pageaccess == 1){

		echo "<div id='archivedemployees'>"; include "archived.php"; echo "</div>";
?>

<script>

		$(function() {

			$("#myTable").tablesorter();

			//Restore the User
			$('#archivedemployees').on('click','.restoreuser',function(){
				var address = $(this).find("a").attr("href");
				$.ajax({
					type: 'POST',
					url: address,
					data: '',
				})
				//Show the notification
				.done(function(response) {
					$("#archivedemployees").load( "modules/directory/archived.php", function() {

						//Register MDL Components
						mdlregister();
						var notification = document.querySelector('.mdl-js-snackbar');
						var data = { message: response };
						notification.MaterialSnackbar.showSnackbar(data);
					});
				})
			});

			//Permanently Delete User
			$('#archivedemployees').on('click','.deleteuser',function(){
				var result = confirm("Want to permanently delete this user?");
				if(result){
					var address = $(this).find("a").attr("href");
					$.ajax({
						type: 'POST',
						url: address,
						data: '',
					})
					//Show the notification
					.done(function(response) {
						$( "#archivedemployees" ).load( "modules/directory/archived.php", function() {

							//Register MDL Components
							mdlregister();
							var notification = document.querySelector('.mdl-js-snackbar');
							var data = { message: response };
							notification.MaterialSnackbar.showSnackbar(data);
						});
					})
				}
			});
		});
</script>
<?php
	}
?>