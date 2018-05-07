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

	//Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');
	require_once('permissions.php');

	if($pagerestrictions=="")
	{
?>

		<div class='fixed-action-btn buttonpin'>
			<?php
			if(GetStaffID($_SESSION["useremail"]) != "ABREDEMO") {
				echo "<a class='modal-studentgroup btn-floating btn-large waves-effect waves-light' style='background-color:".getSiteColor()."' id='studentgroup_button' href='#studentgroup'><i class='large material-icons'>add</i></a>";
				echo "<div class='mdl-tooltip mdl-tooltip--left' for='studentgroup_button'>New Student Group</div>";
			}
			?>
		</div>

<?php
	}
?>
