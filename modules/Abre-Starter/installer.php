<?php

	/*
	* Copyright (C) 2016-2017 Abre.io LLC
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

	if(superadmin() && !isAppInstalled("Abre-Starter")){

		//Check for guide_boards table
		// require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		// if(!$db->query("SELECT * FROM guide_boards LIMIT 1"))
		// {
		// 	$sql = "CREATE TABLE `guide_boards` (`ID` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		// 	$sql .= "ALTER TABLE `guide_boards` ADD PRIMARY KEY (`ID`);";
		// 	$sql .= "ALTER TABLE `guide_boards` MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;";
		// 	$db->multi_query($sql);
		// }
		// $db->close();

		// require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		// if(!$db->query("SELECT Title FROM guide_boards LIMIT 1"))
		// {
		// 	$sql = "ALTER TABLE `guide_boards` ADD `Title` text NOT NULL;";
		// 	$db->multi_query($sql);
		// }
		// $db->close();

		//Write the Setup File
		//Mark app as installed
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		$sql = "UPDATE apps_abre SET installed = 1 WHERE app = 'Abre-Starter' AND siteID = '".$_SESSION['siteID']."'";
		$db->multi_query($sql);
		$db->close();

	}
?>