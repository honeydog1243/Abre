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

	//Include required files
	require_once('abre_verification.php');
	require_once('abre_functions.php');

	//Send the feedback email
	if($_SESSION['usertype'] == "staff"){

		$cloudsetting = getenv("USE_GOOGLE_CLOUD");

		$to = $_POST["email-to"];
		$subject = $_POST["email-subject"];
		$body = $_POST["email-body"];
		if($body != ""){
			$message = "From: ".$_SESSION['useremail']."\r\n\r\n".$_SESSION['displayName']."\r\n\r\n$body";
			if ($cloudsetting=="true") {
				$email = new \SendGrid\Mail\Mail();
				$email->setFrom($_SESSION['useremail'], null);
				$email->setSubject($subject);
				$email->addTo($to, null);
				$email->addContent("text/html", $message);
				$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
				try {
					$response = $sendgrid->send($email);
					echo "Sent!";
				} catch (Exception $e) {
					//echo 'Caught exception: '. $e->getMessage() ."\n";
				}
			}
			else {
				$headers = "From: ". $_SESSION['useremail'];
				mail($to,$subject,$message,$headers);
				echo "Sent!";
			}

		}else{
			echo "Whoops, you didn't enter a message.";
		}
	}

?>
