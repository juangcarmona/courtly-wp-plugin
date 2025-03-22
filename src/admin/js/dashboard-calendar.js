import { renderReadonlyCalendar } from '../../shared/calendar/calendar-readonly.js';
import logger from '../../shared/logger/logger.js';

logger.setLevel('debug');

export function initDashboardCalendar(calendarEl, ajaxUrl) {
    if (!calendarEl) {
        logger.warn('Dashboard calendar element not found.');
        return;
    }

    logger.info('Rendering readonly dashboard calendar...');

    const calendar = renderReadonlyCalendar(calendarEl, {
        fetchUrl: `${ajaxUrl}?action=courtly_get_availability`,
        showTooltips: true,
    });

    calendarEl.__calendar = calendar;
    return calendar;
}

// Auto-initialize for dashboard.php
document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('courtly-calendar');
    if (calendarEl && typeof courtlyAjax !== 'undefined') {
        initDashboardCalendar(calendarEl, courtlyAjax.ajax_url);
    }
});