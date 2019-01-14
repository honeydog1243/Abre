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


    class apiStreams{
        
        private static $baseUrl;

        private static $initialized = false;
            
        private static function initialize()
        {
            if (self::$initialized)
                return;

            self::$baseUrl = $_SESSION['api_url'];
            self::$initialized = true;
        }
        
        // Get all streams
        public static function getAllStreams(){
                    
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
            
            $url = self::$baseUrl."stream/streams";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }

        // Save stream
        // $data: json
        //  group (required)
        //  title (optional)
        //  slug (optional)
        //  type (optional)
        //  required (optional)       
        public static function saveStream($data){
            
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
            
            $url = self::$baseUrl."stream/stream";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }

        // Gets all stream comments/likes
        public static function getAllStreamCommentsLikes(){
                    
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
            
            $url = self::$baseUrl."stream/stream-comments";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }
        
        // Get stream comments/likes by ID
        // $Query string: id
        public static function getStreamCommentsById($id){
            
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
            
            $url = self::$baseUrl."stream/stream-comments/{$id}";

            $value = file_get_contents($url, false, $context);
            return json_decode($value, true);
        }

        // Delete stream comments/likes by ID for authenticated user
        // $Query string: id
        public static function deleteStreamCommentsById($id){
            
            self::initialize();
            $accessToken = $_SESSION['api_token'];
                                                            
            $options = ["http" => [
                "method" => "DELETE",
                "header" => [
                             "Authorization: Bearer " . $accessToken,
                             "X-Requested-With: XMLHttpRequest",
                             "Content-Type: application/json"]
            ]];
            $context = stream_context_create($options);
            
            $url = self::$baseUrl."stream/stream-comments/{$id}";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }

        // Delete stream like by URL for authenticated user
        // $data: json
        //  url (required)
        public static function deleteStreamLike($data){
            
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
            
            $url = self::$baseUrl."stream/stream-like-delete";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }


        // Update stream comment by ID for authenticated user
        // $data: json
        //  id (required)
        //  comment (required)
        public static function updateStreamCommentById($data){
            
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
            
            $url = self::$baseUrl."stream/stream-comments-update";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }

        // Save stream comment
        // $data: json
        //  url (required)
        //  title (required)
        //  image (required)
        //  liked (required)
        //  excerpt (required)
        //  comment (optional)
        public static function saveStreamCommentByUser($data){
            
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
            
            $url = self::$baseUrl."stream/stream-comment";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }
        
        // Get stream contents for a url
        // $data: json
        //  url (required)
        // Notes:
        //  Ordered by id
        //  Also returns total liked count and comments count for url
        public static function getStreamContentsByUrl($data){
            
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
            
            $url = self::$baseUrl."stream/stream-card-contents";

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);
        }
        
        // Get stream cards for authenticated user that have likes
        // $Query string: offset, limit
        // <strong>Notes:</strong><br>
        //  Ordered by id (most recent first)<br>
        public static function getStreamCardsForLikes($beg, $end){
            
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
            
            $url = self::$baseUrl."stream/stream-like-cards?offset=".$beg."&limit=".$end;

            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }
        
        // Get stream cards for authenticated user that have comments
        // $Query string: offset, limit
        // <strong>Notes:</strong><br>
        //  Ordered by id (most recent first)<br>
        public static function getStreamCardsForComments($beg, $end){
            
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
            
            $url = self::$baseUrl."stream/stream-comment-cards?offset=".$beg."&limit=".$end;
            
            $value = file_get_contents($url, false, $context);

            if (!$value) ApiConnection::signIn();
            ApiConnection::refresh($http_response_header);

            return json_decode($value, true);;
        }

    }
?>