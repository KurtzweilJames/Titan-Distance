<?php
$pgtitle = "Class Records";
include($_SERVER['DOCUMENT_ROOT'] . "/header.php");

$result = mysqli_query($con, "SELECT id, Date, Series FROM meets");
$meets = [];

while ($row = mysqli_fetch_array($result)) {
    if (empty($row['Series'])) {
        $meets[$row['id']] = $row['id'];
    } else {
        $meets[$row['id']] = $row['Series'] . "/" . date("Y", strtotime($row['Date']));
    }
}

function generateRow($con, $event, $description, $grade, $indoor, $limit = 1)
{
    global $meets;
    global $currentyear;
    $indoor_int = $indoor ? 1 : 0;
    // echo "SELECT name,result,profile,meet,date,school FROM overalltf WHERE event='$event' AND school='Glenbrook South' AND grade = $grade AND relay IS NULL AND indoor = $indoor_int ORDER BY result LIMIT $limit";
    if ($grade == null) {
        $result = mysqli_query($con, "SELECT name,result,profile,meet,date,school FROM overalltf WHERE event='$event' AND (method != 'h' OR method IS NULL) AND school='Glenbrook South' AND relay IS NULL AND indoor = $indoor_int ORDER BY result LIMIT $limit");
    } else {
        $result = mysqli_query($con, "SELECT name,result,profile,meet,date,school FROM overalltf WHERE event='$event' AND (method != 'h' OR method IS NULL) AND school='Glenbrook South' AND grade = $grade AND relay IS NULL AND indoor = $indoor_int ORDER BY result LIMIT $limit");
    }
        while ($row = mysqli_fetch_array($result)) {
        if (date('Y', strtotime($row['date'])) == $currentyear) {
            echo "<tr class='row-highlight'>";
        } else {
            echo "<tr>";
        }
        echo "<th>$description</th>";
        if (!empty($row['profile'])) {
            echo "<td><a href='/athlete/" . $row['profile'] . "'>" . $row['name'] . "</a></td>";
        } else {
            echo "<td>" . $row['name'] . "</td>";
        }
        echo "<td><a href='/meet/" . $meets[$row['meet']] . "#results'>" . formatTime($row['result']) . "</a></td>";
        echo "<td><a href='/meet/" . $meets[$row['meet']] . "'>" . date('Y', strtotime($row['date'])) . "</a></td>";
        echo "</tr>";
    }
}
?>

<div class="container">
    <h2>Class Records</h2>
    <hr>
    <div class="mx-0 mx-md-4 p-0 p-md-2">
        <ul class="nav nav-tabs mb-3" id="classTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="varsity-outdoor-tab" data-bs-toggle="tab" data-bs-target="#varsity-outdoor-tab-pane" type="button" role="tab" aria-controls="varsity-outdoor-tab-pane" aria-selected="true">Varsity Outdoor</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="varsity-indoor-tab" data-bs-toggle="tab" data-bs-target="#varsity-indoor-tab-pane" type="button" role="tab" aria-controls="varsity-indoor-tab-pane" aria-selected="false">Varsity Indoor</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sophomore-outdoor-tab" data-bs-toggle="tab" data-bs-target="#sophomore-outdoor-tab-pane" type="button" role="tab" aria-controls="sophomore-outdoor-tab-pane" aria-selected="false">Sophomore Outdoor</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sophomore-indoor-tab" data-bs-toggle="tab" data-bs-target="#sophomore-indoor-tab-pane" type="button" role="tab" aria-controls="sophomore-indoor-tab-pane" aria-selected="false">Sophomore Indoor</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="freshmen-outdoor-tab" data-bs-toggle="tab" data-bs-target="#freshmen-outdoor-tab-pane" type="button" role="tab" aria-controls="freshmen-outdoor-tab-pane" aria-selected="false">Freshmen Outdoor</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="freshmen-indoor-tab" data-bs-toggle="tab" data-bs-target="#freshmen-indoor-tab-pane" type="button" role="tab" aria-controls="freshmen-indoor-tab-pane" aria-selected="false">Freshmen Indoor</button>
            </li>
        </ul>
        <div class="tab-content" id="classTabContent">
            <div class="tab-pane fade show active" id="varsity-outdoor-tab-pane" role="tabpanel" aria-labelledby="varsity-outdoor-tab" tabindex="0">
                <h3>Varsity Outdoor Records</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Performance</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        generateRow($con, "100m", "100m Dash", null, false);
                        generateRow($con, "200m", "200m Dash", null, false);
                        generateRow($con, "400m", "400m Dash", null, false);
                        generateRow($con, "800m", "800m Run", null, false);
                        generateRow($con, "1600m", "1600m Run", null, false);
                        generateRow($con, "1mi", "1 Mile Run", null, false);
                        generateRow($con, "3200m", "3200m Run", null, false);
                        generateRow($con, "110mHH", "110m High Hurdles", null, false);
                        generateRow($con, "300mIH", "300m Intermediate Hurdles", null, false);
                        ?>
                        <tr>
                            <td>4 X 100 Relay</td>
                            <td>Ben Freidinger, Thomas Zambianchi, Nathan Shapiro, Noah Shapiro</td>
                            <td>42.48</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>4 X 200 Relay</td>
                            <td>MELCHER KV. CIEZADLO, KTH. CIEZADLO JORGENSON</td>
                            <td>1:28.0</td>
                            <td>1988</td>
                        </tr>
                        <tr>
                            <td>4 X 400 Relay</td>
                            <td>DICKHOLTZ, ENGEL, PEKOSH, PILLIOD</td>
                            <td>3:20.13</td>
                            <td>2012</td>
                        </tr>
                        <tr>
                            <td>4 X 800 Relay</td>
                            <td>T COWHEY, REIGHARD, STANEK, AVILA</td>
                            <td>8:01.59</td>
                            <td>2010</td>
                        </tr>
                        <tr>
                            <td>High Jump</td>
                            <td>Ryan Schaefer</td>
                            <td>6-7.25</td>
                            <td>2023</td>
                        </tr>
                        <tr>
                            <td>Pole Vault</td>
                            <td>Will Schaeffer</td>
                            <td>15-4</td>
                            <td>2007</td>
                        </tr>
                        <tr>
                            <td>Long Jump</td>
                            <td>Nathan Shapiro</td>
                            <td>24-04.25</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>Triple Jump</td>
                            <td>Barry Flint</td>
                            <td>44-7</td>
                            <td>1985</td>
                        </tr>
                        <tr>
                            <td>Shot Put</td>
                            <td>Dimitri Manesiotis</td>
                            <td>57-4</td>
                            <td>2019</td>
                        </tr>
                        <tr>
                            <td>Discus</td>
                            <td>Ryan Faut</td>
                            <td>178-7</td>
                            <td>2021</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="varsity-indoor-tab-pane" role="tabpanel" aria-labelledby="varsity-indoor-tab" tabindex="0">
                <h3>Varsity Indoor Records</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Performance</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        generateRow($con, "50m", "50m Dash", null, true);
                        generateRow($con, "50y", "50y Dash", null, true);
                        generateRow($con, "55m", "55m Dash", null, true);
                        generateRow($con, "60m", "60m Dash", null, true);
                        generateRow($con, "200m", "200m Dash", null, true);
                        ?>
                        <tr>
                            <td rowspan="2">300 Meter Dash</td>
                            <td>Malcolm Engel</td>
                            <td>36.1</td>
                            <td>2013</td>
                        </tr>
                        <tr>
                            <td>Ryan Schaefer</td>
                            <td>36.1</td>
                            <td>2023</td>
                        </tr>
                        <tr>
                            <td>400 Meter Dash</td>
                            <td>John Strickland</td>
                            <td>50.5</td>
                            <td>1990</td>
                        </tr>
                        <tr>
                            <td>800 Meter Run</td>
                            <td>Brian Hiltebrand</td>
                            <td>1:57.94</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>1000 Meter Run</td>
                            <td>Brian Hiltebrand</td>
                            <td>2:37.7</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>1600 Meter Run</td>
                            <td>Zak Avila</td>
                            <td>4:27.3</td>
                            <td>2010</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Run</td>
                            <td>David O&#39;Gara</td>
                            <td>9:35.31</td>
                            <td>2014</td>
                        </tr>
                        <tr>
                            <td>55 INT. HURDLES</td>
                            <td>Kendall Strickland</td>
                            <td>7.5</td>
                            <td>1992</td>
                        </tr>
                        <tr>
                            <td>55 HIGH HURDLES</td>
                            <td>John Strickland</td>
                            <td>7.5</td>
                            <td>1990</td>
                        </tr>
                        <tr class="row-highlight">
                            <td rowspan="2">55 LOW HURDLES</td>
                            <td>Patrick Kuprewicz</td>
                            <td>7.3</td>
                            <td>2024</td>
                        </tr>
                        <tr>
                            <td>Nathan Shapiro</td>
                            <td>7.3</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>4 X 160 Relay</td>
                            <td>FREIDINGER, SCHAEFER, SHAPIRO, SHAPIRO</td>
                            <td>1:13.4</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>4 X 200 Relay</td>
                            <td>FREIDINGER, SCHAEFER, SHAPIRO, SHAPIRO</td>
                            <td>1:34.19</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>4 X 400 Relay</td>
                            <td>SCHAEFER, JERVA, NO. SHAPIRO, B HILTEBRAND</td>
                            <td>3:34.26</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>4 X 800 Relay</td>
                            <td>JERVA, HOUSER, HILTEBRAND, HILTEBRAND</td>
                            <td>8:14.1</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>SPRINT MEDLEY</td>
                            <td>HARRIS, PILLIOD, ENGEL, DICKHOLTZ</td>
                            <td>1:58.1</td>
                            <td>2013</td>
                        </tr>
                        <tr>
                            <td>HIGH JUMP</td>
                            <td>Terry Webb</td>
                            <td>6-63/4</td>
                            <td>1965</td>
                        </tr>
                        <tr>
                            <td>POLE VAULT</td>
                            <td>Will Schaefer</td>
                            <td>14-7</td>
                            <td>2007</td>
                        </tr>
                        <tr>
                            <td>LONG JUMP</td>
                            <td>Nathan Shapiro</td>
                            <td>23-06.75</td>
                            <td>2022</td>
                        </tr>
                        <tr>
                            <td>TRIPLE JUMP</td>
                            <td>John Strickland</td>
                            <td>46-21/2</td>
                            <td>1990</td>
                        </tr>
                        <tr>
                            <td>SHOT PUT</td>
                            <td>Dimitri Manesiotis</td>
                            <td>53-10</td>
                            <td>1988</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="sophomore-outdoor-tab-pane" role="tabpanel" aria-labelledby="sophomore-outdoor-tab" tabindex="0">
                <h3>Sophomore Outdoor Records</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Performance</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>100 Meter Dash</td>
                            <td>Neil Melcher</td>
                            <td>10.6</td>
                            <td>1989</td>
                        </tr>
                        <tr>
                            <td>200 Meter Dash</td>
                            <td>Neil Melcher</td>
                            <td>21.8</td>
                            <td>1989</td>
                        </tr>
                        <tr>
                            <td>400 Meter Dash</td>
                            <td>Neil Melcher</td>
                            <td>48.6</td>
                            <td>1989</td>
                        </tr>
                        <tr>
                            <td>800 Meter Run</td>
                            <td>Mike Falk, Tim Faith</td>
                            <td>2:03.1</td>
                            <td>1988, 2005</td>
                        </tr>
                        <tr class="row-highlight">
                            <td>1600 Meter Run</td>
                            <td>Ryan Taylor</td>
                            <td>4:33.00</td>
                            <td>2024</td>
                        </tr>
                        <tr class="row-highlight">
                            <td>3200 Meter Run</td>
                            <td>Ryan Taylor</td>
                            <td>9:48.30</td>
                            <td>2024</td>
                        </tr>
                        <tr>
                            <td>110 Meter High Hurdles</td>
                            <td>Gus Shipp</td>
                            <td>15.2</td>
                            <td>2010</td>
                        </tr>
                        <tr>
                            <td>300 Meter Int. Hurdles</td>
                            <td>Luke Pilliod</td>
                            <td>39.38</td>
                            <td>2012</td>
                        </tr>
                        <tr>
                            <td>Long Jump</td>
                            <td>Ose Ilenikhena</td>
                            <td>20-111/2</td>
                            <td>2011</td>
                        </tr>
                        <tr>
                            <td>Triple Jump</td>
                            <td>Dontay Marshall</td>
                            <td>42-111/2</td>
                            <td>2004</td>
                        </tr>
                        <tr>
                            <td>High Jump</td>
                            <td>George Heckenbach</td>
                            <td>6-6</td>
                            <td>1991</td>
                        </tr>
                        <tr>
                            <td>Pole Vault</td>
                            <td>Mike Fontana, Will Schaeffer</td>
                            <td>12-6</td>
                            <td>1987, 2005</td>
                        </tr>
                        <tr>
                            <td>Shot Put</td>
                            <td>Sean Campbell</td>
                            <td>52-6</td>
                            <td>1987</td>
                        </tr>
                        <tr>
                            <td>Discus</td>
                            <td>Jim Blondell</td>
                            <td>155-9</td>
                            <td>1981</td>
                        </tr>
                        <tr>
                            <td>400 Meter Relay</td>
                            <td>Whebe, Cotton, Clough, Willits</td>
                            <td>44.45</td>
                            <td>2023</td>
                        </tr>
                        <tr>
                            <td>800 Meter Relay</td>
                            <td>Clough, Cotton, Wehbe, Mathew</td>
                            <td>1:34.9</td>
                            <td>2023</td>
                        </tr>
                        <tr>
                            <td>1600 Meter Relay</td>
                            <td>Alexander, Harris, J Cowhey, Pilliod</td>
                            <td>3:34.5</td>
                            <td>2012</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Relay</td>
                            <td>T Cowhey, Vear, Regalbuto, Avila</td>
                            <td>8:33.9</td>
                            <td>2008</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="sophomore-indoor-tab-pane" role="tabpanel" aria-labelledby="sophomore-indoor-tab" tabindex="0">
                <h3>Sophomore Indoor Records</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Performance</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>50 Meter Dash</td>
                            <td>Colin Hepburn</td>
                            <td>5.9</td>
                            <td>2008</td>
                        </tr>
                        <tr>
                            <td>200 Meter Dash</td>
                            <td>Colin Hepburn</td>
                            <td>23.6</td>
                            <td>2008</td>
                        </tr>
                        <tr>
                            <td>400 Meter Dash</td>
                            <td>Neil Melcher</td>
                            <td>51.4</td>
                            <td>1989</td>
                        </tr>
                        <tr>
                            <td>800 Meter Run</td>
                            <td>Marc Tovar</td>
                            <td>2:06.4</td>
                            <td>1996</td>
                        </tr>
                        <tr class="row-highlight">
                            <td>1600 Meter Run</td>
                            <td>Ryan Taylor</td>
                            <td>4:35.58</td>
                            <td>2024</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Run</td>
                            <td>David O&#39;Gara</td>
                            <td>9:59.3</td>
                            <td>2012</td>
                        </tr>
                        <tr>
                            <td>55 Meter Int Hurdles</td>
                            <td>Peter Wassmann</td>
                            <td>7.8</td>
                            <td>2013</td>
                        </tr>
                        <tr>
                            <td>55 Meter High Hurdles</td>
                            <td>Peter Wassmann</td>
                            <td>7.8</td>
                            <td>2013</td>
                        </tr>
                        <tr>
                            <td>55 Meter Low Hurdles</td>
                            <td>Max Gerber</td>
                            <td>7.8</td>
                            <td>2016</td>
                        </tr>
                        <tr>
                            <td>300 Meter Dash</td>
                            <td>Colin Hepburn</td>
                            <td>38.1</td>
                            <td>2008</td>
                        </tr>
                        <tr class="row-highlight">
                            <td>1000 Meter Run</td>
                            <td>Ryan Taylor</td>
                            <td>2:48.8</td>
                            <td>2024</td>
                        </tr>
                        <tr>
                            <td>Long Jump</td>
                            <td>Adam Smiley</td>
                            <td>20-93/4</td>
                            <td>1993</td>
                        </tr>
                        <tr>
                            <td>Triple Jump</td>
                            <td>Moses Joseph</td>
                            <td>41-11/4</td>
                            <td>1996</td>
                        </tr>
                        <tr>
                            <td>Shot Put</td>
                            <td>Jim Blondell</td>
                            <td>47-11</td>
                            <td>1981</td>
                        </tr>
                        <tr>
                            <td>High Jump</td>
                            <td>Ose Ilenikhena</td>
                            <td>6-4</td>
                            <td>2011</td>
                        </tr>
                        <tr>
                            <td>Pole Vault</td>
                            <td>Rick Schaffel</td>
                            <td>12-6</td>
                            <td>1987</td>
                        </tr>
                        <tr>
                            <td>4 X 160 Relay</td>
                            <td>Isaac, Clough, Kessler, Willits</td>
                            <td>1:15.6</td>
                            <td>2023</td>
                        </tr>
                        <tr>
                            <td>1600 Meter Relay</td>
                            <td>Duran, Alexander, Harris, Pilliod</td>
                            <td>3:42.8</td>
                            <td>2012</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Relay</td>
                            <td>Pauletto, Houser, Lopez, Schultz</td>
                            <td>8:51.5</td>
                            <td>2017</td>
                        </tr>
                        <tr>
                            <td>Throwers Relay</td>
                            <td>Oppegard, Golden, Frazier, Gulbin</td>
                            <td>1:34.4</td>
                            <td>1998</td>
                        </tr>
                        <tr>
                            <td>Sprint Medley Relay</td>
                            <td>Cabrera, Chun, Baker, Howard</td>
                            <td>2:09.1</td>
                            <td>2005</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="freshmen-outdoor-tab-pane" role="tabpanel" aria-labelledby="freshmen-outdoor-tab" tabindex="0">
                <h3>Freshmen Outdoor Records</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Performance</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>100 Meter Dash</td>
                            <td>Adam Smiley</td>
                            <td>11.2</td>
                            <td>1992</td>
                        </tr>
                        <tr>
                            <td>200 Meter Dash</td>
                            <td>Neil Melcher</td>
                            <td>22.9</td>
                            <td>1988</td>
                        </tr>
                        <tr>
                            <td>400 Meter Dash</td>
                            <td>Neil Melcher</td>
                            <td>50.9</td>
                            <td>1988</td>
                        </tr>
                        <tr>
                            <td>800 Meter Run</td>
                            <td>Johnny Cowhey</td>
                            <td>2:06.5</td>
                            <td>2011</td>
                        </tr>
                        <tr>
                            <td>1600 Meter Run</td>
                            <td>Charlie Schultz</td>
                            <td>4:41.65</td>
                            <td>2016</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Run</td>
                            <td>Jordan Theriault</td>
                            <td>9:57.37</td>
                            <td>2015</td>
                        </tr>
                        <tr>
                            <td>110 Meter High Hurdles</td>
                            <td>Peter Wassmann</td>
                            <td>15.8</td>
                            <td>2012</td>
                        </tr>
                        <tr>
                            <td>300 Meter Int. Hurdles</td>
                            <td>Luke Pilliod</td>
                            <td>41.37</td>
                            <td>2011</td>
                        </tr>
                        <tr>
                            <td>Long Jump</td>
                            <td>Sung Park</td>
                            <td>20-21/2</td>
                            <td>2004</td>
                        </tr>
                        <tr>
                            <td>Triple Jump </td>
                            <td>Shu Hiranuma</td>
                            <td>40-4</td>
                            <td>1988</td>
                        </tr>
                        <tr>
                            <td>High Jump</td>
                            <td>Sung Park</td>
                            <td>5-11</td>
                            <td>2004</td>
                        </tr>
                        <tr>
                            <td>Pole Vault</td>
                            <td>Craig Hendee</td>
                            <td>11-0</td>
                            <td>1964</td>
                        </tr>
                        <tr>
                            <td>Shot Put</td>
                            <td>Sean Campbell</td>
                            <td>43-5</td>
                            <td>1986</td>
                        </tr>
                        <tr>
                            <td>Discus</td>
                            <td>Sean Campbell</td>
                            <td>130-2</td>
                            <td>1986</td>
                        </tr>
                        <tr>
                            <td>400 Meter Relay</td>
                            <td>Soiresie, Gaydachuk, Kwasniewski, Wegley</td>
                            <td>46.3</td>
                            <td>2008</td>
                        </tr>
                        <tr>
                            <td>800 Meter Relay</td>
                            <td>Abromowitz, R Misek, Curtis, Koontz</td>
                            <td>1:38.7</td>
                            <td>1999</td>
                        </tr>
                        <tr>
                            <td>1600 Meter Relay</td>
                            <td>Schon, Budko, Novtony, Davis</td>
                            <td>3:44.1</td>
                            <td>1994</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Relay</td>
                            <td>O'Gara, Pilliod, Duran, Cowhey</td>
                            <td>8:47.91</td>
                            <td>2011</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="freshmen-indoor-tab-pane" role="tabpanel" aria-labelledby="freshmen-indoor-tab" tabindex="0">
                <h3>Freshmen Indoor Records</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Name</th>
                            <th>Performance</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>50 Meter Dash</td>
                            <td>Adam Smiley</td>
                            <td>6.2</td>
                            <td>1992</td>
                        </tr>
                        <tr>
                            <td> </td>
                            <td>Noah Shapiro</td>
                            <td>6.2</td>
                            <td>2019</td>
                        </tr>
                        <tr>
                            <td>55 Meter Dash</td>
                            <td>Nathan Shapiro</td>
                            <td>6.95</td>
                            <td>2019</td>
                        </tr>
                        <tr>
                            <td>200 Meter Dash</td>
                            <td>Nathan Shapiro</td>
                            <td>24.0</td>
                            <td>2019</td>
                        </tr>
                        <tr>
                            <td>300 Meter Dash</td>
                            <td>Jon Novotny</td>
                            <td>39.3</td>
                            <td>1994</td>
                        </tr>
                        <tr>
                            <td>400 Meter Dash</td>
                            <td>Neil Melcher</td>
                            <td>54.9</td>
                            <td>1988</td>
                        </tr>
                        <tr>
                            <td>800 Meter Run</td>
                            <td>Brian Hiltebrand</td>
                            <td>2:05.3</td>
                            <td>2019</td>
                        </tr>
                        <tr class="row-highlight">
                            <td>1000 Meter Run</td>
                            <td>Jack Lyons</td>
                            <td>2:51.9</td>
                            <td>2024</td>
                        </tr>
                        <tr>
                            <td>1600 Meter Run</td>
                            <td>Brian Hiltebrand</td>
                            <td>4:38.53</td>
                            <td>2019</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Run</td>
                            <td>Will Kelly</td>
                            <td>10:25.11</td>
                            <td>2017</td>
                        </tr>
                        <tr>
                            <td>55 Meter Int Hurdles</td>
                            <td>Luke Pilliod/Peter Wassmann</td>
                            <td>8.4</td>
                            <td>2011-12</td>
                        </tr>
                        <tr>
                            <td>55 Meter High Hurdles</td>
                            <td>Peter Wassmann</td>
                            <td>8.4</td>
                            <td>2012</td>
                        </tr>
                        <tr>
                            <td>55 Meter Low Hurdles</td>
                            <td>Nathan Shapiro</td>
                            <td>8.19</td>
                            <td>2015</td>
                        </tr>
                        <tr>
                            <td>Triple Jump</td>
                            <td>Al Joseph</td>
                            <td>39-03/4</td>
                            <td>1976</td>
                        </tr>
                        <tr>
                            <td>Shot Put</td>
                            <td>Dane Poyser</td>
                            <td>41-10</td>
                            <td>2006</td>
                        </tr>
                        <tr>
                            <td>High Jump</td>
                            <td>Sung Park</td>
                            <td>6-1</td>
                            <td>2004</td>
                        </tr>
                        <tr>
                            <td>Pole Vault</td>
                            <td>Ron Mori</td>
                            <td>10-6</td>
                            <td>1976</td>
                        </tr>
                        <tr>
                            <td>Long Jump</td>
                            <td>Nathan Shapiro</td>
                            <td>20-31/2</td>
                            <td>2019</td>
                        </tr>
                        <tr>
                            <td>4 x 160 Relay</td>
                            <td>Bryan Scheffler, Hiltebrand, Nathan Shapiro, Noah Shapiro</td>
                            <td>1:19.2</td>
                            <td>2019</td>
                        </tr>
                        <tr>
                            <td>1600 Meter Relay</td>
                            <td>Pilliod, Just, Duran, J Cowhey</td>
                            <td>3:55.6</td>
                            <td>2011</td>
                        </tr>
                        <tr>
                            <td>3200 Meter Relay</td>
                            <td>Belkin, Mikuni, Gazda, Faith</td>
                            <td>9:17.2</td>
                            <td>2004</td>
                        </tr>
                        <tr>
                            <td>Sprint Medley Relay</td>
                            <td>Kwasniewski, Cameron, Wegley, Gaydachuk</td>
                            <td>2:12.8</td>
                            <td>2008</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>*Last Updated May 30, 2024.</p>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . "/footer.php"); ?>