<?php $pgtitle = "Roster"; ?>
<?php include("header.php"); ?>
<?php
$id = htmlspecialchars($_GET["id"]);
?>

<div class="container mt-3">
    <!--
    <form>
        <input type="text" class="form-control mb-3" size="30" onkeyup="showResult(this.value)">
        <div id="livesearch"></div>
    </form>-->
    <div class="d-flex justify-content-between">
        <p>Click on the name of an athlete in the table to view their profile.<br>Rosters contain results post-2016,
            and only results in our database.</p>
        <div class="form-group">
            <select class="form-control" id="SeasonSelect" onchange="showSeason(this.value)">
                <option value="" selected disabled>Select a Season:</option>
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
    <div id="table" onload="showSeason()"></div>
    <script type="text/javascript">
    document.getElementById("table").innerHTML = "<strong>Please select a season from the dropdown above.</strong>";
    var view = window.location.search.substr(1);
    view = view.replace('view=', '');
    console.log("View: " + view);
    if (view !== "") {
        showSeason(view);
        document.getElementById('SeasonSelect').value = view;
    }

    function showSeason(str) {
        var sport = str.substr(0, 2);
        var year = str.substr(2, 4);
        console.log("Sport Selected: " + sport);
        console.log("Year Selected: " + year);
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
    }
    </script>

    <script>
    function showResult(str) {
        if (str.length == 0) {
            document.getElementById("livesearch").innerHTML = "";
            document.getElementById("livesearch").style.border = "0px";
            return;
        }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("livesearch").innerHTML = this.responseText;
                document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
            }
        }
        xmlhttp.open("GET", "/api/searchathletes.php?q=" + str, true);
        xmlhttp.send();
    }
    </script>
</div>


<?php include("footer.php"); ?>