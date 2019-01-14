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

	if(admin() || isStreamHeadlineAdministrator()){
    $headlineid = $_GET["id"];

    $stmt = $db->stmt_init();
    $sql = "DELETE FROM headline_responses WHERE headline_id = ? AND siteID = ?";
    $stmt->prepare($sql);
    $stmt->bind_param("ii", $headlineid, $_SESSION['siteID']);
    $stmt->execute();
    $stmt->close();

		//Delete the headline
		$stmt = $db->stmt_init();
		$sql = "DELETE FROM headlines WHERE id = ? AND siteID = ?";
		$stmt->prepare($sql);
		$stmt->bind_param("ii", $headlineid, $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
		$db->close();
	}

?>