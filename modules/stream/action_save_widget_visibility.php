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

	$widgets = $_POST['widgets'];


	//Check to see if profile record exists
	include "../../core/abre_dbconnect.php";
	$query = "SELECT COUNT(*) FROM profiles WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
	$results = $db->query($query);
	$resultrow = $results->fetch_assoc();
	$records = $resultrow["COUNT(*)"];

	if($records==0){
		$stmt = $db->stmt_init();
		$sql = "INSERT INTO profiles (email, startup, siteID) VALUES (?, '0', ?)";
		$stmt->prepare($sql);
		$stmt->bind_param("si", $_SESSION['useremail'], $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
	}

	$stmt = $db->stmt_init();
	$sql = "UPDATE profiles SET widgets_hidden = ? WHERE email = ? AND siteID = ?";
	$stmt->prepare($sql);
	$stmt->bind_param("ssi", $widgets, $_SESSION['useremail'], $_SESSION['siteID']);
	$stmt->execute();
	$stmt->close();
	$db->close();

?>