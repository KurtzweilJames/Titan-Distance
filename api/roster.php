<?php
include($_SERVER['DOCUMENT_ROOT'] . '/db.php');
header('Content-Type: application/json; charset=utf-8');

$s = $_REQUEST["s"];
if (!empty($_REQUEST["a"])) {
    $a = $_REQUEST["a"];
}
if ($s !== "all") {
    $sport = substr($s, 0, 2);
    $y = substr($s, 2);
}

if (!empty($_REQUEST["a"]) && $a == "true") {
    $a = true;
} else {
    $a = false;
}

if (empty($_REQUEST["strict"]) || $_REQUEST["strict"] == 1) {
    $strict = true;
} else {
    $strict = false;
}

$meets = [];

//Get Meets
$result = mysqli_query($con, "SELECT id,Name,Date FROM meets");
while ($row = mysqli_fetch_array($result)) {
    $meets[$row['id']] = $row['Name'] . " (" . date("n/j/y", strtotime($row['Date'])) . ")";
}

$profiles = [];

if (((!empty($sport) && $sport == "tf") || $s == "all") && $strict == true) {
    if ($s !== "all") {
        if ($a == true) {
            //These have the errors $result = mysqli_query($con, "SELECT MAX(id) AS id, profile, event, MAX(season) AS season, MAX(sr) AS sr, MAX(column1) AS column1, MAX(column2) AS column2, ... FROM overalltf WHERE season = '" . $s . "' AND profile IS NOT NULL AND sr = 1 GROUP BY profile, event");
            $result = mysqli_query($con, "SELECT * FROM overalltf WHERE season = '" . $s . "' AND profile IS NOT NULL AND sr = 1 GROUP BY profile,event");
        } else {
            $result = mysqli_query($con, "SELECT * FROM overalltf WHERE season = '" . $s . "' AND profile IS NOT NULL AND sr = 1 AND event IN ('3200m','1600m','800m','400m') GROUP BY profile,event");
        }
    } else {
        $result = mysqli_query($con, "SELECT * FROM overalltf WHERE profile IS NOT NULL AND pr = 1 AND event IN ('3200m','1600m','800m','400m') GROUP BY profile,event");
    }


    while ($row = mysqli_fetch_array($result)) {
        if (!isset($profiles[$row['profile']]["maxPoints"]) || $profiles[$row['profile']]["maxPoints"] < $row['points']) {
            $profiles[$row['profile']]["maxPoints"] = $row['points'];
        }

        if (!empty($row['relay'])) {
            $profiles[$row['profile']]["records"][$row['event']]["relay"] = true;
        } else {
            $profiles[$row['profile']]["records"][$row['event']]["relay"] = false;
        }

        $profiles[$row['profile']]["records"][$row['event']]["result"] = $row['result'];
        $profiles[$row['profile']]["records"][$row['event']]["meetID"] = $row['meet'];
        $profiles[$row['profile']]["records"][$row['event']]["meetName"] = $meets[$row['meet']];
        $profiles[$row['profile']]["records"][$row['event']]["resultID"] = $row['id'];
        $profiles[$row['profile']]["records"][$row['event']]["isPR"] = $row['pr'];
        $profiles[$row['profile']]["records"][$row['event']]["event"] = $row['event'];
        $profiles[$row['profile']]["records"][$row['event']]["points"] = $row['points'];
    }
}


if (((!empty($sport) && $sport == "xc") || $s == "all") && $strict == true) {
    if ($s !== "all") {
        $result = mysqli_query($con, "SELECT * FROM overallxc WHERE season = '" . $s . "' AND profile IS NOT NULL AND sr = 1 AND distance IN ('3mi','2mi','5k') GROUP BY profile,distance");
    } else {
        $result = mysqli_query($con, "SELECT * FROM overallxc WHERE profile IS NOT NULL AND pr = 1 AND distance IN ('3mi','2mi','5k') GROUP BY profile,distance");
    }

    while ($row = mysqli_fetch_array($result)) {
        $sortvalue = 1000;
        $min = explode(":", $row['time'])[0];
        $secs = explode(":", $row['time'])[1];
        $secs = explode(".", $secs)[0];
        $perMile = ($min * 60) + ($secs);
        if ($row['distance'] == "3mi") {
            $perMile /= 3;
        } else if ($row['distance'] == "2mi") {
            $perMile /= 2;
        } else if ($row['distance'] == "5k") {
            $perMile /= 3.1;
        }
        if ($perMile < $sortvalue) {
            $sortvalue = $perMile;
        }

        $profiles[$row['profile']]["records"][$row['distance']]["result"] = $row['time'];
        $profiles[$row['profile']]["records"][$row['distance']]["meetID"] = $row['meet'];
        $profiles[$row['profile']]["records"][$row['distance']]["meetName"] = $meets[$row['meet']];
        $profiles[$row['profile']]["records"][$row['distance']]["resultID"] = $row['id'];
        $profiles[$row['profile']]["records"][$row['distance']]["perMile"] = $perMile;
        $profiles[$row['profile']]["records"][$row['distance']]["isPR"] = $row['pr'];
        $profiles[$row['profile']]["records"][$row['distance']]["distance"] = $row['distance'];
        $profiles[$row['profile']]["sortValue"] = $sortvalue;
    }
}

if ($strict == true) {
    foreach ($profiles as $profile => $d) {
        $result = mysqli_query($con, "SELECT * FROM athletes WHERE profile = '" . $profile . "'");

        while ($row = mysqli_fetch_array($result)) {

            if (!isset($profiles[$profile]["records"]["3200m"]) && !isset($profiles[$profile]["records"]["1600m"]) && !isset($profiles[$profile]["records"]["800m"]) && !isset($profiles[$profile]["records"]["3mi"]) && !isset($profiles[$profile]["records"]["2mi"]) && !isset($profiles[$profile]["records"]["5k"])) {
                unset($profiles[$profile]);
                continue;
            }

            $class = $row['class'];
            if ($s == "all") {
                $grade = $row['class'];
            } else {
                if ($sport == "tf") {
                    if ($class == $y + 2000) {
                        $grade = "Sr.";
                    } else if ($class == ($y + 2001)) {
                        $grade = "Jr.";
                    } else if ($class == ($y + 2002)) {
                        $grade = "So.";
                    } else if ($class == ($y + 2003)) {
                        $grade = "Fr.";
                    }
                } else if ($sport == "xc") {
                    if ($class == $y + 2001) {
                        $grade = "Sr.";
                    } else if ($class == ($y + 2002)) {
                        $grade = "Jr.";
                    } else if ($class == ($y + 2003)) {
                        $grade = "So.";
                    } else if ($class == ($y + 2004)) {
                        $grade = "Fr.";
                    }
                } else {
                    $grade = $row['class'];
                }
            }

            $profiles[$profile]["profile"] = $profile;
            $profiles[$profile]["class"] = $row['class'];
            $profiles[$profile]["grade"] = $grade;
            $profiles[$row['profile']]["name"] = $row['name'];
            $profiles[$row['profile']]["college"] = $row['college'];
            $profiles[$row['profile']]["alt"] = $row['alt'];
            $profiles[$row['profile']]["athnet"] = $row['athnet'];
            // $profiles[$row['profile']]["name"]["first"] = $row['first'];
            // $profiles[$row['profile']]["name"]["last"] = $row['last'];

            $file = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/athletes/" . $profile . ".jpg";
            if (!file_exists($file)) {
                $profiles[$row['profile']]["image"] = "/assets/images/athletes/blank.jpg";
            } else {
                $profiles[$row['profile']]["image"] = "/assets/images/athletes/" . $profile . ".jpg";
            }

            $profiles[$row['profile']]["captain"] = strpos($row['captain'], $s) !== false;
        }
    }
}

if ($strict == false && $s !== "c") {
    if (strlen($y) > 2) {
        $year = $y;
    } else {
        $year = "2000" + $y;
    }
    if ($sport == "tf") {
        $begyear = $year;
        $endyear = $year + 3;
    } else if ($sport == "xc") {
        $begyear = $year + 1;
        $endyear = $year + 4;
    }
    $result = mysqli_query($con, "SELECT * FROM athletes WHERE class >= '" . $begyear . "' AND class <= '" . $endyear . "'");

    while ($row = mysqli_fetch_array($result)) {
        $class = $row['class'];
        if ($sport == "tf") {
            if ($class == $y + 2000) {
                $grade = 12;
            } else if ($class == ($y + 2001)) {
                $grade = 11;
            } else if ($class == ($y + 2002)) {
                $grade = 10;
            } else if ($class == ($y + 2003)) {
                $grade = 9;
            }
        } else if ($sport == "xc") {
            if ($class == $y + 2001) {
                $grade = 12;
            } else if ($class == ($y + 2002)) {
                $grade = 11;
            } else if ($class == ($y + 2003)) {
                $grade = 10;
            } else if ($class == ($y + 2004)) {
                $grade = 9;
            }
        } else {
            $grade = null;
        }
        $profiles[] = ["profile" => $row['profile'], "name" => $row['name'], "class" => $row['class'], "grade" => $grade];
    }
}


if ($s == "all" && $strict == true) {
    usort($profiles, function ($x, $y) {
        return $x['class'] <=> $y['class'];
    });
} else if ($strict == true && ($s == "all" || $sport == "xc")) {
    usort($profiles, function ($x, $y) {
        return $x['sortValue'] <=> $y['sortValue'];
    });
} else if ($sport == "tf") {
    usort($profiles, function ($x, $y) {
        return ($y['maxPoints'] ?? 0) <=> ($x['maxPoints'] ?? 0);
    });
}

echo json_encode($profiles, JSON_PRETTY_PRINT);
