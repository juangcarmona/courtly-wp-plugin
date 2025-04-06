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
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });

      const timeFormatter = new Intl.DateTimeFormat('default', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
      });

      const formatted = `
        👤 <strong>${currentUser.display_name || 'You'}</strong> (${currentUser.user_type || 'user'})<br>
        📍 <strong>${resourceTitle}</strong><br>
        📅 ${dateFormatter.format(startDate)}<br>
        🕒 ${timeFormatter.format(startDate)} → ${timeFormatter.format(endDate)}
      `;

      const summary = document.getElementById('courtly-summary');
      if (summary) summary.innerHTML = formatted;

      logger.info('Public reservation slot selected:', {
        user: currentUser,
        resource: resourceTitle,
        start, end
      });
    }
  });
});
