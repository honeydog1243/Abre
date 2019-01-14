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
	require_once(dirname(__FILE__) . '/../../core/abre_google_login.php');

	//Insert/Update Profile
	include "../../core/abre_dbconnect.php";
	$sql = "SELECT * FROM profiles WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
	$result = $db->query($sql);
	$stack = array();
	$departmentcount = $_POST["departmentcount"];

	while($row = $result->fetch_assoc()){
		$profileupdatecount = 1;
		for ($x = 0; $x <= $departmentcount; $x++) {
	    	if(!empty($_POST["checkbox_$x"])){ $message = $_POST["checkbox_$x"]; }
	    	if(!empty($message)){ array_push($stack, $message); }
		}
		$str = implode (", ", $stack);
		$stmt = $db->stmt_init();
		$sql = "UPDATE profiles SET streams = ? WHERE email = ? AND siteID = ?";
		$stmt->prepare($sql);
		$stmt->bind_param("ssi", $str, $_SESSION['useremail'], $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
		$db->close();
	}

	if($profileupdatecount == 0){
		for ($x = 0; $x <= $departmentcount; $x++) {
	    	$message = $_POST["checkbox_$x"];
	    	if($message != ""){ array_push($stack, $message); }
		}
		$str = implode (", ", $stack);
		$stmt = $db->stmt_init();
		$sql = "INSERT INTO profiles (email, streams, siteID) VALUES (?, ?, ?)";
		$stmt->prepare($sql);
		$stmt->bind_param("ssi", $_SESSION['useremail'], $str, $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
		$db->close();
	}

?>