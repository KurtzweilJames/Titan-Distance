<?php $pgtitle = "Venues"; ?>
<?php include("header.php"); ?>
<div class="container mt-4">
    <div class="form-group d-block d-md-none">
        <select class="form-control" id="selectTab" onchange="showVenue(this.value)">
            <option value='stadium' name='stadium'>John Davis Titan Stadium</option>
            <option value='fieldhouse' name='fieldhouse'>David Pasquini Fieldhouse</option>
            <option value='xccourse' name='xccourse'>Cross Country Course</option>
            <option value='campus' name='campus'>Campus Map</option>
        </select>
    </div>
    <div class="row">
        <div class="col-12 col-md-3">
            <div class="nav flex-column nav-pills d-none d-md-block" id="list" aria-orientation="vertical">
                <a class="nav-link" id="stadium-toggle" onclick="showVenue(this.id)">John Davis Titan
                    Stadium<br>(Outdoor
                    TF)</a>
                <a class="nav-link" id="fieldhouse-toggle" onclick="showVenue(this.id)">David Pasquini
                    Fieldhouse<br>(Indoor
                    TF)</a>
                <a class="nav-link" id="xccourse-toggle" onclick="showVenue(this.id)">Cross Country Course</a>
                <a class="nav-link" id="campus-toggle" onclick="showVenue(this.id)">Campus Map</a>
            </div>
        </div>
        <div class="col-12 col-md-9" id="venue">

        </div>
    </div>
</div>
<script>
function showVenue(v) {
    t = v;
    v = v.replace("-toggle", "");
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("venue").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "/includes/venues/" + v, true);
    xhttp.send();

    var btnContainer = document.getElementById("list");

    // Get all buttons with class="btn" inside the container
    var btns = btnContainer.getElementsByClassName("nav-link");

    // Loop through the buttons and add the active class to the current/clicked button
    for (var i = 0; i < btns.length; i++) {
        if (t == btns[i].id) {
            btns[i].classList.add("active");
        } else {
            btns[i].classList.remove("active");
        }
    }
    window.location.hash = v;
}

window.onload = function() {
    if (window.location.hash) {
        hash = window.location.hash;
        if (hash.substring(0, 1) == '#') {
            hash = hash.substring(1, hash.length);
        }
        showVenue(hash + "-toggle");
    } else {
        showVenue("stadium-toggle");
    }
};
</script>
<?php include("footer.php"); ?>