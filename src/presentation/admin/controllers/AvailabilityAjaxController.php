<?php
class AvailabilityAjaxController {
    public static function registerHooks() {
        add_action('wp_ajax_courtly_get_blocked_slots', [self::class, 'getBlockedSlots']);
        add_action('wp_ajax_courtly_save_blocked_slot', [self::class, 'saveBlockedSlot']);
        add_action('wp_ajax_courtly_remove_blocked_slot', [self::class, 'removeBlockedSlot']);
        add_action('wp_ajax_courtly_get_courts', [self::class, 'getCourts']);
        add_action('wp_ajax_courtly_get_reservations', [self::class, 'getReservations']);
    }

    public static function getBlockedSlots() {
        $svc = CourtlyContainer::courtBlockService();
        $court_id = intval($_GET['court_id']);
        $start = new DateTime($_GET['start']);
        $end = new DateTime($_GET['end']);

        wp_send_json($svc->getBlockedSlotsForRange($court_id, $start, $end));
    }

    public static function saveBlockedSlot() {
        $svc = CourtlyContainer::courtBlockService();
        $court_id = intval($_POST['court_id']);
        $start = new DateTime($_POST['start_time']);
        $end = new DateTime($_POST['end_time']);
        $reason = sanitize_text_field($_POST['reason']);

        $success = $svc->saveBlockedSlot($court_id, $start, $end, $reason);
        wp_send_json(['status' => $success ? 'success' : 'error']);
    }

    public static function removeBlockedSlot() {
        $svc = CourtlyContainer::courtBlockService();
        $event_id = intval($_POST['event_id']);
        $success = $svc->deleteBlockedSlot($event_id);
        wp_send_json(['status' => $success ? 'success' : 'error']);
    }

    public static function getCourts() {
        $repo = new CourtRepository();
        $courts = $repo->findAll();
        $resources = array_map(fn($c) => ['id' => $c->id, 'title' => $c->name], $courts);
        wp_send_json($resources);
    }

    public static function getReservations() {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $rows = $wpdb->get_results("SELECT id, court_id, reservation_date, time_slot FROM {$prefix}courtly_reservations");
        $events = array_map(function ($r) {
            [$start, $end] = explode('-', $r->time_slot);
            return [
                'title' => 'Reserved',
                'resourceId' => $r->court_id,
                'start' => "{$r->reservation_date}T{$start}",
                'end' => "{$r->reservation_date}T{$end}",
                'backgroundColor' => '#0073aa',
                'type' => 'reservation',
                'extendedProps' => [                    
                    'id' => $r->id,
                    'type' => 'reservation'
                ]
            ];
        }, $rows);
        wp_send_json($events);
    }
}
