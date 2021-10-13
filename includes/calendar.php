<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/main.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        themeSystem: 'bootstrap',
        bootstrapFontAwesome: {
            close: 'fa-times',
            prev: 'bi bi-chevron-left',
            next: 'bi bi-chevron-right',
            prevYear: 'fa-angle-double-left',
            nextYear: 'fa-angle-double-right'
        },
        eventSources: [

            {
                url: '/api/practices.php', // Practices/Workouts
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

        ]
    });

    calendar.render();
});
</script>