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

		$query = "SELECT TestName, Administration, Overallscaledscore, Overallperformancelevel, OverallRawScore, Subscore1performanceband, Subscore2performanceband, Subscore3performanceband, Subscore4performanceband, Subscore5performanceband, Subscore1RawScore, Subscore2RawScore, Subscore3RawScore, Subscore4RawScore, Subscore5RawScore FROM Abre_AIRData WHERE LocalID = '$Student_ID' ORDER BY TestName";
		$dbreturn = databasequery($query);
		$dbreturncount = count($dbreturn);
		$AIRCount=0;
		foreach ($dbreturn as $value)
		{

			$TestName=htmlspecialchars($value['TestName'], ENT_QUOTES);
			$Administration=htmlspecialchars($value['Administration'], ENT_QUOTES);
			$Overallscaledscore=htmlspecialchars($value['Overallscaledscore'], ENT_QUOTES);
			$Overallperformancelevel=htmlspecialchars($value['Overallperformancelevel'], ENT_QUOTES);
			$OverallRawScore=htmlspecialchars($value['OverallRawScore'], ENT_QUOTES);
			$Subscore1performanceband=htmlspecialchars($value['Subscore1performanceband'], ENT_QUOTES);
			if($Subscore1performanceband=='*'){ $Subscore1performanceband='At/Near'; }
			if($Subscore1performanceband=='+'){ $Subscore1performanceband='Above'; }
			if($Subscore1performanceband=='-'){ $Subscore1performanceband='Below'; }
			$Subscore2performanceband=htmlspecialchars($value['Subscore2performanceband'], ENT_QUOTES);
			if($Subscore2performanceband=='*'){ $Subscore2performanceband='At/Near'; }
			if($Subscore2performanceband=='+'){ $Subscore2performanceband='Above'; }
			if($Subscore2performanceband=='-'){ $Subscore2performanceband='Below'; }
			$Subscore3performanceband=htmlspecialchars($value['Subscore3performanceband'], ENT_QUOTES);
			if($Subscore3performanceband=='*'){ $Subscore3performanceband='At/Near'; }
			if($Subscore3performanceband=='+'){ $Subscore3performanceband='Above'; }
			if($Subscore3performanceband=='-'){ $Subscore3performanceband='Below'; }
			$Subscore4performanceband=htmlspecialchars($value['Subscore4performanceband'], ENT_QUOTES);
			if($Subscore4performanceband=='*'){ $Subscore4performanceband='At/Near'; }
			if($Subscore4performanceband=='+'){ $Subscore4performanceband='Above'; }
			if($Subscore4performanceband=='-'){ $Subscore4performanceband='Below'; }
			$Subscore5performanceband=htmlspecialchars($value['Subscore5performanceband'], ENT_QUOTES);
			if($Subscore5performanceband=='*'){ $Subscore5performanceband='At/Near'; }
			if($Subscore5performanceband=='+'){ $Subscore5performanceband='Above'; }
			if($Subscore5performanceband=='-'){ $Subscore5performanceband='Below'; }
			$Subscore1RawScore=htmlspecialchars($value['Subscore1RawScore'], ENT_QUOTES);
			$Subscore2RawScore=htmlspecialchars($value['Subscore2RawScore'], ENT_QUOTES);
			$Subscore3RawScore=htmlspecialchars($value['Subscore3RawScore'], ENT_QUOTES);
			$Subscore4RawScore=htmlspecialchars($value['Subscore4RawScore'], ENT_QUOTES);
			$Subscore5RawScore=htmlspecialchars($value['Subscore5RawScore'], ENT_QUOTES);

			//Determine the Subscore Category
			$Subscore1title=NULL; $Subscore2title=NULL; $Subscore3title=NULL; $Subscore4title=NULL; $Subscore5title=NULL;
			$query2 = "SELECT Subscore, Name FROM Abre_AIRSubscore_Categories WHERE TestName = '$TestName'";
			$dbreturn2 = databasequery($query2);
			foreach ($dbreturn2 as $value2)
			{
				$Subscore=htmlspecialchars($value2['Subscore'], ENT_QUOTES);
				$Name=htmlspecialchars($value2['Name'], ENT_QUOTES);

				if($Subscore==1){ $Subscore1title=$Name; }
				if($Subscore==2){ $Subscore2title=$Name; }
				if($Subscore==3){ $Subscore3title=$Name; }
				if($Subscore==4){ $Subscore4title=$Name; }
				if($Subscore==5){ $Subscore5title=$Name; }
			}

			echo "<table class='bordered responsive-table'>";

				echo "<tr>";
					echo "<th width='28%'>$TestName - $Administration<br></th>";
					echo "<th width='12%' class='center-align'>Level</th>";
					echo "<th width='12%' class='center-align'>$Subscore1title</th>";
					echo "<th width='12%' class='center-align'>$Subscore2title</th>";
					echo "<th width='12%' class='center-align'>$Subscore3title</th>";
					echo "<th width='12%' class='center-align'>$Subscore4title</th>";
					echo "<th width='12%' class='center-align'>$Subscore5title</th>";
				echo "</tr>";

				echo "<tr>";
					echo "<td><b>Scaled Score - $Overallscaledscore</b></td>";
					echo "<td class='center-align'><b>$Overallperformancelevel</b></td>";
					echo "<td class='center-align'><b>$Subscore1RawScore</b><br>$Subscore1performanceband</td>";
					echo "<td class='center-align'><b>$Subscore2RawScore</b><br>$Subscore2performanceband</td>";
					echo "<td class='center-align'><b>$Subscore3RawScore</b><br>$Subscore3performanceband</td>";
					echo "<td class='center-align'><b>$Subscore4RawScore</b><br>$Subscore4performanceband</td>";
					echo "<td class='center-align'><b>$Subscore5RawScore</b><br>$Subscore5performanceband</td>";
				echo "</tr>";

			echo "</table>";

			echo "<br>";
			$AIRCount++;
		}

		if($dbreturncount==0){ echo "<div class='row' style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>No Assessment Details</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Details for this assessment could not be found.</p></div>"; }

	}
?>
