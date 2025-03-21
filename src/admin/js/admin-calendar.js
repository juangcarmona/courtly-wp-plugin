document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('courtly-calendar');
    if (!calendarEl) return;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        height: 400,
        events: courtlyAjax.ajax_url,
        eventBackgroundColor: '#0073aa',
        eventBorderColor: '#005177'
    });

    calendar.render();
});
