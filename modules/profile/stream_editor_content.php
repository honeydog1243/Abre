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

  echo "<table class='bordered' id='streamsort'>";
  $query = "SELECT id, title, url, `group`, required, color, staff_building_restrictions, student_building_restrictions FROM streams WHERE siteID = '".$_SESSION['siteID']."' ORDER BY title";
  $dbreturn = databasequery($query);
  foreach($dbreturn as $value){
    $id = $value['id'];
    $title = $value['title'];
    $titleencoded = base64_encode($title);
    $link = $value['url'];
    $linkencoded = base64_encode($link);
    $group = $value['group'];
    if($group == ""){
      $group = "no assigned groups";
    }
    $required = $value['required'];
    $staffRestrictions = $value['staff_building_restrictions'];
    $studentRestrictions = $value['student_building_restrictions'];
    $color = $value['color'];
    echo "<tr id='item-$id'>";
    echo "<td style='width:30px;'>";
    if($color != ""){
      echo "<div class='btn-floating btn-flat' style='background-color:$color; cursor:default;'>";
    }else{
      echo "<div class='btn-floating btn-flat' style='background-color:#BDBDBD; cursor:default;'>";
    }
    echo "</div></td>";
    echo "<td><b>$title</b> (".ucwords($group).")</td>";
    echo "<td style='width:30px'><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 passstreamdata' data-streamtitle='$titleencoded' data-rsslink='$linkencoded' data-streamid='$id' data-streamgroup='$group' data-required='$required' data-color='$color' data-staffrestrictions='$staffRestrictions' data-studentRestrictions='$studentRestrictions'><i class='material-icons'>mode_edit</i></button></td>";
    echo "<td style='width:30px'><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600 deletestream' data-streamtitle='$titleencoded' data-streamid='$id'><i class='material-icons'>delete</i></button></td>";
    echo "</tr>";
  }
  echo "</table>";
}

?>

<script>

  $(function(){

    <?php
    if(admin() || isStreamHeadlineAdministrator()){
    ?>

      //Delete stream
      $(".deletestream").unbind().click(function() {
        event.preventDefault();
        var result = confirm("This will delete all custom posts associated with this stream. Are you sure you want to delete this stream?");
        if(result){

          $(this).closest("tr").hide();
          var streamid = $(this).data('streamid');
          var streamtitle = $(this).data('streamtitle')

          //Make the post request
          $.ajax({
            type: 'POST',
            url: 'modules/profile/stream_delete.php?id='+streamid+'&title='+streamtitle,
            data: '',
          })
          .done(function(){
            $('#content_holder').load('modules/profile/profile.php');
          });

        }
      });

      //Get Stream Data
      $(".passstreamdata").unbind().click(function() {

        //Fill Modal with Data
        var streamtitle = atob($(this).data('streamtitle'));
        $("#editstreammodaltitle").text(streamtitle + " Stream");
        $("#stream_name").val(streamtitle);
        var rsslink = atob($(this).data('rsslink'));
        $("#rss_link").val(rsslink);

        var streamid = $(this).data('streamid');
        $("#stream_id").val(streamid);
        var group = $(this).data('streamgroup');
        var required = $(this).data('required');
        if(group.indexOf('staff') != -1){
          $('#stream_staff').prop('checked', true);
        }else{
          $('#stream_staff').prop('checked', false);
        }
        if(group.indexOf('student') != -1){
          $('#stream_students').prop('checked', true);
        }else{
          $('#stream_students').prop('checked', false);
        }
        if(group.indexOf('parents') != -1){
          $('#stream_parents').prop('checked', true);
        }else{
          $('#stream_parents').prop('checked', false);
        }
        if(required == 1){
          $('#required_stream').prop('checked', true);
        }else{
          $('#required_stream').prop('checked', false);
        }

        if($("#stream_staff").is(':checked')){
          $("#streamStaffRestrictionsDiv").show();
        }else{
          $("#streamStaffRestrictionsDiv").hide();
        }

        if($("#stream_students").is(':checked')){
          $("#streamStudentRestrictionsDiv").show();
        }else{
          $("#streamStudentRestrictionsDiv").hide();
        }

        var staffRestrictions = $(this).data('staffrestrictions');
        staffRestrictions = String(staffRestrictions);
        if(staffRestrictions != ""){
          if(staffRestrictions.indexOf(',') >= 0){
            var dataarray = staffRestrictions.split(",");
            $("#streamStaffRestrictions").val(dataarray);
          }else{
            $("#streamStaffRestrictions").val(staffRestrictions);
          }
        }else{
          $("#streamStaffRestrictions").val('No Restrictions');
        }

        var studentRestrictions = $(this).data('studentrestrictions');
        studentRestrictions = String(studentRestrictions);
        if(studentRestrictions != ""){
          if(studentRestrictions.indexOf(',') >= 0){
            var dataarray = studentRestrictions.split(",");
            for(var i = 0; i < dataarray.length; i++){
              dataarray[i] = dataarray[i].trim();
            }
            $("#streamStudentRestrictions").val(dataarray);
          }else{
            $("#streamStudentRestrictions").val(studentRestrictions);
          }
        }else{
          $("#streamStudentRestrictions").val('No Restrictions');
        }

        var color = $(this).data('color');
        if(color != ""){
          $("#removeColor").show();
        }else{
          $("#removeColor").hide();
        }
        $('#addeditstream').openModal({
          in_duration: 0,
          out_duration: 0,
          ready: function() {
            $('select').material_select();
            $('.modal-content').scrollTop(0);
            $("#stream_color").spectrum({
              color: color,
              allowEmpty: true,
  						showPaletteOnly: true,
  						showPalette: true,
              palette: [["#F44336", "#B71C1C", "#9C27B0", "#4A148C"],
  											["#2196F3", "#0D47A1", "#4CAF50", "#1B5E20"],
  											["#FF9800", "#E65100", "#607D8B", "#263238"]],
              hide: function(color) {
  							if(color !== null){
  								$("#removeColor").show();
  						 }
  					 }
            });
          }
        });
      });

  <?php
    }
  ?>

  });

</script>