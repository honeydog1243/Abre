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

    function sess_open($sess_path, $sess_name) {

        return true;
    }

    function sess_close() {

        return true;
    }

    function sess_read($sess_id) {

        require('abre_dbconnect.php');

        $result = mysqli_query($db, "SELECT Data FROM sessions WHERE SessionID = '$sess_id';");
        $CurrentTime = time();

        if (!mysqli_num_rows($result)) {

            $stmt = $db->stmt_init();
            $sql = "INSERT INTO sessions (SessionID, DateTouched) VALUES (?, ?);";
            $stmt->prepare($sql);
            $stmt->bind_param("si", $sess_id, $CurrentTime);
            $stmt->execute();
            $stmt->close();

            $db->close();
            return '';
        } else {

            extract(mysqli_fetch_array($result), EXTR_PREFIX_ALL, 'sess');
            $stmt = $db->stmt_init();
            $sql = "UPDATE sessions SET DateTouched = ? WHERE SessionID = ?;";
            $stmt->prepare($sql);
            $stmt->bind_param("is", $CurrentTime, $sess_id);
            $stmt->execute();
            $stmt->close();

            $db->close();

            if (!is_string($sess_Data)) return '';

            return $sess_Data;
        }
    }

    function sess_write($sess_id, $data) {

        require('abre_dbconnect.php');

        $CurrentTime = time();

        $stmt = $db->stmt_init();
        $sql = "UPDATE sessions SET Data = ?, DateTouched = ? WHERE SessionID = ?;";
        $stmt->prepare($sql);
        $stmt->bind_param("sis", $data, $CurrentTime, $sess_id);
        $stmt->execute();
        $stmt->close();
        $db->close();

        return true;
    }

    function sess_destroy($sess_id) {

        require('abre_dbconnect.php');

        mysqli_query($db, "DELETE FROM sessions WHERE SessionID = '$sess_id';");
        $db->close();

        return true;
    }

    function sess_gc($sess_maxlifetime) {

        require('abre_dbconnect.php');

        $CurrentTime = time();
        mysqli_query($db, "DELETE FROM sessions WHERE DateTouched + $sess_maxlifetime < $CurrentTime;");
        $db->close();

        return true;
    }

?>