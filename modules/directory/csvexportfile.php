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
		header('Content-Disposition: attachment; filename=allActiveStaff.csv');

		$output = fopen('php://output', 'w');

		fputcsv($output, array('Last Name', 'First Name', 'Middle Name', 'SSN', 'Date of Birth', 'Address', 'City', 'State', 'Zip', 'Phone', 'Extension', 'Cell Phone', 'Ethnicity', 'Gender', 'Email', 'Title', 'Contract', 'Classification', 'Home Building', 'Grade', 'Subject', 'Date of Hire', 'Seniority Date', 'Effective Date', 'Step', 'Level of Education', 'Contract Days', 'Salary', 'Hours', 'Probation Report Date', 'State Background Check' , 'Federal Background Check', 'State Educator ID', 'License 1 Type', 'License 1 Issue Date', 'License 1 Expiration Date', 'License 1 Term', 'License 2 Type', 'License 2 Issue Date', 'License 2 Expiration Date', 'License 2 Term', 'License 3 Type', 'License 3 Issue Date', 'License 3 Expiration Date', 'License 3 Term', 'License 4 Type', 'License 4 Issue Date', 'License 4 Expiration Date', 'License 4 Term', 'License 5 Type', 'License 5 Issue Date', 'License 5 Expiration Date', 'License 5 Term', 'License 6 Type', 'License 6 Issue Date', 'License 6 Expiration Date', 'License 6 Term'));
		include "../../core/abre_dbconnect.php";
		$rows = mysqli_query($db, "SELECT * FROM directory WHERE archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY lastname");

		while($row = mysqli_fetch_assoc($rows)){
			$firstname = stripslashes($row["firstname"]);
			$lastname = stripslashes($row["lastname"]);
			$middlename = stripslashes($row["middlename"]);
			$ss = stripslashes(decrypt($row["ss"], "", $decryptionKey));
			$dob = stripslashes(decrypt($row["dob"], "", $decryptionKey));
			$address = stripslashes(decrypt($row["address"], "",$decryptionKey));
			$city = stripslashes(decrypt($row["city"], "", $decryptionKey));
			$state = stripslashes(decrypt($row["state"], "", $decryptionKey));
			$zip = stripslashes(decrypt($row["zip"], "", $decryptionKey));
			$phone = stripslashes(decrypt($row["phone"], "", $decryptionKey));
			if(isset($extension)){
				$extension = stripslashes($row["extension"]);
			}else{
				$extension = "";
			}
			$cellphone = stripslashes(decrypt($row["cellphone"], "", $decryptionKey));
			$ethnicity = stripslashes(decrypt($row["ethnicity"], "", $decryptionKey));
			$gender = stripslashes(decrypt($row["gender"], "", $decryptionKey));
			$email = stripslashes($row["email"]);
			$title = stripslashes($row["title"]);
			$contract = stripslashes(decrypt($row["contract"], "", $decryptionKey));
			$classification = stripslashes($row["classification"]);
			$location = stripslashes($row["location"]);
			$grade = stripslashes($row["grade"]);
			$subject = stripslashes($row["subject"]);
			$doh = stripslashes(decrypt($row["doh"], "", $decryptionKey));
			$senioritydate = stripslashes(decrypt($row["senioritydate"], "", $decryptionKey));
			$effectivedate = stripslashes(decrypt($row["effectivedate"], "", $decryptionKey));
			$step = stripslashes(decrypt($row["step"], "", $decryptionKey));
			$educationlevel = stripslashes(decrypt($row["educationlevel"], "", $decryptionKey));
			$contractdays = stripslashes(decrypt($row["contractdays"], "", $decryptionKey));
			$salary = stripslashes(decrypt($row["salary"], "", $decryptionKey));
			$hours = stripslashes(decrypt($row["hours"], "", $decryptionKey));
			$probationreportdate = stripslashes(decrypt($row["probationreportdate"], "", $decryptionKey));
			$statebackgroundcheck = stripslashes(decrypt($row["statebackgroundcheck"], "", $decryptionKey));
			$federalbackgroundcheck = stripslashes(decrypt($row["federalbackgroundcheck"], "", $decryptionKey));
			$stateeducatorid = stripslashes(decrypt($row["stateeducatorid"], "", $decryptionKey));
			$l1_1 = stripslashes(decrypt($row["licensetype1"], "", $decryptionKey));
			$l1_2 = stripslashes(decrypt($row["licenseissuedate1"], "", $decryptionKey));
			$l1_3 = stripslashes(decrypt($row["licenseexpirationdate1"], "", $decryptionKey));
			$l1_4 = stripslashes(decrypt($row["licenseterm1"], "", $decryptionKey));
			$l2_1 = stripslashes(decrypt($row["licensetype2"], "", $decryptionKey));
			$l2_2 = stripslashes(decrypt($row["licenseissuedate2"], "", $decryptionKey));
			$l2_3 = stripslashes(decrypt($row["licenseexpirationdate2"], "", $decryptionKey));
			$l2_4 = stripslashes(decrypt($row["licenseterm2"], "", $decryptionKey));
			$l3_1 = stripslashes(decrypt($row["licensetype3"], "", $decryptionKey));
			$l3_2 = stripslashes(decrypt($row["licenseissuedate3"], "", $decryptionKey));
			$l3_3 = stripslashes(decrypt($row["licenseexpirationdate3"], "", $decryptionKey));
			$l3_4 = stripslashes(decrypt($row["licenseterm3"], "", $decryptionKey));
			$l4_1 = stripslashes(decrypt($row["licensetype4"], "", $decryptionKey));
			$l4_2 = stripslashes(decrypt($row["licenseissuedate4"], "", $decryptionKey));
			$l4_3 = stripslashes(decrypt($row["licenseexpirationdate4"], "", $decryptionKey));
			$l4_4 = stripslashes(decrypt($row["licenseterm4"], "", $decryptionKey));
			$l5_1 = stripslashes(decrypt($row["licensetype5"], "", $decryptionKey));
			$l5_2 = stripslashes(decrypt($row["licenseissuedate5"], "", $decryptionKey));
			$l5_3 = stripslashes(decrypt($row["licenseexpirationdate5"], "", $decryptionKey));
			$l5_4 = stripslashes(decrypt($row["licenseterm5"], "", $decryptionKey));
			$l6_1 = stripslashes(decrypt($row["licensetype6"], "", $decryptionKey));
			$l6_2 = stripslashes(decrypt($row["licenseissuedate6"], "", $decryptionKey));
			$l6_3 = stripslashes(decrypt($row["licenseexpirationdate6"], "", $decryptionKey));
			$l6_4 = stripslashes(decrypt($row["licenseterm6"], "", $decryptionKey));
			$data = [$lastname,$firstname,$middlename,$ss,$dob,$address,$city,$state,$zip,$phone,$extension,$cellphone,$ethnicity,$gender,$email,$title,$contract,$classification,$location,$grade,$subject,$doh,$senioritydate,$effectivedate,$step,$educationlevel,$contractdays,$salary,$hours,$probationreportdate,$statebackgroundcheck,$federalbackgroundcheck,$stateeducatorid,$l1_1,$l1_2,$l1_3,$l1_4,$l2_1,$l2_2,$l2_3,$l2_4,$l3_1,$l3_2,$l3_3,$l3_4,$l4_1,$l4_2,$l4_3,$l4_4,$l5_1,$l5_2,$l5_3,$l5_4,$l6_1,$l6_2,$l6_3,$l6_4];
			fputcsv($output, $data);
		}
		fclose($output);
		mysqli_close($db);
		exit();
	}
?>