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

	//Google Auth Button
	if(!isset($authUrl)){
		echo "<script>";
		echo "window.location.href='/';";
		echo "</script>";
		exit();
	}
	echo "<a class='waves-effect waves-light btn-large mdl-color-text--white loginbutton' style='background-color:#fff !important; color:#757575 !important;' href='$authUrl'><span class='loginicon' style='background: url(\"core/images/integrations/button_icon_google.png\") no-repeat;'></span> Sign in with Google</a>";

?>