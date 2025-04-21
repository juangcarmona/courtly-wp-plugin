import { renderEditableCalendar } from '../../Shared/Calendar/CalendarEditable.js';
import logger from '../../Shared/Logger/Logger.js';

logger.setLevel('debug');

export function initAvailabilityCalendar(calendarEl, ajaxUrl, courtId) {
    if (!calendarEl) {
        logger.warn('Availability calendar element not found.');
        return;
    }

    logger.info(`Rendering editable availability calendar for courtId=${courtId}`);

    const calendar = renderEditableCalendar(calendarEl, {
        fetchUrl: `${ajaxUrl}?action=courtly_get_blocked_slots&court_id=${courtId}`,
        height: 600,

        onSlotBlocked: ({ start, end, reason }) => {
            fetch(ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'courtly_save_blocked_slot',
                    court_id: courtId,
                    start_time: start,
                    end_time: end,
                    reason: reason
                })
            }).then(res => res.json()).then(() => {
                logger.info('Blocked slot saved. Refetching events...');
                calendar.refetchEvents();
            }).catch(err => logger.error('Error saving blocked slot:', err));
        },

        onSlotRemoved: (eventId) => {
            fetch(ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'courtly_remove_blocked_slot',
                    event_id: eventId
                })
            }).then(res => res.json()).then(() => {
                logger.info('Blocked slot removed. Refetching events...');
                calendar.refetchEvents();
            }).catch(err => logger.error('Error removing slot:', err));
        }
    });

    calendarEl.__calendar = calendar;
    return calendar;
}

// Auto-initialize for availability.php
document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('courtly-calendar');
    if (calendarEl && typeof courtlyAjax !== 'undefined' && courtlyAjax.court_id) {
        initAvailabilityCalendar(calendarEl, courtlyAjax.ajax_url, courtlyAjax.court_id);
    }
});
