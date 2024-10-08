<?php
include($_SERVER['DOCUMENT_ROOT'].'/db.php');
$id = isset($_GET["id"]) ? htmlspecialchars($_GET["id"]) : null;
$series = isset($_GET["series"]) ? htmlspecialchars($_GET["series"]) : null;
$year = isset($_GET["year"]) ? htmlspecialchars($_GET["year"]) : null;

mysqli_set_charset($con, 'utf8');

// Set the header to indicate JSON response
header('Content-Type: application/json');

$query = "";
if ($id !== null) {
    $query = "SELECT * FROM meets WHERE id='" . $id . "'";
} elseif ($series !== null && $year !== null) {
    $query = "SELECT * FROM meets WHERE series='" . $series . "' AND YEAR(Date)='" . $year . "'"; // Assuming date_column is the name of your date field
} else {
    // Handle case where neither id nor series and year are provided
    echo json_encode(["error" => "No valid input provided. Please provide either 'id' or both 'series' and 'year'."], JSON_PRETTY_PRINT);
    exit;
}

$result = mysqli_query($con, $query);

$data = [];
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $id = (int)$row["id"];
    $data["id"] = $id;
    $data["meetData"]["Name"] = $row["Name"];
    $unformatteddate = $row['Date'];
    $data["meetData"]["Date"] = $row["Date"];
    $data["meetData"]["Location"] = $row["Location"];
    $series = $row['Series'];
    $data["meetData"]["Series"] = !empty($row["Series"]) ? $row["Series"] : false;
    $sport = $row['Sport'];
    $data["meetData"]["Sport"] = $row["Sport"];
    $data["meetData"]["Indoor"] = (bool)$row["Indoor"];
    $data["meetData"]["Levels"] = $row["Levels"];
    $data["meetSchedule"] = json_decode($row["Schedule"]);
}

$query = "SELECT * FROM photos WHERE meet='" . $id . "'";
$result = mysqli_query($con, $query);
$data["meetPhotos"] = null;
$photos = [];
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $data["meetPhotos"] = $row;
}

if ($sport == "in" || $sport == "out") {
    $query = "SELECT * FROM overalltf WHERE meet='" . $id . "'";
} else if ($sport == "xc" || $sport == "rr") {
    $query = "SELECT * FROM overallxc WHERE meet='" . $id . "'";
}
$result = mysqli_query($con, $query);
$meetResults = [];
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $meetResults[] = $row;
}
$data["meetResults"] = $meetResults;

if (!empty($series)) {
    $result = mysqli_query($con, "SELECT Date FROM meets WHERE Date < '" . $unformatteddate . "' AND Series = '" . $series . "'  ORDER BY Date DESC LIMIT 1");
    while ($row = mysqli_fetch_array($result)) {
        $data["seriesInfo"]["previous"] = date("Y", strtotime($row['Date']));
    }
    $result = mysqli_query($con, "SELECT Date FROM meets WHERE Date > '" . $unformatteddate . "' AND Series = '" . $series . "'  ORDER BY Date ASC LIMIT 1");
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $data["seriesInfo"]["next"] = date("Y", strtotime($row['Date']));
        }
    } else {
        $data["seriesInfo"]["next"] = false;
    }
}

echo json_encode($data, JSON_PRETTY_PRINT);
?>