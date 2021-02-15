<?php 
session_start();
include("db.php");
include("config.php");
$todaydate = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
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

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,700%7CRoboto:400,500,700" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>

    <!-- Meta Tags -->
    <title><?php if (isset($pgtitle)) { echo $pgtitle." - "; } ?>Titan Distance</title>
    <meta name="title" content="<?php if (isset($pgtitle)) { echo $pgtitle." - "; } ?>Titan Distance">
    <meta name="description" content="Home of Glenbrook South Cross Country and Track">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://titandistance.com/">
    <meta property="og:title" content="<?php if (isset($pgtitle)) { echo $pgtitle." - "; } ?>Titan Distance">
    <meta property="og:description" content="Home of Glenbrook South Cross Country and Track">
    <?php
if (isset($image)) {
    echo "<meta property='og:image' content='https://titandistance.com/assets/images/".$image."'>";
    echo "<meta property='twitter:image' content='https://titandistance.com/assets/images/".$image."'>";
} else {
    echo "<meta property='og:image' content='https://titandistance.com/assets/images/td-card.png'>";
    echo "<meta property='twitter:image' content='https://titandistance.com/assets/images/td-card.png'>";
}
    ?>
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://titandistance.com/">
    <meta property="twitter:title" content="<?php if (isset($pgtitle)) { echo $pgtitle." - "; } ?>Titan Distance">
    <meta property="twitter:description" content="Home of Glenbrook South Cross Country and Track">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css"
        type="text/css" />

    <!-- OneSignal -->
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
    var OneSignal = window.OneSignal || [];
    OneSignal.push(function() {
        OneSignal.init({
            appId: "<?php echo $onesignalappid; ?>",
        });
    });
    </script>

    <!-- Other JS -->
    <?php
if ($require == "meet") {
    echo "<script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>";
    echo "<link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />";
}
    ?>

    <style>
    .tdmastheadbkg {
        background-image: url(<?php echo $headerimage; ?>);
        background-size: cover;
        background-position: center top;
    }

    @media (min-width: 768px) {
        .tdmastheadbkg {
            height: 400px;
            padding-right: 15%;
            padding-left: 15%;
            padding-top: 100px;
        }
    }

    @media (max-width: 768px) {
        .tdmastheadbkg {
            height: 200px;
            padding-right: 0%;
            padding-left: 0%;
            padding-top: 25px;
        }
    }

    .card-header {
        font-weight: bold;
    }

    .athlete-image {
        max-width: 75%;
    }

    .tdas {
        width: 100%;
        padding-right: 15%;
        padding-left: 15%;
        padding-top: 5px;
        padding-bottom: 5px;
        margin-right: auto;
        margin-left: auto;
    }

    .hover-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
    }

    .social-icons {
        border-radius: 50%;
    }

    .social-icons:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
    }

    #coursemap {
        position: relative;
        width: 100%;
        height: 400px;
    }

    body {
        font-family: roboto, sans-serif;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        font-size: .875rem;
    }

    .showcase-img {
        min-height: 30rem;
        background-size: cover
    }

    .nav-item {
        line-height: 22px;
        color: #212529;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 14px;
        padding: 0px 15px;
    }

    .footer {
        background-color: #f5f5f5;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        color: #444;
        font-weight: 600;
        line-height: 1.5;
    }

    h1 {
        font-size: 36px;
    }

    h5 {
        font-size: 14px;
    }

    .navbar-collapse {
        border-top: 1px solid #f5f5f5;
    }

    .article-image {
        float: right;
        margin: 5px 0 13px 20px;
        display: block;
        position: relative;
        width: 100%;
        height: auto;
    }

    .meta {
        display: flex;
        flex-wrap: wrap;
        padding: 0.5rem 1rem;
        margin-bottom: 1rem;
        list-style: none;
        border-radius: 0.25rem;
    }

    .meta-item {
        display: flex;
    }

    .meta-item+.meta-item {
        padding-left: 0.5rem;
    }

    .meta-item+.meta-item::before {
        display: inline-block;
        padding-right: 0.5rem;
        color: #6c757d;
        content: "//";
    }

    .meta-item i {
        position: relative;
        top: 1px;
        padding-left: 1px;
        margin-right: 5px;
    }

    .badge-ihsa {
        color: #0d4183;
        background-color: #f4720e;
    }

    .badge-csl {
        color: #fff;
        background-color: #6c757d;
    }

    .btn-strava {
        color: #fff;
        background-color: #fc4d02;
    }

    .row-highlight {
        background-color: #FFF099;
    }

    .sticky-header {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 10;
    }

    .sticky-header+.tdmasthead {
        padding-top: 102px;
    }

    body.dark-theme {
        color: #bebebe !important;
        background-color: #222 !important;
    }

    .card.dark-theme {
        color: #bebebe !important;
        background-color: #181818 !important;
    }

    .bg-light.dark-theme {
        color: #bebebe !important;
        background-color: #181818 !important;
    }

    .footer.dark-theme {
        color: #bebebe !important;
        background-color: #181818 !important;
    }

    .nav-link.dark-theme {
        color: #bebebe !important;
    }

    .table.dark-theme {
        color: #bebebe !important;
    }

    .table:hover.dark-theme {}
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
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $googletapmanagerkey; ?>" height="0"
            width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
        $currentdate = date('Y-m-d');
        $result = mysqli_query($con,"SELECT * FROM tdas WHERE startdate<='".$currentdate."' AND enddate>='".$currentdate."' ORDER BY id DESC");
        while($row = mysqli_fetch_array($result)) {
         if ($row['type'] == 0) {
             $type = "danger";
         } else if ($row['type'] == 1) {
             $type = "info";
         } else if ($row['type'] == 2) {
             $type = "warning";
         } else if ($row['type'] == 3) {
            $type = "success";
         }

        if($row['nostick'] == 0) {
            $type = $type." sticky-top";
        }
        
        if (!empty($row['link'])) {
            $type = $type." clickable-row";
        }
        if (!empty($row['web'])) {
        echo "<div class='tdas alert-".$type." clickable text-center pt-3 pb-3' role='alert' data-href='".$row['link']."'><strong style='text-transform: uppercase;'>".$row['title'].": </strong>".$row['web']."</div>";
        }
    }
        ?>
    <header>
        <div class="bg-light">
            <div class="container d-flex bd-highlight">
                <div class="mr-auto p-2 m-0">
                    <?php
                    $result = mysqli_query($con,"SELECT Live,Name,id FROM meets WHERE Date = '".$todaydate."' AND Official != 1 AND Official != 2");
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result)){
                            if (!empty($row['Live'])) {
                                echo "<a href='".$row['Live']."'><strong>Live Results for ".$row['Name']." are available here.</strong></a>";
                            } else {
                                echo "<a href='/meet/".$row['id']."'><strong>Good Luck Titans at ".$row['Name']."!</strong></a>";
                            }
                        }
                    } else if(isset($_SESSION["loggedin"])){
                        if ($template == "meet") {
                            echo "<a href='/admin/meet?id=".$id."'><i class='fas fa-pencil-alt'></i> Edit Meet (ID = ".$id.")</a>";
                        } else if ($template == "news") {
                            echo "<a href='/admin/news?id=".$id."'><i class='fas fa-pencil-alt'></i> Edit News Article</a>";
                        } else {
                            echo "Welcome, ".$_SESSION["username"]." <a class='ml-2' href='/admin'>Return to Admin</a>";
                        }
                    } else {
                        if ($template == "home") {
                            echo "<div class='text-muted d-none d-md-block' id='sayings'>Home of Glenbrook South Cross Country and Track</div>";
                        } else {
                            echo "<div class='text-muted d-none d-md-block' id='sayings'>".$sayings[array_rand($sayings)]."</div>";
                        }
                    }
                    ?>
                </div>
                <?php
$weatherfile = file_get_contents("https://titandistance.com/api/weather.php");
$jsonweather = json_decode($weatherfile);
                ?>
                <div class="p-2 m-0" id="weather-widget" data-toggle="tooltip" data-html="true" data-placement="bottom"
                    title="Weather: <?php echo $jsonweather->description; ?>
                   <br>Feels Like: <?php echo $jsonweather->feelslike; ?>°F<br>Temperature:
                    <?php echo $jsonweather->temp; ?>°F">
                    <?php
echo "<span id='temp'>".$jsonweather->temp."°F</span>";
echo "<i class='mb-0 mx-1 fas ".$jsonweather->icon."'></i>";
                    ?>
                </div>
            </div>
        </div>
        <div class="container p-4 d-none d-md-block">
            <div class="row">
                <div class="col-sm-4 text-center my-auto">
                    <a href="https://www.facebook.com/titandistance" target="_blank"><img
                            src="/assets/icons/facebook.svg" class="social-icons" alt="Facebook" width="32" height="32"
                            title="Facebook"></a>
                    <a href="https://twitter.com/TitanDistance" target="_blank"><img src="/assets/icons/twitter.svg"
                            class="social-icons" alt="Twitter" width="32" height="32" title="Twitter"></a>
                    <a href="https://instagram.com/TitanDistance" target="_blank"><img src="/assets/icons/instagram.svg"
                            class="social-icons" alt="Instagram" width="32" height="32" title="Instagram"></a>
                </div>
                <div class="col-sm-4">
                    <a href="/"><img src="/assets/logos/dotcom.svg" class="mx-auto img-fluid" alt="Titan Distance"></a>
                </div>
                <div class="col-sm-4 text-center my-auto">
                    <a href="https://www.athletic.net/CrossCountry/School.aspx?SchoolID=16382" target="_blank"><img
                            src="/assets/icons/athnet.svg" class="social-icons" alt="Athletic.net" width="32"
                            height="32" title="Athletic.net"></a>
                    <a href="https://www.strava.com/clubs/titandistance" target="_blank"><img
                            src="/assets/icons/strava.svg" class="social-icons" alt="Strava" width="32" height="32"
                            title="Strava"></a>
                </div>
            </div>
        </div>
        <div class="pb-1" id="top-navbar">
            <nav class="navbar navbar-expand-lg navbar-light p-md-0">
                <a href="/"><img src="https://titandistance.com/assets/logos/dotcom.svg" class="d-block d-md-none"
                        height="30" alt="Titan Distance"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto" id="navbar_nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about">About</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/history" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                History
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/records">Records</a>
                                <a class="dropdown-item" href="/routes">Running Routes</a>
                                <a class="dropdown-item" href="/state">State Qualifers</a>
                                <a class="dropdown-item" href="/innews">In the News</a>
                                <a class="dropdown-item" href="/roster/all">All-Time
                                    Roster</a>
                            </div>
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
                        <li class="nav-item">
                            <a class="nav-link" href="/workouts">Workouts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/photos">Photos</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <main>