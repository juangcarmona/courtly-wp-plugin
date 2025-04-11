<?php
if (!defined('ABSPATH')) exit;

add_action('admin_menu', 'courtly_add_admin_menu');

function courtly_add_admin_menu() {
    // Top-level menu
    add_menu_page('Courtly', 'Courtly', 'manage_options', 'courtly_dashboard', 'courtly_admin_dashboard_page', 'dashicons-calendar-alt', 25);

    // --- Dashboard & Calendar ---
    add_submenu_page('courtly_dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'courtly_dashboard', 'courtly_admin_dashboard_page');
    // --- Activity Calendar ---
    add_submenu_page('courtly_dashboard', 'Activity Calendar', 'Activity Calendar', 'manage_options', 'courtly_activity_calendar', 'courtly_admin_activity_calendar_page');
    // --- Reservations ---
    add_submenu_page('courtly_dashboard', 'New Reservation', 'Reservations New', 'manage_options', 'courtly_reservation_new', 'courtly_admin_reservation_new_page');
    add_submenu_page('courtly_dashboard', 'All Reservations', 'Reservations All', 'manage_options', 'courtly_reservations', 'courtly_admin_reservations_page');
    add_submenu_page('courtly_dashboard', 'Reservation History', 'Reservation  History', 'manage_options', 'courtly_history', 'courtly_admin_history_page');
    
    // --- Availability ---
    add_submenu_page('courtly_dashboard', 'Court Availability', 'Availability', 'manage_options', 'courtly_availability', 'courtly_admin_availability_page');
    
    // --- Courts ---
    add_submenu_page('courtly_dashboard', 'Manage Courts', 'Courts', 'manage_options', 'courtly_courts', 'courtly_admin_courts_page');
    
    // --- Users ---
    add_submenu_page('courtly_dashboard', 'Manage Users', 'Users', 'manage_options', 'courtly_users', 'courtly_admin_users_page');
    add_submenu_page('courtly_dashboard', 'User Types', 'User Types', 'manage_options', 'courtly_user_types', 'courtly_admin_user_types_page');
    
    // Hidden in menu, but accessible via URL
    add_submenu_page('courtly_dashboard', '', '', 'manage_options', 'courtly_reservation_detail', 'courtly_admin_reservation_detail_page');
}



// Page routing

function courtly_admin_dashboard_page() {
    include plugin_dir_path(__FILE__) . 'pages/dashboard.php';
}
// function courtly_admin_calendar_page() {
//     include plugin_dir_path(__FILE__) . 'pages/calendar.php';
// }
function courtly_admin_activity_calendar_page() {
    include plugin_dir_path(__FILE__) . '/pages/activity-calendar.php';
  }
function courtly_admin_reservations_page() {
    include plugin_dir_path(__FILE__) . 'pages/reservation-list.php';
}
function courtly_admin_reservation_new_page() {
    include plugin_dir_path(__FILE__) . 'pages/reservation-new.php';
}
function courtly_admin_history_page() {
    include plugin_dir_path(__FILE__) . 'pages/reservation-history.php';
}
function courtly_admin_reservation_detail_page() {
    include plugin_dir_path(__FILE__) . 'pages/reservation-detail.php';
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
