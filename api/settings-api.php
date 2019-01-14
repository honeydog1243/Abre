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

	//Include required files
    require_once(dirname(__FILE__) . '/../core/abre_functions.php');
    require_once('authentication-api.php');

    class apiSettings{

        private static $baseUrl;

        private static $initialized = false;
            
        private static function initialize()
        {
            if (self::$initialized)
                return;

            self::$baseUrl = $_SESSION['api_url'];
            self::$initialized = true;
        }
        
        // Get settings
        // Access: superadmin
        public static function getSettings(){
                    
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
            
            $url = self::$baseUrl."setting/settings";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }
        
        // Get settings by ID
        // Access: superadmin
        // $Query string: id
        public static function getSettingsById($id){
            
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
            
            $url = self::$baseUrl."setting/settings/{$id}";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }
          
        // Save settings
        // Access: superadmin
        // $data: json
        //  options (optional)
        //  parentaccess (optional)
        //  integrations (optional)
        //  authentication (optional)       
        public static function saveSettings($data){
            
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
            
            $url = self::$baseUrl."setting/setting";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);
            
            return json_decode($value, true);;
        }
        
        // Update settings
        // Access: superadmin
        // $data: json
        //  id (required)
        //  options (optional)
        //  parentaccess (optional)
        //  integrations (optional)
        //  authentication (optional)       
        public static function updateSettingsById($data){
         
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
            
            $url = self::$baseUrl."setting/setting-update";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }

    }

?>