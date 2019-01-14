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
?>

	<!--Display top navigation-->
	<header class='mdl-layout__header mdl-color-text--white' style='background-color: <?php echo getSiteColor(); ?>'>
		<div class='mdl-layout__header-row'>
			<span class='mdl-layout-title' style="max-width: 75%;"><div id='titletext' class='truncate'></div></span>
			<div class='mdl-layout-spacer'></div>
				<?php
					if(!isset($_GET["dash"])){
						if($_SESSION['usertype'] == 'parent'){
							echo "<a id='addstudent' class='mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon modal-verifystudent' href='#verifystudent' style='margin-right:10px'><i class='material-icons'>add</i></a>";
							isVerified();
						}
						echo "<a class='mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon modal-viewapps' href='#viewapps'><i class='material-icons'>apps</i></a>";
					}
				?>
				<div class='navspace'></div>
				<?php
						echo "<a href='#viewprofile' class='mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon modal-viewprofile'><img src='".$_SESSION['picture']."?sz=120' class='img-center' alt='User Profile Photo' style='width:32px; height:32px;'></a>";
				?>
		</div>
		<div id='navigation_top'></div>
	</header>