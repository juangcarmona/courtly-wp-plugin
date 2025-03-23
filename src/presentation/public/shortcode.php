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
