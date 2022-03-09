<?php include("db.php"); ?>
<?php
$id = htmlspecialchars($_GET["id"]);
$name = htmlspecialchars($_GET["name"]);
$profile = htmlspecialchars($_GET["profile"]);

if (!empty($name) or !empty($id)) {
    $redir = 1;
}

$result = mysqli_query($con, "SELECT * FROM athletes WHERE id='" . $id . "' OR name='" . $name . "' OR profile='" . $profile . "'");

if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound');
    exit;
}

while ($row = mysqli_fetch_array($result)) {
    $name = $row['name'];
    $class = $row['class'];
    $id = $row['id'];
    $college = $row['college'];
    $elementary = $row['elementary'];
    $profile = $row['profile'];
    $athnet = $row['athnet'];
    $captain = $row['captain'];
    $awards = $row['awards'];
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
$result = mysqli_query($con, "SELECT distance,time,meet FROM overallxc WHERE pr = 1 AND profile = '" . $profile . "' AND distance IN ('3mi','2mi','5k')");
while ($row = mysqli_fetch_array($result)) {
    $allprs[$row['distance']] = "<td><a href='/meet/" . $row['meet'] . "' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . $meets[$row['meet']] . "'>" . $row['time'] . "</a></td>";
    $prs[$row['distance']] = $row['time'];
}
$result = mysqli_query($con, "SELECT DISTINCT event,result,meet FROM overalltf WHERE pr = 1 AND profile = '" . $profile . "'");
while ($row = mysqli_fetch_array($result)) {
    $allprs[$row['event']] = "<td><a href='/meet/" . $row['meet'] . "' data-bs-toggle='tooltip' data-bs-placement='bottom' title='" . $meets[$row['meet']] . "'>" . formatTime($row['result']) . "</a></td>";
    $prs[$row['event']] = $row['result'];
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 p-md-0 text-center text-md-start">

            <?php
            echo "<div class='athlete-image mx-auto mx-md-0'>";
            echo "<img src='/" . $image . "' class='img-thumbnail'>";
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
            }

            echo "<h3>" . $name . "</h3>";
            echo "<h4>Class of " . $class . $grade . "</h4>";
            if (!empty($college)) {
                $json = json_decode(file_get_contents("api/collegelogos.json"), true);
                $colleges = explode(",", $college);
                foreach ($colleges as $c) {
                    echo "<h5>";
                    echo $c;
                    $c = str_replace(" (DI)", "", $c);
                    $c = str_replace(" (DIII)", "", $c);
                    if ($json[$c]) {
                        echo "<img class='ms-1' src='/assets/logos/colleges/" . $json[$c]["logo"] . "' height='14px'>";
                    }
                    echo "</h5>";
                }
            }
            // if ($currentsport == "xc" && $currentathlete == 1) {
            //     echo "<h5>Team Points: ".$teampoints."</h5>";
            // }

            if ($prs["3mi"] < "15:00:00" && !empty($prs['3mi'])) {
                echo "<span class='badge bg-award mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3mi time under 15 min'>Sub-15 Club</span>";
            } else if ($prs["3mi"] < "16:00:00" && !empty($prs['3mi'])) {
                echo "<span class='badge bg-award mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3mi time under 16 min'>Sub-16 Club</span>";
            }
            if ($prs["3200m"] < "10:00" && !empty($prs["3200m"])) {
                echo "<span class='badge bg-award mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='3200m time under 10 min'>Sub-10 Club</span>";
            }
            if ($prs["1600m"] < "5:00" && !empty($prs["1600m"])) {
                echo "<span class='badge bg-award mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='1600m time under 5 min'>Sub-5 Club</span>";
            }
            if ($prs["800m"] < "2:00" && !empty($prs["800m"])) {
                echo "<span class='badge bg-award mx-1' data-bs-toggle='tooltip' data-bs-placement='top' title='800m time under 2 min'>Sub-2 Club</span>";
            }

            echo "<hr class='mr-md-4'>";

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
                                $badge = 'bg-award';
                            } else {
                                $badge = "bg-award-inv";
                            }
                            echo "<span class='badge " . $badge . " mx-1'>";
                            if (strpos($d, 'state') !== false || strpos($d, 'sectional') !== false || strpos($d, 'regional') !== false) {
                                echo "<img src='/assets/icons/ihsa.svg' height='10px' class='me-2'>";
                            }
                            echo $a . " (" . $y . ")";
                            echo "</span>";
                        }
                    }
                }
            }

            if (!empty($athnet)) {
                echo "<hr class='mr-md-4'>";
                echo "<a class='btn btn-primary btn-sm mx-1' href='https://www.athletic.net/CrossCountry/Athlete.aspx?AID=" . $athnet . "' role='button' target='_null'>Athletic.net XC</a>";
                echo "<a class='btn btn-primary btn-sm mx-1' href='https://www.athletic.net/TrackAndField/Athlete.aspx?AID=" . $athnet . "' role='button' target='_null'>Athletic.net TF</a>";
            }
            ?>
            <hr class="d-block d-md-none">
        </div>
        <div class="col-md-9 p-md-1">
            <h3 class="mb-0">Personal Records</h3>
            <hr class="mt-0 mb-0">
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
            <div class="row">
                <?php
                if (mysqli_num_rows($xc) > 0) {
                    if (mysqli_num_rows($tf) > 0) {
                        echo "<div class='col-md-6'>";
                    } else {
                        echo "<div class='col-md-12'>";
                    }
                    echo "<h3 class='mb-0'>Cross Country</h3>
                    <hr class='mt-0'>
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
                            echo "<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                        } else if ($row['sr'] == 1) {
                            echo "<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
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
                    echo "<h3 class='mb-0'>Track</h3>
                    <hr class='mt-0'>
                    <div class='table-responsive overflow-hidden'>
                        <table class='table table-condensed table-striped table-hover dataTable' id='tfPersonal'>
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Result</th>
                                    <th>Meet</th>
                                </tr>
                            </thead>
                            <tbody>";
                    while ($row = mysqli_fetch_array($tf)) {
                        $meet = $row['meet'];
                        echo "<tr class='clickable-row' data-href='/meet/" . $meet . "'>";
                        echo "<td>" . $row['event'] . "</td>";

                        echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='Finish Place: " . $row['place'] . "'>";
                        echo formatTime($row['result']);
                        if ($row['pr'] == 1) {
                            echo "<span class='badge bg-award ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                        } else if ($row['sr'] == 1) {
                            echo "<span class='badge bg-award-inv ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Season Record'>SR</span>";
                        }
                        if (isset($row['relay'])) {
                            echo "<span class='badge bg-info ms-1'>R</span>";
                        }
                        echo "</td>";

                        echo "<td><a href='/meet/" . $meet . "'>" . $meets[$meet] . "</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    const xcPersonal = new simpleDatatables.DataTable("#xcPersonal", {})
    const tfPersonal = new simpleDatatables.DataTable("#tfPersonal", {})
</script>

<?php $require = "charts"; ?>
<?php include("footer.php"); ?>