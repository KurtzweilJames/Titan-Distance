<?php
        include($_SERVER['DOCUMENT_ROOT'].'/db.php');

            //Start Table
            echo "<div class='table-responsive'>
                <table class='table table-condensed table-striped dataTable' id='rosterTable'>
                    <thead class='text-center'>
                    <tr>
                    <th colspan='2'>Athlete</th>
                    <th colspan='4'>Track</th>
                    <th colspan='3'>Cross Country</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Class</th>
                    <th>3200m</th>
                    <th>1600m</th>
                    <th>800m</th>
                    <th>400m</th>
                    <th>3 Mile</th>
                    <th>2 Mile</th>
                    <th>5k</th>
                </tr>
                    </thead>";
                    echo "<tbody>";
            
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
                
                foreach ($athletes as $profile => $a) {
                    $url = "/athlete/".$profile;
                    echo "<tr class='clickable-row' data-href='".$url."'>";
                    echo "<th><a href='".$url."'>".$athletes[$profile]["name"]."</a></th>";
                    echo "<th>20".$athletes[$profile]["class"]."</th>"; 
                    foreach (["3200m", "1600m", "800m", "400m","3mi","2mi","5k"] as $d) {
                        if (!empty($athletes[$profile][$d])) {
                            echo "<td><a href='/meet/".$athletes[$profile][$d."_meet"]."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$athletes[$profile][$d."_meet"]]."'>".$athletes[$profile][$d]."</a></td>";
                        } else {
                            echo "<td>-</td>";
                        }
                    }
                    echo "</tr>";
                }
                
                    echo "</tbody></table></div>";

            ?>