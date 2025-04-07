import { renderGeneralCalendar } from '../../shared/calendar/calendar-general.js';
import logger from '../../shared/logger/logger.js';

logger.setLevel('info');

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('courtly-calendar');
  if (!el) return;

  const currentUser = window.courtlyCurrentUser || {};
  const calendarWrapper = document.querySelector('.courtly-user-booking');

  renderGeneralCalendar(el, '/wp-admin/admin-ajax.php', {
    rangeInDays: 7,
    selectable: true,
    onSlotSelected: ({ start, end, resourceId, resourceTitle }) => {
      const startDate = new Date(start);
      const endDate = new Date(end);
    
      const dateFormatter = new Intl.DateTimeFormat('default', {
        day: 'numeric', month: 'long', year: 'numeric', 
        timeZone: "UTC" // ← ensures the date is shown in UTC
      });
      const timeFormatter = new Intl.DateTimeFormat('default', {
        hour: '2-digit', minute: '2-digit', hour12: false, 
        timeZone: "UTC" // ← ensures the time is shown in UTC
      });
    
      document.getElementById('courtly-court-name').textContent = resourceTitle;
      document.getElementById('courtly-date').textContent = dateFormatter.format(startDate);
      document.getElementById('courtly-time').textContent = `${timeFormatter.format(startDate)} → ${timeFormatter.format(endDate)}`;
    
      document.getElementById('courtly-court-id').value = resourceId;
      document.getElementById('courtly-start-time').value = start;
      document.getElementById('courtly-end-time').value = end;
    
      const bookBtn = document.querySelector('.courtly-book-btn');
      if (bookBtn) bookBtn.style.display = 'inline-block';
    }
    
    
  });
});
