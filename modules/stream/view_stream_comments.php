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
require_once(dirname(__FILE__) . '/../../api/streams-api.php');
require_once('functions.php');

$siteColor = getSiteColor();

$posts = [];
$scrolling = false;
$streamStartResult = 0;
$streamEndResult = 24;
if (isset($_GET["StreamStartResult"])) {
	if ($_GET["StreamStartResult"] > 0) {
		$streamStartResult = $_GET["StreamStartResult"];
		$scrolling = true;
	} else {
		$streamStartResult = $_GET["StreamStartResult"];
	}
}

if ($scrolling) { 
	$streamEndResult = $streamStartResult + 24; 
}

require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
try {
	$sql = "SELECT sc.title, sc.image, sc.url, sc.creationtime, sc.excerpt
					FROM (
							SELECT MAX(id) AS id, url FROM streams_comments
							WHERE user = '$_SESSION[escapedemail]' AND comment != '' 
									AND siteID = $_SESSION[siteID]
							GROUP BY url
					) AS last_comment
					JOIN streams_comments sc ON last_comment.id = sc.id
					ORDER BY creationtime DESC
					LIMIT $streamStartResult, $streamEndResult";

	$result = $db->query($sql);
	while ($value = $result->fetch_assoc()) {
		// Do we need to check if they are still able to see this stream?
		array_unshift($posts, $value);
	}

	$cardCount = 0;
	$cloudSetting = getenv("USE_GOOGLE_CLOUD");
	while (!empty($posts)) {
		$post = array_pop($posts);
		$date =	strtotime($post['creationtime']);

		$title = sanitizeField($post['title']);
		$rawExcerpt = htmlspecialchars($post['excerpt'], ENT_QUOTES);
		$excerpt = sanitizeField($rawExcerpt);
		$excerpt = filter_var($excerpt, FILTER_SANITIZE_STRING);
		if ($excerpt == "") { 
			$excerpt = $title; 
		}

		$rawLink = $post['url'];
		$image = $post['image'];
		$type = is_numeric($rawLink) ? "custom" : "stream";
		$id = is_numeric($rawLink) ? $rawLink : null;

		//Add images to server to securely store and reference
		if ($cloudSetting == "true") {
			include "action_save_image_gc.php";
		} else {
			include "action_save_image.php";
		}

		$link = mysqli_real_escape_string($db, $rawLink);
		if (useAPI()) {
			$apiValue = apiStreams::getStreamContentsByUrl(json_encode(array("url"=>$link)));
			$result = $apiValue['result'];

			$commentCount = $result['counts']['comments'];
			$likeCount = $result['counts']['likes'];
			$userLiked = $result['counts']['userLikes'] > 0;
		} else {
			$commentSql = "SELECT COUNT(*) FROM streams_comments 
										 WHERE url = '$link' and comment != '' AND siteID = $_SESSION[siteID]";
			$commentResult = $db->query($commentSql);
			$commentRow = $commentResult->fetch_assoc();
			$commentCount = $commentRow["COUNT(*)"];

			$likeSql = "SELECT COUNT(*) FROM streams_comments 
									WHERE url = '$link' AND comment = '' AND liked = '1' AND siteID = $_SESSION[siteID]";
			$likeResult = $db->query($likeSql);
			$likeRow = $likeResult->fetch_assoc();
			$likeCount = $likeRow["COUNT(*)"];

			$likedSql = "SELECT COUNT(*) FROM streams_comments 
									 WHERE url = '$link' AND liked = '1' AND user = '$_SESSION[escapedemail]'";
			$likedResult = $db->query($likedSql);
			$likedRow = $likedResult->fetch_assoc();
			$userLiked = $likedRow["COUNT(*)"] > 0;
		}

		if ($title != "" && $excerpt != "") {
			displayUneditablePostWithMessage($type, $id, $title, $image, $date, $rawExcerpt, $excerpt, $rawLink,
				$likeCount, $userLiked, $commentCount, $cardCount, "You Added a Comment");
			$cardCount++;
		}
	}
} finally {
	$db->close();
}

if ($cardCount == 0 && $streamStartResult == 0) {
?>
	<div class="row center-align">
		<div class="widget" style="padding:30px; text-align:center; width:100%;">
			<span class="default-message-title">
				Stream Comments
			</span>
			<br>
			<p class="default-message-description">
				Get started by commenting on a post from your stream.
				<br>
				You'll see the stream posts you have commented on here.
			</p>
		</div>
	</div>
<?php
}

include "view_stream_cards.php";
?>
