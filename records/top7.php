<?php $pgtitle = "Top 7 by Year"; ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . "/header.php"); ?>

<div class="container">
    <div class="d-flex justify-content-between mb-2 flex-wrap">
        <h2>Top 7 by Year</h2>
        <!-- Dropdown for sorting -->
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-arrow-down-up"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a id="sort-by-year" class="dropdown-item">Sort by Year</a></li>
                <li><a id="sort-by-time" class="dropdown-item">Sort by Time</a></li>
            </ul>
        </div>
    </div>

    <?php
    $result = mysqli_query($con, "SELECT id, Date, Series FROM meets");
    $meets = [];

    while ($row = mysqli_fetch_array($result)) {
        if (empty($row['Series'])) {
            $meets[$row['id']] = $row['id'];
        } else {
            $meets[$row['id']] = $row['Series'] . "/" . date("Y", strtotime($row['Date']));
        }
    }

    function generateTable($con, $year, $count = 7, $meets)
    {
        $result = mysqli_query($con, "SELECT DISTINCT time, name, date, profile, meet FROM (SELECT time, name, date, profile, meet, ROW_NUMBER() OVER (PARTITION BY name ORDER BY time ASC) AS row_num FROM overallxc WHERE distance = '3mi' AND school = 'Glenbrook South' AND YEAR(date) = $year) AS ranked_times WHERE row_num = 1 ORDER BY time ASC LIMIT $count;");
        
        $totalSeconds = 0;
        $rowNum = 0;

        // Collect times for top 5 athletes to calculate average
        while ($row = mysqli_fetch_array($result)) {
            if ($rowNum < 5) {
                list($minutes, $seconds) = explode(':', $row['time']);
                $totalSeconds += $minutes * 60 + floatval($seconds);
                $rowNum++;
            }
        }

        // Calculate average time
        $averageSeconds = $totalSeconds / 5;
        $averageTime = gmdate('i:s', $averageSeconds);

        // Print the table
        echo "<div class='col mb-4' id='$year' data-avg-time='$averageSeconds'>";
        echo "<h4>$year</h4>";
        echo "<table class='table table-condensed table-sm mb-0'>";
        echo "<thead>
            <tr>
                <th>Name</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>";

        // Reset result pointer and print the table
        mysqli_data_seek($result, 0); // Reset result pointer to loop through again
        $rowNum = 1;
        while ($row = mysqli_fetch_array($result)) {
            if ($rowNum == 5) {
                echo "<tr class='border-bottom border-primary'>";
            } else {
                echo "<tr>";
            }
            echo !empty($row['profile']) ? "<td><a href='/athlete/" . $row['profile'] . "'>" . $row['name'] . "</a></td>" : "<td>" . $row['name'] . "</td>";
            echo "<td><a href='/meet/" . $meets[$row['meet']] . "#results'>" . $row['time'] . "</a></td>";
            echo "</tr>";
            $rowNum++;
        }

        echo "</tbody></table>";
        echo "<caption>Top 5 Average Time: $averageTime</caption>";
        echo "</div>";
    }
    ?>

    <div class="row row-cols-2 row-cols-lg-4 table-container">
        <?php for ($year = 2024; $year >= 1980; $year--) {
            generateTable($con, $year, 7, $meets);
        } ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortByYearBtn = document.querySelector('#sort-by-year');
    const sortByTimeBtn = document.querySelector('#sort-by-time');

    sortByYearBtn.addEventListener('click', function() {
        sortTables('year');
    });

    sortByTimeBtn.addEventListener('click', function() {
        sortTables('time');
    });

    function sortTables(sortBy) {
        const container = document.querySelector('.table-container');
        const tables = Array.from(container.children);

        tables.sort((a, b) => {
            const yearA = parseInt(a.id);
            const yearB = parseInt(b.id);
            const avgTimeA = parseFloat(a.getAttribute('data-avg-time'));
            const avgTimeB = parseFloat(b.getAttribute('data-avg-time'));

            if (sortBy === 'year') {
                return yearB - yearA; // Sort by year descending
            } else if (sortBy === 'time') {
                return avgTimeA - avgTimeB; // Sort by average time ascending
            }
        });

        tables.forEach(table => container.appendChild(table)); // Re-append tables in sorted order
    }
});
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>