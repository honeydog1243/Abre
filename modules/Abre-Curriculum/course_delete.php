<?php

	/*
	* Copyright (C) 2016-2018 Abre.io Inc.
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
	require_once(dirname(__FILE__) . '/../../core/abre_dbconnect.php');

	$librarycourseid=mysqli_real_escape_string($db, $_GET["librarycourseid"]);

	//Delete Course from libraries
	$stmt = $db->stmt_init();
	$sql = "DELETE FROM curriculum_libraries WHERE Course_ID = '$librarycourseid'";
	$stmt->prepare($sql);
	$stmt->execute();
	$stmt->close();

	//delete old image
	$sql = "SELECT Image FROM curriculum_course WHERE ID = $librarycourseid";
	$query = $db->query($sql);
	$row = $query->fetch_assoc();
	$existingImage = $row["Image"];
	if($cloudsetting == "true"){
		$storage = new StorageClient([
			'projectId' => constant("GC_PROJECT")
		]);
		$bucket = $storage->bucket(constant("GC_BUCKET"));

		$uploaddir = "private_html/Abre-Curriculum/images/" . $existingImage;

		//Delete old image
		$postimage = $uploaddir;
		$object = $bucket->object($postimage);
		$object->delete();
	}else{
		if(file_exists($portal_path_root."/../$portal_private_root/Abre-Curriculum/images/".$existingImage)){
			unlink($portal_path_root."/../$portal_private_root/Abre-Curriculum/images/".$existingImage);
		}
	}

	//Delete course
	$stmt = $db->stmt_init();
	$sql = "DELETE FROM curriculum_course WHERE id = '$librarycourseid' LIMIT 1";
	$stmt->prepare($sql);
	$stmt->execute();
	$stmt->close();

	//Delete resources for topics
	$sqllookup2 = "SELECT ID FROM curriculum_unit WHERE Course_ID = '$librarycourseid'";
	$result3 = $db->query($sqllookup2);
	$unitcount=mysqli_num_rows($result3);
	while($row2 = $result3->fetch_assoc())
	{
		if (!empty($row2["ID"])){ $Unit_ID=stripslashes($row2["ID"]); }
		$stmt = $db->stmt_init();
		$sql = "DELETE FROM curriculum_resources WHERE TopicID = '$Unit_ID'";
		$stmt->prepare($sql);
		$stmt->execute();
	}

	//Delete topics in course
	$stmt = $db->stmt_init();
	$sql = "DELETE FROM curriculum_unit WHERE Course_ID = '$librarycourseid'";
	$stmt->prepare($sql);
	$stmt->execute();
	$stmt->close();

	$db->close();
	echo "The course has been deleted.";

?>