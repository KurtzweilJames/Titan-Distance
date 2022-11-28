<?php
include($_SERVER['DOCUMENT_ROOT'] . '/db.php');
if (isset($_GET["start"]) && isset($_GET["end"])) {
  $start = htmlspecialchars($_GET["start"]);
  $end = htmlspecialchars($_GET["end"]);

  $start = date('Y-m-d', strtotime($start));
  $end = date('Y-m-d', strtotime($end));
}

header('Content-type:application/json');
if (isset($_GET["start"]) && isset($_GET["end"])) {
  $result = mysqli_query($con, "SELECT * FROM meets WHERE Date < '" . $end . "' AND Date > '" . $start . "' AND NOT(`Status` <=> 'C') ORDER BY Date ASC");
} else {
  $result = mysqli_query($con, "SELECT * FROM meets WHERE Season = '" . $_GET['season'] . "' ORDER BY Date ASC");
}
if (mysqli_num_rows($result) == 0) {
  echo "{}";
  exit();
}

$todaydate = date('Y-m-d');

while ($row = $result->fetch_assoc()) {
  $start = $row['Time'];
  if (empty($row['Day2Time'])) {
    $title = $row['Name'];
  } else {
    $title = $row['Name'] . "(" . $row['Levels'] . ")";
  }

  $year = date("Y", strtotime($row['Date']));

  if (empty($row['Series'])) {
    $url = "https://titandistance.com/meet/" . $row['id'];
  } else {
    $url = "https://titandistance.com/meet/" . $row["Series"] . "/" . $d = date("Y", strtotime($row['Date']));
  }

  $date = new DateTime($row['Date'] . $row['Time']);
  $start = $date->format('c');
  $location = $row['Location'];
  $dow = date("D", strtotime($row['Date']));
  if ($row['Season'] == "Community") {
    $d = date("n/j/y", strtotime($row['Date']));
  } else {
    $d = date("n/j", strtotime($row['Date']));
  }
  $countdown = round((strtotime($row["Date"]) - strtotime($todaydate)) / 86400);

  $events[] = array("id" => $row['id'], "title" => $title, "start" => $start, "url" => $url, "location" => $location, "levels" => $row['Levels'], "opponents" => $row['Opponents'], "official" => $row['Official'], "badge" => $row['Badge'], "message" => $row['Message'], "notes" => $row['Notes'], "dow" => $dow, "md" => $d, "status" => $row['Status'], "countdown" => $countdown);

  //Day 2
  if (!empty($row['Day2Time'])) {
    $start = $row['Day2Time'];
    $title = $row['Name'] . " (" . $row['Day2Levels'] . ")";
    $url = "https://titandistance.com/meet/" . $row['id'];
    $start = date("c", strtotime($row['Day2Time']));
    $dow = date("D", strtotime($row['Day2Time']));
    $d = date("n/j", strtotime($row['Day2Time']));

    $events[] = array("title" => $title, "start" => $start, "url" => $url, "location" => $location, "levels" => $row['Day2Levels'], "opponents" => $row['Opponents'], "official" => $row['Official'], "badge" => $row['Badge'], "message" => $row['Message'], "notes" => $row['Notes'], "dow" => $dow, "md" => $d);
  }
}

echo json_encode($events, JSON_PRETTY_PRINT);
if (empty($events)) {
  echo "{}";
}
