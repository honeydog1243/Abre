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

  $headlineInformationJSON = $_POST['json'];

  $headlineInformationArray = json_decode($headlineInformationJSON, true);
  $headlinePurpose = $headlineInformationArray['headline_purpose'];
  unset($headlineInformationArray['headline_purpose']);
  $headlineID = $headlineInformationArray['headline_id'];
  unset($headlineInformationArray['headline_id']);
  $headlineFormID = $headlineInformationArray['headline_formID'];
  unset($headlineInformationArray['headline_formID']);
  $headlineInformationJSON = json_encode($headlineInformationArray);

  $submitter = $_SESSION['useremail'];

	//Insert the headline_response
	$stmt = $db->stmt_init();
	$sql = "INSERT INTO headline_responses (headline_id, email, siteID) VALUES (?, ?, ?)";
	$stmt->prepare($sql);
	$stmt->bind_param("isi", $headlineID, $submitter, $_SESSION['siteID']);
	$stmt->execute();
  if($stmt->error != ""){
    $response = array("status" => "Error", "message" => "There was a problem saving your headline response!");
    header("Content-Type: application/json");
    echo json_encode($response);
    $stmt->close();
    $db->close();
    exit;
  }
	$stmt->close();
	$db->close();

  $response = array("status" => "Success", "message" => "Your response was saved successfully!");
  header("Content-Type: application/json");
  echo json_encode($response);

?>