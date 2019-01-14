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

	$calendardaystosave = $_POST["calendardaystosave"];
	$year = $_POST["year"];
	$json = $_POST["jsonDates"];
	$email = $_POST["email"];

	//if the json value passed from javascript == null.
	//we need to insert the days to save in to a new blannk array
	if($json == null || !isset($json)){
			$replacement = array($year => $calendardaystosave);
			$ret = array_replace(array(), $replacement);
			$ret = json_encode($ret);
	//there is already existing json, so we just need to replace the year entry
	//with the new dates to save
	}else{
		$replacement = array($year => $calendardaystosave);
		$ret = array_replace($json, $replacement);
		$ret = json_encode($ret);
	}

	//make a request to save the values in the databse for the year.
	include "../../core/abre_dbconnect.php";
	$stmt = $db->stmt_init();
	$sql = "UPDATE profiles SET work_calendar = ? WHERE email = ? AND siteID = ?";
	$stmt->prepare($sql);
	$stmt->bind_param("ssi", $ret, $email, $_SESSION['siteID']);
	$stmt->execute();
	$stmt->close();
	$db->close();

?>