<?php
        include($_SERVER['DOCUMENT_ROOT'].'/db.php');

            //Set Season
            $y = $_REQUEST["y"];
            
            if ($y == "20") {
                echo "<p><strong>*The 2020 Track Season was shortened due to the <a href='https://en.wikipedia.org/wiki/COVID-19_pandemic'>COVID-19 Pandemic</a></strong>.</p>";
            }

            //Start Table
            echo "<div class='table-responsive'>
                <table class='table table-condensed table-striped dataTable' id='rosterTable'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>3200m</th>
                            <th>1600m</th>
                            <th>800m</th>
                            <th>400m</th>";
                        echo"</tr>
                    </thead>";
                    echo "<tbody>";
            
            //Get Athlete Names
            $athletes = [];
            $result = mysqli_query($con,"SELECT * FROM athletes WHERE tf".$y." = 1");
                    while($row = mysqli_fetch_array($result)) {
                        $name = $row['name'];
                        $id = $row['id'];
                        $class = $row['class'];
                        $profile = $row['profile'];
                    if ($class == $y) {
                        $grade = "Sr.";
                    } else if ($class == ($y+1)) {
                        $grade = "Jr.";
                    } else if ($class == ($y+2)) {
                        $grade = "So.";
                    } else if ($class == ($y+3)) {
                        $grade = "Fr.";
                    } else {
                        $grade = $row['year'];
                    }

                    if(strpos($row['captain'], 'tf'.$y) !== false){
                    $name = $name." (C)";
                    }

                    $athletes[$profile] = ["name" => $name, "grade" => $grade];
                }

                $result = mysqli_query($con,"SELECT id,Name,Date FROM meets");
                while($row = mysqli_fetch_array($result)) {
                    $meets[$row['id']] = $row['Name']." (".date("n/j/y",strtotime($row['Date'])).")";
                }

                foreach ($athletes as $profile => $a) {
                   $result = mysqli_query($con,"SELECT * FROM overalltf WHERE sr = 1 AND season = 'tf".$y."' AND profile='".$profile."'");
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
                    echo "<th>".$athletes[$profile]["grade"]."</th>"; 
                    foreach (["3200m", "1600m", "800m", "400m"] as $d) {
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