<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_courtly_get_blocked_slots', 'courtly_get_blocked_slots');
function courtly_get_blocked_slots() {
    global $wpdb;

    $court_id = intval($_GET['court_id'] ?? 0);
    if ($court_id <= 0) {
        wp_send_json_error(['message' => 'Invalid court ID'], 400);
    }

    // Optional handling of dates if you want to use them:
    $start_date = isset($_GET['start']) ? sanitize_text_field($_GET['start']) : null;
    $end_date = isset($_GET['end']) ? sanitize_text_field($_GET['end']) : null;

    error_log("[Courtly] AJAX get_blocked_slots - court_id={$court_id}, start={$start_date}, end={$end_date}");

    // Query building example (ignoring dates):
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT id, start_time, end_time, reason
        FROM {$wpdb->prefix}courtly_availability
        WHERE court_id = %d AND day_of_week != -1 AND is_blocked = 1
    ", $court_id));

    error_log("[Courtly] DB results: " . json_encode($results));

    $events = array_map(function($row) {
        return [
            'id' => $row->id,
            'title' => $row->reason ?: 'Blocked',
            'start' => $row->start_time,
            'end' => $row->end_time,
            'backgroundColor' => '#dc3545',
            'borderColor' => '#dc3545'
        ];
    }, $results);

    wp_send_json($events);
}

add_action('wp_ajax_courtly_save_blocked_slot', 'courtly_save_blocked_slot');
function courtly_save_blocked_slot() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $court_id = intval($_POST['court_id']);
    $start_time = sanitize_text_field($_POST['start_time']);
    $end_time = sanitize_text_field($_POST['end_time']);
    $reason = sanitize_text_field($_POST['reason']);

    $success = $wpdb->insert("{$prefix}courtly_availability", [
        'court_id' => $court_id,
        'day_of_week' => date('w', strtotime($start_time)),
        'start_time' => $start_time,
        'end_time' => $end_time,
        'is_blocked' => 1,
        'reason' => $reason
    ]);

    if ($success !== false) {
        wp_send_json(['status' => 'success', 'message' => 'Blocked slot saved successfully']);
    } else {
        wp_send_json(['status' => 'error', 'message' => 'Failed to save blocked slot'], 500);
    }
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
