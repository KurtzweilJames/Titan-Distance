<?php 
include("db.php");

$id = htmlspecialchars($_GET["id"]);
$result = mysqli_query($con,"SELECT * FROM news WHERE id='". $id ."'");

while($row = mysqli_fetch_array($result)) {
    $title = $row['title'];
    $image = $row['image'];
    $content = $row['content'];
    $link = $row['link'];
    if (isset($row['cat'])) {
       $cat = $row['cat']; 
    } else {
       $cat = "Uncategorized"; 
    }
    $date = date("F j, Y",strtotime($row['date']));
}

?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <title><?php echo $title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Balthazar&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Balthazar', serif;
        color: #073763;
    }

    .header {
        padding-top: 10px;
        padding-bottom: 10px;
        display: block;
        height: 60px;
    }

    @media print {
        html {
            width: 100%;
        }
    }

    @media screen {
        .paper {
            width: 800px;
        }
    }

    .logo {
        width: 50%;
        float: left;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .contact {
        width: 50%;
        float: right;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }

    h1 {
        font-size: 32px;
        font-weight: bold;
        text-align: center;
    }

    h2 {
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        margin-top: -20px;
    }

    .release {
        display: block;
        font-size: 18px;
    }

    .end,
    .btn {
        text-align: center;
    }

    span {
        font-weight: normal;
    }
    </style>
</head>

<body onload="window.print()">
    <div class="paper">
        <div class="header">
            <a href="https://titandistance.com"><img class="logo" alt="Titan Distance"
                    src="/assets/logos/color.svg"></a>
            <div class="contact">
                Titan Distance PR Committee<br>
                Glenbrook South H.S.<br>
                <span>Cross Country and Distance Track</span>
            </div>
        </div>
        <div class="release">
            <h1><?php echo $title; ?></h1>
            <h2>For Immediate Release: <?php echo $date; ?></h2>
            <div class="text">
                <?php echo $content; ?>
                <br><br>
                <div class="end">&#35;&#35;</div>
            </div>
        </div>
    </div>
</body>

</html>