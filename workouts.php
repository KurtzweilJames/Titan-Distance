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
                        <?php
if (!empty($_SESSION["strava"])) {
    echo "<th></th>";
}
                        ?>
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

        //STRAVA
        $metric = $row['1mileage'] * 1;
        $start = $row['date']." ".$row['practicetime'];
        $start = date('Y-m-d', strtotime($row['date'])).'T'.date("H:i:s", strtotime($row['practicetime']));
        $elapsed = $row['1mileage'] * 420;
        echo "</td>";
        if (!empty($_SESSION["strava"])) {
            echo "<th><button type='button' class='btn btn-strava btn-sm' onClick='showStrava(\"".$row['workout']."\",\"".$row['1mileage']."\",\"".$start."\")'><i class='fab fa-strava'></i></button></th>";
        }
        echo "</tr>";

    }

    if (mysqli_num_rows($result) == 0){
        echo "<tr>";
        echo "<td class='text-center' colspan='4'>No Workouts Currently Scheduled.</td>";
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
        <p>
            <?php
            if (empty($_SESSION["strava"])) {
                echo "<a class='btn-strava btn-sm'
                href='http://www.strava.com/oauth/authorize?client_id=42217&response_type=code&redirect_uri=https://titandistance.com/api/stravaauth&state=workouts&scope=read,activity:read_all,activity:write' role='button'>Strava
                Login</a>";
            } else {
                echo "<a class='btn-strava btn-sm' href='/api/stravaauth?state=workouts&destroy=1' role='button'>Strava
                Logout</a>";
            }
        ?>
        </p>
    </div>
</section>

<div class="modal fade" id="StravaModal" tabindex="-1" role="dialog" aria-labelledby="StravaModalTitle"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StravaModalTitle">Send to Strava</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/api/sendstrava.php" method="post">
                    <div class="form-group mb-1">
                        <label for="name">Workout Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Workout Name">
                    </div>
                    <div class="row mb-1">
                        <div class="col-5">
                            <label for="name">Distance</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="distance" name="distance" min="0" max="15"
                                    step="any">
                                <div class="input-group-append">
                                    <div class="input-group-text">miles</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <label for="name">Start</label>
                            <input type="datetime-local" class="form-control" id="start" name="start"
                                placeholder="Start Time">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col">
                            <div class="input-group">
                                <input type="number" class="form-control" id="hr" name="hr" min="0" max="5">
                                <div class="input-group-append">
                                    <div class="input-group-text">hr</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <input type="number" class="form-control" id="min" name="min" min="0" max="60">
                                <div class="input-group-append">
                                    <div class="input-group-text">min</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <input type="number" class="form-control" id="sec" name="sec" min="0" max="60">
                                <div class="input-group-append">
                                    <div class="input-group-text">sec</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-strava">Send to Strava</button>
                        </div>
                    </div>
                </form>
                <small>*Initial estimate is for a 7min/mile pace. All activities logged as runs.</small>
                <img src="/assets/icons/compatible_with_strava.svg" height="16">
            </div>
        </div>
    </div>

    <script>
    function showStrava(n, d, s) {

        d = d.replace(" Miles", "");
        d = d.replace("Up to ", "");

        $('#StravaModal').modal('show');
        document.getElementById("name").value = n;
        document.getElementById("distance").value = d;
        document.getElementById("start").value = s;

        d = document.getElementById("distance").value;
        var e = d * 420;

        hours = Math.floor(e / 3600);
        e %= 3600;
        minutes = Math.floor(e / 60);
        seconds = e % 60;
        document.getElementById("hr").value = hours;
        document.getElementById("min").value = minutes;
        document.getElementById("sec").value = seconds;
    }
    </script>

    <?php include ("footer.php"); ?>