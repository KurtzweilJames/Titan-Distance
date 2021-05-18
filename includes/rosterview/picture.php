<?php
    include($_SERVER['DOCUMENT_ROOT'].'/db.php');

    //Get Athlete Names
    $result = mysqli_query($con,"SELECT * FROM athletes");
    while($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $id = $row['id'];
        $class = "20".$row['class'];
        $profile = $row['profile'];

    $names[] = $name;
    $grades[] = $class;
    $profiles[] = $profile;
    }

    echo "<div class='row row-cols-3 row-cols-md-5 mb-5'>";
    $result = mysqli_query($con,"SELECT * FROM prs WHERE season = 'all' ORDER BY 3mi IS NULL, 3mi ASC, 2mi IS NULL, 2mi ASC");
    while($row = mysqli_fetch_array($result)) {
        $key = array_search($row['profile'], $profiles);
        $url = "/athlete/".$row['profile'];
        echo "<div class='col mb-4'>";
        echo "<div class='card hover-card clickable-row' data-href='".$url."'>";

        $file = $_SERVER['DOCUMENT_ROOT']."/assets/images/athletes/".$row['profile'].".png";
        if (file_exists($file)) {
            echo "<img src='/assets/images/athletes/".$row['profile'].".png' class='card-img-top'>";
        } else {
            echo "<img src='/assets/images/athletes/blank.png' class='card-img-top'>";
        }

        echo "<h4 class='card-title text-center'><a href='".$url."'>".$names[$key]."</a></h4>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";     
?>