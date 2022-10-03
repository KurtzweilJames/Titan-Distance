<?php $pgtitle = "Photos";
include("header.php"); ?>

<?php
$result = mysqli_query($con, "SELECT id,Name FROM meets WHERE Date <= '" . $todaydate . "'");
while ($row = mysqli_fetch_array($result)) {
    $meets[$row['id']] = $row['Name'];
}

?>
<div class="container">
    <?php
    foreach ($seasons as $s) {
        $result = mysqli_query($con, "SELECT * FROM photos WHERE season = '" . $s . "' ORDER BY date DESC");
        if (mysqli_num_rows($result) > 0) {
            echo "<h2 id='" . $s . "'>" . $s . "</h2>";
            echo "<hr>";
            echo "<div class='row row-cols-2 row-cols-md-3 mb-5'>";
            while ($row = mysqli_fetch_array($result)) {
                if (empty($row['title'])) {
                    $title = $meets[$row['meet']];
                } else {
                    $title = $row['title'];
                }
                echo "<div class='col mb-4 p-1 p-md-2'>";
                echo "<a class='card hover-card text-reset' href='" . $row['link'] . "' target='_blank'>";
                echo "<div class='badge bg-award-inv position-absolute' style='top: 0.5rem; right: 0.5rem'>" . date("F j, Y", strtotime($row['date'])) . "</div>";
                if (!empty($row['cover'])) {
                    echo "<img src='/assets/images/meets/" . $row['cover'] . "' class='card-img-top' loading='lazy' alt='" . $title . " Album Cover'>";
                } else {
                    echo "<img src='assets/images/blog/blank.png' class='card-img-top' loading='lazy' alt='" . $title . " Album Cover'>";
                }
                echo "<div class='card-body'>";
                echo "<h3 class='card-title text-center'>" . $title . "</h3>";
                echo "<p class='card-text text-center'>Photographer(s): " . $row['credits'] . "</p>";
                echo "</div>";
                echo "</div>";
                echo "</a>";
            }
            echo "</div>";
        }
    }
    ?>

</div>
<?php include("footer.php"); ?>