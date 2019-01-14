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
	require_once('permissions.php');

  if($classification == ""){
		echo "<option value='$classification' selected>Choose</option>";
	}
	if($classification != ""){
		echo "<option value='$classification' selected>$classification</option>";
	}
	$sql = "SELECT options FROM directory_settings WHERE dropdownID = 'classificationTypes' AND siteID = '".$_SESSION['siteID']."'";
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	$titles = explode(PHP_EOL, $row['options']);
	foreach($titles as $value){
		$val = str_replace(array("\n\r", "\n", "\r"), '', $value);
		echo "<option value ='$val'>$val</option>";
	}
?>