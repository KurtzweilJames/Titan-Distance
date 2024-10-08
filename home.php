<?php
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
                    if (!empty($row['Live']) && ($row['Official'] == 0 || $row['Official'] == 4 || $row['Official'] == 5)) {
                        $live = $row['Live'];
                        if (strpos($live, "athletic.live") !== false || strpos($live, "live.athletic.net") !== false || strpos($live, "results.lakeshoreathleticservices.com") !== false || strpos($live, "live.timingmd.net") !== false || strpos($live, "anet.live") !== false || strpos($live, "live.palatinepack.com") !== false || strpos($live, "results.adkinstrak.com") !== false || strpos($live, "anet.live") !== false || strpos($live, "live.lakeshoreathleticservices.com") !== false) {
                            echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $row['Live'] . '" target="_blank"><img src="https://titandistance.com/assets/icons/athleticlive.svg" height="16px" alt="AthleticLIVE (Live Results)"></a>';
                        } else {
                            echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $row['Live'] . '" target="_blank">Live Results</a>';
                        }
                    } else if (!empty($row['Live'])) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '#results">Results</a>';
                    }
                    if (!empty($row['AthNet'])) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $row['AthNet'] . '" target="_blank"><img src="https://titandistance.com/assets/icons/AthleticNet.svg" height="16px" alt="AthleticNET"></a>';
                    }
                    if (!empty($row['Schedule'])) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '#schedule">Meet Schedule</a>';
                    }
                } else {
                    echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '">' . $row['Name'] . ' Home</a>';
                    if (!empty($row['Live']) && ($row['Official'] == 0 || $row['Official'] == 4 || $row['Official'] == 5)) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $row['Live'] . '" target="_blank">' . $row['Name'] . ' Live Results</a>';
                    } else if (!empty($row['Live'])) {
                        echo '<a type="button" class="btn btn-outline-light m-1" role="button" href="' . $url . '#results">' . $row['Name'] . ' Results</a>';
                    }
                }
            }
        } else {
            echo '<h1 class="text-uppercase text-center text-white">Home of</h1>';
            echo '<img src="/assets/logos/white.svg" class="w-75" alt="Titan Distance">';
            // echo '<img src="/assets/logos/graduation_white.svg" class="w-75" alt="Titan Distance">';
        }
        ?>
    </div>
</div>

<div class="container mt-2">
    <?php
    $currentdate = date('Y-m-d');
    $result = mysqli_query($con, "SELECT * FROM tdas WHERE startdate<='" . $currentdate . "' AND enddate>='" . $currentdate . "' ORDER BY id DESC");
    while ($row = mysqli_fetch_array($result)) {
        if ($row['type'] == 0) {
            $type = "danger";
        } else if ($row['type'] == 1) {
            $type = "info";
        } else if ($row['type'] == 2) {
            $type = "warning";
        } else if ($row['type'] == 3) {
            $type = "success";
        }

        if ($row['nostick'] == 0) {
            //$type = $type . " sticky-top";
        }

        if (!empty($row['link'])) {
            $type = $type . " clickable-row";
        }
        if (!empty($row['web'])) {
            echo "<div class='alert alert-" . $type . " text-center' role='alert' onclick = window.location='" . $row['link'] . "'><strong style='text-transform: uppercase;'>" . $row['title'] . ": </strong>" . $row['web'] . "</div>";
        }
    }
    ?>

    <div class="row">
        <div class="col-md-8 order-2 order-lg-1">
            <div class="list-group list-group-flush">
            <a class="list-group-item" href="/records/top7">
                <div class="row no-gutters overflow-hidden">
                    <div class="col-md-4"><img src="assets/images/blog/ROTW.jpg" class="img-fluid float-start" alt="Record of the Week"></div>
                    <div class="col-md-8 text-center text-md-start"><div class="fw-bold">Record of the Week: Cross Country Teams by Top 7</div>
                    <p class="mb-3">Compare the top 7 on each cross country team since 1980, as well as the average time for the scoring 5.</p>
                    </div>
                </div>
            </a>
                <?php
                // LATEST NEWS
                $result = mysqli_query($con, "SELECT * FROM news WHERE public = 1 ORDER BY date DESC LIMIT 6");
                while ($row = mysqli_fetch_array($result)) {
                    $content = strip_tags($row['content']);
                    $image = "assets/images/" . $row['image'];
                    $date = date("F j, Y", strtotime($row['date']));

                    if (!empty($row['link'])) {
                        if (strpos($row['link'], "titandistance.com") !== false) {
                            echo "<a class='list-group-item' href='" . $row['link'] . "'>";
                        } else {
                            echo "<a class='list-group-item' href='" . $row['link'] . "' target='_blank'>";
                        }
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
                    if (!empty($row['link']) && strpos($row['link'], "titandistance.com") == false) {
                        echo '<i class="bi bi-box-arrow-up-right ms-1"></i>';
                    }
                    echo "</div>";

                    echo "<p class='mb-3'>" . substr($content, 0, 250);
                    if (substr($content, 0, 250) !== $content) {
                        echo "...";
                    }
                    echo "</p>";


                    echo "<p class='mb-0'><small class='text-muted'>Published on " . $date . $status . "</small></p>";
                    echo "</div></div></a>";
                }
                ?>
            </div>
        </div>
        <div class="col-md-4 order-1 order-lg-2">
            <div class="card mb-3">
                <div class="card-header">
                    <a class="h6" href="/schedule">Upcoming Schedule</a>
                </div>
                <div class="card-body p-1 overflow-auto" style="max-height: 250px;">
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
                                echo "<span class='badge text-bg-active rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='Meet Day!'>" . date('D, M d', strtotime($row['Date'])) . "</span>";
                            } else {
                                echo "<span class='badge text-bg-primary rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $countdown . " Days Away'>" . date('D, M d', strtotime($row['Date'])) . "</span>";
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
            <!-- <div class="card mb-3 clickable-row" data-href="/workouts">
                <div class="card-header">
                    <a class="h6" href="/workouts">Today's Workout</a>
                </div>
                <div class="card-body p-1">
                    <ol class="list-group list-group-flush">
                        <?php
                        // $result = mysqli_query($con, "SELECT * FROM workouts WHERE date='" . $todaydate . "' LIMIT 1");
                        // while ($row = mysqli_fetch_array($result)) {
                        //     echo "<a class='list-group-item d-flex justify-content-between align-items-start px-1' href='/workouts'>";
                        //     echo "<div class='ms-2 me-auto'>";
                        //     if (!empty($row['workout'])) {
                        //         echo "<div class='fw-bold'>" . $row['workout'] . "</div>";
                        //         echo "<ul class='list-unstyled'>";
                        //         foreach (["1mileage", "2mileage", "3mileage"] as $mileage) {
                        //             if (!empty($row[$mileage])) {
                        //                 echo "<li><strong class='me-1'>Group " . substr($mileage, 0, 1) . ":</strong>" . $row[$mileage] . "</li>";
                        //             }
                        //         }
                        //         if ($row['weights'] >= 1) {
                        //             echo "<span class='badge text-bg-primary'><i class='bi bi-fire me-1'></i>Weight Circuit";
                        //             if ($row['weights'] > 1) {
                        //                 echo "s (x" . $row['weights'] . ")";
                        //             }
                        //             echo "</span>";
                        //         }
                        //         if (isset($row['strides']) && $row['strides'] !== 0) {
                        //             echo " <span class='badge text-bg-primary text-white'>" . $row['strides'] . " strides</span>";
                        //         }
                        //         if ((empty($row['weights']) && empty($row['strides'])) && !empty($row['notes'])) {
                        //             // echo "<br>";
                        //         }
                        //         if (!empty($row['notes'])) {
                        //             echo "*" . $row['notes'];
                        //         }
                        //         echo "</ul>";
                        //     } else {
                        //         if (!empty($row['practicename'])) {
                        //             echo "<div class='fw-bold'>" . $row['practicename'] . " @ " . date("g:i a", strtotime($row['practicetime'])) . "</div>";
                        //         } else {
                        //             echo "<div class='fw-bold'>No Organized Practice Today</div>";
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
                    <a class="h6" href="/results">Latest Results</a>
                </div>
                <div class="card-body p-1 overflow-auto" style="height: 250px;">
                    <ol class="list-group list-group-flush">
                        <?php
                        $result = mysqli_query($con, "SELECT * FROM meets WHERE Official != 0 ORDER BY Date DESC");
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
                            if ($row['Date'] == $todaydate && $row['Official'] == 4) {
                                echo "<span class='badge text-bg-success rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top'>In Progress</span>";
                            } else if ($row['Date'] == $todaydate) {
                                echo "<span class='badge text-bg-active rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='Meet Day!'>" . date('D, M d', strtotime($row['Date'])) . "</span>";
                            } else if (date("Y", strtotime($row['Date'])) == date("Y")) {
                                echo "<span class='badge text-bg-primary rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $countup . " Days Ago" . "'>" . date('D, M d', strtotime($row['Date'])) . "</span>";
                            } else {
                                echo "<span class='badge text-bg-primary rounded-pill' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $countup . " Days Ago" . "'>" . date('D, M d, Y', strtotime($row['Date'])) . "</span>";
                            }
                            echo "</a>";
                        }
                        ?>
                    </ol>
                </div>
            </div>
            <div class="card mb-3 p-0">
                <div class="card-header">
                    <a class="h6" href="/search">Site Search</a>
                </div>
                <div class="card-body">
                    <button class="form-control hover-card" id="searchBarHome" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="bi bi-search me-2"></i>Search Here...</button>
                </div>
            </div>

            <div class="card mb-3 p-0 d-none d-lg-block">
                <div class="card-body p-0">
                    <a class="twitter-timeline" data-height="500" data-dnt="true" href="https://twitter.com/TitanDistance?ref_src=twsrc%5Etfw">Tweets by TitanDistance</a>
                    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </div>
            <div class="card mb-3 p-0 d-none d-lg-block" style="height:160px">
                <div class="card-body p-0">
                    <iframe title="Strava Club" allowtransparency="" frameborder="0" height="160" width="100%" scrolling="no" src="https://www.strava.com/clubs/504121/latest-rides/5f0253b3bbd931bdde9ec866c542f5b436c33a1b?show_rides=false"></iframe>
                </div>
            </div>
            <div class="card mb-3 p-0 d-none d-lg-block">
                <a class="mx-auto d-block" style="max-width: 75%;" href="/meet/indoorconference/2024">
                    <img src="/assets/images/specials/champs/2024indoorchampions.png" class="img-fluid" alt="CSL Conference Champions">
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>