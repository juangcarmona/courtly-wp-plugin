
import { logCalendarEvent } from './calendar-utils.js';

export function renderBookingCalendar(containerEl, options) {
  const calendar = new FullCalendar.Calendar(containerEl, {
    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
    initialView: 'timeGridWeek',
    slotDuration: '00:30:00',
    selectable: true,
    height: options.height || 600, 
    headerToolbar: {  // <-- Enables toolbar with buttons
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay' // <-- Buttons for views
      },
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
