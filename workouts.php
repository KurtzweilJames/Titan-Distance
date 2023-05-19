<?php $pgtitle = "Workouts"; ?>
<?php include("header.php"); ?>
<?php
if (!empty($_GET["date"])) {
    $sundaydate = date('Y-m-d', strtotime(htmlspecialchars($_GET["date"])));
    // $dateraw = strtotime(htmlspecialchars($_GET["date"]));
    // $sundaydate = $date;
    // $saturdaydate = date('Y-m-d', strtotime('next sunday', $dateraw));
    // echo $date;
    // echo $saturdaydate;
} else {
    if (date('l') == "Sunday") {
        $sundaydate = date('Y-m-d', strtotime('today'));
    } else {
        $sundaydate = date('Y-m-d', strtotime('previous sunday'));
    }
    $saturdaydate = date('Y-m-d', strtotime('next sunday'));
}
if (isset($_GET["strava"])) {
    $strava = $_GET["strava"];
}
?>
<section id="content">
    <div class="container mt-4">
        <?php
        if (!empty($strava)) {
            if ($strava == "loggedin") {
                echo "<div class='alert alert-success' role='alert'>
        You have been logged in to your Strava account.
        </div>";
            } else if ($strava !== "error") {
                echo "<div class='alert alert-success' role='alert'>
                Activity Succesfully Uploaded. <a href='https://www.strava.com/activities/" . $strava . "' target='_blank'>Check it out here!</a>
                </div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>
            Strava Upload failed. Please try again. You may need to log out, then log back in.
            </div>";
            }
        }
        ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Weekday (Date)</th>
                        <th>Workout</th>
                        <th>Mileage</th>
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
                    // $result = mysqli_query($con, "SELECT * FROM workouts WHERE date >= '" . $sundaydate . "' AND date < '" . $saturdaydate . "' ORDER BY date");
                    $result = mysqli_query($con, "SELECT * FROM workouts WHERE date >= '" . $sundaydate . "' ORDER BY date LIMIT 7");
                    while ($row = mysqli_fetch_array($result)) {

                        $d = date("l", strtotime($row['date'])) . " (" . date("n/j", strtotime($row['date'])) . ")";

                        if (!empty($row['practicename'])) {
                            $tooltip = $row['practicename'] . " (" . date("g:i a", strtotime($row['practicetime'])) . ")";
                        } else {
                            $tooltip = "No Organized Practice.";
                        }

                        if ($row['date'] == $todaydate) {
                            echo "<tr class='table-primary' id='" . $row['date'] . "'>";
                        } else {
                            echo "<tr id='" . $row['date'] . "'>";
                        }
                        echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='" . $tooltip . "'>" . $d . "</td>";

                        if (!empty($row['workout'])) {
                            echo "<td>" . $row['workout'] . "</td>";
                        } else {
                            if (!empty($row['practicename'])) {
                                echo "<td>" . $row['practicename'] . "</td>";
                            } else {
                                echo "<td>To Be Announced</td>";
                            }
                        }

                        echo "<td>";
                        if (!empty($row['1mileage']) && empty($row['2mileage'])) {
                            echo $row['1mileage'];
                        }
                        if (!empty($row['1mileage']) && !empty($row['2mileage'])) {
                            echo "<strong>Group 1: </strong>" . $row['1mileage'];
                            echo "<br><strong>Group 2: </strong>" . $row['2mileage'];
                        }
                        if (!empty($row['3mileage'])) {
                            echo "<br><strong>Group 3: </strong>" . $row['3mileage'];
                        }
                        echo "</td>";

                        echo "<td>";
                        if ($row['weights'] >= 1) {
                            echo "<span class='badge bg-primary'><i class='bi bi-fire me-1'></i>Weight Circuit";
                            if ($row['weights'] > 1) {
                                echo "s (x" . $row['weights'] . ")";
                            }
                            echo "</span>";
                        }
                        if (!empty($row['strides']) && $row['strides'] !== 0) {
                            echo " <span class='badge bg-primary'>" . $row['strides'] . " strides</span>";
                        }
                        if (empty($row['weights']) && empty($row['strides']) && !empty($row['notes'])) {
                            // echo "<br>";
                        }
                        if (!empty($row['notes'])) {
                            echo "*" . $row['notes'];
                        }

                        //STRAVA
                        if (is_int($row['1mileage'])) {
                            $metric = $row['1mileage'] * 1;
                            $start = $row['date'] . " " . $row['practicetime'];
                            $start = date('Y-m-d', strtotime($row['date'])) . 'T' . date("H:i:s", strtotime($row['practicetime']));
                            $elapsed = $row['1mileage'] * 420;
                        }
                        echo "</td>";
                        if (!empty($_SESSION["strava"])) {
                            echo "<th><button type='button' class='btn btn-strava btn-sm' data-bs-toggle='modal' data-bs-target='#stravaModal' onClick='showStrava(\"" . $row['workout'] . "\",\"" . $row['1mileage'] . "\",\"" . $start . "\")'><i class='bi bi-strava'></i></button></th>";
                        }
                        echo "</tr>";
                    }

                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr>";
                        echo "<td class='text-center' colspan='4'>No Workouts Currently Scheduled.</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- <a type="button" class="btn btn-link" href="?date=<?php //echo date('Y-m-d', strtotime("-7 days", $sundaydate)); 
                                                                ?>">Previous 7 Days</a> -->
        <p><i>Workouts are weather pending and subject to change.</i></p>
        <p><strong>For Cross Country: </strong>Mileage only includes cooldowns and warmups are not included. Circuits
            are represented with a <i class="fas fa-dumbbell"></i> icon and strides are represented by <i class="fas fa-running"></i>. All warmups are 1 mile unless otherwise stated. For abbreviations, m =
            minutes, mi = miles, E = Easy Pace, M = Medium Pace, H = Hard Pace, bu = Buildup Strides.</p>
        <p><strong>For Track: </strong>Mileage includes warmups, cooldowns, and strides. All warmups are 1 mile unless
            otherwise stated.</p>
        <p>
            <?php
            if (empty($_SESSION["strava"])) {
                echo "<a class='btn btn-strava btn-sm'
                href='http://www.strava.com/oauth/authorize?client_id=42217&response_type=code&redirect_uri=https://titandistance.com/api/stravaauth&state=workouts&scope=read,activity:read_all,activity:write' role='button'><i class='bi bi-strava me-1'></i>Strava Login</a>";
            } else {
                echo "<a class='btn btn-strava btn-sm' href='/api/stravaauth?state=workouts&destroy=1' role='button'>Strava
                Logout</a>";
            }
            ?>
        </p>
    </div>
</section>

<div class="modal fade" id="stravaModal" tabindex="-1" role="dialog" aria-labelledby="StravaModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StravaModalTitle">Send to Strava</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <input type="number" class="form-control" id="distance" name="distance" min="0" max="15" step="0.5">
                                <div class="input-group-append">
                                    <div class="input-group-text">miles</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <label for="name">Start</label>
                            <input type="datetime-local" class="form-control" id="start" name="start" placeholder="Start Time">
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
                        <label for="pace" class="form-label" id="paceLabel">Pace (7:00 min/mile)</label>
                        <input type="range" class="form-range" min="270" max="510" id="pace" value="420" onchange="changePace()">
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
</div>
<script>
    const stravaModal = new bootstrap.Modal(document.getElementById('stravaModal'), {})

    function showStrava(n, d, s) {

        d = d.replace(" Miles", "");
        d = d.replace("Up to ", "");


        //stravaModal.show;
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

    function changePace() {
        range = document.getElementById("pace");
        label = document.getElementById("paceLabel");
        pace = range.value;

        d = document.getElementById("distance").value;
        hrs = document.getElementById("hr").value;
        mins = document.getElementById("min").value;
        secs = document.getElementById("sec").value;

        paceEng = Math.floor(pace / 60) + ":" + ((pace % 60) < 10 ? '0' : '') + (pace % 60).toFixed(0)

        elapsed = d * pace;

        hours = Math.floor(elapsed / 3600);
        elapsed %= 3600;
        minutes = Math.floor(elapsed / 60);
        seconds = elapsed % 60;

        document.getElementById("hr").value = hours
        document.getElementById("min").value = minutes
        document.getElementById("sec").value = seconds

        label.innerHTML = "Pace (" + paceEng + " min/mile)"
    }
</script>

<?php include("footer.php"); ?>