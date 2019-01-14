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

	//Connect to the database
	if(getenv("USE_GOOGLE_CLOUD") == "true"){
		$db_host = getenv("DB_HOST");
		$db_user = getenv("DB_USER");
		$db_password = getenv("DB_PASSWORD");
		$db_name = getenv("DB_NAME");
		$db_socket = getenv("DB_SOCKET");

		$cloudsetting = getenv("USE_GOOGLE_CLOUD");
	}else{
		require_once(dirname(__FILE__) . '/../configuration.php');
		$db_host = constant("DB_HOST");
		$db_user = constant("DB_USER");
		$db_password = constant("DB_PASSWORD");
		$db_name = constant("DB_NAME");
		$db_socket = constant("DB_SOCKET");

		$cloudsetting = constant("USE_GOOGLE_CLOUD");
	}
	if ($cloudsetting=="true")
		$db = new mysqli($db_host, $db_user, $db_password, $db_name, null, $db_socket);
	else
		$db = new mysqli($db_host, $db_user, $db_password, $db_name);
?>