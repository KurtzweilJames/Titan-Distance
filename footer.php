<!-- Footer -->
<div class="footer bg-body-tertiary mt-auto py-3 mt-3">
    <div class="container">
        <div class="text-muted d-flex justify-content-between">
            <a href="/about#disclaimer">&copy; <?php echo date("Y"); ?> Titan Distance PR Committee</a>
            <?php
            if (isset($_SESSION["loggedin"])) {
                echo '<div><a class="me-2" data-bs-toggle="modal" data-bs-target="#settingsModal">Settings</a><a href="/admin">Admin Portal</a><img src="/assets/us_flag.svg" height="18" class="ms-2" alt="USA"></div>';
            } else {
                echo '<div><a class="me-2" data-bs-toggle="modal" data-bs-target="#settingsModal">Settings</a><a href="/admin">Login</a><img src="/assets/us_flag.svg" height="18" class="ms-2" alt="USA"></div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script src="https://cdn.userway.org/widget.js" data-account="{api-key}"></script>

<?php
if (isset($require)) {
    if ($require == "share") {
        echo "<script type='text/javascript' src='//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f2abfd56cc7cfd2'></script>";
    }
}
if (isset($pgtitle) && $pgtitle == "Schedule") {
    include("includes/calendar.php");
}
?>

<!-- Service Worker -->
<script type="module">
    import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

    const el = document.createElement('pwa-update');
    document.body.appendChild(el);
</script>

<script>
    function activateTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            bs = new bootstrap.Tooltip(tooltipTriggerEl)
            return bs
        })
    }
    activateTooltips()

    var modeRadioDark = document.getElementById("modeRadioDark");
    var modeRadioLight = document.getElementById("modeRadioLight");
    var modeRadioAuto = document.getElementById("modeRadioAuto");

    var badgeRadioTrue = document.getElementById("badgeRadioTrue");
    var badgeRadioFalse = document.getElementById("badgeRadioFalse");

    if (storedTheme) {
        var upper = storedTheme[0].toUpperCase() + storedTheme.substr(1);
        document.getElementById("modeRadio" + upper).checked = true;
    } else {
        localStorage.setItem('theme', 'auto');
        document.getElementById("modeRadioAuto").checked = true;
    }

    if (localStorage.getItem('showBadges') == "true") {
        badgeRadioTrue.checked = true;
    } else {
        badgeRadioFalse.checked = true;
    }

    function changeTheme(theme) {
        setTheme(theme)
        localStorage.setItem('theme', theme);
        changeLogo()
    }

    function changeBadge(badge) {
        localStorage.setItem('showBadges', badge)
    }
</script>

<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="searchModalLabel">Site Search</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php include("includes/search.php"); ?>
                <button class="btn btn-sm btn-link mt-1" onclick="localStorage.setItem('latestSearches', '[]');document.getElementById('searchResults').innerHTML = '';">Clear Search History</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Color Mode</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="modeRadio" id="modeRadioLight" value="light" onchange="changeTheme(this.value)">
                    <label class="form-check-label" for="modeRadioLight"><i class="bi bi-sun-fill me-1"></i>Light Mode</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="modeRadio" id="modeRadioDark" value="dark" onchange="changeTheme(this.value)">
                    <label class="form-check-label" for="modeRadioDark"><i class="bi bi-moon-stars-fill me-1"></i>Dark Mode</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="modeRadio" id="modeRadioAuto" value="auto" onchange="changeTheme(this.value)">
                    <label class="form-check-label" for="modeRadioAuto"><i class="bi bi-circle-half me-1"></i>Auto Mode</label>
                </div>
                <h5>Show PR Badges on Roster</h5>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="badgesRadio" id="badgeRadioTrue" value="true" onchange="changeBadge(this.value)">
                    <label class="form-check-label" for="badgeRadioTrue">Show PR Badges</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="badgesRadio" id="badgeRadioFalse" value="false" onchange="changeBadge(this.value)">
                    <label class="form-check-label" for="badgeRadioFalse">Hide PR Badges</label>
                </div>
                <p><i>Reload the Roster page for the PR Badge changes to take effect</i></p>
                <button class="btn btn-primary" onclick="localStorage.setItem('latestSearches', '[]');document.getElementById('searchResults').innerHTML = '';">Clear Search History</button>
            </div>
        </div>
    </div>
</div>

<!-- Cloudflare Web Analytics -->
<script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "{api-key}"}'></script><!-- End Cloudflare Web Analytics -->
</body>

</html>

<?php
if ($con) {
    $con->close();
}
?>