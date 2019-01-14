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
  require_once('abre_functions.php');
  require(dirname(__FILE__). '/facebook/src/Facebook/autoload.php');
  $portal_root = getConfigPortalRoot();

  $fb = new Facebook\Facebook([
    'app_id' => getSiteFacebookClientId(),
    'app_secret' => getSiteFacebookClientSecret(),
    'default_graph_version' => 'v2.9',
  ]);

  $helper = $fb->getRedirectLoginHelper();

  try{
    $accessToken = $helper->getAccessToken($portal_root.'/core/abre_facebook_login_helper.php');
  }catch(Facebook\Exceptions\FacebookResponseException $e){
    // When Graph returns an error
    error_log('Graph returned an error: ' . $e->getMessage());
    exit;
  }catch(Facebook\Exceptions\FacebookSDKException $e){
    // When validation fails or other local issues
    error_log('Facebook SDK returned an error: ' . $e->getMessage());
    exit;
  }

  if(!isset($accessToken)){
    if($helper->getError()){
      header('HTTP/1.0 401 Unauthorized');
      error_log("Error: " . $helper->getError() . "\n");
      error_log("Error Code: " . $helper->getErrorCode() . "\n");
      error_log("Error Reason: " . $helper->getErrorReason() . "\n");
      error_log("Error Description: " . $helper->getErrorDescription() . "\n");
    }else{
      header('HTTP/1.0 400 Bad Request');
      echo 'Bad request';
    }
    exit;
  }

  // Set user access token. They are now logged in.
  $_SESSION['facebook_access_token'] = $accessToken->getValue();
  $pagelocation = $portal_root;
  $_SESSION['auth_service'] = "facebook";

  //Use token to get information about the user we are logging in.
  $response = $fb->get('/me?fields=name,email', $accessToken->getValue());
  $user = $response->getGraphUser();
  $userid = $user['id'];
  $revokeCall = '/'. $userid .'/permissions';

  try{
    // access token set but useremail is not
    if(isset($_SESSION['facebook_access_token'])){
      include "abre_dbconnect.php";
      if(!isset($_SESSION['useremail'])){
        $email = strtolower($user["email"]);
        $_SESSION['useremail'] = $email;
        $_SESSION['escapedemail'] = mysqli_real_escape_string($db, $email);
        $_SESSION['usertype'] = 'parent';
        $_SESSION['displayName'] = $user['name'];
        $_SESSION['auth_service'] = "facebook";
        $_SESSION['picture'] = getSiteLogo();
      }
      if($_SESSION['usertype'] != ""){
        if($result = $db->query("SELECT COUNT(*) FROM users_parent WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'")){
          $resultrow = $result->fetch_assoc();
          $count = $resultrow["COUNT(*)"];

          if($count == 1){
            //If not already logged in, check and get a refresh token
            if(!isset($_SESSION['loggedin'])){
              $_SESSION['loggedin'] = "";
            }
            if($_SESSION['loggedin'] != "yes"){
              $_SESSION['loggedin'] = "yes";
            }
          }else{
            $stmt = $db->stmt_init();
            $sql = "INSERT INTO users_parent (email, siteID) VALUES (?, ?)";
            $stmt->prepare($sql);
            $stmt->bind_param("si", $_SESSION['useremail'], $_SESSION['siteID']);
            $stmt->execute();
            $stmt->close();
            $_SESSION['loggedin'] = "yes";
          }
        }
        $db->close();
      }
    }
  }catch(Exception $x){
    if(strpos($x->getMessage(), 'Invalid Credentials')){
      session_destroy();
      $fb->api($revokeCall, "DELETE", $accessToken);
    }
    if(strpos($x->getMessage(), 'Invalid Credentials')){
      session_destroy();
      $fb->api($revokeCall, "DELETE", $accessToken);
    }
  }

  //Used for assessments and forms (if Chris doesn't remember what its for)
  if(isset($_SESSION["redirecturl"])){
    header("Location: $pagelocation/#".$_SESSION["redirecturl"]);
  }else{
    header("Location: $pagelocation");
  }
  exit();

 ?>