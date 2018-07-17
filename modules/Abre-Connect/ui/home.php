<?php

	/*
	* Copyright (C) 2016-2017 Abre.io LLC
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

	//Required configuration files
	require_once(dirname(__FILE__) . '/../../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../../core/abre_functions.php');


  $thisWeekJournals = ["City Council", "Jimmy Nuveen", "Uncategorized"];
?>


<div class="container">
  <div id="home" class="col s12">home</div>
  <div id="journals" class="col s12">
    <ul class="collection">
      <?php
        for($i = 0; $i < count($thisWeekJournals); $i++) {
      ?>
        <li class="collection-item avatar" 
          style="width: auto; height: auto; border-radius: 0; ">
        
          <i class="material-icons circle">account_circle</i>
          <span class="title"><?php echo $thisWeekJournals[$i] ?></span>
          <div class="row">
            <p class="truncate col s10">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
            <i class="material-icons col s1">attach_file</i>
          </div>
          <div class="secondary-content">Today</div>
        </li>
      <?php } ?>
    </ul>  
  </div>
</div>