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
	require(dirname(__FILE__) . '/../../configuration.php');
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once('functions.php');
	require_once('permissions.php');

	if($pagerestrictions=="")
	{

		$Student_ID=$_GET["StudentID"];

		$query = "SELECT TestingDate, CategoryName, Score FROM Abre_StudentACT WHERE StudentID = '$Student_ID' AND CategoryName != 'Composite Score' ORDER BY CategoryName, TestingDate";
		$dbreturn = databasequery($query);
		$returncount=0;
		foreach ($dbreturn as $value)
		{

			$returncount++;

			$TestingDate=htmlspecialchars($value['TestingDate'], ENT_QUOTES);
			$TestingDate=str_replace(" 00:00:00","",$TestingDate);
			$ACTcolor="";
			$CategoryNameSubArea=htmlspecialchars($value['CategoryName'], ENT_QUOTES);
			$CategoryNameSubArea=str_replace(" Score","",$CategoryNameSubArea);
			$ScoreSubArea=htmlspecialchars($value['Score'], ENT_QUOTES);
			$ScoreSubArea = ltrim($ScoreSubArea, '0');
			if($ScoreSubArea==""){ $ScoreSubArea="0"; }
			if($ScoreSubArea!="1"){ $ScoreSubAreaLevelText="$ScoreSubArea Points"; }else{ $ScoreSubAreaLevelText="$ScoreSubArea Point"; }
			$ACTBarPercentage=($ScoreSubArea/36)*100;
			if($ScoreSubArea<"12"){ $ACTcolor="#F44336"; }
			if($ScoreSubArea>="12" && $ScoreSubArea<="24"){ $ACTcolor="#FFC107"; }
			if($ScoreSubArea>"24"){ $ACTcolor="#4CAF50"; }

			echo "<div class='row' style='margin:5px 0 5px 0;'>";
				echo "<div style='float:left; width:50%;'><b>$CategoryNameSubArea / $TestingDate</b></div><div class='right-align' style='float:left; width:50%;'><b>$ScoreSubAreaLevelText</b></div>";
				echo "<div class='progress' style='padding:8px 0 8px 0;'><div class='determinate' style='width: $ACTBarPercentage%; background-color:$ACTcolor !important;'></div></div>";
			echo "</div>";

		}

		if($returncount==0){ echo "<div class='row' style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>No Assessment Details</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Details for this assessment could not be found.</p></div>"; }

	}
?>
