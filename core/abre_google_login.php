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
	//Include required files
	require_once('abre_functions.php');
	require_once(dirname(__FILE__) . '/../api/authentication-api.php');
	//Load configuration settings
	$studentdomain = getSiteStudentDomain();
	$studentdomainrequired = getSiteStudentDomainRequired();
	$portal_root = getConfigPortalRoot();
	//Add staff profile picture to directory if entry exists and picture is empty
	function staffLogin(){
		$_SESSION['usertype'] = "staff";

	  include "abre_dbconnect.php";
		if($db->query("SELECT * FROM directory LIMIT 1")){
			$sql = "SELECT picture FROM directory WHERE email = '".$_SESSION['escapedemail']."' AND (picture = '' OR picture LIKE '%http%') AND siteID = '".$_SESSION['siteID']."'";
			$result = $db->query($sql);
			while($row = $result->fetch_assoc()){
				$currentpicture = $row['picture'];
				if($currentpicture != $_SESSION['picture']){
					$stmt = $db->stmt_init();
					$sql = "UPDATE directory SET picture = ? WHERE email = ? AND siteID = ?";
					$stmt->prepare($sql);
					$stmt->bind_param("ssi", $_SESSION['picture'], $_SESSION['useremail'], $_SESSION['siteID']);
					$stmt->execute();
					$stmt->close();
				}
			}
		}
	}

  function emailMatchCheck(){
		if(getStaffStudentMatch() == "checked" && !admin()){
			//Check to see if email is in Abre_Staff table
			include "abre_dbconnect.php";
			if($db->query("SELECT * FROM Abre_Staff LIMIT 1")){
				$sql = "SELECT count(*) FROM Abre_Staff WHERE EMail1 = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
				$result = $db->query($sql);
				$row = $result->fetch_assoc();
				$numrows = $row['count(*)'];
				if($numrows == 0){
					$_SESSION['usertype'] = "student";
				}else{
					staffLogin();
				}
			}
		}else{
			staffLogin();
		}
	}
	//Try to login the user, if they have revoked Google access, request access again
	try{
    	//Required configuration files
		require_once('abre_google_authentication.php');
		$cookie_name = getConfigPortalCookieName();
		$site_domain = getConfigSiteGafeDomain();
		//User is returning from closed browser that was logged in
		if(isset($_COOKIE[$cookie_name]) && !isset($_SESSION['access_token'])){
			include "abre_dbconnect.php";
			$cookieValue = $_COOKIE[$cookie_name];
			if($result = $db->query("SELECT * FROM users WHERE cookie_token = '$cookieValue' AND siteID = '".$_SESSION['siteID']."'")){
        		$getRefreshToken2 = mysqli_fetch_assoc(mysqli_query($db, "SELECT refresh_token FROM users WHERE cookie_token = '$cookieValue' AND siteID = '".$_SESSION['siteID']."'"));
				$refreshtoken2 = $getRefreshToken2['refresh_token'];
				$refreshtoken2 = json_decode($refreshtoken2, true);
				$client->setAccessToken($refreshtoken2);
				$_SESSION['access_token'] = $refreshtoken2;
				//Set Cookie for 7 Days
				setcookie($cookie_name, $cookieValue, time() + 86400 * 7, '/', '', true, true);
			}
			$db->close();
		}
		//Login the user
		if(isset($_GET['code'])){
			$client->fetchAccessTokenWithAuthCode($_GET['code']);
			$_SESSION['access_token'] = $client->getAccessToken();
      $_SESSION['auth_service'] = "google";
			$pagelocation = $portal_root;
			session_write_close();
			//Used for assessments and forms (if Chris doesn't remember what its for)
			if(isset($_SESSION["redirecturl"])){
				header("Location: $pagelocation/#".$_SESSION["redirecturl"]);
			}else{
				header("Location: $pagelocation");
			}
			exit();
		}
		//Set access token to make request
		if(isset($_SESSION['access_token'])){
			$client->setAccessToken($_SESSION['access_token']);
		}
		//Get basic user information if they are logged in
		if(!isset($_SESSION['facebook_access_token']) && !isset($_SESSION['google_parent_access_token']) && !isset($_SESSION['microsoft_access_token'])){
			if(isset($_SESSION['access_token']) && $client->getAccessToken()){
				include "abre_dbconnect.php";
				if(!isset($_SESSION['useremail'])){
					$client->setAccessToken($_SESSION['access_token']);
					$userData = $Service_Oauth2->userinfo->get();
					$userEmail = strtolower($userData["email"]);
					$_SESSION['useremail'] = $userEmail;
					$_SESSION['escapedemail'] = mysqli_real_escape_string($db, $userEmail);
					$userPicture = $userData['picture'];
					$_SESSION['picture'] = $userPicture;
	        $_SESSION['auth_service'] = "google";
					$_SESSION['usertype'] = NULL;
					$_SESSION['displayName'] = $userData['name'];
					if($studentdomain == NULL){ $studentdomain = $site_domain; }
			        $userdomain = substr($_SESSION['useremail'], strpos($_SESSION['useremail'], '@'));
			        $username = substr($_SESSION['useremail'], 0, strpos($_SESSION['useremail'], '@'));
					if($site_domain == $studentdomain){
	          			//Check for required chracters (if any)
						if(strcspn($username, $studentdomainrequired) != strlen($username)){
							$_SESSION['usertype'] = "student";
						}else if(strpos($site_domain, $userdomain) !== false || strpos($userdomain, $site_domain) !== false){
	            			//Check to see if settings page says staff and student emails are the same
							emailMatchCheck();
						}
          }else{
						if($studentdomainrequired == "" && (strpos($_SESSION['useremail'], $studentdomain) !== false)){
							$_SESSION['usertype'] = "student";
						}else{
	            if((strpos($_SESSION['useremail'], $studentdomain) !== false) && strcspn($username, $studentdomainrequired) != strlen($username)){
								$_SESSION['usertype'] = "student";
							}else if(strpos($site_domain, $userdomain) !== false){
								//Check to see if settings page says staff and student emails are the same
								emailMatchCheck();
							}
	          }
					}
					if($_SESSION['usertype'] != "staff" && $_SESSION['usertype'] != "student"){
						$_SESSION['invalid_credentials'] = $_SESSION['useremail'];
						$_SESSION['usertype'] = NULL;
						$_SESSION['useremail'] = NULL;
						unset($_SESSION['access_token']);
						session_write_close();
						header("Location: $portal_root");
						exit();
					}
				}
			}else{
				$authUrl = $client->createAuthUrl();
			}
			//Save the user information to Abre users database
			if(isset($_SESSION['access_token'])){
				if($_SESSION['usertype'] != ""){
					include "abre_dbconnect.php";
					if($result = $db->query("SELECT COUNT(*) FROM users WHERE email = '".$_SESSION['escapedemail']."' AND `refresh_token` LIKE '%refresh_token%' AND auth_service = '".$_SESSION['auth_service']."' AND siteID = '".$_SESSION['siteID']."'")){
	          			$resultrow = $result->fetch_assoc();
	          			$count = $resultrow["COUNT(*)"];
						if($count == 1){
							//If not already logged in, check and get a refresh token
							if(!isset($_SESSION['loggedin'])) { $_SESSION['loggedin'] = ""; }
							if($_SESSION['loggedin'] != "yes"){
								//Update the token (if contains refresh_token)
								$getTokenKeyOnly = $_SESSION['access_token'];
								$refreshTokenKey = json_encode($getTokenKeyOnly);
								if($refreshTokenKey != ""){
									if(strpos($refreshTokenKey, 'refresh_token') !== false){
										$stmt = $db->stmt_init();
										$sql = "UPDATE users SET refresh_token = ? WHERE email = ? AND auth_service = ? AND siteID = ?";
										$stmt->prepare($sql);
										$stmt->bind_param("sssi", $refreshTokenKey, $_SESSION['useremail'], $_SESSION['auth_service'], $_SESSION['siteID']);
										$stmt->execute();
										$stmt->close();
									}
								}
								//Get the token from the database
								$getRefreshToken = mysqli_fetch_assoc(mysqli_query($db, "SELECT refresh_token FROM users WHERE email = '".$_SESSION['escapedemail']."' AND auth_service = '".$_SESSION['auth_service']."' AND siteID = '".$_SESSION['siteID']."'"));
								$refreshtoken = $getRefreshToken['refresh_token'];
								$client->setAccessToken($refreshtoken);
								$refreshtoken = json_decode($refreshtoken, true);
								$_SESSION['access_token'] = $refreshtoken;
								//Set cookie for 7 days
								$sha1useremail = sha1($_SESSION['useremail']);
								$cookiekey = getConfigPortalCookieKey();
								$hash = sha1($cookiekey);
								$storetoken = $sha1useremail.$hash;
								setcookie($cookie_name, $storetoken, time()+86400 * 7, '/', '', true, true);
								//Mark that they have logged in
								$_SESSION['loggedin'] = "yes";
							}
						}else{
							$getTokenKeyOnly = json_encode($_SESSION['access_token']);
							//Insert Token if contains refresh_token, otherwise, force consent
							if(strpos($getTokenKeyOnly, 'refresh_token') !== false){
								$sha1useremail = sha1($_SESSION['useremail']);
								$cookiekey = getConfigPortalCookieKey();
								$hash = sha1($cookiekey);
								$storetoken = $sha1useremail.$hash;
								$noRefreshTokenSql = "SELECT COUNT(*) FROM users WHERE email = '".$_SESSION['escapedemail']."' AND refresh_token = '' AND cookie_token = '' AND auth_service = '".$_SESSION['auth_service']."' AND siteID = '".$_SESSION['siteID']."'";
								$noRefreshTokenQuery = $db->query($noRefreshTokenSql);
								$noRefreshTokenResult = $noRefreshTokenQuery->fetch_assoc();
								$noRefreshTokenCount = $noRefreshTokenResult["COUNT(*)"];
								if($noRefreshTokenCount){
									$stmt = $db->stmt_init();
									$sql = "UPDATE users SET refresh_token = ?, cookie_token = ?, auth_service = ?, siteID = ? WHERE email = ?";
									$stmt->prepare($sql);
									$stmt->bind_param("sssis", $getTokenKeyOnly, $storetoken, $_SESSION['auth_service'], $_SESSION['siteID'], $_SESSION['useremail']);
									$stmt->execute();
									$stmt->close();
								}else{
									$stmt = $db->stmt_init();
									$sql = "DELETE FROM users WHERE email = ? AND auth_service = ? AND siteID = ?";
									$stmt->prepare($sql);
									$stmt->bind_param("ssi", $_SESSION['useremail'], $_SESSION['auth_service'], $_SESSION['siteID']);
									$stmt->execute();
									$stmt->close();
									$stmt = $db->stmt_init();
									$sql = "INSERT INTO users (email, refresh_token, cookie_token, auth_service, siteID) VALUES (?, ?, ?, ?, ?)";
									$stmt->prepare($sql);
									$stmt->bind_param("ssssi", $_SESSION['useremail'], $getTokenKeyOnly, $storetoken, $_SESSION['auth_service'], $_SESSION['siteID']);
									$stmt->execute();
									$stmt->close();
								}
								//Set cookie for 7 days
								setcookie($cookie_name, $storetoken, time()+86400 * 7, '/', '', true, true);
							}else{
								$stmt = $db->stmt_init();
								$sql = "DELETE FROM users WHERE email = ? AND auth_service = ? AND siteID = ?";
								$stmt->prepare($sql);
								$stmt->bind_param("ssi", $_SESSION['useremail'], $_SESSION['auth_service'], $_SESSION['siteID']);
								$stmt->execute();
								$stmt->close();
								//Remove cookies and destroy session
								if(isset($_SERVER['HTTP_COOKIE'])){
								    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
								    foreach($cookies as $cookie){
								        $parts = explode('=', $cookie);
								        $name = trim($parts[0]);
								        setcookie($name, '', time()-1000);
								        setcookie($name, '', time()-1000, '/');
								    }
								}
								$client->revokeToken();
								header("Location: $portal_root");
								exit();
							}
						}
					}
					if (isset($api_url)) {
						$_SESSION['api_url']=$api_url;
						ApiConnection::signIn();
					}
					$db->close();
				}
			}
		}
	}catch(Exception $x){

		error_log("CATCH: ".$x->getMessage()." Session: ".http_build_query($_SESSION));
		$authUrl = $client->createAuthUrl();
		//Remove cookies and destroy session
		if(isset($_SERVER['HTTP_COOKIE'])){
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie){
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		session_destroy();
		$client->revokeToken();
	}
?>