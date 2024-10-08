<?php
include($_SERVER['DOCUMENT_ROOT'] . '/db.php');
$q = htmlspecialchars($_GET["q"]);
if (!empty($_GET['from'])) {
    $from = explode(",", htmlspecialchars($_GET["from"]));
} else {
    // $from = ["meets", "athletes", "news"];
    $from = [];
}

$data = [];

$q = strtolower($q);
// $q = str_replace($q,"Invit","Hello world!");

if (in_array("meets", $from)) {
    $result = mysqli_query($con, "SELECT * from meets WHERE `Name` LIKE '%" . $q . "%' OR `Series` LIKE '%" . $q . "%' OR `Location` LIKE '%" . $q . "%' ORDER BY Date DESC LIMIT 50");

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if (empty($row['Series'])) {
            $url = "/meet/" . $row['id'];
        } else {
            $url = "/meet/" . $row['Series'] . "/" . $d = date("Y", strtotime($row['Date']));
        }
        $data[] = ["title" => $row['Name'] . " (" . date("n/j/y", strtotime($row['Date'])) . ")", "url" => $url, "icon" => "bi-flag-fill"];
    }
}

if (in_array("athletes", $from)) {
    $result = mysqli_query($con, "SELECT * from athletes WHERE `name` LIKE '%" . $q . "%' OR `alt` LIKE '%" . $q . "%' OR `college` LIKE '%" . $q . "%' ORDER BY class DESC LIMIT 50");

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $url = "/athlete/" . $row['profile'];

        $data[] = ["title" => $row['name'] . " (" . $row['class'] . ")", "url" => $url, "icon" => "bi-person-fill"];
    }
}

if (in_array("news", $from)) {
    $result = mysqli_query($con, "SELECT * from news WHERE (`title` LIKE '%" . $q . "%' OR `slug` LIKE '%" . $q . "%' OR `content` LIKE '%" . $q . "%') AND `public` = 1 ORDER BY date DESC LIMIT 50");

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $url = "/news/" . $row['slug'];

        $data[] = ["title" => $row['title'], "url" => $url, "icon" => "bi-newspaper"];
    }
}

if (in_array("misc", $from)) {
    $result = mysqli_query($con, "SELECT * from links WHERE (`name` LIKE '%" . $q . "%' OR `key` LIKE '%" . $q . "%') LIMIT 50");

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        if (empty($row['icon'])) {
            $icon = "bi-link-45deg";
        } else {
            $icon = $row['icon'];
        }

        $data[] = ["title" => $row['name'], "url" => $row['link'], "icon" => $icon];
    }
}

// if(count($data) == 0) {
//     $data[] = ["title" => "Results", "url" => "/results", "icon" => "bi bi-file-earmark-post"];
// }

echo json_encode($data, JSON_PRETTY_PRINT);
