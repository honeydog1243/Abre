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

		//Retrieve settings and group as json
		$softwareanswersurl = $_POST["softwareanswersurl"];
		$softwareanswersidentifier = $_POST["softwareanswersidentifier"];
		$softwareanswerskey = $_POST["softwareanswerskey"];

		$array = [
					"softwareanswersurl" => "$softwareanswersurl",
					"softwareanswersidentifier" => "$softwareanswersidentifier",
					"softwareanswerskey" => "$softwareanswerskey"
				];
		$json = json_encode($array);

		$stmt = $db->stmt_init();
		$sql = "UPDATE settings SET integrations = ? WHERE siteID = ?";
		$stmt->prepare($sql);
		$stmt->bind_param("si", $json, $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
		$db->close();

		//Notification message
		echo "Integrations have been updated.";
	}

?>
