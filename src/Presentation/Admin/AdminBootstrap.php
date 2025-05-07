<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'courtly_add_admin_menu');

function courtly_add_admin_menu()
{
    // Top-level menu
    add_menu_page(
        __('Courtly', 'courtly'),
        __('Courtly', 'courtly'),
        'manage_options',
        'courtly_dashboard',
        'courtly_admin_dashboard_page',
        'dashicons-calendar-alt',
        25
    );

    // --- Dashboard ---
    add_submenu_page(
        'courtly_dashboard',
        __('Dashboard', 'courtly'),
        __('Dashboard', 'courtly'),
        'manage_options',
        'courtly_dashboard',
        'courtly_admin_dashboard_page'
    );

    // -- Opening Hours ---
    add_submenu_page(
        'courtly_dashboard',
        __('Opening Hours', 'courtly'),
        __('Opening Hours', 'courtly'),
        'manage_options',
        'courtly-opening-hours',
        'courtly_admin_opening_hours_page'
    );

    // --- Activity Calendar ---
    add_submenu_page(
        'courtly_dashboard',
        __('Activity Calendar', 'courtly'),
        __('Activity Calendar', 'courtly'),
        'manage_options',
        'courtly_activity_calendar',
        'courtly_admin_activity_calendar_page'
    );

    // --- New Reservation ---
    add_submenu_page(
        'courtly_dashboard',
        __('New Reservation', 'courtly'),
        __('Reservations New', 'courtly'),
        'manage_options',
        'courtly_reservation_new',
        'courtly_admin_reservation_new_page'
    );

    // --- All Reservations ---
    add_submenu_page(
        'courtly_dashboard',
        __('All Reservations', 'courtly'),
        __('Reservations All', 'courtly'),
        'manage_options',
        'courtly_reservations',
        'courtly_admin_reservations_page'
    );

    // --- Reservation History ---
    add_submenu_page(
        'courtly_dashboard',
        __('Reservation History', 'courtly'),
        __('Reservation History', 'courtly'),
        'manage_options',
        'courtly_history',
        'courtly_admin_history_page'
    );

    // --- Availability ---
    add_submenu_page(
        'courtly_dashboard',
        __('Court Availability', 'courtly'),
        __('Court Availability', 'courtly'),
        'manage_options',
        'courtly_availability',
        'courtly_admin_availability_page'
    );

    // --- Courts ---
    add_submenu_page(
        'courtly_dashboard',
        __('Manage Courts', 'courtly'),
        __('Courts', 'courtly'),
        'manage_options',
        'courtly_courts',
        'courtly_admin_courts_page'
    );

    // --- Users ---
    add_submenu_page(
        'courtly_dashboard',
        __('Manage Users', 'courtly'),
        __('Users', 'courtly'),
        'manage_options',
        'courtly_users',
        'courtly_admin_users_page'
    );

    add_submenu_page(
        'courtly_dashboard',
        __('User Types', 'courtly'),
        __('User Types', 'courtly'),
        'manage_options',
        'courtly_user_types',
        'courtly_admin_user_types_page'
    );

    // Hidden in menu, but accessible via URL
    add_submenu_page(
        'courtly_dashboard',
        '',
        '',
        'manage_options',
        'courtly_reservation_detail',
        'courtly_admin_reservation_detail_page'
    );
}

// Page routing
function courtly_admin_dashboard_page()
{
    include plugin_dir_path(__FILE__).'Pages/DashboardPage.php';
}

function courtly_admin_opening_hours_page()
{
    include plugin_dir_path(__FILE__).'Pages/OpeningHoursPage.php';
}

function courtly_admin_activity_calendar_page()
{
    include plugin_dir_path(__FILE__).'/Pages/ActivityCalendarPage.php';
}

function courtly_admin_reservations_page()
{
    include plugin_dir_path(__FILE__).'Pages/ReservationListPage.php';
}

function courtly_admin_reservation_new_page()
{
    include plugin_dir_path(__FILE__).'Pages/ReservationNewPage.php';
}

function courtly_admin_history_page()
{
    include plugin_dir_path(__FILE__).'Pages/ReservationHistoryPage.php';
}

function courtly_admin_reservation_detail_page()
{
    include plugin_dir_path(__FILE__).'Pages/ReservationDetailPage.php';
}

function courtly_admin_availability_page()
{
    include plugin_dir_path(__FILE__).'Pages/CourtAvailabilityPage.php';
}

function courtly_admin_courts_page()
{
    include plugin_dir_path(__FILE__).'Pages/CourtsPage.php';
}

function courtly_admin_users_page()
{
    include plugin_dir_path(__FILE__).'Pages/UsersPage.php';
}

function courtly_admin_user_types_page()
{
    include plugin_dir_path(__FILE__).'Pages/UserTypesPage.php';
}

// ✅ Asset loader
require_once __DIR__.'/AdminAssets.php';
add_action('admin_enqueue_scripts', ['AdminAssets', 'enqueue']);
