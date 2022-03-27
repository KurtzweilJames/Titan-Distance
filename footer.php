</main>

<!-- Footer -->
<footer class="footer mt-auto py-3 mt-3">
    <div class="container">
        <div class="text-muted d-flex justify-content-between">
            <div><a href="/about#disclaimer">&copy; <?php echo date("Y"); ?> Titan Distance PR Committee</a></div>
            <div><a href="/admin">Admin Login</a><img src="/assets/us_flag.svg" height="14" class="ms-2" alt="USA"></div>

        </div>
    </div>
</footer>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="Toast" class="toast fade" role="alert" data-bs-autohide="false" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="https://titandistance.com/assets/logos/bolt.svg" class="rounded me-2" height="20px" alt="Titan Distance Bolt">
            <?php
            echo "<strong class='me-auto'>";
            $result = mysqli_query($con, "SELECT * FROM meets WHERE Date = '" . $todaydate . "' AND Official != 1 AND Official != 2");
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    echo $row['Name'];
                }
                $meetday = true;
            } else {
                echo "Titan Distance";
                $meetday = false;
            }
            echo "</strong>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>";
            ?>
        </div>
        <div class="toast-body">
            <?php
            $result = mysqli_query($con, "SELECT * FROM meets WHERE Date = '" . $todaydate . "' AND Official != 1 AND Official != 2");
            while ($row = mysqli_fetch_array($result)) {
                if (empty($row['Series'])) {
                    $url = "/meet/" . $row['id'];
                } else {
                    $url = "/meet/" . $row["Series"] . "/" . $d = date("Y", strtotime($row['Date']));
                }
                if (!empty($row['Live'])) {
                    echo "<a type='button' class='btn btn-primary btn-sm' href='" . $row['Live'] . "'><i class='bi bi-bar-chart-fill me-1'></i>Live Results</a>";
                }
                echo "<a type='button' class='btn btn-primary btn-sm' href='" . $url . "'><i class='bi bi-house-fill me-1'></i>Meet Page</a>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<!-- <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
<script>
    const jsConfetti = new JSConfetti()

    // if (document.title == "Titan Distance" || document.title == "CSL Indoor Conference Championship - Titan Distance") {
    //     launchConfetti();
    // }

    function launchConfetti() {
        jsConfetti.addConfetti({
            confettiColors: ['#ffd700', '#073763']
        })
    }
</script>

<?php
if (isset($require)) {
    if ($require == "share") {
        echo "<script type='text/javascript' src='//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5f2abfd56cc7cfd2'></script>";
    }

    // if ($require == "tabs") {
    //     echo "<script src='/includes/tabs.js'></script>";
    // }

    if ($require == "charts") {
        echo "<script src='https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.js'></script>";
        echo "<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js'></script>";
        include("includes/athletecharts.php");
    }
}
if ($pgtitle == "Schedule") {
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
    // jQuery(document).ready(function($) {
    //     $(".clickable-row").click(function() {
    //         window.location = $(this).data("href");
    //     });
    //     $(".clickable").click(function() {
    //         window.open($(this).data("href"), '_blank');
    //     });
    // });
    function activateTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }
    activateTooltips()
</script>
<?php
// if ($pgtitle == "Results") {
//     // echo "<script>
//     //     $(document).ready(function() {
//     //         $('#resultsTable').DataTable({
//     //             \"ordering:\": false,
//     //             \"lengthMenu\": [
//     //                 [10, 25, 50, -1],
//     //                 [10, 25, 50, \"All\"]
//     //             ],
//     //             \"iDisplayLength\": 50,
//     //             \"order\": []
//     //         });
//     //     });
//     //     </script>";
// }
// if ($pgtitle == "Roster") {
//     // echo "<script>
//     //     function updateTable() {
//     //         $('#rosterTable').DataTable({
//     //             \"ordering:\": false,
//     //             \"lengthMenu\": [
//     //                 [10, 25, 50, -1],
//     //                 [10, 25, 50, \"All\"]
//     //             ],
//     //             \"iDisplayLength\": -1,
//     //             \"order\": []
//     //         });
//     //     }
//     //     </script>";
// }

// if ($require == "charts") {
//     echo "<script>
//         $(document).ready(function() {
//             $('#xcPersonal').DataTable({
//                 \"order\": [],
//                 \"lengthMenu\": [
//                     [10, 25, 50, -1],
//                     [10, 25, 50, \"All\"]
//                 ],
//                 \"iDisplayLength\": 10
//             });
//             $('#tfPersonal').DataTable({
//                 \"order\": [],
//                 \"lengthMenu\": [
//                     [10, 25, 50, -1],
//                     [10, 25, 50, \"All\"]
//                 ],
//                 \"iDisplayLength\": 10
//             });
//         });
//         </script>";
// }
?>

<script>
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

    var toast = new bootstrap.Toast(document.getElementById('Toast'))
    var meetday = <?php echo $meetday; ?>

    var myToastEl = document.getElementById('Toast')
    myToastEl.addEventListener('hidden.bs.toast', function() {
        localStorage.setItem("mdtoast-hidden", Date.now());
    })

    if (meetday == 1) {
        if ((localStorage.getItem("mdtoast-hidden") !== null && localStorage.getItem("mdtoast-hidden") > Date.now() + 86400000) || localStorage.getItem("mdtoast-hidden") == null) {
            toast.show()
        }
    }
</script>

<!-- Cloudflare Web Analytics -->
<script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "7e1ad18bd4604d4486a579c7d687d825"}'></script><!-- End Cloudflare Web Analytics -->
</body>

</html>

<?php
$mysqli->close();
?>