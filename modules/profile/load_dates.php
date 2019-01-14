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

  require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
  require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
  require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

  $date = $_POST["year"];
  $email = mysqli_real_escape_string($db, $_POST["email"]);

  //if the user is not a student grab their information from the database.
  if($_SESSION['usertype'] != "student"){
    $sql = "SELECT work_calendar FROM profiles WHERE email = '$email' AND siteID = '".$_SESSION['siteID']."'";
    $dbreturn = databasequery($sql);
    foreach($dbreturn as $row){
      //grab the work calendar from the entry
      $work_calendar_saved = $row['work_calendar'];

      //check and see if they have existing data
      if($work_calendar_saved != NULL){
        //This temp variable stores the value from the database. If we try and
        //decode a comma separated list the type will be null and we will need to
        //use this variable to get the database value back. Otherwise this variable
        //is not used.
        $work_calendar_temp = $work_calendar_saved;
        $work_calendar_saved = json_decode($work_calendar_saved, TRUE);

        //This if statement is used for conversion of old format(comma separated)
        //list of dates to new json format as of 07/03/2017.
        //If it is in old format, we are unable to treat it as json.
        //author - Bwilson
        if(gettype($work_calendar_saved) == "NULL"){

          $work_calendar_saved = $work_calendar_temp;

          //parse comma separated string
          $dates = str_replace("'", "", $work_calendar_saved);
          $dates = str_replace(' ', '', $dates);
          $dates = explode(",", $dates);

          $date_check = substr($dates[0],6,4);
          if($date_check == $date){
            //return message back to javascript.
            $message = array("addDates"=>$dates);
            header("Content-Type: application/json");
            echo json_encode($message);
          }else{
            //return message back to javascript.
            $message = array("addDates"=>'');
            header("Content-Type: application/json");
            echo json_encode($message);
          }
        }else{

          //get the date entries for the year provided from javascript
          $dates = $work_calendar_saved[$date];

          //if there are no dates for the year provided return an empty string
          if($dates == NULL){
            $message = array("addDates"=>'', "jsonDates"=>$work_calendar_saved);
            header("Content-Type: application/json");
            echo json_encode($message);
          //there is a value for the year passed. Need to parse that value.
          }else{

            $dates = str_replace(' ', '', $dates);
            $dates = explode(",", $dates);

            $message = array("addDates"=>$dates, "jsonDates"=>$work_calendar_saved);
            header("Content-Type: application/json");
            echo json_encode($message);
          }
        }
      //There is no calendar data for the given user so simply return an empty
      //string
      }else{

        //this code can be used to enable default dates if no data is stored in the database
        //this code is not used, but leaving here incase we change design decisions
        //author - Bwilson

        // include "calendar_default_dates.php";
        // $work_calendar_saved = json_decode($work_calendar_saved, TRUE);
        // $dates = $work_calendar_saved[$date];
        // $dates = str_replace(' ', '', $dates);
        // $dates=explode(",", $dates);

        $message = array("addDates"=>'');
        header("Content-Type: application/json");
        echo json_encode($message);

      }
    }
  }
?>
