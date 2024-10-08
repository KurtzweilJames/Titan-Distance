<?php
include($_SERVER['DOCUMENT_ROOT'] . '/db.php');
header('Content-Type: application/json');

if (!isset($_GET["limit"])) {
  $limit = 9;
} else {
  $limit = $_GET["limit"];
}

$result = mysqli_query($con, "SELECT * FROM news WHERE public = 1 ORDER BY date DESC LIMIT $limit");
$data = [];
while ($row = mysqli_fetch_array($result)) {
  $single["title"] = $row['title'];
  //$single['content'] = substr(strip_tags($row['content']), 0, 150);
  $single['date'] = date("F j, Y", strtotime($row['date']));
  if (!empty($row['image'])) {
    $single['image'] = "assets/images/" . $row['image'];
  } else {
    $single['image'] = "assets/images/blog/blank.png";
  }
  if (isset($row['cat'])) {
    $single['category'] = $row['cat'];
  } else {
    $single['category'] = null;
  }

  if (!empty($row['link'])) {
    $link = $row['link'];
  } else {
    $link = "/news/".$row['slug'];
  }
  $single['link'] = $link;
  $single['slug'] = $row['slug'];

  $content = strip_tags($row['content']);

  $single['excerpt'] = substr($content, 0, 250);
  $single['content'] = $row['content'];

  $data[] = $single;
}

echo json_encode($data, JSON_PRETTY_PRINT);
if (empty($data)) {
  echo "{}";
}
