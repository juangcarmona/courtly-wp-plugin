
import { logCalendarEvent } from './calendar-utils.js';

export function renderBookingCalendar(containerEl, options) {
  const calendar = new FullCalendar.Calendar(containerEl, {
    initialView: 'timeGridWeek',
    slotDuration: '00:30:00',
    selectable: true,
    height: options.height || 600,
    events: options.fetchUrl,

    select(info) {
      options.onSlotSelected?.({
        start: info.startStr,
        end: info.endStr
      });
    }
  });

  calendar.render();
  logCalendarEvent('init', 'Booking calendar rendered');
  return calendar;
}
