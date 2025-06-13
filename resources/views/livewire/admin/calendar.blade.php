<div>
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />

    <!-- Calendar container -->
    <div id="calendar"></div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <script>
        document.addEventListener('livewire:load', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events), // Events passed from Livewire PHP
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                eventColor: '#378006' // green-ish color for events
            });

            calendar.render();
        });
    </script>

    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
            padding: 1rem;
        }
    </style>
</div>
