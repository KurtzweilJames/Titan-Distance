<?php
include("db.php");
$todaydate = date('Y-m-d');

$result = mysqli_query($con, "SELECT * FROM meets WHERE Date = '$todaydate'");
if (mysqli_num_rows($result) == 0) {
    $message = "There is no meet scheduled for today.";
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
            <img class="d-block mx-auto mb-4" src="/assets/logos/color.svg" id="logo" alt="Titan Distance" height="64">
        </a>
        <?php
        if (!empty($name)) {
            echo "<h1 class='display-5 fw-bold'>Today is " . $name . "</h1>";
        }

        echo "<div class='col-xs-8 col-lg-10 mx-auto'>";
        echo "<p class='lead mb-4'>" . $message . "</p>";
        echo "<div class='d-grid gap-2 d-sm-flex justify-content-sm-center'>";
        if (!empty($url)) {
            echo "<a type='button' class='btn btn-primary btn-lg px-4 me-sm-3' href='" . $url . "'>Meet Home</a>";
        }
        if (!empty($live)) {
            echo "<a type='button' class='btn btn-primary btn-lg px-4 me-sm-3' href='" . $live . "'>Live Results</a>";
        }
        ?>

        <a class="btn btn-outline-secondary btn-lg" href="https://titandistance.com/">Return to Titan Distance</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script>
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.getElementById("logo").src = "/assets/logos/white.svg"
        } else {
            document.getElementById("logo").src = "/assets/logos/color.svg"
        }
    </script>
    <!-- Cloudflare Web Analytics -->
    <script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "7e1ad18bd4604d4486a579c7d687d825"}'></script><!-- End Cloudflare Web Analytics -->
</body>

</html>