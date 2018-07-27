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
	require_once(dirname(__FILE__) . '/../../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../../core/abre_functions.php');
  require_once(dirname(__FILE__) . '/../../../core/abre_dbconnect.php');
  require_once(dirname(__FILE__) . '/../../../core/uuid/Uuid.php');

  $title = $_POST["title"];
  $subject = $_POST["subject"];
  $body = $_POST["body"];

  $dt = new DateTime('@'.time());
  $now = $dt->format('c');
  $usersId = 10; //TODO harded
  // $files = $_POST["files"];
  $journalSql = "INSERT INTO Abre_Connect_Journal (UsersId, Title, Body, LastUpdated) VALUES (?, ?, ?, ?)";
  $attachmentSql = "INSERT INTO Abre_Connect_Journal_Attachment (UniqueID, JournalID, FileName, FileLocation, LastUpdated) VALUES (?, ?, ?, ?, ?)";

  $journalStatement = $db->stmt_init();
  $journalStatement->prepare($journalSql);
  $journalStatement->bind_param("isss", $usersId, $title, $body, $now);
  $journalStatement->execute();
  $journalId = $journalStatement->insert_id;
  $journalStatement->close();
  
  $uiud = Uuid::generate(4);
  $fileName = $_FILES['files']['name'][0];
  $folder = $portal_path_root . "/../$portal_private_root/Abre-Connect/";
  if(!file_exists($folder)){
    mkdir($folder, 0775);
  }
  $fileLocation = $folder . $uiud;
  $fileTempLocation = $_FILES['files']['tmp_name'][0];
  move_uploaded_file($fileTempLocation, $fileLocation);

  $attachmentStatement = $db->stmt_init();
  $attachmentStatement->prepare($attachmentSql);
  error_log($uiud);
  error_log($journalId);
  error_log($fileName);
  error_log($fileLocation);
  error_log($now);
  $attachmentStatement->bind_param("sisss", $uiud, $journalId, $fileName, $fileLocation, $now);
  error_log(mysqli_stmt_error($attachmentStatement));
  $attachmentStatement->execute();
  error_log(mysqli_stmt_error($attachmentStatement));
  $attachmentStatement->close();
  error_log(mysqli_stmt_error($attachmentStatement));

  $db->close();


?>