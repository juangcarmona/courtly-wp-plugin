<?php
// presentation/admin/ajax/availability.php

require_once plugin_dir_path(__FILE__) . '../../../infrastructure/bootstrap.php';


if (!defined('ABSPATH')) {
    exit;
}

// Make sure you load the container dependencies
require_once plugin_dir_path(__FILE__) . '../../../infrastructure/bootstrap.php';

$availabilityService = CourtlyContainer::availabilityService();

// ----------------------------
// Get locked blocks
// ----------------------------
add_action('wp_ajax_courtly_get_blocked_slots', function () use ($availabilityService) {
    $court_id = intval($_GET['court_id'] ?? 0);
    $start = new DateTime($_GET['start']);
    $end = new DateTime($_GET['end']);

    error_log("[Courtly] AJAX get_blocked_slots - court_id={$court_id}, start={$start->format('c')}, end={$end->format('c')}");

    try {
        $events = $availabilityService->getBlockedSlotsForRange($court_id, $start, $end);
        wp_send_json($events);
    } catch (Exception $e) {
        error_log("[Courtly] Error fetching blocked slots: " . $e->getMessage());
        wp_send_json(['status' => 'error', 'message' => 'Error fetching blocked slots'], 500);
    }
});

// ----------------------------
// Save a new block
// ----------------------------
add_action('wp_ajax_courtly_save_blocked_slot', function () use ($availabilityService) {
    $court_id = intval($_POST['court_id']);
    $start = new DateTime(sanitize_text_field($_POST['start_time']));
    $end = new DateTime(sanitize_text_field($_POST['end_time']));
    $reason = sanitize_text_field($_POST['reason']);

    error_log("[Courtly] Saving blocked slot - start: {$start->format('c')}, end: {$end->format('c')}");

    try {
        $success = $availabilityService->saveBlockedSlot($court_id, $start, $end, $reason);
        if ($success) {
            wp_send_json(['status' => 'success', 'message' => 'Blocked slot saved successfully']);
        } else {
            throw new Exception("Insert failed");
        }
    } catch (Exception $e) {
        error_log("[Courtly] Failed to save blocked slot: " . $e->getMessage());
        wp_send_json(['status' => 'error', 'message' => 'Failed to save blocked slot'], 500);
    }
});

// ----------------------------
// Delete a block
// ----------------------------
add_action('wp_ajax_courtly_remove_blocked_slot', function () use ($availabilityService) {
    $event_id = intval($_POST['event_id']);

    try {
        $deleted = $availabilityService->deleteBlockedSlot($event_id);
        if ($deleted) {
            wp_send_json(['status' => 'success', 'message' => 'Blocked slot removed']);
        } else {
            throw new Exception("Delete failed");
        }
    } catch (Exception $e) {
        error_log("[Courtly] Failed to remove blocked slot: " . $e->getMessage());
        wp_send_json(['status' => 'error', 'message' => 'Failed to remove blocked slot'], 500);
    }
});

// ----------------------------
// Get track listing
// ----------------------------
add_action('wp_ajax_courtly_get_courts', function () {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $results = $wpdb->get_results("SELECT id, name FROM {$prefix}courtly_courts");
    $resources = array_map(fn($court) => [
        'id' => $court->id,
        'title' => $court->name
    ], $results);

    wp_send_json($resources);
});

// ----------------------------
// Get existing reservations
// ----------------------------
add_action('wp_ajax_courtly_get_reservations', function () {
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
});
