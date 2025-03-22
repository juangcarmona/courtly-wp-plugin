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

    add_submenu_page(
        'courtly_settings',
        'Court Availability',
        'Availability',
        'manage_options',
        'courtly_availability',
        'courtly_admin_availability_page'
    );
}

function courtly_enqueue_admin_scripts($hook) {
    if (strpos($hook, 'courtly') === false) {
        return;
    }

    wp_enqueue_style('courtly-bootstrap-css', 'https://bootswatch.com/5/minty/bootstrap.min.css');
    wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js', [], false, true);
    wp_enqueue_style('courtly-calendar-css', plugin_dir_url(__FILE__) . 'css/admin-calendar.css');

    add_filter('script_loader_tag', 'courtly_mark_script_as_module', 10, 3);

    if ($hook === 'toplevel_page_courtly_settings') {
        wp_enqueue_script(
            'courtly-dashboard-calendar',
            plugin_dir_url(__FILE__) . 'js/dashboard-calendar.js',
            ['jquery', 'fullcalendar-js'],
            false,
            true
        );

        wp_localize_script('courtly-dashboard-calendar', 'courtlyAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);
    }

    if ($hook === 'courtly_page_courtly_availability') {
        wp_enqueue_script(
            'courtly-availability-calendar',
            plugin_dir_url(__FILE__) . 'js/availability-calendar.js',
            ['jquery', 'fullcalendar-js'],
            false,
            true
        );

        $court_id = isset($_GET['court_id']) ? intval($_GET['court_id']) : 1;

        wp_localize_script('courtly-availability-calendar', 'courtlyAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'court_id' => $court_id
        ]);
    }
}

function courtly_mark_script_as_module($tag, $handle, $src) {
    if (in_array($handle, ['courtly-dashboard-calendar', 'courtly-availability-calendar'])) {
        return '<script type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
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

function courtly_admin_availability_page() {
    include plugin_dir_path(__FILE__) . 'pages/availability.php';
}
