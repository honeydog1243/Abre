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

	//Update system settings
	if(admin()){

		//Get Post Fields
		if(isset($_POST['clientid'])){
			$clientId = $_POST['clientid'];
		}else{
			$clientId = "";
		}

		if(isset($_POST['clientsecret'])){
			$clientSecret = encrypt($_POST['clientsecret'], '');
		}else{
			$clientSecret = "";
		}

		if(isset($_POST['groupArray'])){
			$groupArray = $_POST['groupArray'];
		}else{
			$groupArray = "";
		}

		if(isset($_POST['service'])){
			$service = $_POST['service'];
		}else{
			$service = "";
		}

		//Get Existing JSON string
		$sql = "SELECT authentication FROM settings WHERE siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$result = $db->query($sql);
		$row = $result->fetch_assoc();
		$json = $row["authentication"];
		$jsonDecoded = json_decode($json, true);

		//Set New Values
		$jsonDecoded[$service."clientid"] = $clientId;
		$jsonDecoded[$service."clientsecret"] = $clientSecret;
		$jsonDecoded[$service."signingroups"] = $groupArray;

		$updateJson = json_encode($jsonDecoded);

		$stmt = $db->stmt_init();
		$sql = "UPDATE settings SET authentication = ? WHERE siteID = ?";
		$stmt->prepare($sql);
		$stmt->bind_param("si", $updateJson, $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
		$db->close();

		//Notification message
		echo ucfirst($service)." authentication settings have been saved!";
	}

?>
