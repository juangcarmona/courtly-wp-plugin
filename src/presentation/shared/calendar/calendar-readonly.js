import { logCalendarEvent } from './calendar-utils.js';

export function renderReadonlyCalendar(containerEl, options) {
  const calendar = new FullCalendar.Calendar(containerEl, {
    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
    initialView: 'dayGridMonth',
    height: options.height || 500, 
    timeZone: 'UTC',
    headerToolbar: {  // <-- Enables toolbar with buttons
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay' // <-- Buttons for views
      },
    events: options.fetchUrl,
    eventClick: function(info) {
      if (options.showTooltips) {
        alert(info.event.title);
      }
    }
  });

  calendar.render();
  logCalendarEvent('init', 'Readonly calendar rendered');
  return calendar;
}