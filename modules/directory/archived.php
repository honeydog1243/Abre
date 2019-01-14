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
	$portal_root = getConfigPortalRoot();

	//Display search results
	if($pageaccess == 1){

		$sql = "SELECT COUNT(*) FROM directory WHERE archived = 1 AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		$resultrow = $result->fetch_assoc();
		$rowcount = $resultrow["COUNT(*)"];
		if($rowcount != 0){

			//Display Recent Searches
			echo "<div class='page_container mdl-shadow--4dp'>";
				echo "<div class='page'>";
					echo "<div class='row'><div class='col s12'>";
						echo "<table id='myTable' class='tablesorter'>";
							echo "<thead>";
								echo "<tr class='pointer'>";
									echo "<th></th>";
									echo "<th>Name</th>";
			 						echo "<th class='hide-on-small-only'>Email</th>";
			 						echo "<th class='hide-on-med-and-down'>Title</th>";
			 						echo "<th style='width:30px'></th>";
			 						echo "<th style='width:30px'></th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
							$sql = "SELECT firstname, lastname, location, email, title, classification, probationreportdate, picture, senioritydate, id FROM directory WHERE archived = 1 AND siteID = '".$_SESSION['siteID']."' ORDER BY updatedtime DESC";
							$result = $db->query($sql);
							while($row = $result->fetch_assoc()){
								$resultcount = 1;
								$firstname = htmlspecialchars($row["firstname"], ENT_QUOTES);
								$firstname = stripslashes($firstname);
								$lastname = htmlspecialchars($row["lastname"], ENT_QUOTES);
								$lastname = stripslashes($lastname);
								$location = htmlspecialchars($row["location"], ENT_QUOTES);
								$location = stripslashes($location);
								$email = $row["email"];
								$email = stripslashes($email);
								$title = htmlspecialchars($row["title"], ENT_QUOTES);
								$title = stripslashes($title);
								$classification = htmlspecialchars($row["classification"], ENT_QUOTES);
								$classification = stripslashes($classification);
								$prd = htmlspecialchars($row["probationreportdate"], ENT_QUOTES);
								$prd = stripslashes(htmlspecialchars(decrypt($prd, ""), ENT_QUOTES));
								$picture = htmlspecialchars($row["picture"], ENT_QUOTES);

								if (strpos($picture, 'http') === false) {
									if($picture == ""){
										$picture = $portal_root."/modules/directory/images/user.png";
									}else{
										$picture = $portal_root."/modules/directory/serveimage.php?file=$picture";
									}
								}

								$senioritydate = htmlspecialchars($row["senioritydate"], ENT_QUOTES);
								$senioritydate = stripslashes(htmlspecialchars(decrypt($senioritydate, ""), ENT_QUOTES));
								$id = htmlspecialchars($row["id"], ENT_QUOTES);
								echo "<tr>";
									echo "<td width=60px><img src='$picture' class='profile-avatar-small demoimage' alt='Profile Picture'></td>";
									echo "<td><strong class='demotext_dark'>$firstname $lastname</strong><a href='$portal_root/#directory/$id' class='hidden'></a></td>";
									echo "<td class='hide-on-small-only demotext_dark'>$email</td>";
									echo "<td class='hide-on-med-and-down demotext_dark'>$title</td>";
									echo "<td width=30px><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 restoreuser'><a href='modules/directory/restoreuser.php?id=$id'></a><i class='material-icons'>cached</i></button></td>";
									if(admin()){
										echo "<td width=30px><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 deleteuser'><a href='modules/directory/permdeleteuser.php?id=$id'></a><i class='material-icons'>delete</i></button></td>";
									}
								echo "</tr>";
							}
							echo "</tbody>";
						echo "</table>";
					echo "</div>";
				echo "</div>";
			 echo "</div>";
			echo "</div>";
		}else{
			echo "<div class='row' style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>No Archived Employees</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Employees you archive will show up here.</p></div>";
		}
?>

<script>

			$(document).ready(function(){

				$("#myTable").tablesorter({
					sortList: [[1,0],[3,0]]
    		});

			});

</script>

<?php
	}
?>