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
    if(getenv("USE_GOOGLE_CLOUD") != "true"){
      require_once(dirname(__FILE__) . '/../configuration.php');
    }


    class ApiAuthentication{

        function signIn() {

            $baseUrl = $_SESSION['api_url'];

            $sha1useremail = sha1($_SESSION['useremail']);
            $cookiekey = getConfigPortalCookieKey();
            $hash = sha1($cookiekey);

            $storetoken = $sha1useremail.$hash;
            $useremail = $_SESSION['useremail'];
            //$site = "local";
            $site = 1;

            $data = json_encode(array(
                "email" => $useremail,
                "site" => $site,
                "cookie_token" => $storetoken
            ));

            $options = ["http" => [
                "method" => "POST",
                "header" => [
                             "X-Requested-With: XMLHttpRequest",
                             "Content-Type: application/json"],
                "content" => $data
            ]];
            $context = stream_context_create($options);

            $url = $baseUrl."signin";

            $value = file_get_contents($url, false, $context);
            $_SESSION['api_token']=$value;

            return $value;
        }
    }

    class ApiConnection
    {

        public static function refresh($http_response_header)
        {

            foreach ($http_response_header as $value1) {

                if (strstr($value1, "Authorization: Bearer")) {

                    $token = substr($value1, 22);

                    $_SESSION['api_token']=$token;

                    break;
                }
            }

        }

        public static function signIn()
        {
            $api = new ApiAuthentication();
            $value = $api->signIn();

            //$_SESSION['usertype'] = NULL;
            //$_SESSION['useremail'] = NULL;
            //header("Location: $portal_root?signout");
        }
    }


?>