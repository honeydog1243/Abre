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
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

	if($pageaccess == 1){

		if(getenv("USE_GOOGLE_CLOUD") == "true"){
			$decryptionKey = getConfigDBKey();
		}else{
			$decryptionKey = constant("DB_KEY");
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=staffworkcalendars.csv');
		$output = fopen('php://output', 'w');
		$array = array('First Name', 'Last Name', 'Email', 'Contracted Days');
		$setupCSV = false;

		include "../../core/abre_dbconnect.php";
		$rows = mysqli_query($db, "SELECT email, firstname, lastname, contractdays FROM directory WHERE archived = 0 AND siteID = '".$_SESSION['siteID']."'");
		while ($row = mysqli_fetch_assoc($rows)) {
			$email = $row["email"];
			$email = stripslashes($email);
			$emailSearch = mysqli_real_escape_string($db, $row["email"]);
			$firstname = $row["firstname"];
			$firstname = stripslashes($firstname);
			$lastname = $row["lastname"];
			$lastname = stripslashes($lastname);
			$contractdays = $row["contractdays"];
			$contractdays = stripslashes(decrypt($contractdays, "", $decryptionKey));

			if($contractdays != ""){
				$rowsselected = mysqli_query($db, "SELECT work_calendar FROM profiles WHERE email = '$emailSearch' AND siteID = '".$_SESSION['siteID']."'");
				while ($rowselect = mysqli_fetch_assoc($rowsselected)){
					$data = [$firstname, $lastname, $email, $contractdays];
					$work_calendar = $rowselect["work_calendar"];
					$work_calendar_decode = json_decode($work_calendar, true);
					foreach($work_calendar_decode as $key => $value){
						$entry2 = $key . " Selected Days Count";
						$entry3 = $key . " Selected Days";
						array_push($array, $entry2, $entry3);
					}
					if(!$setupCSV){
						fputcsv($output, $array);
						$setupCSV = true;
					}
					foreach($work_calendar_decode as $key => $value){
						$work_calendar_count = substr_count($value, ",");
						if($work_calendar_count != 0){ $work_calendar_count=$work_calendar_count + 1; }
						array_push($data, $work_calendar_count, $value);
					}
					fputcsv($output, $data);
				}
			}
		}
		// fputcsv($output, $data);
		fclose($output);
		mysqli_close($db);
		exit();
	}
?>