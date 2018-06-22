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

		$Student_ID = $_GET["StudentID"];
    $termName = $_GET["termName"];

    echo "<div class='row' style='background-color:".getSiteColor().";'>";
      echo "<div class='col s12'>";
        echo "<ul class='tabs' style='background-color:".getSiteColor().";'>";
    		$query = "SELECT TestName FROM Abre_MAPData WHERE StudentID = '$Student_ID' AND TermName ='$termName'";
    		$dbreturn = databasequery($query);
    		$returncount = 0;
    		foreach ($dbreturn as $value){
          $returncount++;
          $testName = $value["TestName"];
          if(strpos($testName, "Math") !== false){
            echo "<li class='tab col s3' data-testname='$testName' data-studentid='$Student_ID' data-term='$termName'><a href=''>Math</a></li>";
          }elseif(strpos($testName, "Algebra") !== false){
            echo "<li class='tab col s3' data-testname='$testName' data-studentid='$Student_ID' data-term='$termName'><a href=''>Algebra</a></li>";
          }elseif(strpos($testName, "Geometry") !== false){
            echo "<li class='tab col s3' data-testname='$testName' data-studentid='$Student_ID' data-term='$termName'><a href=''>Geometry</a></li>";
					}elseif(strpos($testName, "Reading") !== false){
            echo "<li class='tab col s3' data-testname='$testName' data-studentid='$Student_ID' data-term='$termName'><a href=''>Reading</a></li>";
					}
    		}
        echo "</ul>";
      echo "</div>";
    echo "</div>";

    echo"<div class='row'>";
      echo "<div id='testData'></div>";
    echo "</div>";


		if($returncount==0){ echo "<div class='row' style='padding:56px; text-align:center; width:100%;'><span style='font-size: 22px; font-weight:700'>No Assessment Details</span><br><p style='font-size:16px; margin:20px 0 0 0;'>Details for this assessment could not be found.</p></div>"; }

	}
?>

<script>

  $(function(){

    var firstTest = "<?php echo $dbreturn[0]["TestName"]; ?>";
    var StudentID = "<?php echo $Student_ID ?>";
    var termName = "<?php echo $termName ?>";
    firstTest = encodeURIComponent(firstTest);
    StudentID = encodeURIComponent(StudentID);
    termName = encodeURIComponent(termName);
    $("#testData").load('modules/<?php echo basename(__DIR__); ?>/map_assessmenttables.php?StudentID='+StudentID+'&termName='+termName+'&testName='+firstTest, function(){

    });

    $(document).off().on('click', '.tab', function(){
      var StudentID = $(this).data('studentid');
      var termName = encodeURIComponent($(this).data('term'));
      var testName = encodeURIComponent($(this).data('testname'));
      $("#testData").load('modules/<?php echo basename(__DIR__); ?>/map_assessmenttables.php?StudentID='+StudentID+'&termName='+termName+'&testName='+testName, function(){

      });

    });

  });

</script>