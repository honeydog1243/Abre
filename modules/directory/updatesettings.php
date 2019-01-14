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
	require('permissions.php');
	require_once('functions.php');

	//Update Directory Settings
	if($pageaccess == 1){
		foreach($_POST as $key => $value){
			$query = $db->query("SELECT COUNT(*) FROM `directory_settings` WHERE dropdownID = '$key' AND siteID = '".$_SESSION['siteID']."'");
			$resultrow = $query->fetch_assoc();
			$count = $resultrow["COUNT(*)"];

			if($count > 0){
				$stmt = $db->stmt_init();
				$sql = "UPDATE `directory_settings` SET `options` = ? WHERE `dropdownID` = ? AND siteID = ?";
				$stmt->prepare($sql);
				$stmt->bind_param("ssi", $value, $key, $_SESSION['siteID']);
				$stmt->execute();
			}else{
				$stmt = $db->stmt_init();
				$sql = "INSERT INTO `directory_settings` (`dropdownID`,`options`, siteID) VALUES (?, ?, ?);";
				$stmt->prepare($sql);
				$stmt->bind_param("ssi", $key, $value, $_SESSION['siteID']);
				$stmt->execute();
			}
		}
		$stmt->close();
		$db->close();
		echo "Settings have been updated.";
  }

?>