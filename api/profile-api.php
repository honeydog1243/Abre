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
    require_once(dirname(__FILE__) . '/../core/abre_functions.php');
    require_once('authentication-api.php');

    class apiProfile{

        private static $baseUrl;

        private static $initialized = false;
            
        private static function initialize()
        {
            if (self::$initialized)
                return;

            self::$baseUrl = $_SESSION['api_url'];
            self::$initialized = true;
        }
        
        // Get all profiles
        // Access: superadmin
        public static function getAllProfiles(){
                    
            self::initialize();
            $accessToken = $_SESSION['api_token'];
                                                            
            $options = ["http" => [
                "method" => "GET",
                "header" => [
                             "Authorization: Bearer " . $accessToken,
                             "X-Requested-With: XMLHttpRequest",
                             "Content-Type: application/json"]
            ]];
            $context = stream_context_create($options);
            
            $url = self::$baseUrl."profile/profiles";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }
        
        // Get profile by ID
        // $Query string: id
        public static function getProfilesById($id){
            
            self::initialize();
            $accessToken = $_SESSION['api_token'];
                                                            
            $options = ["http" => [
                "method" => "GET",
                "header" => [
                             "Authorization: Bearer " . $accessToken,
                             "X-Requested-With: XMLHttpRequest",
                             "Content-Type: application/json"]
            ]];
            $context = stream_context_create($options);
            
            $url = self::$baseUrl."profile/profiles/{$id}";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }
        
        // Get profile for user
        public static function getUserProfile(){
                    
            self::initialize();
            $accessToken = $_SESSION['api_token'];
                                                            
            $options = ["http" => [
                "method" => "GET",
                "header" => [
                             "Authorization: Bearer " . $accessToken,
                             "X-Requested-With: XMLHttpRequest",
                             "Content-Type: application/json"]
            ]];
            $context = stream_context_create($options);
            
            $url = self::$baseUrl."profile/user-profile";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }
        
        // Save profile
        // $data: json
        //  startup (optional)<br>
        //  streams (optional)<br>
        //  apps_order (optional)<br>
        //  work_calendar (optional)<br>
        //  widgets_order (optional)<br>
        //  widgets_hidden (optional)<br>
        //  widgets_open (optional)<br>
        public static function saveProfile($data){
            
            self::initialize();
            $accessToken = $_SESSION['api_token'];
                                                            
            $options = ["http" => [
                "method" => "POST",
                "header" => [
                             "Authorization: Bearer " . $accessToken,
                             "X-Requested-With: XMLHttpRequest",
                             "Content-Type: application/json"],
                "content" => $data
            ]];
            $context = stream_context_create($options);
            
            $url = self::$baseUrl."profile/profile";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }
        
        // Update profile
        // $data: json
        //  streams (optional)<br>
        //  apps_order (optional)<br>
        //  work_calendar (optional)<br>
        //  widgets_order (optional)<br>
        //  widgets_hidden (optional)<br>
        //  widgets_open (optional)<br>
        public static function updateProfile($data){
            
            self::initialize();
            $accessToken = $_SESSION['api_token'];
                                                            
            $options = ["http" => [
                "method" => "PUT",
                "header" => [
                             "Authorization: Bearer " . $accessToken,
                             "X-Requested-With: XMLHttpRequest",
                             "Content-Type: application/json"],
                "content" => $data
            ]];
            $context = stream_context_create($options);
            
            $url = self::$baseUrl."profile/profile-user";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }

    }

?>