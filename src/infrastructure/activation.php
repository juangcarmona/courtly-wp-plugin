<?php

require_once __DIR__ . '/../infrastructure/require_entities.php';

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
