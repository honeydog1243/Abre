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
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require(dirname(__FILE__) . '/../../core/abre_version.php');

	//Streams
	$sql = "SELECT * FROM users WHERE email = '".$_SESSION['escapedemail']."' and superadmin = 1";
	$result = $db->query($sql);
	while($row = $result->fetch_assoc()){
		echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
			echo "<div class='page'>";
				echo "<table><thead><tr><th>Name</th></tr></thead>";
				echo "<tbody>";
					$modules = array();
					$modulecount = 0;
					$moduledirectory = dirname(__FILE__) . '/../../modules';
					$modulefolders = scandir($moduledirectory);
					foreach($modulefolders as $result){
						echo $result;
						$result;
					}
				echo "</tbody>";
				echo "</table>";
			echo "</div>";
		echo "</div>";
	}

?>