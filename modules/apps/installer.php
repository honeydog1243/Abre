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

	if(superadmin() && !isAppInstalled("apps")){
		//Check for apps table
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT * FROM apps LIMIT 1")){
			$sql = "CREATE TABLE `apps` (`id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$sql .= "ALTER TABLE `apps` ADD PRIMARY KEY (`id`);";
			$sql .= "ALTER TABLE `apps` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for icon field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT icon FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `icon` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for student field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT student FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `student` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for staff field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT staff FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `staff` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for title field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT title FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `title` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for image field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT image FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `image` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for link field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT link FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `link` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for required field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT required FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `required` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for sort field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT sort FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `sort` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for parent field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT parent FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `parent` int(11) NOT NULL DEFAULT '0';";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for staff building restriction field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT staff_building_restrictions FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `staff_building_restrictions` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for student building restriction field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT student_building_restrictions FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `student_building_restrictions` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT siteID FROM apps LIMIT 1")){
			$sql = "ALTER TABLE `apps` ADD `siteID` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Mark app as installed
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		$sql = "UPDATE apps_abre SET installed = 1 WHERE app = 'apps' AND siteID = '".$_SESSION['siteID']."'";
		$db->multi_query($sql);
		$db->close();
	}
?>