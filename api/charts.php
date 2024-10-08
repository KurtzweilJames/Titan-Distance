<?php
include($_SERVER['DOCUMENT_ROOT'] . '/db.php');
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

//Get Meets
$result = mysqli_query($con, "SELECT id,Name,Date FROM meets");
while ($row = mysqli_fetch_array($result)) {
    $meets[$row['id']] = $row['Name'] . " (" . date("n/j/y", strtotime($row['Date'])) . ")";
}

if (!empty($_GET["event"])) {
    if (htmlspecialchars($_GET["event"]) == "3mi" || htmlspecialchars($_GET["event"]) == "2mi" || htmlspecialchars($_GET["event"]) == "5k") {
        $result = mysqli_query($con, "SELECT * FROM overallxc WHERE profile='" . htmlspecialchars($_GET["profile"]) . "' AND distance = '" . htmlspecialchars($_GET["event"]) . "' AND time != 'NT' ORDER BY date ASC");

        $data = [];

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $single = [];
            $single["meetID"] = $row['meet'];
            $single["meetName"] = $meets[$row['meet']];
            $min = explode(":", $row['time'])[0];
            $secs = explode(":", $row['time'])[1];
            $secs = explode(".", $secs)[0];
            $single["secs"] = ($min * 60) + ($secs);
            $single["result"] = $row['time'];
            $single["date"] = $row['date'];

            $data[] = $single;
        }
    } else {
        $result = mysqli_query($con, "SELECT * FROM overalltf WHERE profile='" . htmlspecialchars($_GET["profile"]) . "' AND event = '" . htmlspecialchars($_GET["event"]) . "' AND (result != 'NT' AND result != 'FOUL' AND result != 'DNS' AND result != 'DNF') ORDER BY date ASC");

        $data = [];

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $single = [];
            $single["meetID"] = $row['meet'];
            $single["meetName"] = $meets[$row['meet']];
            if ($row['event'] == "LJ" || $row['event'] == "HJ" || $row['event'] == "TJ" || $row['event'] == "PV" || $row['event'] == "SP" || $row['event'] == "DS") {
                $single['secs'] = floatval(str_replace("m", "", $row['result']));
            } else {
                $min = explode(":", $row['result'])[0];
                $secs = explode(":", $row['result'])[1];
                if (!empty(explode(".", $secs)[1])) {
                    $ms = explode(".", $secs)[1];
                } else {
                    $ms = 0;
                }
                $secs = explode(".", $secs)[0];
                $single['secs'] = floatval(strval(($min * 60) + ($secs)) . "." . $ms);
            }
            $single["result"] = $row['result'];
            $single["date"] = $row['date'];

            $data[] = $single;
        }
    }
} else {
    $result = mysqli_query($con, "SELECT event, count(*) as c FROM overalltf WHERE profile='" . htmlspecialchars($_GET["profile"]) . "' AND result != 'NT' GROUP BY event");

    $data = [];

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $single = [];
        $single["event"] = $row['event'];
        $single["eventDisplay"] = $trackevents[$row['event']];
        $single["count"] = $row['c'];
        $data[] = $single;
    }

    $resultxc = mysqli_query($con, "SELECT distance, count(*) as c FROM overallxc WHERE profile='" . htmlspecialchars($_GET["profile"]) . "' AND time != 'NT' AND (distance = '3mi' OR distance = '2mi' OR distance='5k') GROUP BY distance");

    while ($row = mysqli_fetch_array($resultxc, MYSQLI_ASSOC)) {
        $single = [];
        $single["event"] = $row['distance'];
        $single["eventDisplay"] = str_replace("mi", " Mile", $row['distance']);
        $single["count"] = $row['c'];
        $data[] = $single;
    }
}

echo json_encode(array_values($data), JSON_PRETTY_PRINT);
