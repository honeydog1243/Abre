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

	if(!headers_sent()){

		require_once(dirname(__FILE__) . '/abre_session.php');
		//Start PHP session if not loaded
		if(session_id() == '') {
			session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
			session_start();
		}

		//Include required files
		require_once(dirname(__FILE__) . '/../core/abre_functions.php');

		function siteID(){
			$url = sprintf(
				"%s://%s%s",
				isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
					$_SERVER['SERVER_NAME'],
					$_SERVER['REQUEST_URI']
			);
			$urlParsed = parse_url($url);
			$tenantUrl = $urlParsed['host'];
	
			include('abre_dbconnect.php');
			$sql = "SELECT siteID FROM abre_tenant_map WHERE domain = '$tenantUrl'";
			$query = $db->query($sql);
			$result = $query->fetch_assoc();
			if($result['siteID'] == ""){
				$db->close();
				return null;
			}else{
				$db->close();
				return $result['siteID'];
			}			
		}

		if(!(isset($_SESSION['siteID']) && $_SESSION['siteID'] != "")){
			$_SESSION['siteID'] = siteID();
			if (is_null($_SESSION['siteID'])) exit();
		}

		$cookie_name = getConfigPortalCookieName();

		//Require login script if there is a cookie but not session
		if (!is_null($cookie_name)){
			if(isset($_COOKIE[$cookie_name]) && !isset($_SESSION['access_token'])){
				require_once 'abre_google_login.php';
				exit();
			}
		}

		//Check to make sure they are logged in
		if(!(isset($_SESSION['usertype']) && $_SESSION['usertype'] != "")){
			echo "<script>";
			echo "window.location.href='/core/abre_signout.php';";
			echo "</script>";
			exit();
		}
	}
?>