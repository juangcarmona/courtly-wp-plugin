
import logger from '../logger/logger.js';

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
            const reason = prompt(courtlyAjax.translations.block_reason_prompt);
            if (reason) {
                options.onSlotBlocked?.({ start: info.startStr, end: info.endStr, reason });
            } else {
                logger.debug('Slot selection canceled by user');
            }
        },

        eventClick(info) {
            if (confirm(courtlyAjax.translations.confirm_remove_block)) {
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
    logger.info('🛠 Editable calendar rendered');
    return calendar;
}

