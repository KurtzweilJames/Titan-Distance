<?php $pgtitle = "Schedule"; ?>
<?php include("header.php"); ?>
<?php
$result = mysqli_query($con, "SELECT UNIQUE Season FROM meets ORDER BY Date DESC");
while ($row = mysqli_fetch_array($result)) {
    $allSeasons[] = $row['Season'];
}
?>

<section id="content">
    <div class="container">
        <div class="d-flex justify-content-between mb-2">
            <i class="my-auto">*Schedule is subject to change.</i>
            <div class="form-group d-none d-md-block">
                <select class="form-select" id="SeasonSelect" onchange="showSeason(this.value)">
                    <option value="" disabled>Select a Season:</option>
                    <?php
                    foreach ($allSeasons as $s) {
                        echo "<option value='" . $s . "' name='" . $s . "'";
                        if ($s == $currentseason) {
                            echo "class='row-highlight'";
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
                <tbody class="placeholder-glow">
                    <tr>
                        <td colspan="7"><span class="placeholder w-100"></span></td>
                    </tr>
                    <tr>
                        <td colspan="7"><span class="placeholder w-100"></span></td>
                    </tr>
                    <tr>
                        <td colspan="7"><span class="placeholder w-100"></span></td>
                    </tr>
                    <tr>
                        <td colspan="7"><span class="placeholder w-100"></span></td>
                    </tr>
                    <tr>
                        <td colspan="7"><span class="placeholder w-100"></span></td>
                    </tr>
                    <tr>
                        <td colspan="7"><span class="placeholder w-100"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="container mt-4">
    <div id='calendar'></div>
    <div class="my-3 d-flex justify-content-center">
        <a class="btn btn-primary mx-2" href="https://www.rschoolillinois.org/public/genie/1258/school/2564/" role="button" target="_blank" id="gbsButton">GBS Athletics Schedule</a>
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
        //window.location = "/schedule?season=" + s;
        fetchSchedule(s)
        document.title = s + " Schedule - Titan Distance";
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

    var schedule;

    function fetchSchedule(s) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                response = this.responseText;
                schedule = JSON.parse(response);
                generateSchedule(schedule)
                if (s.includes("Track")) {
                    document.getElementById("gbsButton").href = "https://www.rschoolillinois.org/g5-bin/client.cgi?cwellOnly=1&G5statusflag=view&schoolname=&school_id=2564&G5button=13&G5genie=1258&vw_schoolyear=1&vw_agl=1134-2-2371,1134-2-120,1134-2-2377,1134-2-125,&manual_access=1"
                } else if (s.includes("Cross Country")) {
                    document.getElementById("gbsButton").href = "https://www.rschoolillinois.org/g5-bin/client.cgi?cwellOnly=1&G5statusflag=view&schoolname=&school_id=2564&G5button=13&G5genie=1258&vw_schoolyear=1&vw_agl=78-2-2371,78-2-120,78-2-125,&manual_access=1"
                } else {
                    document.getElementById("gbsButton").href = "https://www.rschoolillinois.org/public/genie/1258/school/2564/"
                }
            }
        };
        var url = "/api/schedule?season=" + s
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    function generateSchedule(schedule) {
        var scheduleContainer = document.getElementById("scheduleContainer");
        var table;
        table = '<table class="table table-condensed table-hover table-sm" id="scheduleTable"><thead><tr><th></th><th>Date</th><th>Name</th><th>Opponents</th><th>Levels</th><th>Location</th></tr></thead><tbody>'

        for (let x in schedule) {
            url = schedule[x].url
            table += "<tr onclick = window.location='" + url + "'";
            if (schedule[x].status == "C") {
                table += " style='text-decoration:line-through;' class='text-danger' data-bs-toggle='tooltip' data-bs-placement='top' title='" + schedule[x].message + "'";
            } else if (schedule[x].status == "R" || schedule[x].status == "P") {
                table += " class='text-danger' data-bs-toggle='tooltip' data-bs-placement='top' title='" + schedule[x].message + "'";
            }
            table += ">";
            table += "<td>" + schedule[x].dow + "</td>";
            table += "<td>" + schedule[x].md + "</td>";

            table += "<td><a href='" + url + "'"
            if (schedule[x].status == "C" || schedule[x].status == "R" || schedule[x].status == "P") {
                table += " class='link-danger'";
            }
            table += ">" + schedule[x].title;
            if (schedule[x].badge == "1") {
                table += '<span class="ms-1 badge bg-csl" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Central Suburban League Conference">CSL</span>'
            } else if (schedule[x].badge == "2") {
                table += '<span class="ms-1 badge bg-ihsa" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="IHSA State Series Competition" aria-label="IHSA State Series Competition"><img src="/assets/icons/ihsa.svg" height="11px" alt="IHSA"></span>'
            } else if (schedule[x].badge == "3") {
                table += '<span class="ms-1 badge bg-info" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Time Trial">TT</span>'
            }
            table += "</a></td>";

            if (schedule[x]["opponents"] == null) {
                table += "<td class='col-5'></td>";
            } else {
                opponents = schedule[x]["opponents"].toString().split(", ")
                if (opponents.length > 7) {
                    table += "<td class='col-5' data-bs-toggle='tooltip' data-bs-placement='top' title='" + opponents.join(", ") + "'>" + opponents.slice(0, 7).join(", ") + ", + " + (opponents.length - 7) + " more</td>";
                } else {
                    table += "<td class='col-5'>" + opponents.join(", ") + "</td>";
                }

            }

            if (schedule[x]["levels"] == null) {
                table += "<td></td>";
            } else {
                table += "<td>" + schedule[x].levels + "</td>";
            }

            table += "<td><a href='" + url + "#venue'"
            if (schedule[x].status == "C" || schedule[x].status == "R" || schedule[x].status == "P") {
                table += " class='link-danger'";
            }
            table += ">";
            if (schedule[x].location !== "David Pasquini Fieldhouse" && schedule[x].location !== "John Davis Titan Stadium" && schedule[x].location !== "Glenbrook South High School") {
                table += "@ ";
            }
            table += schedule[x].location + "</a></td>";

            table += "</tr>";
        }

        table += '</tbody></table>'
        scheduleContainer.innerHTML = table
        activateTooltips()
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'p' && event.ctrlKey) {
            printSchedule();
        }
    });

    window.onload = function exampleFunction() {
        showSeason(document.getElementById("SeasonSelect").value)
    }
</script>
<?php include("footer.php"); ?>