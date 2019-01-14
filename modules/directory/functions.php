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
	require('permissions.php');
	require_once('../../core/abre_functions.php');

	//Send email to notifiy a new user has been added
	function emailPrediction($first, $last){
		sendSupportTicket($first, $last);
		return "";
	}

	function sendSupportTicket($first, $last){
		include "../../core/abre_dbconnect.php";
		$sql = "SELECT options FROM directory_settings WHERE dropdownID = 'supportTicket' AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		while($row = $result->fetch_assoc()){
			$email = $row["options"];
		}

		if($email != ''){
			$to = $email;
			$subject = "New User Account Needed for $first $last";
			$message = "Please create a new user account for:\n\n$first $last.";
			$headers = "From: noreply@abre.io";

			if ($cloudsetting=="true") {
				$email = new \SendGrid\Mail\Mail(); 
				$email->setFrom("noreply@abre.io", null);
				$email->setSubject($subject);
				$email->addTo($to, null);
				$email->addContent("text/plain", $message);
				$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				try {
					$response = $sendgrid->send($email);
				} catch (Exception $e) {
					//echo 'Caught exception: '. $e->getMessage() ."\n";
				}
			}
			else {
				mail($to, $subject, $message, $headers);
			}
		}
	}

?>