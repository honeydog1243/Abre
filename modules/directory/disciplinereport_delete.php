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
	require_once('permissions.php');
	$portal_private_root = getConfigPortalPrivateRoot();

	$cloudsetting = getenv("USE_GOOGLE_CLOUD");
	if ($cloudsetting=="true")
		require(dirname(__FILE__). '/../../vendor/autoload.php');
	use Google\Cloud\Storage\StorageClient;

	//Delete the User
	if($pageaccess == 1){

		$id = $_POST["id"];

		//Delete the File
		include "../../core/abre_dbconnect.php";
		$sql = "SELECT Filename FROM directory_discipline WHERE id = $id AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		while($row = $result->fetch_assoc()){
			$filename = htmlspecialchars($row["Filename"], ENT_QUOTES);
			if($filename != ""){
				if ($cloudsetting=="true") {
					$storage = new StorageClient([
						'projectId' => getenv("GC_PROJECT")
					]);
					$bucket = $storage->bucket(getenv("GC_BUCKET"));

					$cloud_dir = "private_html/directory/discipline/" . $filename;
					if($bucket->object($cloud_dir)->exists()){
						$object = $bucket->object($cloud_dir);
						$object->delete();
					}
				}
				else {
					$filename = dirname(__FILE__) . "/../../../$portal_private_root/directory/discipline/" . $filename;
					unlink($filename);
				}
			}
		}

		//Delete the Record
		include "../../core/abre_dbconnect.php";
		$stmt = $db->prepare("DELETE FROM directory_discipline WHERE id = ? AND siteID = ? LIMIT 1");
		$stmt->bind_param("ii",$id, $_SESSION['siteID']);
		$stmt->execute();
		$stmt->close();
		$db->close();

		echo "The discipline record has been deleted.";
	}

?>