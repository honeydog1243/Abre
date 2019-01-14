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
    $streamid = $_POST["id"];
    $streamtitle = $_POST["title"];
    $rsslink = $_POST["link"];
    $streamgroup = $_POST["group"];
    $required = $_POST["required"];
    $color = $_POST["color"];

    if(isset($_POST['staffRestrictions'])){
      $staffRestrictions = $_POST['staffRestrictions'];
      $staffRestrictions = implode(",", $staffRestrictions);
    }else{
      $staffRestrictions = "No Restrictions";
    }
    if(isset($_POST['studentRestrictions'])){
      $studentRestrictions = $_POST['studentRestrictions'];
      $studentRestrictions = implode(",", $studentRestrictions);
    }else{
      $studentRestrictions = "No Restrictions";
    }

    if($streamid == ""){
      $stmt = $db->stmt_init();
      //needed to backtick because SQL doesn't like when you use reserved words
      $sql = "INSERT INTO `streams` (`group`,`title`,`slug`,`type`,`url`,`required`, `color`, `staff_building_restrictions`, `student_building_restrictions`, siteID) VALUES (?, ?, ?,'flipboard', ?, ?, ?, ?, ?, ?);";
      $stmt->prepare($sql);
      $stmt->bind_param("ssssisssi", $streamgroup, $streamtitle, $streamtitle, $rsslink, $required, $color, $staffRestrictions, $studentRestrictions, $_SESSION['siteID']);
      $stmt->execute();
      $stmt->close();
    }else{
      //need to index stream posts by old stream title incase it has changed
      $sql = "SELECT title FROM streams WHERE id = '$streamid' AND siteID = '".$_SESSION['siteID']."'";
      $result = $db->query($sql);
      $value = $result->fetch_assoc();
      $oldStreamTitle = $value['title'];

      //needed to backtick because SQL doesn't like when you use reserved words
      $stmt = $db->stmt_init();
      $sql = "UPDATE `streams` SET `group` = ?, `title` = ?, `slug` = ?, `type` = 'flipboard', `url` = ?, `required` = ?, `color` = ?, `staff_building_restrictions` = ?, `student_building_restrictions` = ? WHERE `id` = ? AND siteID = ?";
      $stmt->prepare($sql);
      $stmt->bind_param("ssssisssii", $streamgroup, $streamtitle, $streamtitle, $rsslink, $required, $color, $staffRestrictions, $studentRestrictions, $streamid, $_SESSION['siteID']);
      $stmt->execute();

      $sql = "UPDATE `stream_posts` SET post_stream = ?, `post_groups` = ?, color = ?, staff_building_restrictions = ?, student_building_restrictions = ? WHERE post_stream = ? AND siteID = ?";
      $stmt->prepare($sql);
      $stmt->bind_param("ssssssi", $streamtitle, $streamgroup, $color, $staffRestrictions, $studentRestrictions, $oldStreamTitle, $_SESSION['siteID']);
      $stmt->execute();
      $stmt->close();
    }
    $db->close();
  }
?>