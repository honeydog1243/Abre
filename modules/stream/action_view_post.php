<?php
/*
* Copyright (C) 2016-2018 Abre.io LLC
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

require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
require_once(dirname(__FILE__) . '/../../core/abre_event_hub.php');

$id = $_POST["id"];

$eventHub->fireEvent("streams.view-announcement", ["id" => $id]);
?>