<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'courtly_add_admin_menu');
add_action('admin_enqueue_scripts', 'courtly_enqueue_admin_scripts');

function courtly_add_admin_menu() {
    add_menu_page(
        'Courtly Settings',
        'Courtly',
        'manage_options',
        'courtly_settings',
        'courtly_admin_page',
        'dashicons-calendar-alt',
        25
    );

    add_submenu_page(
        'courtly_settings',
        'Manage Courts',
        'Courts',
        'manage_options',
        'courtly_courts',
        'courtly_admin_courts_page'
    );

    add_submenu_page(
        'courtly_settings',
        'User Types',
        'User Types',
        'manage_options',
        'courtly_user_types',
        'courtly_admin_user_types_page'
    );

    add_submenu_page(
        'courtly_settings',
        'Users',
        'Users',
        'manage_options',
        'courtly_users',
        'courtly_admin_users_page'
    );
}

function courtly_enqueue_admin_scripts($hook) {
    // Load scripts only on Courtly admin pages
    if (strpos($hook, 'courtly') === false) {
        return;
    }

    // Load Bootswatch Minty (Bootstrap 5)
    wp_enqueue_style('courtly-bootstrap-css', 'https://bootswatch.com/5/minty/bootstrap.min.css');

    // Load FullCalendar (correct version)
    wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js', [], false, true);

    // Load FullCalendar CSS (manually define styles if needed)
    wp_enqueue_style('courtly-calendar-css', plugin_dir_url(__FILE__) . 'css/admin-calendar.css');

    // Load our custom calendar script
    wp_enqueue_script(
        'courtly-admin-calendar',
        plugin_dir_url(__FILE__) . 'js/admin-calendar.js',
        ['jquery', 'fullcalendar-js'],
        false,
        true
    );

    // Pass AJAX URL to the script
    wp_localize_script('courtly-admin-calendar', 'courtlyAjax', [
        'ajax_url' => admin_url('admin-ajax.php?action=courtly_get_availability'),
    ]);
}

// Dispatch to actual pages

function courtly_admin_page() {
    include plugin_dir_path(__FILE__) . 'pages/dashboard.php';
}

function courtly_admin_courts_page() {
    include plugin_dir_path(__FILE__) . 'pages/courts.php';
}

function courtly_admin_user_types_page() {
    include plugin_dir_path(__FILE__) . 'pages/user-types.php';
}

function courtly_admin_users_page() {
    include plugin_dir_path(__FILE__) . 'pages/users.php';
}