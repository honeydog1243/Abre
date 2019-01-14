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

	//Include required files
	require_once('abre_verification.php');
	require_once('abre_functions.php');
	require('abre_dbconnect.php');

	$session_user_email=isset($_SESSION['useremail']) ? $_SESSION['useremail']: '';
	$session_id=isset($_SESSION['siteID']) ? $_SESSION['siteID']: '';
?>

<script>

		//handles Facebook _=_ callback issue
		if (window.location.hash && window.location.hash == '#_=_') {
				window.location.hash = '';
		}
		//Register MDL
		function mdlregister() {
			componentHandler.upgradeAllRegistered();
		}

		//Toggle slide navigation drawer
		function toggle_drawer() {
			var drawer = document.getElementsByClassName('mdl-layout__drawer')[0];
			var drawer_obfuscator = document.getElementsByClassName('mdl-layout__obfuscator')[0];
			drawer.classList.toggle("is-visible");
			drawer_obfuscator.classList.toggle("is-visible");
		}

		//Start the page
		function init_page(loader) {

			var cloudSetting = "<?php echo getenv("USE_GOOGLE_CLOUD"); ?>";

			//Selection State coloring for App Drawer
			$('.main-app-link').css('background-color','#fdfdfd');
			$('.main-app-link > .main-app-link-span').css('color','#000');
			$('.main-app-link > .main-app-link-span > .main-app-link-icon').css('color','#747474');

			$('.sub-app-link').css('background-color','#fdfdfd');
			$('.sub-app-link > .sub-app-link-span').css('color','#000');
			if(window.location.hash) {
				var hash = window.location.hash.substring(1);

				if (cloudSetting=="true") {
					Raygun.trackEvent('pageView', {
						path: '/' + hash
					});
				}

				hash = hash.split('/')[0];
				if($("#abreapp_" + hash).length == 1){
					$('#abreapp_' + hash).css('background-color','#f3f3f3');
					$('#abreapp_' + hash + '> span').css('color','<?php echo getSiteColor(); ?>');
					$('#abreapp_' + hash + '> span > i').css('color','<?php echo getSiteColor(); ?>');
				}
			}
			else
			{
				$('#abreapp_').css('background-color','#f3f3f3');
				$('#abreapp_ > span').css('color','<?php echo getSiteColor(); ?>');
				$('#abreapp_ > span > i').css('color','<?php echo getSiteColor(); ?>');
			}

			//Hide Loader
			if (loader === undefined | loader === "still"){ $("#loader").hide(); }

			//Scroll to Top
			var content = $(".mdl-layout__content");
			var target = top ? 0 : $(".content").height();
			content.stop().animate({ scrollTop: target }, 0);

			//Fade in Content
			$("#content_holder").fadeTo(0, 0);
			$("#content_holder").css({marginTop: '100px'});
			$("#content_holder").animate({ opacity: 1, marginTop: "0" }, 500, "swing");

			//Register MDL elements
			mdlregister();

			//Make sure top nav is present
			$("header").show();

			//Remove an overlays
			$(".lean-overlay").remove();

			//Add in menu
			$(".mdl-layout__drawer-button").show();
			$("#backbutton").remove();
		}

		//Back button in header
		function back_button(url) {
			$(".mdl-layout__drawer-button").hide();
			$(".mdl-layout__header").append("<a href='"+url+"' class='mdl-layout__drawer-button' id='backbutton'><i class='material-icons'>arrow_back</i></a>");
		}

		//Demo mode
		var DemoMode = false;
		$(document).keypress("d",function(e) {
			if(e.which && e.ctrlKey && e.shiftKey){
				if(DemoMode === false){
					$('<style id="demomodedark">.demotext_dark { color:transparent; text-shadow:0 0 15px rgba(0,0,0,1); }</style>').appendTo('head');
					$('<style id="demomodelight">.demotext_light { color:transparent; text-shadow:0 0 15px rgba(255,255,255,1); }</style>').appendTo('head');
					$('<style id="demomodeimage">.demoimage { -webkit-filter:blur(7px); filter:blur(7px); }</style>').appendTo('head');
					DemoMode = true;
				}else{
					$('#demomodedark').remove();
					$('#demomodelight').remove();
					$('#demomodeimage').remove();
					DemoMode = false;
				}
			}
		});

		//Load page routing
		routie({
			<?php
				if(isset($_SESSION['useremail'])){
					$moduledirectory = dirname(__FILE__) . '/../modules';
					$modulefolders = scandir($moduledirectory);

					foreach($modulefolders as $result){
						if(isAppActive($result)) {
							if(file_exists(dirname(__FILE__) . '/../modules/'.$result.'/routing.php')) {
								include(dirname(__FILE__) . '/../modules/'.$result.'/routing.php');
							}
						}
					}

					//Close Database
					$db->close();
				}
			?>
			'privacy': function(){
				window.location = "https://abre.io/privacy";
			},
			'*': function(){
				//window.location = "/";
			}
		});
</script>
