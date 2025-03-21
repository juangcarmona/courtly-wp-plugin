<?php
// src/activation.php
function courtly_create_tables() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $prefix = $wpdb->prefix;

    $tables[] = "CREATE TABLE {$prefix}courtly_courts (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        number INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        description TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";

    $tables[] = "CREATE TABLE {$prefix}courtly_user_types (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        display_name VARCHAR(100) NOT NULL,
        booking_days_in_advance INT NOT NULL
    ) $charset_collate;";

    $tables[] = "CREATE TABLE {$prefix}courtly_reservations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        court_id BIGINT UNSIGNED NOT NULL,
        reservation_date DATE NOT NULL,
        time_slot VARCHAR(20) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user_reservation (user_id, reservation_date)
    ) $charset_collate;";

    foreach ($tables as $sql) {
        dbDelta($sql);
    }

    // Insert default user types if not present
    $user_types_table = "{$prefix}courtly_user_types";

    $existing = $wpdb->get_var("SELECT COUNT(*) FROM $user_types_table");
    if ($existing == 0) {
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
}

