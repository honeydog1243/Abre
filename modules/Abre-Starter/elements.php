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
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

	$siteColor = getSiteColor();

?>

<div class='page_container mdl-shadow--4dp'>
	<div class='page'>
		<div class='row'>
			<h3>Here are some elements you can use to create your apps!</h3>
			<h4>Here are some elements you can use to create your apps!</h4>
			<h5>Here are some elements you can use to create your apps!</h5>
			<h6>Here are some elements you can use to create your apps!</h6>
		</div>
		<div class='row'>
			<h5>Buttons: </h5>
			<a class='waves-effect waves-light btn' style='background-color: <?php echo $siteColor; ?>'>button</a>
			<a class='waves-effect waves-light btn-large' style='background-color:<?php echo $siteColor; ?>; left:10px;'>Button</a>
			<a class='btn-floating btn-large waves-effect waves-light' style='background-color:<?php echo $siteColor; ?>; left:20px;'><i class='material-icons'>add</i></a>
		</div>
		<div class='row'>
			<h5>Cards: </h5>
			  <div class='mdl-card mdl-shadow--2dp card_stream hoverable' style='float:left;'>
					<h4 class='center-align'>Centered Content</h4>
          <div class="mdl-card__media mdl-color--grey-100 mdl-card--expand valign-wrapper cardclick pointer" style="height:200px; background-image: url(/core/images/abre/abre_pattern.png); background-color: <?php echo $siteColor; ?> !important; overflow:hidden;"></div>
					<div class='mdl-card__actions'>
						<a class='mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect' href='#' style='color:<?php echo $siteColor; ?>;'>Link to Homepage</a>
					</div>
				</div>
				<div class='mdl-card mdl-shadow--2dp card_stream hoverable' style='float:left;'>
					<h4 class='center-align'>Centered Content</h4>
					<div class='mdl-card__media mdl-color--grey-100 mdl-card--expand cardclick pointer' style='height:200px; background-image: url(/core/images/abre/abre_pattern.png); background-color: <?php echo $siteColor; ?> !important; overflow:hidden;'></div>
					<div class='mdl-card__actions'>
						<a class='mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect' href='#' style='color:<?php echo $siteColor; ?>;'>Link to Homepage</a>
					</div>
				</div>
		</div>
		<div class='row'>
			<h5>Modal Example: </h5>
			<a class='modal-startermodal' href='#startermodal' style='color: <?php echo $siteColor; ?>;' data-info='This is data being passed from the clickable button'>This will open a modal</a>
		</div>
		<div class='row'>
			<h5>Example table with data and icons: </h5>
			<table id='myTable' class='tablesorter'>
				<thead>
						<th>Header Title 1</th>
						<th>Header Title 2</th>
						<th style='width:30px'></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Data Element 1</td>
						<td>Data Element 2</td>
						<td><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600' href='#!'><i class='material-icons'>mode_edit</i></button></td>
						<td></td>
					</tr>
          <tr>
            <td>Data Element 3</td>
            <td>Data Element 4</td>
            <td><button class='mdl-button mdl-js-button mdl-button--icon mdl-color-text--grey-600' href='#!'><i class='material-icons'>mode_edit</i></button></td>
            <td></td>
          </tr>
        </tbody>
      </table>
		</div>
    <div class='row'>
      <h5>Sample Form: </h5>
      <form class="" id="form-starter" method="post" action="">
          <b>Sample Text Entry</b>
          <div class='row'>
            <div class="input-field">
              <input id="textinput" name="textinput" type="text" maxlength="20" placeholder="Enter text here" autocomplete="off" required>
            </div>
          </div>
          <div class='row'>
            <div class='col s12'>
              <b>Radio Buttons</b>
            </div>
            <br><br>
            <div class="col l4 s12" style='padding-left:5px;'>
              <span style='margin-right:50px;'><input name="Option" type="radio" id="personal" value="1" required /><label for="personal">Radio Option 1</label></span>
              <span><input name="Option" type="radio" id="office" value="2" /><label for="office">Radio Option 2</label></span>
            </div>
            <div>
              <select class='col l3 s6' id='selectValue' name='selectValue'>
                  <option value=''></option>
                  <option value='1'>Option 1</option>
                  <option value='2'>Option 2</option>
              </select>
            </div>
          </div>
          <button type="submit" class="modal-action waves-effect btn-flat white-text" style='background-color: <?php echo $siteColor; ?>;'>Submit</button>
      </form>
    </div>
    <div class='row'>
      <b>Form Results </b>
      <h5 id='formResults'></h5>
    </div>
    <div class='row'>
      <h5>Additional Resources: </h5>
      <a href='http://materializecss.com/' style='color: <?php echo $siteColor; ?>;'>Materialize CSS</a><br>
      <a href='https://material.io/icons/' style='color: <?php echo $siteColor; ?>;'>Material Icons</a><br>
      <a href='https://getmdl.io/' style='color: <?php echo $siteColor; ?>;'>Material Design Lite</a>
    </div>
	</div>
</div>

<script>

	$(function(){

    $('select').material_select();

		$('.modal-startermodal').leanModal({ in_duration: 0, out_duration: 0, ready: function() { $('.modal-content').scrollTop(0); } });

		$(document).on("click", ".modal-startermodal", function () {
			var info = $(this).data('info');
			$(".modal-content #infoHolder").text(info);
		});

    //you can use this code to send data to the server or another page if needed
    // var formStarter = $('#form-starter');
    //
    // $(formStarter).submit(function(event) {
    //   event.preventDefault();
    //   var formData = $(formStarter).serialize();
    //   $.ajax({
    //     type: 'POST',
    //     url: $(formStarter).attr('action'),
    //     data: formData
    //   })
    //
    //   //Show the notification
    //   .done(function(responseprocess) {
    //     //do something after the ajax call has sent data successfully
    //   })
    // });

    var formStarter = $("#form-starter");
    $(formStarter).submit(function(event){
      event.preventDefault();
      var results = $(formStarter).serialize();
      results = results.replace(/&/g, ", ");
      results = results.replace(/=/g, " = ");
      $("#formResults").text(results);
    });

	});

</script>