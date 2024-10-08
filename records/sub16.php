<?php $pgtitle = "Sub-16 Club"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/header.php"); ?>
<div class="container mt-3">
    <h2>Sub-16 Club</h2>
    <div class="table-responsive">
        <table class="table table-condensed table-sm">
            <caption>Athletes with a cross country 3.00 Mile time faster than 16:00.00.</caption>
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
                $result = mysqli_query($con, "SELECT name,date,time,profile,meet FROM overallxc WHERE time < '16:00' AND school = 'Glenbrook South' AND distance = '3mi' ORDER BY time ASC");
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
                        echo "<td><a href='/meet/" . $meets[$row['meet']] . "#results'>" . $row['time'] . "</a></td>";
                        echo "</tr>";
                        $already[] = $row['profile'];
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>