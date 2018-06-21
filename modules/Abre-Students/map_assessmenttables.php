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

	if($pagerestrictions == "" || $isParent){

		$Student_ID = $_GET['StudentID'];
    $termName = $_GET['termName'];
    $testName = $_GET['testName'];

    $query = "SELECT * FROM Abre_MAPData WHERE StudentID = '$Student_ID' AND TermName ='$termName' AND TestName = '$testName'";
    $dbreturn = databasequery($query);

    echo "<ul class='collapsible' data-collapsible='expandable'>";

      echo "<li>";
        echo "<div class='collapsible-header active'><b>Test Information</b></div>";
        echo "<div class='collapsible-body'><br>";
          echo "<div class='row'>";
            echo "<div class='col s12 l3'><b>Name:</b> ".$testName."</div>";
            echo "<div class='col s12 l3'><b>Start Date:</b> ".$dbreturn[0]['TestStartDate']."</div>";
            echo "<div class='col s12 l3'><b>Start Time:</b> ".date( "g:i A", strtotime($dbreturn[0]['TestStartTime']))."</div>";
            if(isset($dbreturn[0]['TestDurationMinutes']) && $dbreturn[0]['TestDurationMinutes'] != ""){
                echo "<div class='col s12 l3'><b>Duration:</b> ".$dbreturn[0]['TestDurationMinutes']." minutes</div>";
            }else{
              echo "<div class='col s12 l3'><b>Duration:</b></div>";
            }
          echo "</div>";
          echo "<div class='row'>";
            echo "<div class='col s12 l3'><b>RIT Score:</b> ".$dbreturn[0]['TestRITScore']."</div>";
            echo "<div class='col s12 l3'><b>Percent Correct:</b> ".$dbreturn[0]['PercentCorrect']."</div>";
            echo "<div class='col s12 l3'><b>Standard Error:</b> ".$dbreturn[0]['TestStandardError']."</div>";
            echo "<div class='col s12 l3'><b>Percentile:</b> ".$dbreturn[0]['TestPercentile']."</div>";
          echo "</div>";
          echo "<div class='row'>";
            if(isset($dbreturn[0]['Accommodations']) && $dbreturn[0]['Accommodations'] != ""){
              echo "<div class='col s12 l6'><b>Accommodation Category:</b> ".$dbreturn[0]['AccommodationCategory']."</div>";
              echo "<div class='col s12 l6' style='word-wrap: break-word'><b>Accommodations:</b> ".$dbreturn[0]['Accommodations']."</div>";
            }else{
              echo "<div class='col s12 l6'><b>Accommodation Category:</b> N/A</div>";
              echo "<div class='col s12 l6'><b>Accommodations:</b> N/A</div>";
            }
          echo "</div>";
        echo "</div>";
      echo "</li>";

      echo "<li>";
        echo "<div class='collapsible-header'><b>Goals</b></div>";
        echo "<div class='collapsible-body'>";
          echo "<div class='row'>";
            echo "<div class='col s12'>";
              echo "<table id='goalTable' class='highlight'>";
                echo "<thead>";
                  echo "<tr>";
                  echo "<th class=''>Goal Name</th>";
                  echo "<th>RIT Score</th>";
                  echo "<th class='hide-on-med-and-down'>Standard Error</th>";
                  echo "<th class='hide-on-small-only'>Range</th>";
                  echo "<th>Adjective</th>";
                  echo "</tr>";
                echo "</thead>";
                for($i = 1; $i < 9; $i++){
                  if(!isset($dbreturn[0]['Goal'.$i.'Name']) || $dbreturn[0]['Goal'.$i.'Name'] == ""){
                    continue;
                  }else{
                    $goalName = $dbreturn[0]['Goal'.$i.'Name'];
                    $goalScore = $dbreturn[0]['Goal'.$i.'RitScore'];
                    $goalError = $dbreturn[0]['Goal'.$i.'StdErr'];
                    $goalRange = $dbreturn[0]['Goal'.$i.'Range'];
                    $goalAdjective = $dbreturn[0]['Goal'.$i.'Adjective'];

                    echo "<tr>";
                      echo "<td>$goalName</td>";
                      echo "<td>$goalScore</td>";
                      echo "<td class='hide-on-med-and-down'>$goalError</td>";
                      echo "<td class='hide-on-small-only'>$goalRange</td>";
                      echo "<td>$goalAdjective</td>";
                    echo "</tr>";
                  }
                }
              echo "</table>";
            echo "</div>";
          echo "</div>";
        echo "</div>";
      echo "</li>";

      echo "<li>";
        echo "<div class='collapsible-header'><b>Projected Proficiencies</b></div>";
        echo "<div class='collapsible-body'>";
          echo "<div class='row'>";
            echo "<div class='col s12'>";
              echo "<table id='proficiencyTable' class='highlight'>";
                echo "<thead>";
                  echo "<tr>";
                  echo "<th class=''>Proficiency</th>";
                  echo "<th>Proficiency Level</th>";
                  echo "</tr>";
                echo "</thead>";
                for($i = 1; $i < 11; $i++){
                  if(!isset($dbreturn[0]['ProjectedProficiencyStudy'.$i]) || $dbreturn[0]['ProjectedProficiencyStudy'.$i] == ""){
                    continue;
                  }else{
                    $proficiencyStudy = $dbreturn[0]['ProjectedProficiencyStudy'.$i];
                    $proficiencyLevel = $dbreturn[0]['ProjectedProficiencyLevel'.$i];
                    echo "<tr>";
                      echo "<td>$proficiencyStudy</td>";
                      echo "<td>$proficiencyLevel</td>";
                    echo "</tr>";
                  }
                }
              echo "</table>";
            echo "</div>";
          echo "</div>";
        echo "</div>";
      echo "</li>";

      $seasonArray = ["Fall", "Winter", "Spring"];
      $arraySize = sizeof($seasonArray);
      echo "<li>";
        echo "<div class='collapsible-header'><b>Growth Indexes</b></div>";
        echo "<div class='collapsible-body'><br>";
          echo "<div class='row'>";
            echo "<div class='col s12'>";
              echo "<h4>Fall</h4>";
                if($dbreturn[0]["FallToFallProjectedGrowth"] == "" && $dbreturn[0]["FallToWinterProjectedGrowth"] == "" &&
                    $dbreturn[0]["FallToSpringProjectedGrowth"] == ""){
                      echo "<div>No Fall Projections</div>";
                    }else{
                      echo "<table id='fallGrowthIndexTable' class='highlight'>";
                        echo "<thead>";
                          echo "<tr>";
                          echo "<th>Projection Quarter</th>";
                          echo "<th>Projected Growth</th>";
                          echo "<th>Observed Growth</th>";
                          echo "<th class='hide-on-med-and-down'>Observed Growth SE</th>";
                          echo "<th class='hide-on-small-only'>Growth Met?</th>";
                          echo "<th>Growth Index</th>";
                          echo "<th class='hide-on-small-only'>Growth Percentile</th>";
                          echo "</tr>";
                        echo "</thead>";
                        for($i = 0; $i < $arraySize; $i++){
                          $season = $seasonArray[$i];
                          if(!isset($dbreturn[0]['FallTo'.$season.'ProjectedGrowth']) || $dbreturn[0]['FallTo'.$season.'ProjectedGrowth'] == ""){
                            continue;
                          }else{
                            $projectionQuarter = $season;
                            $projectedGrowth = $dbreturn[0]['FallTo'.$season.'ProjectedGrowth'];
                            $observedGrowth = $dbreturn[0]['FallTo'.$season.'ObservedGrowth'];
                            $observedGrowthSE = $dbreturn[0]['FallTo'.$season.'ObservedGrowthSE'];
                            $metGrowth = $dbreturn[0]['FallTo'.$season.'MetProjectedGrowth'];
                            $growthIndex = $dbreturn[0]['FallTo'.$season.'ConditionalGrowthIndex'];
                            $growthPercentile = $dbreturn[0]['FallTo'.$season.'ConditionalGrowthPercentile'];

                            echo "<tr>";
                              echo "<td>$projectionQuarter</td>";
                              echo "<td>$projectedGrowth</td>";
                              echo "<td>$observedGrowth</td>";
                              echo "<td class='hide-on-med-and-down'>$observedGrowthSE</td>";
                              echo "<td class='hide-on-small-only'>$metGrowth</td>";
                              echo "<td>$growthIndex</td>";
                              echo "<td class='hide-on-small-only'>$growthPercentile</td>";
                            echo "</tr>";
                          }
                        }
                      echo "</table>";
                    }

              $seasonArray = ["Winter", "Spring"];
              $arraySize = sizeof($seasonArray);
              echo "<br>";
              echo "<h4>Winter</h4>";
                if($dbreturn[0]["WinterToWinterProjectedGrowth"] == "" && $dbreturn[0]["WinterToSpringProjectedGrowth"] == ""){
                      echo "<div>No Winter Projections</div>";
                    }else{
                      echo "<table id='winterGrowthIndexTable' class='highlight'>";
                        echo "<thead>";
                          echo "<tr>";
                          echo "<th>Projection Quarter</th>";
                          echo "<th>Projected Growth</th>";
                          echo "<th>Observed Growth</th>";
                          echo "<th class='hide-on-med-and-down'>Observed Growth SE</th>";
                          echo "<th class='hide-on-small-only'>Growth Met?</th>";
                          echo "<th>Growth Index</th>";
                          echo "<th class='hide-on-small-only'>Growth Percentile</th>";
                          echo "</tr>";
                        echo "</thead>";
                        for($i = 0; $i < $arraySize; $i++){
                          $season = $seasonArray[$i];
                          if(!isset($dbreturn[0]['WinterTo'.$season.'ProjectedGrowth']) || $dbreturn[0]['WinterTo'.$season.'ProjectedGrowth'] == ""){
                            continue;
                          }else{
                            $projectionQuarter = $season;
                            $projectedGrowth = $dbreturn[0]['WinterTo'.$season.'ProjectedGrowth'];
                            $observedGrowth = $dbreturn[0]['WinterTo'.$season.'ObservedGrowth'];
                            $observedGrowthSE = $dbreturn[0]['WinterTo'.$season.'ObservedGrowthSE'];
                            $metGrowth = $dbreturn[0]['WinterTo'.$season.'MetProjectedGrowth'];
                            $growthIndex = $dbreturn[0]['WinterTo'.$season.'ConditionalGrowthIndex'];
                            $growthPercentile = $dbreturn[0]['WinterTo'.$season.'ConditionalGrowthPercentile'];

                            echo "<tr>";
                              echo "<td>$projectionQuarter</td>";
                              echo "<td>$projectedGrowth</td>";
                              echo "<td>$observedGrowth</td>";
                              echo "<td class='hide-on-med-and-down'>$observedGrowthSE</td>";
                              echo "<td class='hide-on-small-only'>$metGrowth</td>";
                              echo "<td>$growthIndex</td>";
                              echo "<td class='hide-on-small-only'>$growthPercentile</td>";
                            echo "</tr>";
                          }
                        }
                      echo "</table>";
                    }

              $seasonArray = ["Spring"];
              $arraySize = sizeof($seasonArray);
              echo "<br>";
              echo "<h4>Spring</h4>";
                if($dbreturn[0]["SpringToSpringProjectedGrowth"] == ""){
                      echo "<div>No Spring Projections</div>";
                    }else{
                      echo "<table id='winterGrowthIndexTable' class='highlight'>";
                        echo "<thead>";
                          echo "<tr>";
                          echo "<th>Projection Quarter</th>";
                          echo "<th>Projected Growth</th>";
                          echo "<th>Observed Growth</th>";
                          echo "<th class='hide-on-med-and-down'>Observed Growth SE</th>";
                          echo "<th class='hide-on-small-only'>Growth Met?</th>";
                          echo "<th>Growth Index</th>";
                          echo "<th class='hide-on-small-only'>Growth Percentile</th>";
                          echo "</tr>";
                        echo "</thead>";
                        for($i = 0; $i < $arraySize; $i++){
                          $season = $seasonArray[$i];
                          if(!isset($dbreturn[0]['SpringTo'.$season.'ProjectedGrowth']) || $dbreturn[0]['SpringTo'.$season.'ProjectedGrowth'] == ""){
                            continue;
                          }else{
                            $projectionQuarter = $season;
                            $projectedGrowth = $dbreturn[0]['SpringTo'.$season.'ProjectedGrowth'];
                            $observedGrowth = $dbreturn[0]['SpringTo'.$season.'ObservedGrowth'];
                            $observedGrowthSE = $dbreturn[0]['SpringTo'.$season.'ObservedGrowthSE'];
                            $metGrowth = $dbreturn[0]['SpringTo'.$season.'MetProjectedGrowth'];
                            $growthIndex = $dbreturn[0]['SpringTo'.$season.'ConditionalGrowthIndex'];
                            $growthPercentile = $dbreturn[0]['SpringTo'.$season.'ConditionalGrowthPercentile'];

                            echo "<tr>";
                              echo "<td>$projectionQuarter</td>";
                              echo "<td>$projectedGrowth</td>";
                              echo "<td>$observedGrowth</td>";
                              echo "<td class='hide-on-med-and-down'>$observedGrowthSE</td>";
                              echo "<td class='hide-on-small-only'>$metGrowth</td>";
                              echo "<td>$growthIndex</td>";
                              echo "<td class='hide-on-small-only'>$growthPercentile</td>";
                            echo "</tr>";
                          }
                        }
                      echo "</table>";
                    }
            echo "</div>";
          echo "</div>";
        echo "</div>";
      echo "</li>";

    echo "</ul>";
	}
?>

<script>

  $(function(){

      $('select').material_select();
      $('.collapsible').collapsible();
  });

</script>