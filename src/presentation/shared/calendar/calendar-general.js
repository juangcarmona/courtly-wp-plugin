import logger from '../logger/logger.js';

export async function renderGeneralCalendar(containerEl, ajaxUrl, options = {}) {
  if (!containerEl) return;
  
  const rangeInDays = options.rangeInDays || 7; // defaults to 7 if not passed
  const selectable = options.selectable || false;
  const onSlotSelected = options.onSlotSelected;

  logger.info(`Rendering calendar with range: ${rangeInDays} days`);
  if (selectable) logger.info('Calendar is in SELECTABLE mode');

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
  if (!selectable) {
    validStart.setDate(today.getDate() - rangeInDays);
  }
  const validEnd = new Date(today);
  validEnd.setDate(today.getDate() + rangeInDays);

  const calendar = new FullCalendar.Calendar(containerEl, {
    schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
    initialView: 'resourceTimeGridDay',
    slotDuration: '00:30:00',
    timeZone: 'UTC',
    height: 600,
    scrollTime: "09:00:00",
    resourceAreaHeaderContent: 'Courts',
    locale: 'es',
    firstDay: 1,
    resources: courts,
    selectOverlap: false,
    events: [...reservations, ...blocks, ...closedSlots],
    validRange: {
      start: validStart.toISOString().split('T')[0],
      end: validEnd.toISOString().split('T')[0]
    },
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'resourceTimeGridDay,resourceTimeGridWeek,dayGridMonth'
    },
    selectable,
    select: selectable ? (info) => {
      logger.debug('Slot selected:', info);
      onSlotSelected?.({
        start: info.startStr,
        end: info.endStr,
        resourceId: info.resource.id,
        resourceTitle: info.resource.title
      });
    } : null
  });

  calendar.render();
  logger.info('General calendar rendered');
  return calendar;
}
