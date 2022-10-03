<?php include("db.php"); ?>
<?php
$template = "news";
$id = htmlspecialchars($_GET["id"]);
$slug = htmlspecialchars($_GET["slug"]);

if (!empty($id)) {
    $result = mysqli_query($con, "SELECT * FROM news WHERE id='" . $id . "'");
} else if (!empty($slug)) {
    $result = mysqli_query($con, "SELECT * FROM news WHERE slug='" . $slug . "'");
}

if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound?from=news&slug=' . $slug);
    exit;
}

while ($row = mysqli_fetch_array($result)) {
    $title = $row['title'];
    $image = $row['image'];
    $content = $row['content'];
    $hudl = $row['hudl'];
    $meet = $row['meet'];
    $public = $row['public'];
    $author = $row['author'];
    $include = $row['include'];
    if (isset($row['cat'])) {
        $cat = $row['cat'];
    } else {
        $cat = "Uncategorized";
    }

    if (empty($id)) {
        $id = $row['id'];
    }

    if (!empty($row['link'])) {
        header('Location: ' . $row['link']);
        exit;
    }

    $date = date("F j, Y", strtotime($row['date']));



    //Page Title
    $pgtitle = $row['title'];

    $require = "share";
}

$result = mysqli_query($con, "SELECT Name FROM users WHERE id='" . $author . "'");
while ($row = mysqli_fetch_array($result)) {
    $author = $row['Name'];
}

if (!empty($meet)) {
    $result = mysqli_query($con, "SELECT Name FROM meets WHERE id='" . $meet . "'");
    while ($row = mysqli_fetch_array($result)) {
        $meetname = $row['Name'];
    }
}

?>
<?php include("header.php"); ?>

<div class="container my-4">
    <div class="post-head text-center text-md-start">
        <h1 id="title"><?php echo $title; ?></h1>
        <hr>
        <nav aria-label="meta">
            <ol class="meta">
                <li class="meta-item" id="date"><i class="bi bi-calendar-fill"></i> <?php echo $date; ?></li>
                <?php
                if (!empty($author)) {
                    echo "<li class='meta-item'><i class='bi bi-person-circle'></i>" . $author . "</li>";
                }
                ?>
                <li class="meta-item"><i class="bi bi-folder-fill"></i> <?php echo $cat; ?></li>
                <?php
                if (empty($include)) {
                    echo "<li class='meta-item'><i class='bi bi-printer-fill'></i> <a href='/release?id=" . $id . "'>Print
                Release</a></li>";
                }
                if (!empty($meet)) {
                    echo "<li class='meta-item'><i class='bi bi-activity'></i><a href='/meet/" . $meet . "'>" . $meetname . "</a></li>";
                }
                ?>
            </ol>
        </nav>
        <?php
        if ($public != 1) {
            echo '<div class="alert alert-primary" role="alert">
    This news story is not marked as available to the public. This information may be outdated, or not complete.
  </div>';
        }
        ?>
        <hr>
    </div>
    <div class="article" id="articleContainer">
        <?php
        if (!empty($image) && empty($include)) {
            echo "<img class='img-responsive float-end' height='250' src='/assets/images/" . $image . "'>";
        }

        if (!empty($include)) {
            include("includes/specials/" . $include);
        } else {
            echo $content;
        }
        ?>
    </div>
    <hr>

    <div class="row text-center align-middle">
        <div class="col-md-4">
        </div>
        <div class="col-md-4 mb-xs-2 mb-lg-0">
            <?php

            if (!empty($meet)) {
                echo "<a href='/meet/" . $meet . "' class='btn btn-primary' role='button' aria-pressed='true'>Meet Homepage, Results, etc.</a>";
                header('Location: https://titandistance.com/meet/".$meet."');
            }

            ?>
        </div>
        <div class="col-md-4">
            <div class="addthis_inline_share_toolbox"></div>
        </div>
    </div>
</div>
<script>
    function printRelease() {
        var a = window.open('', '', 'height=2100, width=800');
        a.document.write('<html>');
        a.document.write('<head><link href="https://fonts.googleapis.com/css2?family=Balthazar&amp;display=swap" rel="stylesheet"><style>    body {        font-family: "Balthazar", serif;        color: #073763;    }    .header {        padding-top: 10px;        padding-bottom: 10px;        display: block;        height: 60px;    }    @media print {        html {            width: 100%;        }    }    @media screen {        .paper {            width: 800px;        }    }    .logo {        width: 50%;        float: left;        display: block;        margin-left: auto;        margin-right: auto;    }    .contact {        width: 50%;        float: right;        text-align: center;        font-size: 18px;        font-weight: bold;    }    .title {        font-size: 32px;        font-weight: bold;        text-align: center;    }    .meta {        font-size: 18px;        font-weight: bold;        text-align: center;        margin-top: -20px;    }    .release {        display: block;        font-size: 18px;    }    .end,    .btn {        text-align: center;    }    span {        font-weight: normal;    }    </style></head>');
        a.document.write('<body onafterprint="window.close()"><div class="paper"><div class="header"><a href="https://titandistance.com"><img class="logo" alt="Titan Distance" src="/assets/logos/color.svg"></a><div class="contact">Titan Distance PR Committee<br>Glenbrook South H.S.<br><span>Cross Country and Distance Track</span></div></div><div class="release">');
        a.document.write('<h1 class="title">' + document.getElementById('title').innerHTML + '</h1>')
        a.document.write('<h2 class="meta">For Immediate Release: ' + document.getElementById('date').innerHTML + '</h2>')
        a.document.write('<div class="text">' + document.getElementById("articleContainer").innerHTML + '</div>');
        a.document.write('<br><br><div class="end">###</div><br><br><div class="about"><strong>TitanDistance.com</strong> is a website made by the runners dedicated to publishing information about Glenbrook South High School\'s (Glenview, ILL.) Cross Country and Distance Track teams. All articles, posts, multimedia, and other forms of content do not necessarily represent the viewpoints of Glenbrook South High School, Northfield Township High School District 225, or the coaching staff.</div>')
        a.document.write('</div></div></body></html>');
        a.document.close();
    }
</script>
<?php include("footer.php"); ?>