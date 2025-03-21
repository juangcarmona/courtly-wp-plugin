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
    require_once plugin_dir_path(__FILE__) . 'admin/admin.php';
}

// Display a simple admin notice when the plugin is activated
function courtly_hello_world_admin_notice() {
    echo '<div class="notice notice-success is-dismissible">
            <p>Hello Courtly! Your plugin is now active.</p>
          </div>';
}
add_action('admin_notices', 'courtly_hello_world_admin_notice');
