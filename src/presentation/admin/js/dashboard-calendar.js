import { renderEditableCalendar } from '../../shared/calendar/calendar-editable.js';
import logger from '../../shared/logger/logger.js';

logger.setLevel('debug');
export async function initDashboardCalendar(containerEl, ajaxUrl) {
    if (!containerEl) return;

    logger.info('Fetching courtly dashboard data...');

    const [courts, reservations, blocks, openings] = await Promise.all([
        fetch(`${ajaxUrl}?action=courtly_get_dashboard_courts`).then(r => r.json()),
        fetch(`${ajaxUrl}?action=courtly_get_dashboard_reservations`).then(r => r.json()),
        fetch(`${ajaxUrl}?action=courtly_get_dashboard_blocks`).then(r => r.json()),
        fetch(`${ajaxUrl}?action=courtly_get_dashboard_openings`).then(r => r.json())
    ]);

    const closedSlots = [];

    for (let dow = 0; dow < 7; dow++) {
        const open = openings[dow];
        if (!open) continue;

        courts.forEach(court => {
            if (open.start !== '00:00:00') {
                closedSlots.push({
                    resourceId: court.id,
                    daysOfWeek: [dow],
                    startTime: '00:00',
                    endTime: open.start,
                    display: 'background',
                    backgroundColor: '#030303'
                });
            }

            if (open.end !== '24:00:00' && open.end !== '23:59:59') {
                closedSlots.push({
                    resourceId: court.id,
                    daysOfWeek: [dow],
                    startTime: open.end,
                    endTime: '24:00',
                    display: 'background',
                    backgroundColor: '#030303'
                });
            }
        });
    }

    const today = new Date();
    const validStart = new Date(today);
    validStart.setDate(today.getDate() - 7);
    const validEnd = new Date(today);
    validEnd.setDate(today.getDate() + 7);

    const calendar = new FullCalendar.Calendar(containerEl, {
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        initialView: 'resourceTimeGridWeek',
        slotDuration: '00:30:00',
        timeZone: 'UTC',
        height: 700,
        resourceAreaHeaderContent: 'Courts',
        resources: courts,
        events: [...reservations, ...blocks, ...closedSlots],
        validRange: {
            start: validStart.toISOString().split('T')[0],
            end: validEnd.toISOString().split('T')[0]
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'resourceTimeGridDay,resourceTimeGridWeek'
        }
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
