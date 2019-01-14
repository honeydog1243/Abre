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
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');

	if(superadmin()){

		//Get POST Variables
		$uniqueappname=$_POST["uniquename"];
		$activestate=$_POST["activestate"];

		//Check if record exists
		$apprecordexists = 0;
		$sqlcountcheck = "SELECT COUNT(*) FROM apps_abre WHERE app='$uniqueappname' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$sqlcountcheckresult = $db->query($sqlcountcheck);
		$sqlcountcheckreturn = $sqlcountcheckresult->fetch_assoc();
		$apprecordexists = $sqlcountcheckreturn["COUNT(*)"];

		//If app exists, change active state
		if($apprecordexists != 0){
			mysqli_query($db, "UPDATE apps_abre SET active='$activestate' WHERE app='$uniqueappname' AND siteID = '".$_SESSION['siteID']."'") or die (mysqli_error($db));

		}else{

			$stmt = $db->stmt_init();
			$sql = "INSERT INTO apps_abre (app, active, siteID) VALUES ('$uniqueappname', '0', '".$_SESSION['siteID']."');";
			$stmt->prepare($sql);
			$stmt->execute();
			$stmt->close();

		}

		//Close Database
		$db->close();

	}

?>
