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
        <h1><?php echo $title; ?></h1>
        <hr>
        <nav aria-label="meta">
            <ol class="meta">
                <li class="meta-item"><i class="bi bi-calendar-fill"></i> <?php echo $date; ?></li>
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
        var divContents = document.getElementById("articleContainer").innerHTML;
        var a = window.open('', '', 'height=2100, width=800');
        a.document.write('<html>');
        a.document.write('<head><title></title><style>.badge {display:none;} a {text-decoration: none; color: inherit;} button {display:none;} table {width:100%;text-align: center;} h4 {text-align: center; font-size: 18px;}</style></head>');
        a.document.write('<body onafterprint="window.close()"><img src="https://titandistance.com/assets/logos/color.svg" onload="window.print()" style="display: block;margin-left: auto;margin-right: auto;width: 40%;" alt="Titan Distance"><pre>');
        a.document.write(divContents);
        a.document.write('</pre></body></html>');
        a.document.close();
    }
</script>
<?php include("footer.php"); ?>