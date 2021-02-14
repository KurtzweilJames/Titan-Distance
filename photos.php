<?php $pgtitle = "Photos"; ?>
<?php include("header.php");?>

<?php
$result = mysqli_query($con,"SELECT id,Name FROM meets WHERE Date < '".$todaydate."'");
while($row = mysqli_fetch_array($result)) {
$meets[$row['id']]=$row['Name'];
}
?>
<section id="content">
    <div class="container mt-4">
        <?php
        foreach ($seasons as $s) {
    echo "<h2 id='".$s."'>".$s."</h2>";
    echo "<hr>";
    $result = mysqli_query($con,"SELECT * FROM photos WHERE date < '".$todaydate."' AND season = '".$s."' ORDER BY date DESC"); 
    echo "<div class='row row-cols-2 row-cols-md-3 mb-5'>";
   while($row = mysqli_fetch_array($result)) {
       echo "<div class='col mb-4'>";
       echo "<div class='card clickable hover-card' data-href='".$row['link']."'>";
       echo "<img src='/assets/images/meets/".$row['cover']."' class='card-img-top'>";
       echo "<div class='card-body'>";
       echo "<h3 class='card-title text-center'>".$meets[$row['meet']]."</h3>";
       echo "<p class='card-text text-center'>Photographer(s): ".$row['credits']."</p>";
       echo "</div>";
       echo "</div>";
       echo "</div>";
   }
   echo "</div>";
}
?>

    </div>
</section><!-- #content end -->
<?php include("footer.php");?>