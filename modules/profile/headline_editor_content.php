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
require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

if(admin() || isStreamHeadlineAdministrator()){

  echo "<table class='bordered' id='headlinestable'>";
  $today = date("Y-m-d");
  $query = "SELECT id, title, purpose, content, form_id, video_id, groups, date_restriction, start_date, end_date, required FROM headlines WHERE siteID = '".$_SESSION['siteID']."' ORDER BY start_date";
  $dbreturn = databasequery($query);
  $i = 0;
  foreach($dbreturn as $value){
    $id = $value['id'];
    $title = $value['title'];
    $titleencoded = htmlspecialchars($title, ENT_QUOTES);
    $purpose = $value['purpose'];
    $content = htmlspecialchars($value['content'], ENT_QUOTES);
    $video_id = htmlspecialchars($value['video_id'], ENT_QUOTES);
    $form_id = $value['form_id'];
    $groups = $value['groups'];
    $date_restriction = $value['date_restriction'];
    $start_date = $value['start_date'];
    $end_date = $value['end_date'];
    $required = $value['required'];
    $requiredText = "";
    if($required == 1){
      $requiredText = "Yes";
    }else{
      $requiredText = "No";
    }
    if($i == 0){
      echo "<tr>";
      echo "<th>Title</th>";
      echo "<th>Groups</th>";
      echo "<th class='center-align'>Active</th>";
      echo "<th></th>";
      echo "<th></th>";
      echo "</tr>";
    }
    echo "<tr>";
    echo "<td>$title</td>";
    echo "<td>".ucwords($groups)."</td>";
    if($date_restriction == 0 || ($start_date <= $today && $today <= $end_date)){
      echo "<td class='center-align'><i class='material-icons' style='color:#4CAF50;'>brightness_1</i></td>";
    }else{
      echo "<td class='center-align'><i class='material-icons' style='color:#F44336;'>brightness_1</i></td>";
    }
    echo "<td style='width:30px'><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 passheadlinedata' data-id='$id' data-headlinetitle='$titleencoded' data-purpose='$purpose' data-groups='$groups' data-formid='$form_id' data-video='$video_id' data-required='$required' data-content='$content' data-startdate='$start_date' data-enddate='$end_date' data-daterestriction='$date_restriction'><i class='material-icons'>mode_edit</i></button></td>";
    echo "<td style='width:30px'><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 deleteheadline' data-headlineid='$id'><i class='material-icons'>delete</i></button></td>";
    echo "</tr>";
    $i++;
  }
  echo "</table>";
}

?>

<script>

  $(function(){
    <?php
    if(admin() || isStreamHeadlineAdministrator()){
    ?>

      //Delete headline
      $(".deleteheadline").unbind().click(function() {
        event.preventDefault();
        var result = confirm("All responses with this headline will be deleted. Are you sure you want to delete this headline?");
        if(result){

          $(this).closest("tr").hide();
          var headlineid = $(this).data('headlineid');

          //Make the post request
          $.ajax({
            type: 'POST',
            url: 'modules/profile/delete_headline.php?id='+headlineid,
            data: '',
          })
          .done(function(){
            $('#headlinestable').load('modules/profile/headline_editor_content.php');
          });
        }
      });

      //Get Headline Data
      $(".passheadlinedata").unbind().click(function() {

        //Fill Modal with Data
        var headlineid = $(this).data('id');
        $("#headline_id").val(headlineid);
        var headlineTitle = $(this).data('headlinetitle');
        $("#addeditheadlinetitle").text(headlineTitle);
        $("#headlineTitle").val(headlineTitle);
        var date_restriction = $(this).data('daterestriction');
        if(date_restriction == 1){
          $("#headlineDateRestriction").prop('checked', true);
        }else{
          $("#headlineDateRestriction").prop('checked', false);
        }

        if(date_restriction == 1){
          $("#headlineStartDateDiv").show();
          $("#headlineEndDateDiv").show();
        }else{
          $("#headlineStartDateDiv").hide();
          $("#headlineEndDateDiv").hide();
        }

        var headlineStartDate = $(this).data('startdate');
        var picker = $('#headlineStartDate').pickadate('picker');
        picker.set('select', headlineStartDate);
        var headlineEndDate = $(this).data('enddate');
        var picker2 = $('#headlineEndDate').pickadate('picker');
        picker2.set('select', headlineEndDate);

        var headlinePurpose = $(this).data('purpose');
        $("#headlinePurpose").val(headlinePurpose);
        if(headlinePurpose == "text"){
          $("#headlineFormDiv").hide();
          $("#headlineVideoDiv").hide();
        }
        if(headlinePurpose == "form"){
          $("#headlineFormDiv").show();
          $("#headlineVideoDiv").hide();
        }
        if(headlinePurpose == "video"){
          $("#headlineFormDiv").hide();
          $("#headlineVideoDiv").show();
        }
        if(headlinePurpose == ""){
          $("#headlineFormDiv").hide();
          $("#headlineVideoDiv").hide();
        }

        var headlineContent = $(this).data('content');
        $("#headlineContent").val(headlineContent);
        var form_id = $(this).data('formid');
        $("#headlineForm").val(form_id);
        var video_id = $(this).data('video');
        $("#headlineVideo").val(video_id);
        var groups = $(this).data('groups');

        if(groups.indexOf('staff') != -1){
          $('#headline_staff').prop('checked', true);
        }else{
          $('#headline_staff').prop('checked', false);
        }
        if(groups.indexOf('student') != -1){
          $('#headline_students').prop('checked', true);
        }else{
          $('#headline_students').prop('checked', false);
        }
        if(groups.indexOf('parents') != -1){
          $('#headline_parents').prop('checked', true);
        }else{
          $('#headline_parents').prop('checked', false);
        }

        var required = $(this).data('required');
        if(required == 1){
          $('#headlineRequired').prop('checked', true);
        }else{
          $('#headlineRequired').prop('checked', false);
        }

        $('#addeditheadline').openModal({
          in_duration: 0,
          out_duration: 0,
          ready: function() {
            $('select').material_select();
            $('.datepickerformatted').pickadate({ container: 'body', format: 'yyyy-mm-dd', selectMonths: true, selectYears: 15 });
            $(".modal-content").scrollTop(0);
          }
        });
      });

  <?php
    }
  ?>
  });

</script>