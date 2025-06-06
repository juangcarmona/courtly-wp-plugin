import logger from '../Logger/Logger.js';

export async function renderGeneralCalendar(containerEl, ajaxUrl, options = {}) {
  if (!containerEl) return;
  
  const rangeInDays = options.rangeInDays || 7; // defaults to 7 if not passed
  const selectable = options.selectable || false;
  const onSlotSelected = options.onSlotSelected;
  const isAdmin = options.isAdmin === true;
  
  logger.info(isAdmin ? '🛠 Admin mode' : '🌐 Public mode');
  logger.info(`Rendering calendar with range: ${rangeInDays} days`);
  if (selectable) logger.info('Calendar is in SELECTABLE mode');

  const [courts, reservations, blocks, openings] = await Promise.all([
    fetch(`${ajaxUrl}?action=courtly_get_courts`).then(r => r.json()),
    fetch(`${ajaxUrl}?action=courtly_get_reservations`).then(r => r.json()),
    fetch(`${ajaxUrl}?action=courtly_get_blocks`).then(r => r.json()),
    fetch(`${ajaxUrl}?action=courtly_get_opening_hours`).then(r => r.json())
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

      if (open.end !== '23:59:59') {
        closedSlots.push({
          resourceId: court.id,
          daysOfWeek: [dow],
          startTime: open.end,
          endTime: '23:59',
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
    } : null,
    eventClick: (info) => {
      const event = info.event;
      const props = event.extendedProps;
      const eventType = props.type;
      const eventId = props.id;
      logger.debug('Clicked event type:', eventType);
      logger.debug('Clicked event id:', eventId);
    
      if (eventType === 'reservation') {
        const url = isAdmin
          ? `/wp-admin/admin.php?page=courtly_reservation_detail&id=${eventId}`
          : buildReservationUrl(courtlyAjax.reservation_detail_base_url, eventId);
    
        window.location.href = url;
    
      } else if (eventType === 'block') {
        const start = new Date(event.start);
        const end = new Date(event.end);
    
        const timeFormatter = new Intl.DateTimeFormat('default', {
          hour: '2-digit', minute: '2-digit'
        });
    
        const dateFormatter = new Intl.DateTimeFormat('default', {
          weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
    
        alert(
          `⛔ ${props.court} — ${props.reason || courtlyAjax.translations.no_reason_provided}},\n` +
          `📅 ${courtlyAjax.translations.every} ${start.toLocaleDateString(undefined, { weekday: 'long' })}\n` +
          `🕒 ${timeFormatter.format(start)} → ${timeFormatter.format(end)}`
        );
        
      } else {
        logger.warn('Clicked event with unknown type:', eventType);
      }
    }
  });

  
  

  calendar.render();
  logger.info('General calendar rendered');
  return calendar;
}


function buildReservationUrl(baseUrl, reservationId) {
  const hasQuery = baseUrl.includes('?');
  const separator = hasQuery ? '&' : '?';
  return `${baseUrl}${separator}courtly_reservation_id=${reservationId}`;
}