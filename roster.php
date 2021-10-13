<?php $pgtitle = "Roster"; ?>
<?php include("header.php"); ?>
<?php
$id = htmlspecialchars($_GET["id"]);
?>

<div class="container mt-3 h-100">
    <div class="d-flex justify-content-between flex-wrap text-center text-md-start">
        <p>Only times in our database are considered. Individual Season rosters show the top times for that season,
            while the All-Time rosters show Personal Records.
        </p>
        <div class="form-group mx-auto mx-lg-0 mb-2 mb-lg-0">
            <select class="form-select" id="SeasonSelect" onchange="showSeason(this.value)">
                <option value="" selected disabled>Select a Season:</option>
                <option value="xc21" name="xc21">2021 Cross Country</option>
                <option value="tf21" name="tf21">2021 Track</option>
                <option value="xc20" name="xc20">2020 Cross Country</option>
                <option value="tf20" name="tf20">2020 Track</option>
                <option value="xc19" name="xc19">2019 Cross Country</option>
                <option value="tf19" name="tf19">2019 Track</option>
                <option value="xc18" name="xc18">2018 Cross Country</option>
                <option value="tf18" name="tf18">2018 Track</option>
                <option value="xc17" name="xc17">2017 Cross Country</option>
                <option value="tf17" name="tf17">2017 Track</option>
                <option value="xc16" name="xc16">2016 Cross Country</option>
                <option value="all" name="all">All Time</option>
                <option value="picture" name="picture">All Time Picture</option>
            </select>
        </div>
    </div>
    <div id="table" class="overflow-hidden"><strong>Please select a season from the dropdown above.</strong></div>
    <script type="text/javascript">
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const view = window.location.pathname.split("/").pop();

        if (view != null) {
            document.getElementById("SeasonSelect").value = view;
            showSeason(view);
            updateTable();
        }
    });

    function showSeason(str) {
        var sport = str.substr(0, 2);
        var year = str.substr(2, 4);
        var xhttp;
        if (str == "" || str == null) {
            document.getElementById("table").innerHTML =
                "<strong>Please select a season from the dropdown above</strong>";
            return;
        }
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("table").innerHTML = this.responseText;
                updateTable();
            }
        };
        if (sport == "tf") {
            xhttp.open("GET", "/includes/rosterview/tfroster.php?y=" + year, true);
        }
        if (sport == "xc") {
            xhttp.open("GET", "/includes/rosterview/xcroster.php?y=" + year, true);
        }
        if (str.includes("all")) {
            xhttp.open("GET", "/includes/rosterview/allroster.php", true);
        }
        if (str.includes("picture")) {
            xhttp.open("GET", "/includes/rosterview/picture.php", true);
        }
        xhttp.send();

        if (window.history.replaceState) {
            window.history.replaceState({}, null, "/roster/" + str)
        }

        if (sport == "tf") {
            sport = "Track";
        } else if (sport == "xc") {
            sport = "Cross Country"
        }
        if (document.title) {
            if (str == "all") {
                document.title = "All Time Roster - Titan Distance";
            } else if (str == "picture") {
                document.title = "All Time Picture Roster - Titan Distance";
            } else {
                document.title = sport + " 20" + year + " Roster - Titan Distance";
            }
        }
    }
    </script>
</div>


<?php include("footer.php"); ?>