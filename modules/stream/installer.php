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

	if(superadmin() && !isAppInstalled("stream")){

		//Setup tables if new module
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$resultstreams = $db->query("SELECT * FROM streams LIMIT 1")){
			$sql = "CREATE TABLE `streams` (`id` int(11) NOT NULL,`group` text NOT NULL,`title` text NOT NULL,`slug` text NOT NULL,`type` text NOT NULL,`url` text NOT NULL,`required` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$sql .= "ALTER TABLE `streams` ADD PRIMARY KEY (`id`);";
			$sql .= "ALTER TABLE `streams` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			$db->multi_query($sql);
			while ($db->next_result()) {;}
		}

		//check for stream color column
		if(!$db->query("SELECT color FROM streams LIMIT 1")){
			$sql = "ALTER TABLE `streams` ADD `color` text NOT NULL;";
			$db->query($sql);
		}

		//Check for staff building restriction field
		if(!$db->query("SELECT staff_building_restrictions FROM streams LIMIT 1")){
			$sql = "ALTER TABLE `streams` ADD `staff_building_restrictions` text NOT NULL;";
			$db->query($sql);
		}

		//Check for student building restriction field
		if(!$db->query("SELECT student_building_restrictions FROM streams LIMIT 1")){
			$sql = "ALTER TABLE `streams` ADD `student_building_restrictions` text NOT NULL;";
			$db->query($sql);
		}

		if(!$db->query("SELECT siteID FROM streams LIMIT 1")){
			$sql = "ALTER TABLE `streams` ADD `siteID` int(11) NOT NULL;";
			$db->query($sql);
		}

		//Setup stream_posts table
		if(!$db->query("SELECT * FROM stream_posts LIMIT 1")){
			$sql = "CREATE TABLE `stream_posts` (`id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$sql .= "ALTER TABLE `stream_posts` ADD PRIMARY KEY (`id`);";
			$sql .= "ALTER TABLE `stream_posts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			$db->multi_query($sql);
			while ($db->next_result()) {;}
		}

		//Check for submission time field
		if(!$db->query("SELECT submission_time FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `submission_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;";
			$db->query($sql);
		}

		//Check for post author field
		if(!$db->query("SELECT post_author FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `post_author` text NOT NULL;";
			$db->query($sql);
		}

		//Check for author first name field
		if(!$db->query("SELECT author_firstname FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `author_firstname` text NOT NULL;";
			$db->query($sql);
		}

		//Check for author last name field
		if(!$db->query("SELECT author_lastname FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `author_lastname` text NOT NULL;";
			$db->query($sql);
		}

		//Check for post groups field
		if(!$db->query("SELECT post_groups FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `post_groups` text NOT NULL;";
			$db->query($sql);
		}

		//Check for post title field
		if(!$db->query("SELECT post_title FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `post_title` text NOT NULL;";
			$db->query($sql);
		}

		//Check for post stream field
		if(!$db->query("SELECT post_stream FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `post_stream` text NOT NULL;";
			$db->query($sql);
		}

		//Check for post content field
		if(!$db->query("SELECT post_content FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `post_content` text NOT NULL;";
			$db->query($sql);
		}

		//Check for post image field
		if(!$db->query("SELECT post_image FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `post_image` text NOT NULL;";
			$db->query($sql);
		}

		//Check for color field
		if(!$db->query("SELECT color FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `color` text NOT NULL;";
			$db->query($sql);
		}

		//Check for staff building restriction field
		if(!$db->query("SELECT staff_building_restrictions FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `staff_building_restrictions` text NOT NULL;";
			$db->query($sql);
		}

		//Check for student building restriction field
		if(!$db->query("SELECT student_building_restrictions FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `student_building_restrictions` text NOT NULL;";
			$db->query($sql);
		}

		//Check for last_modified_time field
		if(!$db->query("SELECT last_modified_time FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `last_modified_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;";
			$db->query($sql);
		}

		//Check for siteID field
		if(!$db->query("SELECT siteID FROM stream_posts LIMIT 1")){
			$sql = "ALTER TABLE `stream_posts` ADD `siteID` int(11) NOT NULL;";
			$db->query($sql);
		}

		//Setup tables if new module
		if(!$resultstreamscomments = $db->query("SELECT * FROM streams_comments LIMIT 1")){
			$sql = "CREATE TABLE `streams_comments` (`id` int(11) NOT NULL AUTO_INCREMENT,`url` text NOT NULL,`title` text NOT NULL,`image` text NOT NULL,`user` text NOT NULL,`comment` text NOT NULL,`creationtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,`liked` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
			$db->multi_query($sql);
			while ($db->next_result()) {;}
		}

		//check for excerpt field
		if(!$db->query("SELECT excerpt FROM streams_comments LIMIT 1")){
			$sql = "ALTER TABLE `streams_comments` ADD `excerpt` text NOT NULL;";
			$db->query($sql);
		}

		//check for siteID field
		if(!$db->query("SELECT siteID FROM streams_comments LIMIT 1")){
			$sql = "ALTER TABLE `streams_comments` ADD `siteID` int(11) NOT NULL;";
			$db->query($sql);
		}

		//check for widgets_order column
		if(!$db->query("SELECT widgets_order FROM profiles LIMIT 1")){
			$sql = "ALTER TABLE `profiles` ADD `widgets_order` text NOT NULL;";
			$db->query($sql);
		}

		//check for widgets_hidden column
		if(!$db->query("SELECT widgets_hidden FROM profiles LIMIT 1")){
			$sql = "ALTER TABLE `profiles` ADD `widgets_hidden` text NOT NULL;";
			$db->query($sql);
		}

		//check for widgets_open column
		if(!$db->query("SELECT widgets_open FROM profiles LIMIT 1")){
			$sql = "ALTER TABLE `profiles` ADD `widgets_open` text NOT NULL;";
			$db->query($sql);
		}

		//check for siteID field
		if(!$db->query("SELECT siteID FROM profiles LIMIT 1")){
			$sql = "ALTER TABLE `profiles` ADD `siteID` int(11) NOT NULL;";
			$db->query($sql);
		}

		//mark app as installed
		$sql = "UPDATE apps_abre SET installed = 1 WHERE app = 'stream' AND siteID = '".$_SESSION['siteID']."'";
		$db->query($sql);
		$db->close();
	}
?>
