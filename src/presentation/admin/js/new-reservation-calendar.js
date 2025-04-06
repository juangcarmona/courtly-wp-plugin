import { renderGeneralCalendar } from "../../shared/calendar/calendar-general.js";
import logger from "../../shared/logger/logger.js";

logger.setLevel("info");

document.addEventListener("DOMContentLoaded", async () => {
  const el = document.getElementById("courtly-calendar");
  const summary = document.getElementById("courtly-summary");
  const submitBtn = document.getElementById("courtly-submit-btn");
  const userSelect = document.getElementById("courtly-user-select");
  const calendarWrapper = document.querySelector(".courtly-calendar-wrapper");

  if (!el || typeof courtlyAjax === "undefined") return;

  let calendarInstance = null;

  userSelect.addEventListener("change", () => {
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    const userId = selectedOption.value;
    const userType = selectedOption.dataset.type;
    const range = parseInt(selectedOption.dataset.days, 10) || 7;

    // Reset UI
    el.innerHTML = "";
    summary.textContent = "";
    submitBtn.disabled = true;
    calendarWrapper.style.display = "none";

    if (!userId) return;

    logger.info("User selected:", { userId, userType, range });

    calendarWrapper.style.display = "block";

    renderGeneralCalendar(el, courtlyAjax.ajax_url, {
      rangeInDays: range,
      selectable: true,
      onSlotSelected: ({ start, end, resourceId, resourceTitle }) => {
        logger.info("User selected a slot for reservation:", {
          start, end, resourceId, resourceTitle,
        });

        document.getElementById("courtly-court-id").value = resourceId;
        document.getElementById("courtly-start-time").value = start;
        document.getElementById("courtly-end-time").value = end;

        const startDate = new Date(start);
        const endDate = new Date(end);

        const dateFormatter = new Intl.DateTimeFormat("default", {
          day: "numeric", month: "long", year: "numeric"
        });

        const timeFormatter = new Intl.DateTimeFormat("default", {
          hour: "2-digit", minute: "2-digit", hour12: false
        });

        summary.textContent = `${resourceTitle} — ${dateFormatter.format(startDate)}, ${timeFormatter.format(startDate)} → ${timeFormatter.format(endDate)}`;
        submitBtn.disabled = false;
      }
    });
  });
  if (userSelect.value) {
    userSelect.dispatchEvent(new Event("change"));
  }
});


