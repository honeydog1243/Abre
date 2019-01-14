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

    //Required configuration files

	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
  
  if(!superadmin()) {
    return;
  }
?>

<link rel='stylesheet' href='modules/settings/css/style.css'>

<div class='page_container page_container_limit mdl-shadow--4dp'>
  <div class="page">
    <h4>Session Variables</h4>

    <table class="striped">
      <thead>
        <tr>
          <th>Variable</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          foreach ($_SESSION as $key => $value) { 
            if(gettype($value) == "array" || gettype($value) == "object") {
              foreach($value as $key2 => $value2) {
          ?>
              <tr>
                <td><?= $key .".". $key2 ?></td>
                <td class="wrap-long-token"><?= $value2 ?></td>
              </tr>
        <?php
              }
            } else {
        ?>
              <tr>
                <td><?= $key ?></td>
                <td><?= $value ?></td>
              </tr>
        <?php 
            } 
          } 
        ?>
      </tbody>
    </table>
  </div>
</div>