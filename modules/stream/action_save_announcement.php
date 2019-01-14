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

$postAuthor = $_SESSION['escapedemail'];
$authorFirstName = GetStaffFirstName($postAuthor);
$authorLastName = GetStaffLastName($postAuthor);

if ($_POST['post_title'] != "") {
	$postTitle = $_POST['post_title'];
} else {
	$response = array("status" => "Error", "message" => "Error! You did not provide an announcement title!");
	header("Content-Type: application/json");
	echo json_encode($response);
	exit;
}
if ($_POST['post_stream'] != "") {
	$postStream = $_POST['post_stream'];
} else {
	$response = array("status" => "Error", "message" => "Please provide an announcement stream!");
	header("Content-Type: application/json");
	echo json_encode($response);
	exit;
}

$streamStmt = $db->stmt_init();
$sql = "SELECT `group`, staff_building_restrictions, student_building_restrictions, color FROM streams WHERE title = ? AND siteID = ? LIMIT 1";
$streamStmt->prepare($sql);
$streamStmt->bind_param("si", $postStream, $_SESSION['siteID']);
$streamStmt->execute();
$streamStmt->store_result();
$streamStmt->bind_result($postGroup, $staffRestrictions, $studentRestrictions, $color);
$streamStmt->fetch();

if ($staffRestrictions == "") {
	$staffRestrictions = "No Restrictions";
}

if ($studentRestrictions == "") {
	$studentRestrictions = "No Restrictions";
}

if ($_POST['post_content'] != "") {
	$postContent = $_POST['post_content'];
} else {
	$response = array("status" => "Error", "message" => "Please provide content for your announcement!");
	header("Content-Type: application/json");
	echo json_encode($response);
	exit;
}

//Save POST Image
$image_file_name = "";
if ($_FILES['customimage']['name']) {

	//Get file information
	$file = $_FILES['customimage']['name'];
	$fileextention = pathinfo($file, PATHINFO_EXTENSION);
	$cleanfilename = basename($file);
	$image_file_name = time() . "_post." . $fileextention;

	if ($cloudsetting=="true") {
		$storage = new StorageClient([
			'projectId' => getenv("GC_PROJECT")
		]);
		$bucket = $storage->bucket(getenv("GC_BUCKET"));

		//Resize image
		rename($_FILES['customimage']['tmp_name'], $_FILES['customimage']['tmp_name'].'.'.$fileextention);

		ResizeImage($_FILES['customimage']['tmp_name'].'.'.$fileextention, "1000", "90");

		$uploaddir = "private_html/stream/cache/images/$image_file_name";
		//Upload new image
		$postimage = $uploaddir;
		$options = [
			'resumable' => true,
			'name' => $postimage,
			'metadata' => [
				'contentLanguage' => 'en'
			]
		];

		$upload = $bucket->upload(
			fopen($_FILES['customimage']['tmp_name'].'.'.$fileextention, 'r'),
			$options
		);

		$stmt = $db->stmt_init();
		$sql = "INSERT INTO streams_cache (link) VALUES (?)";
		$stmt->prepare($sql);
		$stmt->bind_param("s", $image_file_name);
		$stmt->execute();
		$stmt->close();
	}	else {
		$uploaddir = "$portal_path_root/../$portal_private_root/stream/cache/images/$image_file_name";

		//Upload new image
		$postimage = $uploaddir;
		move_uploaded_file($_FILES['customimage']['tmp_name'], $postimage);

		//Resize image
		ResizeImage($uploaddir, "1000", "90");
	}
}

if (isset($_POST['post_id'])) {
	$postID = $_POST['post_id'];
} else {
	$postID = "";
}

if ($postID == "") {
	$insertStmt = $db->stmt_init();
	$sql = "INSERT INTO stream_posts (post_author, author_firstname, author_lastname, post_groups, post_title, post_stream, post_content, post_image, color, staff_building_restrictions, student_building_restrictions, siteID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$insertStmt->prepare($sql);
	$insertStmt->bind_param("sssssssssssi", $postAuthor, $authorFirstName, $authorLastName, $postGroup, $postTitle, $postStream, $postContent, $image_file_name, $color, $staffRestrictions, $studentRestrictions, $_SESSION['siteID']);
	$insertStmt->execute();
	if ($insertStmt->error != "") {
		$insertStmt->close();
		$db->close();
		$response = array("status" => "Error", "message" => "There was a problem saving your announcement. Please try again.");
		header("Content-Type: application/json");
		echo json_encode($response);
		exit;
	}
	$insertStmt->close();
} else {
	$updateStmt = $db->stmt_init();
	if ($image_file_name == "") {
		$sql = "UPDATE stream_posts SET post_author = ?, author_firstname = ?, author_lastname = ?, post_groups = ?, post_title = ?, post_stream = ?, post_content = ?, color = ?, staff_building_restrictions = ?, student_building_restrictions = ?, last_modified_time = CURRENT_TIMESTAMP, siteID = ? WHERE id = ?";
		$updateStmt->prepare($sql);
		$updateStmt->bind_param("ssssssssssii", $postAuthor, $authorFirstName, $authorLastName, $postGroup, $postTitle, $postStream, $postContent, $color, $staffRestrictions, $studentRestrictions, $_SESSION['siteID'], $postID);
		$updateStmt->execute();
		if ($updateStmt->error != "") {
			$updateStmt->close();
			$db->close();
			$response = array("status" => "Error", "message" => "There was a problem updating your announcement. Please try again.");
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		$updateStmt->close();
	} else {
		$sql = "SELECT post_image FROM stream_posts WHERE id = $postID LIMIT 1";
		$query = $db->query($sql);
		$row = $query->fetch_assoc();
		$image = $row['post_image'];

		if ($cloudsetting == "true") {
			$storage = new StorageClient([
				'projectId' => getenv("GC_PROJECT")
			]);
			$bucket = $storage->bucket(getenv("GC_BUCKET"));

			$uploaddir = "private_html/stream/cache/images/$image";

			//Delete old image
			$postimage = $uploaddir;
			if($bucket->object($postimage)->exists()){
				$object = $bucket->object($postimage);
				$object->delete();
			}
		} else {
			if(file_exists("$portal_path_root/../$portal_private_root/stream/cache/images/$image")){
				unlink("$portal_path_root/../$portal_private_root/stream/cache/images/$image");
			}
		}
		$sql = "UPDATE stream_posts SET post_author = ?, author_firstname = ?, author_lastname = ?, post_groups = ?, post_title = ?, post_stream = ?, post_content = ?, post_image = ?, color = ?, staff_building_restrictions = ?, student_building_restrictions = ?, last_modified_time = CURRENT_TIMESTAMP, siteID = ? WHERE id = ?";
		$updateStmt->prepare($sql);
		$updateStmt->bind_param("sssssssssssii", $postAuthor, $authorFirstName, $authorLastName, $postGroup, $postTitle, $postStream, $postContent, $image_file_name, $color, $staffRestrictions, $studentRestrictions, $_SESSION['siteID'], $postID);
		$updateStmt->execute();
		if ($updateStmt->error != "") {
			$updateStmt->close();
			$db->close();
			$response = array("status" => "Error", "message" => "There was a problem updating your announcement. Please try again.");
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		$updateStmt->close();
	}
}

$streamStmt->close();
$db->close();
$response = array("status" => "Success", "message" => "Your announcement was saved successfully!");
header("Content-Type: application/json");
echo json_encode($response);

?>