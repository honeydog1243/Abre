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

	if(superadmin() && !isAppInstalled("profile")){

    //Setup tables if new module
    require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
    if(!$resultprofile = $db->query("SELECT * FROM profiles LIMIT 1")){
      $sql = "CREATE TABLE `profiles` (`id` int(11) NOT NULL,`email` text NOT NULL,`startup` int(11) NOT NULL DEFAULT '1',`streams` text NOT NULL,`apps_order` text NOT NULL,`work_calendar` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
      $sql .= "ALTER TABLE `profiles` ADD PRIMARY KEY (`id`);";
      $sql .= "ALTER TABLE `profiles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
      $sql .= "INSERT INTO `profiles` (`id`, `email`, `startup`, `streams`, `apps_order`, `work_calendar`, siteID) VALUES (NULL, '".$_SESSION['escapedemail']."', '', '', '".$_SESSION['siteID']."');";
      if ($db->multi_query($sql) === TRUE) { }
    }
    $db->close();

		//Setup headlines table
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT * FROM headlines LIMIT 1")){
			$sql = "CREATE TABLE `headlines` (`id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$sql .= "ALTER TABLE `headlines` ADD PRIMARY KEY (`id`);";
			$sql .= "ALTER TABLE `headlines` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			if ($db->multi_query($sql) === TRUE) { }
		}
		$db->close();

		//Check for owner field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT owner FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `owner` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for submission time field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT submission_time FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `submission_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for title field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT title FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `title` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for content field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT content FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `content` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for purpose field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT purpose FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `purpose` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for form_id field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT form_id FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `form_id` int(11);";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for video_id field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT video_id FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `video_id` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for groups field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT groups FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `groups` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for required field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT date_restriction FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `date_restriction` int(11) NOT NULL DEFAULT 0;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for start date field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT start_date FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `start_date` date DEFAULT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for end date field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT end_date FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `end_date` date DEFAULT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for required field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT required FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `required` int(11);";
			$db->multi_query($sql);
		}
		$db->close();

		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT siteID FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headlines` ADD `siteID` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for headline_responses table
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT * FROM headline_responses LIMIT 1")){
			$sql = "CREATE TABLE `headline_responses` (`response_id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$sql .= "ALTER TABLE `headline_responses` ADD PRIMARY KEY (`response_id`);";
			$sql .= "ALTER TABLE `headline_responses` MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for headline_id field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT headline_id FROM headline_responses LIMIT 1")){
			$sql = "ALTER TABLE `headline_responses` ADD `headline_id` int(11) NOT NULL;";
			$sql .= "ALTER TABLE `headline_responses` ADD FOREIGN KEY (`headline_id`) REFERENCES headlines(`id`);";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for email field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT email FROM headlines LIMIT 1")){
			$sql = "ALTER TABLE `headline_responses` ADD `email` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for submission time field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT submission_time FROM headline_responses LIMIT 1")){
			$sql = "ALTER TABLE `headline_responses` ADD `submission_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;";
			$db->multi_query($sql);
		}
		$db->close();

		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT siteID FROM headline_responses LIMIT 1")){
			$sql = "ALTER TABLE `headline_responses` ADD `siteID` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Mark app as installed
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		$sql = "UPDATE apps_abre SET installed = 1 WHERE app = 'profile' AND siteID = '".$_SESSION['siteID']."'";
		$db->multi_query($sql);
		$db->close();
	}

?>