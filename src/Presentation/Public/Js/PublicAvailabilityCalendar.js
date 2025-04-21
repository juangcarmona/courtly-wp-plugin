import { renderGeneralCalendar } from '../../shared/calendar/calendar-general.js';
import logger from '../../shared/logger/logger.js';

logger.setLevel('info');

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('courtly-calendar');
  if (el) {
    renderGeneralCalendar(el, '/wp-admin/admin-ajax.php');
  }
});
