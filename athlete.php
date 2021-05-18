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

//Personal Records
$result = mysqli_query($con,"SELECT * FROM overalltf WHERE pr = 1 AND profile = '".$profile."'");
while($row = mysqli_fetch_array($result)) {
    if ($row['distance'] == "3200m") {
        $pr3200m = $row['time'];
        $meet3200m = $row['meet'];
    } else if ($row['distance'] == "1600m") {
        $pr1600m = $row['time'];
        $meet1600m = $row['meet'];
    } else if ($row['distance'] == "800m") {
        $pr800m = $row['time'];
        $meet800m = $row['meet'];
    } else if ($row['distance'] == "400m") {
        $pr400m = $row['time'];
        $meet400m = $row['meet'];
    }
}
$result = mysqli_query($con,"SELECT * FROM overallxc WHERE pr = 1 AND profile = '".$profile."'");
while($row = mysqli_fetch_array($result)) {
    if ($row['distance'] == "3mi") {
        $pr3mi = $row['time'];
        $meet3mi = $row['meet'];
    } else if ($row['distance'] == "2mi") {
        $pr2mi = $row['time'];
        $meet2mi = $row['meet'];
    } else if ($row['distance'] == "5k") {
        $pr5k = $row['time'];
        $meet5k = $row['meet'];
    }
}

/*
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
*/

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

$file = $_SERVER['DOCUMENT_ROOT']."/assets/images/athletes/".$profile.".png";
if (file_exists($file)) {
    $image = "assets/images/athletes/".$profile.".png";
} else {
    $image = "assets/images/athletes/blank.png";
}

include("header.php");
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 p-md-0 text-center text-md-start">

            <?php
                    echo "<div class='athlete-image mx-auto mx-md-0'>";
                    echo "<img src='/".$image."' class='img-thumbnail'>";
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
    echo "<span class='badge bg-warning mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3mi time under 15 min'>Sub-15 Club</span>";
} else if ($pr3mi < "16:00:00" && !empty($pr3mi)) {
    echo "<span class='badge bg-warning mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3mi time under 16 min'>Sub-16 Club</span>";
}
if ($pr3200m < "10:00" && !empty($pr3200m)) {
    echo "<span class='badge bg-warning mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3200m time under 10 min'>Sub-10 Club</span>";
}
if ($pr1600m < "5:00" && !empty($pr1600m)) {
    echo "<span class='badge bg-warning mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='1600m time under 5 min'>Sub-5 Club</span>";
}
if ($pr800m < "2:00" && !empty($pr800m)) {
    echo "<span class='badge bg-warning mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='800m time under 2 min'>Sub-2 Club</span>";
}

if (!empty($awards)) {
    echo "<hr class='mr-md-4'>";
    $awards = explode(", ", $awards);
    foreach ($awards as $a) {
        echo "<span class='badge bg-primary mx-1'>".$a."</span>";
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
                            <td><?php echo "<a href='/meet/".$meet3mi."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$meet3mi]."'>".$pr3mi."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet2mi."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$meet2mi]."'>".$pr2mi."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet5k."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$meet5k]."'>".$pr5k."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet3200m."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$meet3200m]."'>".$pr3200m."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet1600m."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$meet1600m]."'>".$pr1600m."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet800m."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$meet800m]."'>".$pr800m."</a>"; ?>
                            </td>
                            <td><?php echo "<a href='/meet/".$meet400m."' data-bs-toggle='tooltip' data-bs-placement='bottom' title='".$meets[$meet400m]."'>".$pr400m."</a>"; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
$xc = mysqli_query($con,"SELECT * FROM overallxc WHERE profile='". $profile ."' ORDER BY date IS NULL, date DESC");
$tf = mysqli_query($con,"SELECT * FROM overalltf WHERE profile='". $profile ."' ORDER BY date IS NULL, date DESC");
?>
            <div class="row">
                <?php
                if(mysqli_num_rows($xc) > 0) {
                    if(mysqli_num_rows($tf) > 0) {
                        echo "<div class='col-md-6'>";
                    } else {
                        echo "<div class='col-md-12'>";
                    }
                    echo "<h3>Cross Country</h3>
                    <hr>
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
                    while($row = mysqli_fetch_array($xc)) {
                        $meet = $row['meet'];
                        $distance = str_replace("mi"," Mile",$row['distance']);
                        echo "<tr class='clickable-row' data-href='/meet/".$meet."'>";

                        echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Place: ".$row['place']." (".$row['percent']."%)'>";
                        echo $row['time'];
                        if ($row['pr'] == 1) {
                            echo "<span class='badge bg-warning text-dark ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                        } else if ($row['sr'] == 1) {
                            echo "<span class='badge bg-secondary ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                        }
                        echo "</td>"; 

                        echo "<td><a href='/meet/".$meet."'>".$meets[$meet]."</a></td>";
                        echo "<td>".$distance."</td>";
                        echo "</tr>";
                    }
                echo "</tbody></table></div></div>";
                }

                if(mysqli_num_rows($tf) > 0) {
                    if(mysqli_num_rows($xc) > 0) {
                        echo "<div class='col-md-6'>";
                    } else {
                        echo "<div class='col-md-12'>";
                    }
                    echo "<h3>Distance Track</h3>
                    <hr>
                    <div class='table-responsive overflow-hidden'>
                        <table class='table table-condensed table-striped table-hover dataTable' id='tfPersonal'>
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Mark</th>
                                    <th>Meet</th>
                                </tr>
                            </thead>
                            <tbody>";   
                            while($row = mysqli_fetch_array($tf)) {
                                $meet = $row['meet'];
                                echo "<tr class='clickable-row' data-href='/meet/".$meet."'>";
                                echo "<td>".$row['distance']."</td>";
                                
                                echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Place: ".$row['place']."'>";
                                echo $row['time'];
                                if ($row['pr'] == 1) {
                                    echo "<span class='badge bg-warning text-dark ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                                } else if ($row['sr'] == 1) {
                                    echo "<span class='badge bg-secondary ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                                }
                                if (isset($row['relay'])) {
                                   echo "<span class='badge bg-info ms-1'>R</span>";
                                }
                                echo "</td>";
        
                                echo "<td><a href='/meet/".$meet."'>".$meets[$meet]."</a></td>";
                                echo "</tr>";
                            }  
                            echo "</tbody></table></div></div>";
                        }
                        ?>
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