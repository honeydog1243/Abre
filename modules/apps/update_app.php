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

	if(admin()){

		//Add the app
		$appid = $_POST["id"];
		$appname = $_POST["name"];
		$applink = $_POST["link"];
		$appicon = $_POST["icon"];
		$appicon = str_replace("thumb_", "", $appicon);
		$appstaff = $_POST["staff"];
		$appstudents = $_POST["students"];
		$appparents = $_POST["parents"];
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

		if($appid == ""){
			$stmt = $db->stmt_init();
			$sql = "INSERT INTO apps (title,link,icon,image,staff,student,required,parent,staff_building_restrictions,student_building_restrictions, siteID) VALUES (?, ?, ?, ?, ?, ?, '1', ?, ?, ?, ?);";
			$stmt->prepare($sql);
			$stmt->bind_param("ssssiiissi", $appname, $applink, $appicon, $appicon, $appstaff, $appstudents, $appparents, $staffRestrictions, $studentRestrictions, $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
		}else{
			$stmt = $db->stmt_init();
			$sql = "UPDATE apps SET title = ?, link = ?, icon = ?, image = ?, staff = ?, student = ?, parent = ?, staff_building_restrictions = ?, student_building_restrictions = ? WHERE id = ? AND siteID = ?;";
			$stmt->prepare($sql);
			$stmt->bind_param("ssssiiissii", $appname, $applink, $appicon, $appicon, $appstaff, $appstudents, $appparents, $staffRestrictions, $studentRestrictions, $appid, $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
		}
		$db->close();
	}
?>
