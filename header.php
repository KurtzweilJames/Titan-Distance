<?php
session_start();
include("db.php");
include("config.php");
$todaydate = date('Y-m-d');
$currenttime = date("g:i a");

function formatTime($time)
{
    if (substr($time, 0, 3) == "0:0") {
        return substr($time, 3);
    } else if (substr($time, 0, 2) == "0:") {
        return substr($time, 2);
    } else if (substr($time, 0, 3) == "09:") {
        return substr($time, 1);
    } else {
        return $time;
    }
    return $time;
}
?>

<!DOCTYPE html>
<html lang="en-US" data-bs-theme="auto">

<head>
    <!-- Color Mode (Dark Mode) -->
    <script src="/includes/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '<?php echo $googletapmanagerkey; ?>');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Clarity tracking code for http://titandistance.com/ -->
    <script>
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i + "?ref=bwt";
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "{api-key}");
    </script>

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700%7CRoboto:400,500,700" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">


    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Simple Data Tables -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@5.0.3/dist/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@5.0.3" type="text/javascript"></script>
    <?php
    if (isset($pgtitle) && ($pgtitle == "Roster" || $pgtitle == "Results")) {
        echo '<script src="https://cdn.jsdelivr.net/npm/moment@2.29.3/moment.min.js"></script>';
    }
    ?>

    <!-- Meta Tags -->
    <?php
    $pageTitle = isset($pgtitle) ? $pgtitle . " - " : "";
    ?>
    <title><?php echo $pageTitle; ?>Titan Distance</title>
    <meta name="title" content="<?php echo $pageTitle; ?>Titan Distance">
    <meta name="description" content="Home of Glenbrook South Cross Country and Track">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://titandistance.com/">
    <meta property="og:title" content="<?php echo $pageTitle; ?>Titan Distance">
    <meta property="og:description" content="Home of Glenbrook South Cross Country and Track">
    <?php
    if (isset($image)) {
        echo "<meta property='og:image' content='https://titandistance.com/assets/images/" . $image . "'>";
        echo "<meta property='twitter:image' content='https://titandistance.com/assets/images/" . $image . "'>";
    } else {
        echo "<meta property='og:image' content='https://titandistance.com/assets/images/td-card.png'>";
        echo "<meta property='twitter:image' content='https://titandistance.com/assets/images/td-card.png'>";
    }
    ?>
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://titandistance.com/">
    <meta property="twitter:title" content="<?php echo $pageTitle; ?>Titan Distance">
    <meta property="twitter:description" content="Home of Glenbrook South Cross Country and Track">

    <?php
    if (isset($public) && $public != 1) {
        echo '<meta name="robots" content="noindex">';
    }
    if (isset($noindex) && $noindex == true) {
        echo '<meta name="robots" content="noindex">';
    }
    ?>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SportsTeam",
            "url": "https://titandistance.com",
            "logo": "https://titandistance.com/assets/logos/color.svg",
            "name": "Titan Distance",
            "sport": ["Track and Field", "Cross Country"],
            "gender": "male",
            "memberOf": [{
                "@type": "SportsOrganization",
                "name": "Illinois High School Association"
            }, {
                "@type": "SportsOrganization",
                "name": "Central Suburban League"
            }, {
                "@type": "School",
                "name": "Glenbrook South High School"
            }]
        }
    </script>

    <!-- OneSignal -->
    <!-- <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "<?php //echo $onesignalappid; 
                        ?>",
            });
        });
    </script> -->

    <!-- Other JS -->
    <?php
    if ((isset($require) && $require == "meet") || (isset($pgtitle) && $pgtitle == "Routes")) {
        echo "<script src='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js'></script>";
        echo "<link href='https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css' rel='stylesheet'>";
    }
    ?>

    <style>
        [data-bs-theme=light],
        [data-bs-theme=auto] {
            --bs-blue: #073763;
            --bs-primary: #073763;
            --bs-primary-rgb: 7, 55, 99;
            --bs-link-color: #073763;
            --bs-link-color-rgb: 7, 55, 99;
            /* --bs-body-color: #073763 !important;
            --bs-body-color-rgb: 7, 55, 99 !important; */
            --bs-heading-color: #073763;
            --bs-heading-color-rgb: 7, 55, 99;
        }

        .tdmastheadbkg {
            background-image: url(<?php echo $headerimage; ?>);
            background-size: cover;
            /* background-position: center top; */
            background-position: center;
            margin-top: -.5rem !important;
        }

        .relay-underline {
            text-decoration-style: wavy;
        }

        @media (min-width: 768px) {
            .tdmastheadbkg {
                min-height: 400px;
                padding-right: 15%;
                padding-left: 15%;
            }
        }

        @media (max-width: 768px) {
            .tdmastheadbkg {
                min-height: 200px;
                padding-right: 0%;
                padding-left: 0%;
            }
        }

        .hover-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
        }

        .showcase-img {
            min-height: 20rem;
            background-size: cover;
        }

        /* .dataTable-input,
        .dataTable-selector {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--bs-body-color);
            background-color: var(--bs-form-control-bg);
            background-clip: padding-box;
            border: var(--bs-border-width) solid var(--bs-border-color);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: .375rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        } */

        /* .card-header {
            font-weight: bold;
        }

        .tdas {
            width: 100%;
        }

        .social-icons {
            border-radius: 50%;
        }

        .social-icons:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
        }
*/
        #coursemap {
            position: relative;
            width: 100%;
            height: 400px;
        }

        body {
            font-family: roboto, sans-serif;
            font-weight: 400;
            line-height: 1.5;
            /* color: #212529; */
            font-size: .875rem;
        }

        /*
        .showcase-img {
            min-height: 30rem;
            background-size: cover
        }
*/
        .nav-item {
            line-height: 22px;
            color: #212529;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 14px;
            padding: 0px 10px;
        }

        .text-bg-active {
            color: #073763 !important;
            background-color: #ffd700 !important;
        }

        .text-bg-primary {
            color: #ffd700 !important;
            background-color: #073763 !important;
        }

        .article {
            min-height: 250px;
        }

        .article-image {
            float: right;
            margin: 5px 0 13px 20px;
            display: block;
            position: relative;
            width: 100%;
            height: auto;
        }

        .bg-ihsa {
            color: #0d4183;
            background-color: #f4720e;
        }

        .bg-csl {
            color: #fff;
            background-color: #6c757d;
        }

        [data-bs-theme=light] .row-highlight {
            --bs-table-color-state: var(--bs-table-active-color);
            --bs-table-bg-state: #FFF099;
        }

        [data-bs-theme=dark] .row-highlight {
            --bs-table-color-state: var(--bs-table-active-color);
            --bs-table-bg-state: rgba(255, 220, 40, .15);
        }

        a {
            text-decoration: none
        }

        .btn-strava {
            color: #fff;
            background-color: #fc4d02;
        }

        .btn-td,
        .btn-primary {
            --bs-btn-color: #ffd700;
            --bs-btn-bg: #073763;
            --bs-btn-border-color: #073763;
            --bs-btn-hover-color: #073763;
            --bs-btn-hover-bg: #ffd700;
            --bs-btn-hover-border-color: #ffd700;
        }

        /*
        @media print {
            .sticky-header {
                display: none;
            }

            footer {
                display: none;
            }

            #header-logo {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 50%;
            }

            .series-navigation {
                display: none !important;
            }

            #top-bar {
                display: none;
            }

            .navbar-toggler {
                display: none;
            }

            .header-logo {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 40%;
            }

            #selectTab {
                display: none;
            }

            hr {
                display: none;
            }
        }

        .container {
            max-width: 1200px;
        }
        */

        .splits-col {
            display: table-cell;
        }

        .college-overlay {
            position: absolute;
            bottom: 10px;
            right: 10px;
            max-width: 35%;
            max-height: 40%;
            opacity: 0.98;
        }

        .athlete-image {
            position: relative;
            display: inline-block;
        }

        [data-bs-theme=light] .logo-fill {
            fill: #073763;
        }

        [data-bs-theme=dark] .logo-fill {
            fill: #fff;
        }
    </style>


    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
    <link rel='manifest' href='/manifest.json'>
    <link rel="mask-icon" href="/assets/icons/safari-pinned-tab.svg" color="#ffd700">
    <link rel="shortcut icon" href="/assets/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="/assets/icons/browserconfig.xml">
    <meta name="theme-color" content="#073763">


</head>

<body class="h-100">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $googletapmanagerkey; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="/assets/logos/bolt.svg" height="18px" class="rounded me-2" alt="Titan Distance">
                <strong class="me-auto" id="toastHeader">Titan Distance</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toastBody">bootstrap.Toast.getOrCreateInstance(document.getElementById("liveToast")).show()</div>
        </div>
    </div> -->
    <?php
    $currentdate = date('Y-m-d');
    $result = mysqli_query($con, "SELECT * FROM tdas WHERE startdate<='" . $currentdate . "' AND enddate>='" . $currentdate . "' ORDER BY id DESC");
    while ($row = mysqli_fetch_array($result)) {
        if ($row['type'] == 0) {
            $type = "danger";
        } else if ($row['type'] == 1) {
            $type = "info";
        } else if ($row['type'] == 2) {
            $type = "warning";
        } else if ($row['type'] == 3) {
            $type = "success";
        }

        if ($row['nostick'] == 0) {
            //$type = $type . " sticky-top";
        }

        if (!empty($row['link'])) {
            $type = $type . " clickable-row";
        }
        if (!empty($row['web'])) {
            echo "<div id='tdas-topbar' class='tdas alert alert-" . $type . " text-center py-2 px-5 m-0' role='alert' onclick = window.location='" . $row['link'] . "'><strong style='text-transform: uppercase;'>" . $row['title'] . ": </strong>" . $row['web'] . "</div>";
        }
    }
    ?>

    <?php
    $result = mysqli_query($con, "SELECT * FROM meets WHERE Date = '" . $todaydate . "' AND (Official = 0 OR Official = 4) AND NOT(`Status` <=> 'C')");
    if (isset($_SESSION["loggedin"]) || mysqli_num_rows($result) > 0) {
        include($_SERVER['DOCUMENT_ROOT'] . "/includes/topbar.php");
    }
    ?>
    <div class="container p-2 d-none d-lg-block">
        <div class="row">
            <div class="col-sm-4 text-center my-auto">
                <a href="https://x.com/TitanDistance" target="_blank"><img src="/assets/icons/x.svg" class="social-icons" alt="X (Twitter)" width="32" height="32" title="X (Twitter)"></a>
                <a href="https://www.facebook.com/titandistance" target="_blank"><img src="/assets/icons/facebook.svg" class="social-icons" alt="Facebook" width="32" height="32" title="Facebook"></a>
                <a href="https://instagram.com/TitanDistance" target="_blank"><img src="/assets/icons/instagram.svg" class="social-icons" alt="Instagram" width="32" height="32" title="Instagram"></a>
            </div>
            <div class="col-sm-4">
                <a href="/home">
                    <svg xmlns="http://www.w3.org/2000/svg" stroke-miterlimit="10" style="fill:none;stroke:none;stroke-linecap:square;stroke-miterlimit:10" viewBox="0 0 948.999 160"><path class="logo-fill" style="fill-rule:nonzero" d="m3.578 37.927 16.266.89h18.562l16.25-.89.64-3.703h2.438l.5 18.172h-2.422l-1.156-5-19.203-.641-1.156 17.031v26.875l1.547 21.89 5.75.891-.125 2.438H17.016l-.125-2.438 5.765-.89 1.532-22.016V63.661l-1.282-16.906-19.328.64-1.14 5H0l.516-18.171h2.421Zm66.817 74.625 1.547-22.016V63.661l-1.547-21.89-3.828-.907.125-2.421h20.61l.124 2.421-3.843.907-1.532 22.015v26.875l1.532 21.89 3.843.891-.125 2.438h-20.61l-.124-2.438zm28.927-74.625 16.266.89h18.562l16.25-.89.64-3.703h2.438l.5 18.172h-2.421l-1.157-5-19.203-.641-1.156 17.031v26.875l1.547 21.89 5.75.891-.125 2.438H112.76l-.125-2.438 5.765-.89 1.532-22.016V63.661l-1.282-16.906-19.328.64-1.14 5h-2.438l.516-18.171h2.422zm108.16 58.625-2.421-6.781h-29.188l-2.562 6.78-3.844 16 5.516.641-.14 2.688h-21.376l-.125-2.438 3.844-.89 28.922-70.781-4.86-.907.125-2.421h21l.125 2.421-4.859.907 26.75 70.78 3.828.891-.125 2.438h-22.781l-.125-2.688 5.5-.64zm-16.374-45.563-11.906 30.203h22.78zm47.351 61.563 1.547-22.016V63.661l-1.672-21.89-3.703-.907.125-2.421h21.25l.125 2.687-5.64.64h-.126l40.328 55.422v-33.53l-1.671-21.891-5.125-.641.14-2.688h21.11l.14 2.422-3.844.907-1.546 22.015v23.422l1.546 25.344 3.844.89-.14 2.438h-20.735l-.125-2.687 5.766-.641-40.844-55.938v34.047l1.547 21.89 5.11.642-.126 2.687h-20.984l-.125-2.437zM426.875 112.427l1.547-21.89v-26.75l-1.547-21.891-3.828-.891.125-2.438q15.61-.515 30.078-.515 19.063 0 28.281 9.094 9.219 9.093 9.219 28.796 0 40.329-38.906 40.329l-28.672-.516-.125-2.438zm11.656-21.766 1.016 15.36q6.406 2.046 14.469 2.046 25.093 0 25.093-30.078 0-16.375-6.656-24.312-6.656-7.938-20.484-7.938-6.016 0-12.297 2.047l-1.14 16.125zm70.136 21.89 1.547-22.015V63.661l-1.547-21.89-3.828-.907.125-2.421h20.61l.124 2.421-3.843.907-1.532 22.015v26.875l1.532 21.89 3.843.891-.125 2.438h-20.61l-.124-2.438zm39.052-14.843-.64 5.89q9.984 5.75 17.78 5.75 7.813 0 12.282-3.187 4.484-3.203 4.484-8.39 0-5.188-2.937-8.829-2.938-3.656-7.422-5.578-4.484-1.922-9.672-4.28-5.172-2.376-9.656-4.61-4.484-2.235-7.438-6.594-2.937-4.36-2.937-10.375 0-9.344 7.75-15.156 7.75-5.828 20.031-5.828 7.547 0 15.485 2.437l.906-3.328h2.422l-1.016 21.625h-2.437l-.891-5.5q-10.89-7.172-19.078-7.172-5.625 0-8.703 2.5-3.078 2.5-3.078 7.5 0 4.985 3.968 8.563 3.969 3.578 9.657 5.765 5.703 2.172 11.39 4.672 5.703 2.485 9.672 7.422 3.969 4.922 3.969 12.344 0 11.265-8.328 17.86-8.313 6.593-22.266 6.593-8.187 0-18.422-2.953l-1.797 2.828-2.297-.766 6.907-19.843zm53.875-59.781 16.266.89h18.562l16.25-.89.64-3.703h2.438l.5 18.172h-2.421l-1.157-5-19.203-.641-1.156 17.031v26.875l1.547 21.89 5.75.891-.125 2.438h-24.453l-.125-2.438 5.765-.89 1.532-22.016V63.661l-1.282-16.906-19.328.64-1.14 5h-2.438l.516-18.171h2.422zm108.16 58.625-2.421-6.781h-29.188l-2.562 6.78-3.844 16 5.516.641-.14 2.688h-21.376l-.125-2.438 3.844-.89L688.38 41.77l-4.86-.907.125-2.421h21l.125 2.421-4.859.907 26.75 70.78 3.828.891-.125 2.438h-22.781l-.125-2.688 5.5-.64zM693.38 50.989l-11.907 30.203h22.782zm47.351 61.563 1.547-22.016V63.661l-1.672-21.89-3.703-.907.125-2.421h21.25l.125 2.687-5.64.64h-.126l40.328 55.422v-33.53l-1.672-21.891-5.125-.641.141-2.688h21.11l.14 2.422-3.844.907-1.547 22.015v23.422l1.547 25.344 3.844.89-.14 2.438h-20.735l-.125-2.687 5.766-.641-40.844-55.938v34.047l1.547 21.89 5.11.642-.126 2.687h-20.984l-.125-2.437zm114.954-67.578q-10.5 0-16.141 8.25-5.625 8.25-5.625 23.547t5.953 23.937q5.953 8.64 16.125 8.64t21.703-6.655l2.172-5.11 2.297.5-4.469 20.36h-2.562l.125-3.204q-8.578 2.563-19.203 2.563-16.25 0-25.344-10.36-9.078-10.374-9.078-29.374 0-19.016 9.843-30.282 9.86-11.265 26.766-11.265 8.438 0 17.906 3.203l.907-3.203h2.421l-.89 19.718h-2.438l-.89-5.39q-11.266-5.875-19.578-5.875zm87.923 3.453-13.687-1.281h-18.953l-1.14 16.64v5.89h24.577l1.016-3.718h2.437l-.765 17.531h-2.438l-.64-5.109h-24.188v12.281l1.14 16.516h18.954l14.719-1.406 2.172-5.125 2.187.515-3.969 18.172-2.312-.125.125-3.328h-48.375l-.125-2.438 3.828-.89 1.547-22.016V63.661l-1.547-21.89-3.828-.907.125-2.421h49.531l1.031-3.72h2.422l-.765 18.813h-2.438z"/><path style="fill:#feb120;fill-opacity:1;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" d="M394.222 2.49 309.34 87.373l53.5 1.196-46.327 69.041 101.32-92.653-54.695-.597z"/></svg>
                </a>
            </div>
            <div class="col-sm-4 text-center my-auto">
                <?php if ($currentsport == "xc") {
                    echo '<a href="https://www.athletic.net/team/16382/cross-country" target="_blank"><img src="/assets/icons/athnet.svg" class="social-icons" alt="Athletic.net" width="32" height="32" title="Athletic.net"></a>';
                } else if ($currentsport == "tf") {
                    echo '<a href="https://www.athletic.net/team/16382/track-and-field" target="_blank"><img src="/assets/icons/athnet.svg" class="social-icons" alt="Athletic.net" width="32" height="32" title="Athletic.net"></a>';
                }
                ?>
                <!-- <a href="https://connect.garmin.com/modern/group/3215758" target="_blank"><img src="/assets/icons/garmin.svg" class="social-icons" alt="Garmin" width="32" height="32" title="Garmin"></a> -->
                <a href="https://www.strava.com/clubs/titandistance" target="_blank"><img src="/assets/icons/strava.svg" class="social-icons" alt="Strava" width="32" height="32" title="Strava"></a>
            </div>
        </div>
    </div>
    <div class="sticky-top mb-2" id="top-navbar">
        <nav class="navbar navbar-expand-lg bg-body pb-1">
            <a href="/home" id="header-logo" class="d-block d-lg-none mx-auto ms-2">
                <svg height="30" xmlns="http://www.w3.org/2000/svg" stroke-miterlimit="10" style="fill:none;stroke:none;stroke-linecap:square;stroke-miterlimit:10" viewBox="0 0 948.999 160"><path class="logo-fill" style="fill-rule:nonzero" d="m3.578 37.927 16.266.89h18.562l16.25-.89.64-3.703h2.438l.5 18.172h-2.422l-1.156-5-19.203-.641-1.156 17.031v26.875l1.547 21.89 5.75.891-.125 2.438H17.016l-.125-2.438 5.765-.89 1.532-22.016V63.661l-1.282-16.906-19.328.64-1.14 5H0l.516-18.171h2.421Zm66.817 74.625 1.547-22.016V63.661l-1.547-21.89-3.828-.907.125-2.421h20.61l.124 2.421-3.843.907-1.532 22.015v26.875l1.532 21.89 3.843.891-.125 2.438h-20.61l-.124-2.438zm28.927-74.625 16.266.89h18.562l16.25-.89.64-3.703h2.438l.5 18.172h-2.421l-1.157-5-19.203-.641-1.156 17.031v26.875l1.547 21.89 5.75.891-.125 2.438H112.76l-.125-2.438 5.765-.89 1.532-22.016V63.661l-1.282-16.906-19.328.64-1.14 5h-2.438l.516-18.171h2.422zm108.16 58.625-2.421-6.781h-29.188l-2.562 6.78-3.844 16 5.516.641-.14 2.688h-21.376l-.125-2.438 3.844-.89 28.922-70.781-4.86-.907.125-2.421h21l.125 2.421-4.859.907 26.75 70.78 3.828.891-.125 2.438h-22.781l-.125-2.688 5.5-.64zm-16.374-45.563-11.906 30.203h22.78zm47.351 61.563 1.547-22.016V63.661l-1.672-21.89-3.703-.907.125-2.421h21.25l.125 2.687-5.64.64h-.126l40.328 55.422v-33.53l-1.671-21.891-5.125-.641.14-2.688h21.11l.14 2.422-3.844.907-1.546 22.015v23.422l1.546 25.344 3.844.89-.14 2.438h-20.735l-.125-2.687 5.766-.641-40.844-55.938v34.047l1.547 21.89 5.11.642-.126 2.687h-20.984l-.125-2.437zM426.875 112.427l1.547-21.89v-26.75l-1.547-21.891-3.828-.891.125-2.438q15.61-.515 30.078-.515 19.063 0 28.281 9.094 9.219 9.093 9.219 28.796 0 40.329-38.906 40.329l-28.672-.516-.125-2.438zm11.656-21.766 1.016 15.36q6.406 2.046 14.469 2.046 25.093 0 25.093-30.078 0-16.375-6.656-24.312-6.656-7.938-20.484-7.938-6.016 0-12.297 2.047l-1.14 16.125zm70.136 21.89 1.547-22.015V63.661l-1.547-21.89-3.828-.907.125-2.421h20.61l.124 2.421-3.843.907-1.532 22.015v26.875l1.532 21.89 3.843.891-.125 2.438h-20.61l-.124-2.438zm39.052-14.843-.64 5.89q9.984 5.75 17.78 5.75 7.813 0 12.282-3.187 4.484-3.203 4.484-8.39 0-5.188-2.937-8.829-2.938-3.656-7.422-5.578-4.484-1.922-9.672-4.28-5.172-2.376-9.656-4.61-4.484-2.235-7.438-6.594-2.937-4.36-2.937-10.375 0-9.344 7.75-15.156 7.75-5.828 20.031-5.828 7.547 0 15.485 2.437l.906-3.328h2.422l-1.016 21.625h-2.437l-.891-5.5q-10.89-7.172-19.078-7.172-5.625 0-8.703 2.5-3.078 2.5-3.078 7.5 0 4.985 3.968 8.563 3.969 3.578 9.657 5.765 5.703 2.172 11.39 4.672 5.703 2.485 9.672 7.422 3.969 4.922 3.969 12.344 0 11.265-8.328 17.86-8.313 6.593-22.266 6.593-8.187 0-18.422-2.953l-1.797 2.828-2.297-.766 6.907-19.843zm53.875-59.781 16.266.89h18.562l16.25-.89.64-3.703h2.438l.5 18.172h-2.421l-1.157-5-19.203-.641-1.156 17.031v26.875l1.547 21.89 5.75.891-.125 2.438h-24.453l-.125-2.438 5.765-.89 1.532-22.016V63.661l-1.282-16.906-19.328.64-1.14 5h-2.438l.516-18.171h2.422zm108.16 58.625-2.421-6.781h-29.188l-2.562 6.78-3.844 16 5.516.641-.14 2.688h-21.376l-.125-2.438 3.844-.89L688.38 41.77l-4.86-.907.125-2.421h21l.125 2.421-4.859.907 26.75 70.78 3.828.891-.125 2.438h-22.781l-.125-2.688 5.5-.64zM693.38 50.989l-11.907 30.203h22.782zm47.351 61.563 1.547-22.016V63.661l-1.672-21.89-3.703-.907.125-2.421h21.25l.125 2.687-5.64.64h-.126l40.328 55.422v-33.53l-1.672-21.891-5.125-.641.141-2.688h21.11l.14 2.422-3.844.907-1.547 22.015v23.422l1.547 25.344 3.844.89-.14 2.438h-20.735l-.125-2.687 5.766-.641-40.844-55.938v34.047l1.547 21.89 5.11.642-.126 2.687h-20.984l-.125-2.437zm114.954-67.578q-10.5 0-16.141 8.25-5.625 8.25-5.625 23.547t5.953 23.937q5.953 8.64 16.125 8.64t21.703-6.655l2.172-5.11 2.297.5-4.469 20.36h-2.562l.125-3.204q-8.578 2.563-19.203 2.563-16.25 0-25.344-10.36-9.078-10.374-9.078-29.374 0-19.016 9.843-30.282 9.86-11.265 26.766-11.265 8.438 0 17.906 3.203l.907-3.203h2.421l-.89 19.718h-2.438l-.89-5.39q-11.266-5.875-19.578-5.875zm87.923 3.453-13.687-1.281h-18.953l-1.14 16.64v5.89h24.577l1.016-3.718h2.437l-.765 17.531h-2.438l-.64-5.109h-24.188v12.281l1.14 16.516h18.954l14.719-1.406 2.172-5.125 2.187.515-3.969 18.172-2.312-.125.125-3.328h-48.375l-.125-2.438 3.828-.89 1.547-22.016V63.661l-1.547-21.89-3.828-.907.125-2.421h49.531l1.031-3.72h2.422l-.765 18.813h-2.438z"/><path style="fill:#feb120;fill-opacity:1;stroke:none;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" d="M394.222 2.49 309.34 87.373l53.5 1.196-46.327 69.041 101.32-92.653-54.695-.597z"/></svg>
            </a>
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto" id="navbar_nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/about" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            About
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <a class="dropdown-item" href="/about">Welcome</a>
                            <a class="dropdown-item" href="/about#coaches">Coaching Staff</a>
                            <a class="dropdown-item" href="/venues">Home Venues</a>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/records" id="historyDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            History
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="historyDropdown">
                            <a class="dropdown-item" href="/routes">Running Routes</a>
                            <a class="dropdown-item" href="/state">State Qualifers</a>
                            <!-- <a class="dropdown-item" href="/innews">In the News</a> -->
                            <a class="dropdown-item" href="/booklets">Post-Season Booklets</a>
                            <a class="dropdown-item" href="/venues">Home Venues</a>
                            <a class="dropdown-item" href="/roster/all">All-Time Roster</a>
                            <a class="dropdown-item" href="/records/mvps">MVPs</a>
                            <a class="dropdown-item" href="/records">All Records</a>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <h6 class="dropdown-header">Cross Country</h6>
                            </li>
                            <a class="dropdown-item" href="/records/course">Course Records</a>
                            <a class="dropdown-item" href="/records/sub16">Sub-16 Club & Top Times</a>
                            <a class="dropdown-item" href="/records/top7">Top 7 by Year</a>
                            <a class="dropdown-item" href="/records/xcclass">Top 3-Mile Times by Class</a>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <h6 class="dropdown-header">Track</h6>
                            </li>
                            <a class="dropdown-item" href="/records/outdoor10">Outdoor Top 10</a>
                            <a class="dropdown-item" href="/records/indoor10">Indoor Home Meets Top 10</a>
                            <a class="dropdown-item" href="/records/class">Class Records</a>
                            <a class="dropdown-item" href="/records/sub5">Sub-5/10/2 Club</a>
                            <!-- <a class="dropdown-item" href="/records/distance">Distance by Class</a> -->
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/roster/<?php echo $currentshort; ?>">Roster</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/schedule">Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/results">Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/news">News</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/workouts">Workouts</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="/photos">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/alumni">Alumni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="bi bi-search me-2 me-md-0"></i><span class="d-sm-block d-md-none">Search</span></a>
                    </li>
                    <li class="nav-item d-sm-block d-md-none">
                        <a class="mx-2" href="https://instagram.com/TitanDistance"><i class="bi bi-instagram"></i></a>
                        <a class="mx-2" href="https://twitter.com/TitanDistance"><i class="bi bi-twitter-x"></i></a>
                        <a class="mx-2" href="https://facebook.com/TitanDistance"><i class="bi bi-facebook"></i></a>
                        <?php if ($currentsport == "xc") {
                            echo '<a class="mx-2" href="https://www.athletic.net/team/16382/cross-country"><img src="/assets/icons/AthNetSquare.svg" alt="AthleticNET" height="14"></a>';
                        } else if ($currentsport == "tf") {
                            echo '<a class="mx-2" href="https://www.athletic.net/team/16382/track-and-field"><img src="/assets/icons/AthNetSquare.svg" alt="AthleticNET" height="14"></a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
            <!-- </div> -->
        </nav>
    </div>