import { renderGeneralCalendar } from '../../Shared/Calendar/CalendarGeneral.js';
import logger from '../../Shared/Logger/Logger.js';

logger.setLevel('info');

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('courtly-calendar');
  if (el) {
    renderGeneralCalendar(el, '/wp-admin/admin-ajax.php');
  }
});
