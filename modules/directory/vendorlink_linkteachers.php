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

	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

	$VendorLinkURL = getSiteVendorLinkUrl();
	$json = vendorLinkGet("$VendorLinkURL/GBService/HA/teacher");

	//Retrieve employee information from database
	foreach($json as $key => $result){
		foreach($result as $key => $result){
			$employeeTeacherID = encrypt($result[TeacherID]);
			$staffrefid = $result[ExternalRefId];
			$json2 = vendorLinkGet("$VendorLinkURL/SisService/Staff?staffPersonalRefId=$staffrefid");
			foreach($json2 as $key => $result){
				foreach ($result as $key => $result){
					$employeeemail = $result[EmailList][0][Value];
					$employeeemailencrypted = encrypt($employeeemail, "");
					$employeeRefID = encrypt($result[RefId]);
					$employeeStateID = encrypt($result[StateProvinceId]);
					$employeeLocalId = encrypt($result[LocalId]);

					//Add information to employee database
					$stmt = $db->stmt_init();
					$sql = "UPDATE directory SET RefID = ?, StateID = ?, TeacherID = ?, LocalId = ? WHERE email = ? AND siteID = ?";
					$stmt->prepare($sql);
					$stmt->bind_param("sssssi", $employeeRefID, $employeeStateID, $employeeTeacherID, $employeeLocalId, $employeeemailencrypted, $_SESSION['siteID']);
					$stmt->execute();
					$stmt->close();
					$db->close();
				}
			}
		}
	}
?>