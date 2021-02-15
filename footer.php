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
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>
</script>

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

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/d5ee56d8d1.js" crossorigin="anonymous"></script>

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
    $('[data-toggle="tooltip"]').tooltip();
});
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


// When the user scrolls the page, execute myFunction
window.onscroll = function() {
    myFunction()
};

// Get the header
var navbar = document.getElementById("top-navbar");

// Get the offset position of the navbar
var sticky = navbar.offsetTop;

// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
    if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky-header");
        navbar.classList.add("bg-light");
    } else {
        navbar.classList.remove("sticky-header");
        navbar.classList.remove("bg-light");
    }
}
</script>
</body>

</html>

<?php
$mysqli -> close();
?>