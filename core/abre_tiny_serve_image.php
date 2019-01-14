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

  $uuid = $_GET['uuid'];
  $siteID = $_SESSION['siteID'];

  $sql = "SELECT Content_Type FROM abre_tiny_upload
          WHERE Stored_File = ? AND siteID = ?";

  $stmt = $db->stmt_init();
  $stmt->prepare($sql);
  $stmt->bind_param("si", $uuid, $siteID);
  $stmt->execute();
  $stmt->bind_result($contentType);
  $stmt->fetch();

  if(!$contentType) {
    return;
  }

	header('Pragma: public');
  header("Content-Type: $contentType");
  header('Cache-Control: max-age=31536000');
	header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));

  if($cloudSetting == "true") {
    $path = "private_html/tiny-uploads/$uuid";

    $storage = new StorageClient([
			'projectId' => getenv("GC_PROJECT")
		]);
		$bucket = $storage->bucket(getenv("GC_BUCKET"));
		$object = $bucket->object($path);
		$stream = $object->downloadAsStream();
		echo($stream->getContents());
  } else {
    $contentFolder = getConfigPortalPrivateRoot();
    $projectRoot = getConfigPortalPathRoot();
    $file = "$projectRoot/../$contentFolder/tiny-uploads/$uuid";
    echo(file_get_contents($file));
  }
?>