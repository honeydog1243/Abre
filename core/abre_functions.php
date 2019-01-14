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

	require_once(dirname(__FILE__) . '/abre_session.php');
	if(session_id() == '') {
		session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
		session_start();
	}

	//$portal_root = getConfigPortalRoot();
	$portal_private_root = getConfigPortalPrivateRoot();

	//Include required files
	$cloudsetting = getenv("USE_GOOGLE_CLOUD");
	if ($cloudsetting=="true")
		require(dirname(__FILE__). '/../vendor/autoload.php');
	use Google\Cloud\Storage\StorageClient;

	// This function caches the db call using a static variable
	function getConfigSettings($siteID, $value) {
		static $configData = null;
		if(is_null($configData)) {
			$configData = loadConfigData($siteID);
		}
		return $configData->$value;
	}

	function loadConfigData($siteID) {
		include "abre_dbconnect.php";

		$sql = "SELECT config_settings FROM abre_tenant_map WHERE siteID = $siteID";
		$query = $db->query($sql);
		if($query) {
			$row = $query->fetch_assoc();
			$options = $row['config_settings'];
		}
		$db->close();
		return isset($options) && $options != "" ? json_decode($options) : "";
	}

	function getConfigPortalRoot() {
		return getConfigSettings($_SESSION['siteID'], "portal_root");
	}

	function getConfigPortalPrivateRoot() {
		$cloudsetting = getenv("USE_GOOGLE_CLOUD");
		if ($cloudsetting=="true") return null;
		return getConfigSettings($_SESSION['siteID'], "portal_private_root");
	}

	function getConfigSiteGafeDomain() {
		return getConfigSettings($_SESSION['siteID'], "SITE_GAFE_DOMAIN");
	}

	function getConfigDBKey() {
		return getConfigSettings($_SESSION['siteID'], "DB_KEY");
	}

	function getConfigGCProject() {
		return getConfigSettings($_SESSION['siteID'], "GC_PROJECT");
	}

	function getConfigGCBucket() {
		return getConfigSettings($_SESSION['siteID'], "GC_BUCKET");
	}

	function getConfigGoogleClientID() {
		return getConfigSettings($_SESSION['siteID'], "GOOGLE_CLIENT_ID");
	}

	function getConfigGoogleClientSecret() {
		return getConfigSettings($_SESSION['siteID'], "GOOGLE_CLIENT_SECRET");
	}

	function getConfigGoogleApiKey() {
		return getConfigSettings($_SESSION['siteID'], "GOOGLE_API_KEY");
	}

	function getConfigGoogleHD() {
		return getConfigSettings($_SESSION['siteID'], "GOOGLE_HD");
	}

	function getConfigPortalCookieKey() {
		return getConfigSettings($_SESSION['siteID'], "PORTAL_COOKIE_KEY");
	}

	function getConfigPortalCookieName() {
		return getConfigSettings($_SESSION['siteID'], "PORTAL_COOKIE_NAME");
	}

	function getConfigPortalPathRoot() {
		return $_SERVER['DOCUMENT_ROOT'];
	}

	function getConfigGoogleRedirect() {
		return getConfigSettings($_SESSION['siteID'], "GOOGLE_REDIRECT");
	}

	function getConfigGoogleScopes() {
		$value = getConfigSettings($_SESSION['siteID'], "GOOGLE_SCOPES");
		if($value == "") {
			return $value;
		} else {
			return unserialize($value);
		}
	}

	function getConfigStreamCache() {
		return getConfigSettings($_SESSION['siteID'], "STREAM_CACHE");
	}

	function getConfigSiteMode() {
		return getConfigSettings($_SESSION['siteID'], "SITE_MODE");
	}

	function useApi() {

		if(isset($_SESSION['api_url'])){
			$url = $_SESSION['api_url'];
			return true;
		}

		return false;
	}

	//Encryption function
	function encrypt($string, $encryption_key, $siteKey = NULL){
		if(is_null($siteKey)){
			if(getenv("USE_GOOGLE_CLOUD") == "true"){
				$encryption_key = getConfigDBKey();
			}else{
				$encryption_key = constant("DB_KEY");
			}
		}else{
			$encryption_key = $siteKey;
		}
		return rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $encryption_key, $string, MCRYPT_MODE_ECB)));
	}

	//Decryption function
	function decrypt($string, $encryption_key, $siteKey = NULL){
		if(is_null($siteKey)){
			if(getenv("USE_GOOGLE_CLOUD") == "true"){
				$encryption_key = getConfigDBKey();
			}else{
				$encryption_key = constant("DB_KEY");
			}
		}else{
			$encryption_key = $siteKey;
		}
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $encryption_key, base64_decode($string), MCRYPT_MODE_ECB));
	}

	//Find user ID in directory module given an email
	function finduserid($email){
		$sql = "SELECT id FROM directory WHERE email = '$email' AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		while($row = $result->fetch_assoc()){
			$id = $row["id"];
			return $id;
		}
	}

	//Determine if users is superadmin or district admin
	function admin() {
		return getCurrentUser()->superadmin || getCurrentUser()->admin;
	}

	//Determine if a user is a superadmin
	function superadmin() {
		return getCurrentUser()->superadmin;
	}

	// This function caches the db call using a static variable
	function getCurrentUser() {
		static $currentUser = null;
		if(is_null($currentUser)) {
			$currentUser = lookupCurrentUser();
		}
		return $currentUser;
	}

	function lookupCurrentUser() {
		include "abre_dbconnect.php";
		$sql = "SELECT admin, superadmin FROM users
						WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		$row = $result->fetch_assoc();

		$user = new stdClass;
		$user->admin = $row["admin"];
		$user->superadmin = $row["superadmin"];

		$db->close();
		return $user;
	}



	//Find user ID given an email
	function finduseridcore($email){
		include "abre_dbconnect.php";
		$sql = "SELECT id FROM users WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		while($row = $result->fetch_assoc()){
			$id = $row["id"];
			return $id;
		}
	}

	//determine if user is stream and headline administrator
	function isStreamHeadlineAdministrator(){
		$email = $_SESSION['escapedemail'];
		include "abre_dbconnect.php";
		$sql = "SELECT role FROM directory WHERE email = '$email' AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		while($row = $result->fetch_assoc()){
			$role = decrypt($row["role"], "");
			if(strpos($role, 'Stream and Headline Administrator') !== false) {
				return true;
			}
		}
		return false;
	}

	//returns an array containing all school codes and school names for a given
	//district
	function getAllSchoolCodesAndNames(){
		require('abre_dbconnect.php');
		$schoolResults = array();
		if($db->query("SELECT * FROM Abre_Students LIMIT 1")){
			$sql = "SELECT SchoolCode, SchoolName FROM Abre_Students WHERE siteID = '".$_SESSION['siteID']."' ORDER BY SchoolCode";
			$query = $db->query($sql);
			while($results = $query->fetch_assoc()){
				if($results['SchoolCode'] == ''){
					continue;
				}else{
					$schoolResults[$results['SchoolCode']] = $results['SchoolName'];
				}
			}
		}
		$db->close();
		return $schoolResults;
	}

	//returns an array containing all school codes for a district
	function getAllSchoolCodes(){
		require('abre_dbconnect.php');
		$schoolCodes = array();
		if($db->query("SELECT * FROM Abre_Students LIMIT 1")){
			$sql = "SELECT SchoolCode FROM Abre_Students WHERE siteID = '".$_SESSION['siteID']."' ORDER BY SchoolCode";
			$query = $db->query($sql);
			while($results = $query->fetch_assoc()){
					array_push($schoolCodes, $results['SchoolCode']);
				}
			}
		if(sizeof($schoolCodes) != 0){
			$schoolCodes = array_unique($schoolCodes, SORT_REGULAR);
		}
		$db->close();
		return $schoolCodes;
	}

	//set verified session data for parent
	/* the first part of this function finds students who have a registered
	parent in the abre parent contact table, but not claimed in the
	users_parents table. We wanted to make parents who are registered as a contact
	for the school to be able to "fastpass" registering a student token */
	function isVerified(){
    if($_SESSION['usertype'] == 'parent'){
			include "abre_dbconnect.php";
			$sql = "SELECT id FROM users_parent WHERE email LIKE '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."';";
      $result = $db->query($sql);
      $row = $result->fetch_assoc();
      $parent_id = $row["id"];

      // if($db->query("SELECT * FROM student_tokens LIMIT 1") && $db->query("SELECT * FROM users_parent LIMIT 1")
      //     && $db->query("SELECT * FROM Abre_Students LIMIT 1") && $db->query("SELECT * FROM Abre_ParentContacts LIMIT 1")){
			// 		//see if email matches any records
	    //     $sql = "SELECT StudentID FROM Abre_ParentContacts WHERE Email1 LIKE '".$_SESSION['useremail']."' AND primary_contact = 'Y' AND siteID = '".$_SESSION['siteID']."'";
	    //     $result = $db->query($sql);
	    //     while($row = $result->fetch_assoc()){
			// 			//for records that match find kids associated with that email
	    //       $sql2 = "SELECT token, studentId FROM student_tokens WHERE studentId = '".$row['StudentID']."' AND siteID = '".$_SESSION['siteID']."'";
	    //       $result2 = $db->query($sql2);
	    //       while($row2 = $result2->fetch_assoc()){
			// 				$studenttokenencrypted = $row2['token'];
	    //         $studentId = $row2['studentId'];
			//
	    //         //Check to see if student has already been claimed by parent
	    //         $sqlcheck = "SELECT COUNT(*) FROM parent_students WHERE student_token = '$studenttokenencrypted' AND parent_id = $parent_id AND studentId = '$studentId' AND siteID = '".$_SESSION['siteID']."'";
	    //         $resultcheck = $db->query($sqlcheck);
			// 				$resultrow = $resultcheck->fetch_assoc();
	    //         $numrows2 = $resultrow["COUNT(*)"];
			//
	    //         //this parent does not have access
	    //         if($numrows2 == 0 && $_SESSION['useremail'] != ''){
			// 					$stmt = $db->stmt_init();
	    //           $sql = "INSERT INTO parent_students (parent_id, student_token, studentId, siteID) VALUES (?, ?, ?, ?)";
	    //           $stmt->prepare($sql);
	    //           $stmt->bind_param("issi", $parent_id, $studenttokenencrypted, $row2['studentId'], $_SESSION['siteID']);
	    //           $stmt->execute();
	    //           $stmt->close();
			// 			}
			// 		}
			// 	}
			// }
      // $db->close();
			//
      // include "abre_dbconnect.php";
      if($db->query("SELECT * FROM student_tokens LIMIT 1") && $db->query("SELECT * FROM users_parent LIMIT 1")){
				$sql = "SELECT student_token FROM parent_students WHERE parent_id = $parent_id AND siteID = '".$_SESSION['siteID']."'";
        if($result = $db->query($sql)){
					$_SESSION['auth_students'] = '';
          while($row = $result->fetch_assoc()){
						$sql2 = "SELECT studentId FROM student_tokens WHERE token = '".$row['student_token']."' AND siteID = '".$_SESSION['siteID']."'";
            $result2 = $db->query($sql2);
            $row2 = $result2->fetch_assoc();
            $_SESSION['auth_students'] .= $row2['studentId'].',';
          }
          $_SESSION['auth_students'] = rtrim($_SESSION['auth_students'], ", ");
				}
			}
			$db->close();
		}
	}

	//Query the database
	function databasequery($query){
		include "abre_dbconnect.php";
		$result = $db->query($query);
		$rowarray = array();
		while($row = $result->fetch_assoc()){
			array_push($rowarray, $row);
		}
		$db->close();

		return $rowarray;
	}

	//Save Screenshot to server
	function savescreenshot($website, $filename){
		//Get Image and Use Google Page Speed API
		$website = $website;
		$api = "https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$website&screenshot=true";
		$string = file_get_contents($api);
		$json = json_decode($string, true);

		//Get Data from JSON
		$data = $json['screenshot']['data'];

		//Replace characters for correct decode
		$data = str_replace("_","/",$data);
		$data = str_replace("-","+",$data);

		//Decode base64
		$data = base64_decode($data);

		//Save image to server
		$im = imagecreatefromstring($data);

		$cloudsetting = getenv("USE_GOOGLE_CLOUD");
		if ($cloudsetting=="true") {

			$storage = new StorageClient([
				'projectId' => getenv("GC_PROJECT")
			]);
			$bucket = $storage->bucket(getenv("GC_BUCKET"));

			$cloud_dir = "private_html/guide/";

			ob_start ();
			imagejpeg($im);
			$image_data = ob_get_contents ();
			ob_end_clean ();

			$options = [
				'resumable' => true,
				'name' => $cloud_dir . $fileName,
				'metadata' => [
					'contentLanguage' => 'en'
				]
			];
			$upload = $bucket->upload(
				$image_data,
				$options
			);
		}
		else {
			if(!file_exists("../../../$portal_private_root/guide")){
				mkdir("../../../$portal_private_root/guide", 0777, true);
			}
			imagejpeg($im, "../../../$portal_private_root/guide/$filename");
		}
	}

	// This function caches the db call using a static variable
	function getSettingsDbValue($value) {
		static $settingsData = null;
		if(is_null($settingsData)) {
			$settingsData = loadSettingsDbValue();
		}
		if(!$settingsData) return null;
		return $settingsData->$value;
	}

	function loadSettingsDbValue() {
		include "abre_dbconnect.php";
		if (!isset($_SESSION['siteID'])) return "";
		$sql = "SELECT options FROM settings WHERE siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$query = $db->query($sql);
		if($query) {
			$row = $query->fetch_assoc();
			$options = $row['options'];
		}
		$db->close();
		return isset($options) && $options != "" ? json_decode($options) : "";
	}

	function getSiteTitle(){
		$valuereturn = getSettingsDbValue('sitetitle');
		if($valuereturn == ""){ $valuereturn = "Abre"; }

		return $valuereturn;
	}

	function getSiteColor(){
		$valuereturn = getSettingsDbValue('sitecolor');
		if($valuereturn == ""){ $valuereturn = "#2B2E4A"; }

		return $valuereturn;
	}

	function getSiteDescription(){
		$valuereturn = getSettingsDbValue('sitedescription');
		if($valuereturn == ""){ $valuereturn = "Abre Open Platform for Education"; }

		return $valuereturn;
	}

	function getSiteLoginText(){
		$valuereturn = getSettingsDbValue('sitelogintext');
		if($valuereturn == ""){ $valuereturn = "Open Platform for Education"; }

		return $valuereturn;
	}

	function getSiteAnalytics(){
		return getSettingsDbValue('siteanalytics');
	}

	function getSiteAnalyticsViewId(){
		return getSettingsDbValue('analyticsViewId');
	}

	function getSiteAdminEmail(){
		return getSettingsDbValue('siteadminemail');
	}

	function getSiteCerticaBaseUrl(){
		return getSettingsDbValue('certicabaseurl');
	}

	function getSiteCerticaAccessKey(){
		return getSettingsDbValue('certicaaccesskey');
	}

	function getSiteStudentDomain(){
		return getSettingsDbValue('studentdomain');
	}

	function getSiteStudentDomainRequired(){
		return getSettingsDbValue('studentdomainrequired');
	}

	function getStaffStudentMatch(){
		$valuereturn = getSettingsDbValue('staffstudentdomainsame');
		if($valuereturn == ""){ $valuereturn = "unchecked"; }

		return $valuereturn;
	}

	function getSiteLogo(){
		$valuereturn = getSettingsDbValue('sitelogo');
		if($valuereturn == ""){
			$valuereturn = "/core/images/abre/abre_glyph.png";
		}else{
			if($valuereturn != '/core/images/abre/abre_glyph.png'){

				$cloudsetting = getenv("USE_GOOGLE_CLOUD");
				if ($cloudsetting=="true") {
					$bucket = getenv("GC_BUCKET");
					$valuereturn = "https://storage.googleapis.com/$bucket/content/$valuereturn";
				}
				else {
					$valuereturn = "/content/$valuereturn";
				}
			}else{
				$valuereturn="/core/images/abre/abre_glyph.png";
			}
		}

		return $valuereturn;
	}

	function getSiteAbreCommunity(){
		return getSettingsDbValue('abre_community');
	}

	function getSiteCommunityFirstName(){
		return getSettingsDbValue('community_first_name');
	}

	function getSiteCommunityLastName(){
		return getSettingsDbValue('community_last_name');
	}

	function getSiteCommunityEmail(){
		return getSettingsDbValue('community_email');
	}

	function getSiteCommunityUsers(){
		return getSettingsDbValue('community_users');
	}

	function getIntegrationsDbValue($value){
		include "abre_dbconnect.php";
		$sql2 = "SELECT integrations FROM settings WHERE siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$result2 = $db->query($sql2);
		if($result2){
			$row = $result2->fetch_assoc();
			$options = $row["integrations"];
			if($options != ""){
				$options = json_decode($options);
				if(isset($options->$value)){
					$valuereturn = $options->$value;
				}else{
					$valuereturn = "";
				}
			}else{
				$valuereturn = "";
			}
		}else{
			$sql = "ALTER TABLE `settings` ADD `integrations` text NOT NULL;";
			$db->multi_query($sql);
			$valuereturn = "";
		}
		$db->close();
		return $valuereturn;
	}

	function getSoftwareAnswersURL(){
		return getIntegrationsDbValue('softwareanswersurl');
	}

	function getSoftwareAnswersIdentifier(){
		return getIntegrationsDbValue('softwareanswersidentifier');
	}

	function getSoftwareAnswersKey(){
		return getIntegrationsDbValue('softwareanswerskey');
	}

	function getAuthenticationDbValue($value){
		include "abre_dbconnect.php";
		$sql2 = "SELECT authentication FROM settings WHERE siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$result2 = $db->query($sql2);
		if($result2){
			$row = $result2->fetch_assoc();
			$options = $row["authentication"];
			if($options != ""){
				$options = json_decode($options);
				if(isset($options->$value)){
					$valuereturn = $options->$value;
				}else{
					$valuereturn = "";
				}
			}else{
				$array = [
							"googleclientid" => getConfigGoogleClientID(),
							"googleclientsecret" => encrypt(getConfigGoogleClientSecret(), ''),
							"googlesigningroups" => ["staff", "students"]
								];
				$json = json_encode($array);

				$stmt = $db->stmt_init();
				$sql = "UPDATE settings SET authentication = ? WHERE siteID = ?";
				$stmt->prepare($sql);
				$stmt->bind_param("si", $json, $_SESSION['siteID']);
				$stmt->execute();
				$stmt->close();

				if($value == "googleclientid"){
					$valuereturn = $array['googleclientid'];
				}elseif($value == "googleclientsecret"){
					$valuereturn = decrypt($array['googleclientsecret'], "");
				}elseif($value == 'googlesigningroups'){
					$valuereturn = $array['googlesigningroups'];
				}else{
					$valuereturn = "";
				}
			}
		}else{
			$sql = "ALTER TABLE `settings` ADD `authentication` text NOT NULL;";
			$db->multi_query($sql);

			$array = [
						"googleclientid" => getConfigGoogleClientID(),
						"googleclientsecret" => encrypt(getConfigGoogleClientSecret(), ''),
						"googlesigningroups" => ["staff", "students"]
							];
			$json = json_encode($array);

			$stmt = $db->stmt_init();
			$sql = "UPDATE settings SET authentication = ? WHERE siteID = ?";
			$stmt->prepare($sql);
			$stmt->bind_param("si", $json, $_SESSION['siteID']);
			$stmt->execute();
			$stmt->close();

			if($value == "googleclientid"){
				$valuereturn = $array['googleclientid'];
			}elseif($value == "googleclientsecret"){
				$valuereturn = decrypt($array['googleclientsecret'], "");
			}elseif($value == 'googlesigningroups'){
				$valuereturn = $array['googlesigningroups'];
			}else{
				$valuereturn = "";
			}
		}
		$db->close();
		return $valuereturn;
	}

	function getSiteGoogleClientId(){
		return getAuthenticationDbValue('googleclientid');
	}

	function getSiteGoogleClientSecret(){
		$valuereturn = getAuthenticationDbValue('googleclientsecret');
		if($valuereturn == ""){
			return "";
		}else{
			return decrypt($valuereturn, '');
		}
	}

	function getSiteGoogleSignInGroups($group){
		$valuereturn = getAuthenticationDbValue('googlesigningroups');
		if($valuereturn == ""){
			return "";
		}else{
			if(in_array($group, $valuereturn)){
				return "checked";
			}else{
				return "";
			}
		}
	}

	function getSiteFacebookClientId(){
		return getAuthenticationDbValue('facebookclientid');
	}

	function getSiteFacebookClientSecret(){
		$valuereturn = getAuthenticationDbValue('facebookclientsecret');
		if($valuereturn == ""){
			return "";
		}else{
			return decrypt($valuereturn, '');
		}
	}

	function getSiteFacebookSignInGroups($group){
		$valuereturn = getAuthenticationDbValue('facebooksigningroups');
		if($valuereturn == ""){
			return "";
		}else{
			if(in_array($group, $valuereturn)){
				return "checked";
			}else{
				return "";
			}
		}
	}

	function getSiteMicrosoftClientId(){
		return getAuthenticationDbValue('microsoftclientid');
	}

	function getSiteMicrosoftClientSecret(){
		$valuereturn = getAuthenticationDbValue('microsoftclientsecret');
		if($valuereturn == ""){
			return "";
		}else{
			return decrypt($valuereturn, '');
		}
	}

	function getSiteMicrosoftSignInGroups($group){
		$valuereturn = getAuthenticationDbValue('microsoftsigningroups');
		if($valuereturn == ""){
			return "";
		}else{
			if(in_array($group, $valuereturn)){
				return "checked";
			}else{
				return "";
			}
		}
	}

	function getUpdateRequiredDbValue(){
		include "abre_dbconnect.php";
		$sql2 = "SELECT `update_required` FROM settings WHERE siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$result2 = $db->query($sql2);
		if($result2){
			$row = $result2->fetch_assoc();
			if($row["update_required"] == 0){
				return false;
			}else{
				return true;
			}
		}else{
			$sql = "ALTER TABLE `settings` ADD `update_required` int(11) NOT NULL DEFAULT '1';";
			$db->multi_query($sql);
			return true;
		}
	}

	function isAppInstalled($appName) {
		$app = getAppOverviewData();
		return isset($app[$appName]) && isset($app[$appName]["installed"]) && $app[$appName]["installed"];
	}

	function isAppActive($appName) {
		$app = getAppOverviewData();
		return isset($app[$appName]) && isset($app[$appName]["active"]) && $app[$appName]["active"];
	}

	function getAppOverviewData() {
		static $appData = null;
		if(is_null($appData)) {
			$appData = loadAppOverviewData();
		}
		return $appData;
	}

	function loadAppOverviewData() {
		include "abre_dbconnect.php";
		$sql = "SELECT app, active, installed FROM apps_abre
						WHERE siteID = ?";

		$stmt = $db->stmt_init();
		$stmt->prepare($sql);
		$stmt->bind_param("i", $_SESSION['siteID']);
		$stmt->execute();
		$stmt->bind_result($name, $active, $installed);

		$apps = [];
		while($stmt->fetch()) {
			$apps[$name] = ["active" => $active, "installed" => $installed];
		}

		$db->close();

		$apps = addNonDbApps($apps);
		return $apps;
	}

	function addNonDbApps($apps) {
		$dbApps = ["calendar", "classroom", "drive", "mail", "modules"];

		foreach($dbApps as $app) {
			$apps[$app] = ["active" => true, "installed" => true];
		}

		return $apps;
	}

	function linkify($value, $protocols = array('http', 'mail'), array $attributes = array()){
		// Link attributes
		$attr = '';
    foreach($attributes as $key => $val){
        $attr = ' ' . $key . '="' . htmlentities($val) . '"';
    }
    $links = array();

    // Extract existing links and tags
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links){ return '<' . array_push($links, $match[1]) . '>'; }, $value);

    // Extract text links for each protocol
    foreach ((array)$protocols as $protocol){
        switch ($protocol){
            case 'http':
            case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\" target=\"_blank\" style='color: ".getSiteColor()."'>$link</a>") . '>'; }, $value); break;
            case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\" target=\"_blank\" style='color: ".getSiteColor()."'>{$match[1]}</a>") . '>'; }, $value); break;
            case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\" target=\"_blank\" style='color: ".getSiteColor()."'>{$match[0]}</a>") . '>'; }, $value); break;
            default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\" target=\"_blank\" style='color: ".getSiteColor()."'>{$match[1]}</a>") . '>'; }, $value); break;
        }
    }
    // Insert all link
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links){ return $links[$match[1] - 1]; }, $value);
  }

	//Insert into the database
	function vendorLinkGet($call){
		$VendorLinkURL = getSoftwareAnswersURL();
		$vendorIdentifier = getSoftwareAnswersIdentifier();
		$vendorKey = getSoftwareAnswersKey();
		$userID = "";
		$requestDate = gmdate('D, d M Y H:i:s').' GMT';
		$userName = $vendorIdentifier."|".$userID."|".$requestDate;
		$password = $vendorIdentifier.$userID.$requestDate.$vendorKey;
		$hmacData = $vendorIdentifier.$userID.$requestDate.$vendorKey;
		$hmacSignature = base64_encode(pack("H*", sha1($hmacData)));
		$vlauthheader = $vendorIdentifier."||".$hmacSignature;

		$url = $call;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('VL-Authorization: '.$vlauthheader, 'Date: '.$requestDate));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$json = json_decode($result,true);

		return $json;
	}

	//Insert into the database
	function vendorLinkPost($call, $fields){
		$VendorLinkURL = getSoftwareAnswersURL();
		$vendorIdentifier = getSoftwareAnswersIdentifier();
		$vendorKey = getSoftwareAnswersKey();
		$userID = "";
		$requestDate = gmdate('D, d M Y H:i:s').' GMT';
		$userName = $vendorIdentifier."|".$userID."|".$requestDate;
		$password = $vendorIdentifier.$userID.$requestDate.$vendorKey;
		$hmacData = $vendorIdentifier.$userID.$requestDate.$vendorKey;
		$hmacSignature = base64_encode(pack("H*", sha1($hmacData)));
		$vlauthheader = $vendorIdentifier."||".$hmacSignature;

		$url = $call;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('VL-Authorization: '.$vlauthheader, 'Date: '.$requestDate, 'Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$json = json_decode($result,true);

		return $json;
	}

	//Insert into the database
	function vendorLinkPut($call, $fields){
		$VendorLinkURL = getSoftwareAnswersURL();
		$vendorIdentifier = getSoftwareAnswersIdentifier();
		$vendorKey = getSoftwareAnswersKey();
		$userID = "";
		$requestDate = gmdate('D, d M Y H:i:s').' GMT';
		$userName = $vendorIdentifier."|".$userID."|".$requestDate;
		$password = $vendorIdentifier.$userID.$requestDate.$vendorKey;
		$hmacData = $vendorIdentifier.$userID.$requestDate.$vendorKey;
		$hmacSignature = base64_encode(pack("H*", sha1($hmacData)));
		$vlauthheader = $vendorIdentifier."||".$hmacSignature;

		$url = $call;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('VL-Authorization: '.$vlauthheader, 'Date: '.$requestDate, 'Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$json = json_decode($result,true);

		return $json;
	}

	//returns a string listing all scopes the user has access too.
	function getCurrentGoogleScopes($access_token){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=".$access_token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		$json = json_decode($result, true);
		return $json["scope"];
	}

	//calls the microsoft graph api to get a active token and stores it in the session
	function getActiveMicrosoftAccessToken(){
		//token has expired, we need to get a new one
		$fields = array(
			'client_id' => urlencode(getSiteMicrosoftClientId()),
			'redirect_uri' => urlencode(''),
			'grant_type' => 'refresh_token',
			'client_secret' => urlencode(getSiteMicrosoftClientSecret()),
			'refresh_token' => $_SESSION['access_token']['refresh_token'],
			'scope' => urlencode('openid profile user.read mail.read')
		);

		//url-ify the data for the POST
		$fields_string = "";
		foreach($fields as $key=>$value){
			$fields_string .= $key.'='.$value.'&';
		}
		rtrim($fields_string, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://login.microsoftonline.com/common/oauth2/v2.0/token");
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);

		$accessTokenArray = json_decode($result, true);
		$_SESSION['access_token']['access_token'] = $accessTokenArray['access_token'];
	}

	function getRestrictions(){
		include "abre_dbconnect.php";
		//get schools codes for the staff member
		$schoolCodeArray = array();
		if($_SESSION['usertype'] == "staff"){
			if($db->query("SELECT * FROM Abre_Staff LIMIT 1")){
				$sql = "SELECT SchoolCode FROM Abre_Staff WHERE EMail1 = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
				$resultrow = $db->query($sql);
				$count = mysqli_num_rows($resultrow);
				while($result = $resultrow->fetch_assoc()){
						array_push($schoolCodeArray, $result['SchoolCode']);
				}
			}
		}

		if($_SESSION['usertype'] == "student"){
			$sql = "SELECT StudentID FROM Abre_AD WHERE Email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
			$query = $db->query($sql);
			$result = $query->fetch_assoc();
			$studentID = $result["StudentID"];
			if(isset($studentID)){
				if($db->query("SELECT * FROM Abre_Students LIMIT 1")){
					$sql = "SELECT SchoolCode FROM Abre_Students WHERE StudentId = '$studentID' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
					$resultrow = $db->query($sql);
					$result = $resultrow->fetch_assoc();
					array_push($schoolCodeArray, $result['SchoolCode']);
				}
			}
		}

		return $schoolCodeArray;
	}

	//Admin Check
	function AdminCheck($email){
		include "abre_dbconnect.php";
		$contract = encrypt('Administrator', "");
		$sql = "SELECT COUNT(*) FROM directory WHERE email = '$email' AND contract = '$contract' AND siteID = '".$_SESSION['siteID']."'";
		$result = $db->query($sql);
		$returnrow = $result->fetch_assoc();
		$count = $returnrow["COUNT(*)"];
		$db->close();
		if($count >= 1){
			return true;
		}
		else{
			return false;
		}
	}

	//Resize Google Cloud Image
	function ResizeGCImage($image, $maxsize, $quality){

		$image = 'data://application/octet-stream;base64,' . base64_encode($image);

		$mime = getimagesize($image);
		$width = $mime[0];

		if ($width < 1000) {
			$data = explode( ',', $image );
			$image = base64_decode( $data[ 1 ]);
			return $image;
		}
		if($mime['mime'] == 'image/jpeg'){ $imagecreated = imagecreatefromjpeg($image); }
		if($mime['mime'] == 'image/jpg'){ $imagecreated = imagecreatefromjpeg($image); }
		if($mime['mime'] == 'image/png'){ $imagecreated = imagecreatefrompng($image); }
		if($mime['mime'] == 'image/gif'){ $imagecreated = imagecreatefromgif($image); }
		$imageScaled = imagescale($imagecreated, $maxsize);

		ob_start ();
		if($mime['mime'] == 'image/jpeg'){ imagejpeg($imageScaled, null, $quality); }
		if($mime['mime'] == 'image/jpg'){ imagejpeg($imageScaled, null, $quality); }
		if($mime['mime'] == 'image/png'){ imagepng($imageScaled, null, "8"); }
		if($mime['mime'] == 'image/gif'){ imagegif($imageScaled, null, $quality); }
		$image = ob_get_contents ();
		ob_end_clean ();

		imagedestroy($imagecreated);

		return $image;
	}

	//Resize Image
	function ResizeImage($image, $maxsize, $quality){
		$mime = getimagesize($image);
		$width = $mime[0];
		if ($width < 1000) return;
		if($mime['mime'] == 'image/jpeg'){ $imagecreated = imagecreatefromjpeg($image); }
		if($mime['mime'] == 'image/jpg'){ $imagecreated = imagecreatefromjpeg($image); }
		if($mime['mime'] == 'image/png'){ $imagecreated = imagecreatefrompng($image); }
		if($mime['mime'] == 'image/gif'){ $imagecreated = imagecreatefromgif($image); }
		$imageScaled = imagescale($imagecreated, $maxsize);
		if($mime['mime'] == 'image/jpeg'){ imagejpeg($imageScaled, $image, $quality); }
		if($mime['mime'] == 'image/jpg'){ imagejpeg($imageScaled, $image, $quality); }
		if($mime['mime'] == 'image/png'){ imagepng($imageScaled, $image, "8"); }
		if($mime['mime'] == 'image/gif'){ imagegif($imageScaled, $image, $quality); }
		imagedestroy($imagecreated);
	}

	//Get Staff Name Given Email
	function GetStaffName($email){
		include "abre_dbconnect.php";
		$email = strtolower($email);
		$emailEscaped = mysqli_real_escape_string($db, $email);

		//Check Staff Table
		$query = "SELECT FirstName, LastName FROM Abre_Staff WHERE EMail1 LIKE '$emailEscaped' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$dbreturn = databasequery($query);
		foreach ($dbreturn as $value){
			$FirstName = htmlspecialchars($value["FirstName"], ENT_QUOTES);
			$LastName = htmlspecialchars($value["LastName"], ENT_QUOTES);
			if($email == $_SESSION['useremail']){
				$db->close();
				return "$FirstName $LastName (You)";
			}
			else {
				$db->close();
				return "$FirstName $LastName";
			}
		}

		//Check Directory Table
		$query = "SELECT firstname, lastname FROM directory WHERE email LIKE '$emailEscaped' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$dbreturn = databasequery($query);
		foreach ($dbreturn as $value){
			$FirstName = htmlspecialchars($value["firstname"], ENT_QUOTES);
			$LastName = htmlspecialchars($value["lastname"], ENT_QUOTES);
			if($email == $_SESSION['useremail']){
				$db->close();
				return "$FirstName $LastName (You)";
			}
			else {
				$db->close();
				return "$FirstName $LastName";
			}
		}
		$db->close();

		return $email;
	}

	//Get Staff FirstName Given Email
	function GetStaffFirstName($email){
		include "abre_dbconnect.php";
		$email = strtolower($email);
		if($db->query("SELECT * FROM Abre_Staff LIMIT 1")){
			$query = "SELECT FirstName FROM Abre_Staff WHERE EMail1 LIKE '$email' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value){
				$FirstName = htmlspecialchars($value["FirstName"], ENT_QUOTES);
				return $FirstName;
			}
		}

		$query = "SELECT firstname FROM directory WHERE email LIKE '$email' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$dbreturn = databasequery($query);
		foreach ($dbreturn as $value){
			$FirstName = htmlspecialchars($value["firstname"], ENT_QUOTES);
			return $FirstName;
		}
		$db->close();
		return "";
	}

	//Get Staff LastName Given Email
	function GetStaffLastName($email){
		include "abre_dbconnect.php";
		$email = strtolower($email);
		if($db->query("SELECT * FROM Abre_Staff LIMIT 1")){
			$query = "SELECT LastName FROM Abre_Staff WHERE EMail1 LIKE '$email' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value){
				$LastName = htmlspecialchars($value["LastName"], ENT_QUOTES);
				return $LastName;
			}
		}

		$query = "SELECT lastname FROM directory WHERE email LIKE '$email' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$dbreturn = databasequery($query);
		foreach ($dbreturn as $value){
			$LastName = htmlspecialchars($value["lastname"], ENT_QUOTES);
			return $LastName;
		}
		$db->close();
		return "";
	}

	//Get Staff UniqueID Given Email
	function GetStaffUniqueID($email){
		include "abre_dbconnect.php";
		$email = strtolower($email);
		if($db->query("SELECT * FROM Abre_Staff LIMIT 1")){
			$query = "SELECT StaffID FROM Abre_Staff WHERE EMail1 LIKE '$email' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
			$dbreturn = databasequery($query);
			foreach ($dbreturn as $value){
				$StaffID = htmlspecialchars($value["StaffID"], ENT_QUOTES);
				return $StaffID;
			}
		}
		$db->close();
		return "";
	}

	//Get Student FirstName Given Email
	function GetStudentFirstName($email){
		include "abre_dbconnect.php";
		$email = strtolower($email);
		$query = "SELECT students.FirstName FROM Abre_AD
		JOIN Abre_Students AS students ON Abre_AD.StudentID = students.StudentId
		WHERE Abre_AD.Email LIKE '$email' AND students.siteID = $_SESSION[siteID] AND Abre_AD.siteID = $_SESSION[siteID]";
		$dbreturn = databasequery($query);
		foreach($dbreturn as $value){
			$FirstName = $value["FirstName"];
			return $FirstName;
		}
		$db->close();
		return "";
	}

	//Get Student LastName Given Email
	function GetStudentLastName($email){
		include "abre_dbconnect.php";
		$email = strtolower($email);
		$query = "SELECT students.LastName FROM Abre_AD
		JOIN Abre_Students AS students ON Abre_AD.StudentID = students.StudentId
		WHERE Abre_AD.Email LIKE '$email' AND students.siteID = $_SESSION[siteID] AND Abre_AD.siteID = $_SESSION[siteID]";
		$dbreturn = databasequery($query);
		foreach($dbreturn as $value){
			$LastName = $value["LastName"];
			return $LastName;
		}
		$db->close();
		return "";
	}

	//Get Student UniqueID Given Email
	function GetStudentUniqueID($email){
		include "abre_dbconnect.php";
		$email = strtolower($email);
		$query = "SELECT StudentID FROM Abre_AD WHERE Email LIKE '$email' AND siteID = '".$_SESSION['siteID']."' LIMIT 1";
		$dbreturn = databasequery($query);
		foreach ($dbreturn as $value){
			$StudentID = htmlspecialchars($value["StudentID"], ENT_QUOTES);
			return $StudentID;
		}
		$db->close();
		return "";
	}

	function sendFormEmailNotification($ownerEmail, $formName, $url){
		$to = $ownerEmail;
		$subject = "New Response";
		$message = 'Your form, '.$formName.', has a new response. <a href='.$url.'>View all responses now!</a>';
		$headers = "From: noreply@abre.io";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		$cloudsetting = getenv("USE_GOOGLE_CLOUD");
		if ($cloudsetting=="true") {
			$email = new \SendGrid\Mail\Mail();
			$email->setFrom("noreply@abre.io", null);
			$email->setSubject($subject);
			$email->addTo($to, null);
			$email->addContent("text/plain", $message);
			$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
			try {
				$response = $sendgrid->send($email);
			} catch (Exception $e) {
				//echo 'Caught exception: '. $e->getMessage() ."\n";
			}
		}
		else {
			mail($to,$subject,$message,$headers);
		}
	}

	function getOpenWidgets() {
		static $openWidgets = null;
		if(is_null($openWidgets)) {
			$openWidgets = loadOpenWidgets();
		}
		return $openWidgets;
	}

	function loadOpenWidgets() {
		require('abre_dbconnect.php');
		$sql = "SELECT widgets_open FROM profiles
						WHERE email = ? AND siteID = ?";

		$stmt = $db->stmt_init();
		$stmt->prepare($sql);
		$stmt->bind_param('si', $_SESSION['escapedemail'], $_SESSION['siteID']);
		$stmt->execute();
		$stmt->bind_result($widgetsOpen);

		return $stmt->fetch() ? explode(',', $widgetsOpen) : [];
	}

	//Display Widget
	function DisplayWidget($path, $icon, $title, $color, $url, $newtab) {
		$URLPath = "/modules/$path/widget_content.php";
		$active = in_array($path, getOpenWidgets()) ? "active" : "";
	?>
		<ul class="widget mdl-card mdl-shadow--2dp hoverable" style="width:100%;" data-collapsible="accordion">
			<li class="widgetli" data-path="<?= $path ?>">
				<div class="collapsible-header <?= $active ?>" data-path="<?= $URLPath ?>"
						data-widget="<?= $path ?>" style="border-top: solid 3px <?= $color ?>">
					<span class="widgeticonlink" data-link="<?= $url ?>" data-newtab="<?= $newtab ?>">
					<i class="material-icons" style="color: <?= $color ?>"><?= $icon ?></i>
					<span style="color:#000;"><?= $title ?></span>
					</span>
					<i class="right material-icons" style="color: #666; margin-right:2px;">expand_more</i>
				</div>
				<div class="collapsible-body" id="widgetbody_<?= $path ?>"></div>
			</li>
		</ul>
<?php
	}
?>
