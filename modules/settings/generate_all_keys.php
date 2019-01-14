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

	//Check for student_tokens table
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	if(!$db->query("SELECT * FROM student_tokens LIMIT 1")){
		$tableCreationSql = "CREATE TABLE `student_tokens` (`id` int(11) NOT NULL,`studentId` text NOT NULL,`token` text NOT NULL, `siteID` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    $tableCreationSql .= "ALTER TABLE `student_tokens` ADD PRIMARY KEY (`id`);";
    $tableCreationSql .= "ALTER TABLE `student_tokens` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
    $db->multi_query($tableCreationSql);
		while ($db->next_result()) {;}

    $studentSql = "SELECT StudentId FROM Abre_Students WHERE siteID = '".$_SESSION['siteID']."'";
    $result = $db->query($studentSql);
    while($row = $result->fetch_assoc()){
      $id = $row['StudentId'];
      $stringToken = $id . time();
      $token = encrypt(substr(hash('sha256', $stringToken), 0, 10), "");

			$stmt = $db->stmt_init();
      $tokenGenerationSql = "INSERT INTO `student_tokens` (`studentId`, `token`, siteID) VALUES (?, ?, ?);";
			$stmt->prepare($tokenGenerationSql);
			$stmt->bind_param("isi", $id, $token, $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
    }
		$db->close();
	}else{
		//if database already exists delete it and re-add
		$studentTokensSql = "DELETE FROM `student_tokens` WHERE siteID = '".$_SESSION['siteID']."'";
    $db->query($studentTokensSql);

		$parentStudentsSql = "DELETE FROM parent_students WHERE siteID = '".$_SESSION['siteID']."'";
		$db->query($parentStudentsSql);

    $studentSql = "SELECT StudentId FROM Abre_Students WHERE siteID = '".$_SESSION['siteID']."'";
    $result = $db->query($studentSql);
    while($row = $result->fetch_assoc()){
      $id = $row['StudentId'];
      $stringToken = $id . time();
      $token = encrypt(substr(hash('sha256', $stringToken), 0, 10), "");

			$stmt = $db->stmt_init();
      $tokenGenerationSql = "INSERT INTO `student_tokens` (`studentId`, `token`, siteID) VALUES (?, ?, ?);";
			$stmt->prepare($tokenGenerationSql);
			$stmt->bind_param("isi", $id, $token, $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
    }
		$db->close();
	}

?>