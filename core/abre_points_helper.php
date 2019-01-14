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

/**
 * Updates the number of points that the source (identified by the $app-$item-$id triplet) has
 * awarded to the user (idenfitied by $user-$email pair). This method will update the current record
 * if one exists, or add a new one otherwise.
 * Returns true on success, false on failure
 */
function updatePoints($user, $siteID, $app, $item, $id, $points) {
  require('abre_dbconnect.php');
  try {
    $getSql = "SELECT id FROM abre_points
               WHERE site_id = $siteID AND User = '$user' AND source_app = '$app' AND source_item = '$item' AND source_id = $id";
    $getResult = $db->query($getSql);
    $numRows = $getResult->num_rows;

    if ($numRows == 0) {
      $insertSql = "INSERT INTO abre_points (site_id, user, source_app, source_item, source_id, points)
                    VALUES (?, ?, ?, ?, ?, ?)";
      $insertStmt = $db->stmt_init();
      $insertStmt->prepare($insertSql);
      $insertStmt->bind_param("isssii", $siteID, $user, $app, $item, $id, $points);
      $insertStmt->execute();
      $insertStmt->close();
    } else {
      $row = $getResult->fetch_assoc();
      $pointsId = $row["id"];

      $updateSql = "UPDATE abre_points
                    SET points = ?
                    WHERE id = ?";
      $updateStmt = $db->stmt_init();
      $updateStmt->prepare($updateSql);
      $updateStmt->bind_param("ii", $points, $pointsId);
      $updateStmt->execute();
      $updateStmt->close();

      return true;
    }
  } catch (Exception $exception) {
    error_log("failed to add points: $exception");
    return false;
  } finally {
    $db->close();
  }
}

/**
 * Returns an array of point sources where each item has an "app", "title", and "points" value. 
 * Returns false if the sql statement fails. An empty array is a valid return (no points awarded). 
 */
function getPointBreakDownByUser($user, $siteID) {
  require('abre_dbconnect.php');
  try {
    $sql = "SELECT ap.source_app, ap.source_item, ap.points, ap.source_id, 
                   cu.Title AS learn_course_title, le.Title AS learn_event_title,
                   sp.post_title AS stream_post_title
            FROM abre_points ap
            LEFT JOIN curriculum_course cu ON ap.source_app = 'Learn' AND ap.source_item = 'Course' AND ap.source_id = cu.ID
            LEFT JOIN learn_event le ON ap.source_app = 'Learn' AND ap.source_item = 'Event' AND ap.source_id = le.ID
            LEFT JOIN stream_posts sp ON ap.source_app = 'Stream' AND ap.source_item = 'View Announcement' AND ap.source_id = sp.id
            WHERE ap.user = ? AND ap.site_id = ?";

    $stmt = $db->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param("si", $_SESSION['useremail'], $_SESSION['siteID']);
    $stmt->execute();
    $stmt->bind_result($app, $item, $points, $id, $learnCourseTitle, $learnEventTitle, $streamPostTitle);

    $results = [];
    while ($stmt->fetch()) {
      if ($app == "Learn" && $item == "Course") {
        $results[] = [
          "app" => "Learn", 
          "item" => "Course",
          "title" => $learnCourseTitle, 
          "points" => $points
        ];
      } elseif ($app == "Learn" && $item == "Event") {
        $results[] = [
          "app" => "Learn",
          "item" => "Event",
          "title" => $learnEventTitle,
          "points" => $points
        ];
      } elseif ($app == "Stream" && $item == "View Announcement") {
        $results[] = [
          "app" => "Stream",
          "item" => "View Announcement",
          "title" => $streamPostTitle,
          "points" => $points
        ];
      }
    }

    return $results;
  } catch (Exception $exception) {
    error_log("failed to get points: $exception");
    return false;
  } finally {
    $db->close();
  }
}

/**
 * Returns the total number of awarded points for the user
 * Returns false if the sql statement fails. 0 means no points have been awarded
 */
function getTotalPointsByUser($user, $siteID) {
  require('abre_dbconnect.php');
  try {
    $sql = "SELECT SUM(points) FROM abre_points
            WHERE user = ? AND site_id = ?";

    $stmt = $db->stmt_init();
    $stmt->prepare($sql);
    $stmt->bind_param("si", $_SESSION['useremail'], $_SESSION['siteID']);
    $stmt->execute();
    $stmt->bind_result($points);
    $stmt->fetch(); // only 1 record

    return $points;
  } catch (Exception $exception) {
    error_log("failed to get points: $exception");
    return false;
  } finally {
    $db->close();
  }
}
?>