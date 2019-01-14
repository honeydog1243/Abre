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
	require_once('permissions.php');

	$siteColor = getSiteColor();

	if(getConfigSiteMode() == "DEMO"){
		echo "<div style='padding:30px; text-align:center; width:100%;'>";
			echo "<div class='row'>";
				echo "<span style='font-size: 22px; font-weight:700'>Learn more about the Directory App!</span>";
			echo "</div>";
			echo "<div class='row'>";
				echo "<p style='font-size:16px; margin:20px 0 0 0;'>For more information about the Abre Platform visit <a href='https://www.abre.io/' style='color:".$siteColor.";' target='_blank'>our website</p>";
			echo "</div>";
		echo "</div>";
	}else{
		//Show the Search and Last 10 Modified Users
		if($pageaccess == 1 or $_SESSION['usertype'] == "staff"){

			$sql = "SELECT COUNT(*) FROM directory WHERE archived = 0 AND siteID = '".$_SESSION['siteID']."'";
			$dbreturn = $db->query($sql);
			$resultrow = $dbreturn->fetch_assoc();
			$num_users = $resultrow["COUNT(*)"];

			if($num_users > 0){

				//Search
				echo "<div class='page_container'>";
					echo "<div class='row'>";
						echo "<div class='col l12 m12 s12' style='padding:0'>";
							echo "<nav style='background-color:"; echo $siteColor; echo ";'>";
									echo "<div class='nav-wrapper'>";
										echo "<form id='form-search' method='post' action='modules/directory/searchresults.php'>";
											echo "<div class='input-field'>";
												echo "<input id='searchquery' type='search' placeholder='Search' autocomplete='off'>";
												echo "<label class='label-icon' for='searchquery'><i class='material-icons'>search</i></label>";
											echo "</div>";
										echo "</form>";
									echo "</div>";
							echo "</nav>";
						echo "</div>";
					echo "</div>";
				echo "</div>";

				//Display Recent Searches
				echo "<div id='searchresults'>";
					include "searchresults.php";
				echo "</div>";

			}else{
				if($_SESSION['usertype'] == "staff"){
					echo "<div style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>No Active Staff</span><br><p style='font-size:16px; margin:20px 0 0 0;'></p></div>";
				}else{
					echo "<div style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>No Active Staff</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Click the '+' button in the bottom right to add a staff member.</p></div>";
				}
			}

			if($pageaccess == 1){ include "button.php"; }
		}
	}
?>

<script>

		//Process the profile form
		$(function(){

			//when clicking pagination button reload table with next page's results
			$('#searchresults').off('.pagebutton').on('click', '.pagebutton', function(){
				event.preventDefault();
				$('.mdl-layout__content').animate({scrollTop:0}, 0);
				var currentPage = $(this).data('page');
				var searchQuery = $('#searchquery').val();
				$.post( "modules/directory/searchresults.php", {page: currentPage, searchquery: searchQuery})
				.done(function(data){
					$("#searchresults").html(data);
				});
			});

			//Press the search data
			var form = $('#form-search');
			$(form).submit(function(event) {
				event.preventDefault();
				var searchQuery = $('#searchquery').val();
				$.ajax({
				    type: 'POST',
				    data: {searchquery: searchQuery},
				    url: $(form).attr('action'),
				    success: function(data) {
				    	$('#searchresults').html(data);
				    }
				});
			});

		});

</script>