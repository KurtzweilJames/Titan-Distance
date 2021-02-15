<?php include("db.php"); ?>
<?php
$id = htmlspecialchars($_GET["id"]);
$name = htmlspecialchars($_GET["name"]);
$profile = htmlspecialchars($_GET["profile"]);

if(!empty($name) or !empty($id)) {
    $redir = 1;
}

$result = mysqli_query($con,"SELECT * FROM athletes WHERE id='". $id ."' OR name='".$name."' OR profile='".$profile."'");

if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound');
    exit;
}

while($row = mysqli_fetch_array($result)) {
    $name = $row['name'];
    $class = $row['class'];
    $id = $row['id'];
    $college = $row['college'];
    $elementary = $row['elementary'];
    $profile = $row['profile'];
    $athnet = $row['athnet'];
    $captain = $row['captain'];
    $awards = $row['awards'];

    if ($row['xc20'] == 1) {
        $currentathlete = 1;
    } else {
        $currentathlete = 0;
    }
}

if ($redir == 1) {
    if (!empty($profile)) {
        $url = "https://titandistance.com/athlete/".$profile;
        header('Location: '.$url, TRUE);
        exit;
    }
}
$pgtitle = $name;

$result = mysqli_query($con,"SELECT id,Name,Date FROM meets");
while($row = mysqli_fetch_array($result)) {
    $meets[$row['id']] = $row['Name']." (".date("n/j/y",strtotime($row['Date'])).")";
}
?>
<?php include("header.php"); ?>
<?php
//PRs
$result = mysqli_query($con,"SELECT * FROM prs WHERE profile='". $profile ."' AND season='all'");
while($row = mysqli_fetch_array($result)) {
    $pr3mi = $row['3mi'];
    $pr2mi = $row['2mi'];
    $pr5k = $row['5k'];
    $pr3200m = $row['3200m'];
    $pr1600m = $row['1600m'];
    $pr800m = $row['800m'];
    $pr400m = $row['400m'];

    $meet3mi = $row['ID3mi'];
    $meet2mi = $row['ID2mi'];
    $meet5k = $row['ID5k'];
    $meet3200m = $row['ID3200m'];
    $meet1600m = $row['ID1600m'];
    $meet800m = $row['ID800m'];
    $meet400m = $row['ID400m'];
}

//SRs
$result = mysqli_query($con,"SELECT * FROM prs WHERE profile='". $profile ."' AND season!='all'");
while($row = mysqli_fetch_array($result)) {
    $srs[] = $row['season'];
    
}

if ($currentsport == "xc" && $currentathlete == 1) {
    $teampoints = 0;
    $result = mysqli_query($con,"SELECT percent FROM overallxc WHERE profile='". $profile ."' AND season='".$currentshort."'"); 
    while($row = mysqli_fetch_array($result)) {
        $percent = $row['percent'];
    if ($percent <= 33 && $percent > 0) {
        $teampoints = $teampoints + 3;
    } else if ($percent <= 66 && $percent > 33) {
        $teampoints = $teampoints + 2;
    } else if ($percent <= 100 && $percent > 66) {
        $teampoints = $teampoints + 1;
    }
    }
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 p-md-0 text-center text-md-left">

            <?php
                    echo "<div class='athlete-image'>";

                    $file = $_SERVER['DOCUMENT_ROOT']."/assets/images/athletes/".$profile.".png";
                    if (file_exists($file)) {
                        echo "<img src='/assets/images/athletes/".$profile.".png' class='img-thumbnail'>";
                    } else {
                        echo "<img src='/assets/images/athletes/blank.png' class='img-thumbnail'>";
                    }
                    
                    echo "</div>";

                    $y = substr($currentyear, -2);
                    if (date('n') > 6) {
                    $y = $y + 1;
                    }

                    if ($class == $y) {
                        $grade = " (Sr.)";
                    } else if ($class == $y + 1){
                        $grade = " (Jr.)";
                    } else if ($class == $y + 2){
                        $grade = " (So.)";
                    } else if ($class == $y + 3){
                        $grade = " (Fr.)";
                    }
                    
                    echo "<h3>".$name."</h3>";
                    echo "<h5>Class of 20".$class.$grade."</h5>";
                    if (!empty($college)) {
                    echo "<h5>".$college."</h5>";
                    } else {
                    echo "<h5>".$elementary."</h5>";   
                    }
                    if ($currentsport == "xc" && $currentathlete == 1) {
                        echo "<h5>Team Points: ".$teampoints."</h5>";
                    }

if ($pr3mi < "15:00:00" && !empty($pr3mi)) {
    echo "<span class='badge badge-warning mx-1'>Sub-15 Club</span>";
} else if ($pr3mi < "16:00:00" && !empty($pr3mi)) {
    echo "<span class='badge badge-warning mx-1'>Sub-16 Club</span>";
}
if ($pr1600m < "5:00" && !empty($pr1600m)) {
    echo "<span class='badge badge-warning mx-1'>Sub-5 Club</span>";
}
if ($pr800m < "2:00" && !empty($pr800m)) {
    echo "<span class='badge badge-warning mx-1'>Sub-2 Club</span>";
}

if (!empty($awards)) {
    echo "<hr class='mr-md-4'>";
    $awards = explode(", ", $awards);
    foreach ($awards as $a) {
        echo "<span class='badge badge-primary mx-1'>".$a."</span>";
    }
}

                    if(!empty($athnet)){
                        echo "<hr class='mr-md-4'>";
                        echo "<a class='btn btn-primary btn-sm mx-1' href='https://www.athletic.net/CrossCountry/Athlete.aspx?AID=".$athnet."' role='button' target='_null'>Athletic.net XC</a>";
                        echo "<a class='btn btn-primary btn-sm mx-1' href='https://www.athletic.net/TrackAndField/Athlete.aspx?AID=".$athnet."' role='button' target='_null'>Athletic.net TF</a>";
                    }
                    ?>
            <hr class="d-block d-md-none">
        </div>
        <div class="col-md-9 p-md-1">
            <h3>Personal Records</h3>
            <hr>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">3mi</th>
                            <th scope="col">2mi</th>
                            <th scope="col">5k</th>
                            <th scope="col">3200m</th>
                            <th scope="col">1600m</th>
                            <th scope="col">800m</th>
                            <th scope="col">400m</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo "<a href='/meet/".$meet3mi."' data-toggle='tooltip' data-placement='bottom' title='".$meets[$meet3mi]."'>".$pr3mi."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet2mi."' data-toggle='tooltip' data-placement='bottom' title='".$meets[$meet2mi]."'>".$pr2mi."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet5k."' data-toggle='tooltip' data-placement='bottom' title='".$meets[$meet5k]."'>".$pr5k."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet3200m."' data-toggle='tooltip' data-placement='bottom' title='".$meets[$meet3200m]."'>".$pr3200m."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet1600m."' data-toggle='tooltip' data-placement='bottom' title='".$meets[$meet1600m]."'>".$pr1600m."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet800m."' data-toggle='tooltip' data-placement='bottom' title='".$meets[$meet800m]."'>".$pr800m."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet400m."' data-toggle='tooltip' data-placement='bottom' title='".$meets[$meet400m]."'>".$pr400m."</a>"; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6 p-md-1">
                    <h3>Cross Country</h3>
                    <hr>
                    <div class="table-responsive overflow-hidden">
                        <table class="table table-condensed table-striped table-hover dataTable" id="xcPersonal">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Meet</th>
                                    <th>Distance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $result = mysqli_query($con,"SELECT time,meet,name,distance,place,percent FROM overallxc WHERE profile='". $profile ."' ORDER BY date IS NULL, date DESC");
            
                    while($row = mysqli_fetch_array($result)) {
                        $meet = $row['meet'];
                        $distance = str_replace("mi"," Mile",$row['distance']);
                        echo "<tr class='clickable-row' data-href='/meet/".$meet."'>";

                        echo "<td data-toggle='tooltip' data-placement='top' title='Finish Place: ".$row['place']." (".$row['percent']."%)'>";
                        echo $row['time'];
                        if (($row['time'] == $pr3mi && $row['distance'] == "3mi") || ($row['time'] == $pr2mi && $row['distance'] == "2mi") || ($row['time'] == $pr5k && $row['distance'] == "5k")) {
                            echo " <span class='badge badge-warning'>PR</span>";
                        }
                        echo "</td>";

                        echo "<td><a href='/meet/".$meet."'>".$meets[$meet]."</a></td>";
                        echo "<td>".$distance."</td>";
                        echo "</tr>";
                    }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 p-md-1">
                    <h3>Track</h3>
                    <hr>
                    <div class="table-responsive overflow-hidden">
                        <table class="table table-condensed table-striped table-hover dataTable" id="tfPersonal">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Mark</th>
                                    <th>Meet</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $result = mysqli_query($con,"SELECT time,meet,name,distance,points,relay,place FROM overalltf WHERE profile='". $profile ."' ORDER BY meet DESC");
            
                    while($row = mysqli_fetch_array($result)) {
                        $meet = $row['meet'];
                        $points = $row['points'];
                        echo "<tr class='clickable-row' data-href='/meet/".$meet."'>";
                        echo "<td>".$row['distance']."</td>";
                        
                        echo "<td data-toggle='tooltip' data-placement='top' title='Finish Place: ".$row['place']."'>";
                        echo $row['time'];
                        if (($row['time'] == $pr3200m && $row['distance'] == "3200m") || ($row['time'] == $pr1600m && $row['distance'] == "1600m") || ($row['time'] == $pr800m && $row['distance'] == "800m") || ($row['time'] == $pr400m && $row['distance'] == "400m")) {
                            echo " <span class='badge badge-warning'>PR</span>";
                        }
                        if (isset($row['relay'])) {
                           echo " <span class='badge badge-info'>R</span>";
                        }
                        echo "</td>";

                        echo "<td><a href='/meet/".$meet."'>".$meets[$meet]."</a></td>";
                        echo "<td>".$points."</td>";
                        echo "</tr>";
                    }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <h3>Data Visualization</h3>
            <hr>
            <!--
            <div class="form-group">
                <select class="form-control" id="ChartSelect" onchange="showChart()">
                    <option value="pp">Performance Points</option>
                    <option value="xcpercent">XC Finish Percentage</option>
                    <option value="tfpercent">TF Finish Percentage</option>
                    <option value="3mi" disabled>3 Mile Times</option>
                    <option value="2mi" disabled>2 Mile Times</option>
                    <option value="3200m" disabled>3200m Times</option>
                    <option value="1600m" disabled>1600m Times</option>
                    <option value="800m" disabled>800m Times</option>
                    <option value="400m" disabled>400m Times</option>
                </select>
            </div>-->
            <canvas id="ppChart" width="400" height="200"></canvas>
            <canvas id="xcpercentChart" width="400" height="200"></canvas>
            <canvas id="tfpercentChart" width="400" height="200"></canvas>
            <canvas id="3miChart" width="400" height="200"></canvas>
            <canvas id="2miChart" width="400" height="200"></canvas>
            <canvas id="3200mChart" width="400" height="200"></canvas>
            <canvas id="1600mChart" width="400" height="200"></canvas>
            <canvas id="800mChart" width="400" height="200"></canvas>
            <canvas id="400mChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>
<script>
/*
function showChart() {
    var x = document.getElementById("ChartSelect");
    var chart = x.value;
}

var json = (function() {
    var json = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': "https://titandistance.com/api/charts?profile=kurtzweil_j&chart=pp",
        'dataType': "json",
        'success': function(data) {
            json = data;
        }
    });
    console.log(json);
})();
*/
</script>

<?php $require = "charts"; ?>
<?php include("footer.php"); ?>