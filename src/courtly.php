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

require_once plugin_dir_path(__FILE__) . 'public/shortcode.php';

require_once plugin_dir_path(__FILE__) . 'activation.php';
register_activation_hook(__FILE__, 'courtly_create_tables');

// Load admin area
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin/ajax.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin.php';
}

// Show admin notice only once after activation
function courtly_hello_world_admin_notice() {
    if (get_transient('courtly_activation_notice')) {
        echo '<div class="notice notice-success is-dismissible">
                <p>Hello Courtly! Your plugin is now active.</p>
              </div>';
        delete_transient('courtly_activation_notice'); // Remove it after showing
    }
}
add_action('admin_notices', 'courtly_hello_world_admin_notice');

// Set transient when plugin is activated
function courtly_activation_notice() {
    set_transient('courtly_activation_notice', true, 5); // Show for 5 seconds
}
register_activation_hook(__FILE__, 'courtly_activation_notice');
