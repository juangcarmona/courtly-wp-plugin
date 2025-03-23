<?php
/**
 * Plugin Name:       Courtly - Padel Court Booking
 * Plugin URI:        https://github.com/juangcarmona/courtly-wp-plugin
 * Description:       A WordPress plugin for managing padel court reservations.
 * Version:           1.0.0
 * Author:            Juan G. Carmona
 * Author URI:        https://jgcarmona.com
 * License:           Apache License 2.0
 * License URI:       https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       courtly
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Tested up to:      6.3
 * Stable tag:        1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Activation hook
require_once plugin_dir_path(__FILE__) . 'infrastructure/activation.php';
register_activation_hook(__FILE__, 'courtly_create_tables');

// Load public-facing shortcode
require_once plugin_dir_path(__FILE__) . 'presentation/public/shortcode.php';

// Load admin area
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/admin.php';

    // explicitly load AJAX handlers
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/ajax/availability.php';
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/ajax/dashboard.php';
}

// Admin activation notice
function courtly_hello_world_admin_notice() {
    if (get_transient('courtly_activation_notice')) {
        echo '<div class="notice notice-success is-dismissible">
                <p>Hello Courtly! Your plugin is now active.</p>
              </div>';
        delete_transient('courtly_activation_notice');
    }
}
add_action('admin_notices', 'courtly_hello_world_admin_notice');

function courtly_activation_notice() {
    set_transient('courtly_activation_notice', true, 5);
}
register_activation_hook(__FILE__, 'courtly_activation_notice');
