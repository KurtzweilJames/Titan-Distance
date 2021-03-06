<?php include("db.php"); ?>
<?php 
$template = "news";
$id = htmlspecialchars($_GET["id"]);
$slug = htmlspecialchars($_GET["slug"]);

if (!empty($id)) {
$result = mysqli_query($con,"SELECT * FROM news WHERE id='". $id ."'");
} else if (!empty($slug)) {
$result = mysqli_query($con,"SELECT * FROM news WHERE slug='". $slug ."'");    
}

if (mysqli_num_rows($result) == 0) {
    header('Location: https://titandistance.com/notfound');
    exit;
}

while($row = mysqli_fetch_array($result)) {
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
    
    if(empty($id)) {
        $id = $row['id'];
    }

    $date = date("F j, Y",strtotime($row['date']));



    //Page Title
$pgtitle = $row['title'];

$require = "share";
}

$result = mysqli_query($con,"SELECT Name FROM users WHERE id='". $author ."'"); 
while($row = mysqli_fetch_array($result)) {
$author = $row['Name'];
}

if(!empty($meet)) {
    $result = mysqli_query($con,"SELECT Name FROM meets WHERE id='". $meet ."'"); 
    while($row = mysqli_fetch_array($result)) {
    $meetname = $row['Name'];
    }    
}

?>
<?php include("header.php");?>

<div class="container my-4">
    <div class="post-head text-center text-md-start">
        <h1><?php echo $title;?></h1>
        <hr>
        <nav aria-label="meta">
            <ol class="meta">
                <li class="meta-item"><i class="bi bi-calendar-fill"></i> <?php echo $date;?></li>
                <?php
            if (!empty($author)) {
                echo "<li class='meta-item'><i class='bi bi-person-circle'></i>".$author."</li>";
            }
            ?>
                <li class="meta-item"><i class="bi bi-folder-fill"></i> <?php echo $cat;?></li>
                <li class="meta-item"><i class="bi bi-printer-fill"></i> <a href="/release?id=<?php echo $id; ?>">Print
                        Release</a></li>
                <?php
            if (!empty($meet)) {
                echo "<li class='meta-item'><i class='bi bi-award-fill'></i><a href='/meet/".$meet."'>".$meetname."</a></li>";
            }
            ?>
            </ol>
        </nav>
        <hr>
    </div>
    <div class="article">
        <?php
        if (!empty($image) && empty($include)) {
            echo "<img class='img-responsive float-end' height='250' src='/assets/images/".$image."'>";
        } 
        
        echo $content;

        if (!empty($include)) {
            include("includes/specials/".$include);
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
    echo "<a href='/meet/".$meet."' class='btn btn-primary' role='button' aria-pressed='true'>Meet Homepage, Results, etc.</a>";
    header('Location: https://titandistance.com/meet/".$meet."');
}

?>
        </div>
        <div class="col-md-4">
            <div class="addthis_inline_share_toolbox"></div>
        </div>
    </div>
</div>

<?php include("footer.php");?>