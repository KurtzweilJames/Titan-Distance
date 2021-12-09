<?php include "db.php"; ?>
<?php
$id = htmlspecialchars($_GET["id"]);
$template = "meet";
if (strpos($id, '/') !== false) {
    list($slug, $year) = explode("/", $id, 2);
    $result = mysqli_query($con, "SELECT * FROM meets WHERE Date LIKE '" . $year . "-%' AND Slug = '" . $slug . "'");
} else {
    $result = mysqli_query($con, "SELECT * FROM meets WHERE id='" . $id . "'");
}
if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound?from=meets&url='.$id);
    exit();
}
while ($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
    if (!empty($row['Series']) && empty($slug)) {
        $year = date("Y", strtotime($row['Date']));
        $result = mysqli_query($con, "SELECT * FROM series WHERE id='" . $row['Series'] . "'");
        while ($row = mysqli_fetch_array($result)) {
            $slug = $row['slug'];
        }
        http_response_code(301);
        header('Location: https://titandistance.com/meet/' . $slug . "/" . $year);
    } //Page Title
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
    $official = $row['Official']; //Two Day
    if (!empty($row['Day2Time'])) {
        $date = $date . " -<br>" . date("l, F d, Y", strtotime($row['Day2Time']));
    } //Sport
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
    /*
    if (array_key_exists($row['Badge'], $badges)) {
        $badge = "<span class='ml-1 badge " . $badges[$row['Badge']][0] . "'>" . $badges[$row['Badge']][1] . "</span>";
    } else {
        $badge = "";
    }
    */
} //Photos
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
    $slug = $row['slug'];
}

if(empty($image)) {
    $result = mysqli_query($con, "SELECT * FROM photos WHERE meet='" . $id . "' LIMIT 1");
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $image = "meets/".$row['cover'];
        }
    }
}
include "header.php";
?>
<div class="container mt-4 mb-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card" style="height: 100%;">
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
                    <h4><?php echo $name . $badge; ?></h4>
                    <h5 class="mb-0"><i class="bi bi-calendar-fill me-1"></i><?php echo $date; ?></h5>
                    <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-1"></i><?php echo $location; ?></h5>
                    <?php
                        if(!empty($weather)) {
                            echo "<h5 class='mb-0'><i class='bi bi-cloud-sun-fill me-1'></i>".$weather."</h5>";
                        }
                    ?>
                    <hr class="mt-2">
                    <div class="nav flex-column nav-pills d-none d-md-block" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <?php
                        if ($prepost == "pre") {
                            echo "<a class='nav-link active' id='news-tab' data-bs-toggle='pill' data-bs-target='#news' role='tab' aria-controls='news-tab' aria-selected='true'><i class='bi bi-newspaper me-1'></i>Meet Information</a>";
                            $dropdown[] = "<option value='news' name='news'>Meet Information</option>";
                        } elseif ($prepost == "post") {
                            echo "<a class='nav-link active' id='news-tab' data-bs-toggle='pill' data-bs-target='#news' role='tab' aria-controls='news-tab' aria-selected='true'><i class='bi bi-newspaper me-1'></i>Meet Recap</a>";
                            $dropdown[] = "<option value='news' name='news'>Meet Recap</option>";
                        }
                        if ($sport == "xc") {
                            $result = mysqli_query($con, "SELECT DISTINCT level FROM overallxc WHERE meet = '" . $id . "' ORDER BY level ASC");
                            while ($row = mysqli_fetch_array($result)) {
                                $levelnum = $row['level'];
                                echo "<a class='nav-link' id='" .
                                    $abbreviations[$levelnum] .
                                    "-tab' data-bs-toggle='pill' data-bs-target='#" .
                                    $abbreviations[$levelnum] .
                                    "-results' role='tab' aria-controls='" .
                                    $abbreviations[$levelnum] .
                                    "-tab' aria-selected='false'>" . "<i class='bi bi-list-ul me-1'></i>" .
                                    $teams[$levelnum] .
                                    " Results</a>";
                                $meetlevels[] = $levelnum;
                                $dropdown[] = "<option value='" . $abbreviations[$levelnum] . "' name='" . $abbreviations[$levelnum] . "'>" . $teams[$levelnum] . " Results</option>";
                            }
                        }
                        if ($sport == "tf") {
                            $result = mysqli_query($con, "SELECT DISTINCT level FROM overalltf WHERE meet = '" . $id . "' ORDER BY level ASC");
                            while ($row = mysqli_fetch_array($result)) {
                                $levelnum = $row['level'];
                                echo "<a class='nav-link' id='" .
                                    $abbreviations[$levelnum] .
                                    "-tab' data-bs-toggle='pill' data-bs-target='#" .
                                    $abbreviations[$levelnum] .
                                    "-results' role='tab' aria-controls='" .
                                    $abbreviations[$levelnum] .
                                    "-tab' aria-selected='false'>" . "<i class='bi bi-list-ul me-1'></i>" .
                                    $teams[$levelnum] .
                                    " Results</a>";
                                $meetlevels[] = $levelnum;
                                $dropdown[] = "<option value='" . $abbreviations[$levelnum] . "' name='" . $abbreviations[$levelnum] . "'>" . $teams[$levelnum] . " Results</option>";
                            }
                        }
                        $result = mysqli_query($con, "SELECT DISTINCT school FROM overalltf WHERE meet = '" . $id . "'");
                        if ($prepost == "post" && $sport == "tf" && mysqli_num_rows($result) > 1) {
                            echo "<a class='nav-link' id='dscores-tab' data-bs-toggle='pill' data-bs-target='#dscores' role='tab' aria-controls='dscores-tab' aria-selected='false'><i class='bi bi-list-ol me-1'></i>Distance Scores</a>";
                            $dropdown[] = "<option value='dscores' name='dscores'>Distance Scores</option>";
                        }
                        $result = mysqli_query($con, "SELECT * FROM overallscores WHERE meet='" . $id . "'");
                        if ($prepost == "post" && mysqli_num_rows($result) > 0) {
                            echo "<a class='nav-link' id='scores-tab' data-bs-toggle='pill' data-bs-target='#scores' role='tab' aria-controls='scores-tab' aria-selected='false'><i class='bi bi-award-fill me-1'></i>Team Scores</a>";
                            $dropdown[] = "<option value='scores' name='scores'>Team Scores</option>";
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
                        if ($series == 11) {
                            echo "<a class='nav-link' id='special-tab' data-bs-toggle='pill' data-bs-target='#special' role='tab' aria-controls='special-tab' aria-selected='false'><i class='bi bi-award-fill me-1'></i>Titan Invite Records</a>";
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
                            echo "<a class='nav-link' id='ath-net' href='" . $athnet . "' role='tab' target='_blank'>Athletic.net Page <i class='bi bi-box-arrow-in-up-right'></i></a>";
                            $dropdown[] = "<option value='link-" . $athnet . "' name='athnet'>Athletic.net</option>";
                        }
                        if (!empty($website)) {
                            echo "<a class='nav-link' id='homepage' href='" . $website . "' role='tab' target='_blank'>Meet Homepage <i class='bi bi-box-arrow-in-up-right'></i></a>";
                            $dropdown[] = "<option value='link-" . $website . "' name='results'>Meet Homepage</option>";
                        }
                        if (!empty($results)) {
                            echo "<a class='nav-link' id='report' href='https://forms.gle/NQjahvTVmbNnsASo8' role='tab' target='_blank'>Request Correction <i class='bi bi-box-arrow-in-up-right'></i></a>";
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
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="news" role="tabpanel" aria-labelledby="news-tab">
                            <?php
                            if ($prepost == "pre") {
                                echo "<h2>Meet Information</h2>";
                            } elseif ($prepost == "post") {
                                if (!empty($title)) {
                                    echo "<h2><a href='/news/" . $slug . "'>" . $title . "</a></h2>";
                                } else {
                                    echo "<h2>Meet Recap</h2>";
                                }
                            }
                            echo $content;
                            if (empty($content)) {
                                echo "<p>No meet recap or information is available at this time. Please be sure to use the links on the left to view more about the meet.</p><p>If this meet has already occured, our results are missing or have not been processed into our database.</p>";
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="scores" role="tabpanel" aria-labelledby="scores-tab">
                            <?php
                                echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                                echo "<h1>Team Scores</h1>";
                                echo "<div class='d-none d-md-block'>";
                                echo "<div class='form-check form-switch'>";
                                echo "<input type='checkbox' class='form-check-input' onChange='showHighlight(this.checked)' id='highlightSwitch' checked>";
                                echo "<label class='custom-control-label' for='highlightSwitch'>Toggle Highlight</label>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";

                                if ($sport == "tf") {
                                   echo "<p><strong>Team scores for all events, including short-distance, field, etc.</strong></p>";
                                }
                            ?>
                            <?php
                            $quad = 0;
                            foreach ($meetlevels as $l) {
                                $result = mysqli_query($con, "SELECT * FROM overallscores WHERE meet='" . $id . "' AND level = '" . $l . "'");
                                echo "<h3>" . $teams[$l] . "</h3>";
                                echo "<table class='table table-sm'>";
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
                        <div class="tab-pane fade" id="dscores" role="tabpanel" aria-labelledby="dscores-tab">
                            <?php
                                echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                                echo "<h1>Distance Team Scores</h1>";
                                echo "<div class='d-none d-md-block'>";
                                echo "<div class='form-check form-switch'>";
                                echo "<input type='checkbox' class='form-check-input' onChange='showHighlight(this.checked)' id='highlightSwitch' checked>";
                                echo "<label class='custom-control-label' for='highlightSwitch'>Toggle Highlight</label>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            ?>
                            <p><strong>Team scores only for Distance Events (ones featured on results tabs). This is an
                                    automated process, so results
                                    should be treated as-is.</strong></p>
                            <?php foreach ($meetlevels as $l) {
                                $result = mysqli_query($con, "SELECT school, COUNT(*)  FROM overalltf WHERE meet=" . $id . " AND level = " . $l . " GROUP BY school");
                                while ($row = mysqli_fetch_array($result)) {
                                    $scores[$row['school']] = 0;
                                    $math[$row['school']] = [];
                                }
                                $result = mysqli_query($con, "SELECT * FROM overalltf WHERE meet='" . $id . "' AND level = " . $l . " AND place IS NOT NULL");
                                while ($row = mysqli_fetch_array($result)) {
                                    $place = $row['place'];
                                    if ($place == 1) {
                                        $score = 10;
                                    } elseif ($place == 2) {
                                        $score = 8;
                                    } elseif ($place == 3) {
                                        $score = 6;
                                    } elseif ($place == 4) {
                                        $score = 5;
                                    } elseif ($place == 5) {
                                        $score = 4;
                                    } elseif ($place == 6 && empty($row['relay'])) {
                                        $score = 3;
                                    } elseif ($place == 7 && empty($row['relay'])) {
                                        $score = 2;
                                    } elseif ($place == 8 && empty($row['relay'])) {
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
                                echo "<h3>" . $teams[$l] . "</h3>";
                                echo "<table class='table table-sm'>";
                                $n = 1;
                                foreach ($scores as $s => $p) {
                                    if ($s == "Glenbrook South" or $s == "Glenview (Glenbrook South)") {
                                        echo "<tr class='row-highlight'>";
                                    } else {
                                        echo "<tr>";
                                    }
                                    echo "<td>" . $n . "</td>";
                                    echo "<td>" . $s . "</td>";
                                    echo "<td data-bs-toggle='tooltip' data-bs-placement='left' title='".join(" + ",$math[$s]). " = ". $p ."'>" . $p . "</td>";
                                    echo "</tr>";
                                    $n += 1;
                                }
                                echo "</table>";
                            } ?>
                        </div>
                        <div class="tab-pane fade" id="venue" role="tabpanel" aria-labelledby="venue-tab">
                            <?php
                            $result = mysqli_query($con, "SELECT * FROM locations WHERE name='" . addslashes($location) . "'");
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    if ($location == "John Davis Titan Stadium") {
                                        include $_SERVER['DOCUMENT_ROOT'] . "/includes/venues/stadium.php";
                                    } elseif ($location == "David Pasquini Fieldhouse") {
                                        include $_SERVER['DOCUMENT_ROOT'] . "/includes/venues/fieldhouse.php";
                                    } elseif ($location == "Glenbrook South High School") {
                                        include $_SERVER['DOCUMENT_ROOT'] . "/includes/venues/xccourse.php";
                                    } else {
                                        $geojson = $row['geojson'];

                                        if ($sport == "xc") {
                                            echo "<h1>Course Information & Directions</h1>";
                                        } else {
                                            echo "<h1>Map & Directions</h1>";
                                        }
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
                                                    echo "<a class='btn btn-primary mt-2 ms-2' href='https://openstreetmap.org/way/" . $row['osm'] ."' role='button' target='_blank'>Open in OpenStreetMap</a>";
                                                }
                                            echo "</div>";
                                        } else {
                                            echo "<div class='text-center'>";
                                            echo "<a class='btn btn-primary mt-2' href='https://maps.google.com/?q=" . addslashes($location) . "' role='button' target='_blank'>Open in Google Maps</a>";
                                            echo "</div>";
                                        }

                                        //Top Times
                                    if ($sport == "xc") {
                                    echo"<hr>";
                                        echo"<h4>Top ".$row['primarydistance']." Times by GBS Athletes";
                                        
                                    if ($row['verified'] == 1) {
                                        echo "<i class=\"ms-4 text-success bi bi-patch-check-fill\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Verified as Accurate & Updated\"></i>";
                                    } else {
                                        echo "<i class=\"ms-4 text-warning bi bi-patch-exclamation-fill\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Some Times May be Missing\"></i>";
                                    }
                                    echo "</h4>";
                                    if(!empty($row['startdate'])) {
                                        $result2 = mysqli_query($con,"SELECT * FROM meets WHERE Location = '".addslashes($location)."' ORDER BY Date DESC");
                                    } else {
                                        $result2 = mysqli_query($con,"SELECT * FROM meets WHERE Location = '".addslashes($location)."' AND Date >= '".$row['startdate']."' ORDER BY Date DESC");
                                    }   
                                    $meets2 = [];
                                    while($row2 = mysqli_fetch_array($result2)) { 
                            array_push($meets2,"meet = ".$row2['id']);
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
                        $result2 = mysqli_query($con,"SELECT * FROM overallxc"." WHERE (".join($meets2," OR ").") AND distance = '".$row['primarydistance']."' AND school = 'Glenbrook South' ORDER BY time ASC LIMIT 50");
                        while($row2 = mysqli_fetch_array($result2)) {
                            if (in_array($row2['name'],$duplicates) OR $num >= 10) {
                                continue;
                            }
                            echo "<tr>";
                        echo "<td>".$row2["time"]."</td>";
                        if (!empty($row2['profile'])) {
                            echo "<td><a href='/athlete/".$row2['profile']."'>".$row2["name"]."</a></td>";
                        } else {
                            echo "<td>".$row2["name"]."</td>";
                        }
                        echo "<td><a href='/meet/".$row2['meet']."'>".date("Y",strtotime($row2['date']))."</a></td>";
                        echo "</tr>";
                        $duplicates[] = $row2['name'];
                        $num += 1;
                        }

echo"                                            </tbody>
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
                                    
                        $result = mysqli_query($con,"SELECT * FROM meets WHERE Location = '".addslashes($location)."' ORDER BY Date DESC");
                        while($row = mysqli_fetch_array($result)) {
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
                            echo "<td>".$row["Date"]."</td>";
                            echo "<td><a href='/meet/".$row['id']."'>".$row['Name'].$badge."</a></td>";
                            echo "<td>".$row["Season"]."</td>";
                            echo "</tr>";
                        }

echo"                                            </tbody>
                                        </table>
                                    </div>";
                                    }
                                }
                            } else {
                                if ($sport == "xc") {
                                    echo "<h1>Course Information</h1>";
                                } else {
                                    echo "<h1>Map & Directions</h1>";
                                }
                                echo "<div class='embed-responsive embed-responsive-4by3'>";
                                echo "<iframe class='embed-responsive-item' width='600' height='450' src='https://www.google.com/maps/embed/v1/search?key=" . $gmapapikey . "&q=" . $location . "' allowfullscreen></iframe>";
                                echo "</div>";
                            }
                            ?>
                        </div>

                        <div class="tab-pane fade" id="special" role="tabpanel" aria-labelledby="special-tab">
                            <?php if ($series == 11) {
                                include $_SERVER['DOCUMENT_ROOT'] . "/includes/titanrecords.php";
                            } ?>
                        </div>


                        <div class="tab-pane fade" id="photos" role="tabpanel" aria-labelledby="photos-tab">
                            <h1>Photos</h1>
                            <?php
                            echo "<div class='row row-cols-1 row-cols-md-2'>";
                            $result = mysqli_query($con, "SELECT * FROM photos WHERE meet='" . $id . "'");
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<div class='col mb-2'>";
                                echo "<div class='card clickable text-center' data-href='" . $row['link'] . "'>";
                                echo "<img src='/assets/images/meets/" . $row['cover'] . "' class='card-img-top'>";
                                echo "<div class='card-body'>";
                                echo "<p class='card-text'>Photographer(s): " . $row['credits'] . "</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                            echo "</div>";
                            ?>
                        </div>

                        <?php foreach ($meetlevels as $l) {
                            echo "<div class='tab-pane fade' id='" . $abbreviations[$l] . "-results' role='tabpanel' aria-labelledby='" . $abbreviations[$l] . "-tab'>";
                            if ($sport == "xc") {
                                $result = mysqli_query($con,"SELECT * FROM overallxc WHERE meet='" .$id ."' AND level = '" .$l ."' AND (school = 'Glenbrook South' OR school = 'Glenview (Glenbrook South)' OR school = 'Glenbrook South*') ORDER BY place IS NULL, place ASC LIMIT 1");
                                while ($row = mysqli_fetch_array($result)) {
                                    $distance = $row['distance'];
                                    if (!empty($row['split1'])) {
                                        $splits = 1;
                                    } else {
                                        $splits = 0;
                                    }
                                    if (!empty($row['place'])) {
                                        $places = 1;
                                    } else {
                                        $places = 0;
                                    }
                                }
                            } else if ($sport == "tf") {
                                $result = mysqli_query($con,"SELECT * FROM overalltf WHERE meet='" .$id ."' AND level = '" .$l ."' AND (school = 'Glenbrook South' OR school = 'Glenview (Glenbrook South)' OR school = 'Glenbrook South*') AND relay IS NULL ORDER BY place IS NULL, place ASC LIMIT 1");
                                while ($row = mysqli_fetch_array($result)) {  
                                    if (!empty($row['split1'])) {
                                        $splits = 1;
                                    } else {
                                        $splits = 0;
                                    }
                                    if (!empty($row['heat'])) {
                                        $heats = 1;
                                    } else {
                                        $heats = 0;
                                    }  
                                }
                                
                            }
                            echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                            if ($sport == "xc") {
                                echo "<h1>" . $teams[$l] . " Results (" . $distance . ")</h1>";
                            } else {
                                echo "<h1>" . $teams[$l] . " Results</h1>";
                            }
                            
                            echo "<div class='d-none d-md-block'>";
                            if ($splits == 1) {
                                echo "<div class='form-check form-switch'>";
                                echo "<input type='checkbox' class='form-check-input' onChange='showSplits(this.checked)' id='splitsSwitch' checked>";
                                echo "<label class='custom-control-label' for='splitsSwitch'>Toggle Splits</label>";
                                echo "</div>";
                            }
                            echo "<div class='form-check form-switch'>";
                            echo "<input type='checkbox' class='form-check-input' onChange='showHighlight(this.checked)' id='highlightSwitch' checked>";
                            echo "<label class='custom-control-label' for='highlightSwitch'>Toggle Highlight</label>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            if ($official == 1) {
                                echo "<span class='badge bg-success'>Official Results (F.A.T.)</span>";
                            } elseif ($official == 2) {
                                echo "<span class='badge bg-warning'>Official Results (Hand Timed)</span>";
                            } elseif ($official == 3) {
                                echo "<span class='badge bg-danger'>Unofficial Results</span>";
                            } elseif ($official == 4) {
                                echo "<span class='badge bg-primary'>Live Results</span>";
                            }
                            if ($sport == "xc") {
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-condensed table-sm table-striped dataTable' id='" . $abbreviations[$l] . "Results'>";
                                echo "<thead>
                        <tr>";
                                if ($places == 1) {
                                    echo "<th>Place</th>";
                                }
                                echo "<th>Name</th>
                        <th>Grade</th> <th>Time</th>
                        <th>Team</th>";
                                if ($splits == 1) {
                                    echo "<th class='splits-col'>1 Mile</th>";
                                    if ($distance == "2mi") {
                                        echo "<th class='splits-col'>Finish</th>";
                                    } else {
                                        echo "<th class='splits-col'>2 Mile</th>
                        <th class='splits-col'>Finish</th>";
                                    }
                                }
                                echo "</tr>
                        </thead>";
                                echo "<tbody>";
                                $result = mysqli_query($con, "SELECT * FROM overallxc WHERE meet='" . $id . "' AND level = '" . $l . "' ORDER BY place IS NULL, place ASC, time ASC");
                                while ($row = mysqli_fetch_array($result)) {
                                    if ($row['grade'] == 12) {
                                        $grade = "Sr.";
                                    } elseif ($row['grade'] == 11) {
                                        $grade = "Jr.";
                                    } elseif ($row['grade'] == 10) {
                                        $grade = "So.";
                                    } elseif ($row['grade'] == 9) {
                                        $grade = "Fr.";
                                    } else {
                                        $grade = $row['grade'];
                                    }
                                    if ($row['school'] == "Glenbrook South" or $row['school'] == "Glenview (Glenbrook South)" or $row['school'] == "Glenbrook South*") {
                                        echo "<tr class='row-highlight clickable-row' data-href='/athlete/" . $row['profile'] . "'>";
                                        if ($places == 1) {
                                            echo "<th>" . $row['place'] . "</th>";
                                        }
                                    } else {
                                        echo "<tr>";
                                        if ($places == 1) {
                                            echo "<th>" . $row['place'] . "</th>";
                                        }
                                    }
                                    if (!empty($row['profile'])) {
                                        echo "<th><a href='/athlete/" . $row['profile'] . "'>" . $row['name'] . "</a></th>";
                                    } else {
                                        echo "<th>" . $row['name'] . "</th>";
                                    }
                                    echo "<td>" . $grade . "</td>";
                                    $time = $row['time'];

                                    if($row['pr'] == 1) {
                                        $time = $time."<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                                    } else if ($row["sr"] == 1) {
                                        $time = $time."<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                                    }

                                    if($row['tags'] == "IQ") {
                                        $time = $time."<span class='badge bg-warning text-dark ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Individual Qualifier'>IQ</span>";
                                    } else if($row['tags'] == "TQ") {
                                        $time = $time."<span class='badge bg-warning text-dark ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Team Qualifier'>TQ</span>";
                                    } else if($row['tags'] == "All-Conf") {
                                        $time = $time."<span class='badge bg-csl ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='All Conference'>All-Conf</span>";
                                    } else if(!empty($row['tags'])) {
                                        $time = $time."<span class='badge bg-secondary text-light ms-1'>".$row['tags']."</span>";
                                    }
                                    echo "<th>" . $time . "</th>";
                                    echo "<td>" . $row['school'] . "</td>";
                                    if ($splits == 1) {
                                        echo "<td class='splits-col'>" . $row['split1'] . "</td>";
                                        echo "<td class='splits-col'>" . $row['split2'] . "</td>";
                                        if ($distance !== "2mi") {
                                            echo "<td class='splits-col'>" . $row['split3'] . "</td>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                echo "</div>";
                            } elseif ($sport == "tf") {
                                $fivesplits = 0;
                                $result = mysqli_query($con,"SELECT * FROM overalltf WHERE meet='".$id."' AND distance = '3200m' AND level = '".$l."' AND (school = 'Glenbrook South' OR school = 'Glenview (Glenbrook South)' OR school = 'Glenbrook South*') LIMIT 1");
                                while ($row = mysqli_fetch_array($result)) {
                                    if (!empty($row['split5'])) {
                                        $fivesplits = 1;
                                    }
                                }
                                $events = [];
                                $relays = [];
                                $result = mysqli_query($con, "SELECT DISTINCT distance,relay FROM overalltf WHERE meet = '" . $id . "' AND level = '" . $l . "'");
                                while ($row = mysqli_fetch_array($result)) {
                                    if (!in_array($row['distance'], $events) and !in_array($row['distance'], $relays)) {
                                    if (strpos($row['distance'], 'x') !== false or $row['distance'] == "DMR") {
                                        $relays[] = $row['distance'];
                                    } else if (empty($row['relay'])) {
                                        $events[] = $row['distance'];
                                    }
                                }
                                }

                                if(in_array("DMR",$relays)) {
                                    echo "<p>*There is a formatting error that occurs with the DMR event. While a solution is being worked on, please use the \"Downloadable Results\" tab to view the proper formatting.</p>";
                                }

                                //INDIVIDUAL EVENTS
                                foreach ($events as $d) {
                                    $result = mysqli_query($con, "SELECT * FROM overalltf WHERE meet='" . $id . "' AND level = '" . $l . "' AND relay IS NULL AND distance = '" . $d . "'");
                                    echo "<h5>" . $d . "</h5>";
                                    echo "<div class='table-responsive'>";
                                    echo "<table class='table table-sm table-striped'>";
                                    echo "<thead><tr>";
                                    echo "<th>Place</th>";
                                    if ($heats == 1) {
                                        echo "<th>Heat</th>";
                                    }
                                    echo "<th>Name</th><th>Grade</th><th>Time</th><th>Team</th>";
                                    if ($splits == 1) {
                                        if ($d == "800m") {
                                            echo "<th class='splits-col'>400m</th>";
                                            echo "<th class='splits-col'>800m</th>";
                                        }
                                        if ($d == "1600m") {
                                            echo "<th class='splits-col'>400m</th>";
                                            echo "<th class='splits-col'>800m</th>";
                                            echo "<th class='splits-col'>1200m</th>";
                                            echo "<th class='splits-col'>1600m</th>";
                                        }
                                        if ($d == "3200m" && $fivesplits == 1) {
                                            echo "<th class='splits-col'>400m</th>";
                                            echo "<th class='splits-col'>800m</th>";
                                            echo "<th class='splits-col'>1200m</th>";
                                            echo "<th class='splits-col'>1600m</th>";
                                            echo "<th class='splits-col'>2000m</th>";
                                            echo "<th class='splits-col'>2400m</th>";
                                            echo "<th class='splits-col'>2800m</th>";
                                            echo "<th class='splits-col'>3200m</th>";
                                        } elseif ($d == "3200m" && $fivesplits == 0) {
                                            echo "<th class='splits-col'>800m</th>";
                                            echo "<th class='splits-col'>1600m</th>";
                                            echo "<th class='splits-col'>2400m</th>";
                                            echo "<th class='splits-col'>3200m</th>";
                                        }
                                    }  
                                    echo "</tr></thead><tbody>";
                                    
                                    while ($row = mysqli_fetch_array($result)) {
                                        if ($row['grade'] == 12) {
                                            $grade = "Sr.";
                                        } elseif ($row['grade'] == 11) {
                                            $grade = "Jr.";
                                        } elseif ($row['grade'] == 10) {
                                            $grade = "So.";
                                        } elseif ($row['grade'] == 9) {
                                            $grade = "Fr.";
                                        } else {
                                            $grade = $row['grade'];
                                        }
                                        if ($row['name'] == "RELAY") {
                                            $name = "Relay Team";
                                        } else {
                                            $name = $row['name'];
                                        }
                                        if (($row['school'] == "Glenbrook South" or $row['school'] == "Glenview (Glenbrook South)" or $row['school'] == "Glenbrook South*") and $row['name'] !== "RELAY" and $row['name'] !== "Relay Team") {
                                            echo "<tr class='row-highlight clickable-row' data-href='/athlete/" . $row['profile'] . "'>";
                                        } elseif ($row['school'] == "Glenbrook South" or $row['school'] == "Glenview (Glenbrook South)" or $row['school'] == "Glenbrook South*") {
                                            echo "<tr class='row-highlight'>";
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<th>" . $row['place'] . "</th>";
                                        if ($heats == 1) {
                                            echo "<td>" . $row['heat'] . "</td>";  
                                        }
                                        if (isset($row['relay']) and $name != "Relay Team") {
                                            if (empty($row['profile'])) {
                                                echo "<td>" . $name . "</td>";
                                            } else {
                                                echo "<td><a href='/athlete/" . $row['profile'] . "'>" . $name . "</a></td>";
                                            }
                                        } else {
                                            if (empty($row['profile'])) {
                                                echo "<th>" . $name . "</th>";
                                            } else {
                                                echo "<th><a href='/athlete/" . $row['profile'] . "'>" . $name . "</a></th>";
                                            }
                                        }
                                        echo "<td>" . $grade . "</td>";
                                        if($row['distance'] == "3200m" AND substr($row['time'], 0,2) == "09") {
                                            $time = substr($row['time'], 1);
                                        } else if($row['distance'] == "400m" AND substr($row['time'], 0,2) == "0:") {
                                            $time = substr($row['time'], 2);
                                        } else {
                                            $time = $row['time'];
                                        }

                                        if($row['pr'] == 1) {
                                            $time = $time."<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                                        } else if ($row["sr"] == 1) {
                                            $time = $time."<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                                        }

                                        if($row['tags'] == "IQ") {
                                            $time = $time."<span class='badge bg-warning text-dark ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Individual Qualifier'>IQ</span>";
                                        } else if($row['tags'] == "TQ") {
                                            $time = $time."<span class='badge bg-warning text-dark ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Team Qualifier'>TQ</span>";
                                        } else if($row['tags'] == "All-Conf") {
                                            $time = $time."<span class='badge bg-csl ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='All Conference'>All-Conf</span>";
                                        } else if(!empty($row['tags'])) {
                                            $time = $time."<span class='badge bg-secondary text-light ms-1'>".$row['tags']."</span>";
                                        }
                                        
                                        echo "<td>" . $time . "</td>";
                                        echo "<td>" . $row['school'] . "</td>";
                                        if ($splits == 1) {
                                            if ($row['distance'] !== "400m") {
                                                echo "<td class='splits-col'>" . $row['split1'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split2'] . "</td>";
                                            }
                                            if ($row['distance'] == "1600m" or $row['distance'] == "3200m") {
                                                echo "<td class='splits-col'>" . $row['split3'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split4'] . "</td>";
                                            }
                                            if ($row['distance'] == "3200m" && $fivesplits == 1) {
                                                echo "<td class='splits-col'>" . $row['split5'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split6'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split7'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split8'] . "</td>";
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table></div>";
                                } 
                                //RELAYS
                                foreach ($relays as $d) {
                                    $rd = str_replace("4x", "", $d);
                                    echo "<h5>" . $d . "</h5>";
                                    echo "<div class='table-responsive'>";
                                    echo "<table class='table table-sm table-striped'>";
                                    echo "<thead><tr>";
                                    echo "<th>Place</th><th>Name</th><th>Grade</th><th>Time</th><th>Team</th>";
                                    if ($splits == 1) {
                                        if ($d == "4x800m" or $d == "800m") {
                                            echo "<th class='splits-col'>400m</th>";
                                            echo "<th class='splits-col'>800m</th>";
                                        }
                                        if ($d == "4x1600m" or $d == "1600m") {
                                            echo "<th class='splits-col'>400m</th>";
                                            echo "<th class='splits-col'>800m</th>";
                                            echo "<th class='splits-col'>1200m</th>";
                                            echo "<th class='splits-col'>1600m</th>";
                                        }
                                        if ($d == "3200m" && $fivesplits == 1) {
                                            echo "<th class='splits-col'>400m</th>";
                                            echo "<th class='splits-col'>800m</th>";
                                            echo "<th class='splits-col'>1200m</th>";
                                            echo "<th class='splits-col'>1600m</th>";
                                            echo "<th class='splits-col'>2000m</th>";
                                            echo "<th class='splits-col'>2400m</th>";
                                            echo "<th class='splits-col'>2800m</th>";
                                            echo "<th class='splits-col'>3200m</th>";
                                        } elseif ($d == "3200m" && $fivesplits == 0) {
                                            echo "<th class='splits-col'>800m</th>";
                                            echo "<th class='splits-col'>1600m</th>";
                                            echo "<th class='splits-col'>2400m</th>";
                                            echo "<th class='splits-col'>3200m</th>";
                                        }
                                    }
                                    echo "</tr></thead><tbody>";
                                    $result = mysqli_query($con, "SELECT * FROM overalltf WHERE meet='" . $id . "' AND level = '" . $l . "' AND (distance = '" . $d . "' OR (relay IS NOT NULL AND distance = '" . $rd . "'))");
                                    while ($row = mysqli_fetch_array($result)) {
                                        if ($row['grade'] == 12) {
                                            $grade = "Sr.";
                                        } elseif ($row['grade'] == 11) {
                                            $grade = "Jr.";
                                        } elseif ($row['grade'] == 10) {
                                            $grade = "So.";
                                        } elseif ($row['grade'] == 9) {
                                            $grade = "Fr.";
                                        } else {
                                            $grade = $row['grade'];
                                        }
                                        if ($row['name'] == "RELAY") {
                                            $name = "Relay Team";
                                        } else {
                                            $name = $row['name'];
                                        }
                                        if (($row['school'] == "Glenbrook South" or $row['school'] == "Glenview (Glenbrook South)" or $row['school'] == "Glenbrook South*") and $row['name'] !== "RELAY" and $row['name'] !== "Relay Team") {
                                            echo "<tr class='row-highlight clickable-row' data-href='/athlete/" . $row['profile'] . "'>";
                                        } elseif ($row['school'] == "Glenbrook South" or $row['school'] == "Glenview (Glenbrook South)" or $row['school'] == "Glenbrook South*") {
                                            echo "<tr class='row-highlight'>";
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<th>" . $row['place'] . "</th>";
                                        if (isset($row['relay']) and $name != "Relay Team") {
                                            if (empty($row['profile'])) {
                                                echo "<td>" . $name . "</td>";
                                            } else {
                                                echo "<td><a href='/athlete/" . $row['profile'] . "'>" . $name . "</a></td>";
                                            }
                                        } else {
                                            if (empty($row['profile'])) {
                                                echo "<th>" . $name . "</th>";
                                            } else {
                                                echo "<th><a href='/athlete/" . $row['profile'] . "'>" . $name . "</a></th>";
                                            }
                                        }
                                        echo "<td>" . $grade . "</td>";
                                        
                                        if($row['distance'] == "3200m" AND substr($row['time'], 0,2) == "09") {
                                            $time = substr($row['time'], 1);
                                        } else if($row['distance'] == "400m" AND substr($row['time'], 0,2) == "0:") {
                                            $time = substr($row['time'], 2);
                                        } else {
                                            $time = $row['time'];
                                        }

                                        if($row['pr'] == 1) {
                                            $time = $time."<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                                        } else if ($row["sr"] == 1) {
                                            $time = $time."<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                                        }
                                        
                                        echo "<td>" . $time . "</td>";
                                        echo "<td>" . $row['school'] . "</td>";
                                        if ($splits == 1) {
                                            if ($row['distance'] == "4x800m" or $row['distance'] == "800m") {
                                                echo "<td class='splits-col'>" . $row['split1'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split2'] . "</td>";
                                            }
                                            if ($row['distance'] == "4x1600m" or $row['distance'] == "1600m") {
                                                echo "<td class='splits-col'>" . $row['split1'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split2'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split3'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split4'] . "</td>";
                                            }
                                            if ($row['distance'] == "3200m" && $fivesplits == 0) {
                                                echo "<td class='splits-col'>" . $row['split1'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split2'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split3'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split4'] . "</td>";
                                            } elseif ($row['distance'] == "3200m" && $fivesplits == 1) {
                                                echo "<td class='splits-col'>" . $row['split1'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split2'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split3'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split4'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split5'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split6'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split7'] . "</td>";
                                                echo "<td class='splits-col'>" . $row['split8'] . "</td>";
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table></div>";
                                }
                            }
                            echo "<a class='btn btn-primary' href='/printresults?id=" . $id . "' role='button'>Print Results</a>";
                            echo "</div>";
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
if ($sport == "xc") {
foreach ($meetlevels as $l) {
    echo "<script>";
    echo "$(document).ready(function() {";
    echo "    $('#" .
        $abbreviations[$l] .
        "Results').DataTable({
        \"lengthMenu\": [
            [25, 50, 100, -1],
            [25, 50, 100, \"All\"]
        ],
        \"iDisplayLength\": 50,
        \"order\": []
    });";
    echo "});";
    echo "</script>";
}
}
?>
<script type="text/javascript">
document.getElementById("selectTab").addEventListener("change", function() {
    selectTab();
});

var tabEl = document.getElementById("v-pills-tab");
tabEl.addEventListener('shown.bs.tab', function(event) {
    map.resize();
    //window.location.hash = event.target.id.replace("-tab", "")
})
window.onload = function() {
    if (window.location.hash) {
        tab = window.location.hash + "-tab"
        var someTabTriggerEl = document.querySelector(tab)
        var tabt = new bootstrap.Tab(someTabTriggerEl)
        tabt.show()
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
        console.log(link);
        if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
            window.location.assign(link);
        } else {
            window.open(link, "_blank")
        }
    }
}

function showSplits(c) {
    var cells = document.getElementsByClassName('splits-col');
    for (var i = 0; i < cells.length; i++) {
        if (c == true) {
            cells[i].style.display = "table-cell"
        } else if (c == false) {
            cells[i].style.display = "none"
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
if(!empty($geojson)) {
    echo "map.addSource(\"course\", {
        type: 'geojson',
        data: '/assets/geojson/".$geojson.".geojson'
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