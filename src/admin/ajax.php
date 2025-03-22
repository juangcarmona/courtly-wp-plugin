<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_courtly_get_availability', 'courtly_get_availability');
function courtly_get_availability() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    
    error_log('[Courtly] get_availability called');
    
    $results = $wpdb->get_results("SELECT reservation_date, COUNT(*) as total_reserved FROM {$prefix}courtly_reservations GROUP BY reservation_date");
    
    $events = [];
    foreach ($results as $row) {
        $slots_available = max(0, 10 - intval($row->total_reserved));
        $color = ($slots_available > 5) ? '#28a745' : (($slots_available > 2) ? '#ffc107' : '#dc3545');
        
        $events[] = [
            'title' => "$slots_available slots available",
            'start' => $row->reservation_date,
            'backgroundColor' => $color,
            'borderColor' => $color
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($events);
    wp_die();
}

add_action('wp_ajax_courtly_get_blocked_slots', 'courtly_get_blocked_slots');
function courtly_get_blocked_slots() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $court_id = intval($_GET['court_id'] ?? 0);

    error_log("[Courtly] get_blocked_slots for court_id={$court_id}");

    $results = $wpdb->get_results($wpdb->prepare("SELECT id, start_time, end_time, reason FROM {$prefix}courtly_availability WHERE court_id = %d AND day_of_week != -1 AND is_blocked = 1", $court_id));

    $events = [];
    foreach ($results as $row) {
        logger('[Courtly] get_blocked_slots: ' . json_encode($row));
        $events[] = [
            'id' => $row->id,
            'title' => $row->reason ?: 'Blocked',
            'start' => $row->start_time,
            'end' => $row->end_time,
            'backgroundColor' => '#dc3545',
            'borderColor' => '#dc3545'
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($events);
    wp_die();
}

add_action('wp_ajax_courtly_save_blocked_slot', 'courtly_save_blocked_slot');
function courtly_save_blocked_slot() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    
    $court_id = intval($_POST['court_id']);
    $start_time = sanitize_text_field($_POST['start_time']);
    $end_time = sanitize_text_field($_POST['end_time']);
    $reason = sanitize_text_field($_POST['reason']);

    error_log("[Courtly] save_blocked_slot court_id={$court_id}, start={$start_time}, end={$end_time}, reason={$reason}");

    $success = $wpdb->insert("{$prefix}courtly_availability", [
        'court_id' => $court_id,
        'day_of_week' => date('w', strtotime($start_time)),
        'start_time' => $start_time,
        'end_time' => $end_time,
        'is_blocked' => 1,
        'reason' => $reason
    ]);

    if ($success !== false) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Blocked slot saved successfully']);
    } else {
        error_log("[Courtly] Failed to save blocked slot: " . $wpdb->last_error);
        header('Content-Type: application/json', true, 500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to save blocked slot']);
    }
    wp_die();
}

add_action('wp_ajax_courtly_remove_blocked_slot', 'courtly_remove_blocked_slot');
function courtly_remove_blocked_slot() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    
    $event_id = intval($_POST['event_id']);
    error_log("[Courtly] remove_blocked_slot id={$event_id}");
    
    $deleted = $wpdb->delete("{$prefix}courtly_availability", ['id' => $event_id]);
    
    if ($deleted !== false) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Blocked slot removed']);
    } else {
        error_log("[Courtly] Failed to remove blocked slot: " . $wpdb->last_error);
        header('Content-Type: application/json', true, 500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove blocked slot']);
    }
    wp_die();
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
        $parts = explode('-', $r->time_slot);
        return [
            'title' => 'Reserved',
            'resourceId' => $r->court_id,
            'start' => $r->reservation_date . 'T' . $parts[0],
            'end' => $r->reservation_date . 'T' . $parts[1],
            'backgroundColor' => '#0073aa'
        ];
    }, $results);

    wp_send_json($events);
}
