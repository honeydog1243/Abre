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
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	$portal_private_root = getConfigPortalPrivateRoot();
	$portal_path_root = getConfigPortalPathRoot();

	$cloudsetting = getenv("USE_GOOGLE_CLOUD");
	if ($cloudsetting=="true")
		require(dirname(__FILE__). '/../../vendor/autoload.php');
	use Google\Cloud\Storage\StorageClient;

  if($_POST['id'] != ""){
    $id = $_POST['id'];
  }else{
    $response = array("status" => "Error", "message" => "There was a problem deleting your announcement. Try again!");
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
  }

	$sql = "SELECT post_image FROM stream_posts WHERE id = $id LIMIT 1";
	$query = $db->query($sql);
	$row = $query->fetch_assoc();
	$image = $row['post_image'];

	if($cloudsetting == "true"){
		$storage = new StorageClient([
			'projectId' => getenv("GC_PROJECT")
		]);
		$bucket = $storage->bucket(getenv("GC_BUCKET"));

		$uploaddir = "private_html/stream/cache/images/".$image;

		//Delete old image
		$postimage = $uploaddir;
		if($bucket->object($postimage)->exists()){
			$object = $bucket->object($postimage);
			$object->delete();
		}
	}else{
		if(file_exists($portal_path_root."/../$portal_private_root/stream/cache/images/".$image)){
			unlink($portal_path_root."/../$portal_private_root/stream/cache/images/".$image);
		}
	}

  //Delete Post
  $stmt = $db->stmt_init();
  $sql = "DELETE FROM stream_posts WHERE id = ? AND siteID = ?";
  $stmt->prepare($sql);
  $stmt->bind_param("ii", $id, $_SESSION['siteID']);
  $stmt->execute();
  if($stmt->error != ""){
		$stmt->close();
		$db->close();
    $response = array("status" => "Error", "message" => "There was a problem deleting your announcement.");
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
  }
  $stmt->close();

	$stmt = $db->stmt_init();
	$sql = "DELETE FROM streams_comments WHERE url = ? AND siteID = ?";
	$stmt->prepare($sql);
	$stmt->bind_param("si", $id, $_SESSION['siteID']);
	$stmt->execute();
	$stmt->close();
	$db->close();

  $response = array("status" => "Success", "message" => "Your announcement has been deleted.");
  header("Content-Type: application/json");
  echo json_encode($response);




?>