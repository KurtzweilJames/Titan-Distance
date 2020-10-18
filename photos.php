<?php $pgtitle = "Photos"; ?>
<?php include("header.php");?>

<section id="content">
    <div class="container mt-4">
        <?php
        foreach ($seasons as $s) {
   $result = mysqli_query($con,"SELECT Name,Season,Photos,PhotosCredits,PhotosAlbums FROM meets WHERE Photos IS NOT NULL AND Date < '".$todaydate."' AND Season = '".$s."' AND Photos <> '' ORDER BY Date DESC");                  
   if (mysqli_num_rows($result) > 0 ) {
    echo "<h2 id='".$s."'>".$s."</h2>";
    echo "<hr>";
    echo "<div class='row row-cols-2 row-cols-md-3 mb-5'>";
   while($row = mysqli_fetch_array($result)) {
        $PhotosAlbums = explode(";", $row['PhotosAlbums']);
        $Photos = explode(";", $row['Photos']);
        $PhotosCredits = explode(";", $row['PhotosCredits']);
       
      foreach($Photos as $index => $link) {
       echo "<div class='col mb-4'>";
       echo "<div class='card clickable hover-card' data-href='".$link."'>";
       echo "<img src='/assets/images/meets/".$PhotosAlbums[$index]."' class='card-img-top'>";
       echo "<div class='card-body'>";
       echo "<h3 class='card-title text-center'>".$row['Name']."</h3>";
       echo "<p class='card-text text-center'>".$PhotosCredits[$index]."</p>";
       echo "</div>";
       echo "</div>";
       echo "</div>";
      }
   }
   echo "</div>";
}
}
?>

    </div>
</section><!-- #content end -->
<?php include("footer.php");?>