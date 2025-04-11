<?php
/**
 * Plugin Name:       Courtly – Padel Court Booking
 * Plugin URI:        https://github.com/juangcarmona/courtly-wp-plugin
 * Description:       A modular plugin for managing padel court reservations using DDD principles.
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

// Load infrastructure and dependency container (always available)
require_once plugin_dir_path(__FILE__) . 'infrastructure/CourtlyContainer.php';

// Setup activation logic (create tables, defaults, etc.)
require_once plugin_dir_path(__FILE__) . 'infrastructure/activation.php';
register_activation_hook(__FILE__, function () {
    courtly_create_tables();
    courtly_seed_data();
    courtly_activation_notice_flag();
});

// Register shortcode for public-facing booking interface
require_once plugin_dir_path(__FILE__) . 'presentation/public/shortcode.php';

// Load admin interface and AJAX handlers only when in admin context
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/admin.php';

    require_once plugin_dir_path(__FILE__) . 'presentation/admin/controllers/AvailabilityAjaxController.php';
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/controllers/DashboardAjaxController.php';
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/controllers/ReservationAjaxController.php';
    
    AvailabilityAjaxController::registerHooks();
    DashboardAjaxController::registerHooks();
    ReservationAjaxController::registerHooks();
}

// Display admin notice after activation
function courtly_show_activation_notice() {
    if (get_transient('courtly_activation_notice')) {
        echo '<div class="notice notice-success is-dismissible">
                <p>Courtly is now active and ready to use.</p>
              </div>';
        delete_transient('courtly_activation_notice');
    }
}
add_action('admin_notices', 'courtly_show_activation_notice');

// Store flag to show activation notice
function courtly_activation_notice_flag() {
    set_transient('courtly_activation_notice', true, 5);
}
register_activation_hook(__FILE__, 'courtly_activation_notice_flag');
