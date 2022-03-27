<?php include "db.php"; ?>
<?php
$id = htmlspecialchars($_GET["id"]);
$template = "meet";

if (strpos($_GET["id"], '/') !== false) {
    list($urlslug, $year) = explode("/", $id, 2);
    $result = mysqli_query($con, "SELECT * FROM meets WHERE Date LIKE '" . $year . "-%' AND Series = '" . $urlslug . "'");
} else {
    $result = mysqli_query($con, "SELECT * FROM meets WHERE id='" . $_GET["id"] . "'");
}
if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound?from=meets&year=' . $year . "'&slug='" . $urlslug . "'&id='" . $id);
    exit();
}
while ($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
    if (!empty($row['Series']) && empty($urlslug)) {
        $year = date("Y", strtotime($row['Date']));
        http_response_code(301);
        header('Location: https://titandistance.com/meet/' . $row['Series'] . "/" . $year);
    }

    //Page Title
    if (!empty($series)) {
        $pgtitle = $row['Name'] . " (" . $year . ")";
    } else {
        $pgtitle = $row['Name'];
    }

    $pgtitleignore = 1;
    $require = "meet"; //Meet Variables
    $name = $row['Name'];
    $date = date("l, F d, Y", strtotime($row['Date']));
    $unformatteddate = $row['Date'];
    $location = $row['Location'];
    $athnet = $row['AthNet'];
    $message = $row['Message'];
    $notes = $row['Notes'];
    $season = $row['Season'];
    $live = $row['Live'];
    $stream = $row['Stream'];
    $results = $row['Results'];
    $series = $row['Series'];
    $website = $row['Website'];
    $weather = $row['Weather'];
    $levels = $row['Levels'];
    $opponentsArray = explode(", ", $row['Opponents']);
    $official = $row['Official']; //Two Day
    if (!empty($row['Day2Time'])) {
        $date = $date . " -<br>" . date("l, F d, Y", strtotime($row['Day2Time']));
    }

    //Sport
    $xc = mysqli_query($con, "SELECT * FROM overallxc WHERE meet='" . $id . "'");
    $tf = mysqli_query($con, "SELECT * FROM overalltf WHERE meet='" . $id . "'");
    if (mysqli_num_rows($xc) > 0) {
        $sport = "xc";
        $prepost = "post";
    } elseif (mysqli_num_rows($tf) > 0) {
        $sport = "tf";
        $prepost = "post";
    } else {
        $prepost = "pre";
        if (strpos($season, 'Cross Country') !== false) {
            $sport = "xc";
        } else {
            $sport = "tf";
        }
    }
}

//Photos
$result = mysqli_query($con, "SELECT * FROM photos WHERE meet='" . $id . "'");
if (mysqli_num_rows($result) > 0) {
    $photos = 1;
} else {
    $photos = 0;
}
if ($prepost == "post") {
    $result = mysqli_query($con, "SELECT * FROM news WHERE meet='" . $id . "' AND recap = 1 AND public = 1");
} elseif ($prepost == "pre") {
    $result = mysqli_query($con, "SELECT * FROM news WHERE meet='" . $id . "' AND info = 1 AND public = 1");
}
while ($row = mysqli_fetch_array($result)) {
    $content = $row['content'];
    $title = $row['title'];
    $image = $row['image'];
    $newsslug = $row['slug'];
}

if (empty($image)) {
    $result = mysqli_query($con, "SELECT * FROM photos WHERE meet='" . $id . "' LIMIT 1");
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $image = "meets/" . $row['cover'];
        }
    }
}
include "header.php";
echo '<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SportsEvent",
  "name": "' . $name . '",
  "description": "' . $name . ' results and news from Titan Distance",
  "startDate": "' . date("c", strtotime($unformatteddate)) . '",
  "competitor": [
      {
        "@type": "SportsTeam",
        "name": "Glenbrook South High School"
      }
    ],
    "location": "' . $location . '",
    "sport": "Track And Field",
    "eventStatus":"EventScheduled",
    "eventAttendanceMode":"OfflineEventAttendanceMode"
}
</script>';
?>
<div class="container mt-4 mb-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center text-md-start">
                    <div class="series-navigation d-flex justify-content-between">
                        <?php if (!empty($series)) {
                            echo "<div>";
                            $result = mysqli_query($con, "SELECT Date FROM meets WHERE Date < '" . $unformatteddate . "' AND Series = '" . $series . "'  ORDER BY Date DESC LIMIT 1");
                            while ($row = mysqli_fetch_array($result)) {
                                $yr = date("Y", strtotime($row['Date']));
                                echo "<a href='./" . $yr . "'><i class='bi bi-arrow-left'></i>" . $yr . "</a>";
                            }
                            echo "</div>";
                            echo "<div>";
                            $result = mysqli_query($con, "SELECT Date FROM meets WHERE Date > '" . $unformatteddate . "' AND Series = '" . $series . "'  ORDER BY Date ASC LIMIT 1");
                            while ($row = mysqli_fetch_array($result)) {
                                $yr = date("Y", strtotime($row['Date']));
                                echo "<a href='./" . $yr . "'>" . $yr . "<i class='bi bi-arrow-right'></i></a>";
                            }
                            echo "</div>";
                        } ?>
                    </div>
                    <div id="meetInfo">
                        <h4 id="meetName"><?php echo $name; ?></h4>
                        <h5 class="mb-0"><i class="bi bi-calendar-fill me-1"></i><?php echo $date; ?></h5>
                        <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-1"></i><?php echo $location; ?></h5>
                    </div>
                    <?php
                    if (!empty($weather)) {
                        echo "<h5 class='mb-0'><i class='bi bi-cloud-sun-fill me-1'></i>" . $weather . "</h5>";
                    }
                    ?>
                    <hr class="mt-2">
                    <div class="nav flex-column nav-pills d-none d-md-block" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php
                        echo "<a class='nav-link active' id='home-tab' data-bs-toggle='pill' data-bs-target='#home' role='tab' aria-controls='home-tab' aria-selected='false'><i class='bi bi-house-fill me-1'></i>Home</a>";
                        $dropdown[] = "<option value='home' name='home'>Home</option>";

                        if ($prepost == "pre" && !empty($content)) {
                            echo "<a class='nav-link' id='news-tab' data-bs-toggle='pill' data-bs-target='#news' role='tab' aria-controls='news-tab' aria-selected='true'><i class='bi bi-newspaper me-1'></i>Meet Information</a>";
                            $dropdown[] = "<option value='news' name='news'>Meet Information</option>";
                        } elseif ($prepost == "post" && !empty($content)) {
                            echo "<a class='nav-link' id='news-tab' data-bs-toggle='pill' data-bs-target='#news' role='tab' aria-controls='news-tab' aria-selected='true'><i class='bi bi-newspaper me-1'></i>Meet Recap</a>";
                            $dropdown[] = "<option value='news' name='news'>Meet Recap</option>";
                        }

                        //INDIVIDUAL RESULTS
                        if ($prepost == "post") {
                            echo "<a class='nav-link' id='results-tab' data-bs-toggle='pill' data-bs-target='#results' role='tab' aria-controls='scores-tab' aria-selected='false'><i class='bi bi-list-ol me-1'></i>Individual Results</a>";
                            $dropdown[] = "<option value='results' name='results'>Individual Results</option>";
                        }

                        $result = mysqli_query($con, "SELECT * FROM overallscores WHERE meet='" . $id . "'");
                        if ($prepost == "post" && mysqli_num_rows($result) > 0) {
                            echo "<a class='nav-link' id='scores-tab' data-bs-toggle='pill' data-bs-target='#scores' role='tab' aria-controls='scores-tab' aria-selected='false'><i class='bi bi-trophy-fill me-1'></i>Team Scores</a>";
                            $dropdown[] = "<option value='scores' name='scores'>Team Scores</option>";
                        }

                        $result = mysqli_query($con, "SELECT DISTINCT school FROM overalltf WHERE meet = '" . $id . "'");
                        if ($prepost == "post" && $sport == "tf" && mysqli_num_rows($result) > 1) {
                            echo "<a class='nav-link' id='dscores-tab' data-bs-toggle='pill' data-bs-target='#dscores' role='tab' aria-controls='dscores-tab' aria-selected='false'><i class='bi bi-list-stars me-1'></i>Distance Scores</a>";
                            $dropdown[] = "<option value='dscores' name='dscores'>Distance Scores</option>";
                        }

                        if ($location == "David Pasquini Fieldhouse" or $location == "John Davis Titan Stadium" or $location == "Glenbrook South High School") {
                            echo "<a class='nav-link' id='venue-tab' data-bs-toggle='pill' data-bs-target='#venue' role='tab' aria-controls='venue-tab' aria-selected='false'><i class='bi bi-geo-alt-fill me-1'></i>" . $location . "</a>";
                            $dropdown[] = "<option value='venue' name='venue'>" . $location . "</option>";
                        } elseif ($sport == "xc") {
                            echo "<a class='nav-link' id='venue-tab' data-bs-toggle='pill' data-bs-target='#venue' role='tab' aria-controls='venue-tab' aria-selected='false'><i class='bi bi-geo-alt-fill me-1'></i>Course Information</a>";
                            $dropdown[] = "<option value='venue' name='venue'>Course Information</option>";
                        } else {
                            echo "<a class='nav-link' id='venue-tab' data-bs-toggle='pill' data-bs-target='#venue' role='tab' aria-controls='venue-tab' aria-selected='false'><i class='bi bi-geo-alt-fill me-1'></i>Map & Directions</a>";
                            $dropdown[] = "<option value='venue' name='venue'>Map & Directions</option>";
                        }
                        if ($series == "titan") {
                            echo "<a class='nav-link' id='special-tab' data-bs-toggle='pill' data-bs-target='#special' role='tab' aria-controls='special-tab' aria-selected='false'><i class='bi bi-lightning-charge-fill me-1'></i>Titan Invite Records</a>";
                            $dropdown[] = "<option value='special' name='special'>Meet Records</option>";
                        }
                        if (!empty($live)) {
                            echo "<a class='nav-link' id='live' href='" . $live . "' role='tab' target='_blank'><i class='bi bi-bar-chart-fill me-1'></i>LIVE Results</a>";
                            $dropdown[] = "<option value='link-" . $live . "' name='live'>LIVE Results</option>";
                        }
                        if (!empty($stream)) {
                            echo "<a class='nav-link' id='stream' href='" . $stream . "' role='tab' target='_blank'><i class='bi bi-tv me-1'></i>Live Stream/Video</a>";
                            $dropdown[] = "<option value='link-" . $stream . "' name='stream'>Live Stream/Video</option>";
                        }
                        if ($photos == 1) {
                            echo "<a class='nav-link' id='photos-tab' data-bs-toggle='pill' data-bs-target='#photos' role='tab' aria-controls='photos-tab' aria-selected='false'><i class='bi bi-camera-fill me-1'></i>Photos</a>";
                            $dropdown[] = "<option value='photos' name='photos'>Photos</option>";
                        }
                        if (!empty($results)) {
                            echo "<a class='nav-link' id='download' href='" . $results . "' role='tab' target='_blank'><i class='bi bi-file-earmark-pdf-fill me-1'></i>Download Results</a>";
                            $dropdown[] = "<option value='link-" . $results . "' name='results'>Download Results</option>";
                        }
                        if (!empty($athnet)) {
                            echo "<a class='nav-link' id='ath-net' href='" . $athnet . "' role='tab' target='_blank'>Athletic.net Page<i class='bi bi-box-arrow-in-up-right ms-1'></i></a>";
                            $dropdown[] = "<option value='link-" . $athnet . "' name='athnet'>Athletic.net</option>";
                        }
                        if (!empty($website)) {
                            echo "<a class='nav-link' id='homepage' href='" . $website . "' role='tab' target='_blank'>Meet Website<i class='bi bi-box-arrow-in-up-right ms-1'></i></a>";
                            $dropdown[] = "<option value='link-" . $website . "' name='results'>Meet Website</option>";
                        }
                        if (!empty($results)) {
                            echo "<a class='nav-link' id='report' href='https://forms.gle/NQjahvTVmbNnsASo8' role='tab' target='_blank'>Request Correction<i class='bi bi-box-arrow-in-up-right ms-1'></i></a>";
                            $dropdown[] = "<option value='link-https://forms.gle/NQjahvTVmbNnsASo8' name='report'>Request Correction</option>";
                        }
                        ?>

                    </div>

                    <div class="form-group d-block d-md-none">
                        <select class="form-select" id="selectTab">
                            <?php foreach ($dropdown as $d) {
                                echo $d;
                            } ?>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-9 mt-2 mt-md-0">
            <?php
            if ($season == "Community") {
                echo "<div class='alert alert-primary' role='alert'>This event is not school-sponsored, in accordance with IHSA By-law 3.101. Athletes compete Unattached at these events, and are only listed as Glenbrook South* for technical purposes.</div>";
            }
            if (!empty($message)) {
                echo "<div class='alert alert-danger' role='alert'>" . $message . "</div>";
            }
            if (!empty($notes)) {
                echo "<div class='alert alert-info' role='alert'>" . $notes . "</div>";
            }
            if ($official == 3) {
                echo "<div class='alert alert-danger' role='alert'>These results have been marked Unofficial for a variety of reasons. The results may be incomplete or incorrect. Please use caution when viewing these results, as the times and date may be inaccurate.</div>";
            }
            ?>
            <div class="card h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h2><?php echo $name; ?></h2>
                            <?php
                            // echo '<a class="btn btn-primary" data-bs-toggle="pill" data-bs-target="#news" role="button">News</a>';
                            ?>
                            <div class="card w-75 mt-3">
                                <div class="card-header">
                                    Meet Information
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Meet Name: <?php echo $name; ?></li>
                                    <li class="list-group-item">Meet Date: <?php echo $date; ?></li>
                                    <li class="list-group-item">Location : <?php echo $location; ?></li>
                                    <li class="list-group-item">Opponents: <?php echo join(", ", $opponentsArray); ?></li>
                                    <li class="list-group-item">Levels : <?php echo $levels; ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="news" role="tabpanel" aria-labelledby="news-tab">
                            <?php
                            if ($prepost == "pre") {
                                echo "<h2>Meet Information</h2>";
                            } elseif ($prepost == "post") {
                                if (!empty($title)) {
                                    echo "<h2><a href='/news/" . $newsslug . "'>" . $title . "</a></h2>";
                                } else {
                                    echo "<h2>Meet Recap</h2>";
                                }
                            }
                            if (empty($content)) {
                                echo "<p>No meet recap or information is available at this time. Please be sure to use the links on the left to view more about the meet.</p><p>If this meet has already occured, our results are missing or have not been processed into our database.</p>";
                            } else {
                                $content = str_replace("../athlete/", "/athlete/", $content);
                                echo $content;
                            }
                            ?>
                        </div>

                        <div class="tab-pane fade" id="results" role="tabpanel" aria-labelledby="results-tab">
                            <div class='d-flex justify-content-between align-items-center mb-2'>
                                <h2>Individual Results</h2>
                                <div class='d-none d-md-block'>
                                    <?php
                                    if ($official == 1) {
                                        echo "<span class='badge bg-success'>Official Results (F.A.T.)</span>";
                                    } elseif ($official == 2) {
                                        echo "<span class='badge bg-warning'>Official Results (Hand Timed)</span>";
                                    } elseif ($official == 3) {
                                        echo "<span class='badge bg-danger'>Unofficial Results</span>";
                                    } elseif ($official == 4) {
                                        echo "<span class='badge bg-primary'>Live Results</span>";
                                    }
                                    ?>
                                    <div class='form-check form-switch'>
                                        <input type='checkbox' class='form-check-input' onChange='showHighlight(this.checked)' id='INDVhighlightSwitch' checked>
                                        <label class='custom-control-label' for='INDVhighlightSwitch'>Toggle
                                            Highlight</label>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $meetEvents = [];
                            $meetLevels = [];
                            if ($sport == "tf") {
                                echo "<select class='form-select' aria-label='Event Selector' id='selectEvent'
                                onChange='showTFResults(this.value)'>
                                <option selected disabled>Select Event</option>";
                                $result = mysqli_query($con, "SELECT DISTINCT event,level FROM overalltf WHERE meet = '" . $id . "' AND (relay IS NULL or name = 'RELAY')");
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . $row['event'] . "-" . $row['level'] . "'>" . $teams[$row['level']] . " " . $trackevents[$row['event']] . "</option>";
                                    $meetEvents[] = "\"" . $row['event'] . "-" . $row['level'] . "\"";
                                    if (!in_array($row['level'], $meetLevels)) {
                                        $meetLevels[] = $row['level'];
                                    }
                                }
                                echo "<option value='all'>Show All</option>";
                                echo "</select>";
                            } else if ($sport == "xc") {
                                echo "<select class='form-select' aria-label='Event Selector' id='selectEvent'
                                onChange='showXCResults(this.value)'>
                                <option selected disabled>Select Division</option>";
                                $result = mysqli_query($con, "SELECT DISTINCT level FROM overallxc WHERE meet = '" . $id . "'");
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<option value='" . $row['level'] . "'>" . $teams[$row['level']] . "</option>";
                                    $meetEvents[] = "\"" . $row['level'] . "\"";
                                    if (!in_array($row['level'], $meetLevels)) {
                                        $meetLevels[] = $row['level'];
                                    }
                                }
                                echo "<option value='all'>Show All</option>";
                                echo "</select>";
                            }
                            ?>

                            <div class="mt-3" id="indresultsContainer">
                                <p>Please select an event from the dropdown above.</p>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="scores" role="tabpanel" aria-labelledby="scores-tab">
                            <?php
                            echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                            echo "<h2>Team Scores</h2>";
                            echo "<div class='d-none d-md-block'>";
                            echo "<div class='form-check form-switch'>";
                            echo "<input type='checkbox' class='form-check-input' onChange='showHighlight(this.checked)' id='highlightSwitch' checked>";
                            echo "<label class='custom-control-label' for='highlightSwitch'>Toggle Highlight</label>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";

                            if ($sport == "tf") {
                                echo "<p><strong>Team scores for all events, including sprints, hurdles, field, etc.</strong></p>";
                            }
                            ?>
                            <div id="scoresContainer">
                                <?php
                                $quad = 0;
                                foreach ($meetLevels as $l) {
                                    $result = mysqli_query($con, "SELECT * FROM overallscores WHERE meet='" . $id . "' AND level = '" . $l . "'");
                                    echo "<h4>" . $teams[$l] . "</h4>";
                                    echo "<table class='table table-sm'>";
                                    echo "<thead><tr><th>Place</th><th>School</th><th>Score</th></tr></thead>";
                                    while ($row = mysqli_fetch_array($result)) {
                                        if ($row['school'] == "Glenbrook South" or $row['school'] == "Glenview (Glenbrook South)") {
                                            echo "<tr class='row-highlight'>";
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . $row['place'] . "</td>";
                                        echo "<td>" . $row['school'] . "</td>";
                                        echo "<td>" . $row['score'] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                    if (mysqli_num_rows($result) <= 0) {
                                        echo "<p class='card-text'><strong>This is either an unscored meet, or team results are missing from our database. If you believe this is an error, please reach out.</strong></p>";
                                    }
                                    if (strpos($row['score'], '-') !== false) {
                                        $quad = 1;
                                    }
                                }
                                if ($quad == 1) {
                                    echo "<p>This meet is a scored team meet, where each team is scored against each other. This is why you see a record instead of a score.</p>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dscores" role="tabpanel" aria-labelledby="dscores-tab">
                            <?php
                            echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                            echo "<h2>Distance Team Scores</h2>";
                            echo "<div class='d-none d-md-block'>";
                            echo "<div class='form-check form-switch'>";
                            echo "<input type='checkbox' class='form-check-input' onChange='showHighlight(this.checked)' id='highlightSwitch' checked>";
                            echo "<label class='custom-control-label' for='highlightSwitch'>Toggle Highlight</label>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            ?>
                            <p><strong>Team scores only for Distance Events (3200m,1600m,800m,4x800m) using <a data-bs-toggle="tooltip" data-bs-placement="top" title="10-8-6-4-2-1">IHSA State Series</a> scoring.</strong></p>
                            <?php foreach ($meetLevels as $l) {
                                $result = mysqli_query($con, "SELECT school, COUNT(*)  FROM overalltf WHERE meet=" . $id . " AND level = " . $l . " GROUP BY school");
                                while ($row = mysqli_fetch_array($result)) {
                                    $scores[$row['school']] = 0;
                                    $math[$row['school']] = [];
                                }
                                $result = mysqli_query($con, "SELECT * FROM overalltf WHERE meet='" . $id . "' AND level = " . $l . " AND (event = '3200m' OR event = '1600m' OR event = '800m' OR event = '4x800m') AND place IS NOT NULL");
                                while ($row = mysqli_fetch_array($result)) {
                                    $place = $row['place'];
                                    if ($place == 1) {
                                        $score = 10;
                                    } elseif ($place == 2) {
                                        $score = 8;
                                    } elseif ($place == 3) {
                                        $score = 6;
                                    } elseif ($place == 4) {
                                        $score = 4;
                                    } elseif ($place == 5) {
                                        $score = 2;
                                    } elseif ($place == 6) {
                                        $score = 1;
                                    } else {
                                        $score = 0;
                                    }
                                    $scores[$row['school']] += $score;
                                    if ($score > 0) {
                                        $math[$row['school']][] = $score;
                                    }
                                }
                                arsort($scores);
                                echo "<h4>" . $teams[$l] . "</h4>";
                                echo "<table class='table table-sm'>";
                                echo "<thead><tr><th>Place</th><th>School</th><th>Score</th></tr></thead>";
                                $n = 1;
                                foreach ($scores as $s => $p) {
                                    if ($s == "Glenbrook South" or $s == "Glenview (Glenbrook South)") {
                                        echo "<tr class='row-highlight'>";
                                    } else {
                                        echo "<tr>";
                                    }
                                    echo "<td>" . $n . "</td>";
                                    echo "<td>" . $s . "</td>";
                                    echo "<td data-bs-toggle='tooltip' data-bs-placement='left' title='" . join(" + ", $math[$s]) . " = " . $p . "'>" . $p . "</td>";
                                    echo "</tr>";
                                    $n += 1;
                                }
                                echo "</table>";
                            } ?>
                        </div>
                        <div class="tab-pane fade" id="venue" role="tabpanel" aria-labelledby="venue-tab">
                            <?php
                            $result = mysqli_query($con, "SELECT * FROM locations WHERE name='" . $location . "'");
                            if (mysqli_num_rows($result) !== 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    if ($location == "John Davis Titan Stadium") {
                                        include $_SERVER['DOCUMENT_ROOT'] . "/includes/venues/stadium.php";
                                    } elseif ($location == "David Pasquini Fieldhouse") {
                                        include $_SERVER['DOCUMENT_ROOT'] . "/includes/venues/fieldhouse.php";
                                    } elseif ($location == "Glenbrook South High School") {
                                        include $_SERVER['DOCUMENT_ROOT'] . "/includes/venues/xccourse.php";
                                    } else {
                                        $geojson = $row['geojson'];

                                        echo "<h2>" . $location . "</h2>";

                                        if (!empty($row['xccourse']) && $sport == "xc") {
                                            echo "<img class='img-fluid' src='/assets/images/course-maps/" . $row['xccourse'] . "'>";
                                            echo "<hr>";
                                        }
                                        if (!empty($row['coordinates'])) {
                                            echo "<div id='coursemap'></div>";
                                            echo "<script>
                            mapboxgl.accessToken = '" .
                                                $mapboxapikey .
                                                "';
                        var map = new mapboxgl.Map({
                        container: 'coursemap', // container id
                        style: 'mapbox://styles/jkurtzweil2/ckgacb5xw02fp19r16el5q4xc', // style URL
                        center: [" .
                                                $row['coordinates'] .
                                                "], // starting position [lng, lat]
                        zoom: 15 // starting zoom
                        });

                        var marker = new mapboxgl.Marker()
                            .setLngLat([" .
                                                $row['coordinates'] .
                                                "])
                        .addTo(map);";
                                            echo "</script>";
                                        }
                                        if (!empty($row['gmap'])) {
                                            echo "<div class='text-center'>";
                                            echo "<a class='btn btn-primary mt-2' href='https://www.google.com/maps/search/?api=1&query=" .
                                                $row['name'] .
                                                "&query_place_id=" .
                                                $row['gmap'] .
                                                "' role='button' target='_blank'>Open in Google Maps</a>";
                                            if (!empty($row['osm'])) {
                                                echo "<a class='btn btn-primary mt-2 ms-2' href='https://openstreetmap.org/way/" . $row['osm'] . "' role='button' target='_blank'>Open in OpenStreetMap</a>";
                                            }
                                            echo "</div>";
                                        } else {
                                            echo "<div class='text-center'>";
                                            echo "<a class='btn btn-primary mt-2' href='https://maps.google.com/?q=" . addslashes($location) . "' role='button' target='_blank'>Open in Google Maps</a>";
                                            echo "</div>";
                                        }

                                        //Top Times
                                        if ($sport == "xc") {
                                            echo "<hr>";
                                            echo "<h4>Top " . $row['primarydistance'] . " Times by GBS Athletes";

                                            if ($row['verified'] == 1) {
                                                echo "<i class=\"ms-4 text-success bi bi-patch-check-fill\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Verified as Accurate & Updated\"></i>";
                                            } else {
                                                echo "<i class=\"ms-4 text-warning bi bi-patch-exclamation-fill\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Some Times May be Missing\"></i>";
                                            }
                                            echo "</h4>";
                                            if (!empty($row['startdate'])) {
                                                $result2 = mysqli_query($con, "SELECT * FROM meets WHERE Location = '" . addslashes($location) . "' ORDER BY Date DESC");
                                            } else {
                                                $result2 = mysqli_query($con, "SELECT * FROM meets WHERE Location = '" . addslashes($location) . "' AND Date >= '" . $row['startdate'] . "' ORDER BY Date DESC");
                                            }
                                            $meets2 = [];
                                            while ($row2 = mysqli_fetch_array($result2)) {
                                                //array_push($meets2,"meet = ".$row2['id']);
                                                array_push($meets2, $row2['id']);
                                            }


                                            echo "
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-condensed table-sm table-hover\">
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Athlete</th>
                                                    <th>Year</th>
                                            </thead>
                                            <tbody>";
                                            $duplicates = [];
                                            $num = 0;
                                            //$result2 = mysqli_query($con,"SELECT * FROM overallxc"." WHERE (".join($meets2," OR ").") AND distance = '".$row['primarydistance']."' AND school = 'Glenbrook South' ORDER BY time ASC LIMIT 50");
                                            $result2 = mysqli_query($con, "SELECT * FROM overallxc" . " WHERE meet IN (" . join(",", $meets2) . ") AND distance = '" . $row['primarydistance'] . "' AND school = 'Glenbrook South' ORDER BY time ASC LIMIT 50");

                                            while ($row2 = mysqli_fetch_array($result2)) {
                                                if (in_array($row2['name'], $duplicates) or $num >= 10) {
                                                    continue;
                                                }
                                                echo "<tr>";
                                                echo "<td>" . $row2["time"] . "</td>";
                                                if (!empty($row2['profile'])) {
                                                    echo "<td><a href='/athlete/" . $row2['profile'] . "'>" . $row2["name"] . "</a></td>";
                                                } else {
                                                    echo "<td>" . $row2["name"] . "</td>";
                                                }
                                                echo "<td><a href='/meet/" . $row2['meet'] . "'>" . date("Y", strtotime($row2['date'])) . "</a></td>";
                                                echo "</tr>";
                                                $duplicates[] = $row2['name'];
                                                $num += 1;
                                            }

                                            echo "                                            </tbody>
                                        </table>
                                    </div>";
                                        }
                                        echo " <h4>Previous GBS Appearances</h4>
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-condensed table-sm table-hover\">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Season</th>
                                            </thead>
                                            <tbody>";

                                        $result = mysqli_query($con, "SELECT * FROM meets WHERE Location = '" . addslashes($location) . "' ORDER BY Date DESC");
                                        while ($row = mysqli_fetch_array($result)) {
                                            //Badge
                                            if (!empty($row['Badge'])) {
                                                if ($row['Badge'] == 1) {
                                                    $badge = " <span class='badge bg-csl' data-bs-toggle='tooltip' data-bs-placement='top' title='Central Suburban League'>CSL</span>";
                                                } else if ($row['Badge'] == 2) {
                                                    $badge = " <span class='badge bg-ihsa' data-bs-toggle='tooltip' data-bs-placement='top' title='Illinois High School Association'>IHSA</span>";
                                                } else if ($row['Badge'] == 3) {
                                                    $badge = " <span class='badge bg-info' data-bs-toggle='tooltip' data-bs-placement='top' title='Time Trial'>TT</span>";
                                                }
                                            } else {
                                                $badge = "";
                                            }
                                            echo "<tr>";
                                            echo "<td>" . $row["Date"] . "</td>";
                                            echo "<td><a href='/meet/" . $row['id'] . "'>" . $row['Name'] . $badge . "</a></td>";
                                            echo "<td>" . $row["Season"] . "</td>";
                                            echo "</tr>";
                                        }

                                        echo "                                            </tbody>
                                        </table>
                                    </div>";
                                    }
                                }
                            } else {
                                echo "<h2>" . $location . "</h2>";
                                echo "<div class='embed-responsive embed-responsive-4by3'>";
                                echo "<iframe class='embed-responsive-item' width='600' height='450' src='https://www.google.com/maps/embed/v1/search?key=" . $gmapapikey . "&q=" . $location . "' allowfullscreen></iframe>";
                                echo "</div>";
                            }
                            ?>
                        </div>

                        <div class="tab-pane fade" id="special" role="tabpanel" aria-labelledby="special-tab">
                            <?php if ($series == "titan") {
                                include $_SERVER['DOCUMENT_ROOT'] . "/includes/titanrecords.php";
                            } ?>
                        </div>


                        <div class="tab-pane fade" id="photos" role="tabpanel" aria-labelledby="photos-tab">
                            <h2>Photos</h2>
                            <?php
                            echo "<div class='row row-cols-1 row-cols-md-2'>";
                            $result = mysqli_query($con, "SELECT * FROM photos WHERE meet='" . $id . "'");
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<div class='col mb-2'>";
                                echo "<a class='card text-center hover-card text-reset' href='" . $row['link'] . "'>";
                                echo "<img src='/assets/images/meets/" . $row['cover'] . "' class='card-img-top'>";
                                echo "<div class='card-body'>";
                                echo "<p class='card-text'>Photographer(s): " . $row['credits'] . "</p>";
                                echo "</div>";
                                echo "</a>";
                                echo "</div>";
                            }
                            echo "</div>";
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //INDIVIDUAL RESULTS
    var events = {
        "3200m": "3200m Run",
        "1600m": "1600m Run",
        "1000m": "1000m Run",
        "800m": "800m Run",
        "400m": "400m Dash",
        "300mIH": "300m Intermediate Hurdles",
        "300m": "300m Dash",
        "200m": "200m Dash",
        "160m": "160m Dash",
        "110mHH": "110m High Hurdles",
        "110m IH": "110m Intermediate Hurdles",
        "100m": "100m Dash",
        "60m": "60m Dash",
        "60mHH": "60m High Hurdles",
        "50mLH": "50m Low Hurdles",
        "55mIH": "55m Intermediate Hurdles",
        "55mHH": "55m High Hurdles",
        "55mLH": "55m Low Hurdles",
        "55m": "55m Dash",
        "50m": "50m Dash",
        "SP": "Shot Put",
        "DS": "Discus",
        "HJ": "High Jump",
        "PV": "Pole Vault",
        "LJ": "Long Jump",
        "TJ": "Triple Jump",
        "4x1600m": "4x1600m Relay",
        "4x800m": "4x800m Relay",
        "4x400m": "4x400m Relay",
        "4x240m": "4x2 Lap Relay",
        "4x200m": "4x200m Relay",
        "4x160m": "4x1 Lap Relay",
        "4x100m": "4x100m Relay",
        "DMR": "Distance Medley Relay",
        "SMR": "Sprint Medley Relay"
    };

    var levels = {
        1: "Varsity",
        2: "Junior Varsity",
        3: "Sophomore",
        4: "Freshmen",
        5: "Frosh/Soph",
        6: "Open",
        7: "Junior Varsity 2"
    }

    var response, results;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            response = this.responseText;
            results = JSON.parse(response);
            <?php
            if ($sport == "xc" && count($meetLevels) == 1) {
                echo "showXCResults(" . $meetLevels[0] . ");\n";
                echo "document.getElementById(\"selectEvent\").value=" . $meetLevels[0] . ";\n";
                echo "document.getElementById(\"selectEvent\").classList.add(\"d-none\");\n";
            }
            ?>

        }
    };
    var url = "/api/results.php?id=<?php echo $id; ?>"
    xhttp.open("GET", url, true);
    xhttp.send();

    var indresultsContainer = document.getElementById("indresultsContainer");
    var INDVhighlightSwitch = document.getElementById("INDVhighlightSwitch");

    function showTFResults(raw) {
        var tables = [];

        if (raw == "all") {
            tables.push(<?php echo implode(",", $meetEvents); ?>)
        } else {
            tables.push(raw)
        }

        indresultsContainer.innerHTML = ""
        tables.forEach(generateTFTables)
        indresultsContainer.innerHTML += "<button type=\"button\" class=\"btn btn-secondary btn-sm\" onClick=\"printindResults()\"><i class=\"bi bi-printer-fill me-1\"></i>Print Results</button>"
        activateTooltips()
    }

    function generateTFTables(single) {
        // console.log(single)
        single = single.split("-")
        var event = single[0]
        var level = single[1]

        let table = "<table class='table table-sm table-striped' id='" + single + "'>";
        table += "<thead><tr><th>Place</th><th>Name</th><th>Grade</th><th>Result</th><th>Team</th></tr></thead>";
        table += "<tbody>";

        // var relay, relaydistance;

        // if (event.includes("4x")) {
        //     relay = true;
        //     relaydistance = event.replace("4x", "")
        // } else {
        //     relay = false;
        //     relaydistance = null;
        // }

        for (let x in results) {
            // if ((relay == false && results[x].level == level && results[x].event == event && results[x].type !== "R") || (
            //         relay == true &&
            //         results[x].level == level && (results[x].event == event || (results[x].event == relaydistance &&
            //             results[x].type == "R")))) {
            if (results[x].level == level && results[x].event == event && (results[x].relay == null || results[x].name == "RELAY")) {
                var gbs;
                if (results[x].school == "Glenbrook South" || results[x].school == "Glenbrook South*") {
                    gbs = true;
                } else {
                    gbs = false;
                }

                if (gbs == true) {
                    if (INDVhighlightSwitch.checked == true) {
                        table += "<tr class='row-highlight' data-td-resultid='" + results[x].id + "'>";
                    } else {
                        table += "<tr class='row-nohighlight' data-td-resultid='" + results[x].id + "'>";
                    }
                } else {
                    table += "<tr data-td-resultid='" + results[x].id + "'>";
                }

                if (results[x].place == null) {
                    table += "<th></th>";
                } else {
                    table += "<th>" + results[x].place + "</th>";
                }

                if (results[x].name == "RELAY") {
                    table += "<th>Relay Team</th>";
                } else if (results[x].profile !== null) {
                    table += "<th><a href='/athlete/" + results[x].profile + "'>" + results[x].name + "</a></th>";
                } else {
                    table += "<th>" + results[x].name + "</th>";
                }

                if (results[x].grade == 12) {
                    table += "<td>Sr.</td>";
                } else if (results[x].grade == 11) {
                    table += "<td>Jr.</td>";
                } else if (results[x].grade == 10) {
                    table += "<td>So.</td>";
                } else if (results[x].grade == 9) {
                    table += "<td>Fr.</td>";
                } else if (results[x].grade !== null) {
                    table += "<td>" + results[x].grade + "</td>";
                } else {
                    table += "<td></td>";
                }
                if (results[x].result.slice(-1) == "m") {
                    m = parseFloat(results[x].result.slice(0, -1))
                    var inches = (m * 39.3700787).toFixed(0);
                    var feet = Math.floor(inches / 12);
                    inches %= 12;
                    table += "<td data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"" + feet + "'-" + inches + "\">";
                } else {
                    table += "<td>";
                }
                if (results[x].result.substring(0, 2) == "0:") {
                    table += results[x].result.substring(2);
                } else if (results[x].result.substring(0, 1) == "0") {
                    table += results[x].result.substring(1);
                } else {
                    table += results[x].result;
                }
                if (results[x].pr == 1) {
                    table +=
                        "<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                } else if (results[x].sr == 1) {
                    table +=
                        "<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                }
                if (results[x].tags == "TQ") {
                    table +=
                        "<span class='badge bg-ihsa ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Team Qualifier'>TQ</span>";
                } else if (results[x].tags == "IQ") {
                    table +=
                        "<span class='badge bg-ihsa ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Individual Qualifier'>IQ</span>";
                } else if (results[x].tags == "All-Conf") {
                    table +=
                        "<span class='badge bg-csl ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='All Conference'>All-Conf</span>";
                } else if (results[x].tags !== null) {
                    table += "<span class='badge bg-primary ms-1'>" + results[x].tags + "</span>";
                }
                table += "</td>";

                table += "<td>" + results[x].school + "</td>";
                table += "</tr>"
                if (results[x].relay !== null) {
                    table += addRelayRows(results[x].relay)
                }
            }
        }
        table += "</tbody>";
        table += "</table>";

        if (events[event]) {
            indresultsContainer.innerHTML += "<h4>" + levels[level] + " " + events[event] + "</h4>" + table
        } else {
            indresultsContainer.innerHTML += "<h4>" + levels[level] + " " + event + "</h4>" + table
        }
    }

    function addRelayRows(relayNo) {
        table = "";

        for (let x in results) {
            if (results[x].relay == relayNo && results[x].name !== "RELAY") {

                if (INDVhighlightSwitch.checked == true) {
                    table += "<tr class='row-highlight'>";
                } else {
                    table += "<tr class='row-nohighlight'>";
                }

                table += "<th></th>";

                if (results[x].profile !== null) {
                    table += "<td><a href='/athlete/" + results[x].profile + "'>" + results[x].name + "</a></td>";
                } else {
                    table += "<td>" + results[x].name + "</td>";
                }

                if (results[x].grade == 12) {
                    table += "<td>Sr.</td>";
                } else if (results[x].grade == 11) {
                    table += "<td>Jr.</td>";
                } else if (results[x].grade == 10) {
                    table += "<td>So.</td>";
                } else if (results[x].grade == 9) {
                    table += "<td>Fr.</td>";
                } else if (results[x].grade !== null) {
                    table += "<td>" + results[x].grade + "</td>";
                } else {
                    table += "<td></td>";
                }

                table += "<td>";

                if (results[x].result.substring(0, 2) == "0:") {
                    table += results[x].result.substring(2);
                } else if (results[x].result.substring(0, 1) == "0") {
                    table += results[x].result.substring(1);
                } else {
                    table += results[x].result;
                }
                if (results[x].pr == 1) {
                    table +=
                        "<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                } else if (results[x].sr == 1) {
                    table +=
                        "<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                }
                if (results[x].tags == "TQ") {
                    table +=
                        "<span class='badge bg-ihsa ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Team Qualifier'>TQ</span>";
                } else if (results[x].tags == "IQ") {
                    table +=
                        "<span class='badge bg-ihsa ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Individual Qualifier'>IQ</span>";
                } else if (results[x].tags == "All-Conf") {
                    table +=
                        "<span class='badge bg-csl ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='All Conference'>All-Conf</span>";
                } else if (results[x].tags !== null) {
                    table += "<span class='badge bg-primary ms-1'>" + results[x].tags + "</span>";
                }
                table += "</td>";

                table += "<td>" + results[x].school + "</td>";
                table += "</tr>"
            }
        }
        return (table)
    }

    function showXCResults(raw) {
        var tables = [];

        if (raw == "all") {
            tables.push(<?php echo implode(",", $meetEvents); ?>)
        } else {
            tables.push(raw)
        }

        indresultsContainer.innerHTML = ""
        tables.forEach(generateXCTables)
        indresultsContainer.innerHTML += "<button type=\"button\" class=\"btn btn-secondary btn-sm\" onClick=\"printindResults()\"><i class=\"bi bi-printer-fill me-1\"></i>Print Results</button>"
    }

    function generateXCTables(level) {
        let table = "<table class='table table-sm table-striped' id='" + levels[level].toLowerCase().replace(" ", "_") + "Table'>";
        table += "<thead><tr><th>Place</th><th>Name</th><th>Grade</th><th>Time</th><th>Team</th></tr></thead>";
        table += "<tbody>";
        for (let x in results) {
            var distance;
            if (results[x].level == level) {
                var gbs;
                if (results[x].school == "Glenbrook South" || results[x].school == "Glenbrook South*") {
                    gbs = true;
                } else {
                    gbs = false;
                }

                if (gbs == true) {
                    if (INDVhighlightSwitch.checked == true) {
                        table += "<tr class='row-highlight'>";
                    } else {
                        table += "<tr class='row-nohighlight'>";
                    }
                } else {
                    table += "<tr>";
                }

                if (results[x].place == null) {
                    table += "<th></th>";
                } else {
                    table += "<th>" + results[x].place + "</th>";
                }

                if (results[x].profile !== null) {
                    table += "<th><a href='/athlete/" + results[x].profile + "'>" + results[x].name + "</a></th>";
                } else {
                    table += "<th>" + results[x].name + "</th>";
                }

                if (results[x].grade == 12) {
                    table += "<td>Sr.</td>";
                } else if (results[x].grade == 11) {
                    table += "<td>Jr.</td>";
                } else if (results[x].grade == 10) {
                    table += "<td>So.</td>";
                } else if (results[x].grade == 9) {
                    table += "<td>Fr.</td>";
                } else if (results[x].grade !== null) {
                    table += "<td>" + results[x].grade + "</td>";
                } else {
                    table += "<td></td>";
                }

                table += "<td>";
                table += results[x].time;
                if (results[x].pr == 1) {
                    table +=
                        "<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                } else if (results[x].sr == 1) {
                    table +=
                        "<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                }
                if (results[x].tags == "TQ") {
                    table +=
                        "<span class='badge bg-ihsa ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Team Qualifier'>TQ</span>";
                } else if (results[x].tags == "IQ") {
                    table +=
                        "<span class='badge bg-ihsa ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Individual Qualifier'>IQ</span>";
                } else if (results[x].tags == "All-Conf") {
                    table +=
                        "<span class='badge bg-csl ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='All Conference'>All-Conf</span>";
                } else if (results[x].tags !== null) {
                    table += "<span class='badge bg-primary ms-1'>" + results[x].tags + "</span>";
                }
                table += "</td>";

                table += "<td>" + results[x].school + "</td>";
                table += "</tr>"
                distance = " (" + results[x].distance.replace("mi", " miles") + ")";
            }
        }
        table += "</tbody>";
        table += "</table>";

        indresultsContainer.innerHTML += "<h4>" + levels[level] + " Results" + distance + "</h4>" + table
        // new simpleDatatables.DataTable("#" + levels[level].toLowerCase().replace(" ", "_") + "Table", {
        //     searchable: true,
        //     fixedHeight: true,
        //     "perPageSelect": false,
        //     "perPage": 1000
        // })
        activateTooltips()
    }

    function printindResults() {
        var divContents = document.getElementById("indresultsContainer").innerHTML;
        var meetInfo = document.getElementById("meetInfo").innerHTML;
        var meetName = document.getElementById("meetName").innerHTML;
        var a = window.open('', '', 'height=2100, width=800');
        a.document.write('<html>');
        a.document.write('<head><title>' + meetName + ' Results</title><style>.badge {display:none;} a {text-decoration: none; color: inherit;} button {display:none;} table {width:100%;text-align: center;} h4 {text-align: center; font-size: 18px;}</style></head>');
        a.document.write('<body onafterprint="window.close()"><img src="https://titandistance.com/assets/logos/color.svg" onload="window.print()" style="display: block;margin-left: auto;margin-right: auto;width: 40%;" alt="Titan Distance"><pre>');
        a.document.write('<h2 style="text-align: center;">' + meetName + '</h2>')
        a.document.write(divContents);
        a.document.write('<p style="text-align:center; margin-top: 15px;">View Results Online: ' + document.location.href + '</p>')
        a.document.write('</pre></body></html>');
        a.document.close();
    }

    function printScores() {
        var divContents = document.getElementById("scoresContainer").innerHTML;
        var meetName = document.getElementById("meetName").innerHTML;
        var a = window.open('', '', 'height=2100, width=800');
        a.document.write('<html>');
        a.document.write('<head><title>' + meetName + ' Team Scores</title><style>.badge {display:none;} a {text-decoration: none; color: inherit;} button {display:none;} table {width:100%;text-align: center;} h4 {text-align: center; font-size: 18px;}</style></head>');
        a.document.write('<body onafterprint="window.close()"><img src="https://titandistance.com/assets/logos/color.svg" onload="window.print()" style="display: block;margin-left: auto;margin-right: auto;width: 40%;" alt="Titan Distance"><pre>');
        a.document.write('<h2 style="text-align: center;">' + meetName + ' Team Scores</h2>')
        a.document.write(divContents);
        a.document.write('</pre></body></html>');
        a.document.close();
    }

    document.getElementById("selectTab").addEventListener("change", function() {
        selectTab();
    });

    var activetab;
    var tabEl = document.getElementById("v-pills-tab");
    tabEl.addEventListener('shown.bs.tab', function(event) {
        // if (map) {
        //     map.resize();
        // }
        window.location.hash = event.target.id.replace("-tab", "")
        activetab = event.target.id;
    })

    document.addEventListener('keydown', function(event) {
        if (event.key === 'p' && event.ctrlKey && activetab == "results-tab") {
            printindResults();
        }
        if (event.key === 'p' && event.ctrlKey && activetab == "scores-tab") {
            printScores();
        }
    });

    window.onload = function() {
        if (window.location.hash) {
            tab = window.location.hash + "-tab"
            var someTabTriggerEl = document.querySelector(tab)
            var tabt = new bootstrap.Tab(someTabTriggerEl)
            tabt.show()
            document.getElementById("selectTab").value = window.location.hash.replace("#", "");
        }
    };

    function selectTab(str) {
        var dropdown = document.getElementById('selectTab');
        tab = "#" + dropdown.options[dropdown.selectedIndex].value + "-tab";
        if (tab.includes("link-") == false) {
            var someTabTriggerEl = document.querySelector(tab)
            var tabt = new bootstrap.Tab(someTabTriggerEl)
            tabt.show()
        } else {
            tab = tab.replace("-tab", "")
            var link = tab.substring(6);
            if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
                window.location.assign(link);
            } else {
                window.open(link, "_blank")
            }
        }
    }

    function showHighlight(c) {
        if (c == true) {
            var rows = document.querySelectorAll(".row-nohighlight");
            for (var d = 0; d < rows.length; d++) {
                rows[d].classList.replace("row-nohighlight", "row-highlight");
            }
        } else if (c == false) {
            var rows = document.querySelectorAll(".row-highlight");
            for (var d = 0; d < rows.length; d++) {
                rows[d].classList.replace("row-highlight", "row-nohighlight");
            }
        }

    }
    map.on('style.load', function() {
        <?php
        if (!empty($geojson)) {
            echo "map.addSource(\"course\", {
        type: 'geojson',
        data: '/assets/geojson/" . $geojson . ".geojson'
    });

    map.addLayer({
        id: \"course\",
        source: \"course\",
        type: 'line',
        'paint': {
            'line-width': 5,
            'line-color': '#073763',
            'line-opacity': 0.9
        }
    });";
        }
        ?>
    })
</script>
<?php include "footer.php"; ?>