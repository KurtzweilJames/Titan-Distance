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
                            <th>3 Mile</th>
                            <th>2 Mile</th>";
                            if ($y != 20) {
                            echo "<th>5k</th>";
                            }
                        echo"</tr>
                    </thead>";
                    echo "<tbody>";
            
            //Get Athlete Names
            $result = mysqli_query($con,"SELECT * FROM athletes WHERE xc".$y." = 1");
                    while($row = mysqli_fetch_array($result)) {
                        $name = $row['name'];
                        $id = $row['id'];
                        $class = $row['class'];
                        $profile = $row['profile'];
                    if ($class == $y+1) {
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

                    $names[] = $name;
                    $grades[] = $grade;
                    $profiles[] = $profile;
                    
                }

                $result = mysqli_query($con,"SELECT id,Name,Date FROM meets");
                while($row = mysqli_fetch_array($result)) {
                    $meets[$row['id']] = $row['Name']." (".date("n/j/y",strtotime($row['Date'])).")";
                }

                $result = mysqli_query($con,"SELECT * FROM prs WHERE season = 'xc".$y."' ORDER BY 3mi IS NULL, 3mi ASC, 2mi IS NULL, 2mi ASC");
            while($row = mysqli_fetch_array($result)) {
                $key = array_search($row['profile'], $profiles);
                $url = "/athlete/".$row['profile'];
                echo "<tr class='clickable-row' data-href='".$url."'>";
                echo "<th><a href='".$url."'>".$names[$key]."</a></th>";
                echo "<th>".$grades[$key]."</th>";
                echo "<td><a href='/meet/".$row['ID3mi']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID3mi']]."'>".$row['3mi']."</a></td>";
                echo "<td><a href='/meet/".$row['ID2mi']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID2mi']]."'>".$row['2mi']."</a></td>";
                if ($y != 20) {
                echo "<td><a href='/meet/".$row['ID5k']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID5k']]."'>".$row['5k']."</a></td>";
                }
            }
                        echo "</tr>";
                    echo "</tbody></table></div>";
            ?>