<?php

	/*
	* Copyright (C) 2016-2018 Abre.io Inc.
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
	require_once(dirname(__FILE__) . '/../../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../../core/abre_functions.php');
	require_once(dirname(__FILE__) . '/../../../core/abre_dbconnect.php');

    $title = $_POST["title"];
    $subject = $_POST["subject"];
    $body = $_POST["body"];

    $dt = new DateTime('@'.time());
    $now = $dt->format('c');
    $usersId = 10; //TODO harded
    // $files = $_POST["files"];
    $sql = "INSERT INTO Abre_Connect_Journal (UsersId, Title, Body, LastUpdated) VALUES (?, ?, ?, ?)";

    $statement = $db->stmt_init();
    $statement->prepare($sql);
    $statement->bind_param("isss", $usersId, $title, $body, $now);
    $statement->execute();
    $statement->close();
    $db->close();
?>