<?php $pgtitle = "Roster"; ?>
<?php include("header.php"); ?>
<?php
$id = htmlspecialchars($_GET["id"]);
?>

<div class="container mt-3 h-100">
    <!--
    <form>
        <input type="text" class="form-control mb-3" size="30" onkeyup="showResult(this.value)">
        <div id="livesearch"></div>
    </form>-->
    <div class="d-flex justify-content-between flex-wrap text-sm-center text-lg-start">
        <p>Click on the name of an athlete in the table to view their profile.<br>The only times listed are ones in our
            database, so a "PR" listed below may not be accurate until our database is up-to-date.</p>
        <div class="form-group">
            <select class="form-select" id="SeasonSelect" onchange="showSeason(this.value)">
                <option value="" selected disabled>Select a Season:</option>
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
    <div id="loading-spinner">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div id="table"><strong>Please select a season from the dropdown above.</strong></div>
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
            }
        };
        if (sport == "tf") {
            xhttp.open("GET", "/rosterview/tfroster.php?y=" + year, true);
        }
        if (sport == "xc") {
            xhttp.open("GET", "/rosterview/xcroster.php?y=" + year, true);
        }
        if (str.includes("all")) {
            xhttp.open("GET", "/rosterview/allroster.php", true);
        }
        if (str.includes("picture")) {
            xhttp.open("GET", "/rosterview/picture.php", true);
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