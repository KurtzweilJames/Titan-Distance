<?php
        include($_SERVER['DOCUMENT_ROOT'].'/db.php');

            //Set Season
            $y = $_REQUEST["y"];

            //Start Table
            echo "<div class='table-responsive'>
                <table class='table table-condensed table-striped dataTable' id='rosterTable'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Grade</th>
                            <th>3mi</th>
                            <th>2 Mile</th>
                            <th>5k</th>";
                        echo"</tr>
                    </thead>";
                    echo "<tbody>";
            
            //Get Athlete Names
            $athletes = [];
            $result = mysqli_query($con,"SELECT * FROM athletes WHERE xc".$y." = 1");
                    while($row = mysqli_fetch_array($result)) {
                        $name = $row['name'];
                        $id = $row['id'];
                        $class = $row['class'];
                        $profile = $row['profile'];
                    if ($class == ($y+1)) {
                        $grade = "Sr.";
                    } else if ($class == ($y+2)) {
                        $grade = "Jr.";
                    } else if ($class == ($y+3)) {
                        $grade = "So.";
                    } else if ($class == ($y+4)) {
                        $grade = "Fr.";
                    } else {
                        $grade = $row['year'];
                    }

                    if(strpos($row['captain'], 'xc'.$y) !== false){
                    $name = $name." (C)";
                    }

                    $athletes[$profile] = ["name" => $name, "grade" => $grade];
                }

                $result = mysqli_query($con,"SELECT id,Name,Date FROM meets");
                while($row = mysqli_fetch_array($result)) {
                    $meets[$row['id']] = $row['Name']." (".date("n/j/y",strtotime($row['Date'])).")";
                }

                foreach ($athletes as $profile => $a) {
                   $result = mysqli_query($con,"SELECT * FROM overallxc WHERE sr = 1 AND season = 'xc".$y."' AND profile='".$profile."'");
                    while($row = mysqli_fetch_array($result)) { 
                        $athletes[$profile][$row['distance']] = $row['time'];
                        $athletes[$profile][$row['distance']."_meet"] = $row['meet'];
                    }
                }

                uasort($athletes, function ($a, $b) {
                    if (empty($a["3mi"])) {
                        return 1;
                    } else if (empty($b["3mi"])) {
                        return -1;
                    } else if ($a['3mi'] < $b['3mi']) {
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
                    foreach (["3mi", "2mi", "5k"] as $d) {
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