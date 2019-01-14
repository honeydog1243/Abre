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

require_once('abre_verification.php');

$eventHub = new EventHub;

// Read in event files
if (isset($_SESSION['useremail'])) {
  $moduleDirectory = dirname(__FILE__) . '/../modules';
  $moduleFolders = scandir($moduleDirectory);

  foreach($moduleFolders as $result){
    if(isAppActive($result)) {
      if(file_exists(dirname(__FILE__) . "/../modules/$result/events.php")) {
        include(dirname(__FILE__) . "/../modules/$result/events.php");
      }
    }
  }
}

class EventHub {
  private $events = [];

  /**
   * Registers $function to be called whenever $eventName is called
   * This function is not able to prevent duplicate registrations
   * $function should be of form $args => void where $args is anything
   * you want. I recommend an associative array or object. 
   */
  function registerEvent($eventName, $function) {
    if (!array_key_exists($eventName, $this->events)) {
      $this->events[$eventName] = [];
    }

    $this->events[$eventName] []= $function;
  }

  /**
   * The event name and the arguments provided. I recommend 
   * using an associative array or object for $args
   */
  function fireEvent($eventName, $args) {
    if (array_key_exists($eventName, $this->events)) {
      foreach($this->events[$eventName] as $f) {
        $f($args);
      }
    }
  }
}

?>