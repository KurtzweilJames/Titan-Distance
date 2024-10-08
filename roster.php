<?php $pgtitle = "Roster"; ?>
<?php include("header.php"); ?>

<div class="container h-100">
    <div class="row">
        <div class="col-md-9 text-center text-md-start">
            <p>Only results imported into our database are displayed. Select a record for more details.<br>Track Points are based off <a href="https://caltaf.com/pointscalc/calc.html" target="_blank">IAAF Points</a> (0-1400). This is a beta feature, so some unexpected results may be displayed.</p>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="SeasonSelect" onchange="showSeason(this.value)">
                <option value="" selected disabled>Select a Season:</option>
                <option value="xc24" name="xc24">2024 Cross Country</option>
                <option value="tf24" name="tf24">2024 Distance Track</option>
                <option value="xc23" name="xc23">2023 Cross Country</option>
                <option value="tf23" name="tf23">2023 Distance Track</option>
                <option value="xc22" name="xc22">2022 Cross Country</option>
                <option value="tf22" name="tf22">2022 Distance Track</option>
                <option value="xc21" name="xc21">2021 Cross Country</option>
                <option value="tf21" name="tf21">2021 Distance Track</option>
                <option value="xc20" name="xc20">2020 Cross Country</option>
                <option value="tf20" name="tf20">2020 Distance Track</option>
                <option value="xc19" name="xc19">2019 Cross Country</option>
                <option value="tf19" name="tf19">2019 Distance Track</option>
                <option value="xc18" name="xc18">2018 Cross Country</option>
                <option value="tf18" name="tf18">2018 Distance Track</option>
                <option value="xc17" name="xc17">2017 Cross Country</option>
                <option value="tf17" name="tf17">2017 Distance Track</option>
                <option value="xc16" name="xc16">2016 Cross Country</option>
                <option value="all" name="all">All Time</option>
            </select>
        </div>
    </div>
    <div id="rosterTableContainer" class="overflow-hidden">
        <strong>Please select a season from the dropdown above.</strong>
        <p class="placeholder-glow">
            <span class="placeholder col-12"></span><span class="placeholder col-12"></span><span class="placeholder col-12"></span><span class="placeholder col-12"></span>
        </p>
    </div>
    <div class="container text-center mt-1">
        <p>*Have missing results or notice an issue? <a href="https://docs.google.com/forms/d/e/1FAIpQLSdCNMNZBMD5wCgcQ2SBcwuVOTOdV0y4j33HlwR53fCCaLaPag/viewform?usp=pp_url&entry.1449250561=Result+Correction" target="_blank">Please fill out this form for manual review.</a> Toggle PR badges by clicking "Settings" in the footer.</p>
        <a class="btn btn-primary" href="https://docs.google.com/forms/d/e/1FAIpQLSdCNMNZBMD5wCgcQ2SBcwuVOTOdV0y4j33HlwR53fCCaLaPag/viewform?usp=pp_url&entry.1449250561=Result+Correction" role="button" target="_blank">Request Correction</a>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasAthlete" aria-labelledby="offcanvasAthleteLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasAthleteLabel">Offcanvas</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- <div class="athlete-image mx-auto mx-md-0">
                <img src="" class="d-block" alt="" height="200" id="athleteImage">
            </div> -->
            <div class="athlete-image d-flex justify-content-center mx-auto w-75">
                <img src="" class="img-thumbnail" alt="" id="athleteImage">
                <img src="" class="college-overlay" alt="" id="athleteCollegeLogo">
            </div>
            <hr>
            <div class="text-center">
                <h3 id="athleteName" class="mb-0"></h3>
                <h4 id="athleteClass" class="my-0"></h4>
                <h5 id="athleteCollege" class="mt-0"></h5>
                <div id="athleteButtons">
                    <a class="btn btn-primary btn-sm" href="" role="button" id="athleteLink">Titan Distance
                        Profile</a>
                    <a href="#" class="btn btn-primary btn-sm" id="atNetTF" target="_blank">AthNET TF</a>
                    <a href="#" class="btn btn-primary btn-sm" id="atNetXC" target="_blank">AthNET XC</a>
                </div>
            </div>
            <table class="table table-sm mt-4">
                <thead>
                    <th>Event</th>
                    <th>Meet</th>
                    <th>Result</th>
                </thead>
                <tbody id="athleteRecordTable">

                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function exampleFunction() {
            const view = window.location.pathname.split("/").pop();
            console.log(view);

            if (view == "roster") {
                showSeason("<?php echo $currentshort; ?>")
                document.getElementById("SeasonSelect").value = "<?php echo $currentshort; ?>";
            } else {
                document.getElementById("SeasonSelect").value = view;
                showSeason(view);
            }
        }

        tableContainer = document.getElementById("rosterTableContainer");
        var roster, showBadges;

        const storedShowBadges = localStorage.getItem('showBadges');

        if (!storedShowBadges) {
            showBadges = true;
            localStorage.setItem('showBadges', 'true');
        } else if (storedShowBadges == "true") {
            showBadges = true;
        } else {
            showBadges = false;
        }

        function showSeason(str) {
            var sport = str.substr(0, 2);
            var year = str.substr(2, 4);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    response = this.responseText;
                    roster = JSON.parse(response);
                    if (year > (new Date().getFullYear() - 2000)) {
                        tableContainer.innerHTML =
                            "<p class='lead my-5 py-5'>Hello from the Future! We are still in " + new Date()
                            .getFullYear() +
                            ", so this information won't be available for a few more years. Check back in 20" +
                            year + "!</p>";
                    } else {
                        generateRosterTable(sport, year);
                    }
                }
            };
            var url = "/api/roster?s=" + str
            xhttp.open("GET", url, true);
            xhttp.send();

            if (window.history.replaceState) {
                window.history.replaceState({}, null, "/roster/" + str)
            }

            if (sport == "tf") {
                sport = "Track";
            } else if (sport == "xc") {
                sport = "Cross Country"
            } else if (sport == "al") {
                sport = "All"
            } else if (sport == "it") {
                sport = "Indoor Track"
            } else if (sport == "ot") {
                sport = "Outdoor Track"
            }
            if (document.title) {
                if (str == "all") {
                    document.title = "All Time Roster - Titan Distance";
                } else {
                    document.title = sport + " 20" + year + " Roster - Titan Distance";
                }
            }
        }

        function generateRosterTable(sport, year) {
            if (sport == "All") {
                season = "All Time"
            } else {
                season = sport + " 20" + year
            }

            tableContainer.innerHTML = "";
            if (sport == "All") {
                tableContainer.innerHTML += "<h3 class='position-absolute d-none d-md-inline'>" + season + " Personal Records</h3>";
            } else {
                tableContainer.innerHTML += "<h3 class='position-absolute d-none d-md-inline'>" + season + " Season Bests</h3>";
            }

            let table = "<div class='table-responsive'><table class='table' id='rosterTable'>";
            if (sport == "Track") {
                table +=
                    "<thead class='text-center'><tr><th>Name</th><th>Grade</th><th>3200m</th><th>1600m</th><th>800m</th><th>400m</th><th>Points</th></tr></thead>";
                var events = ["3200m", "1600m", "800m", "400m"];
            } else if (sport == "Cross Country") {
                table +=
                    "<thead class='text-center'><tr><th>Name</th><th>Grade</th><th>3mi</th><th>2mi</th><th>5k</th></tr></thead>";
                var events = ["3mi", "2mi", "5k"];
            } else if (sport == "All") {
                table +=
                    "<thead class='text-center'><tr><th>Name</th><th>Class</th><th>3mi</th><th>2mi</th><th>5k</th><th>3200m</th><th>1600m</th><th>800m</th><th>400m</th></tr></thead>";
                var events = ["3mi", "2mi", "5k", "3200m", "1600m", "800m", "400m"];
            } else if (sport == "Indoor Track") {
                table +=
                    "<thead class='text-center'><tr><th>Name</th><th>Grade</th><th>3200m</th><th>1600m</th><th>800m</th><th>400m</th><th>200m</th><th>160m</th><th>Points</th></tr></thead>";
                var events = ["3200m", "1600m", "800m", "400m", "200m", "160m"];
            }

            for (let x in roster) {
                table += "<tr onClick = \"athleteFlyout('" + roster[x].profile + "','" + x + "','" + sport + "')\">";
                if (roster[x].captain == true) {
                    captain = " (C)"
                } else {
                    captain = ""
                }
                table += "<th><a class='link-primary'>" + roster[x].name + captain + "</a></th>";

                if (sport == "All") {
                    table += "<th>" + roster[x].class + "</th>";
                } else {
                    table += "<th>" + roster[x].grade + "</th>";
                }

                for (let event in events) {
                    if (roster[x].records[events[event]]) {
                        table += "<td data-type=\"date\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" title=\"" +
                            roster[x].records[events[event]].meetName + "\"><a href=\"/meet/" + roster[x].records[
                                events[event]].meetID + "#results\">" + formatResult(roster[x].records[events[event]]
                                .result);
                        if (roster[x].records[events[event]].relay == true) {
                            table += "<span class='badge text-bg-info ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Relay Split'>R</span>";
                        }
                        if (roster[x].records[events[event]].isPR == "1" && season !== "All Time" && showBadges == true) {
                            table += "<span class='badge text-bg-primary ms-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Personal Record'>PR</span>";
                        }
                        table += "</a></td>";
                    } else {
                        table += "<td>-</td>";
                    }
                }

                if (sport == "Track" || sport == "Indoor Track" || sport == "Outdoor Track") {
                    table += "<th>" + roster[x].maxPoints + "</th>";
                }

                table += "</tr>";
            }
            table += "<tbody>";
            table += "</tbody>";
            table += "</table></div>";

            let currentDate = new Date();
            let cDay = currentDate.getDate();
            let cMonth = currentDate.getMonth() + 1;
            let cYear = currentDate.getFullYear();

            tableContainer.innerHTML += table
            tableContainer.innerHTML += "<div class=\"text-center my-2\">Information is current as of " + cMonth + "/" +
                cDay + "/" + cYear + " based on results in our database.</div>"
            tableContainer.innerHTML +=
                "<div class=\"text-center my-2\"><button type=\"button\" class=\"btn btn-secondary btn-sm mx-2\" onClick=\"printRoster()\"><i class=\"bi bi-printer-fill me-1\"></i>Print Roster</button><button type=\"button\" class=\"btn btn-secondary btn-sm mx-2\" onClick=\"showCards()\"><i class=\"bi bi-image me-1\"></i>Image Cards</button></div>"

            const dataTable = new simpleDatatables.DataTable("#rosterTable", {
                searchable: true,
                fixedHeight: true,
                "perPageSelect": false,
                "perPage": 1000
            })
            activateTooltips()
        }

        function generateRosterCards(sport, year) {
            if (sport == "tf") {
                sport = "Track";
            } else if (sport == "xc") {
                sport = "Cross Country"
            } else if (sport == "al") {
                sport = "All"
            }

            if (sport == "All") {
                season = "All Time"
            } else {
                season = sport + " 20" + year
            }

            tableContainer.innerHTML = "";
            tableContainer.innerHTML += "<h3>" + season + " Roster</h3>";
            let cards = "<div class='row row-cols-2 row-cols-md-3 row-cols-lg-4'>";
            for (let x in roster) {
                cards += '<div class="col mb-2 p-1 p-md-2" onClick = "athleteFlyout(\'' + roster[x].profile + '\',\'' + x + '\',\'' + sport + '\')"><div class="card hover-card"><img src="' + roster[x].image + '" class="card-img-top">';
                cards += '<div class="card-body text-center"><h5 class="card-title">' + roster[x].name + '</h5></div>';
                cards += '</div></div>';
            }
            cards += "</div>";
            tableContainer.innerHTML += cards
        }

        var colleges;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText;
                colleges = JSON.parse(response);
            }
        };
        var url = "/api/collegelogos.json"
        xhttp.open("GET", url, true);
        xhttp.send();

        function athleteFlyout(profile, row, sport) {
            var myOffcanvas = document.getElementById('offcanvasAthlete')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
            bsOffcanvas.show()

            document.getElementById("offcanvasAthleteLabel").innerHTML = roster[row].name;
            document.getElementById("athleteImage").src = roster[row].image;
            document.getElementById("athleteImage").alt = roster[row].name;
            document.getElementById("athleteName").innerHTML = roster[row].name;
            document.getElementById("athleteClass").innerHTML = "Class of " + roster[row].class;

            var singleCollege = roster[row].college;

            if (roster[row].college) {
                if (singleCollege.includes(";")) {
                    singleCollege = singleCollege.split(";")[0]
                }

                singleCollege = singleCollege.replace(" (DI)", "")
                singleCollege = singleCollege.replace(" (DII)", "")
                singleCollege = singleCollege.replace(" (DIII)", "")

                document.getElementById("athleteCollege").innerHTML = roster[row].college.replace(";", ", ");

                if (colleges[singleCollege] && colleges[singleCollege].logo) {
                    document.getElementById("athleteCollegeLogo").classList.remove("d-none");
                    document.getElementById("athleteCollegeLogo").src = "/assets/logos/colleges/" + colleges[singleCollege].logo;
                    document.getElementById("athleteCollegeLogo").alt = singleCollege;
                } else {
                    document.getElementById("athleteCollegeLogo").classList.add("d-none");
                    document.getElementById("athleteCollegeLogo").src = "";
                    document.getElementById("athleteCollegeLogo").alt = "";
                }
            } else {
                document.getElementById("athleteCollege").innerHTML = "";
                document.getElementById("athleteCollegeLogo").classList.add("d-none");
                document.getElementById("athleteCollegeLogo").src = "";
                document.getElementById("athleteCollegeLogo").alt = "";
            }

            document.getElementById("athleteLink").href = "/athlete/" + roster[row].profile;
            athleteRecordTable = document.getElementById("athleteRecordTable");
            athleteRecordTable.innerHTML = "";
            table = ""
            for (i in roster[row].records) {
                table += "<tr>"
                table += "<th>" + i + "</th>"
                table += "<td><a href='/meet/" + roster[row].records[i].meetID + "#results'>" + roster[row].records[i]
                    .meetName + "</a></td>"
                table += "<th>" + formatResult(roster[row].records[i].result) + "</th>"
                table += "</tr>"
                athleteRecordTable.innerHTML = table;
            }
            if (roster[row].athnet !== null) {
                // document.getElementById("atNetTF").href = "https://www.athletic.net/TrackAndField/Athlete.aspx?AID=" +
                //     roster[row].athnet;
                // document.getElementById("atNetXC").href = "https://www.athletic.net/CrossCountry/Athlete.aspx?AID=" +
                //     roster[row].athnet;
                document.getElementById("atNetTF").href = "https://www.athletic.net/athlete/" + roster[row].athnet + "/track-and-field/high-school";
                document.getElementById("atNetXC").href = "https://www.athletic.net/athlete/" + roster[row].athnet + "/cross-country/high-school";
            } else {
                document.getElementById("atNetTF").classList = "d-none"
                document.getElementById("atNetXC").classList = "d-none"
            }
        }

        function formatResult(result) {
            if (result.substring(0, 2) == "0:") {
                return result.substring(2);
            } else if (result.substring(0, 1) == "0") {
                return result.substring(1);
            } else {
                return result;
            }
        }

        function printRoster() {
            var divContents = document.getElementById("rosterTableContainer").innerHTML;
            var a = window.open('', '', 'height=2100, width=800');
            a.document.write('<html>');
            a.document.write('<head><title>' + season +
                ' Roster</title><style>.badge {display:none;} button {display:none;} .dataTable-bottom {display:none;} a {text-decoration: none; color: inherit;} .dataTable-top {display:none;} table {width:100%;text-align: center;} h3 {text-align: center; font-size: 18px;} .text-center {text-align: center!important;} .my-2 {margin-top: .5rem!important;margin-bottom: .5rem!important;}</style></head>'
            );
            a.document.write(
                '<body onafterprint="window.close()"><img src="https://titandistance.com/assets/logos/color.svg" onload="window.print()" style="display: block;margin-left: auto;margin-right: auto;width: 40%;" alt="Titan Distance"><pre>'
            );
            a.document.write(divContents.replace("style=", "data-td-style="));
            a.document.write('</pre></body></html>');
            a.document.close();
        }

        function showCards() {
            str = document.getElementById("SeasonSelect").value
            var sport = str.substr(0, 2);
            var year = str.substr(2, 4);
            generateRosterCards(sport, year)
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'p' && event.ctrlKey) {
                printRoster();
            }
        });
    </script>
</div>

<?php include("footer.php"); ?>