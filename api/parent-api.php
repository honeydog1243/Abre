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

    class apiParent{

        private static $baseUrl;

        private static $initialized = false;
            
        private static function initialize()
        {
            if (self::$initialized)
                return;

            self::$baseUrl = $_SESSION['api_url'];
            self::$initialized = true;
        }
        
        // Get all parents
        // Access: superadmin
        public static function getParents($data){
            
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
            
            $url = self::$baseUrl."parent/parent_student";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }
        
        // Get Parent_Student
        // Access: parent
        // Body:
        //  parent_id (required)
        //  student_id (optional)
        //  student_token (optional)
        public static function getParentStudent($data){
            
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
            
            $url = self::$baseUrl."parent/parent_student";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }

        // Get Parent by ID
        // Access: parent
        // $Query string: id
        public static function getParentID($id){
            
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
            
            $url = self::$baseUrl."parent/parents/{$id}";

            $value = file_get_contents($url, false, $context);
            
            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }
        
        //TODO
        

    }

?>