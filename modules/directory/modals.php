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
	require_once('permissions.php');

	$siteColor = getSiteColor();

?>

	<!-- Employee Modal -->
<div id="employeeprofile" class="fullmodal modal modal-fixed-footer modal-mobile-full" style="width: 90%">
	<form class="col s12" id='form-hr' method='post' enctype='multipart/form-data' action='modules/directory/updateuser.php'>
		<div class="modal-content">
			<a class="modal-close black-text" style='position:absolute; right:20px; top:25px;'><i class='material-icons'>clear</i></a>
			<div class="row">
				<div id="employeedata"></div>
				<input type="hidden" id="searchquerysave" name="searchquerysave">
			</div>
    </div>
	  <div class="modal-footer">
			<?php if($pageaccess != 2) { ?> <a class='waves-effect btn-flat white-text' id='archiveuser' id='archiveuser' style='float:left; background-color: <?php echo $siteColor; ?>'>Archive User</a>
			<button type="submit" class="modal-action waves-effect btn-flat white-text" style='margin-left:5px; background-color: <?php echo $siteColor; ?>'>Save</button>
			<a class="modal-close waves-effect btn-flat white-text"  style='background-color: <?php echo $siteColor; ?>'>Cancel</a> <?php } ?>
		</div>
	</form>
</div>

<script>

		$(document).ready(function(){

    		//Open Modal
    		<?php
	    	if($pageaccess == 1 or $pageaccess == 2)
	    	{
		    ?>
				$(document).off().on("click", ".employeeview", function (){
					event.preventDefault();
					$("#employeedata").hide();
					var EmployeeID = $(this).data('employeeid');
					var SearchQuerySaved = $(this).data('searchquerysaved');
					$("#searchquerysave").val(SearchQuerySaved);

					$("#employeedata").load( "modules/<?php echo basename(__DIR__); ?>/profile.php?id="+EmployeeID, function(){
						$("#employeedata").show();
					});

					$('#employeeprofile').openModal({ in_duration: 0, out_duration: 0, ready: function() { } });
				});
			<?php
			}
			?>

			//Archive the User
			$(document).on("click", "#archiveuser", function (){
				event.preventDefault();
				var result = confirm("Are you sure you want to archive this user?");
				if(result){
					var userid = $('#userid').val();
				    $.ajax({
					    type: 'POST',
					    url: 'modules/directory/archiveuser.php',
					    data: { id : userid }
					})
					//Show the notification
					.done(function(response) {
						$('#employeeprofile').closeModal({ in_duration: 0, out_duration: 0, ready: function() { } });
						var SearchQuery = $("#searchquerysave").val();
						$.post("modules/<?php echo basename(__DIR__); ?>/searchresults.php", { searchquery: SearchQuery })
						.done(function( data ) {
							$('#searchresults').html(data);
						});
					})
				}
			});

			//Save Form Data
			var form = $('#form-hr');
			$(form).submit(function(event) {
				event.preventDefault();
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
					var SearchQuery = $("#searchquerysave").val();
					$.post("modules/<?php echo basename(__DIR__); ?>/searchresults.php", { searchquery: SearchQuery })
					.done(function( data ) {
						$("#content_holder").load( "modules/directory/directory.php" );
				  });

					$('#employeeprofile').closeModal({ in_duration: 0, out_duration: 0, ready: function() { } });

					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: response };
					notification.MaterialSnackbar.showSnackbar(data);
				})
			});
		});

</script>