import logger from '../../shared/logger/logger.js';

document.addEventListener('DOMContentLoaded', () => {
  const calendarEl = document.getElementById('courtly-calendar');
  if (!calendarEl) return;

  logger.info('Rendering reservation availability (ResourceTimeline)...');

  const calendar = new FullCalendar.Calendar(calendarEl, {
    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    initialView: 'resourceTimelineWeek',
    slotMinTime: '08:00:00',
    slotMaxTime: '22:00:00',
    height: 600,
    nowIndicator: true,
    resourceAreaHeaderContent: 'Courts',
    resources: courtlyAjax.ajax_url + '?action=courtly_get_courts',
    events: courtlyAjax.ajax_url + '?action=courtly_get_reservations'
  });

  calendar.render();
});
