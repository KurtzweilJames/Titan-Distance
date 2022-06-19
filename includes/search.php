<div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="meetsCheckbox" value="meets" checked>
    <label class="form-check-label" for="meetsCheckbox">Meets</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="athletesCheckbox" value="athletes" checked>
    <label class="form-check-label" for="athletesCheckbox">Athletes</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="newsCheckbox" value="news" checked>
    <label class="form-check-label" for="newsCheckbox">News Articles</label>
</div>
<div class="mb-3">
    <input type="search" class="form-control" id="searchBar" onkeyup="showResult(this.value)"
        placeholder="Search Here...">
</div>
<ul class="list-group my-3" id="searchResults"></ul>
<script>
function showResult(str) {
    let from = new Array();
    if (document.getElementById("meetsCheckbox").checked) {
        from.push("meets")
    }
    if (document.getElementById("athletesCheckbox").checked) {
        from.push("athletes")
    }
    if (document.getElementById("newsCheckbox").checked) {
        from.push("news")
    }

    if (str.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("searchResults").innerHTML = "";
            results = JSON.parse(this.responseText);
            results.forEach(obj => {
                document.getElementById("searchResults").innerHTML += '<a href="' + obj.url +
                    '" class="list-group-item list-group-item-action"><div class="d-flex justify-content-between">' +
                    obj.title + '<i class="bi ' + obj.icon + '"></i></div></a>'
            });
        }
    }
    xmlhttp.open("GET", "/api/search?q=" + str + "&from=" + from.join(','), true);
    xmlhttp.send();
}
</script>