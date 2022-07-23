<div class="bg-light" id="top-bar">
    <div class="container d-flex justify-content-between">
        <div class="mr-auto p-2 m-0">
            <?php
            $result = mysqli_query($con, "SELECT * FROM meets WHERE Date = '" . $todaydate . "' AND Official = 0 AND NOT(`Status` <=> 'C')");
            if (isset($_SESSION["loggedin"])) {
                if ($template == "meet") {
                    echo "<a href='/admin/meet?id=" . $id . "'><i class='bi bi-pencil-fill'></i> Edit Meet (ID = " . $id . ")</a><a href='/admin/results?id=" . $id . "'><i class='bi bi-list-ul ms-2'></i> Results Manager</a>";
                } else if ($template == "news") {
                    echo "<a href='/admin/news?id=" . $id . "'><i class='bi bi-pencil-fill'></i> Edit News Article</a>";
                } else {
                    echo "Welcome, " . $_SESSION["username"] . " <a class='ml-2' href='/admin'><i class='bi bi-gear-wide-connected'></i> Return to Admin</a>";
                }
            } else if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    if (empty($row['Series'])) {
                        $url = "/meet/" . $row['id'];
                    } else {
                        $url = "/meet/" . $row['Series'] . "/" . date("Y", strtotime($row['Date']));
                    }

                    if (!empty($row['Live'])) {
                        echo "<a href='" . $row['Live'] . "' target='_blank'><strong><i class='bi bi-bar-chart-fill me-1'></i>" . $row['Name'] . " Live Results</strong></a>";
                    } else {
                        echo "<a href='" . $url . "'><strong><i class='bi bi-info-circle-fill me-1'></i>" . $row['Name'] . " Information</strong></a>";
                    }
                    if (mysqli_num_rows($result) > 1) {
                        echo "<br>";
                    }
                }
            } else {
                echo "<div class='text-muted d-none d-md-block'>Home of Glenbrook South Cross Country and Distance Track</div>";
                // echo "<div class='text-muted d-none d-md-block'>Track is Back!</div>";
            }
            ?>
        </div>
        <?php
        $weatherfile = file_get_contents("https://titandistance.com/api/weather.php");
        $jsonweather = json_decode($weatherfile);
        ?>
        <a class="p-2 m-0 clickable-row my-auto text-reset" id="weather-widget" href="/weather" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom" title="Weather: <?php echo $jsonweather->current->description; ?>
                   <br>Feels Like: <?php echo $jsonweather->current->feelslike; ?>°F<br>Wind: <?php echo $jsonweather->current->wind; ?>mph<br>Temperature:
                    <?php echo $jsonweather->current->temp; ?>°F">
            <?php
            echo "<span id='temp'>" . $jsonweather->current->temp . "°F</span>";
            echo "<i class='mb-0 mx-1 " . $jsonweather->current->icon . "'></i>";
            if ($jsonweather->current->wind > 10) {
                echo "<i class='mb-0 mx-1 bi bi-wind'></i>";
            }
            ?>
        </a>
    </div>
</div>