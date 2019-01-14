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
	require_once('permissions.php');

	if($pageaccess==1)
	{
?>
	    <div class="col s12">
			<ul class="tabs_2" style='background-color: <?php echo getSiteColor(); ?>'>
				<li class="tab col s3 tab_1 directorymenu pointer" data="#directory"><a href="#directory" class='mdl-color-text--white'>
					<span class='hide-on-small-only'>Active</span>
					<i class='material-icons hide-on-med-and-up'>person</i>
				</a></li>
				<li class="tab col s3 tab_2 directorymenu pointer" data="#directory/archived"><a href="#directory/archived" class='mdl-color-text--white'>
					<span class='hide-on-small-only'>Archived</span>
					<i class='material-icons hide-on-med-and-up'>perm_identity</i>
				</a></li>
				<li class="tab col s3 tab_3 directorymenu pointer" data="#directory/settings"><a href="#directory/settings" class='mdl-color-text--white'>
					<span class='hide-on-small-only'>Settings</span>
					<i class='material-icons hide-on-med-and-up'>settings</i>
				</a></li>
				<li class="tab col s3 tab_4 directorymenu pointer" data="#directory/reports"><a href="#directory/reports" class='mdl-color-text--white'>
					<span class='hide-on-small-only'>Reports</span>
					<i class='material-icons hide-on-med-and-up'>import_export</i>
				</a></li>
			</ul>
		</div>
<?php
	}
?>

<script>

	$(function(){

		$(".directorymenu").click(function(){
			window.open($(this).attr("data"), '_self');
		});

	});

</script>