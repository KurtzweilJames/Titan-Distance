<?php $pgtitle = "Course Records"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/header.php"); ?>

<?php
$result = mysqli_query($con, "SELECT id,Name FROM meets WHERE Date <= '" . $todaydate . "'");
while ($row = mysqli_fetch_array($result)) {
    $meets[$row['id']] = $row['Name'];
}

$result = mysqli_query($con, "SELECT * FROM locations WHERE xc = 1");
while ($row = mysqli_fetch_array($result)) {
    $locations[$row['name']] = $row['primarydistance'];
    $verified[$row['name']] = $row['verified'];
    $startdate[$row['name']] = $row['startdate'];
}
?>
<section id="content">
    <div class="container mt-4">
        <div class="row row-cols-1 row-cols-md-2">
            <?php
            foreach ($locations as $location => $primary) {
                echo "<div class='col'>";
                echo "<h2>" . $location . "</h2>";
                echo "<h4>Top " . $primary . " by GBS Athletes";
                if ($verified[$location] == 1) {
                    echo "<i class=\"ms-4 text-success bi bi-patch-check-fill\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Verified as Accurate & Updated\"></i>";
                } else {
                    echo "<i class=\"ms-4 text-warning bi bi-patch-exclamation-fill\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Some Results May be Missing\"></i>";
                }
                echo "</h4>";

                if (!empty($startdate[$location])) {
                    $result = mysqli_query($con, "SELECT * FROM meets WHERE Location = '" . addslashes($location) . "' AND Date >= '" . $startdate[$location] . "' ORDER BY Date DESC");
                } else {
                    $result = mysqli_query($con, "SELECT * FROM meets WHERE Location = '" . addslashes($location) . "' ORDER BY Date DESC");
                }
                $locationmeets = [];
                while ($row = mysqli_fetch_array($result)) {
                    array_push($locationmeets, $row['id']);
                }
                echo "
                                    <div class=\"table-responsive\">
                                        <table class=\"table table-condensed table-sm table-hover\">";
                if ($verified[$location] != 1) {
                    echo "<caption>Only results imported into our database will be shown in this table- performances may be missing.</caption>";
                }
                echo "<thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Athlete</th>
                                                    <th>Year</th>
                                                </tr>
                                            </thead>
                                            <tbody>";
                $duplicates = [];
                $num = 0;
                $result2 = mysqli_query($con, "SELECT * FROM overallxc" . " WHERE meet IN (" . implode(",", $locationmeets) . ") AND distance = '" . $primary . "' AND (school = 'Glenbrook South' OR school = 'Glenbrook South*') ORDER BY time ASC LIMIT 50");
                while ($row2 = mysqli_fetch_array($result2)) {
                    if (in_array($row2['name'], $duplicates) or $num >= 10) {
                        continue;
                    }
                    if (date("Y", strtotime($row2['date'])) == $currentyear) {
                        echo "<tr class='row-highlight'>";
                    } else {
                        echo "<tr>";
                    }
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
                echo "</div>";
            }
            ?>
        </div>

        <div class="my-3 d-flex justify-content-center">
            <a class="btn btn-primary" href="https://docs.google.com/spreadsheets/d/1ZtjRJh9UTv_eRSrQZr_6sS_SXlX5A9iftSoQsZiwFp0/edit#gid=573011141" role="button" target="_blank">Complete Listing</a>
        </div>

    </div>
</section>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>