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
                    </thead>
                    <tbody>";
            
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

        $result = mysqli_query($con,"SELECT id,Name,Date FROM meets");
        while($row = mysqli_fetch_array($result)) {
            $meets[$row['id']] = $row['Name']." (".date("n/j/y",strtotime($row['Date'])).")";
        }

        $result = mysqli_query($con,"SELECT * FROM prs WHERE season = 'all' ORDER BY 3mi IS NULL, 3mi ASC");
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
            echo "<td><a href='/meet/".$row['ID3mi']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID3mi']]."'>".$row['3mi']."</a></td>";
            echo "<td><a href='/meet/".$row['ID2mi']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID2mi']]."'>".$row['2mi']."</a></td>";
            echo "<td><a href='/meet/".$row['ID5k']."' data-toggle='tooltip' data-placement='top' title='".$meets[$row['ID5k']]."'>".$row['5k']."</a></td>";
        }
                    echo "</tr>";
                echo "</tbody></table></div>";
                
                
                ?>