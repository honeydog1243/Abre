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

	//Modules
	if(superadmin()){

		//List all modules
		$modules = array();
		$modulecount = 0;
		$moduledirectory = '../../modules/';
		$modulefolders = scandir($moduledirectory);
		foreach($modulefolders as $result){
			if ($result == '.' or
				$result == '..' or
				$result == '.DS_Store' or
				$result == 'apps' or
				$result == 'calendar' or
				$result == 'classroom' or
				$result == 'directory' or
				$result == 'drive' or
				$result == 'mail' or
				$result == 'modules' or
				$result == 'profile' or
				$result == 'settings' or
				$result == 'stream' or
				$result == 'startup'
				) continue;

				//Count non core modules
				$modulecount++;

				if($modulecount == 1){
					echo "<div class='page_container page_container_limit mdl-shadow--4dp'>";
					echo "<div class='page'>";
					echo "<div class='row'>";
					echo "<div class='col s12'>";
					echo "<table id='myTable'>";
					echo "<thead>";
						echo "<tr>";
						echo "<th>Title</th>";
						echo "<th>Description</th>";
						echo "<th>Version</th>";
						echo "<th class='center-align'>Active</th>";
						echo "<th style='width:30px'></th>";
						echo "<th style='width:30px'></th>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
				}

				//Load the module meta
				$pagetitle = NULL;
				$description = NULL;
				$version = NULL;
				$repo = NULL;
				$uniquename = $result;
				require_once('../../modules/'.$result.'/config.php');
				if($description == NULL){ $description = "No Description"; }
				if($version == NULL){ $version = "No Version"; }

				echo "<tr>";
					echo "<td>$pagetitle</td>";
					echo "<td>$description</td>";
					echo "<td>$version</td>";

					$checkstatus = isAppActive($uniquename) ? "checked" : "";

					echo "<td class='center-align'>";
						echo "<div class='switch'>";
					    echo "<label>";
					      echo "<input id='app_$pagetitle' data-uniquename='$uniquename' class='activeswitch' type='checkbox' $checkstatus>";
					      echo "<span class='lever'></span>";
					    echo "</label>";
					  echo "</div>";
					echo "</td>";

					//Update Module if new version available
					if($repo != NULL){
						$project = strstr($repo, '/');
						$project = substr($project, 1);
						$opts = ['http' => ['method' => 'GET','header' => ['User-Agent: PHP']]];
						$context = stream_context_create($opts);
						$content = file_get_contents("https://api.github.com/repos/$repo/releases/latest", false, $context);
						$json = json_decode($content, true);
						$currentversion = $json['name'];
						if($version < $currentversion){
							$currentlink = "https://github.com/$repo/archive/".$currentversion.".zip";
							echo "<td width=30px><button class='updatemodule mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600' data-version='$currentlink' data-repo='$repo'><i class='material-icons'>update</i></button></td>";
						}else{
							echo "<td width=30px></td>";
						}
						//Delete module
						echo "<td width=30px><button class='deletemodule mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600' data-module='$project'><i class='material-icons'>delete</i></button></td>";
					}else{
						echo "<td width=30px></td>";
						echo "<td width=30px></td>";
					}
				echo "</tr>";
			}

			if($modulecount == 0){
				echo "<div style='padding:30px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>Abre Apps</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Click the '+' button at the bottom right to add an app.</p></div>";
			}else{
				echo "</tbody>";
				echo "</table>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}

			//Close Database
			$db->close();

			include "addmodule.php";

?>

<script>

				$(function(){

					//Turn App on/off
					$(".activeswitch").unbind().click(function(event) {

						var UniqueName = $(this).data('uniquename');
						AppStatus = $(this).is(':checked');

						if(AppStatus)
						{
							var result = confirm("Turn on this app?");
							AppStatusValue = 1;
						}
						else
						{
							var result = confirm("Turn off this app?");
							AppStatusValue = 0;
						}

		        if(result){
							$.post("modules/modules/action_updateactive.php", { uniquename: UniqueName, activestate: AppStatusValue }, function(){ })
							.done(function() {
								location.reload();
					  	})

						}
						else {
							event.preventDefault();
						}

					});

					//Update module
					$(".updatemodule").click(function(event) {
						event.preventDefault();
						var Link = $(this).data('version');
						var Repo = $(this).data('repo');
						$(this).html("<i class='material-icons'>file_download</i>");
						$.post("modules/modules/update.php",
						{ link: Link, repo: Repo }, function(){ })
						.done(function() {
							location.reload();
				  	})
					});

					//Update module
					$(".deletemodule").click(function(event) {
						event.preventDefault();
						var result = confirm("Are you sure you want to delete this app?");
						if(result){
							var Module = $(this).data('module');
							$.post("modules/modules/deletemodule.php",
							{ link: Module }, function(){ })
							.done(function() {
								location.reload();
					  	})
					  }
				  });

				});

</script>

<?php
	}
?>
