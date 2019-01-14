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
	require_once('abre_functions.php');
	$siteColor = getSiteColor();

	//Display the Login
	echo "<main>";

		//Overlay Div
		echo "<div id='loadingcover' style='background-color:".$siteColor."; position:fixed; width:100%; height:100%; z-index:2'></div>";

		//Login Holder
		echo "<div class='mdl-layout mdl-js-layout login-card' style='background-color:".$siteColor."; background-image: url(/core/images/abre/abre_pattern.png); '>";

			echo "<div class='login_wrapper' style='z-index:1;'>";
				echo "<div class='login-card-square mdl-card'>";

						//Site Logo
						echo "<div style='margin:30px 40px 10px 45px; height:240px; width:240px; background:url(". getSiteLogo() .") center center no-repeat; margin-bottom:10px; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;'></div>";

						//Site Login Text
						echo "<div class='mdl-card-text mdl-color-text--grey-600' style='text-align:center; margin:0 40px 10px 40px;'>Choose a sign in service</div>";

						//Logins
						echo "<div class='loginholder'>";

							//Display SSO Options
							if(getSiteGoogleSignInGroups('staff') == "checked" && getSiteGoogleClientId() !== '' && getSiteGoogleClientSecret() !== ''){
								echo "<div style='padding-top: 10px'>";
									include "abre_button_google.php";
								echo "</div>";
							}
							if(getSiteMicrosoftSignInGroups('staff') == "checked" && getSiteMicrosoftClientId() !== '' && getSiteMicrosoftClientSecret() !== ''){
								echo "<div style='padding-top: 10px'>";
									include "abre_button_microsoft.php";
								echo "</div>";
							}

							//Return Text
							echo "<div class='mdl-color-text--grey-600' style='text-align:center; margin:30px 40px 10px 40px; font-size:13px;'><a href='/' class='mdl-color-text--grey-600' style='font-weight:400'>Return to Homepage</a></span></div>";

						echo "</div>";

				echo "</div>";
			echo "</div>";

			include "abre_copyright.php";

		echo "</div>";

	echo "</main>";

?>

<script>

	$(function(){

		$("#loadingcover").delay(300).fadeOut();

		//Responsive login view
		function loginWidthCheck(){
			if ($(window).width() < 600){
				$(".login-card-square").removeClass("mdl-shadow--2dp");
				$(".login-card").addClass("mdl-color--white");
			}else{
				$(".login-card-square").addClass("mdl-shadow--2dp");
				$(".login-card").removeClass("mdl-color--white");
			}
		}

		loginWidthCheck();
		$(window).resize(loginWidthCheck);

	});

</script>