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
	require_once('abre_functions.php');
	require_once('abre_google_authentication.php');
  $portal_root = getConfigPortalRoot();

  //Remove cookies and destroy session
	foreach($_COOKIE as $name => $value){
		setcookie($name, '', 1);
		setcookie($name, '', 1, '/');
	}
	session_destroy();
	$client->revokeToken();

  //Redirect user
	header("Location: $portal_root");

?>