<?php
        include($_SERVER['DOCUMENT_ROOT'].'/db.php');

            //Set Season
            $y = $_REQUEST["y"];
            
            //Start Table
            echo "<div class='table-responsive'>
                <table class='table table-condensed table-striped dataTable' id='rosterTable' style='display:none'>
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

                    $names[] = $name;
                    $grades[] = $grade;
                    $profiles[] = $profile;

                }

                $result = mysqli_query($con,"SELECT id,Name,Date FROM meets");
                while($row = mysqli_fetch_array($result)) {
                    $meets[$row['id']] = $row['Name']." (".date("n/j/y",strtotime($row['Date'])).")";
                }

                $result = mysqli_query($con,"SELECT * FROM prs WHERE season = 'tf".$y."' ORDER BY 1600m IS NULL, 1600m ASC");
            while($row = mysqli_fetch_array($result)) {
                $key = array_search($row['profile'], $profiles);
                $url = "/athlete/".$row['profile'];
                echo "<tr class='clickable-row' data-href='".$url."'>";
                echo "<th><a href='".$url."'>".$names[$key]."</a></th>";
                echo "<th>".$grades[$key]."</th>";
                echo "<td><a href='/meet/".$row['ID3200m']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID3200m']]."'>".$row['3200m']."</a></td>";
                echo "<td><a href='/meet/".$row['ID1600m']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID1600m']]."'>".$row['1600m']."</a></td>";
                echo "<td><a href='/meet/".$row['ID800m']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID800m']]."'>".$row['800m']."</a></td>";
                echo "<td><a href='/meet/".$row['ID400m']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID400m']]."'>".$row['400m']."</a></td>";
            }
                        echo "</tr>";
                    echo "</tbody></table></div>";

            ?>