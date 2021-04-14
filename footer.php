</main>

<!-- Footer -->
<footer class="footer mt-auto py-3 mt-3">
    <div class="container">
        <div class="text-muted d-flex justify-content-between">
            <div><a href="/about#disclaimer">&copy; <?php echo date("Y"); ?> Titan Distance PR Committee</a></div>
            <div><a href="/admin">Admin Login</a></div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
</script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css"></script>

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
    $(document).ready(function() {
        setTimeout(function() {
            showSpinner();
            updateTable();
          }, 1000);
    });
    $('#SeasonSelect').change(function(){
        setTimeout(function() {
            showSpinner();
            updateTable();
          }, 100);
    });
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
            $('#rosterTable').show();
            hideSpinner();
        }

        function showSpinner() {
            document.getElementById(\"loading-spinner\").classList = 'd-block';
        }
        function hideSpinner() {
            document.getElementById(\"loading-spinner\").classList = 'd-none';
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

    if ($require == "countdown") {
        echo "<script>
var countDownDate = new Date(\"April 19, 2021 00:00:00\").getTime();

var x = setInterval(function() {

  var now = new Date().getTime();

  var distance = countDownDate - now;

  var days = Math.floor(distance / (1000 * 60 * 60 * 24));

  document.getElementById(\"countdown\").innerHTML = days + \" days\";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById(\"countdown\").innerHTML = \"EXPIRED\";
  }
}, 1000);
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


window.onscroll = function() {
    myFunction()
};

var navbar = document.getElementById("top-navbar");
var content = document.getElementById("main");

var sticky = navbar.offsetTop;
var navheight = navbar.offsetHeight;

function myFunction() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky-header");
        navbar.classList.add("bg-light");
        content.style = "margin-top:" + navheight + "px;";
    } else {
        navbar.classList.remove("sticky-header");
        navbar.classList.remove("bg-light");
        content.style = "";
    }
}
</script>
</body>

</html>

<?php
$mysqli -> close();
?>