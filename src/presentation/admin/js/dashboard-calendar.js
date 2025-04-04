import { renderEditableCalendar } from '../../shared/calendar/calendar-editable.js';
import logger from '../../shared/logger/logger.js';

logger.setLevel('debug');

export async function initDashboardCalendar(containerEl, ajaxUrl) {
    if (!containerEl) return;

    logger.info('Fetching courtly dashboard data...');

    const [courts, reservations, blocks] = await Promise.all([
        fetch(`${ajaxUrl}?action=courtly_get_dashboard_courts`).then(r => r.json()),
        fetch(`${ajaxUrl}?action=courtly_get_dashboard_reservations`).then(r => r.json()),
        fetch(`${ajaxUrl}?action=courtly_get_dashboard_blocks`).then(r => r.json())
    ]);

    const calendar = new FullCalendar.Calendar(containerEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'resourceTimeGridWeek',
        slotDuration: '00:30:00',
        timeZone: 'UTC',
        height: 700,
        resourceAreaHeaderContent: 'Courts',
        resources: courts,
        events: [...reservations, ...blocks],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'resourceTimeGridDay,resourceTimeGridWeek'
        },
    });

    calendar.render();
    logger.info('Dashboard calendar initialized');
    return calendar;
}

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('courtly-calendar');
    if (el && typeof courtlyAjax !== 'undefined') {
        initDashboardCalendar(el, courtlyAjax.ajax_url);
    }
});
