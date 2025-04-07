<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'courtly_add_admin_menu');

function courtly_add_admin_menu() {
    // Top-level menu
    add_menu_page(
        'Courtly',
        'Courtly',
        'manage_options',
        'courtly_dashboard',
        'courtly_admin_dashboard_page',
        'dashicons-calendar-alt',
        25
    );

    // --- Dashboard & Calendar ---
    add_submenu_page('courtly_dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'courtly_dashboard', 'courtly_admin_dashboard_page');
    add_submenu_page('courtly_dashboard', 'Calendar', 'Calendar', 'manage_options', 'courtly_calendar', 'courtly_admin_calendar_page');

    // --- Reservations ---
    add_submenu_page('courtly_dashboard', 'All Reservations', 'Reservations → All', 'manage_options', 'courtly_reservations', 'courtly_admin_reservations_page');
    add_submenu_page('courtly_dashboard', 'New Reservation', 'Reservations → New', 'manage_options', 'courtly_new_reservation', 'courtly_admin_new_reservation_page');
    add_submenu_page('courtly_dashboard', 'Recurring Blocks', 'Reservations → Blocks', 'manage_options', 'courtly_blocks', 'courtly_admin_blocks_page');
    add_submenu_page('courtly_dashboard', 'Reservation History', 'Reservations → History', 'manage_options', 'courtly_history', 'courtly_admin_history_page');

    // --- Availability ---
    add_submenu_page('courtly_dashboard', 'Opening Hours', 'Availability → Hours', 'manage_options', 'courtly_opening_hours', 'courtly_admin_opening_hours_page');
    add_submenu_page('courtly_dashboard', 'Court Availability', 'Availability → Per Court', 'manage_options', 'courtly_availability', 'courtly_admin_availability_page');

    // --- Courts ---
    add_submenu_page('courtly_dashboard', 'Manage Courts', 'Courts', 'manage_options', 'courtly_courts', 'courtly_admin_courts_page');

    // --- Users ---
    add_submenu_page('courtly_dashboard', 'Manage Users', 'Users → All', 'manage_options', 'courtly_users', 'courtly_admin_users_page');
    add_submenu_page('courtly_dashboard', 'User Types', 'Users → Types', 'manage_options', 'courtly_user_types', 'courtly_admin_user_types_page');
}

// Page routing

function courtly_admin_dashboard_page() {
    include plugin_dir_path(__FILE__) . 'pages/dashboard.php';
}
function courtly_admin_calendar_page() {
    include plugin_dir_path(__FILE__) . 'pages/calendar.php'; // Reuse calendar view
}
function courtly_admin_reservations_page() {
    echo '<div class="wrap"><h1>All Reservations (coming soon)</h1></div>';
}
function courtly_admin_new_reservation_page() {
    include plugin_dir_path(__FILE__) . 'pages/new-reservation.php';
}
function courtly_admin_blocks_page() {
    echo '<div class="wrap"><h1>Recurring Blocks (coming soon)</h1></div>';
}
function courtly_admin_history_page() {
    echo '<div class="wrap"><h1>Reservation History (coming soon)</h1></div>';
}
function courtly_admin_opening_hours_page() {
    echo '<div class="wrap"><h1>Opening Hours (coming soon)</h1></div>';
}
function courtly_admin_availability_page() {
    include plugin_dir_path(__FILE__) . 'pages/availability.php';
}
function courtly_admin_courts_page() {
    include plugin_dir_path(__FILE__) . 'pages/courts.php';
}
function courtly_admin_users_page() {
    include plugin_dir_path(__FILE__) . 'pages/users.php';
}
function courtly_admin_user_types_page() {
    include plugin_dir_path(__FILE__) . 'pages/user-types.php';
}

// ✅ Asset loader
require_once __DIR__ . '/AdminAssets.php';
add_action('admin_enqueue_scripts', ['AdminAssets', 'enqueue']);
