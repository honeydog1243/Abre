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

    //Add the stream
    $owner = $_SESSION['useremail'];
    $headlineid = $_POST['id'];
    $headlinePurpose = $_POST['purpose'];
    $headlineContent = $_POST['content'];
    $dateRestriction = $_POST['dateRestriction'];
    $headlineForm = $_POST['form'];
    $headlineVideo = $_POST['video'];
    $headlineGroup = $_POST['groups'];
    $headlineRequired = $_POST['required'];

    if($_POST['title'] != ""){
      $headlineTitle = $_POST['title'];
    }else{
      $response = array("status" => "Error", "message" => "Please provide a headline title!");
      header("Content-Type: application/json");
      echo json_encode($response);
      exit;
    }
    if($dateRestriction == 1){
      if($_POST['startDate'] != ""){
        $headlineStartDate = $_POST['startDate'];
      }else{
        $_POST['startDate'] = NULL;
      }
      if($_POST['endDate'] != ""){
        $headlineEndDate = $_POST['endDate'];
      }else{
        $_POST['endDate'] = NULL;
      }
    }else{
      $_POST['startDate'] = NULL;
      $_POST['endDate'] = NULL;
    }

    if($headlineid == ""){
      $stmt = $db->stmt_init();
      $sql = "INSERT INTO headlines (id, owner, title, content, purpose, form_id, video_id, groups, date_restriction, start_date, end_date, required, siteID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
      $stmt->prepare($sql);
      $stmt->bind_param("issssississii", $headlineid, $owner, $headlineTitle, $headlineContent, $headlinePurpose, $headlineForm, $headlineVideo, $headlineGroup, $dateRestriction, $headlineStartDate, $headlineEndDate, $headlineRequired, $_SESSION['siteID']);
      $stmt->execute();
      $stmt->close();
    }else{
      $stmt = $db->stmt_init();
      $sql = "UPDATE `headlines` SET title = ?, content = ?, purpose = ?, form_id = ?, video_id = ?, groups = ?, date_restriction = ?, start_date = ?, end_date = ?, required = ? WHERE id = ? AND siteID = ?";
      $stmt->prepare($sql);
      $stmt->bind_param("sssississiii", $headlineTitle, $headlineContent, $headlinePurpose, $headlineForm, $headlineVideo, $headlineGroup, $dateRestriction, $headlineStartDate, $headlineEndDate, $headlineRequired, $headlineid, $_SESSION['siteID']);
      $stmt->execute();
    }
    $db->close();
  }
?>