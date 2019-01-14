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

	require_once(dirname(__FILE__) . '/core/abre_session.php');

	//Start PHP session if one does not exist
	if(session_id() == '') {
		session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
		session_start();
	}

	function url(){
		return sprintf(
			"%s://%s%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
				$_SERVER['SERVER_NAME'],
				$_SERVER['REQUEST_URI']
		);
	}

	//Check for configuration file and run installer if one does not exist
	if (file_exists('configuration.php') || getenv("USE_GOOGLE_CLOUD") == "true")
	{

		//Load configuration file, google authentication, and site header
		if(getenv("USE_GOOGLE_CLOUD") != "true"){
			require_once('configuration.php');
		}

		$url = url();
		$urlParsed = parse_url($url);
		$tenantUrl = $urlParsed['host'];

		include('core/abre_dbconnect.php');
		$sql = "SELECT siteID FROM abre_tenant_map WHERE domain = '$tenantUrl'";
		$query = $db->query($sql);
		$result = $query->fetch_assoc();
		if($result['siteID'] == ""){
			exit();
		}else{
			$_SESSION['siteID'] = $result['siteID'];
			require_once('core/abre_functions.php');
		}

		//Look for redirected urls
		if(isset($_GET["url"]))
		{
			$_SESSION["redirecturl"]=htmlspecialchars($_GET["url"]);
		}

		//If there is a session redirect, load the redirect url
		if(isset($_GET["url"]) && isset($_SESSION['useremail']))
		{
			$_SESSION["redirecturl"]=htmlspecialchars($_GET["url"]);
			session_write_close();
			$portal_root = getConfigPortalRoot();
			header("Location: $portal_root/#".$_SESSION["redirecturl"]);
			exit();
		}

		//Include Google Login
		require_once('core/abre_google_login.php');

		//Include Site Header
		require_once('core/abre_header.php');

		//Display login if user is not logged in
		if(isset($_GET["usertype"])){
			if($_GET["usertype"]=="Staff"){ require_once('core/abre_staff_login.php'); }
			if($_GET["usertype"]=="Student"){ require_once('core/abre_student_login.php'); }
			if($_GET["usertype"]=="Parent"){ $_SESSION['usertype'] = "parent"; require_once('core/abre_parent_login.php'); }
		}
		else if(isset($authUrl))
		{
			require_once('core/abre_login.php');
		}
		else
		{
			require_once('core/abre_load_modules.php');
			require_once('core/abre_layout_page.php');
		}

		//Display site close and footer
		require_once('core/abre_footer.php');
	}
	else
	{

		//Display the installer
		require('core/abre_installer.php');
		require_once('core/abre_footer.php');

	}

?>
