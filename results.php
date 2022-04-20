<?php $pgtitle = "Results"; ?>
<?php include("header.php"); ?>

<section id="content">
    <div class="container">
        <div class="table-responsive">
            <table class="table table-condensed table-sm table-hover" id="resultsTable">
                <thead>
                    <tr>
                        <th data-type="date" data-format="DD/MM/YYYY">Date</th>
                        <th>Name</th>
                        <th>Opponents</th>
                        <th>Location</th>
                        <th>Season</th>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($con, "SELECT * FROM meets WHERE (Official != 0 OR (`Official` = 0 && `Live` != '' && `Date` = '" . $todaydate . "')) ORDER BY Date DESC");
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['Date'] == $todaydate) {
                            $url = $row['Live'];
                        } else if (empty($row['Series'])) {
                            $url = "/meet/" . $row['id'];
                        } else {
                            $url = "/meet/" . $row['Series'] . "/" . $d = date("Y", strtotime($row['Date']));
                        }

                        //Badge
                        if (array_key_exists($row['Badge'], $badges)) {
                            $badge = "<span class='ms-1 badge " . $badges[$row['Badge']][0] . "' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $badges[$row['Badge']][2] . "'>" . $badges[$row['Badge']][1] . "</span>";
                        } else {
                            $badge = "";
                        }
                        if ($row['Official'] == "3") {
                            $badge = $badge . "<span class='badge bg-danger ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Results marked as Unofficial, and may not be complete.'>U</span>";
                        }

                        $d = date("n/j/Y", strtotime($row['Date']));
                        echo "<tr onclick = (window.location='" . $url . "#results')";
                        if ($row['Date'] == $todaydate) {
                            echo " class='row-highlight'";
                        }
                        echo ">";
                        echo "<td>" . $d . "</td>";
                        echo "<td><a href='" . $url . "#results'>" . $row['Name'] . $badge . "</a></td>";
                        $opponents = explode(",", $row['Opponents']);
                        if (count($opponents) > 7) {
                            echo "<td class='col-5' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $row['Opponents'] . "'>" . join(", ", array_slice($opponents, 0, 7)) . ", +" . (count($opponents) - 7) . " more</td>";
                        } else {
                            echo "<td class='col-5'>" . $row['Opponents'] . "</td>";
                        }
                        echo "<td><a href='" . $url . "#venue'>" . $row['Location'] . "</a></td>";
                        echo "<td>" . $row['Season'] . "</td>";
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="container">
        <p>*Have missing results or notice an issue? <a href="https://forms.gle/WBJbebPNkvjz3XQB9" target="_blank">Please fill out this form for manual review.</a></p>
        <a class="btn btn-primary" href="https://forms.gle/WBJbebPNkvjz3XQB9" role="button" target="_blank">Request Correction</a>
        <a href="https://docs.google.com/document/d/e/2PACX-1vTYaYKeX2zvWhx0BhUB6r2cV1serdWvaovwq81u51l05Sz55IunMbwQDkCFwiLzQl0uwZUdb5kwY4LP/pub">
            <p>Wondering where we find these results? Check here for our sources.</p>
        </a>
    </div>
</section>

<script>
    const resultsTable = new simpleDatatables.DataTable("#resultsTable", {
        searchable: true,
        "perPageSelect": [10, 25, 50, 100, 1000],
        "perPage": 50
    })
</script>
<?php include("footer.php"); ?>