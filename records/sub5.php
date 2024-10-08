<?php $pgtitle = "Sub-5 Club"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/header.php"); ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-xl-6">
            <h2>Sub-5 Club</h2>
            <p>*This list is <u>not</u> exhaustive, and only includes times in our database.</p>
            <div class="table-responsive">
                <table class="table table-condensed table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Name</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($con, "SELECT id,Date,Series FROM meets");
                        while ($row = mysqli_fetch_array($result)) {
                            if (empty($row['Series'])) {
                                $meets[$row['id']] = $row['id'];
                            } else {
                                $meets[$row['id']] = $row['Series'] . "/" . $d = date("Y", strtotime($row['Date']));
                            }
                        }

                        $already = [];
                        $result = mysqli_query($con, "SELECT name,date,result,profile,meet FROM overalltf WHERE result < '5:00' AND school = 'Glenbrook South' AND event = '1600m' AND season != 'c' ORDER BY result ASC");
                        while ($row = mysqli_fetch_array($result)) {
                            if (!in_array($row['profile'], $already)) {
                                if (date("Y", strtotime($row['date'])) == $currentyear) {
                                    echo "<tr class='row-highlight'>";
                                } else {
                                    echo "<tr>";
                                }
                                echo "<td><a href='/meet/" . $meets[$row['meet']] . "'>" . date('Y', strtotime($row['date'])) . "</a></td>";
                                if (!empty($row['profile'])) {
                                    echo "<td><a href='/athlete/" . $row['profile'] . "'>" . $row['name'] . "</a></td>";
                                } else {
                                    echo "<td>" . $row['name'] . "</td>";
                                }
                                echo "<td><a href='/meet/" . $meets[$row['meet']] . "#results'>" . $row['result'] . "</a></td>";
                                echo "</tr>";
                                $already[] = $row['profile'];
                            }
                        }
                        ?>
                    </tbody>
                    <caption>Non-GBS events (Community) are not included. Only includes Track performances.</caption>
                </table>
            </div>
        </div>
        <div class="col-xl-6">
            <h2>Sub-2 Club</h2>
            <div class="table-responsive">
                <table class="table table-condensed table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Name</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $already = [];
                        $result = mysqli_query($con, "SELECT name,date,result,profile,meet FROM overalltf WHERE result < '2:00' AND school = 'Glenbrook South' AND event = '800m' AND season != 'c' ORDER BY result ASC");
                        while ($row = mysqli_fetch_array($result)) {
                            if (!in_array($row['profile'], $already)) {
                                if (date("Y", strtotime($row['date'])) == $currentyear) {
                                    echo "<tr class='row-highlight'>";
                                } else {
                                    echo "<tr>";
                                }
                                echo "<td><a href='/meet/" . $meets[$row['meet']] . "'>" . date('Y', strtotime($row['date'])) . "</a></td>";
                                if (!empty($row['profile'])) {
                                    echo "<td><a href='/athlete/" . $row['profile'] . "'>" . $row['name'] . "</a></td>";
                                } else {
                                    echo "<td>" . $row['name'] . "</td>";
                                }
                                echo "<td><a href='/meet/" . $meets[$row['meet']] . "#results'>" . $row['result'] . "</a></td>";
                                echo "</tr>";
                                $already[] = $row['profile'];
                            }
                        }
                        ?>
                    </tbody>
                    <caption>Non-GBS events (Community) are not included. Only includes Track performances. List captures all Sub-2 Performances.</caption>
                </table>
            </div>
            <hr class="my-2 d-none d-xl-block">
            <h2>Sub-10 Club</h2>
            <p>*This list is <u>not</u> exhaustive, and only includes times in our database.</p>
            <div class="table-responsive">
                <table class="table table-condensed table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Name</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $already = [];
                        $result = mysqli_query($con, "SELECT name,date,result,profile,meet FROM overalltf WHERE result < '10:00' AND school = 'Glenbrook South' AND event = '3200m' AND season != 'c' ORDER BY result ASC");
                        while ($row = mysqli_fetch_array($result)) {
                            if (!in_array($row['profile'], $already)) {
                                if (date("Y", strtotime($row['date'])) == $currentyear) {
                                    echo "<tr class='row-highlight'>";
                                } else {
                                    echo "<tr>";
                                }
                                echo "<td><a href='/meet/" . $meets[$row['meet']] . "'>" . date('Y', strtotime($row['date'])) . "</a></td>";
                                if (!empty($row['profile'])) {
                                    echo "<td><a href='/athlete/" . $row['profile'] . "'>" . $row['name'] . "</a></td>";
                                } else {
                                    echo "<td>" . $row['name'] . "</td>";
                                }
                                echo "<td><a href='/meet/" . $meets[$row['meet']] . "#results'>" . formatTime($row['result']) . "</a></td>";
                                echo "</tr>";
                                $already[] = $row['profile'];
                            }
                        }
                        ?>
                    </tbody>
                    <caption>Non-GBS events (Community) are not included. Only includes Track performances.</caption>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>