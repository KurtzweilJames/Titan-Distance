<?php
include($_SERVER['DOCUMENT_ROOT'] . '/db.php');
header('Content-type:application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = htmlspecialchars($_GET["id"]);

    mysqli_set_charset($con, 'utf8');

    $xc = mysqli_query($con, "SELECT * FROM overallxc WHERE meet='" . $id . "'");
    $tf = mysqli_query($con, "SELECT * FROM overalltf WHERE meet='" . $id . "'");
    if (mysqli_num_rows($xc) > 0) {
        $sport = "xc";
    } elseif (mysqli_num_rows($tf) > 0) {
        $sport = "tf";
    } else {
        exit;
    }
    $data = [];
    if ($sport == "xc") {
        while ($row = mysqli_fetch_array($xc, MYSQLI_ASSOC)) {
            $data[] = $row;
        }
    } else if ($sport == "tf") {
        while ($row = mysqli_fetch_array($tf, MYSQLI_ASSOC)) {
            $data[] = $row;
        }
    } else {
        $data = [];
    }
    // var_dump($data);
    echo json_encode($data, JSON_PRETTY_PRINT);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize the session
    session_start();

    // Check if the user is logged in, if not then redirect him to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: /admin/login.php");
        exit;
    }
}
