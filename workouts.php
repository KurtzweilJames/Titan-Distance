<?php $pgtitle = "Workouts"; ?>
<?php include ("header.php"); ?>


<section id="content">
    <div class="container mt-4">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Weekday (Date)</th>
                        <th>Mileage</th>
                        <th>Workout</th>
                        <th>Post-Workout</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (date(l) == "Sunday") {
                            $sundaydate = date('Y-m-d', strtotime('today')); 
                        } else {
                            $sundaydate = date('Y-m-d', strtotime('previous sunday'));
                        }
                        $saturdaydate = date('Y-m-d', strtotime('next sunday'));
                        $todaydate = date('Y-m-d');
                        
$result = mysqli_query($con, "SELECT * FROM xcworkouts WHERE date >= '".$sundaydate."' AND date < '".$saturdaydate."' ORDER BY date");

while ($row = mysqli_fetch_array($result)) {

    $d = date("l", strtotime($row['date'])) . " (" . date("n/j", strtotime($row['date'])) . ")";
        if ($row['date'] == $todaydate) {
            echo "<tr class='table-primary'>";
        }
        else {
            echo "<tr>";
        }
        echo "<td>" . $d . "</td>";
        echo "<td>";
        if (!empty($row['1mileage'])){
            echo "<strong>Group 1: </strong>".$row['1mileage'];
        }
        if (!empty($row['2mileage'])){
            echo "<br><strong>Group 2: </strong>".$row['2mileage'];
        }
        if (!empty($row['3mileage'])){
            echo "<br><strong>Group 3: </strong>".$row['3mileage'];
        }
        echo "</td>";
        if (!empty($row['workout'])) {
            echo "<td>" . $row['workout'] . "</td>";
        } else {
            echo "<td>To Be Announced</td>";
        }

        echo "<td>";
        if ($row['weights'] == 1) {
            echo "<span class='badge badge-primary'><i class='fas fa-dumbbell'></i> Weight Circuit</span>";
        }
        if (isset($row['strides']) && $row['strides'] != 0) {
            echo " <span class='badge badge-primary'><i class='fas fa-running'></i> ".$row['strides']."</span>";
        }
        echo $row['notes'];
        echo "</td>";
        
        echo "</tr>";

    }
?>
                </tbody>
            </table>
        </div>
        <p><i>Workouts are weather pending and subject to change.</i></p>
        <p><strong>For Cross Country: </strong>Mileage only includes cooldowns and warmups are not included. Circuits
            are represented with a <i class="fas fa-dumbbell"></i> icon and strides are represented by <i
                class="fas fa-running"></i>. All warmups are 1 mile unless otherwise stated. For abbreviations, m =
            minutes, mi = miles, E = Easy Pace, M = Medium Pace, H = Hard Pace, bu = Buildup Strides.</p>
        <p><strong>For Track: </strong>Mileage includes warmups, cooldowns, and strides. All warmups are 1 mile unless
            otherwise stated.</p>
    </div>
</section>
<?php include ("footer.php"); ?>