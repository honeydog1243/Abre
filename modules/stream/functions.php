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
require_once(dirname(__FILE__) . '/../../api/profile-api.php');

/**
 * Returns an array of associative arrays of the form
 * [
 * 	title =>
 *  type => "flipboard" OR "rss feed"
 *  color =>
 *  url =>
 * ]
 */
function getUsersApplicableStreams() {
	$schoolCodeArray = getRestrictions();

	//Find what streams to display
	if (useAPI()) {
		$apiValue = apiProfile::getUserProfile();
		$dbreturn = $apiValue['result'];
	}
	else {
		$query = "SELECT streams FROM profiles WHERE email = '$_SESSION[escapedemail]' AND siteID = $_SESSION[siteID]";
		$dbreturn = databasequery($query);
	}

	foreach($dbreturn as $value) {
		$userStreams = htmlspecialchars($value['streams'], ENT_QUOTES);
	}

	//Create the Feed Array & Count
	$feeds = array();

	//Get Feeds
	$streamsCheck = empty($userStreams) ? "FALSE" : "id IN ($userStreams)";
	$streamsSql = "SELECT title, url, `group`, color, staff_building_restrictions, student_building_restrictions FROM streams
								 WHERE (required = 1 OR $streamsCheck) AND siteID = $_SESSION[siteID]";

	//Look for all streams that apply to user
	$enrolledStreams = array();
	$streamsResult = databasequery($streamsSql);
	foreach($streamsResult as $value){
		if (strpos($value["group"], $_SESSION["usertype"]) !== false) {
			$result = [];

			$result["url"] = htmlspecialchars($value['url'], ENT_QUOTES);

			if($_SESSION['usertype'] == "staff"){
				$restrictions = $value['staff_building_restrictions'];
				$restrictionsArray = explode(",", $restrictions);
			}elseif($_SESSION['usertype'] == "student"){
				$restrictions = $value['student_building_restrictions'];
				$restrictionsArray = explode(",", $restrictions);
			}else{
				// parent
				$restrictions = null;
			}

			$result["color"] = $value["color"];
			$result["title"] = $value["title"];

			$addToArray = false;
			if ($restrictions == null || in_array("No Restrictions", $restrictionsArray)) {
				$addToArray = true;
			} else {
				if (!empty($schoolCodeArray)) {
					foreach ($schoolCodeArray as $code) {
						if (in_array($code, $restrictionsArray)) {
								$addToArray = true;
								break;
						}
					}
				}
			}

			if ($addToArray) {
				$enrolledStreams[] = $result;
			}
		}
	}

	return $enrolledStreams;
}

function sanitizeField($value) {
	$value = str_replace("<p>", " ", $value);
	$value = strip_tags(html_entity_decode($value));
	$value = preg_replace('/(\.)([[:alpha:]]{2,})/', '$1 $2', $value);
	$value = str_replace("'",'"',$value);
	$value = str_replace('"',"'",$value);
	$value = str_replace('’',"'",$value);
	$value = str_replace('—',"-",$value);
	return $value;
}

function displayUneditablePostWithStreamName($type, $id, $streamTitle, $postTitle, $image, $date, $rawExcerpt, $excerpt, $rawLink,
																						 $likeCount, $userLiked, $commentCount, $cardCountLoop,
																						 $color = '#BDBDBD') {
	_displayStreamCard($type, $id, null, $streamTitle, $postTitle, $image, $date, $rawExcerpt, $excerpt, $rawLink,
		$likeCount, $userLiked, $commentCount, $cardCountLoop, $color, null);
}

function displayUneditablePostWithMessage($type, $id, $postTitle, $image, $date, $rawExcerpt, $excerpt, $rawLink,
																			    $likeCount, $userLiked, $commentCount, $cardCountLoop, $message = '') {
	_displayStreamCard($type, $id, null, null, $postTitle, $image, $date, $rawExcerpt, $excerpt, $rawLink,
		$likeCount, $userLiked, $commentCount, $cardCountLoop, null, $message);
}

function displayEditablePostWithStreamName($type, $id, $owner, $streamTitle, $postTitle, $image, $date, $rawExcerpt, $excerpt,
																					 $rawLink, $likeCount, $userLiked, $commentCount, $cardCountLoop,
																					 $color = '#BDBDBD') {
	_displayStreamCard($type, $id, $owner, $streamTitle, $postTitle, $image, $date, $rawExcerpt, $excerpt, $rawLink,
		$likeCount, $userLiked, $commentCount, $cardCountLoop, $color, null);
}

// NOT CURRENTLY USED
function displayEditablePostWithMessage($type, $id, $owner, $postTitle, $image, $date, $rawExcerpt, $excerpt,
																				$rawLink, $likeCount, $userLiked, $commentCount, $cardCountLoop,
																				$message = '') {
	_displayStreamCard($type, $id, $owner, null, $postTitle, $image, $date, $rawExcerpt, $excerpt, $rawLink,
		$likeCount, $userLiked, $commentCount, $cardCountLoop, null, $message);
}

/**
 * DO NOT CALL DIRECTLY
 * Use displayStreamCardWithColor or displayStreamCardWithMessage
 */
function _displayStreamCard($type, $id, $owner, $feedTitle, $title, $image, $date, $rawExcerpt, $excerpt, $rawLink,
	$likeCount, $userLiked, $commentCount, $cardCountLoop, $color, $altFeedMessage) {

	$siteColor = getSiteColor();
	$linkEncoded = base64_encode($rawLink);
	$imageEncoded = base64_encode($image);
	$titleEscaped = htmlspecialchars($title, ENT_QUOTES);
	$titleWithoutLongWords = preg_replace("~\b\S{30,}\b~", "", $title);
	$feedTitleEncoded = !is_null($feedTitle) ? htmlspecialchars($feedTitle, ENT_QUOTES) : null;
	$body = $excerpt != "" ? $excerpt : $title;
	$renderShareLikeComments = $_SESSION["usertype"] == "staff";
	$heartColor = $userLiked ? "mdl-color-text--red" : "mdl-color-text--grey-600";
	$commentColor = $commentCount > 0 ? $siteColor : "#757575";

	if (strlen($body) > 100) {
		$body = substr($body, 0, strrpos(substr($body, 0, 100), " "));
		$body = substr($body, 0, 97) . " ...";
	}
?>
	<!-- Display Card -->
	<div class="mdl-card mdl-shadow--2dp card_stream hoverable stream-post" style="float:left;"
				data-commenticonid="comment_<?= $cardCountLoop ?>" data-id="<?= $id ?>"
				data-image="<?= $imageEncoded ?>" data-redirect="latest" data-title="<?= $titleEscaped ?>"
				data-excerpt="<?= $rawExcerpt ?>"
				data-url="<?= $linkEncoded ?>" data-type="<?= $type ?>">
		<!-- Stream -->
<?php
		if ($altFeedMessage != null) {
?>
			<div class="truncate"
					 style="padding: 16px 16px 0 16px; font-size: 12px; color: #999; font-weight: 500;">
				<?= $altFeedMessage ?>
			</div>
<?php
		} else {
?>
		<div class="truncate" style="padding: 20px 20px 0 20px;">
			<a class="chip read-stream pointer"
				 style="background-color: <?= $color ?>; color: #fff; height:20px; line-height:20px; margin-bottom: 0px; font-weight: 500;">
				<?= $feedTitle ?>
			</a>
<?php	if ($type == "custom" && ($owner == $_SESSION["useremail"] || admin())) { ?>
				<div class="right-align pointer" style="float: right; position: absolute; right:15px; top: 18px;">
					<a class="editpost" data-feedtitle="<?= $feedTitleEncoded ?>" data-id="<?= $id ?>" data-image="<?= $imageEncoded ?>"
							data-title="<?= $titleEscaped ?>" data-excerpt="<?= $rawExcerpt ?>" data-type="custom">
							<i class="material-icons" style="font-size: 16px; color: #333;">edit</i>
					</a>
				</div>
<?php
			}
			echo "</div>";
		}
?>

		<!-- Title -->
		<div class="cardtitle" style="padding:10px 20px 0px 20px; height:65px;">
			<div class="mdl-card__title-text ellipsis-multiline pointer read-stream"
					 style="font-weight:700; font-size:20px; line-height:24px;">
				<?= $titleWithoutLongWords ?>
			</div>
		</div>
		<!-- Date -->
		<div class="truncate" style="padding:0 20px 20px 20px;">
			<a class="read-stream" href="#addstreamcomment" style="color: #9E9E9E; font-size: 14px;" >
				<?= date("F jS, Y", $date) ?>
			</a>
		</div>
		<!-- Card Image -->
<?php
		if ($image != "") { ?>
			<div class="mdl-card__media mdl-color--grey-100 mdl-card--expand pointer read-stream"
					 style="height:200px; background-image: url(<?= $image ?>);">
			</div>
<?php
		} else {
?>
			<div class="mdl-card__media mdl-color--grey-100 mdl-card--expand valign-wrapper pointer read-stream"
					 style="height: 200px; background-image: url(/core/images/abre/abre_pattern.png); background-color: <?= $siteColor ?> !important; overflow:hidden;">
				<span class="wrap-links" style="width: 100%; color: #fff; padding: 32px; font-size: 18px; line-height: normal; font-weight: 700; text-align: center;">
					<?= $body ?>
				</span>
			</div>
<?php
		}
?>
		<!-- Card Actions -->
		<div class="mdl-card__actions">
			<!-- Read Button -->
			<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect read-stream pointer"
					style="color: <?= $siteColor ?>">
				Read
			</a>
			<!-- Share, Likes, Comments for Staff Only -->
<?php if ($renderShareLikeComments) { ?>
				<div class="mdl-layout-spacer">
				</div>
				<!-- Share -->
<?php 	if ($type != "custom") { ?>
					<a class="material-icons mdl-color-text--grey-600 modal-sharecard commenticon share-info" style="margin-right: 30px;"
							data-url="<?= $linkEncoded ?>" title="Share" href="#sharecard">
						share
					</a>
<?php		} ?>
				<!-- Likes -->
				<a class="material-icons <?= $heartColor ?> like-icon" data-title="<?= $titleEscaped ?>" data-excerpt="<?= $rawExcerpt ?>"
						data-url="<?= $linkEncoded ?>" data-image="<?= $imageEncoded ?>" title="Like" href="#">
					favorite
				</a>
				<span class="<?= $heartColor ?>" style="font-size:12px; font-weight:600; width:30px; padding-left:5px;">
					<?= $likeCount ?>
				</span>
				<!-- Comments -->
				<a class="material-icons modal-addstreamcomment commenticon" style="color: <?= $commentColor ?>;" data-redirect="latest"
						data-commenticonid="comment_<?= $cardCountLoop ?>" data-image="<?= $imageEncoded ?>" data-title="<?= $titleEscaped ?>"
						data-excerpt="<?= $rawExcerpt ?>" data-url="<?= $linkEncoded ?>" data-type="<?= $type ?>" title="Add a comment"
						href="#addstreamcomment">
					insert_comment
				</a>
				<span id="comment_<?= $cardCountLoop ?>"
							style="font-size:12px; font-weight:600; width:30px; padding-left:5px; color: <?= $commentColor ?>">
					<?= $commentCount ?>
				</span>
<?php
			}
		echo "</div>";
	echo "</div>";
}
?>
