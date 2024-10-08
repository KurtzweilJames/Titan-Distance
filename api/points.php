<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /admin/login.php");
    exit;
}

include($_SERVER['DOCUMENT_ROOT'] . '/db.php');

// $result = htmlspecialchars($_GET["result"]);
// $event = htmlspecialchars($_GET["event"]);
// $location = htmlspecialchars($_GET["location"]);
$pointsMap = [];

function timeToSeconds($time)
{
    // Split the time into minutes, seconds, and milliseconds
    list($minutes, $seconds) = explode(':', $time);

    // Extract seconds and milliseconds
    if (strpos($seconds, '.') !== false) {
        list($sec, $milli) = explode('.', $seconds);
    } else {
        $sec = $seconds;
        $milli = 0;
    }

    // Convert minutes and seconds to seconds
    $divisor = pow(10, strlen($milli)); // Calculate the divisor based on the length of milli
    $totalSeconds = ($minutes * 60) + $sec + ($milli / $divisor);

    //echo $totalSeconds;
    return $totalSeconds;
}

function secondsToTime($totalSeconds)
{
    // Get the integer part of the total seconds for minutes and seconds calculation
    $minutes = floor($totalSeconds / 60);
    $seconds = floor($totalSeconds % 60);

    // Get the fractional part of the total seconds for milliseconds calculation
    $fraction = $totalSeconds - floor($totalSeconds);
    
    // Determine the number of decimal places in the fractional part
    $fractionStr = rtrim(substr(strrchr($totalSeconds, '.'), 1), '0');
    $numDecimals = strlen($fractionStr);

    // Calculate the milliseconds based on the number of decimals
    $milliseconds = round($fraction * pow(10, $numDecimals));
    
    // Format the time as mm:ss.ms with dynamic decimal places
    $time = sprintf('%01d:%02d.%0' . $numDecimals . 'd', $minutes, $seconds, $milliseconds);

    return $time;
}

function handConversion($time) {
    if (!in_array($time, ["DNS", "DNF", "DQ", "NT", "NH", "ND", "NM", "SCR", "FOUL", "OTHR", "FS", "?"])) {
        $seconds = timeToSeconds($time);
        $seconds = $seconds + 0.24;
        return secondsToTime($seconds);
    }
}

function readPoints($event, $location)
{
    global $pointsMap;

    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/includes/scoring_tables/' . $location . " " . $event . '.csv';

    // Check if the file exists
    if (file_exists($filePath)) {
        // Only read the file and update $pointsMap if it hasn't been read before
        if (!isset($pointsMap[$event])) {
            $pointsMap[$event] = array_map('str_getcsv', file($filePath));
        }
        return $pointsMap[$event];
    } else {
        // File doesn't exist, return null
        return null;
    }
}

function getPoints($event, $location, $result)
{
    if (!in_array($result, ["DNS", "DNF", "DQ", "NT", "NH", "ND", "NM", "SCR", "FOUL", "OTHR", "FS", "?"])) {
        if ($event == "1600m") {
            $seconds = timeToSeconds($result) * (1609.344 / 1600) ** 1.06;
            $event = "Mile";
        } else if ($event == "3200m") {
            $seconds = timeToSeconds($result) * (3218.688 / 3200) ** 1.06;
            $event = "2 Miles";
        } else if (in_array($event, ["LJ", "TJ", "HJ", "PV", "SP", "DT"])) {
            $seconds = rtrim($result, "m");
        } else if ($event == "300mIH") {
            $event = "400mH";
            $seconds = timeToSeconds($result) * (400 / 300) ** 1.06;
        } else if ($event == "110mHH") {
            $event = "110mH";
            $seconds = timeToSeconds($result);
        } else if ($event == "55mHH") {
            $event = "55mH";
            $seconds = timeToSeconds($result);
        } else if ($event == "1mi") {
            $event = "Mile";
            $seconds = timeToSeconds($result);
        } else if ($event == "50y") {
            //$seconds = timeToSeconds($result) * (0.9144 / 50) ** 1.06;
            //$event = "50m";
        } else {
            $seconds = timeToSeconds($result);
        }
        // else if (in_array($event, ["100m", "200m", "400m", "800m", "4x100m", "4x200m", "4x400m", "1000m", "55m"])) {
        //     $seconds = timeToSeconds($result);
        // }

        $data = readPoints($event, $location);

        if (isset($data) && isset($seconds)) {
            // Iterate through the data
            foreach ($data as $row) {
                if ($row[0] == "performance") {
                    continue;
                }
                if ($seconds < floatval($row[0])) {
                    return floatVal($row[1]);
                }
            }

            return 0;
        } else {
            return null;
        }
    }
}

$result = mysqli_query($con, "SELECT * FROM overalltf WHERE points IS NULL OR (method = 'h' AND conversion IS NULL)");
while ($row = mysqli_fetch_array($result)) {
    if ($row['indoor'] == 1) {
        $location = "Indoor";
    } else {
        $location = "Outdoor";
    }

    if ($row['method'] == 'h') {
        $resultEntry = handConversion($row['result']);
        $sql = "UPDATE overalltf SET conversion = '" . $resultEntry . "' WHERE id='" . $row['id'] . "'";
        if ($con->query($sql) != TRUE) {    
            echo "Error updating conversion: " . $con->error;
        }
    } else {
        $resultEntry = $row['result'];
    }

    $points = getPoints($row['event'], $location, $resultEntry);

    if ($points !== null) {
        // echo $row['id'] . "(" . $row['event'] . "," . $row['result'] . ")=>" . $points . "\n";
        $sql = "UPDATE overalltf SET points = '" . $points . "' WHERE id='" . $row['id'] . "'";
        if ($con->query($sql) === TRUE) {
            echo "Points updated successfully";
        } else {
            echo "Error updating points: " . $con->error;
        }
    }
}
