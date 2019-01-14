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

	//Verify admin
	if(superadmin()){
		//Retrieve last repo link and zip file
		$link = $_POST["link"];
		$linkfile = basename($link);
		$linkfile = substr($linkfile, 0, -4);
		file_put_contents("$portal_path_root/update.zip", file_get_contents($link));

		//Upzip the file
		$zip = new ZipArchive;
		if ($zip->open("$portal_path_root/update.zip") === TRUE) {
		    $zip->extractTo("$portal_path_root");
		    $zip->close();
		}

		//Copy all files/folders to update directory
		mkdir("$portal_path_root/update/");
		$src = "$portal_path_root/Abre-$linkfile/";
		$dst = "$portal_path_root/update/";

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

		//Make a copy
		function rcopy($src, $dst) {
	        if (file_exists ( $dst ))
	            rrmdir ( $dst );
	        if (is_dir ( $src )) {
	            mkdir ( $dst );
	            $files = scandir ( $src );
	            foreach ( $files as $file )
	                if ($file != "." && $file != "..")
	                    rcopy ( "$src/$file", "$dst/$file" );
	        } else if (file_exists ( $src ))
	            copy ( $src, $dst );
    	}
    	rcopy($src,$dst);

    //Delete the zip file and extract directory
		function deleteDirectory($dir) {
		    if(!file_exists($dir)){
		        return true;
		    }
		    if(!is_dir($dir)){
		        return unlink($dir);
		    }
		    foreach(scandir($dir) as $item){
		        if($item == '.' || $item == '..'){
		            continue;
		        }
		        if(!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)){
		            return false;
		        }
		    }
		    return rmdir($dir);
		}

		//Delete zipped files
  	deleteDirectory("$portal_path_root/Abre-$linkfile/");
  	unlink("$portal_path_root/update.zip");

		//Replace settings module
		rcopy("$portal_path_root/update/modules/settings/","$portal_path_root/modules/settings/");

		//Replace core modules
		rcopy("$portal_path_root/update/modules/apps/","$portal_path_root/modules/apps/");
		rcopy("$portal_path_root/update/modules/calendar/","$portal_path_root/modules/calendar/");
		rcopy("$portal_path_root/update/modules/classroom/","$portal_path_root/modules/classroom/");
		rcopy("$portal_path_root/update/modules/directory/","$portal_path_root/modules/directory/");
		rcopy("$portal_path_root/update/modules/drive/","$portal_path_root/modules/drive/");
		rcopy("$portal_path_root/update/modules/mail/","$portal_path_root/modules/mail/");
		rcopy("$portal_path_root/update/modules/profile/","$portal_path_root/modules/profile/");
		rcopy("$portal_path_root/update/modules/stream/","$portal_path_root/modules/stream/");
		rcopy("$portal_path_root/update/modules/modules/","$portal_path_root/modules/modules/");
		rcopy("$portal_path_root/update/core/","$portal_path_root/core/");

		//check for/replace api folder and Abre apps
		if(!file_exists("$portal_path_root/api/")){ mkdir("$portal_path_root/api/"); }
		rcopy("$portal_path_root/update/api/", "$portal_path_root/api/");

		if(!file_exists("$portal_path_root/modules/Abre-Assessments")){ mkdir("$portal_path_root/modules/Abre-Assessments"); }
		rcopy("$portal_path_root/update/modules/Abre-Assessments", "$portal_path_root/modules/Abre-Assessments");

		if(!file_exists("$portal_path_root/modules/Abre-Books")){ mkdir("$portal_path_root/modules/Abre-Books"); }
		rcopy("$portal_path_root/update/modules/Abre-Books", "$portal_path_root/modules/Abre-Books");

		if(!file_exists("$portal_path_root/modules/Abre-Conduct")){ mkdir("$portal_path_root/modules/Abre-Conduct"); }
		rcopy("$portal_path_root/update/modules/Abre-Conduct", "$portal_path_root/modules/Abre-Conduct");

		if(!file_exists("$portal_path_root/modules/Abre-Curriculum")){ mkdir("$portal_path_root/modules/Abre-Curriculum"); }
		rcopy("$portal_path_root/update/modules/Abre-Curriculum", "$portal_path_root/modules/Abre-Curriculum");

		if(!file_exists("$portal_path_root/modules/Abre-Forms")){ mkdir("$portal_path_root/modules/Abre-Forms"); }
		rcopy("$portal_path_root/update/modules/Abre-Forms", "$portal_path_root/modules/Abre-Forms");

		if(!file_exists("$portal_path_root/modules/Abre-Guided-Learning")){ mkdir("$portal_path_root/modules/Abre-Guided-Learning"); }
		rcopy("$portal_path_root/update/modules/Abre-Guided-Learning", "$portal_path_root/modules/Abre-Guided-Learning");

		if(!file_exists("$portal_path_root/modules/Abre-Students")){ mkdir("$portal_path_root/modules/Abre-Students"); }
		rcopy("$portal_path_root/update/modules/Abre-Students", "$portal_path_root/modules/Abre-Students");

		//Replace core files
		copy("$portal_path_root/update/configuration-sample.php", "$portal_path_root/configuration-sample.php");
		copy("$portal_path_root/update/README.md", "$portal_path_root/README.md");
		copy("$portal_path_root/update/index.php", "$portal_path_root/index.php");

		//Create content folder if one doesn't exist
		if(!file_exists("$portal_path_root/content/")){ mkdir("$portal_path_root/content/"); }

		$sql = "UPDATE apps_abre SET installed = 0 WHERE siteID = '".$_SESSION['siteID']."'";
		$db->multi_query($sql);

		$sql = "UPDATE settings SET update_required = 1 WHERE siteID = '".$_SESSION['siteID']."'";
		$db->multi_query($sql);

		$db->close();

		//Delete the update directory
		deleteDirectory("$portal_path_root/update/");
	}

?>