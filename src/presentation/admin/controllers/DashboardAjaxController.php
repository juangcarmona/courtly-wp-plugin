<?php

class DashboardAjaxController {
    public static function registerHooks() {
        add_action('wp_ajax_courtly_get_availability', [self::class, 'getAvailability']);
    }

    public static function getAvailability() {
        global $wpdb;
        $prefix = $wpdb->prefix;

        $results = $wpdb->get_results("
            SELECT reservation_date, COUNT(*) AS total_reserved
            FROM {$prefix}courtly_reservations
            GROUP BY reservation_date
        ");

        $events = array_map(function($row) {
            $slots_available = max(0, 10 - intval($row->total_reserved));
            $color = ($slots_available > 5) ? '#28a745' : (($slots_available > 2) ? '#ffc107' : '#dc3545');

            return [
                'title' => "{$slots_available} slots available",
                'start' => $row->reservation_date,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'allDay' => true
            ];
        }, $results);

        wp_send_json($events);
    }
}
