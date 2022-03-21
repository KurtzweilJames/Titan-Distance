<?php $pgtitle = "Schedule"; ?>
<?php include("header.php"); ?>
<?php
$season = htmlspecialchars($_GET["season"]);
if (empty($season)) {
    $season = $currentseason;
}
$result = mysqli_query($con, "SELECT UNIQUE Season FROM meets ORDER BY Date DESC");
while ($row = mysqli_fetch_array($result)) {
    $allSeasons[] = $row['Season'];
}
?>

<section id="content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <i class="my-auto">*Schedule is subject to change.</i>
            <div class="form-group d-none d-md-block">
                <select class="form-select" id="SeasonSelect" onchange="showSeason(this.value)">
                    <option value="" disabled>Select a Season:</option>
                    <?php
                    foreach ($allSeasons as $s) {
                        echo "<option value='" . $s . "' name='" . $s . "'";
                        if ($s == $currentseason) {
                            echo "class='bg-info'";
                        }
                        if ($s == $season) {
                            echo "selected";
                        }
                        echo ">" . $s . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="button" class="btn btn-primary btn-sm text-center" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-calendar-plus-fill me-1"></i>Add to Your Calendar
            </button>
        </div>
        <div class="table-responsive" id="scheduleContainer">
            <table class="table table-condensed table-hover table-sm" id="scheduleTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Opponents</th>
                        <th>Levels</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $result = mysqli_query($con, "SELECT * FROM meets WHERE Season = '" . $season . "' ORDER BY Date ASC");

                    while ($row = mysqli_fetch_array($result)) {
                        if (empty($row['Series'])) {
                            $url = "/meet/" . $row['id'];
                        } else {
                            $url = "/meet/" . $row["Series"] . "/" . $d = date("Y", strtotime($row['Date']));
                        }
                        $dow = date("D", strtotime($row['Date']));
                        $d = date("n/j", strtotime($row['Date']));

                        $dir = "<a href='" . $url . "#venue'>" . $row['Location'] . "</a>";

                        if (array_key_exists($row['Badge'], $badges)) {
                            $badge = "<span class='ms-1 badge " . $badges[$row['Badge']][0] . "' data-bs-toggle='tooltip' data-bs-placement='top' title='" . $badges[$row['Badge']][2] . "'>" . $badges[$row['Badge']][1] . "</span>";
                        } else {
                            $badge = "";
                        }


                        echo "<tr onclick = window.location='" . $url . "'>";
                        echo "<td>" . $dow . "</td>";
                        echo "<td>" . $d . "</td>";
                        echo "<td><a href='" . $url . "'>" . $row['Name'] . $badge . "</a></td>";
                        if (strlen($row['Opponents']) > 100) {
                            echo "<td data-bs-toggle='tooltip' data-bs-placement='top' title='" . $row['Opponents'] . "'>" . substr($row['Opponents'], 0, 100) . "..." . "</td>";
                        } else {
                            echo "<td>" . $row['Opponents'] . "</td>";
                        }
                        echo "<td>" . $row['Levels'] . "</td>";
                        echo "<td>" . $dir . "</td>";
                        echo "</tr>";

                        if (!empty($row['Day2Time'])) {
                            echo "<tr class='clickable-row' data-href='" . $url . "'>";
                            echo "<td>" . date("D", strtotime($row['Day2Time'])) . "</td>";
                            echo "<td>" . date("n/j", strtotime($row['Day2Time'])) . "</td>";
                            echo "<td><a href='/meet/" . $row['id'] . "'>" . $row['Name'] . $badge . "</a></td>";
                            echo "<td>" . $row['Opponents'] . "</td>";
                            echo "<td>" . $row['Day2Levels'] . "</td>";
                            echo "<td>" . $dir . "</td>";
                            echo "</tr>";
                        }
                    }
                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr>";
                        echo "<td class='text-center' colspan='6'>No Meets Currently Scheduled.</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</section>

<div class="container mt-4">
    <div id='calendar'></div>
    <div class="my-3 d-flex justify-content-center">
        <a class="btn btn-primary mx-2" href="
        <?php
        if (strpos($season, 'Track') !== false) {
            echo "https://gbsathletics.glenbrook225.org/page/3050";
        } else {
            echo "https://gbsathletics.glenbrook225.org/page/2937";
        }
        ?>
        " role="button" target="_blank">GBS Athletics
            Schedule</a>
        <button type="button" class="btn btn-secondary mx-2" onClick="printSchedule()"><i class="bi bi-printer-fill me-1"></i>Print Schedule</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add to Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong>Select Calendar Options:</strong>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="scheduleToggle" onChange="generateURL()" checked>
                    <label class="form-check-label" for="scheduleToggle">
                        Meet Schedule
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="practicesToggle" onChange="generateURL()" checked>
                    <label class="form-check-label" for="practicesToggle">
                        Practices and Workouts
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="eventsToggle" onChange="generateURL()" checked>
                    <label class="form-check-label" for="eventsToggle">
                        Other Events
                    </label>
                </div>
                <div class="my-3 d-flex justify-content-center">
                    <a class="btn btn-primary" href="webcal://titandistance.com/exportcal?include=schedule,practices,events" id="addCalButton" role="button" target="_blank"><i class="bi bi-calendar-plus-fill me-1"></i>Add to Personal Calendar</a>
                </div>
                <hr>
                <p>Try to use the button above to add to your personal clanedar. If the link above does not open in your calendar app, use the instructions below.</p>
                <strong>To add to Google Calendar:</strong><br>
                1) Navigate to <a href="https://calendar.google.com/calendar/r/settings/addbyurl">https://calendar.google.com/calendar/r/settings/addbyurl</a><br>
                2) Paste the red iCal link below into the text box.<br>
                <br>
                <strong>To add to iOS Calendar</strong><br>
                1) Open the Settings application, and click "Mail, Contacts, Calendars"<br>
                2) Click "Add Account" then "Other" then "Add Subscribed Calendar"<br>
                3) Paste in the link to the red iCal File found below.<br>
                <br>
                <strong>To add to Outlook, Apple Calendar:</strong><br>
                Paste the link below where you can add an external calendar.<br>
                <code id="urlOutput" class="user-select-all">https://titandistance.com/exportcal?include=schedule,practices,events</code>
                <hr>
                <p>*Changes to the schedule may take upto 24 hours to appear.</p>
            </div>
        </div>
    </div>
</div>
<script>
    function generateURL() {
        var scheduleToggle = document.getElementById("scheduleToggle");
        var practicesToggle = document.getElementById("practicesToggle");
        var eventsToggle = document.getElementById("eventsToggle");
        var urlOutput = document.getElementById("urlOutput");
        var addCalButton = document.getElementById("addCalButton");

        var options = [];

        if (scheduleToggle.checked) {
            options.push("schedule");
        }
        if (practicesToggle.checked) {
            options.push("practices");
        }
        if (eventsToggle.checked) {
            options.push("events");
        }

        if (scheduleToggle.checked == false && practicesToggle.checked == false && eventsToggle.checked == false) {
            alert("You must select at least one option");
            var url = "PLEASE SELECT MULTIPLE CHECKBOXES";
            urlOutput.innerHTML = url;
            addCalButton.classList.add("disabled");
        } else {
            var url = "https://titandistance.com/exportcal?include=" + options.join(",");
            var webcal = "webcal://titandistance.com/exportcal?include=" + options.join(",");

            urlOutput.innerHTML = url;
            addCalButton.href = webcal;
            addCalButton.classList.remove("disabled");
        }
    }

    function showSeason(s) {
        window.location = "/schedule?season=" + s;
    }

    function printSchedule() {
        var season = document.getElementById("SeasonSelect").value;
        var divContents = document.getElementById("scheduleContainer").innerHTML;
        var a = window.open('', '', 'height=2100, width=800');
        a.document.write('<html>');
        a.document.write('<head><title>' + season + ' Schedule</title><style>.badge {display:none;} button {display:none;} .dataTable-bottom {display:none;} a {text-decoration: none; color: inherit;} .dataTable-top {display:none;} table {width:100%;text-align: center;} h3 {text-align: center; font-size: 18px;}</style></head>');
        a.document.write('<body onafterprint="window.close()"><img src="https://titandistance.com/assets/logos/color.svg" onload="window.print()" style="display: block;margin-left: auto;margin-right: auto;width: 40%;" alt="Titan Distance"><pre>');
        a.document.write(divContents.replace("style=", "data-td-style="));
        a.document.write('</pre></body></html>');
        a.document.close();
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'p' && event.ctrlKey) {
            printSchedule();
        }
    });
</script>
<?php include("footer.php"); ?>