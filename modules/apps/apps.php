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
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	$portal_root = getConfigPortalRoot();

	$schoolCodeArray = getRestrictions();

	$siteID = $_SESSION['siteID'];
	$userEscaped = $_SESSION['escapedemail'];

	function get3rdPartyAppsData() {
		static $appData = null;
		if($appData == null) {
			$appData = load3rdPartyAppsData();
		}
		return $appData;
	}

	// defaults to ordered by sort column
	// if a usecase wants it ordered differently, it's up to them to do this
	function load3rdPartyAppsData() {
		require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
		$sql = "SELECT id, title, image, link, staff_building_restrictions, staff, required, parent,
									 student, student_building_restrictions
						FROM apps
						WHERE siteID = ?
						ORDER BY sort";

		$stmt = $db->stmt_init();
		$stmt->prepare($sql);
		$stmt->bind_param('i', $_SESSION['siteID']);
		$stmt->execute();
		$stmt->bind_result($id, $title, $image, $link, $staffBuildingRestrictions, $staff, $required,
			$parent, $student, $studentBuildingRestrictions);

		$apps = [];
		while($stmt->fetch()) {
			$apps[$id] = [
				"id" => $id,
				"title" => $title,
				"image" => $image,
				"link" => $link,
				"staff_building_restrictions" => $staffBuildingRestrictions,
				"staff" => $staff,
				"required" => $required,
				"parent" => $parent,
				"student" => $student,
				"student_building_restrictions" => $studentBuildingRestrictions
			];
		}

		return $apps;
	}

	function emitAppHtml($portal_root, $id, $image, $link, $title) {
	?>
		<li id="item_<?= $id ?>" class="col s4 app"
				style="display:block; height:110px; overflow:hidden; word-wrap: break-word; margin:0 0 10px 0 !important;">
			<img src="<?= $portal_root ?>/core/images/apps/<?= $image ?>" class="appicon_modal">
			<span>
				<a href="<?= $link ?>" class="applink truncate" style="display:block;">
					<?= $title ?>
				</a>
			</span>
		</li>
	<?php
	}

	//Display customized apps for staff
	if($_SESSION['usertype'] == "staff") {
		//Display Staff Apps
		echo "<div class='row'><p style='text-align:center; font-weight:600;'>My Staff Apps</p><hr style='margin-bottom:20px;'>";
			echo "<ul class='appssort'>";

			$required = array();

			//Get App preference settings (if they exist)
			$sql2 = "SELECT apps_order FROM profiles WHERE email = '$userEscaped' AND siteID = $siteID";
			$result2 = $db->query($sql2);
			$apps_order = null;
			while($row2 = $result2->fetch_assoc()) {
				$apps_order = htmlspecialchars($row2["apps_order"], ENT_QUOTES);
			}

			//Build Array of Required Apps
			$apps = get3rdPartyAppsData();
			foreach($apps as $app) {
				if($app["required"] && $app["staff"]) {
					$id = $app["id"];
					$restrictions = $app['staff_building_restrictions'];
					$restrictionsArray = explode(",", $restrictions);
					if($restrictions == null || in_array("No Restrictions", $restrictionsArray)) {
						array_push($required, $id);
					} else {
						if(!empty($schoolCodeArray)) {
							foreach($schoolCodeArray as $code) {
								if(in_array($code, $restrictionsArray)) {
									array_push($required, $id);
									break;
								}
							}
						}
					}
				}
			}

			//Display default order, unless they have saved prefrences
			if($apps_order != null) {
				$order = explode(',', $apps_order);
			} else {
				$order = array();
			}

			//Compare
			foreach($required as $requiredvalue) {
				$hit = null;
				foreach($order as $ordervalue) {
					if($requiredvalue == $ordervalue) {
						$hit = "yes";
					}
				}
				if($hit == null) {
					array_push($order, $requiredvalue);
				}
			}
			if($apps_order != null) {
				foreach($order as $value) {
					if(isset(get3rdPartyAppsData()[$value])){
						$app = get3rdPartyAppsData()[$value];
						if($app["staff"]) {
							$id = $app["id"];
							$title = $app["title"];
							$image = $app["image"];
							$link = $app["link"];
							$restrictions = $app["staff_building_restrictions"];
							$restrictionsArray = explode(",", $restrictions);
							if($restrictions == null || in_array("No Restrictions", $restrictionsArray)) {
								emitAppHtml($portal_root, $id, $image, $link, $title);
							} else {
								if(!empty($schoolCodeArray)) {
									foreach($schoolCodeArray as $code) {
										if(in_array($code, $restrictionsArray)) {
											emitAppHtml($portal_root, $id, $image, $link, $title);
											break;
										}
									}
								}
							}
						}
					}
				}
			} else {
				$apps = get3rdPartyAppsData();
				foreach($apps as $app) {
					if($app["staff"]) {
						$id = $app["id"];
						$title = $app["title"];
						$image = $app["image"];
						$link = $app["link"];
						$restrictions = $app["staff_building_restrictions"];
						$restrictionsArray = explode(",", $restrictions);
						if($restrictions == null || in_array("No Restrictions", $restrictionsArray)){
							emitAppHtml($portal_root, $id, $image, $link, $title);
						} else {
							if(!empty($schoolCodeArray)) {
								foreach($schoolCodeArray as $code) {
									if(in_array($code, $restrictionsArray)) {
										emitAppHtml($portal_root, $id, $image, $link, $title);
										break;
									}
								}
							}
						}
					}
				}
			}
			echo "</ul>";
	  echo "</div>";
	}

	//Display parent and student apps
	if($_SESSION['usertype'] == 'parent') {
		echo "<div class='row'><p style='text-align:center; font-weight:600;'>Parent Apps</p><hr style='margin-bottom:20px;'>";
		$apps = get3rdPartyAppsData();
		foreach($apps as $app) {
			if($app["required"] && $app["parent"]) {
				$id = $app["id"];
				$title = $app["title"];
				$image = $app["image"];
				$link = $app["link"];
				emitAppHtml($portal_root, $id, $image, $link, $title);
			}
		}
		echo "</div>";
	} else {
		if($_SESSION['usertype'] == "staff") {
			//Display uneditable student apps
			echo "<div class='row'><p style='text-align:center; font-weight:600;'>Student Apps</p><hr style='margin-bottom:20px;'>";
			$apps = get3rdPartyAppsData();
			foreach($apps as $app) {
				if($app["required"] && $app["student"]) {
					$id = $app["id"];
					$title = $app["title"];
					$image = $app["image"];
					$link = $app["link"];
					$restrictions = $app["student_building_restrictions"];
					$restrictionsArray = explode(",", $restrictions);
					if($restrictions == null || in_array("No Restrictions", $restrictionsArray)) {
						emitAppHtml($portal_root, $id, $image, $link, $title);
					} else {
						if(!empty($schoolCodeArray)) {
							foreach($schoolCodeArray as $code) {
								if(in_array($code, $restrictionsArray)) {
									emitAppHtml($portal_root, $id, $image, $link, $title);
									break;
								}
							}
						}
					}
				}
			}
			echo "</div>";
			if(admin()){
				echo "<div class='row'><p style='text-align:center; font-weight:600;'>Parent Apps</p><hr style='margin-bottom:20px;'>";
				foreach($apps as $app){
					if($app["required"] && $app["parent"]){
						$id = $app["id"];
						$title = $app["title"];
						$image = $app["image"];
						$link = $app["link"];
						emitAppHtml($portal_root, $id, $image, $link, $title);
					}
				}
				echo "</div>";
			}
		} else {
			//Display editable student apps
			echo "<div class='row'><p style='text-align:center; font-weight:600;'>Student Apps</p><hr style='margin-bottom:20px;'>";
				echo "<ul class='appssort'>";

				$required = array();

				//Get App preference settings (if they exist)
				$sql2 = "SELECT apps_order FROM profiles WHERE email = '$userEscaped' AND siteID = $siteID";
				$result2 = $db->query($sql2);
				$apps_order = null;
				while($row2 = $result2->fetch_assoc()) {
					$apps_order = htmlspecialchars($row2["apps_order"], ENT_QUOTES);
				}

				//Build Array of Required Apps
				$apps = get3rdPartyAppsData();
				foreach($apps as $app) {
					if($app["required"] && $app["student"]) {
						$id = $app["id"];
						$restrictions = $app["student_building_restrictions"];
						$restrictionsArray = explode(",", $restrictions);
						if($restrictions == null || in_array("No Restrictions", $restrictionsArray)) {
							array_push($required, $id);
						} else {
							if(!empty($schoolCodeArray)) {
								foreach($schoolCodeArray as $code) {
									if(in_array($code, $restrictionsArray)) {
										array_push($required, $id);
										break;
									}
								}
							}
						}
					}
				}

				//Display default order, unless they have saved prefrences
				if($apps_order != null){
					$order = explode(',', $apps_order);
				}else{
					$order = array();
				}

				//Compare
				foreach($required as $requiredvalue) {
					$hit = null;
					foreach($order as $ordervalue) {
						if($requiredvalue == $ordervalue) {
							$hit = "yes";
						}
					}
					if($hit == null) {
						array_push($order, $requiredvalue);
					}
				}
				if($apps_order != null) {
					foreach($order as $value) {
						if(isset(get3rdPartyAppsData()[$value])){
							$app = get3rdPartyAppsData()[$value];
							if($app["student"]) {
								$id = $app["id"];
								$title = $app["title"];
								$image = $app["image"];
								$link = $app["link"];
								$restrictions = $app["student_building_restrictions"];
								$restrictionsArray = explode(",", $restrictions);
								if($restrictions == null || in_array("No Restrictions", $restrictionsArray)) {
									emitAppHtml($portal_root, $id, $image, $link, $title);
								} else {
									if(!empty($schoolCodeArray)) {
										foreach($schoolCodeArray as $code) {
											if(in_array($code, $restrictionsArray)) {
												emitAppHtml($portal_root, $id, $image, $link, $title);
												break;
											}
										}
									}
								}
							}
						}
					}
				} else {
					$apps = get3rdPartyAppsData();
					foreach($apps as $app) {
						if($app["student"]) {
							$id = $app["id"];
							$title = $app["title"];
							$image = $app["image"];
							$link = $app["link"];
							$restrictions = $app["student_building_restrictions"];
							$restrictionsArray = explode(",", $restrictions);
							if($restrictions == null || in_array("No Restrictions", $restrictionsArray)) {
								emitAppHtml($portal_root, $id, $image, $link, $title);
							} else {
								if(!empty($schoolCodeArray)) {
									foreach($schoolCodeArray as $code) {
										if(in_array($code, $restrictionsArray)) {
											emitAppHtml($portal_root, $id, $image, $link, $title);
											break;
										}
									}
								}
							}
						}
					}
				}
				echo "</ul>";
			echo "</div>";
		}
	}
	$db->close();

	//Display Apps Editor if admin
	if(admin() && $_SESSION['usertype'] == 'staff'){
		echo "<div class='row center-align'><a href='#appeditor' class='modal-editapps waves-effect btn-flat white-text' style='background-color: "; echo getSiteColor(); echo "'>Manage</a></div>";
	}
?>

<script>

	$(function() {
		//Load Masonry
		function checkWidthApps() {
			if ($(window).width() > 600) {
				//Sortable settings
				$( ".appssort" ).sortable({
					cursorAt: { top: 25, left: 45 },
					update: function(event, ui) {
						var postData = $(this).sortable('serialize');
						<?php
							echo "$.post('$portal_root/modules/apps/apps_save_order.php', {list: postData})";
						?>
						.done(function() {
							if (typeof loadOtherCardsApps == 'function') {
								loadOtherCardsApps();
							}
						});
					}
				});
			}
		}
		checkWidthApps();

		//Make the Icons Clickable
		$(".app").unbind().click(function(event) {

			//Open link
			event.preventDefault();
			window.open($(this).find("a").attr("href"), '_blank');

			//Close dock
		    $("#viewapps_arrow").hide();
		    $('#viewapps').closeModal({ in_duration: 0, out_duration: 0, });

	    //Track click
	    var linktitle = '/#apps/'+$(this).find("a").text().trim()+'/';
	    ga('set', 'page', linktitle);
			ga('send', 'pageview');

		});

		//Hide truncate on hover
		$(".app").hover(function() {
			$(this).find('.applink').toggleClass("truncate");
		});

		//Apps Modal
		<?php
		if(admin()){
		?>
		    $('.modal-editapps').leanModal({
					in_duration: 0,
					out_duration: 0,
					ready: function() {
						$('.modal-content').scrollTop(0);
				    $("#viewapps_arrow").hide();
						$("#viewloadappeditorloader").show();

						$('#loadappeditor').load('modules/apps/app_editor_content.php', function(){
							$("#viewloadappeditorloader").hide();
						});

						$('#viewapps').closeModal({ in_duration: 0, out_duration: 0, });
					},
				});
		<?php
		}
		?>
	});

</script>
