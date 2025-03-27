
export function parseAvailabilityEvents(data) {
    return data.map((item) => {
        const slotsAvailable = Math.max(0, 10 - parseInt(item.total_reserved, 10));
        const color = getColorByAvailability(slotsAvailable);
        return {
            title: `${slotsAvailable} slots available`,
            start: item.reservation_date,
            allDay: true,
            backgroundColor: color,
            borderColor: color
        };
    });
}

export function parseBlockedSlots(data) {
    return data.map((item) => ({
        id: item.id,
        title: item.reason || 'Blocked',
        start: item.start_time,
        end: item.end_time,
        backgroundColor: '#dc3545',
        borderColor: '#dc3545'
    }));
}

export function getColorByAvailability(slotsAvailable) {
    if (slotsAvailable > 5) return '#28a745';
    if (slotsAvailable > 2) return '#ffc107';
    return '#dc3545';
}

export function logCalendarEvent(type, ...args) {
    const prefix = `[Calendar::${type.toUpperCase()}]`;
    console.debug(prefix, ...args);
}
