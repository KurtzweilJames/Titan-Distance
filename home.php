<?php $template = "home";
include('header.php') ?>

<div class="tdmastheadbkg d-flex justify-content-center">
    <div class="container text-center my-auto p-1" style="background-color: rgba(7,55,99,0.8);">
        <?php
        $result = mysqli_query($con, "SELECT * FROM meets WHERE date = '" . $todaydate . "' AND season = '" . $currentseason . "' AND NOT(`Status` <=> 'C')");
        $numrows = mysqli_num_rows($result);
        if ($numrows > 0) {
            echo '<img src="/assets/logos/white.svg" class="w-75" alt="Titan Distance">';
            if ($numrows > 1) {
                echo '<h1 class="text-uppercase text-center text-white">Meet Day</h1>';
            }
            while ($row = mysqli_fetch_array($result)) {
                if (empty($row['Series'])) {
                    $url = "/meet/" . $row['id'];
                } else {
                    $url = "/meet/" . $row['Series'] . "/" . $d = date("Y", strtotime($row['Date']));
                }


                if ($numrows == 1) {
                    echo '<h1 class="text-uppercase text-center text-white">' . $row['Name'] . ' Meet Day</h1>';
                    echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '">Meet Home</a>';
                    if (!empty($row['Live']) && $row['Official'] == 0) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $row['Live'] . '" target="_blank">Live Results</a>';
                    } else if (!empty($row['Live'])) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '#results">Results</a>';
                    }
                    if (!empty($row['AthNet'])) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $row['AthNet'] . '" target="_blank">Athletic.net</a>';
                    }
                } else {
                    echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '">' . $row['Name'] . ' Home</a>';
                    if (!empty($row['Live']) && $row['Official'] == 0) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $row['Live'] . '" target="_blank">' . $row['Name'] . ' Live Results</a>';
                    } else if (!empty($row['Live'])) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '#results">' . $row['Name'] . ' Results</a>';
                    }
                }
            }
        } else {
            echo '<h1 class="text-uppercase text-center text-white">Home of</h1>';
            echo '<img src="/assets/logos/white.svg" class="w-75" alt="Titan Distance">';
        }
        ?>
    </div>
</div>

<div class="container mt-2">
    <div class="row">
        <div class="col-md-8 order-2 order-lg-1">
            <ul class="list-group list-group-flush">
                <?php
                // LATEST NEWS
                $result = mysqli_query($con, "SELECT * FROM news WHERE public = 1 ORDER BY date DESC LIMIT 8");
                while ($row = mysqli_fetch_array($result)) {
                    $content = strip_tags($row['content']);
                    $image = "assets/images/" . $row['image'];
                    $date = date("F j, Y", strtotime($row['date']));

                    if (!empty($row['link'])) {
                        echo "<a class='list-group-item' href='" . $row['link'] . "' target='_blank'>";
                    } else {
                        echo "<a class='list-group-item' href='/news/" . $row['slug'] . "'>";
                    }

                    echo "<div class='row no-gutters overflow-hidden'><div class='col-md-4'>";
                    if (file_exists($image)) {
                        echo "<img src='" . $image . "' class='img-fluid float-start' alt='" . $row['title'] . "'>";
                    } else {
                        echo "<img src='assets/images/blog/blank.png' class='img-fluid float-start' alt='" . $row['title'] . "'>";
                    }
                    if (!empty($row['info'])) {
                        $status = " // Meet Information";
                    } else if (!empty($row['recap'])) {
                        $status = " // Meet Recap";
                    } else {
                        $status = " // " . $row['cat'];
                    }
                    echo "</div>";
                    echo "<div class='col-md-8 text-center text-md-start'>";

                    echo "<div class='fw-bold'>" . $row['title'];
                    if (!empty($row['link'])) {
                        echo '<i class="bi bi-box-arrow-up-right ms-1"></i>';
                    }
                    echo "</div>";

                    echo "<p mb-3'>" . substr($content, 0, 225) . "...</p>";
                    echo "<p><small class='text-muted'>Published on " . $date . $status . "</small></p>";
                    echo "</div></div></a>";
                }
                ?>
            </ul>
        </div>
        <div class="col-md-4 order-1 order-lg-2">
            <!-- <div class="card mb-3 clickable-row" data-href="/workouts">
                <div class="card-header">
                    <a href="/workouts">Today's Workout</a>
                </div>
                <div class="card-body p-1">
                    <ol class="list-group list-group-flush">
                        <?php
                        // $result = mysqli_query($con,"SELECT * FROM workouts WHERE date='".$todaydate."' LIMIT 1");
                        // while($row = mysqli_fetch_array($result)) {
                        //     echo "<a class='list-group-item d-flex justify-content-between align-items-start px-1' href='/workouts'>";
                        //     echo "<div class='ms-2 me-auto'>";
                        //     if (!empty($row['workout'])) {
                        //         echo "<div class='fw-bold'>".$row['workout']."</div>";
                        //         echo "<ul class='list-unstyled'>";
                        //         foreach (["1mileage","2mileage","3mileage"] as $mileage) {
                        //             if (!empty($row[$mileage])) {
                        //                 echo "<li><strong class='me-1'>Group ".substr($mileage,0,1).":</strong>".$row[$mileage]."</li>";
                        //             }
                        //         }
                        //         if ($row['weights'] >= 1) {
                        //             echo "<span class='badge bg-primary'>Weight Circuit";
                        //             if ($row['weights'] > 1) {
                        //                 echo "s (x".$row['weights'].")";
                        //             }
                        //                 echo "</span>";
                        //             }
                        //             if (isset($row['strides']) && $row['strides'] !== 0) {
                        //                 echo " <span class='badge bg-primary text-white'>".$row['strides']." strides</span>";
                        //             }
                        //             if ((empty($row['weights']) && empty($row['strides'])) && !empty($row['notes'])) {
                        //                 echo "<br>";
                        //             }
                        //             if (!empty($row['notes'])) {
                        //                 echo "*".$row['notes'];
                        //             }
                        //         echo "</ul>";
                        //     } else {
                        //         if (!empty($row['practicename'])) {
                        //             echo "<div class='fw-bold'>".$row['practicename']." @ ".date("g:i a",strtotime($row['practicetime']))."</div>";
                        //         }
                        //         echo "Workout not Published";
                        //     }
                        //     echo "</div>";
                        //     echo "</a>";
                        // }
                        ?>
                    </ol>
                </div>
            </div> -->
            <div class="card mb-3">
                <div class="card-header">
                    <a href="/schedule">Upcoming Schedule</a>
                </div>
                <div class="card-body p-1 overflow-auto" style="height: 250px;">
                    <ol class="list-group list-group-flush">
                        <?php
                        $result = mysqli_query($con, "SELECT * FROM meets WHERE Date >= '" . $todaydate . "' AND Official != 1 AND Official != 2 AND NOT(`Status` <=> 'C') ORDER BY Date");
                        while ($row = mysqli_fetch_array($result)) {
                            if (empty($row['Series'])) {
                                $url = "/meet/" . $row['id'];
                            } else {
                                $url = "/meet/" . $row['Series'] . "/" . date("Y", strtotime($row['Date']));
                            }
                            if (array_key_exists($row['Badge'], $badges)) {
                                $badge = "<span class='ms-1 badge " . $badges[$row['Badge']][0] . "' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $badges[$row['Badge']][2] . "'>" . $badges[$row['Badge']][1] . "</span>";
                            } else {
                                $badge = "";
                            }
                            $countdown = round((strtotime($row["Date"]) - strtotime($todaydate)) / 86400);
                            echo "<a class='list-group-item d-flex justify-content-between align-items-start px-1' href='" . $url . "'>";
                            echo "<div class='ms-2 me-auto'>";
                            echo "<div class='fw-bold'>" . $row['Name'] . $badge . "</div>";
                            if ($row['Location'] !== "David Pasquini Fieldhouse" && $row['Location'] !== "John Davis Titan Stadium" && $row['Location'] !== "Glenbrook South High School") {
                                echo "@ ";
                            }
                            echo $row['Location'] . "</div>";
                            if ($row['Date'] == $todaydate) {
                                echo "<span class='badge bg-award rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='Meet Day!'>" . date('D, M d', strtotime($row['Date'])) . "</span>";
                            } else {
                                echo "<span class='badge bg-award-inv rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $countdown . " Days Away'>" . date('D, M d', strtotime($row['Date'])) . "</span>";
                            }
                            echo "</a>";
                        }
                        if (mysqli_num_rows($result) == 0) {
                            echo '<div class="list-group-item d-flex justify-content-between align-items-start px-1"><div class="ms-2 me-auto"><div class="fw-bold">No Meets Currently Scheduled</div>Check Back Soon</div></div>';
                        }
                        ?>
                    </ol>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <a href="/results">Latest Results</a>
                </div>
                <div class="card-body p-1 overflow-auto" style="height: 250px;">
                    <ol class="list-group list-group-flush">
                        <?php
                        $result = mysqli_query($con, "SELECT * FROM meets WHERE Official != 0 ORDER BY Date DESC LIMIT 50");
                        while ($row = mysqli_fetch_array($result)) {
                            if (empty($row['Series'])) {
                                $url = "/meet/" . $row['id'] . "#results";
                            } else {
                                $url = "/meet/" . $row['Series'] . "/" . date("Y", strtotime($row['Date'])) . "#results";
                            }
                            if (array_key_exists($row['Badge'], $badges)) {
                                $badge = "<span class='ms-1 badge " . $badges[$row['Badge']][0] . "' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $badges[$row['Badge']][2] . "'>" . $badges[$row['Badge']][1] . "</span>";
                            } else {
                                $badge = "";
                            }
                            $countup = round((strtotime($todaydate) - strtotime($row["Date"])) / 86400);
                            echo "<a class='list-group-item d-flex justify-content-between align-items-start px-1' href='" . $url . "'>";
                            echo "<div class='ms-2 me-auto'>";
                            echo "<div class='fw-bold'>" . $row['Name'] . $badge . "</div>";
                            if ($row['Location'] !== "David Pasquini Fieldhouse" && $row['Location'] !== "John Davis Titan Stadium" && $row['Location'] !== "Glenbrook South High School") {
                                echo "@ ";
                            }
                            echo $row['Location'] . "</div>";
                            echo "<span class='badge bg-award-inv rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $countup . " Days Ago" . "'>" . date('D, M d', strtotime($row['Date'])) . "</span>";
                            echo "</a>";
                        }
                        ?>
                    </ol>
                </div>
            </div>
            <div class="card mb-3 p-0 d-none d-lg-block">
                <div class="card-body p-0">
                    <!-- Twitter Embed -->
                </div>
            </div>
            <div class="card mb-3 p-0 d-none d-lg-block" style="height:160px">
                <div class="card-body p-0">
                    <!-- Strava Embed -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>