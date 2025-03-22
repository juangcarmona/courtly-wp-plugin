// admin/js/admin-calendar.js (refactored)

import { renderEditableCalendar } from '../../shared/calendar/calendar-editable.js';
import logger from '../../shared/logger/logger.js';

logger.setLevel('debug');
logger.enable();

export function initEditableAdminCalendar(calendarEl, options) {
  if (!calendarEl) {
    logger.warn('No calendar element found with id "courtly-calendar"');
    return;
  }

  logger.info('Rendering admin editable calendar...');

  const calendar = renderEditableCalendar(calendarEl, {
    courtId: options.courtId,
    fetchUrl: `${options.ajaxUrl}&action=courtly_get_blocked_slots&court_id=${options.courtId}`,

    onSlotBlocked: ({ start, end, reason }) => {
      fetch(options.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'courtly_save_blocked_slot',
          court_id: options.courtId,
          start_time: start,
          end_time: end,
          reason: reason
        })
      }).then(() => {
        logger.info('Blocked slot saved. Refetching events...');
        calendar.refetchEvents();
      }).catch(err => logger.error('Error saving blocked slot:', err));
    },

    onSlotRemoved: (eventId) => {
      fetch(options.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'courtly_remove_blocked_slot',
          event_id: eventId
        })
      }).then(() => {
        logger.debug('Blocked slot removed. Refetching events...');
        calendar.refetchEvents();
      }).catch(err => logger.error('Error removing slot:', err));
    },

    onSlotUpdated: ({ eventId, newStart, newEnd }) => {
      fetch(options.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'courtly_update_blocked_slot',
          event_id: eventId,
          start_time: newStart,
          end_time: newEnd
        })
      }).then(() => {
        logger.debug('Blocked slot updated. Refetching events...');
        calendar.refetchEvents();
      }).catch(err => logger.error('Error updating blocked slot:', err));
    },

    height: 600
  });

  calendarEl.__calendar = calendar;
  return calendar;
}

// Auto init for availability.php or others using this directly
document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('courtly-calendar');
  if (typeof courtlyAjax !== 'undefined' && calendarEl) {
    initEditableAdminCalendar(calendarEl, {
      ajaxUrl: courtlyAjax.ajax_url,
      courtId: courtlyAjax.court_id
    });
  }
});