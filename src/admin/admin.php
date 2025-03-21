<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'courtly_add_admin_menu');

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

// Dispatch to actual pages
function courtly_admin_courts_page() {
    include plugin_dir_path(__FILE__) . 'pages/courts.php';
}

function courtly_admin_user_types_page() {
    include plugin_dir_path(__FILE__) . 'pages/user-types.php';
}

function courtly_admin_users_page() {
    include plugin_dir_path(__FILE__) . 'pages/users.php';
}

function courtly_admin_page() {
    ?>
    <div class="wrap">
        <h1>Courtly Settings</h1>
        <p>Welcome to Courtly! Configure your padel court booking settings here.</p>
    </div>
    <?php
}
