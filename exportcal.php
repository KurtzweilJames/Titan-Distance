<?php

$include = explode(",", htmlspecialchars($_GET["include"]));

// require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/calendar/zapcallib.php");

include("db.php");

header('Content-type:text/calendar');

echo "BEGIN:VCALENDAR
VERSION:2.0
PRODID://TITANDISTANCE//20
TZID:America/Chicago\n";

// $icalobj = new ZCiCal();

if (in_array('practices', $include)) {
      $result = mysqli_query($con, "SELECT date,practicetime,practicename FROM workouts WHERE practicename IS NOT NULL");
      while ($row = mysqli_fetch_array($result)) {
            $timestamp = strtotime($row['date'] . $row['practicetime']);
            $title = $row['practicename'];
            $description = "View the workout at https://titandistance.com/workouts";

            generateEvent($title, $timestamp, $timestamp, $timestamp, $description, null);
      }
}

if (in_array('schedule', $include)) {
      $result = mysqli_query($con, "SELECT Date,Time,Name,id,Location,Day2Time,Day2Levels,Levels,Series FROM meets WHERE NOT(`Status` <=> 'C')");
      while ($row = mysqli_fetch_array($result)) {
            if (empty($row['Day2Time'])) {
                  $title = $row['Name'];
            } else {
                  $title = $row['Name'] . " (" . $row['Levels'] . ")";
            }

            if (empty($row['Series'])) {
                  $url = "https://titandistance.com/meet/" . $row['id'];
            } else {
                  $url = "https://titandistance.com/meet/" . $row['Series'] . "/" . $d = date("Y", strtotime($row['Date']));
            }

            $timestamp = strtotime($row['Date'] . $row['Time']);
            $description = "For Meet Information, please visit: " . $url;
            $location = $row['Location'];
            $endtime = $timestamp + strtotime("+3 hours");

            if (!str_contains($title, "Unknown")) {
                  generateEvent($title, $timestamp, $timestamp, $row['id'], $description, $location);
            }

            // if (!empty($row['Day2Time'])) {
            //       $start = $row['Day2Time'];
            //       $finish = date('Y-m-d H:i:s', strtotime('+4 hour', strtotime($start)));
            //       $title = $row['Name'] . " (" . $row['Day2Levels'] . ")";
            //       ZCTimeZoneHelper::getTZNode(substr($start, 0, 4), substr($finish, 0, 4), $tzid, $icalobj->curnode);
            //       $eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);
            //       $eventobj->addNode(new ZCiCalDataNode("SUMMARY:" . $title));
            //       $eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($start)));
            //       $eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($finish)));
            //       $uid = $row['id'] . "-2@schedule.cal.titandistance.com";
            //       $eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));
            //       $eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));
            //       $eventobj->addNode(new ZCiCalDataNode("LOCATION:" . ZCiCal::formatContent($location)));
            //       $eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent("For Meet Information, please visit: " . $url)));
            // }
      }
}



if (in_array('events', $include)) {
      $result = mysqli_query($con, "SELECT * FROM events WHERE title IS NOT NULL");
      while ($row = mysqli_fetch_array($result)) {
            if ($row['allday'] == 1) {
                  $timestamp = strtotime($row['start']);
                  $finish = $timestamp;
            } else {
                  $timestamp = strtotime($row['start']);
                  if (!empty($row['end'])) {
                        $finish = strtotime($row['end']);
                  } else {
                        $finish = strtotime('+2 hour', strtotime($row['start']));
                  }
            }
            $title = $row['title'];

            if (empty($row['description'])) {
                  $description = "Learn more about this event on https://titandistance.com/";
            } else {
                  $description = $row['description'];
            }

            generateEvent($title, $timestamp, $finish, $row['id'] . "event", $description, null);
      }
}

echo "END:VCALENDAR";

function generateEvent($title, $start, $end, $uid, $description, $location)
{
      echo "BEGIN:VEVENT\n";
      echo "SUMMARY:" . $title . "\n";
      echo "DTSTAMP:" . gmdate("Ymd\THis", $start) . "\n";
      echo "DTSTART:" . gmdate("Ymd\THis", $start) . "\n";
      echo "DTEND:" . gmdate("Ymd\THis", $end) . "\n";
      if (!empty($description)) {
            echo "DESCRIPTION:" . $description . "\n";
      }
      if (!empty($location)) {
            echo "LOCATION:" . $location . "\n";
      }
      echo "UID:" . $uid . "@schedule.cal.titandistance.com\n";
      echo "END:VEVENT\n";
}
