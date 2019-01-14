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

	//Delete a folder
	function rrmdir($dir){
		if(is_dir($dir)){
			$files = scandir($dir);
			foreach($files as $file){
				if($file != "." && $file != ".."){
					rrmdir("$dir/$file");
				}
			}
			rmdir($dir);
		}else if(file_exists($dir)){
			unlink($dir);
		}
	}

	//Verify superadmin
	$sql = "SELECT * FROM users WHERE email = '".$_SESSION['escapedemail']."' AND superadmin = 1";
	$result = $db->query($sql);
	while($row = $result->fetch_assoc()){

		//Retrieve last repo link and zip file
		$link = $_POST["link"];
		$repo = $_POST["repo"];

		if(($pos = strpos($repo, '/')) !== false){
		   $repo = substr($repo, $pos + 1);
		}

		$linkfile = basename($link);
		$linkfile = substr($linkfile, 0, -4);
		file_put_contents("$portal_path_root/modules/$repo.zip", file_get_contents($link));

		//Upzip the file
		$zip = new ZipArchive;
		if ($zip->open("$portal_path_root/modules/$repo.zip") === TRUE) {
		    $zip->extractTo("$portal_path_root/modules/");
		    $zip->close();
		}

		//Delete old module
		rrmdir(realpath(dirname(__FILE__))."/../$repo");

		//Rename folder
		rename(realpath(dirname(__FILE__))."/../$repo-$linkfile",realpath(dirname(__FILE__))."/../$repo");

		//Delete zipped files
    unlink("$portal_path_root/modules/$repo.zip");
	}
?>