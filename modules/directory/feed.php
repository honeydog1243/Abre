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

	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

	$returnarray = array();

	$building = htmlspecialchars($_GET["building"]);
	$building = preg_replace("/[^ \w]+/", "", $building);
	if($building == "All"){
		$sql = "SELECT firstname, lastname, email, title, picture FROM directory WHERE archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY lastname";
	}else{
		$sql = "SELECT firstname, lastname, email, title, picture FROM directory WHERE location = '$building' AND archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY lastname";
	}
	$result = $db->query($sql);
	$numberofrows = $result->num_rows;
	while($row = $result->fetch_assoc()){
		$firstname = htmlspecialchars($row["firstname"], ENT_QUOTES);
		$firstname = stripslashes($firstname);
		$lastname = htmlspecialchars($row["lastname"], ENT_QUOTES);
		$lastname = stripslashes($lastname);
		$email = $row["email"];
		$email = stripslashes($email);
		$title = htmlspecialchars($row["title"], ENT_QUOTES);
		$title = stripslashes($title);
		$picture = htmlspecialchars($row["picture"], ENT_QUOTES);
		if($picture == ""){
			$picture='user.png';
		}
		$returnarray[] = array('firstname' => $firstname,'lastname' => $lastname,'email' => $email,'title' => $title,'picture' => $picture);
	}

	if (!empty($returnarray)){
		print_r(json_encode($returnarray));
	}
?>