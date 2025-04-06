<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register shortcode
add_shortcode('courtly_general_calendar', function () {
    wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js', [], false, true);
    wp_enqueue_script('courtly-general-calendar',
        plugin_dir_url(__FILE__) . 'js/public-availability-calendar.js',
        ['fullcalendar-js'], false, true
    );
    wp_enqueue_style('courtly-calendar-css',
        plugin_dir_url(__DIR__) . '/../shared/calendar/calendar.css'
    );

    add_filter('script_loader_tag', function ($tag, $handle, $src) {
        if ($handle === 'courtly-general-calendar') {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }, 10, 3);

    return '<div id="courtly-calendar" style="margin-bottom: 50px;"></div>';
});

add_shortcode('courtly_user_booking', function () {
    if (!is_user_logged_in()) {
        return '<div class="courtly-booking-login"><p>Please <a href="' . wp_login_url() . '">log in</a> to book a court.</p></div>';
    }

    wp_enqueue_script('fullcalendar-js', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js', [], false, true);
    wp_enqueue_script('courtly-user-booking-calendar',
        plugin_dir_url(__FILE__) . 'js/public-user-booking-calendar.js',
        ['fullcalendar-js'], false, true
    );
    wp_enqueue_style('courtly-calendar-css',
        plugin_dir_url(__DIR__) . '/../shared/calendar/calendar.css'
    );

    add_filter('script_loader_tag', function ($tag, $handle, $src) {
        if ($handle === 'courtly-user-booking-calendar') {
            return '<script type="module" src="' . esc_url($src) . '"></script>';
        }
        return $tag;
    }, 10, 3);

    ob_start();
    ?>
    <div class="courtly-user-booking">
        <h2>Reserve Your Court</h2>
        <p><strong>Selected Slot:</strong> <span id="courtly-summary">None</span></p>

        <div id="courtly-calendar" style="margin-bottom: 50px;"></div>
    </div>
    <?php
    wp_localize_script('courtly-user-booking-calendar', 'courtlyCurrentUser', [
        'ID' => get_current_user_id(),
        'display_name' => wp_get_current_user()->display_name,
        'user_type' => get_user_meta(get_current_user_id(), 'courtly_user_type', true),
    ]);
    return ob_get_clean();
});

