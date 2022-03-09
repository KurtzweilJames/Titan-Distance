<?php

$include = explode(",", htmlspecialchars($_GET["include"]));

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/calendar/zapcallib.php");

include("db.php");

header('Content-type:text/calendar');

$icalobj = new ZCiCal();

if (in_array('practices', $include)) {
      $result = mysqli_query($con, "SELECT date,practicetime,practicename FROM workouts WHERE practicename IS NOT NULL");
      while ($row = mysqli_fetch_array($result)) {
            $start = $row['date'] . " " . $row['practicetime'];
            $finish = date('Y-m-d H:i:s', strtotime($row['date'] . $row['practicetime']));
            $finish = date('Y-m-d H:i:s', strtotime('+2 hour', strtotime($finish)));
            $tzid = "America/Chicago";
            $url = "https://titandistance.com/workouts/";
            $title = $row['practicename'];
            ZCTimeZoneHelper::getTZNode(substr($start, 0, 4), substr($finish, 0, 4), $tzid, $icalobj->curnode);
            $eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);
            $eventobj->addNode(new ZCiCalDataNode("SUMMARY:" . $title));
            $eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($start)));
            $eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($finish)));
            $uid = date('Y-m-d-H-i-s', strtotime($finish)) . "@practices.cal.titandistance.com";
            $eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));
            $eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));
            $eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent("View the workout at https://titandistance.com/workouts")));
      }
}

if (in_array('schedule', $include)) {
      $result = mysqli_query($con, "SELECT Date,Time,Name,id,Location,Day2Time,Day2Levels,Levels,Series FROM meets");
      while ($row = mysqli_fetch_array($result)) {
            $location = $row['Location'];
            $start = $row['Date'] . " " . $row['Time'];
            $finish = date('Y-m-d H:i:s', strtotime($row['Date'] . $row['Time']));
            $finish = date('Y-m-d H:i:s', strtotime('+4 hour', strtotime($finish)));
            $tzid = "America/Chicago";
            if (empty($row['Day2Time'])) {
                  $title = $row['Name'];
            } else {
                  $title = $row['Name'] . " (" . $row['Levels'] . ")";
            }
            ZCTimeZoneHelper::getTZNode(substr($start, 0, 4), substr($finish, 0, 4), $tzid, $icalobj->curnode);
            $eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);
            $eventobj->addNode(new ZCiCalDataNode("SUMMARY:" . $title));
            $eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($start)));
            $eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($finish)));
            $uid = $row['id'] . "@schedule.cal.titandistance.com";
            $eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));
            $eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));
            $eventobj->addNode(new ZCiCalDataNode("LOCATION:" . ZCiCal::formatContent($location)));

            if (empty($row['Series'])) {
                  $url = "https://titandistance.com/meet/" . $row['id'];
            } else {
                  $url = "https://titandistance.com/meet/" . $row['Series'] . "/" . $d = date("Y", strtotime($row['Date']));
            }

            $eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent("For Meet Information, please visit: " . $url)));

            if (!empty($row['Day2Time'])) {
                  $start = $row['Day2Time'];
                  $finish = date('Y-m-d H:i:s', strtotime('+4 hour', strtotime($start)));
                  $title = $row['Name'] . " (" . $row['Day2Levels'] . ")";
                  ZCTimeZoneHelper::getTZNode(substr($start, 0, 4), substr($finish, 0, 4), $tzid, $icalobj->curnode);
                  $eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);
                  $eventobj->addNode(new ZCiCalDataNode("SUMMARY:" . $title));
                  $eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($start)));
                  $eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($finish)));
                  $uid = $row['id'] . "-2@schedule.cal.titandistance.com";
                  $eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));
                  $eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));
                  $eventobj->addNode(new ZCiCalDataNode("LOCATION:" . ZCiCal::formatContent($location)));
                  $eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent("For Meet Information, please visit: " . $url)));
            }
      }
}



if (in_array('events', $include)) {
      $result = mysqli_query($con, "SELECT * FROM events WHERE title IS NOT NULL");
      while ($row = mysqli_fetch_array($result)) {
            if ($row['allday'] == 1) {
                  $start = date('Y-m-d', strtotime($row['start']));
                  $finish = $start;
            } else {
                  $start = $row['start'];
                  if (!empty($row['end'])) {
                        $finish = date('Y-m-d H:i:s', strtotime($row['end']));
                  } else {
                        $finish = date('Y-m-d H:i:s', strtotime($row['start']));
                        $finish = date('Y-m-d H:i:s', strtotime('+2 hour', strtotime($start)));
                  }
            }
            $tzid = "America/Chicago";
            $url = "https://titandistance.com/";
            $title = $row['title'];

            // Add timezone data
            ZCTimeZoneHelper::getTZNode(substr($start, 0, 4), substr($finish, 0, 4), $tzid, $icalobj->curnode);


            // create the event within the ical object
            $eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);

            // add title
            $eventobj->addNode(new ZCiCalDataNode("SUMMARY:" . $title));

            // add start date
            $eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($start)));

            // add end date
            $eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($finish)));

            // UID is a required item in VEVENT, create unique string for this event
            // Adding your domain to the end is a good way of creating uniqueness
            $uid = $row['id'] . "@events.cal.titandistance.com";
            $eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));

            // DTSTAMP is a required item in VEVENT
            $eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));

            if (empty($row['description'])) {
                  $eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent("Learn more about this event on https://titandistance.com/")));
            } else {
                  $eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent($row['description'])));
            }
      }
}

echo $icalobj->export();
