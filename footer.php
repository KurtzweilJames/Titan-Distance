</main>

<!-- Footer -->
<footer class="footer mt-auto py-3 mt-3">
    <div class="container">
        <div class="text-muted d-flex justify-content-between">
            <a href="/about#disclaimer">&copy; <?php echo date("Y"); ?> Titan Distance PR Committee</a>
            <?php
            if (isset($_SESSION["loggedin"])) {
                echo '<div><a href="/admin">Admin Portal</a><img src="/assets/us_flag.svg" height="18" class="ms-2" alt="USA"></div>';
            } else {
                echo '<div><a href="/admin/login">Login</a><img src="/assets/us_flag.svg" height="18" class="ms-2" alt="USA"></div>';
            }
            ?>
        </div>
    </div>
</footer>

<!-- Scripts -->
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
<script>
    const jsConfetti = new JSConfetti()

    if (document.title == "Titan Distance Class of 2022 - Titan Distance") {
        launchConfetti();
    }

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

    // if ($require == "charts") {
    //     // echo "<script src='https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.js'></script>";
    //     echo "<script src='https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js></script>";
    //     // include("includes/athletecharts.php");
    // }
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
    var activatedTooltips = [];

    function activateTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        // if (activatedTooltips.length !== 0) {
        //     activatedTooltips.forEach(function(index) {
        //         activatedTooltips[index].destroy()
        //     });
        // }
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            bs = new bootstrap.Tooltip(tooltipTriggerEl)
            activatedTooltips.push(bs)
            return bs
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
</script>

<!-- PDF -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.23/dist/jspdf.plugin.autotable.min.js"></script>
<script>
    function generatePDF(table) {
        jspdf.jsPDF.autoTableSetDefaults({
            headStyles: {
                fillColor: "#073763"
            },
        })

        var doc = new jspdf.jsPDF()
        //var imgData = 'data:image/svg;base64,'+ Base64.encode('https://titandistance.com/assets/logos/color.svg');
        //doc.addSvgAsImage(tdlogo, 15, 20)

        // Simple html example
        doc.autoTable({
            html: table,
            startY: 30,
        })

        doc.output('dataurlnewwindow', {
            filename: "report"
        });
    }
</script> -->


<!-- Cloudflare Web Analytics -->
<script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "7e1ad18bd4604d4486a579c7d687d825"}'></script><!-- End Cloudflare Web Analytics -->
</body>

</html>

<?php
if ($con) {
    $con->close();
}
?>