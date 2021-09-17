<?php
    include($_SERVER['DOCUMENT_ROOT'].'/db.php');

            //Get Athlete Names
            $athletes = [];
            $result = mysqli_query($con,"SELECT * FROM athletes");
                    while($row = mysqli_fetch_array($result)) {
                        $name = $row['name'];
                        $id = $row['id'];
                        $class = $row['class'];
                        $profile = $row['profile'];

                    $athletes[$profile] = ["name" => $name, "class" => $class];
                }

                $result = mysqli_query($con,"SELECT id,Name,Date FROM meets");
                while($row = mysqli_fetch_array($result)) {
                    $meets[$row['id']] = $row['Name']." (".date("n/j/y",strtotime($row['Date'])).")";
                }

                foreach ($athletes as $profile => $a) {
                   $result = mysqli_query($con,"SELECT * FROM overalltf WHERE pr = 1 AND profile='".$profile."'");
                    while($row = mysqli_fetch_array($result)) { 
                        $athletes[$profile][$row['distance']] = $row['time'];
                        $athletes[$profile][$row['distance']."_meet"] = $row['meet'];
                    }
                }
                foreach ($athletes as $profile => $a) {
                    $result = mysqli_query($con,"SELECT * FROM overallxc WHERE pr = 1 AND profile='".$profile."'");
                     while($row = mysqli_fetch_array($result)) { 
                         $athletes[$profile][$row['distance']] = $row['time'];
                         $athletes[$profile][$row['distance']."_meet"] = $row['meet'];
                     }
                 }

                uasort($athletes, function ($a, $b) {
                    if (empty($a["1600m"])) {
                        return 1;
                    } else if (empty($b["1600m"])) {
                        return -1;
                    } else if ($a['1600m'] < $b['1600m']) {
                        return -1;
                    } else {
                        return 1;
                    }
                });
                echo "<div class='row row-cols-3 row-cols-md-5 mb-5'>";

                foreach ($athletes as $profile => $a) {
                    $url = "/athlete/".$profile;
                    echo "<div class='col mb-4 athlete-card' data-class='".$athletes[$profile]["class"]."'>";
                    echo "<div class='card hover-card clickable-row' data-href='".$url."'>";
                    $file = $_SERVER['DOCUMENT_ROOT']."/assets/images/athletes/".$profile.".jpg";
                    if (file_exists($file)) {
                        echo "<img src='/assets/images/athletes/".$profile.".jpg' class='card-img-top' loading='lazy'>";
                    } else {
                        echo "<img src='/assets/images/athletes/blank.png' class='card-img-top' loading='lazy'>";
                    }
                    echo "<h4 class='card-title text-center'><a href='".$url."'>".$athletes[$profile]["name"]."</a></h4>";
                    echo "</div>";
                    echo "</div>";
                }

                echo "</div>";
?>