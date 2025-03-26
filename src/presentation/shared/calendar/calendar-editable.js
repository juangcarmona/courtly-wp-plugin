
import { logCalendarEvent } from './calendar-utils.js';

export function renderEditableCalendar(containerEl, options) {
    const calendar = new FullCalendar.Calendar(containerEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'timeGridWeek',
        slotDuration: '00:30:00',
        timeZone: 'UTC',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
        editable: true,
        selectable: true,
        eventOverlap: false,
        height: options.height || 600, 
        events: options.fetchUrl,

        select(info) {
            const reason = prompt('Reason for blocking this slot:');
            if (reason) {
                options.onSlotBlocked?.({ start: info.startStr, end: info.endStr, reason });
            } else {
                logCalendarEvent('select', 'Cancelled by user');
            }
        },

        eventClick(info) {
            if (confirm('Remove this blocked slot?')) {
                options.onSlotRemoved?.(info.event.id);
            }
        },

        eventDrop(info) {
            options.onSlotUpdated?.({
                eventId: info.event.id,
                newStart: info.event.startStr,
                newEnd: info.event.endStr
            });
        },

        eventResize(info) {
            options.onSlotUpdated?.({
                eventId: info.event.id,
                newStart: info.event.startStr,
                newEnd: info.event.endStr
            });
        }
    });

    calendar.render();
    logCalendarEvent('init', 'Editable calendar rendered');
    return calendar;
}

