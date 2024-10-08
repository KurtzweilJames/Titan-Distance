<?php
include("db.php");
$require = "athlete";

if (!empty($_GET["name"])) {
    $name = htmlspecialchars($_GET["name"]);
} else {
    $name = null;
}
if (!empty($_GET["profile"])) {
    $profile = htmlspecialchars($_GET["profile"]);
} else {
    $profile = null;
}

if (!empty($name)) {
    $redir = 1;
} else {
    $redir = 0;
}

$result = mysqli_query($con, "SELECT * FROM athletes WHERE name='" . $name . "' OR profile='" . $profile . "'");

if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound');
    exit;
}

while ($row = mysqli_fetch_array($result)) {
    $name = $row['name'];
    $class = $row['class'];
    $college = $row['college'];
    $elementary = $row['elementary'];
    $profile = $row['profile'];
    $athnet = $row['athnet'];
    $captain = $row['captain'];
    $awards = $row['awards'];
    $tfrrs = $row['tfrrs'];
    $bio = $row['bio'];
}

if ($redir == 1) {
    if (!empty($profile)) {
        $url = "https://titandistance.com/athlete/" . $profile;
        header('Location: ' . $url, TRUE);
        exit;
    }
}
$pgtitle = $name;

$result = mysqli_query($con, "SELECT id,Name,Date FROM meets");
while ($row = mysqli_fetch_array($result)) {
    $meets[$row['id']] = $row['Name'] . " (" . date("n/j/y", strtotime($row['Date'])) . ")";
}

$file = $_SERVER['DOCUMENT_ROOT'] . "/assets/images/athletes/" . $profile . ".jpg";
if (file_exists($file)) {
    $image = "assets/images/athletes/" . $profile . ".jpg";
} else {
    $image = "assets/images/athletes/blank.jpg";
}

include("header.php");

//Personal Records
$allprs = [];
$prs = [];
$trackevents = [];
$result = mysqli_query($con, "SELECT distance,time,meet FROM overallxc WHERE pr = 1 AND profile = '" . $profile . "' AND distance IN ('3mi','2mi','5k')");
while ($row = mysqli_fetch_array($result)) {
    $allprs[$row['distance']] = "<td><a href='/meet/" . $row['meet'] . "' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . $meets[$row['meet']] . "'>" . $row['time'] . "</a></td>";
    $prs[$row['distance']] = $row['time'];
}
$result = mysqli_query($con, "SELECT DISTINCT event,result,meet FROM overalltf WHERE pr = 1 AND profile = '" . $profile . "'");
while ($row = mysqli_fetch_array($result)) {
    $allprs[$row['event']] = "<td><a href='/meet/" . $row['meet'] . "' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . $meets[$row['meet']] . "'>" . formatTime($row['result']) . "</a></td>";
    $prs[$row['event']] = formatTime($row['result']);
    $trackevents[] = $row['event'];
}
?>

<div class="container-xl my-2">
    <div class="row">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center text-md-start">
                    <?php
                    echo "<div class='athlete-image mx-auto mx-md-0'>";
                    echo "<img src='/" . $image . "' class='img-thumbnail' alt='" . $name . "'>";
                    if (!empty($college)) {
                        $json = json_decode(file_get_contents("api/collegelogos.json"), true);

                        $singleCollege = explode(';', $college)[0];

                        $singleCollege = str_replace(" (DI)", "", $singleCollege);
                        $singleCollege = str_replace(" (DII)", "", $singleCollege);
                        $singleCollege = str_replace(" (DIII)", "", $singleCollege);

                        if (!empty($json[$singleCollege])) {
                            echo "<img src='/assets/logos/colleges/" . $json[$singleCollege]["logo"] . "' class='college-overlay' alt='" . $singleCollege . "'>";
                        }
                    }
                    echo "</div>";

                    $y = substr($currentyear, -2);
                    if (date('n') > 6) {
                        $y = $y + 1;
                    }

                    if ($class == $y + 2000) {
                        $grade = " (Sr.)";
                    } else if ($class == $y + 2001) {
                        $grade = " (Jr.)";
                    } else if ($class == $y + 2002) {
                        $grade = " (So.)";
                    } else if ($class == $y + 2003) {
                        $grade = " (Fr.)";
                    } else {
                        $grade = null;
                    }

                    echo "<h4>" . $name . "</h5>";
                    echo "<h5>Class of " . $class . $grade . "</h5>";
                    if (!empty($college)) {
                        // $json = json_decode(file_get_contents("api/collegelogos.json"), true);
                        $colleges = explode(";", $college);
                        foreach ($colleges as $c) {
                            echo "<h6>";
                            echo $c;
                            $c = str_replace(" (DI)", "", $c);
                            $c = str_replace(" (DIII)", "", $c);
                            // if (!empty($json[$c])) {
                            //     echo "<img class='ms-1' src='/assets/logos/colleges/" . $json[$c]["logo"] . "' height='14px'>";
                            // }
                            echo "</h6>";
                        }
                    }
                    // if ($currentsport == "xc" && $currentathlete == 1) {
                    //     echo "<h5>Team Points: ".$teampoints."</h5>";
                    // }

                    if (!empty($prs['3mi']) && $prs["3mi"] < "15:00:00") {
                        echo "<a class='badge text-bg-primary mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3mi time under 15 min' href='/records/sub16'>Sub-15 Club</a>";
                    } else if (!empty($prs['3mi']) && $prs["3mi"] < "16:00:00") {
                        echo "<a class='badge text-bg-primary mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3mi time under 16 min' href='/records/sub16'>Sub-16 Club</a>";
                    }
                    if (!empty($prs["3200m"]) && $prs["3200m"] < "10:00") {
                        echo "<a class='badge text-bg-primary mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3200m time under 10 min' href='/records/sub5'>Sub-10 Club</a>";
                    }
                    if (!empty($prs["1600m"]) && $prs["1600m"] < "5:00") {
                        echo "<a class='badge text-bg-primary mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='1600m time under 5 min' href='/records/sub5'>Sub-5 Club</a>";
                    }
                    if (!empty($prs["800m"]) && $prs["800m"] < "2:00") {
                        echo "<a class='badge text-bg-primary mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='800m time under 2 min' href='/records/sub5'>Sub-2 Club</a>";
                    }

                    echo "<hr class='mr-md-4'>";

                    $displayedAwards = false;
                    $possible = ["xc_allconf" => "XC All-Conference", "tf_allconf" => "TF All-Conference", "xc_mvp" => "XC MVP", "tf_mvp" => "TF MVP", "xc_allstate" => "XC All-State", "tf_allstate" => "TF All-State", "xc_allsectional" => "XC All-Sectional", "tf_allsectional" => "TF All-Sectional", "xc_allregional" => "XC All-Regional", "xc_improved" => "XC Most Improved", "xc_spirited" => "XC Most Spirited", "xc_ironman" => "XC Dave Pasquini \"Mr. Ironman\"", "xc_sportsmanship" => "CSL Sportsmanship", "xc_goldbrick" => "Goldbrick"];
                    $result = mysqli_query($con, "SELECT * FROM athletes WHERE profile='" . $profile . "'");
                    while ($row = mysqli_fetch_array($result)) {
                        foreach ($possible as $d => $a) {
                            if (!empty($row[$d])) {
                                $years = [];
                                $years = explode(",", $row[$d]);
                                foreach ($years as $y) {
                                    if (strpos($d, 'conf') !== false) {
                                        $badge = 'bg-csl';
                                    } else if (strpos($d, 'state') !== false || strpos($d, 'sectional') !== false || strpos($d, 'regional') !== false) {
                                        $badge = 'bg-ihsa';
                                    } else if (strpos($d, 'goldbrick') !== false) {
                                        $badge = 'text-bg-primary';
                                    } else {
                                        $badge = "text-bg-active";
                                    }
                                    echo "<span class='badge " . $badge . " mx-1'>";
                                    if (strpos($d, 'state') !== false || strpos($d, 'sectional') !== false || strpos($d, 'regional') !== false) {
                                        echo "<img src='/assets/icons/ihsa.svg' height='10px' class='me-2'>";
                                    }
                                    echo $a . " (" . $y . ")";
                                    echo "</span>";
                                }
                                $displayedAwards = true;
                            }
                        }
                    }

                    if ($displayedAwards == true) {
                        echo "<hr class='mr-md-4'>";
                    }

                    if (!empty($athnet)) {
                        echo "<a class='btn btn-td btn-sm mx-1' href='https://www.athletic.net/athlete/" . $athnet . "/cross-country/' role='button' target='_null'>AthleticNET XC</a>";
                        echo "<a class='btn btn-td btn-sm mx-1' href='https://www.athletic.net/athlete/" . $athnet . "/track-and-field/' role='button' target='_null'>AthleticNET TF</a>";
                    }

                    if (!empty($tfrrs)) {
                        echo "<a class='btn btn-td btn-sm mx-1' href='" . $tfrrs . "' role='button' target='_null' data-bs-toggle='tooltip' data-bs-title='Collegiate Results'>TFRRS</a>";
                    }
                    ?>
                    <a class="btn btn-link btn-sm my-2" href="https://docs.google.com/forms/d/e/1FAIpQLSdCNMNZBMD5wCgcQ2SBcwuVOTOdV0y4j33HlwR53fCCaLaPag/viewform?usp=pp_url&entry.1449250561=Profile+Update" role="button" target="_blank">Update Profile Info</a>
                    <hr class="d-block d-md-none">
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card h-100">
                <div class="card-body">
                    <?php
                    if (!empty($bio)) {
                        echo '<h3 class="mb-0 border-bottom">Biography</h3>';
                        echo $bio;
                    }
                    if ($image == "assets/images/athletes/blank.jpg") {
                        echo "<div class=\"alert alert-warning\" role=\"alert\"><i class=\"bi bi-question-circle me-2\"></i>Is this your profile? <a href='https://docs.google.com/forms/d/e/1FAIpQLSdCNMNZBMD5wCgcQ2SBcwuVOTOdV0y4j33HlwR53fCCaLaPag/viewform?usp=pp_url&entry.1449250561=Profile+Update'>Submit a Profile Image.</a></div>";
                    }
                    ?>
                    <h3 class="mb-0 border-bottom">Personal Records</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <?php
                                    foreach ($allprs as $event => $row) {
                                        echo "<th scope='col'>" . $event . "</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    foreach ($allprs as $row) {
                                        echo $row;
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    $xc = mysqli_query($con, "SELECT * FROM overallxc WHERE profile='" . $profile . "' ORDER BY date IS NULL, date DESC");
                    $tf = mysqli_query($con, "SELECT * FROM overalltf WHERE profile='" . $profile . "' ORDER BY date IS NULL, date DESC");
                    ?>
                    <div class="row my-2">
                        <?php
                        if (mysqli_num_rows($xc) > 0) {
                            if (mysqli_num_rows($tf) > 0) {
                                echo "<div class='col-md-6'>";
                            } else {
                                echo "<div class='col-md-12'>";
                            }
                            echo "<h3 class='mb-0 border-bottom'>Cross Country</h3>
                    <div class='table-responsive overflow-hidden'>
                        <table class='table table-condensed table-striped table-hover dataTable' id='xcPersonal'>
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Meet</th>
                                    <th>Distance</th>
                                </tr>
                            </thead>
                            <tbody>";
                            while ($row = mysqli_fetch_array($xc)) {
                                $meet = $row['meet'];
                                $distance = str_replace("mi", " Mile", $row['distance']);
                                echo "<tr class='clickable-row' data-href='/meet/" . $meet . "'>";

                                echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Place: " . $row['place'] . "'>";
                                echo $row['time'];
                                if ($row['pr'] == 1) {
                                    echo "<span class='badge text-bg-primary ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                                } else if ($row['sr'] == 1) {
                                    echo "<span class='badge text-bg-active ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                                }
                                echo "</td>";

                                echo "<td><a href='/meet/" . $meet . "'>" . $meets[$meet] . "</a></td>";
                                echo "<td>" . $distance . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table></div></div>";
                        }

                        if (mysqli_num_rows($tf) > 0) {
                            if (mysqli_num_rows($xc) > 0) {
                                echo "<div class='col-md-6'>";
                            } else {
                                echo "<div class='col-md-12'>";
                            }
                            echo "<h3 class='mb-0 border-bottom'>Track</h3>
                    <div class='table-responsive overflow-hidden'>
                        <table class='table table-condensed table-striped table-hover dataTable' id='tfPersonal'>
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Result</th>
                                    <th>Meet</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>";
                            while ($row = mysqli_fetch_array($tf)) {
                                $meet = $row['meet'];
                                echo "<tr class='clickable-row' data-href='/meet/" . $meet . "'>";
                                echo "<td>" . $row['event'] . "</td>";

                                if ($row['result'] == "NT") {
                                    echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='NT=No Time. Athlete most likely competed, but no split recorded.'>";
                                } else if (!empty($row['place'])) {
                                    echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Place: " . $row['place'] . "'>";
                                } else {
                                    echo "<td>";
                                }
                                echo formatTime($row['result']);
                                if ($row['pr'] == 1) {
                                    echo "<span class='badge text-bg-primary ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                                } else if ($row['sr'] == 1) {
                                    echo "<span class='badge text-bg-active ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                                }
                                if (isset($row['relay'])) {
                                    echo "<span class='badge text-bg-info ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Relay Split'>R</span>";
                                }
                                echo "</td>";

                                echo "<td>";
                                echo "<a href='/meet/" . $meet . "'>" . $meets[$meet] . "</a>";
                                if ($row['indoor'] == 1) {
                                    echo "<span class='badge text-bg-info ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Indoor'><i class='bi bi-snow2'></i></span>";
                                }
                                echo "</td>";

                                echo "<td>" . $row['points'] . "</td>";

                                echo "</tr>";
                            }
                            echo "</tbody></table></div></div>";
                        }
                        ?>
                    </div>

                    <?php
                    $result = mysqli_query($con, "SELECT * FROM news WHERE `content` LIKE '%{$name}%' OR `content` LIKE '%{$profile}%' ORDER BY date DESC LIMIT 3");

                    if (mysqli_num_rows($result) > 0) {
                        echo "<h3 class='mt-3'>Recent News</h3>";
                        echo "<div class='row row-cols-1 row-cols-sm-2 row-cols-lg-3'>";

                        while ($row = mysqli_fetch_array($result)) {
                            $content = strip_tags($row['content']);
                            $date = date("F j, Y", strtotime($row['date']));
                            if (!empty($row['image'])) {
                                $newsImage = "/assets/images/" . $row['image'];
                            } else {
                                $newsImage = "/assets/images/blog/blank.png";
                            }

                            echo "<div class='col mb-4'>";
                            if (!empty($row['link'])) {
                                echo "<a class='card hover-card text-reset' href='" . $row['link'] . "' target='_blank'>";
                            } else {
                                echo "<a class='card hover-card text-reset' href='/news/" . $row['slug'] . "'>";
                            }
                            echo "<img src='" . $newsImage . "' class='card-img-top'>";
                            echo "<div class='card-body'>";
                            echo "<h3 class='card-title text-center'>" . $row['title'] . "</h3>";
                            if (!empty($content)) {
                                echo "<p class='card-text text-center'>" . substr($content, 0, 150) . "...</p>";
                            } else {
                                echo "<p class='card-text text-center'><u>Read More</u></p>";
                            }
                            echo "<p class='card-text'><small class='text-muted'>Published on " . $date . "</small></p>";
                            echo "</div>";
                            echo "</div>";
                            echo "</a>";
                        }

                        echo "</div>";
                    }
                    ?>
                    <h3 class="mt-3">Result Charting</h3>
                    <select class="form-select" id="chartSelect" onchange="getChartData(this.value, this.text)">
                        <option value="" selected disabled>Select a Chart to Display</option>
                    </select>
                    <canvas id="chartContainer" class="d-none" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0/dist/chartjs-adapter-moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@1.2.1/dist/chartjs-plugin-zoom.min.js"></script>
<script>
    if (document.getElementById("xcPersonal")) {
        const xcPersonal = new simpleDatatables.DataTable("#xcPersonal", {})
    }
    if (document.getElementById("tfPersonal")) {
        const tfPersonal = new simpleDatatables.DataTable("#tfPersonal", {})
    }

    var data;

    function getChartData(event, eventDisplay) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                response = this.responseText;
                data = JSON.parse(response);
                if (eventDisplay == null) {
                    eventDisplay = event
                }
                generateChart(event, eventDisplay);
            }
        };
        var url = "/api/charts?profile=<?php echo $profile; ?>&event=" + event
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    var events;
    var athleteProfile = "<?php echo $profile; ?>";
    var athleteName = "<?php echo $name; ?>";
    getEvents();

    function getEvents() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                response = this.responseText;
                events = JSON.parse(response);
                var select = document.getElementById("chartSelect");
                for (i in events) {
                    if (events[i]["count"] > 1) {
                        var option = document.createElement('option');
                        option.text = events[i]["eventDisplay"];
                        option.value = events[i]["event"];
                        select.add(option);
                    }
                }
                return events;
            }
        };
        var url = "/api/charts?profile=<?php echo $profile; ?>"
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    var athleteChart;

    const ctx = document.getElementById('chartContainer').getContext('2d');
    athleteChart = new Chart(ctx, {
        type: 'line',
        options: {
            title: {
                display: true,
                text: athleteName + "'s Progression"
            },
            legend: {
                display: false,
            }
        }
    });


    function generateChart(event, eventDisplay) {
        athleteChart.destroy()
        document.getElementById('chartContainer').classList.remove("d-none")
        var labels = [];
        var secs = [];
        for (i in data) {
            labels.push(data[i]["date"])
            secs.push(data[i]["secs"])
        }
        athleteChart = new Chart(ctx, {
            type: 'line',
            data: {
                // labels: labels,
                datasets: [{
                    label: "Time (secs)",
                    // data: secs,
                    data: data,
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: athleteName + "'s " + eventDisplay + " Progression"
                    },
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        mode: 'index',
                        callbacks: {
                            title: (context) => {
                                return context[0].raw.meetName;
                            },
                            label: (context) => {
                                return context.raw.result;
                            },
                        }
                    },
                    zoom: {
                        zoom: {
                            wheel: {
                                enabled: true,
                            },
                            pinch: {
                                enabled: true
                            },
                            mode: 'xy',
                        }
                    }
                },
                parsing: {
                    xAxisKey: 'date',
                    yAxisKey: 'secs',
                    // yAxisKey: 'result'
                },
                borderDash: [5, 5],
                backgroundColor: [
                    'rgba(7, 55, 99, 0.1)',
                ],
                borderColor: [
                    'rgba(7, 55, 99, 1)',
                ],
                borderWidth: 2,
                lineTension: 0.4,
                pointBackgroundColor: '#ffd700',
                pointRadius: 5,
                pointHoverRadius: 7,
                scales: {
                    x: {
                        type: 'time',
                    },
                    y: {
                        type: 'time',
                        time: {
                            unit: 'second',
                        }
                    }
                }
            }
        });
    }
    window.onload = function() {
        getChartData(document.getElementById("chartSelect").options[1].value, document.getElementById("chartSelect").options[1].text)
        document.getElementById("chartSelect").options[1].selected = true
    };
</script>

<!-- <?php $require = "charts"; ?> -->
<?php include("footer.php"); ?>