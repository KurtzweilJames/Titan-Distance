</main>

<!-- Footer -->
<footer class="footer mt-auto py-3 mt-3">
    <div class="container">
        <div class="text-muted d-flex justify-content-between">
            <div><a href="/about#disclaimer">&copy; <?php echo date("Y"); ?> Titan Distance PR Committee</a></div>
            <div><a href="/admin">Admin Login</a><img src="/assets/us_flag.svg" height="14" class="ms-2"></div>

        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<?php
if ($require == "share") {
echo "<script type='text/javascript' src='//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f2abfd56cc7cfd2'></script>";
}

if ($pgtitle == "Schedule") {
    include("includes/calendar.php");
}

if ($require == "tabs") {
    echo "<script src='/includes/tabs.js'></script>";
}

if ($require == "charts") {
    echo "<script src='https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.js'></script>";
    echo "<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js'></script>";
    include("includes/athletecharts.php"); 
}
?>

<!-- Service Worker -->
<script type="module">
import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

const el = document.createElement('pwa-update');
document.body.appendChild(el);
</script>

<script>
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
    $(".clickable").click(function() {
        window.open($(this).data("href"), '_blank');
    });
});
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
<?php
if ($pgtitle == "Results") {
    echo "<script>
        $(document).ready(function() {
            $('#resultsTable').DataTable({
                \"ordering:\": false,
                \"lengthMenu\": [
                    [10, 25, 50, -1],
                    [10, 25, 50, \"All\"]
                ],
                \"iDisplayLength\": 50,
                \"order\": []
            });
        });
        </script>";
}
if ($pgtitle == "Roster") {
    echo "<script>
        function updateTable() {
            $('#rosterTable').DataTable({
                \"ordering:\": false,
                \"lengthMenu\": [
                    [10, 25, 50, -1],
                    [10, 25, 50, \"All\"]
                ],
                \"iDisplayLength\": -1,
                \"order\": []
            });
        }
        </script>";
    }
    
    if ($require == "charts") {
        echo "<script>
        $(document).ready(function() {
            $('#xcPersonal').DataTable({
                \"order\": [],
                \"lengthMenu\": [
                    [10, 25, 50, -1],
                    [10, 25, 50, \"All\"]
                ],
                \"iDisplayLength\": 10
            });
            $('#tfPersonal').DataTable({
                \"order\": [],
                \"lengthMenu\": [
                    [10, 25, 50, -1],
                    [10, 25, 50, \"All\"]
                ],
                \"iDisplayLength\": 10
            });
        });
        </script>";
    }
?>

<script>
var sayings = <?php echo json_encode($sayings); ?>;
if (document.getElementById("sayings")) {
    window.setInterval(function() {
        document.getElementById("sayings").innerHTML = sayings[Math.floor(Math.random() * sayings
            .length)];
    }, 10000);
}

var navbar = document.getElementById("top-navbar");
var content = document.getElementById("main");

var sticky = navbar.offsetTop;
var navheight = navbar.offsetHeight;

window.onscroll = function() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky-header");
        navbar.classList.add("bg-light");
        content.style = "margin-top:" + navheight + "px;";
    } else {
        navbar.classList.remove("sticky-header");
        navbar.classList.remove("bg-light");
        content.style = "";
    }
};
</script>

<!-- Cloudflare Web Analytics -->
<script defer src='https://static.cloudflareinsights.com/beacon.min.js'
    data-cf-beacon='{"token": "7e1ad18bd4604d4486a579c7d687d825"}'></script><!-- End Cloudflare Web Analytics -->
</body>

</html>

<?php
$mysqli -> close();
?>