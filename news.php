<?php $pgtitle = "News"; ?>
<?php
$page = htmlspecialchars($_GET["page"]);
if (empty($page)) {
    $page = 1;
} 

$show = htmlspecialchars($_GET["show"]);
if (empty($_GET["show"])) {
    $show = "public = 1";
} else if ($_GET["show"] == "all") {
    $show = "public = 1 OR public = 0";
} else {
    $show = "public = 1 AND catergory = ".$show;
}

?>
<?php include("header.php");?>

<div class="container mt-4">
    <?php
                echo "<div class='row row-cols-1 row-cols-sm-2 row-cols-lg-3'>";
                $no_of_records_per_page = 12;
                $offset = ($page-1) * $no_of_records_per_page; 

                $total_pages_sql = "SELECT COUNT(*) FROM news WHERE ".$show;
                $result = mysqli_query($con,$total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);
if ($page == 0) {
    $result = mysqli_query($con,"SELECT * FROM news WHERE ".$show." ORDER BY date DESC");
} else {
    $result = mysqli_query($con,"SELECT * FROM news WHERE ".$show." ORDER BY date DESC LIMIT ".$offset.", ".$no_of_records_per_page);              
}
    while($row = mysqli_fetch_array($result)) {
       $content = strip_tags($row['content']);
       $date = date("F j, Y",strtotime($row['date']));
       if (!empty($row['image'])) {
       $image = "assets/images/".$row['image'];
       } else {
           $image = "askldhalskhdla";
       }
       if (isset($row['cat'])) {
       $cat = " // Categorized under ".$row['cat']; 
        } else {
       $cat = ""; 
       }
       
       echo "<div class='col mb-4'>";
       echo "<div class='card hover-card clickable-row' data-href='/news/".$row['slug']."' loading='lazy'>";
       if (file_exists($image)) {
       echo "<img src='".$image."' class='card-img-top'>";
       } else {
       echo "<img src='assets/images/blog/blank.png' class='card-img-top'>";    
       }
       echo "<div class='card-body'>";
       echo "<h3 class='card-title text-center'>".$row['title']."</h3>";
       if (!empty($content)) {
            echo "<p class='card-text text-center'>".substr($content,0, 150)."...</p>";
       } else {
            echo "<p class='card-text text-center'><u>Read More</u></p>";
       }
       echo "<p class='card-text'><small class='text-muted'>Published on ".$date.$cat."</small></p>";
       echo "</div>";
       echo "</div>";
       echo "</div>";
      }
       
       echo "</div>";
?>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php
if ($page == 1) {
    echo "<li class='page-item disabled'>";
} else {
    echo "<li class='page-item'>";
}
        ?>
            <a class="page-link" href="?page=<?php echo ($page - 1); ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
            </li>
            <?php
                    $no = 1;
                    while ($no <= $total_pages) {
                        if ($no == $page) {
                            echo "<li class='page-item active'>";
                        } else {
                            echo "<li class='page-item'>";
                        }
                        echo "<a class='page-link' href='?page=".$no."'>".$no."</a></li>";
                        $no = $no+1;
                    }
                ?>
            <?php
if ($page == $total_pages) {
    echo "<li class='page-item disabled'>";
} else {
    echo "<li class='page-item'>";
}
        ?>
            <a class="page-link" href="?page=<?php echo ($page + 1); ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
            </li>
        </ul>
    </nav>
</div>
<?php include("footer.php");?>