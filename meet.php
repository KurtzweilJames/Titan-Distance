<?php include("db.php"); ?>
<?php
$id = htmlspecialchars($_GET["id"]);
$template = "meet";

if (strpos($id, '/') !== false) {
    list($slug, $year) = explode("/", $id, 2);
    $result = mysqli_query($con,"SELECT * FROM series WHERE slug='". $slug ."'");
    while($row = mysqli_fetch_array($result)) {
        $series = $row['id'];
    }
    $result = mysqli_query($con,"SELECT * FROM meets WHERE Date LIKE '". $year ."-%' AND Series = '".$series."'");
} else {
    $result = mysqli_query($con,"SELECT * FROM meets WHERE id='". $id ."'");
}

if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound');
    exit;
}

while($row = mysqli_fetch_array($result)) {
    $id = $row['id'];

    if (!empty($row['Series']) && empty($slug)) {
        $year = date("Y",strtotime($row['Date']));
        
        $result = mysqli_query($con,"SELECT * FROM series WHERE id='". $row['Series'] ."'");
        while($row = mysqli_fetch_array($result)) {
            $slug = $row['slug'];
        }
        header('Location: https://titandistance.com/meet/'.$slug."/".$year);
    }

//Page Title
$pgtitle = $row['Name'];
$pgtitleignore = 1;
$require = "meet";

//Meet Variables
$name = $row['Name'];
$date = date("l, F d, Y",strtotime($row['Date']));
$location = $row['Location'];
$athnet = $row['AthNet'];
$message = $row['Message'];
$notes = $row['Notes'];
$season = $row['Season'];
$live = $row['Live'];
$results = $row['Results'];
$official = $row['Official'];

//Two Day
if (!empty($row['Day2Time'])) {
    $date = $date." -<br>".date("l, F d, Y",strtotime($row['Day2Time']));
}

//Sport
$xc = mysqli_query($con,"SELECT * FROM overallxc WHERE meet='". $id ."'");
$tf = mysqli_query($con,"SELECT * FROM overalltf WHERE meet='". $id ."'");
if (mysqli_num_rows($xc) > 0) {
    $sport = "xc";
    $prepost = "post";
} else if (mysqli_num_rows($tf) > 0) {
    $sport = "tf";
    $prepost = "post";
} else {
    $prepost = "pre";
    if (strpos($season, 'Cross Country') !== false){
        $sport = "xc";
    } else {
        $sport = "tf";
    }
}

//Badge
if (!empty($row['Badge'])) {
    if ($row['Badge'] == 1) {
        $badge = " <span class='badge badge-csl'>CSL</span>";
    } else if ($row['Badge'] == 2) {
        $badge = " <span class='badge badge-ihsa'>IHSA</span>";
    } else if ($row['Badge'] == 3) {
        $badge = " <span class='badge badge-info'>TT</span>";
    }
}

}

//Photos
$result = mysqli_query($con,"SELECT * FROM photos WHERE meet='". $id ."'");
if (mysqli_num_rows($result) > 0) {
    $photos = yes;
} else {
    $photos = no;
}

if ($prepost == "post") {
$result = mysqli_query($con,"SELECT * FROM news WHERE meet='". $id ."' AND recap = 1");
} else if ($prepost == "pre") {
$result = mysqli_query($con,"SELECT * FROM news WHERE meet='". $id ."' AND info = 1");   
}
while($row = mysqli_fetch_array($result)) {
$content = $row['content'];
$title = $row['title'];
$image = $row['image'];
$slug = $row['slug'];
}

include("header.php");
?>
<div class="container mt-4 mb-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card" style="height: 100%;">
                <div class="card-body">
                    <h4><?php echo $name.$badge; ?></h4>
                    <h5 class="mb-0"><?php echo $date; ?></h5>
                    <h5 class="mb-2"><?php echo $location; ?></h5>
                    <div class="nav flex-column nav-pills d-none d-md-block" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <?php
                    if ($prepost == "pre") {
                        echo "<a class='nav-link active' id='news-tab' data-toggle='pill' href='#news' role='tab' aria-controls='news-tab' aria-selected='true'>Meet Information</a>";
                        $dropdown[] = "<option value='news' name='news'>Meet Information</option>";
                    } else if ($prepost == "post") {
                        echo "<a class='nav-link active' id='news-tab' data-toggle='pill' href='#news' role='tab' aria-controls='news-tab' aria-selected='true'>Meet Recap</a>";
                        $dropdown[] = "<option value='news' name='news'>Meet Recap</option>";
                    }
                        if ($sport == "xc") {
                            $result = mysqli_query($con,"SELECT DISTINCT level FROM overallxc WHERE meet = '".$id."'");
                            while($row = mysqli_fetch_array($result)) {
                                $levelnum = $row['level'];
                                echo "<a class='nav-link' id='".$abbreviations[$levelnum]."-tab' data-toggle='pill' href='#".$abbreviations[$levelnum]."-results' role='tab' aria-controls='".$abbreviations[$levelnum]."-tab' aria-selected='false'>".$teams[$levelnum]." Results</a>";
                                $meetlevels[]=$levelnum;
                                $dropdown[] = "<option value='".$abbreviations[$levelnum]."' name='".$abbreviations[$levelnum]."'>".$teams[$levelnum]." Results</option>";
                            }
                        }
                        if ($sport == "tf") {
                            $result = mysqli_query($con,"SELECT DISTINCT level FROM overalltf WHERE meet = '".$id."'");
                            while($row = mysqli_fetch_array($result)) {
                                $levelnum = $row['level'];
                                echo "<a class='nav-link' id='".$abbreviations[$levelnum]."-tab' data-toggle='pill' href='#".$abbreviations[$levelnum]."-results' role='tab' aria-controls='".$abbreviations[$levelnum]."-tab' aria-selected='false'>".$teams[$levelnum]." Results</a>";
                                $meetlevels[]=$levelnum;
                                $dropdown[] = "<option value='".$abbreviations[$levelnum]."' name='".$abbreviations[$levelnum]."'>".$teams[$levelnum]." Results</option>";
                            }
                        }

                        if ($prepost == "post" && $sport == "tf"){
                            echo "<a class='nav-link' id='dscores-tab' data-toggle='pill' href='#dscores' role='tab' aria-controls='dscores-tab' aria-selected='false'>Distance Scores</a>";
                            $dropdown[] = "<option value='dscores' name='dscores'>Distance Scores</option>";
                        }

                        if ($prepost == "post"){
                            echo "<a class='nav-link' id='scores-tab' data-toggle='pill' href='#scores' role='tab' aria-controls='scores-tab' aria-selected='false'>Team Scores</a>";
                            $dropdown[] = "<option value='scores' name='scores'>Team Scores</option>";
                        }

                        if ($sport == "xc") {
                            echo "<a class='nav-link' id='map-tab' data-toggle='pill' href='#map' role='tab' aria-controls='map-tab' aria-selected='false'>Course Map/Directions</a>";
                            $dropdown[] = "<option value='map' name='map'>Course Map/Directions</option>";
                        } else {
                            echo "<a class='nav-link' id='map-tab' data-toggle='pill' href='#map' role='tab' aria-controls='map-tab' aria-selected='false'>Map & Directions</a>";
                            $dropdown[] = "<option value='map' name='map'>Map & Directions</option>";
                        }
                        if (!empty($live) && $prepost == "pre"){
                            echo "<a class='nav-link' id='live' href='".$live."' role='tab' target='_blank'>LIVE Results <i class='fas fa-external-link-alt'></i></a>";
                            $dropdown[] = "<option value='link-".$live."' name='live'>LIVE Results <i class='fas fa-external-link-alt'></option>";
                        }

                        if (!empty($photos) && $prepost == "post"){
                            echo "<a class='nav-link' id='photos-tab' data-toggle='pill' href='#photos' role='tab' aria-controls='photos-tab' aria-selected='false'>Photos</a>";
                            $dropdown[] = "<option value='photos' name='photos'>Photos</option>";
                        }

                        if (!empty($results)){
                            echo "<a class='nav-link' id='download' href='".$results."' role='tab' target='_blank'>Downloadable Results <i class='fas fa-external-link-alt'></i></a>";
                            $dropdown[] = "<option value='link-".$results."' name='results'>Downloadable Results <i class='fas fa-external-link-alt'></option>";
                        }

                        if (!empty($athnet)){
                            echo "<a class='nav-link' id='ath-net' href='".$athnet."' role='tab' target='_blank'>Athletic.net <i class='fas fa-external-link-alt'></i></a>";
                            $dropdown[] = "<option value='link-".$athnet."' name='athnet'>Athletic.net <i class='fas fa-external-link-alt'></option>";
                        }

                        if (!empty($results)){
                            echo "<a class='nav-link' id='report' href='https://forms.gle/NQjahvTVmbNnsASo8' role='tab' target='_blank'>Request Correction <i class='fas fa-external-link-alt'></i></a>";
                            $dropdown[] = "<option value='link-https://forms.gle/NQjahvTVmbNnsASo8' name='report'>Request Correction <i class='fas fa-external-link-alt'></option>";
                        }
                        ?>

                    </div>

                    <div class="form-group d-block d-md-none">
                        <select class="form-control" id="selectTab" onchange="selectTab(this.value)">
                            <?php
foreach($dropdown as $d) {
    echo $d;
}
                        ?>
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
            echo "<div class='alert alert-danger' role='alert'>".$message."</div>";
        }

        if (!empty($notes)) {
            echo "<div class='alert alert-info' role='alert'>".$notes."</div>";
        }

        ?>
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="news" role="tabpanel" aria-labelledby="news-tab">
                            <?php
                         if ($prepost == "pre") {
                        echo "<h2>Meet Information</h2>";
                         } else if ($prepost == "post") {
                             if (!empty($title)) {
                                echo "<h2><a href='/news/".$slug."'>".$title."</a></h2>"; 
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
                            <h1>Team Scores</h1>
                            <p><strong>Team scores for all events, including short-distance, field, etc.</strong></p>
                            <?php
foreach ($meetlevels as $l) {
$result = mysqli_query($con,"SELECT * FROM overallscores WHERE meet='". $id ."' AND level = '".$l."'");
echo "<h3>".$teams[$l]."</h3>";
echo "<table class='table table-sm'>";
while($row = mysqli_fetch_array($result)) {
    if ($row['school'] == "Glenbrook South" OR $row['school'] == "Glenview (Glenbrook South)") {
        echo "<tr class='row-highlight'>";
    } else {
    echo "<tr>";
    }
    echo "<td>".$row['place']."</td>";
    echo "<td>".$row['school']."</td>";
    echo "<td>".$row['score']."</td>";
    echo "</tr>";
}
echo "</table>";
if (mysqli_num_rows($result) <= 0) {
    echo "<p class='card-text'><strong>This is either an unscored meet, or team results are missing from our database. If you believe this is an error, please reach out.</strong></p>";
}
}
                            ?>
                        </div>
                        <div class="tab-pane fade" id="dscores" role="tabpanel" aria-labelledby="dscores-tab">
                            <h1>Distance Scores</h1>
                            <p><strong>Team scores only for Distance Events</strong></p>
                            <?php
                            foreach ($meetlevels as $l) {
                                $result = mysqli_query($con,"SELECT school, COUNT(*)  FROM overalltf WHERE meet=".$id." AND level = ".$l." GROUP BY school");
                                while($row = mysqli_fetch_array($result)) {
                                $scores[$row['school']] = 0;
                                }
                                
                                $result = mysqli_query($con,"SELECT * FROM overalltf WHERE meet='". $id ."' AND level = ".$l." AND place IS NOT NULL");
                                while($row = mysqli_fetch_array($result)) {
                                    $place = $row['place'];
                                    if ($place == 1) {
                                        $score = 10;
                                    } else if ($place == 2) {
                                        $score = 8;
                                    } else if ($place == 3) {
                                        $score = 6;
                                    } else if ($place == 4) {
                                        $score = 4;
                                    } else if ($place == 5) {
                                        $score = 2;
                                    } else if ($place == 6 && empty($row['relay'])) {
                                        $score = 1;
                                    } else if ($place == 6 && !empty($row['relay'])) {
                                        $score = 0;
                                    } else {
                                        $score = 0;
                                    }
                                    
                                    $scores[$row['school']] +=$score;
                                }
                            
                                arsort($scores);
                                echo "<h3>".$teams[$l]."</h3>";
                                echo "<table class='table table-sm'>";
                                $n = 1;
                                foreach ($scores as $s => $p) {
                                    if ($s == "Glenbrook South" OR $s == "Glenview (Glenbrook South)") {
                                        echo "<tr class='row-highlight'>";
                                    } else {
                                    echo "<tr>";
                                    }
                                    echo "<td>".$n."</td>";
                                    echo "<td>".$s."</td>";
                                    echo "<td>".$p."</td>";
                                    echo "</tr>";
                                    $n += 1;
                                }

                                echo "</table>";
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="map" role="tabpanel" aria-labelledby="map-tab">
                            <?php
                            if ($sport == "xc") {
                                echo "<h1>Course Map & Directions</h1>";
                            } else {
                                echo "<h1>Map & Directions</h1>";
                            }
                        ?>
                            <?php
                        $result = mysqli_query($con,"SELECT * FROM locations WHERE name='". $location. "'");
                        if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result)) {
                            if (!empty($row['xccourse']) && $sport == "xc") {
                            echo "<img class='img-fluid' src='/assets/images/course-maps/".$row['xccourse']."'>";
                            echo "<hr>";
                            }
                            if (!empty($row['coordinates'])) {
                            echo "<div id='coursemap'></div>";
                            echo "<script>
                            mapboxgl.accessToken = '".$mapboxapikey."';
                        var map = new mapboxgl.Map({
                        container: 'coursemap', // container id
                        style: 'mapbox://styles/jkurtzweil2/ckgacb5xw02fp19r16el5q4xc', // style URL
                        center: [".$row['coordinates']."], // starting position [lng, lat]
                        zoom: 14 // starting zoom
                        });

                        var marker = new mapboxgl.Marker()
                            .setLngLat([".$row['coordinates']."])
                        .addTo(map);";

                        echo "$('#map-tab').on('shown.bs.tab', function() {
                            map.resize();
                          });";
/*
                        if (!empty($row['geojson'])) {
                            echo "map.addSource(route, {
                                type: 'geojson',
                                data: 'https://titandistance.com/assets/geojson/".$row['geojson'].".geojson'
                            });
                            map.addLayer({
                                id: route,
                                source: route,
                                type: 'line',
                                'paint': {
                            'line-width': 5,
                            'line-color': '#073763'
                                }
                            });";
                        }
*/
                        echo"</script>";
                            }
                            if (!empty($row['gmap'])) {
                                echo "<div class='text-center'>";
                                echo "<a class='btn btn-primary' href='https://www.google.com/maps/search/?api=1&query=".$row['name']."&query_place_id=".$row['gmap']."' role='button' target='_blank'>Open in Google Maps</a>";
                                echo "</div>";
                            } else {
                                echo "<div class='text-center'>";
                                echo "<a class='btn btn-primary' href='https://maps.google.com/?q=" . $location . "' role='button' target='_blank'>Open in Google Maps</a>";
                                echo "</div>";
                            }
                        }
                    } else {
                            echo "<div class='embed-responsive embed-responsive-4by3'>";
                            echo "<iframe class='embed-responsive-item' width='600' height='450' src='https://www.google.com/maps/embed/v1/search?key=".$gmapapikey."&q=".$location."' allowfullscreen></iframe>";
                            echo "</div>";
                    }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="photos" role="tabpanel" aria-labelledby="photos-tab">
                            <h1>Photos</h1>
                            <?php
                            echo "<div class='row row-cols-1 row-cols-md-2'>";

                            $result = mysqli_query($con,"SELECT * FROM photos WHERE meet='". $id ."'");
                            while($row = mysqli_fetch_array($result)) {
                                echo "<div class='col mb-2'>";
                                    echo "<div class='card clickable text-center' data-href='".$row['link']."'>";
                                        echo "<img src='/assets/images/meets/".$row['cover']."' class='card-img-top'>";
                                        echo "<div class='card-body'>";
                                            echo "<p class='card-text'>Photographer(s): ".$row['credits']."</p>";
                                            echo "</div>";
                                        echo "</div>";
                                    echo "</div>";
                            }

                                echo "</div>";
                        ?>
                        </div>

                        <?php
                    foreach ($meetlevels as $l) {
                    echo "<div class='tab-pane fade' id='".$abbreviations[$l]."-results' role='tabpanel' aria-labelledby='".$abbreviations[$l]."-tab'>";
                    $result = mysqli_query($con,"SELECT * FROM overallxc WHERE meet='". $id ."' AND level = '".$l."' AND (school = 'Glenbrook South' OR school = 'Glenview (Glenbrook South)' OR school = 'Glenbrook South*') ORDER BY place IS NULL, place ASC LIMIT 1");
                        while($row = mysqli_fetch_array($result)) {
                        $distance = $row['distance'];
                        if (!empty($row['split1'])) {
                            $splits = 1;
                        } else {
                            $splits = 0;
                        }
                        }
                    echo "<div class='d-flex justify-content-between align-items-center mb-2'>";
                    if ($sport == "xc") {    
                    echo "<h1>".$teams[$l]." Results (".$distance.")</h1>";
                    } else {
                    echo "<h1>".$teams[$l]." Results</h1>";   
                    }

                    if($splits == 1) {
                        echo "<div class='d-none d-md-block'>";
                        echo "<div class='custom-control custom-switch'>";
                        echo "<input type='checkbox' class='custom-control-input' onChange='showSplits(this.checked)' id='splitsSwitch' checked>";
                        echo "<label class='custom-control-label' for='splitsSwitch'>Toggle Splits</label>";
                        echo "</div>";
                        echo "<div class='custom-control custom-switch'>";
                        echo "<input type='checkbox' class='custom-control-input' onChange='showHighlight(this.checked)' id='highlightSwitch' checked>";
                        echo "<label class='custom-control-label' for='highlightSwitch'>Toggle Highlight</label>";
                        echo "</div>";
                        echo "</div>";
                    }

                    echo "</div>";

                    if ($official == 1) {
                        echo "<span class='badge badge-success'>Official Results (F.A.T.)</span>";
                    } else if ($official == 2) {
                        echo "<span class='badge badge-warning'>Official Results (Hand Timed)</span>";
                    } else if ($official == 0) {
                        echo "<span class='badge badge-danger'>Unofficial Results</span>";
                    }

                    if ($sport == "xc") {
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-condensed table-sm dataTable' id='".$abbreviations[$l]."Results'>";
                        echo "<thead>
                        <tr>";
                        if (!empty($row['place'])){
                        echo "<th>Place</th>";
                        }
                        echo "<th>Name</th>
                        <th>Grade</th> <th>Time</th>
                        <th>Team</th>";
                    if ($splits == 1) {
                        echo "<th>Team</th><th>1 Mile</th>";
                        if ($distance == "2mi") {
                        echo"<th>Finish</th>";
                        } else {
                        echo"<th>2 Mile</th>
                        <th>Finish</th>";
                        }
                    }
                        echo "</tr>
                        </thead>";
                        echo "<tbody>";

                        $result = mysqli_query($con,"SELECT * FROM overallxc WHERE meet='". $id ."' AND level = '".$l."' ORDER BY place IS NULL, place ASC, time ASC");
                        while($row = mysqli_fetch_array($result)) {

                            $percent = $row['percent']."%";

                            if ($row['grade'] == 12) {
                                $grade = "Sr.";
                            } else if ($row['grade'] == 11) {
                                $grade = "Jr.";
                            } else if ($row['grade'] == 10) {
                                $grade = "So.";
                            } else if ($row['grade'] == 9) {
                                $grade = "Fr.";
                            } else {
                                $grade = $row['grade'];
                            }
                            
                            if ($row['school'] == "Glenbrook South" OR $row['school'] == "Glenview (Glenbrook South)" OR $row['school'] == "Glenbrook South*"){
                                echo "<tr class='row-highlight clickable-row' data-href='/athlete/".$row['profile']."'>";
                                if (!empty($row['place'])) {
                                    echo "<th data-toggle='tooltip' data-placement='top' title='".$percent."'>" . $row['place'] . "</th>";
                                }
                            } else {
                                echo "<tr>";
                                if (!empty($row['place'])) {
                                echo "<th>".$row['place'] . "</th>";
                                }
                            }

                            if (!empty($row['profile'])){
                                echo "<th><a href='/athlete/".$row['profile']."'>" . $row['name'] . "</a></th>";
                            } else {
                                echo "<th>".$row['name']."</th>";
                            }
                            
                            echo "<td>" . $grade . "</td>";
                            echo "<th>" . $row['time'] . "</th>";
                            echo "<td>" . $row['school'] . "</td>";
                            if ($splits == 1) {
                            echo "<td>" . $row['split1'] . "</td>";
                            echo "<td>" . $row['split2'] . "</td>";
                            if ($distance !== "2mi") {
                            echo "<td>" . $row['split3'] . "</td>";
                            }
                            }
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else if ($sport == "tf") {
                        $prevrow = 0; 
            
            $result = mysqli_query($con,"SELECT * FROM overalltf WHERE meet='". $id ."' AND level = '".$l."'");     
                while($row = mysqli_fetch_array($result)) {
                    if ($row['grade'] == 12) {
                        $grade = "Sr.";
                    } else if ($row['grade'] == 11) {
                        $grade = "Jr.";
                    } else if ($row['grade'] == 10) {
                        $grade = "So.";
                    } else if ($row['grade'] == 9) {
                        $grade = "Fr.";
                    } else {
                        $grade = $row['grade'];
                    }
                    
                    if ($row['name'] == "RELAY"){
                        $name = "Relay Team";
                    } else {
                        $name = $row['name'];
                    }
                    
                    if ($row['place'] == 1) {
                    echo "</tbody></table>";
                    echo "<h5>".$row['distance']."</h5>";
                    echo "<table class='table table-responsive table-sm table-striped'>";
                    echo "<thead><tr>";
                    echo "<th>Place</th><th>Name</th><th>Grade</th><th>Time</th><th>Team</th>";
                    
                    if ($row['distance'] == "4x800m" OR $row['distance'] == "800m") {
                        echo "<th>400m</th>";
                        echo "<th>800m</th>";
                    }
                    if ($row['distance'] == "4x1600m" OR $row['distance'] == "1600m") {
                        echo "<th>400m</th>";
                        echo "<th>800m</th>";
                        echo "<th>1200m</th>";
                        echo "<th>1600m</th>";                        
                    }
                    if ($row['distance'] == "3200m") {
                        echo "<th>800m</th>";
                        echo "<th>1600m</th>";
                        echo "<th>2400m</th>";
                        echo "<th>3200m</th>";
                    }
                        
                    echo "</tr></thead><tbody>";
                    }
                    
                    if (($row['school'] == "Glenbrook South" OR $row['school'] == "Glenview (Glenbrook South)" OR $row['school'] == "Glenbrook South*") AND $row['name'] !== "RELAY" AND $row['name'] !== "Relay Team"){
                    echo "<tr class='table-info clickable-row' data-href='/athlete/".$row['profile']."'>";
                    } else if ($row['school'] == "Glenbrook South" OR $row['school'] == "Glenview (Glenbrook South)" OR $row['school'] == "Glenbrook South*"){
                        echo "<tr class='table-info'>";
                    } else {
                    echo "<tr>";
                    }
                        echo "<th>" . $row['place'] . "</th>";
                        
                    if (isset($row['relay']) AND $name != "Relay Team") {
                        if (empty($row['profile'])) {
                            echo "<td>" . $name . "</td>"; 
                        } else {
                            echo "<td><a href='/athlete/". $row['profile'] . "'>" . $name . "</a></td>";
                            }
                    } else {
                        if (empty($row['profile'])) {
                        echo "<th>" . $name . "</th>"; 
                    } else {
                        echo "<th><a href='/athlete/". $row['profile'] . "'>" . $name . "</a></th>";
                        }
                    }
                        echo "<td>" . $grade . "</td>";
                        echo "<td>" . $row['time'] . "</td>";
                        echo "<td>" . $row['school'] . "</td>";
                    
                    if ($row['distance'] == "4x800m" OR $row['distance'] == "800m") {
                        echo "<td>".$row['split1']."</td>";
                        echo "<td>".$row['split2']."</td>";
                    }
                    if ($row['distance'] == "4x1600m" OR $row['distance'] == "1600m") {
                        echo "<td>".$row['split1']."</td>";
                        echo "<td>".$row['split2']."</td>";
                        echo "<td>".$row['split3']."</td>";
                        echo "<td>".$row['split4']."</td>";                         
                    }
                    if ($row['distance'] == "3200m") {
                        echo "<td>".$row['split1']."</td>";
                        echo "<td>".$row['split2']."</td>";
                        echo "<td>".$row['split3']."</td>";
                        echo "<td>".$row['split4']."</td>"; 
                    }
                    
                        echo "</tr>"; 
                    
                    
                        $prevrelay = $row['relay'];
                        $prevlevel = $row['level'];
                        $prevdistance = $row['distance'];
                    if (isset($row['relay'])) {
                        echo "<tr></tr>";
                        $prevrelaydistance = "4x".$row['distance'];
                    } else {
                        $prevrelaydistance = "0";
                    }
                    
                }
            echo "</tbody></table>";
                    }
                    echo "</div>";
                    }
                    ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
foreach ($meetlevels as $l) {
echo "<script>";
echo "$(document).ready(function() {";
echo "    $('#".$abbreviations[$l]."Results').DataTable({
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
?>
<script type="text/javascript">
var tab;

function selectTab(str) {
    var dropdown = document.getElementById('selectTab');
    tab = "#" + dropdown.options[dropdown.selectedIndex].value;
    if (tab.includes("link-") == false) {
        $(tab + "-tab").trigger('click');
    } else {
        var link = tab.substring(6);
        console.log(link);
        window.open(link, '_blank');
    }
}

function showSplits(c) {
    if (c == true) {} else if (c == false) {}
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
</script>
<?php include("footer.php"); ?>