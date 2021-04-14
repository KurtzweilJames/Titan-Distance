<?php $pgtitle = "Results"; ?>
<?php include("header.php");?>
<?php
$result = mysqli_query($con,"SELECT * FROM series");
    while($row = mysqli_fetch_array($result)) {
        $series[$row['id']] = $row['slug'];
    }
?>

<section id="content">
    <div class="container mt-4">
        <?php
            $result = mysqli_query($con,"SELECT * FROM meets WHERE date = '".$todaydate."'");
            while($row = mysqli_fetch_array($result)){
            if (!empty($row['Live'])){
            echo "<div class='alert alert-info' role='alert'>";
            echo "<a href='".$row['Live']."' target='_blank'>Live Results for ".$row['Name']." are available at ".$row['Live'].".</a>";
            echo "</div>";
            }
            }
                ?>
        <div class="table-responsive">
            <table class="table table-condensed table-sm table-hover datatable" id="resultsTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Opponents</th>
                        <th>Location</th>
                        <!--<th></th>
                            <th></th>-->
                        <th>Season</th>
                </thead>
                <tbody>
                    <?php
            $result = mysqli_query($con,"SELECT * FROM meets WHERE Date<'".$todaydate."' OR (Date <= '".$todaydate."' AND (Official = 1 OR Official = 2)) ORDER BY Date DESC");                  
            while($row = mysqli_fetch_array($result)) {
            if (empty($row['Series'])) {
                $url = "/meet/".$row['id'];
            } else {
                $url = "/meet/".$series[$row['Series']]."/".$d = date("Y",strtotime($row['Date']));
            }
                  
            //Badge
if (!empty($row['Badge'])) {
    if ($row['Badge'] == 1) {
        $badge = " <span class='badge bg-csl'>CSL</span>";
    } else if ($row['Badge'] == 2) {
        $badge = " <span class='badge bg-ihsa'>IHSA</span>";
    } else if ($row['Badge'] == 3) {
        $badge = " <span class='badge bg-info'>TT</span>";
    }
} else {
    $badge = "";
}
            
                  $d = date("n/j/Y",strtotime($row['Date']));
echo "<tr class='clickable-row' data-href='".$url."'>";
echo "<td>" . $d . "</td>";
echo "<td><a href='".$url."'>" . $row['Name'] . $badge. "</a></td>";
if(strlen($row['Opponents']) > 50) {
    echo "<td data-toggle='tooltip' data-placement='top' title='".$row['Opponents']."'>" . substr($row['Opponents'],0, 50)."..." . "</td>";
} else {
    echo "<td>" . $row['Opponents'] . "</td>";
}
echo "<td>" . $row['Location'] . "</td>";
echo "<td>" . $row['Season'] . "</td>";
echo "</tr>";  
              }  
                            
                            ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="container">
        <p>*While we do our best to input accurate results, inconsistencies may unfortunately arise. <a
                href="https://forms.gle/WBJbebPNkvjz3XQB9" target="_blank">If you believe there's a mistake, please fill
                out this form.</a></p>
        <a class="btn btn-info" href="https://forms.gle/WBJbebPNkvjz3XQB9" role="button" target="_blank">Request
            Correction</a>
        <a
            href="https://docs.google.com/document/d/e/2PACX-1vTYaYKeX2zvWhx0BhUB6r2cV1serdWvaovwq81u51l05Sz55IunMbwQDkCFwiLzQl0uwZUdb5kwY4LP/pub">
            <p>Wondering where we find these results? Check here for our sources.</p>
        </a>
    </div>
</section>

<?php $require = "resultstablefalse"; ?>
<?php include("footer.php");?>