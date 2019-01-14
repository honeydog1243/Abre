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

  require_once('abre_verification.php');
	require_once('abre_functions.php');
  require("abre_dbconnect.php");

  $cloudSetting = getenv("USE_GOOGLE_CLOUD");
	if ($cloudSetting == "true") {
    require(dirname(__FILE__). '/../vendor/autoload.php');
  }
  use Google\Cloud\Storage\StorageClient;
  
  $file = current($_FILES);
  $info = new SplFileInfo($file['name']);
  $ext = $info->getExtension();
  $contentType = "image/$ext";

  $insertSql = "INSERT INTO abre_tiny_upload (Original_File_Name, Content_Type, Stored_File, siteID)
                VALUES (?, ?, UUID(), ?)";
  
  $insertStmt = $db->stmt_init();
  $insertStmt->prepare($insertSql);
  $insertStmt->bind_param("ssi", $file['name'], $contentType, $_SESSION['siteID']);
  $insertStmt->execute();
  $id = $insertStmt->insert_id;
  $insertStmt->close();

  $lookupSql = "SELECT Stored_File FROM abre_tiny_upload WHERE ID = ?";
  $lookupStmt = $db->stmt_init();
  $lookupStmt->prepare($lookupSql);
  $lookupStmt->bind_param("i", $id);
  $lookupStmt->execute();
  $lookupStmt->bind_result($uuid);
  $lookupStmt->fetch();
  $lookupStmt->close();
  $db->close();

  
  if ($cloudSetting == "true") {
    try {
      $storage = new StorageClient([
        'projectId' => getenv("GC_PROJECT")
      ]);
      $bucket = $storage->bucket(getenv("GC_BUCKET"));
      $uploadPath = "private_html/tiny-uploads/$uuid";
      $stream = fopen($file['tmp_name'], 'r');
      
      $options = [
        'name' => $uploadPath
      ];
      
      $obj = $upload = $bucket->upload($stream, $options);
    } catch (Exception $ex) {
      error_log($ex);
    }
  } else {
    $contentFolder = getConfigPortalPrivateRoot();
    $projectRoot = getConfigPortalPathRoot();
    $destination = "$projectRoot/../$contentFolder/tiny-uploads/$uuid";
    rename($file['tmp_name'], $destination);
  }

  $result = [
    "uuid" => $uuid,
    "name" => $info->getFileName(),
    // DON'T CHANGE - "location" is necessary for tiny's auto-uploading
    "location" => "core/abre_tiny_serve_image.php?uuid=$uuid",
    "message" => "The file has been added."
	];
	header("Content-Type: application/json");
	echo json_encode($result);

?>
