<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_courtly_get_blocked_slots', 'courtly_get_blocked_slots');

function courtly_get_blocked_slots() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $court_id = intval($_GET['court_id'] ?? 0);
    $start = new DateTime($_GET['start']);
    $end = new DateTime($_GET['end']);

    error_log("[Courtly] AJAX get_blocked_slots - court_id={$court_id}, start={$start->format('c')}, end={$end->format('c')}");

    // Fetch recurring slots from DB
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT id, day_of_week, start_time, end_time, reason FROM {$prefix}courtly_availability 
         WHERE court_id = %d AND is_blocked = 1",
        $court_id
    ));

    error_log("[Courtly] DB results: " . json_encode($results));

    $events = [];
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $date) {
        $weekday = (int)$date->format('w');  // Matches PHP date('w') output: 0 (Sun) to 6 (Sat)
        foreach ($results as $row) {
            if ((int)$row->day_of_week === $weekday) {
                $startDateTime = new DateTime($date->format('Y-m-d') . ' ' . $row->start_time);
                $endDateTime = new DateTime($date->format('Y-m-d') . ' ' . $row->end_time);

                $events[] = [
                    'id' => $row->id,
                    'title' => $row->reason ?: 'Blocked',
                    'start' => $startDateTime->format(DateTime::ATOM),
                    'end' => $endDateTime->format(DateTime::ATOM),
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                    'editable' => false,
                ];
            }
        }
    }

    error_log('[Courtly] AJAX events returned: ' . json_encode($events));

    wp_send_json($events);
}


add_action('wp_ajax_courtly_save_blocked_slot', 'courtly_save_blocked_slot');
function courtly_save_blocked_slot() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $court_id = intval($_POST['court_id']);
    $start_time_iso = sanitize_text_field($_POST['start_time']); // ISO datetime from frontend
    $end_time_iso = sanitize_text_field($_POST['end_time']);     // ISO datetime from frontend
    $reason = sanitize_text_field($_POST['reason']);

    $start_timestamp = strtotime($start_time_iso);
    $end_timestamp = strtotime($end_time_iso);
    $day_of_week = date('w', $start_timestamp);

    error_log("[Courtly] Saving blocked slot - start: {$start_time_iso}, end: {$end_time_iso}, day_of_week: {$day_of_week}");

    $success = $wpdb->insert("{$prefix}courtly_availability", [
        'court_id' => $court_id,
        'day_of_week' => $day_of_week,
        'start_time' => date('H:i:s', $start_timestamp), 
        'end_time' => date('H:i:s', $end_timestamp),     
        'is_blocked' => 1,
        'reason' => $reason
    ]);

    if ($success !== false) {
        wp_send_json(['status' => 'success', 'message' => 'Blocked slot saved successfully']);
    } else {
        error_log("[Courtly] Failed to save blocked slot: " . $wpdb->last_error);
        wp_send_json(['status' => 'error', 'message' => 'Failed to save blocked slot'], 500);
    }
    wp_die();
}

add_action('wp_ajax_courtly_remove_blocked_slot', 'courtly_remove_blocked_slot');
function courtly_remove_blocked_slot() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $event_id = intval($_POST['event_id']);

    $deleted = $wpdb->delete("{$prefix}courtly_availability", ['id' => $event_id]);

    if ($deleted !== false) {
        wp_send_json(['status' => 'success', 'message' => 'Blocked slot removed']);
    } else {
        wp_send_json(['status' => 'error', 'message' => 'Failed to remove blocked slot'], 500);
    }
}

add_action('wp_ajax_courtly_get_courts', 'courtly_get_courts');
function courtly_get_courts() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $results = $wpdb->get_results("SELECT id, name FROM {$prefix}courtly_courts");
    $resources = array_map(fn($court) => [
        'id' => $court->id,
        'title' => $court->name
    ], $results);

    wp_send_json($resources);
}

add_action('wp_ajax_courtly_get_reservations', 'courtly_get_reservations');
function courtly_get_reservations() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $results = $wpdb->get_results("
        SELECT court_id, reservation_date, time_slot 
        FROM {$prefix}courtly_reservations
    ");

    $events = array_map(function($r) {
        [$start, $end] = explode('-', $r->time_slot);
        return [
            'title' => 'Reserved',
            'resourceId' => $r->court_id,
            'start' => "{$r->reservation_date}T{$start}",
            'end' => "{$r->reservation_date}T{$end}",
            'backgroundColor' => '#0073aa'
        ];
    }, $results);

    wp_send_json($events);
}
