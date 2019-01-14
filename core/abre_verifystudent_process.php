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
	require_once('abre_verification.php');
	require_once('abre_functions.php');
	require('abre_dbconnect.php');

  header("content-type: none");

	//Verify student token
	$studenttoken = $_POST["studenttoken"];
  $studenttokenencrypted = encrypt($studenttoken, "");

	$sql = "SELECT id FROM users_parent WHERE email LIKE '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."';";
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$parent_id = $row["id"];

	$sql = "SELECT studentId FROM student_tokens WHERE token = '$studenttokenencrypted' AND siteID = '".$_SESSION['siteID']."'";
	$result = $db->query($sql);
	$numrows = $result->num_rows;
	while($row = $result->fetch_assoc()){
		//Check to see if student has already been claimed by parent
		$sqlcheck = "SELECT student_token FROM parent_students WHERE parent_id = $parent_id AND studentId = '".$row['studentId']."' AND siteID = '".$_SESSION['siteID']."'";
		$resultcheck = $db->query($sqlcheck);
		$parentrow = $resultcheck->fetch_assoc();
		$numrows2 = $resultcheck->num_rows;

		if($numrows2 == 0){
			$stmt = $db->stmt_init();
			$sql = "INSERT INTO parent_students (parent_id, student_token, studentId, siteID) VALUES (?, ?, ?, ?)";
			$stmt->prepare($sql);
			$stmt->bind_param("issi", $parent_id, $studenttokenencrypted, $row['studentId'], $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
			$db->close();

			$message = array("status"=>"Success", "message"=>"You now have access to your student's information.");
			header("Content-Type: application/json");
			echo json_encode($message);
			break;
		}else{
			if($studenttokenencrypted == $parentrow['student_token']){
				$message = array("status"=>"Success", "message"=>"You already have access to your student's information.");
				header("Content-Type: application/json");
				echo json_encode($message);
				break;
			}else{
				$stmt = $db->stmt_init();
				$sql = "UPDATE parent_students SET student_token = ? WHERE parent_id = ? AND studentId = ? AND siteID = ?";
				$stmt->prepare($sql);
				$stmt->bind_param("sisi", $studenttokenencrypted, $parent_id, $row['studentId'], $_SESSION['siteID']);
				$stmt->execute();
				$stmt->close();
				$db->close();

				$message = array("status"=>"Success", "message"=>"You now have access to your student's information.");
				header("Content-Type: application/json");
				echo json_encode($message);
				break;
			}
		}
	}
  if($numrows == 0){
    $message = array("status"=>"Error", "message"=>"Error: Your code does not match our records. Please confirm your code and try again.");
    header("Content-Type: application/json");
    echo json_encode($message);
  }

?>