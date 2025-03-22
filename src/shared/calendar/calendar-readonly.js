import { logCalendarEvent } from './calendar-utils.js';

export function renderReadonlyCalendar(containerEl, options) {
  const calendar = new FullCalendar.Calendar(containerEl, {
    initialView: 'dayGridMonth',
    height: options.height || 500,
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