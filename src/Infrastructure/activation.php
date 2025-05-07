<?php

use Juangcarmona\Courtly\Domain\Entities\UserType;
use Juangcarmona\Courtly\Domain\Entities\Court;
use Juangcarmona\Courtly\Domain\Entities\CourtBlock;
use Juangcarmona\Courtly\Domain\Entities\CourtReservation;
use Juangcarmona\Courtly\Domain\Entities\OpeningHours;


function courtly_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $entities = [
        'user_types' => UserType::class,
        'courts' => Court::class,
        'court_blocks' => CourtBlock::class,
        'reservations' => CourtReservation::class,
        'opening_hours' => OpeningHours::class,
    ];

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    foreach ($entities as $suffix => $class) {
        $table = $wpdb->prefix . 'courtly_' . $suffix;

        if (method_exists($class, 'schema')) {
            $sql = $class::schema($table) . " $charset_collate;";
            error_log("[Courtly] Creating table $table with $class");
            dbDelta($sql);
        } else {
            error_log("[Courtly] ERROR: No schema method in class $class");
        }
    }
}

function courtly_seed_data() {
    global $wpdb;

    $user_types_table = $wpdb->prefix . 'courtly_user_types';
    $courts_table = $wpdb->prefix . 'courtly_courts';
    $hours_table = $wpdb->prefix . 'courtly_opening_hours';

    // Seed user types
    if ((int)$wpdb->get_var("SELECT COUNT(*) FROM $user_types_table") === 0) {
        $wpdb->insert($user_types_table, [
            'name' => 'guest',
            'display_name' => __('Guest', 'courtly'),
            'booking_days_in_advance' => 4
        ]);
        $wpdb->insert($user_types_table, [
            'name' => 'member',
            'display_name' => __('Member', 'courtly'),
            'booking_days_in_advance' => 5
        ]);
    }

    // Seed court
    if ((int)$wpdb->get_var("SELECT COUNT(*) FROM $courts_table") === 0) {
        $wpdb->insert($courts_table, [
            'number' => 1,
            'name' => __('Court 1', 'courtly'),
            'description' => __('Main court', 'courtly'),
            'pictures' => serialize([]),
        ]);
    }

    // Seed opening hours (10:00 to 22:00)
    if ((int)$wpdb->get_var("SELECT COUNT(*) FROM $hours_table") === 0) {
        for ($day = 0; $day <= 6; $day++) {
            $wpdb->insert($hours_table, [
                'day_of_week' => $day,
                'time_ranges' => json_encode([
                    ['start' => '10:00:00', 'end' => '22:00:00']
                ]),
                'closed' => false
            ]);
        }
    }
}
