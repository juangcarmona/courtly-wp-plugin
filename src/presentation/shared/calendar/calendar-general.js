import logger from '../logger/logger.js';

export async function renderGeneralCalendar(containerEl, ajaxUrl, options = {}) {
  if (!containerEl) return;
  const rangeInDays = options.rangeInDays || 7; // defaults to 7 if not passed
  logger.info(`Rendering calendar with range: ${rangeInDays} days`);

  const [courts, reservations, blocks, openings] = await Promise.all([
    fetch(`${ajaxUrl}?action=courtly_get_dashboard_courts`).then(r => r.json()),
    fetch(`${ajaxUrl}?action=courtly_get_dashboard_reservations`).then(r => r.json()),
    fetch(`${ajaxUrl}?action=courtly_get_dashboard_blocks`).then(r => r.json()),
    fetch(`${ajaxUrl}?action=courtly_get_dashboard_openings`).then(r => r.json())
  ]);

  const closedSlots = [];
  for (let dow = 0; dow < rangeInDays; dow++) {
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
  validStart.setDate(today.getDate() - rangeInDays);
  const validEnd = new Date(today);
  validEnd.setDate(today.getDate() + rangeInDays);

  const calendar = new FullCalendar.Calendar(containerEl, {
    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
    initialView: 'resourceTimeGridDay',
    slotDuration: '00:30:00',
    timeZone: 'UTC',
    height: 700,
    scrollTime: "09:00:00",
    resourceAreaHeaderContent: 'Courts',
    locale: 'es',
    firstDay: 1,
    resources: courts,
    events: [...reservations, ...blocks, ...closedSlots],
    validRange: {
      start: validStart.toISOString().split('T')[0],
      end: validEnd.toISOString().split('T')[0]
    },
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'resourceTimeGridDay,resourceTimeGridWeek,dayGridMonth'
    }
  });

  calendar.render();
  logger.info('General calendar rendered');
  return calendar;
}
