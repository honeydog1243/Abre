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

		$streamid = $_GET['id'];
		$streamtitle = base64_decode($_GET['title']);

		//Delete the Stream
		$stmt = $db->stmt_init();
		$sql = "DELETE FROM streams WHERE id = ? AND siteID = ?";
		$stmt->prepare($sql);
		$stmt->bind_param("ii", $streamid, $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();

		$stmt = $db->stmt_init();
		$sql = "DELETE FROM stream_posts WHERE post_stream = ? AND siteID = ?";
		$stmt->prepare($sql);
		$stmt->bind_param("si", $streamtitle, $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
		$db->close();
	}

?>