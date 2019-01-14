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

	//Check for Admin Authentication
	$pageaccess = 0;
	$sql = "SELECT * FROM directory WHERE email = '".$_SESSION['escapedemail']."' AND (admin = 1 OR superadmin = 1) AND archived = 0 AND siteID = '".$_SESSION['siteID']."'";
	$result = $db->query($sql);
	while($row = $result->fetch_assoc()){
		$pageaccess = 1;
	}

	$sql = "SELECT superadmin FROM users WHERE email='".$_SESSION['escapedemail']."' AND (superadmin = 1 OR admin = 1) AND siteID = '".$_SESSION['siteID']."'";
	$result = $db->query($sql);
	while($row = $result->fetch_assoc()){
		$pageaccess = 1;
	}

	$sql = "SELECT * FROM directory WHERE email = '".$_SESSION['escapedemail']."' AND admin = 2 AND archived = 0 AND siteID = '".$_SESSION['siteID']."'";
	$result = $db->query($sql);
	while($row = $result->fetch_assoc()){
		$pageaccess = 2;
	}
?>