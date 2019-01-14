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
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require_once('permissions.php');

	$siteColor = getSiteColor();

	if($pagerestrictions == ""){

	}
?>

<div id="startermodal" class="modal modal-fixed-footer modal-mobile-full">
  <div class="modal-content" style="padding: 0px !important;">
		<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
			<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Example Starter Modal</span></div>
			<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
		</div>
		<div style='padding: 0px 24px 0px 24px;'>
	    <div class="row center-align">
	      <p id="infoHolder" style=""></p>
	    </div>
		</div>
  </div>
  <div class="modal-footer">
	  <div id ="footerButtonsDiv" style='display: inline-block; float:right'>
			<button class="modal-close waves-effect btn-flat white-text" style='margin-left:5px; background-color: <?php echo $siteColor; ?>'>Close</button>
	    <a class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo $siteColor; ?>'>Button</a>
	  </div>
  </div>
</div>

<script>

</script>
