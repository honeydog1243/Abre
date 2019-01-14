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

	if(superadmin() && !isAppInstalled("settings")){

		//Ping Update
		require(dirname(__FILE__) . '/../../core/abre_ping.php');

		//Check for users_parents table
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT * FROM users_parent LIMIT 1")){
			 $sql = "CREATE TABLE `users_parent` (`id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			 $sql .= "ALTER TABLE `users_parent` ADD PRIMARY KEY (`id`);";
			 $sql .= "ALTER TABLE `users_parent` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			 $db->multi_query($sql);
			 while ($db->next_result()) {;}
		}

		//check for user email field
		if(!$db->query("SELECT email FROM users_parent LIMIT 1")){
			$sql = "ALTER TABLE `users_parent` ADD `email` text NOT NULL;";
			$db->query($sql);
		}

		if(!$db->query("SELECT siteID FROM users_parent LIMIT 1")){
			$sql = "ALTER TABLE `users_parent` ADD `siteID` int(11) NOT NULL;";
			$db->query($sql);
		}

		//Check for parents_students table
		if(!$db->query("SELECT * FROM parent_students LIMIT 1")){
			 $sql = "CREATE TABLE `parent_students` (`id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			 $sql .= "ALTER TABLE `parent_students` ADD PRIMARY KEY (`id`);";
			 $sql .= "ALTER TABLE `parent_students` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			 $db->multi_query($sql);
			 while ($db->next_result()) {;}
		}

		//check for parent_ID field
		if(!$db->query("SELECT parent_id FROM parent_students LIMIT 1")){
			$sql = "ALTER TABLE `parent_students` ADD `parent_id` int(11) NOT NULL;";
			$sql .= "ALTER TABLE `parent_students` ADD FOREIGN KEY (`parent_id`) REFERENCES users_parent(`id`);";
			$db->multi_query($sql);
			while ($db->next_result()) {;}
		}

		//check for student token field
		if(!$db->query("SELECT student_token FROM parent_students LIMIT 1")){
			$sql = "ALTER TABLE `parent_students` ADD `student_token` text NOT NULL;";
			$db->query($sql);
		}

		//check for student id field
		if(!$db->query("SELECT studentId FROM parent_students LIMIT 1")){
			$sql = "ALTER TABLE `parent_students` ADD `studentId` text NOT NULL;";
			$db->query($sql);
		}

		if(!$db->query("SELECT siteID FROM parent_students LIMIT 1")){
			$sql = "ALTER TABLE `parent_students` ADD `siteID` int(11) NOT NULL;";
			$db->query($sql);
		}

		//Check for abre_tiny_upload table
		if(!$db->query("SELECT * FROM abre_tiny_upload LIMIT 1")){
			$db->query("CREATE TABLE `abre_tiny_upload` (`ID` INT PRIMARY KEY AUTO_INCREMENT) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		}

		//Check for abre_tiny_upload.Orginal_File_Name column
		if(!$db->query("SELECT Original_File_Name FROM abre_tiny_upload LIMIT 1")){
			$db->query("ALTER TABLE `abre_tiny_upload` ADD `Original_File_Name` TEXT NOT NULL");
		}

		//Check for abre_tiny_upload.Content_Type column
		if(!$db->query("SELECT Content_Type FROM abre_tiny_upload LIMIT 1")){
			$db->query("ALTER TABLE `abre_tiny_upload` ADD `Content_Type` TEXT NOT NULL");
		}

		//Check for abre_tiny_upload.Stored_File column
		if(!$db->query("SELECT Stored_File FROM abre_tiny_upload LIMIT 1")){
			$db->query("ALTER TABLE `abre_tiny_upload` ADD `Stored_File` CHAR(36) NOT NULL");
		}

		//Check for abre_tiny_upload.siteID column
		if(!$db->query("SELECT siteID FROM abre_tiny_upload LIMIT 1")){
			$db->multi_query("ALTER TABLE `abre_tiny_upload` ADD `siteID` INT NOT NULL;
												ALTER TABLE `abre_tiny_upload` ADD FOREIGN KEY siteID(siteID) REFERENCES abre_tenant_map(siteID);");
			while ($db->next_result()) {;}
		}

		$cloudsetting = getenv("USE_GOOGLE_CLOUD");
		if($cloudsetting != "true"){
			$portal_private_root = getConfigPortalPrivateRoot();
			$portal_path_root = getConfigPortalPathRoot();
			if(!file_exists($portal_path_root . "/../$portal_private_root/tiny-upload/")){
				mkdir($portal_path_root . "/../$portal_private_root/tiny-upload/", 0775);
			}
		}

		//Check for abre_points table
		if(!$db->query("SELECT * FROM abre_points LIMIT 1")){
			$db->query("CREATE TABLE `abre_points` (`id` INT PRIMARY KEY AUTO_INCREMENT) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		}

		//Check for abre_points.site_id column
		if(!$db->query("SELECT site_id FROM abre_points LIMIT 1")){
			$db->multi_query("ALTER TABLE `abre_points` ADD `site_id` INT NOT NULL;
												ALTER TABLE `abre_points` ADD FOREIGN KEY site_id(site_id) REFERENCES abre_tenant_map(siteID);");
			while ($db->next_result()) {;}
		}

		//Check for abre_points.user column
		if(!$db->query("SELECT user FROM abre_points LIMIT 1")){
			$db->query("ALTER TABLE `abre_points` ADD `user` TEXT NOT NULL");
		}

		//Check for abre_points.source_app column
		if(!$db->query("SELECT source_app FROM abre_points LIMIT 1")){
			$db->query("ALTER TABLE `abre_points` ADD `source_app` VARCHAR(25) NOT NULL");
		}

		//Check for abre_points.source_item column
		if(!$db->query("SELECT source_item FROM abre_points LIMIT 1")){
			$db->query("ALTER TABLE `abre_points` ADD `source_item` VARCHAR(25) NOT NULL");
		}

		//Check for abre_points.source_id column
		if(!$db->query("SELECT source_id FROM abre_points LIMIT 1")){
			$db->query("ALTER TABLE `abre_points` ADD `source_id` INT NOT NULL");
		}

		//Check for abre_points.points column
		if(!$db->query("SELECT points FROM abre_points LIMIT 1")){
			$db->query("ALTER TABLE `abre_points` ADD `points` INT NOT NULL");
		}

		//Check for abre_points_listener table
		if(!$db->query("SELECT * FROM abre_points_listener LIMIT 1")){
			$db->query("CREATE TABLE `abre_points_listener` (`id` INT PRIMARY KEY AUTO_INCREMENT) ENGINE=InnoDB DEFAULT CHARSET=latin1");
		}

		//Check for abre_points_listener.site_id column
		if(!$db->query("SELECT site_id FROM abre_points_listener LIMIT 1")){
			$db->multi_query("ALTER TABLE `abre_points_listener` ADD `site_id` INT NOT NULL;
												ALTER TABLE `abre_points_listener` ADD FOREIGN KEY site_id(site_id) REFERENCES abre_tenant_map(siteID);");
			while ($db->next_result()) {;}
		}

		//Check for abre_points_listener.event_name column
		if(!$db->query("SELECT user FROM abre_points_listener LIMIT 1")){
			$db->query("ALTER TABLE `abre_points_listener` ADD `event_name` VARCHAR(50) NOT NULL");
		}

		//Check for abre_points_listener.parameters column
		if(!$db->query("SELECT user FROM abre_points_listener LIMIT 1")){
			$db->query("ALTER TABLE `abre_points_listener` ADD `parameters` TEXT NOT NULL");
		}

		//mark app as installed
		$sql = "UPDATE apps_abre SET installed = 1 WHERE app = 'settings' AND siteID = '".$_SESSION['siteID']."'";
		$db->query($sql);
		$db->close();
	}
?>
