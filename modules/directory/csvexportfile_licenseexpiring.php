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
		header('Content-Disposition: attachment; filename=licenseExpiring.csv');

		$output = fopen('php://output', 'w');

		fputcsv($output, array('Last Name', 'First Name', 'Middle Name', 'State Educator ID', 'License 1 Type', 'License 1 Expiration Date', 'License 2 Type', 'License 2 Expiration Date', 'License 3 Type', 'License 3 Expiration Date', 'License 4 Type', 'License 4 Expiration Date', 'License 5 Type', 'License 5 Expiration Date', 'License 6 Type', 'License 6 Expiration Date'));
		include "../../core/abre_dbconnect.php";
		$rows = mysqli_query($db, "SELECT firstname, lastname, middlename, stateeducatorid, licensetype1, licenseexpirationdate1, licensetype2, licenseexpirationdate2, licensetype3, licenseexpirationdate3, licensetype4, licenseexpirationdate4, licensetype5, licenseexpirationdate5, licensetype6, licenseexpirationdate6 FROM directory WHERE archived = 0 AND siteID = '".$_SESSION['siteID']."' ORDER BY lastname");

		while($row = mysqli_fetch_assoc($rows)){
			$firstname = $row["firstname"];
			$firstname = stripslashes($firstname);
			$lastname = $row["lastname"];
			$lastname = stripslashes($lastname);
			$middlename = $row["middlename"];
			$middlename = stripslashes($middlename);
			$stateeducatorid = $row["stateeducatorid"];
			$stateeducatorid = stripslashes(decrypt($stateeducatorid, "", $decryptionKey));
			$l1_1 = $row["licensetype1"];
			$l1_1 = stripslashes(decrypt($l1_1, "", $decryptionKey));
			$l1_3 = $row["licenseexpirationdate1"];
			$l1_3 = stripslashes(decrypt($l1_3, "", $decryptionKey));
			$l2_1 = $row["licensetype2"];
			$l2_1 = stripslashes(decrypt($l2_1, "", $decryptionKey));
			$l2_3 = $row["licenseexpirationdate2"];
			$l2_3 = stripslashes(decrypt($l2_3, "", $decryptionKey));
			$l3_1 = $row["licensetype3"];
			$l3_1 = stripslashes(decrypt($l3_1, "", $decryptionKey));
			$l3_3 = $row["licenseexpirationdate3"];
			$l3_3 = stripslashes(decrypt($l3_3, "", $decryptionKey));
			$l4_1 = $row["licensetype4"];
			$l4_1 = stripslashes(decrypt($l4_1, "", $decryptionKey));
			$l4_3 = $row["licenseexpirationdate4"];
			$l4_3 = stripslashes(decrypt($l4_3, "", $decryptionKey));
			$l5_1 = $row["licensetype5"];
			$l5_1 = stripslashes(decrypt($l5_1, "", $decryptionKey));
			$l5_3 = $row["licenseexpirationdate5"];
			$l5_3 = stripslashes(decrypt($l5_3, "", $decryptionKey));
			$l6_1 = $row["licensetype6"];
			$l6_1 = stripslashes(decrypt($l6_1, "", $decryptionKey));
			$l6_3 = $row["licenseexpirationdate6"];
			$l6_3 = stripslashes(decrypt($l6_3, "", $decryptionKey));
			$data = [$lastname,$firstname,$middlename,$stateeducatorid,$l1_1,$l1_3,$l2_1,$l2_3,$l3_1,$l3_3,$l4_1,$l4_3,$l5_1,$l5_3,$l6_1,$l6_3];
			fputcsv($output, $data);
		}
		fclose($output);
		mysqli_close($db);
		exit();
	}
?>