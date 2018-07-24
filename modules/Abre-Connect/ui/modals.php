<?php

	/*
	* Copyright (C) 2016-2017 Abre.io LLC
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
	require_once(dirname(__FILE__) . '/../../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../../core/abre_functions.php');
	require_once('../permissions.php');

	if($pagerestrictions == ""){

	}
?>

<div id="startermodal" class="modal modal-fixed-footer modal-mobile-full">
  <form id="new-journal-form" action="modules/Abre-Connect/functions/create-journal.php">
    <div class="modal-content" style="padding: 0px !important;">
      <div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
        <div class='col s11'>
          <span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">
            Create a New Journal
          </span>
        </div>
        <div class='col s1 right-align'>
          <a class="modal-close">
            <i class='material-icons' style='color: #fff;'>
              clear
            </i>
          </a>
        </div>
      </div>
      <div style='padding: 0px 24px 0px 24px;'>
        <div class="row">
          <div class="col s6">
            <label for="title">Title</label>
            <input id="title" name="title" type="text" class="validate" placeholder="Title">
          </div>
        </div>
        <div class="row">
          <div class="col s6">
            <label for="subject">Subjects</label>
            <input id="subject" name="subject" type="text" placeholder="Insert a Subject">
          </div>
          <div class="col s6">
            <!-- todo make a chip set of subjects -->
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <label for="body">Journal Body</label>
            <textarea id="body" name="body" placeholder="Write a Journal"></textarea>
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <div class="file-field input-field">
              <div class="btn" style="background-color: <?php echo getSiteColor(); ?>">
                <span>FILE</span>
                <input id="files" name="files" type="file" multiple>
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload files">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <div id ="footerButtonsDiv" style='display: inline-block; float:right'>
        <input type="submit" id="new-journal-submit" content="Save" class="btn" style="background-color: <?php echo getSiteColor(); ?>">
      </div>
    </div>
  </form>
</div>

<script>
  var form = $("#new-journal-form");
  form.submit((e) => {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: $(form).serialize()
    }).done((response) => {
      console.log('post finished:', response);
    });
  });
</script>
