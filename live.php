<?php
include("db.php");
$todaydate = date('Y-m-d');

$result = mysqli_query($con, "SELECT * FROM meets WHERE Date = '" . $todaydate . "'");
if (mysqli_num_rows($result) == 0) {
    $message = "There are currently no live events.";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['Name'];
        $id = $row['id'];

        if (empty($row['Live'])) {
            $message = "Live Results are not available for this meet. Please check on TitanDistance.com after the meet for results.";
        } else {
            $message = "Please click the link below for live results for the meet.";
            $live = $row['Live'];
        }
    }

    if (empty($row['Series'])) {
        $url = "/meet/" . $id;
    } else {
        $result = mysqli_query($con, "SELECT * FROM series");
        while ($row = mysqli_fetch_array($result)) {
            $series[$id] = $row['slug'];
        }
        $url = "/meet/" . $series[$row['Series']] . "/" . $d = date("Y", strtotime($row['Date']));
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Titan Distance Live</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script>
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light')
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-M96PNNSZKB"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-M96PNNSZKB');
    </script>
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

<body>
    <div class="px-4 py-5 my-md-5 text-center">
        <a href="https://titandistance.com">
            <img class="d-block mx-auto mb-4" src="/assets/logos/color.svg" alt="" height="57">
        </a>
        <?php
        if (!empty($name)) {
            echo "<h1 class='display-5 fw-bold'>Today is " . $name . "</h1>";
        }


        //echo "<h1 class='display-5 fw-bold'>IHSA Glenbrook South Sectionals</h1>";

        echo "<div class='col-xs-8 col-lg-10 mx-auto'>";
        echo "<p class='lead mb-4'>" . $message . "</p>";
        echo "<div class='d-grid gap-2 d-sm-flex justify-content-sm-center'>";
        if (!empty($url)) {
            echo "<a type='button' class='btn btn-primary btn-lg px-4 me-sm-3' href='" . $url . "'>Meet Home</a>";
        }
        if (!empty($live)) {
            echo "<a type='button' class='btn btn-primary btn-lg px-4 me-sm-3' href='" . $live . "'>Live Results</a>";
        }

        /* SECTIONALS
                echo "<a class='btn btn-primary btn-lg' href='http://results.tfmeetpro.com/LA_Timing/2021_Glenbrook_South_3A_Boys_Sectional/' role='button'><i class='bi bi-bar-chart-fill me-1'></i>Live Results</a>";
                echo "<a class='btn btn-primary btn-lg' href='https://drive.google.com/file/d/1i3T1FSCfOlhJxn0gunqxoVqI8W4TnHwC/view?usp=sharing' role='button'><i class='bi bi-clock-fill me-1'></i>Time Schedule</a>";
                echo "<a class='btn btn-primary btn-lg' href='https://www.youtube.com/watch?v=ExmFcLcP8Xc' role='button'><i class='bi bi-play-btn me-1'></i>Live Stream</a>";
                    */
        ?>

        <a class="btn btn-outline-secondary btn-lg" href="https://titandistance.com/">Return to
            Titan Distance</a>
    </div>
    <!--
    <div class="card mt-3">
        <div class="card-body">
            <p class="card-text">Thanks to Coach Behof of Loyola Academy for providing timing services!</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Event</th>
                        <th>Heats</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>4:00 P.M.</td>
                        <td colspan="2">Field Events</td>
                    </tr>
                    <tr>
                        <td>6:15 P.M.</td>
                        <td>4 x 800m Relay</td>
                        <td>1 Heat</td>
                    </tr>
                    <tr>
                        <td>6:30 P.M.</td>
                        <td>4 x 100m Relay</td>
                        <td>2 Heats</td>
                    </tr>
                    <tr>
                        <td>6:45 P.M.</td>
                        <td>3200m Run</td>
                        <td>2 Heats</td>
                    </tr>
                    <tr>
                        <td>7:15 P.M.</td>
                        <td>110m Hurdles</td>
                        <td>4 Heats</td>
                    </tr>
                    <tr>
                        <td>7:30 P.M.</td>
                        <td>100m Dash</td>
                        <td>4 Heats</td>
                    </tr>
                    <tr>
                        <td>7:40 P.M.</td>
                        <td>800m Run</td>
                        <td>3 Heats</td>
                    </tr>
                    <tr>
                        <td>7:55 P.M.</td>
                        <td>4 x 200m Relay</td>
                        <td>2 Heats</td>
                    </tr>
                    <tr>
                        <td>8:10 P.M.</td>
                        <td>400m Dash</td>
                        <td>4 Heats</td>
                    </tr>
                    <tr>
                        <td>8:25 P.M.</td>
                        <td>300m Hurdles</td>
                        <td>4 Heats </td>
                    </tr>
                    <tr>
                        <td>8:40 P.M.</td>
                        <td>1600m Run</td>
                        <td>2 Heats </td>
                    </tr>
                    <tr>
                        <td>8:55 P.M.</td>
                        <td>200m Dash</td>
                        <td>4 Heats</td>
                    </tr>
                    <tr>
                        <td>9:10 P.M.</td>
                        <td>4 x 400m Relay</td>
                        <td>2 Heats</td>
                    </tr>
                    <caption>Events will not run ahead of schedule unless weather dictates.</caption>
                </tbody>
            </table>
            <pre class="text-start">
Long Jump - 4 jumps, no finals 
Triple Jump (follows Long Jump) – 4 jumps, no finals
High Jump – starting height 1.60 meters.  Move up 5cm to 1.90 (state qual.)
Pole Vault – starting height 3.11 meters.  Move up 15cm to 4.16 (state qual.)
Discus – 4 throws, no finals
Shot Put (follows Discus)- 4 throws, no finals 
            </pre>
            <div class='d-grid gap-2 d-sm-flex justify-content-sm-center'>
                <a class='btn btn-primary' href='https://titandistance.com/venues#stadium' role='button'
                    target="_blank">John
                    Davis
                    Titan Stadium Information</a>
                <a class='btn btn-primary' href='https://www.athletic.net/TrackAndField/meet/430897/' role='button'
                    target="_blank">Athletic.net</a>
            </div>
            <div class="d-flex justify-content-center">
                <a href="https://www.ihsa.org/SportsActivities/BoysTrackField.aspx" target="_blank">
                    <img height="100px" src="/assets/icons/ihsa.svg" class="me-3">
                </a>
                <a href="https://gbs.glenbrook225.org/athletics" target="_blank">
                    <img height="100px" src="/assets/icons/gbsathletics.svg" class="ms-3">
                </a>
            </div>
        </div>
        -->
    </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <!-- Cloudflare Web Analytics -->
    <script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "7e1ad18bd4604d4486a579c7d687d825"}'></script><!-- End Cloudflare Web Analytics -->
</body>

</html>