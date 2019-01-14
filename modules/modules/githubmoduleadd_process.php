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
	$portal_path_root = getConfigPortalPathRoot();

	//Verify superadmin
	if(superadmin()){

		//Retrieve the repo
		$repoaddress = $_POST["repoaddress"];
		$repoaddress = str_replace('https://github.com/', '', $repoaddress);
		$project = strstr($repoaddress, '/');

		$opts = ['http' => ['method' => 'GET','header' => ['User-Agent: PHP']]];
		$context = stream_context_create($opts);
		$content = file_get_contents("https://api.github.com/repos/$repoaddress/releases/latest", false, $context);
		$json = json_decode($content, true);
		$currentversion = $json['name'];

		$currentlink = "https://github.com/$repoaddress/archive/".$currentversion.".zip";
		file_put_contents("$portal_path_root/modules/$project.zip", file_get_contents($currentlink));

		//Upzip the file
		$zip = new ZipArchive;
		if ($zip->open("$portal_path_root/modules/$project.zip") === TRUE) {
			$zip->extractTo("$portal_path_root/modules/");
			$zip->close();
		}

		//Rename folder
		rename(realpath(dirname(__FILE__))."/../$project-$currentversion",realpath(dirname(__FILE__))."/../$project");

		//Delete zipped files
    unlink("$portal_path_root/modules/$project.zip");

		$app = str_replace("/", "", $project);
		$sql = "SELECT COUNT(*) FROM apps_abre WHERE app = '$app' AND siteID = '".$_SESSION['siteID']."'";
		$query = $db->query($sql);
		$result = $query->fetch_assoc();
		$count = $result["COUNT(*)"];

		$active = 1;
		$installed = 0;
		if($count == 0){
			$stmt = $db->stmt_init();
			$insertSql = "INSERT INTO apps_abre (app, active, installed, siteID) VALUES (?, ?, ?, ?)";
			$stmt->prepare($insertSql);
			$stmt->bind_param("siii", $app, $active, $installed, $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
		}else{
			$stmt = $db->stmt_init();
			$insertSql = "UPDATE apps_abre SET active = ?, installed = ? WHERE app = ? AND siteID = ?";
			$stmt->prepare($insertSql);
			$stmt->bind_param("iisi", $active, $installed, $app, $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();
		}
		$db->close();

		echo "Module Added";
	}
?>