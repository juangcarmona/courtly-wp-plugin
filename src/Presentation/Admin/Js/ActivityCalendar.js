import { renderGeneralCalendar } from '../../Shared/Calendar/CalendarGeneral.js';
import logger from '../../Shared/Logger/Logger.js';

logger.setLevel('debug');

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('courtly-calendar');
  if (el && typeof courtlyAjax !== 'undefined') {
    renderGeneralCalendar(el, courtlyAjax.ajax_url, { rangeInDays: 30, isAdmin: true });
  }
});
