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
	require_once('permissions.php');

	//Assessment Roles
	if(isAppActive("Abre-Assessments")){
		echo "<div class='col l4 m12 s12'>";
			if(strpos($role, 'Assessment Administrator') !== false){
				echo "<input type='checkbox' id='AssessmentAdministrator' name='AssessmentAdministrator' class='filled-in' value='Assessment Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='AssessmentAdministrator' name='AssessmentAdministrator' class='filled-in' value='Assessment Administrator'/>";
			}
			echo "<label for='AssessmentAdministrator' style='color:#000;'>Assessment Administrator<i class='material-icons tooltipped' data-tooltip='Allow the user to create and modify district level assessments.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
	}

	//Conduct Roles
	if(isAppActive("Abre-Conduct")){
		echo "<div class='col l4 s12' id='ConductAdministratorLabel'>";
			if(strpos($role, 'Conduct Administrator') !== false){
				echo "<input type='checkbox' id='ConductAdministrator' name='ConductAdministrator' class='filled-in' value='Conduct Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='ConductAdministrator' name='ConductAdministrator' class='filled-in' value='Conduct Administrator'/>";
			}
			echo "<label for='ConductAdministrator' style='color:#000;'>Conduct Administrator<i class='material-icons tooltipped' data-tooltip='Allow the user to view, modify, and create conduct incidents for all district students.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
		echo "<div class='col l4 s12'>";
			if(strpos($role, 'Conduct Verification Monitor') !== false){
				echo "<input type='checkbox' id='ConductVerificationMonitor' name='ConductVerificationMonitor' class='filled-in' value='Conduct Verification Monitor' checked/>";
			}else{
				echo "<input type='checkbox' id='ConductVerificationMonitor' name='ConductVerificationMonitor' class='filled-in' value='Conduct Verification Monitor'/>";
			}
			echo "<label for='ConductVerificationMonitor' style='color:#000;'>Conduct Verification Monitor<i class='material-icons tooltipped' data-tooltip='Allow the user to verify conduct consequences have been served by students.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
	}

	//Curriculum Roles
	if(isAppActive("Abre-Curriculum")){
		echo "<div class='col l4 s12'>";
			if(strpos($role, 'Curriculum Administrator') !== false){
				echo "<input type='checkbox' id='CurriculumAdministrator' name='CurriculumAdministrator' class='filled-in' value='Curriculum Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='CurriculumAdministrator' name='CurriculumAdministrator' class='filled-in' value='Curriculum Administrator'/>";
			}
			echo "<label for='CurriculumAdministrator' style='color:#000;'>Curriculum Administrator<i class='material-icons tooltipped' data-tooltip='Allow the user to view, modify, and create curriculum maps.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
	}

	//Directory Roles
	echo "<div class='col l4 s12'>";
		if(strpos($role, 'Directory Administrator') !== false){
			echo "<input type='checkbox' id='DirectoryAdministrator' name='DirectoryAdministrator' class='filled-in' value='Directory Administrator' checked/>";
		}else{
			echo "<input type='checkbox' id='DirectoryAdministrator' name='DirectoryAdministrator' class='filled-in' value='Directory Administrator'/>";
		}
		echo "<label for='DirectoryAdministrator' style='color:#000;'>Directory Administrator<i class='material-icons tooltipped' data-tooltip='Allows the user to view and modify confidential user information of all directory profiles. Administrators can also modify settings, generate reports, and archive users.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
	echo "</div>";

	echo "<div class='col l4 s12'>";
		if(strpos($role, 'Directory Advisor') !== false){
			echo "<input type='checkbox' id='DirectoryAdvisor' name='DirectoryAdvisor' class='filled-in' value='Directory Advisor' checked/>";
		}else{
			echo "<input type='checkbox' id='DirectoryAdvisor' name='DirectoryAdvisor' class='filled-in' value='Directory Advisor'/>";
		}
		echo "<label for='DirectoryAdvisor' style='color:#000;'>Directory Advisor<i class='material-icons tooltipped' data-tooltip='Allow the user to view general user information(name, email, address) of all directory profiles.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
	echo "</div>";

	echo "<div class='col l4 s12'>";
		if(strpos($role, 'Directory Supervisor') !== false){
			echo "<input type='checkbox' id='DirectorySupervisor' name='DirectorySupervisor' class='filled-in' value='Directory Supervisor' checked/>";
		}else{
			echo "<input type='checkbox' id='DirectorySupervisor' name='DirectorySupervisor' class='filled-in' value='Directory Supervisor'/>";
		}
		echo "<label for='DirectorySupervisor' style='color:#000;'>Directory Supervisor<i class='material-icons tooltipped' data-tooltip='Allows the user to view and modify confidential user information of all directory profiles. Supervisors can also modify settings, generate reports, and archive users.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
	echo "</div>";

	//Forms Roles
	if(isAppActive("Abre-Forms")){
		echo "<div class='col l4 s12'>";
			if(strpos($role, 'Forms Administrator') !== false){
				echo "<input type='checkbox' id='FormsAdministrator' name='FormsAdministrator' class='filled-in' value='Forms Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='FormsAdministrator' name='FormsAdministrator' class='filled-in' value='Forms Administrator'/>";
			}
			echo "<label for='FormsAdministrator' style='color:#000;'>Forms Administrator<i class='material-icons tooltipped' data-tooltip='Allow the user to create district forms and recommend forms to staff, students, and parents.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
	}

	//Learn Roles
	if(isAppActive("Abre-Plans")){
		echo "<div class='col l4 s12'>";
			if(strpos($role, 'Learn Administrator') !== false){
				echo "<input type='checkbox' id='LearnAdministrator' name='LearnAdministrator' class='filled-in' value='Learn Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='LearnAdministrator' name='LearnAdministrator' class='filled-in' value='Learn Administrator'/>";
			}
			echo "<label for='LearnAdministrator' style='color:#000;'>Learn Administrator<i class='material-icons tooltipped' data-tooltip='Allow the user to view and generate reports for learn courses.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
	}

	//Plans Roles
	if(isAppActive("Abre-Plans")){
		echo "<div class='col l4 s12'>";
			if(strpos($role, 'Building Plans Administrator') !== false){
				echo "<input type='checkbox' id='BuildingPlansAdministrator' name='BuildingPlansAdministrator' class='filled-in' value='Building Plans Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='BuildingPlansAdministrator' name='BuildingPlansAdministrator' class='filled-in' value='Building Plans Administrator'/>";
			}
			echo "<label for='BuildingPlansAdministrator' style='color:#000;'>Plans Administrator (Building)<i class='material-icons tooltipped' data-tooltip='Allow the user to view, edit, modify, and generate reports for plans associated with students in their buildings.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";

		echo "<div class='col l4 s12'>";
			if(strpos($role, 'District Plans Administrator') !== false){
				echo "<input type='checkbox' id='DistrictPlansAdministrator' name='DistrictPlansAdministrator' class='filled-in' value='District Plans Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='DistrictPlansAdministrator' name='DistrictPlansAdministrator' class='filled-in' value='District Plans Administrator'/>";
			}
			echo "<label for='DistrictPlansAdministrator' style='color:#000;'>Plans Administrator (District)<i class='material-icons tooltipped' data-tooltip='Allow the user to view, edit, modify, and generate reports for plans associated with students in their district.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
	}

	//Stream and Headline Roles
	echo "<div class='col l4 s12'>";
		if(strpos($role, 'Stream and Headline Administrator') !== false){
			echo "<input type='checkbox' id='StreamandHeadlineAdministrator' name='StreamandHeadlineAdministrator' class='filled-in' value='Stream and Headline Administrator' checked/>";
		}else{
			echo "<input type='checkbox' id='StreamandHeadlineAdministrator' name='StreamandHeadlineAdministrator' class='filled-in' value='Stream and Headline Administrator'/>";
		}
		echo "<label for='StreamandHeadlineAdministrator' style='color:#000;'>Stream and Headline Administrator<i class='material-icons tooltipped' data-tooltip='Allow the user to create and modify streams and headlines.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
	echo "</div>";

	if(isAppActive("Abre-Students")){
		echo "<div class='col l4 s12'>";
			if(strpos($role, 'Student Administrator') !== false){
				echo "<input type='checkbox' id='student-administrator' name='student-administrator' class='filled-in' value='Student Administrator' checked/>";
			}else{
				echo "<input type='checkbox' id='student-administrator' name='student-administrator' class='filled-in' value='Student Administrator'/>";
			}
			echo "<label for='student-administrator' style='color:#000;'>Student Administrator<i class='material-icons tooltipped' data-tooltip='Allow the user to access administrator dashboard and generate reports on student data.' data-position='right' style='font-size: 12px; color:#9E9E9E; padding-left:5px;'>help</i></label>";
		echo "</div>";
	}

?>

<script>

	$(function(){
		mdlregister();
		$('.tooltipped').tooltip();

	});


</script>
