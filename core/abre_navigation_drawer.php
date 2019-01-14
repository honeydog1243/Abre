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
	require_once('abre_google_login.php');
	require_once('abre_functions.php');
?>

	<!--Display the drawer-->
	<div class='drawer mdl-layout__drawer' id='drawer' style='border:none;'>
		<header class='drawer-header' style='background-color: <?php echo getSiteColor(); ?>'>
			<?php
				echo "<img src='".$_SESSION['picture']."?sz=100' class='avatar' alt='User Profile Photo'>";
				echo "<span class='mdl-color-text--white truncate'>".$_SESSION['displayName']."</span>";
				echo "<span class='mdl-color-text--white truncate'>".$_SESSION['useremail']."</span>";
			?>
		</header>
		<nav class='navigation collapse mdl-navigation' style='background-color: #fdfdfd;'>
			<?php
				//Load Modules
				for($modulecountloop = 0; $modulecountloop < $modulecount; $modulecountloop++){
					$pagetitle = $modules[$modulecountloop][1];
					$pageview = $modules[$modulecountloop][2];
					$pageicon = $modules[$modulecountloop][3];
					$pagepath = $modules[$modulecountloop][4];
					$drawerhidden = $modules[$modulecountloop][5];
					$subpages = $modules[$modulecountloop][6];

					$pagepathArray = explode('/', $pagepath);
					$pageHighlighting = $pagepathArray[0];

					if($pageview == 1 && $drawerhidden != 1){

						echo "<li style='display:block;'>";
							echo "<div class='collapsible-header main-app-link' id='abreapp_$pageHighlighting' style='padding:0; border-bottom: 0;'>";;
									if($subpages!=NULL){
										echo "<span class='mdl-navigation__link main-app-link-span' ";
									}else{
										echo "<span class='mdl-navigation__link path main-app-link-span' data-path='#$pagepath' ";
										echo "onclick='toggle_drawer()' ";
									}
									echo ">";
										echo "<i class='material-icons drawericon main-app-link-icon' style='margin-left:-3px; color: #747474' role='presentation'>$pageicon</i>";
										echo "<span class='truncate main-app-link-span'>$pagetitle</span>";
									echo "</span>";
							echo "</div>";

							if($subpages!=NULL){

								foreach ($subpages as $key => $sublinks){

									$pagepath=$sublinks['path'];
									echo "<div class='collapsible-body pointer sub-app-link' id='abreapp_$pagepath' style='border:0;'>";
										echo "<span class='mdl-navigation__link path sub-app-link-span' data-path='#$pagepath' onclick='toggle_drawer()'>";
										echo "<span class='truncate sub-app-link-span' style='margin-left:66px; font-weight:normal !important;'>";
											echo $sublinks['title'];
										echo "</span></span>";
									echo "</div>";

								}

							}

						echo "</li>";

					}
				}

				//Divider
				if(superadmin() or admin())
				{
					echo "<div class='mdl-menu__item--full-bleed-divider' style='margin:10px 0 10px 0'></div>";
				}

				//Store link
				if(superadmin())
				{
					echo "<li style='display:block;'>";
						echo "<div class='collapsible-header main-app-link' id='abreapp_store' style='padding:0; background-color:none; border-bottom: 0;'>";
							echo "<span class='mdl-navigation__link path main-app-link-span' data-path='#store' onclick='toggle_drawer()'>";
							echo "<i class='material-icons drawericon main-app-link-icon' style='margin-left:-3px; color: #747474' role='presentation'>store</i>";
							echo "<span class='truncate main-app-link-span'>Store</span></span>";
						echo "</div>";
					echo "</li>";
				}

				//Settings link
				if(admin())
				{
					echo "<li style='display:block;'>";

						echo "<div class='collapsible-header main-app-link' id='abreapp_settings' style='padding:0; background-color:none; border-bottom: 0;'>";
							echo "<span class='mdl-navigation__link path main-app-link-span' data-path='#settings' onclick='toggle_drawer()'>";
							echo "<i class='material-icons drawericon main-app-link-icon' style='margin-left:-3px; color: #747474' role='presentation'>settings</i>";
							echo "<span class='truncate main-app-link-span'>Settings</span></span>";
						echo "</div>";
					echo "</li>";
				}

			echo "</nav>";
		echo "</div>";

		//Help Icon
		echo "<button class='mdl-button mdl-js-button mdl-button--icon' id='help-icon-button' style='position:absolute; z-index:2; bottom:25px; left:25px;'>";
			echo "<i class='material-icons' style='color:#9E9E9E;'>help</i>";
		echo "</button>";

		//Help Menu
		echo "<ul class='mdl-menu mdl-menu--top-left mdl-js-menu mdl-js-ripple-effect' data-mdl-for='help-icon-button'>";
			echo "<li class='mdl-menu__item' id='help-icon-kb'>Knowledge Base</li>";
			if(superadmin() or admin()){
				echo "<li class='mdl-menu__item' id='help-icon-ticket'>Create Support Ticket</li>";
			}
			echo "<li class='mdl-menu__item help-icon-email' data-to='".getSiteAdminEmail()."' data-subject='Abre Administrator Message' data-title='Contact Abre Administrator' data-helptext='Have a question about Abre? Reach out to your district Abre Administrator (".getSiteAdminEmail().") below.'>Contact Abre Administrator</li>";
			echo "<li class='mdl-menu__item' id='help-icon-featurerequest'>Ideas and Feature Requests</li>";
		echo "</ul>";


	?>

<script>

	$(function(){

		//Collaspsable Sublink Menu Items
		$('.collapse').collapsible();

		//Send Email Click
    $(".help-icon-email").unbind().click(function(event){
			event.preventDefault();
			var to = $(this).data('to');
			$('#email-to').val(to);
			var subject = $(this).data('subject');
			$('#email-subject').val(subject);
			var title = $(this).data('title');
			$('#email-title').text(title);
			var helptext = $(this).data('helptext');
			$('#email-helptext').text(helptext);
			$('#send-email').openModal({
				in_duration: 0,
				out_duration: 0
			});
		});

		//Support Ticket Click
		$("#help-icon-ticket").unbind().click(function(event){
			event.preventDefault();
			window.open('https://abreio.atlassian.net/servicedesk/customer/portal/1/group/1/create/1', '_blank');
		});

		//KB Click
		$("#help-icon-kb").unbind().click(function(event){
			event.preventDefault();
			window.open('https://abreio.atlassian.net/wiki/spaces/AKBK/overview', '_blank');
		});

		//Support Ticket Click
		$("#help-icon-featurerequest").unbind().click(function(event){
			event.preventDefault();
			window.open('https://community.abre.io/forums/forum/feature-requests/', '_blank');
		});

		//Make Links Clickable
		$(".path").unbind().click(function(){
			var Path = $(this).data('path');
			if(Path!=''){
				window.location = Path;
			}
		});

	});

</script>
