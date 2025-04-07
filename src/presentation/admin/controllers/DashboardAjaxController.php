<?php

require_once plugin_dir_path(__FILE__) . '../../../infrastructure/repositories/CourtRepository.php';
require_once plugin_dir_path(__FILE__) . '../../../infrastructure/repositories/CourtReservationRepository.php';
require_once plugin_dir_path(__FILE__) . '../../../infrastructure/repositories/CourtBlockRepository.php';
require_once plugin_dir_path(__FILE__) . '../../../infrastructure/repositories/OpeningHoursRepository.php';

class DashboardAjaxController {
    public static function registerHooks() {
        add_action('wp_ajax_courtly_get_dashboard_courts', [self::class, 'getCourts']);
        add_action('wp_ajax_courtly_get_dashboard_reservations', [self::class, 'getReservations']);
        add_action('wp_ajax_courtly_get_dashboard_blocks', [self::class, 'getBlocks']);
        add_action('wp_ajax_courtly_get_dashboard_openings', [self::class, 'getOpeningHours']);
    }

    public static function getCourts() {
        $repo = new CourtRepository();
        $courts = $repo->findAll();
        $resources = array_map(fn($c) => ['id' => $c->id, 'title' => $c->name], $courts);
        wp_send_json($resources);
    }

    public static function getReservations() {
        $repo = new CourtReservationRepository();
        $events = [];

        $today = new DateTimeImmutable();
        $start = $today->modify('-7 days')->format('Y-m-d');
        $end = $today->modify('+7 days')->format('Y-m-d');

        global $wpdb;
        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}courtly_reservations 
                 WHERE reservation_date BETWEEN %s AND %s",
                $start, $end
            )
        );

        foreach ($rows as $r) {
            [$startTime, $endTime] = explode('-', $r->time_slot);
            $events[] = [
                'title' => 'Reserved',
                'start' => "{$r->reservation_date}T{$startTime}",
                'end' => "{$r->reservation_date}T{$endTime}",
                'resourceId' => $r->court_id,
                'backgroundColor' => '#0073aa',
                'borderColor' => '#0073aa',
                'type' => 'reservation',
                'extendedProps' => [
                    'id' => $r->id,
                    'type' => 'reservation'
                ]
            ];
        }

        wp_send_json($events);
    }

    public static function getBlocks() {
        $repo = new CourtBlockRepository();
        $events = [];

        $courts = (new CourtRepository())->findAll();
        $startDate = new DateTimeImmutable('last sunday');
        $endDate = $startDate->modify('+14 days');
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $tz = new DateTimeZone('UTC');

        foreach ($courts as $court) {
            $blocks = $repo->getBlockedSlots($court->id);
            foreach ($period as $date) {
                $weekday = (int)$date->format('w');
                foreach ($blocks as $block) {
                    if ((int)$block->day_of_week !== $weekday) continue;
                    $start = new DateTime("{$date->format('Y-m-d')} {$block->start_time}", $tz);
                    $end = new DateTime("{$date->format('Y-m-d')} {$block->end_time}", $tz);

                    $events[] = [
                        'title' => $block->reason ?: 'Blocked',
                        'start' => $start->format(DateTime::ATOM),
                        'end' => $end->format(DateTime::ATOM),
                        'resourceId' => $court->id,
                        'backgroundColor' => '#dc3545',
                        'borderColor' => '#dc3545',
                        'extendedProps' => [
                            'id' => $block->id,
                            'type' => 'block',
                            'court' => $court->name,
                            'reason' => $block->reason
                        ]

                    ];
                }
            }
        }

        wp_send_json($events);
    }

    public static function getOpeningHours() {
        $repo = new OpeningHoursRepository();
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
    
}
