import { renderGeneralCalendar } from '../../shared/calendar/calendar-general.js';
import logger from '../../shared/logger/logger.js';

logger.setLevel('debug');

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('courtly-calendar');
  if (el && typeof courtlyAjax !== 'undefined') {
    renderGeneralCalendar(el, courtlyAjax.ajax_url, { rangeInDays: 30, isAdmin: true });
  }
});
