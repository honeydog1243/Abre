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

	//Display Search Results
	if($pageaccess == 1 or $_SESSION['usertype'] == "staff"){

		//get the current page the user is on
		if(isset($_POST["page"])){
			if($_POST["page"] == ""){
				$PageNumber = 1;
			}else{
				$PageNumber = $_POST["page"];
			}
		}else{
			$PageNumber = 1;
		}

		$PerPage = 10;

		//set bounds for pagination
	  $LowerBound = $PerPage * ($PageNumber - 1);
	  $UpperBound = $PerPage * $PageNumber;

		//Retrieve Search Query
		if(isset($_POST["searchquery"])){ $searchquery = strtolower(mysqli_real_escape_string($db, $_POST["searchquery"])); }else{ $searchquery = ""; }

		//filter results based on the query
		if($searchquery != ""){
			$querycount = "SELECT id, firstname, lastname, location, email, title, picture, extension FROM directory WHERE (LOWER(lastname) LIKE '%$searchquery%' OR LOWER(firstname) LIKE '%$searchquery%' OR LOWER(email) LIKE '%$searchquery%' OR LOWER(location) LIKE '%$searchquery%' OR LOWER(classification) LIKE '%$searchquery%') AND archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY firstname, lastname";

			$sql = "SELECT id, firstname, lastname, location, email, title, picture, extension FROM directory WHERE (LOWER(lastname) LIKE '%$searchquery%' OR LOWER(firstname) LIKE '%$searchquery%' OR LOWER(email) LIKE '%$searchquery%' OR LOWER(location) LIKE '%$searchquery%' OR LOWER(classification) LIKE '%$searchquery%') AND archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY firstname, lastname LIMIT $LowerBound, $PerPage";
		}else{
			$querycount = $sql = "SELECT id, firstname, lastname, location, email, title, picture, extension FROM directory WHERE archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY firstname, lastname";

			$sql = "SELECT id, firstname, lastname, location, email, title, picture, extension FROM directory WHERE archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY firstname, lastname LIMIT $LowerBound, $PerPage";
		}

		$result = $db->query($sql);
		$resultscount = $result->num_rows;
		$resultscounter = 0;

		//retrieve info from database
		while($row = $result->fetch_assoc()){
			$resultscounter++;
			if($resultscounter == 1){
				//Display the Page
				echo "<div class='page_container mdl-shadow--4dp'>";
					echo "<div class='page'>";
						echo "<div class='row'>";
							echo "<table id='myTable' class='highlight'>";
							echo "<thead>";
							echo "<tr>";
							echo "<th></th>";
							echo "<th>Name</th>";
							echo "<th class='hide-on-small-only'>Email</th>";
							echo "<th class='hide-on-small-only'>Location</th>";
							echo "<th class='hide-on-med-and-down'>Title</th>";
							echo "<th class='hide-on-med-and-down'>Extension</th>";
							echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
			}
			$employeeid = htmlspecialchars($row["id"], ENT_QUOTES);
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
			$extension = htmlspecialchars($row["extension"], ENT_QUOTES);
			$extension = stripslashes($extension);
			$picture = htmlspecialchars($row["picture"], ENT_QUOTES);

			if (strpos($picture, 'http') === false) {

				if($picture == ""){
					$picture = $portal_root."/modules/directory/images/user.png";
				}else{
					$picture = $portal_root."/modules/directory/serveimage.php?file=$picture";
				}

			}

			//display the results in table
			echo "<tr class='employeeview pointer' data-employeeid='$employeeid' data-searchquerysaved='$searchquery'>";
				echo "<td width='75px;'><img src='$picture' class='profile-avatar-small' alt='Profile Picture' style='margin-left:5px;'></td>";
				echo "<td><strong class='demotext_dark'>$firstname $lastname</strong></td>";
				echo "<td class='hide-on-small-only demotext_dark'>$email</td>";
				echo "<td class='hide-on-small-only demotext_dark'>$location</td>";
				echo "<td class='hide-on-med-and-down demotext_dark'>$title</td>";
				if($extension == ""){
					echo "<td class='hide-on-med-and-down demotext_dark'></td>";
				}else{
					echo "<td class='hide-on-med-and-down demotext_dark'>$extension</td>";
				}
			echo "</tr>";

			if($resultscounter == $resultscount){
				echo "</tbody></table>";
				echo "</div>";

				//getting count for pagination
				$dbreturnpossible = databasequery($querycount);
				$totalpossibleresults = count($dbreturnpossible);

				//Paging
				$totalpages = ceil($totalpossibleresults / $PerPage);
				if($totalpossibleresults > $PerPage){
					$previouspage = $PageNumber-1;
					$nextpage = $PageNumber+1;
					if($PageNumber > 5){
						if($totalpages > $PageNumber + 5){
							$pagingstart = $PageNumber - 5;
							$pagingend = $PageNumber + 5;
						}else{
							$pagingstart = $PageNumber - 5;
							$pagingend = $totalpages;
						}
					}else{
						if($totalpages >= 10){ $pagingstart = 1; $pagingend = 10; }else{ $pagingstart = 1; $pagingend = $totalpages; }
					}

					echo "<div class='row'><br>";
					echo "<ul class='pagination center-align'>";
						if($PageNumber != 1){ echo "<li class='pagebutton' data-page='$previouspage'><a href='#'><i class='material-icons'>chevron_left</i></a></li>"; }
						for($x = $pagingstart; $x <= $pagingend; $x++){
							if($PageNumber == $x){
								echo "<li class='active pagebutton' style='background-color: ".getSiteColor().";' data-page='$x'><a href='#'>$x</a></li>";
							}else{
								echo "<li class='waves-effect pagebutton' data-page='$x'><a href='#'>$x</a></li>";
							}
						}
						if($PageNumber != $totalpages){ echo "<li class='waves-effect pagebutton' data-page='$nextpage'><a href='#'><i class='material-icons'>chevron_right</i></a></li>"; }
					echo "</ul>";
					echo "</div>";
				}
				echo "</div>";
				echo "</div>";
			}
		}

		if($resultscount == 0){
			echo "<div class='row' style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>No Staff Found</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Click the '+' in the bottom right to create a directory entry.</p></div>";
		}


?>
<script>

			$(document).ready(function(){

			});

</script>
<?php
	}
?>
