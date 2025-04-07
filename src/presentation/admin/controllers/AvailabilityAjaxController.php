<?php

require_once plugin_dir_path(__FILE__) . '../../../infrastructure/CourtlyContainer.php';

class AvailabilityAjaxController {
    public static function registerHooks() {
        $hooks = [
            'courtly_get_courts' => 'getCourts',
            'courtly_get_reservations' => 'getReservations',
            'courtly_get_opening_hours' => 'getOpeningHours',
            'courtly_get_blocks' => 'getBlocks',
        ];

        foreach ($hooks as $action => $method) {
            add_action("wp_ajax_$action", [self::class, $method]);
            add_action("wp_ajax_nopriv_$action", [self::class, $method]);
        }

        add_action('wp_ajax_courtly_get_blocked_slots', [self::class, 'getBlockedSlots']);
        add_action('wp_ajax_courtly_save_blocked_slot', [self::class, 'saveBlockedSlot']);
        add_action('wp_ajax_courtly_remove_blocked_slot', [self::class, 'removeBlockedSlot']);
    }

    public static function getCourts() {
        $repo = CourtlyContainer::courtRepository();
        $courts = $repo->findAll();
        $resources = array_map(fn($c) => [
            'id' => $c->id,
            'title' => $c->name
        ], $courts);

        wp_send_json($resources);
    }

    public static function getReservations() {
        $repo = CourtlyContainer::courtReservationRepository();
        $transformer = CourtlyContainer::eventTransformer();
        $reservations = $repo->findBetweenDates(
            new DateTime('today -30 days'),
            new DateTime('today +30 days')
        );

        $events = array_map(fn($r) => $transformer::reservationToEvent($r), $reservations);
        wp_send_json($events);
    }

    public static function getOpeningHours() {
        $repo = CourtlyContainer::openingHoursRepository();
        $all = $repo->getAll();

        $result = [];
        foreach ($all as $row) {
            $result[(int)$row->day_of_week] = [
                'start' => $row->open_time,
                'end' => $row->close_time
            ];
        }

        wp_send_json($result);
    }

    public static function getBlocks() {
        $blockRepo = CourtlyContainer::courtBlockRepository();
        $courtRepo = CourtlyContainer::courtRepository();
        $transformer = CourtlyContainer::eventTransformer();

        $today = new DateTimeImmutable('today');
        $end = $today->add(new DateInterval('P30D'));
        $period = new DatePeriod($today, new DateInterval('P1D'), $end);

        $courts = $courtRepo->findAll();
        $events = [];

        foreach ($courts as $court) {
            $blocks = $blockRepo->getBlockedSlots($court->id);

            foreach ($period as $date) {
                foreach ($blocks as $block) {
                    if ((int)$block->day_of_week === (int)$date->format('w')) {
                        $events[] = $transformer::blockToEvent($date, $block, $court->id, $court->name);
                    }
                }
            }
        }

        wp_send_json($events);
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
}
