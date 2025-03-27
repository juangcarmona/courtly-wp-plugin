<?php

require_once __DIR__ . '/../domain/entities/UserType.php';
require_once __DIR__ . '/../domain/entities/Court.php';
require_once __DIR__ . '/../domain/entities/OpeningHours.php';

function courtly_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $entities = [
        'user_types' => 'UserType',
        'courts' => 'Court',
        'court_blocks' => 'CourtBlock',
        'reservations' => 'CourtReservation',
        'opening_hours' => 'OpeningHours',
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
            'display_name' => 'Guest',
            'booking_days_in_advance' => 4
        ]);
        $wpdb->insert($user_types_table, [
            'name' => 'member',
            'display_name' => 'Member',
            'booking_days_in_advance' => 5
        ]);
    }

    // Seed court
    if ((int)$wpdb->get_var("SELECT COUNT(*) FROM $courts_table") === 0) {
        $wpdb->insert($courts_table, [
            'number' => 1,
            'name' => 'Court 1',
            'description' => 'Main court',
            'pictures' => serialize([]),
        ]);
    }

    // Seed opening hours (10:00 to 22:00)
    if ((int)$wpdb->get_var("SELECT COUNT(*) FROM $hours_table") === 0) {
        for ($day = 0; $day <= 6; $day++) {
            $wpdb->insert($hours_table, [
                'day_of_week' => $day,
                'open_time' => '10:00:00',
                'close_time' => '22:00:00'
            ]);
        }
    }
}
