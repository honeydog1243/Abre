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


    class apiDirectory{

        private static $baseUrl;

        private static $initialized = false;
            
        private static function initialize()
        {
            if (self::$initialized)
                return;

            self::$baseUrl = $_SESSION['api_url'];
            self::$initialized = true;
        }
        
        // Get all directories
        // Access: superadmin
        public static function getDirectories($data){
            
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
            
            $url = self::$baseUrl."directory/directories";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }

        // Get user directory
        public static function getDirectory($data){
            
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
            
            $url = self::$baseUrl."directory/directory";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }

        //TODO
        
        

    }

?>