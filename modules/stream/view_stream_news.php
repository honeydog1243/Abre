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
require_once(dirname(__FILE__) . '/../../api/streams-api.php');
require_once(dirname(__FILE__) . '/../../api/profile-api.php');
require_once('functions.php');

$siteColor = getSiteColor();

//Create the Feed Array & Count
$feeds = array();
$totalcount = 0;

$enrolledStreams = getUsersApplicableStreams();
$flipBoardArray = array_column($enrolledStreams, 'url');

require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
try {
	//Get Feeds
	require_once('simplepie/autoloader.php');
	$feed_flipboard = new SimplePie();
	$feed_flipboard->set_cache_duration(1800);
	$feed_flipboard->set_stupidly_fast(true);
	$feed_flipboard->set_feed_url($flipBoardArray);
	$streamCacheSetting = getConfigStreamCache();
	$location = 'mysql://'.$db_user.':'.$db_password.'@hostname:port/database?prefix=sp_';	// from db_connect
	$feed_flipboard->set_cache_location($location);
	$feed_flipboard->enable_cache($streamCacheSetting);
	$feed_flipboard->init();
	$feed_flipboard->handle_content_type();

	$streamStartResult = 0;
	$streamEndResult = 24;
	if (isset($_GET["StreamStartResult"])) {
		if ($_GET["StreamStartResult"] > 0) {
			$streamStartResult = $_GET["StreamStartResult"];
		} else {
			$streamStartResult = $_GET["StreamStartResult"];
		}
	}
	if (isset($_GET["StreamEndResult"])) { 
		$streamEndResult = $_GET["StreamEndResult"]; 
	}
	date_default_timezone_set("EST");
	foreach($feed_flipboard->get_items($streamStartResult,$streamEndResult) as $item){
		$date = $item->get_date();
		$title = $item->get_title();
		$link = $item->get_link();
		$feedTitle = $item->get_feed()->get_title();

		$date = strtotime($date);
		$excerpt = $item->get_description();
		if($enclosure = $item->get_enclosure()){
			$image = $enclosure->get_link();
		}

		if ($image == "") {
			preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $excerpt, $embededimage);
			if (isset($embededimage['src'])) { 
				$image = $embededimage['src']; 
			}
		}

		$protocols = array("https://", "http://");
		$indexLink = $item->get_feed()->get_link(0, 'self');
		$indexLinkUrl = strtolower(str_replace($protocols, "", $indexLink));

		// reset values in case the loop fails
		$color = "";
		$feedTitle = "Stream";
		for ($i = 0; $i < count($enrolledStreams); $i++) {
			$url = strtolower(str_replace($protocols, '', $enrolledStreams[$i]['url']));
			// twitrss.me can create urls in the form of twitrss.me/twitter_search_to_rss/?term=#masonmoment
			// we store this as twitrss.me/twitter_search_to_rss/?term=%23masonmoment. We'll give urls an 
			// extra shot to match by replacing all %23 with # and checking once more. 
			// If you write a second special case, please rewrite this to be better. 
			$urlHashtags = str_replace("%23", "#", $url);
			if ($url == $indexLinkUrl || $urlHashtags == $indexLinkUrl) {
				$color = $enrolledStreams[$i]['color'];
				$feedTitle = $enrolledStreams[$i]['title'];
				break;
			}
		}
		$feeds[] = [
			"date" => $date, 
			"title" => $title, 
			"excerpt" => $excerpt, 
			"link" => $link, 
			"image" => $image, 
			"feedTitle" => $feedTitle, 
			"color" => $color, 
		];
		$totalcount++;
	}

	//Display the Feeds
	$cardCount = 0;
	$cloudsetting = getenv("USE_GOOGLE_CLOUD");
	for($cardCountLoop = 0; $cardCountLoop < $totalcount; $cardCountLoop++){
		$date = $feeds[$cardCountLoop]['date'];

		$title = sanitizeField($feeds[$cardCountLoop]['title']);

		$excerpt = sanitizeField($feeds[$cardCountLoop]['excerpt']);
		$excerpt = filter_var($excerpt, FILTER_SANITIZE_STRING);
		if ($excerpt == "") { 
			$excerpt = $title; 
		}
		$rawExcerpt = $excerpt;

		$rawLink = $feeds[$cardCountLoop]['link'];
		$image = $feeds[$cardCountLoop]['image'];
		$feedTitle = $feeds[$cardCountLoop]['feedTitle'];
		$color = $feeds[$cardCountLoop]['color'];
		$type = $feeds[$cardCountLoop]['type'] ?? "";

		//Add images to server to securely store and reference
		if ($cloudsetting == "true") {
			include "action_save_image_gc.php";
		}	else {
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
			$commentSql = "SELECT COUNT(*) FROM streams_comments WHERE url = '$link' and comment != '' AND siteID = $_SESSION[siteID]";
			$commentResult = $db->query($commentSql);
			$commentRow = $commentResult->fetch_assoc();
			$commentCount = $commentRow["COUNT(*)"];

			$likeSql = "SELECT COUNT(*) FROM streams_comments WHERE url = '$link' AND comment = '' AND liked = '1' AND siteID = $_SESSION[siteID]";
			$likeResult = $db->query($likeSql);
			$likeRow = $likeResult->fetch_assoc();
			$likeCount = $likeRow["COUNT(*)"];

			$likedSql = "SELECT COUNT(*) FROM streams_comments WHERE url = '$link' AND liked = '1' AND user = '$_SESSION[escapedemail]'";
			$likedResult = $db->query($likedSql);
			$likedRow = $likedResult->fetch_assoc();
			$userLiked = $likedRow["COUNT(*)"] > 0;
		}

		if($title != "" && $excerpt != ""){
			displayUneditablePostWithStreamName($type, 0, $feedTitle, $title, $image, $date, $rawExcerpt, $excerpt, $rawLink,
				$likeCount, $userLiked, $commentCount, $cardCount, $color);
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
				Welcome to Your Stream
			</span>
			<br>
			<p class="default-message-description">
				Get started by following a few streams.
				<br>
				You"ll see the latest posts from the streams you follow here.
			</p>
		</div>
		<a class="mdl-button mdl-js-button mdl-js-ripple-effect" style="background-color: <?= $siteColor ?>; color:#fff;" href="#profile">
			View Available Streams
		</a>
	</div>
<?php
}

include "view_stream_cards.php";
?>
