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

	if(superadmin() && !isAppInstalled("directory")){

		//Setup tables if new module
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$resultstreams = $db->query("SELECT * FROM directory LIMIT 1")){
			$sql = "CREATE TABLE `directory` (
			  `id` int(11) NOT NULL,
			  `updatedtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `superadmin` int(11) NOT NULL,
			  `admin` int(11) NOT NULL,
			  `archived` int(11) NOT NULL,
			  `picture` text NOT NULL,
			  `firstname` text NOT NULL,
			  `lastname` text NOT NULL,
			  `middlename` text NOT NULL,
			  `title` text NOT NULL,
			  `contract` text NOT NULL,
			  `address` text NOT NULL,
			  `city` text NOT NULL,
			  `state` text NOT NULL,
			  `zip` text NOT NULL,
			  `email` text NOT NULL,
			  `phone` text NOT NULL,
			  `cellphone` text NOT NULL,
			  `ss` text NOT NULL,
			  `dob` text NOT NULL,
			  `gender` text NOT NULL,
			  `ethnicity` text NOT NULL,
			  `classification` text NOT NULL,
			  `location` text NOT NULL,
			  `grade` text NOT NULL,
			  `subject` text NOT NULL,
			  `doh` text NOT NULL,
			  `senioritydate` text NOT NULL,
			  `effectivedate` text NOT NULL,
			  `rategroup` text NOT NULL,
			  `step` text NOT NULL,
			  `educationlevel` text NOT NULL,
			  `salary` text NOT NULL,
			  `hours` text NOT NULL,
			  `stateeducatorid` text NOT NULL,
			  `licensetype1` text NOT NULL,
			  `licenseissuedate1` text NOT NULL,
			  `licenseexpirationdate1` text NOT NULL,
			  `licenseterm1` text NOT NULL,
			  `licensetype2` text NOT NULL,
			  `licenseissuedate2` text NOT NULL,
			  `licenseexpirationdate2` text NOT NULL,
			  `licenseterm2` text NOT NULL,
			  `licensetype3` text NOT NULL,
			  `licenseissuedate3` text NOT NULL,
			  `licenseexpirationdate3` text NOT NULL,
			  `licenseterm3` text NOT NULL,
			  `licensetype4` text NOT NULL,
			  `licenseissuedate4` text NOT NULL,
			  `licenseexpirationdate4` text NOT NULL,
			  `licenseterm4` text NOT NULL,
			  `licensetype5` text NOT NULL,
			  `licenseissuedate5` text NOT NULL,
			  `licenseexpirationdate5` text NOT NULL,
			  `licenseterm5` text NOT NULL,
			  `licensetype6` text NOT NULL,
			  `licenseissuedate6` text NOT NULL,
			  `licenseexpirationdate6` text NOT NULL,
			  `licenseterm6` text NOT NULL,
			  `probationreportdate` text NOT NULL,
			  `statebackgroundcheck` text NOT NULL,
			  `federalbackgroundcheck` text NOT NULL,
			  `permissions` text NOT NULL,
			  `contractdays` text NOT NULL,
			  `RefID` text NOT NULL,
			  `StateID` text NOT NULL,
			  `TeacherID` text NOT NULL,
			  `LocalId` text NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
					$sql .= "ALTER TABLE `directory`
			  ADD PRIMARY KEY (`id`);";
			  		$sql .= "ALTER TABLE `directory`
			  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			  		if ($db->multi_query($sql) === TRUE) { }
		}
		$db->close();

		//Check for Role field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$resultstreams = $db->query("SELECT role FROM directory LIMIT 1")){
			$sql = "ALTER TABLE `directory` ADD `role` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for Phone Extension field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$resultstreams = $db->query("SELECT extension FROM directory LIMIT 1")){
			$sql = "ALTER TABLE `directory` ADD `extension` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT siteID FROM directory LIMIT 1")){
			$sql = "ALTER TABLE `directory` ADD `siteID` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$resultstreams = $db->query("SELECT * FROM directory_discipline LIMIT 1")){
			$sql = "CREATE TABLE IF NOT EXISTS `directory_discipline` (
				`id` int(11) NOT NULL AUTO_INCREMENT,`archived` int(11) NOT NULL,
				`UserID` int(11) NOT NULL,
				`Filename` text NOT NULL,
				PRIMARY KEY (`id`))
				ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15;";
			if ($db->multi_query($sql) === TRUE) { }
		}
		$db->close();

		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT siteID FROM directory_discipline LIMIT 1")){
			$sql = "ALTER TABLE `directory_discipline` ADD `siteID` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for directory settings table
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT * FROM directory_settings LIMIT 1")){
			$sql = "CREATE TABLE `directory_settings` (`id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$sql .= "ALTER TABLE `directory_settings` ADD PRIMARY KEY (`id`);";
			$sql .= "ALTER TABLE `directory_settings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for dropdown title field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT dropdownID FROM directory_settings LIMIT 1")){
			$sql = "ALTER TABLE `directory_settings` ADD `dropdownID` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Check for options field
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT options FROM directory_settings LIMIT 1")){
			$sql = "ALTER TABLE `directory_settings` ADD `options` text NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		if(!$db->query("SELECT siteID FROM directory_settings LIMIT 1")){
			$sql = "ALTER TABLE `directory_settings` ADD `siteID` int(11) NOT NULL;";
			$db->multi_query($sql);
		}
		$db->close();

		//Mark app as installed
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		$sql = "UPDATE apps_abre SET installed = 1 WHERE app = 'directory' AND siteID = '".$_SESSION['siteID']."'";
		$db->multi_query($sql);
		$db->close();
	}
?>