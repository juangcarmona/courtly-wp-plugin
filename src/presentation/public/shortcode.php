<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register shortcode
add_shortcode('courtly_booking', 'courtly_render_booking');

function courtly_render_booking() {
    return '<div class="courtly-booking">
                <h2>Book Your Padel Court</h2>
                <p>Coming soon...</p>
            </div>';
}


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
