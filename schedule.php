<?php $pgtitle = "Schedule"; ?>
<?php include("header.php");?>
<?php
$result = mysqli_query($con,"SELECT * FROM series");
    while($row = mysqli_fetch_array($result)) {
        $series[$row['id']] = $row['slug'];
    }
?>

<section id="content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <i>*Schedule is weather-pending (and pandemic-pending for 2020) and subject to change.</i>
            <button type="button" class="btn btn-primary btn-sm text-center" data-toggle="modal"
                data-target="#addModal">
                <i class="fas fa-calendar-plus"></i> Add to Calendar
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-hover table-sm" id="scheduleTable">
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Opponents</th>
                        <th>Levels</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
$season = htmlspecialchars($_GET["season"]);
if (empty($season)) {
    $season = $currentseason;
}
$result = mysqli_query($con,"SELECT * FROM meets WHERE Season = '".$season."' ORDER BY Date ASC");

while($row = mysqli_fetch_array($result)) {
    if (empty($row['Series'])) {
        $url = "/meet/".$row['id'];
    } else {
        $url = "/meet/".$series[$row['Series']]."/".$d = date("Y",strtotime($row['Date']));
    }
    $dow = date("D",strtotime($row['Date']));
    $d = date("n/j",strtotime($row['Date']));
    $dir = "<a href='https://maps.google.com/?q=" . $row['Location'] . "' target='_blank'>".$row['Location']."</a>";
    $meethome = "/meet/" . $row['id'];
    $home = "<a href='". $meethome . "' class='btn btn-primary' role='button' aria-pressed='true'><i class='fas fa-home'></i></a>";
    
        echo "<tr class='clickable-row' data-href='".$url."'>";
        echo "<td>" . $dow . "</td>";
        echo "<td>" . $d . "</td>";
        echo "<td><a href='".$url."'>" . $row['Name'] . "</a></td>";
        echo "<td>" . $row['Opponents'] . "</td>";
        echo "<td>" . $row['Levels'] . "</td>";
        echo "<td>" . $dir . "</td>";
        echo "</tr>";

        if (!empty($row['Day2Time'])){
            echo "<tr class='clickable-row' data-href='".$meethome."'>";
            echo "<td>" . date("D",strtotime($row['Day2Time'])) . "</td>";
            echo "<td>" . date("n/j",strtotime($row['Day2Time'])) . "</td>";
            echo "<td><a href='/meet/" .$row['id'] . "'>" . $row['Name'] . "</a></td>";
            echo "<td>" . $row['Opponents'] . "</td>";
            echo "<td>" . $row['Day2Levels'] . "</td>";
            echo "<td>" . $dir . "</td>";
            echo "</tr>";   
        }
    }
?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</section>

<div class="container mt-4">
    <div id='calendar'></div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['dayGrid', 'list', 'bootstrap'],
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },

        themeSystem: 'bootstrap',

        eventSources: [

            {
                url: '/api/xcpractices.php', // XC Practices
                color: '#ffd700',
                textColor: 'black'
            },
            {
                url: '/api/tfpractices.php', // TF Practices
                color: '#ffd700',
                textColor: 'black'
            },
            {
                url: '/api/events.php', // Events
                color: '#007bff',
                textColor: 'white'
            },
            {
                url: '/api/schedule.php', // Meet Schedule
                color: '#073763',
                textColor: 'white'
            }

        ],

        eventClick: function(info) {
            if (event.url) {
                return false;
            }
        }


    });

    calendar.render();
});
</script>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add to Calendar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <strong>To add to Google Calendar:</strong><br>
                1) Navigate to <a
                    href="https://calendar.google.com/calendar/r/settings/addbyurl">https://calendar.google.com/calendar/r/settings/addbyurl</a><br>
                2) Paste the iCal link below into the text box.<br>
                <br>
                <strong>To add to iOS Calendar</strong><br>
                1) Open the Settings application, and click "Mail, Contacts, Calendars"<br>
                2) Click "Add Account" then "Other" then "Add Subscribed Calendar"<br>
                3) Paste in the link to the iCal File found below.<br>
                <br>
                <strong>To add to Outlook, Apple Calendar:</strong><br>
                Paste the link below where you can add an external calendar.<br>
                <br>Meet Schedule: <code id="icals"
                    class="user-select-all">https://titandistance.com/calendar/schedule</code>
                <br>Practice Schedule: <code id="icalp"
                    class="user-select-all">https://titandistance.com/calendar/practices</code><br><br>
                *Changes to the schedule may take upto 24 hours to appear.

            </div>
        </div>
    </div>
</div>

<?php include("footer.php");?>