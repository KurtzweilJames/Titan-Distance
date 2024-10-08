<?php
include("db.php");

$require = "news";

if (!empty($_GET["id"])) {
    $result = mysqli_query($con, "SELECT * FROM news WHERE id='" . $_GET["id"] . "'");
} else if (!empty($_GET["slug"])) {
    $result = mysqli_query($con, "SELECT * FROM news WHERE slug='" . $_GET["slug"] . "'");
} else {
    header('Location: https://titandistance.com/notfound?from=news');
    exit;
}

if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound?from=news&slug=' . $_GET["slug"]);
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

    if (empty($slug)) {
        $slug = $row['slug'];
    }

    if (!empty($row['link'])) {
        header('Location: ' . $row['link']);
        exit;
    }

    $date = date("F j, Y", strtotime($row['date']));



    //Page Title
    $pgtitle = $row['title'];
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

include("header.php"); ?>

<div class="container my-4">
    <div class="post-head text-center text-md-start">
        <h1 id="title"><?php echo $title; ?></h1>
        <div class="border-top border-bottom py-2 mb-2 d-flex justify-content-between">
            <?php
            echo '<div>Posted on ' . $date;
            if (!empty($author)) {
                echo " by " . $author;
            }
            echo "</div>";
            ?>
            <a id="shareButton" class="bi bi-box-arrow-in-up-right" onclick="share('<?php echo $title; ?>')"></a>
            <?php
            if ($public != 1) {
                echo '</div><div class="alert alert-primary" role="alert">
    This news story is not marked as available to the public. This information may be outdated, or not complete.';
            }
            ?>
        </div>
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
                echo "<a href='/meet/" . $meet . "' class='btn btn-primary me-2' role='button'>Meet Homepage, Results, & Photos</a>";
            }
            echo "<a onclick='printRelease()' class='btn btn-primary' role='button'>Print News Release</a>";
            ?>
        </div>
        <div class="col-md-4">
            <div class="addthis_inline_share_toolbox"></div>
        </div>
    </div>
</div>
<script>
    function printRelease() {
        var date = "<?php echo $date; ?>";
        var a = window.open('', '', 'height=2100, width=800');
        a.document.write('<html>');
        a.document.write('<head><link href="https://fonts.googleapis.com/css2?family=Balthazar&amp;display=swap" rel="stylesheet"><style>    body {        font-family: "Balthazar", serif;        color: #073763;    }    .header {        padding-top: 10px;        padding-bottom: 10px;        display: block;        height: 60px;    }    @media print {        html {            width: 100%;        }    }    @media screen {        .paper {            width: 800px;        }    }    .logo {        width: 50%;        float: left;        display: block;        margin-left: auto;        margin-right: auto;    }    .contact {        width: 50%;        float: right;        text-align: center;        font-size: 18px;        font-weight: bold;    }    .title {        font-size: 32px;        font-weight: bold;        text-align: center;    }    .meta {        font-size: 18px;        font-weight: bold;        text-align: center;        margin-top: -20px;    }    .release {        display: block;        font-size: 18px;    }    .end,    .btn {        text-align: center;    }    span {        font-weight: normal;    }    </style><title>' + document.title.replace(" - Titan Distance", "") + '</title></head>');
        a.document.write('<body onafterprint="window.close()" onload="window.print()"><div class="paper"><div class="header"><a href="https://titandistance.com"><img class="logo" alt="Titan Distance" src="/assets/logos/color.svg"></a><div class="contact">Titan Distance PR Committee<br>Glenbrook South H.S.<br><span>Cross Country and Distance Track</span></div></div><div class="release">');
        a.document.write('<h1 class="title">' + document.getElementById('title').innerHTML + '</h1>')
        a.document.write('<h2 class="meta">For Immediate Release: ' + date + '</h2>')
        a.document.write('<div class="text">' + document.getElementById("articleContainer").innerHTML.replace("img", "none") + '</div>');
        a.document.write('<br><br><div class="end">###</div><br><br><div class="about" onload="window.print()"><strong>TitanDistance.com</strong> is a website operated and maintained by the athletes dedicated to publishing information about Glenbrook South High School\'s (Glenview, IL.) Cross Country and Distance Track teams. All articles, posts, multimedia, and other forms of content do not necessarily represent the viewpoints of Glenbrook South High School, Northfield Township High School District 225, or the coaching staff.</div>')
        a.document.write('</div></div></body></html>');
        a.document.close();
    }
</script>
<?php include("footer.php"); ?>