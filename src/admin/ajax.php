<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_courtly_get_availability', 'courtly_get_availability');

function courtly_get_availability() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    
    $results = $wpdb->get_results("
        SELECT reservation_date, COUNT(*) as total_reserved
        FROM {$prefix}courtly_reservations
        GROUP BY reservation_date
    ");

    $events = [];
    foreach ($results as $row) {
        $slots_available = max(0, 10 - intval($row->total_reserved)); // Ejemplo: 10 slots por día
        $color = ($slots_available > 5) ? '#28a745' : (($slots_available > 2) ? '#ffc107' : '#dc3545');

        $events[] = [
            'title' => "$slots_available slots available",
            'start' => $row->reservation_date,
            'backgroundColor' => $color,
            'borderColor' => $color
        ];
    }

    echo json_encode($events);
    wp_die();
}
