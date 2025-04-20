<?php
/**
 * Plugin Name:       Courtly – Padel Court Booking
 * Plugin URI:        https://github.com/juangcarmona/courtly-wp-plugin
 * Description:       A modular plugin for managing padel court reservations using DDD principles.
 * Version:           0.0.1
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


require_once __DIR__ . '/Infrastructure/require_entities.php';

// -----------------------------------------------------------------------------
// Load plugin textdomain for internationalization
// -----------------------------------------------------------------------------
function courtly_load_textdomain() {
    $loaded = load_plugin_textdomain(
        'courtly',
        false,
        'courtly/languages'
    );
    

    if ($loaded) {
        error_log('✅ [Courtly] Textdomain "courtly" loaded successfully.');
    } else {
        error_log('❌ [Courtly] Failed to load textdomain "courtly".');
    }
}
add_action('init', 'courtly_load_textdomain');

// -----------------------------------------------------------------------------
// Load core infrastructure (dependency container, etc.)
// -----------------------------------------------------------------------------
require_once plugin_dir_path(__FILE__) . 'Infrastructure/CourtlyContainer.php';

// -----------------------------------------------------------------------------
// Load public routing BEFORE flush_rewrite_rules
// -----------------------------------------------------------------------------
require_once plugin_dir_path(__FILE__) . 'Infrastructure/Public/routes.php';
add_action('init', 'courtly_register_public_routes');       // Register rewrite rules early
add_filter('query_vars', 'courtly_add_query_vars');         // Add query var: courtly_reservation_id

// -----------------------------------------------------------------------------
// Activation logic (runs once on plugin activation)
// -----------------------------------------------------------------------------
require_once plugin_dir_path(__FILE__) . 'Infrastructure/activation.php';
register_activation_hook(__FILE__, function () {
    courtly_create_tables();
    courtly_seed_data();
    courtly_activation_notice_flag();
    courtly_create_booking_page(); 
    courtly_create_reservation_detail_page();
    courtly_create_general_calendar_page();
    flush_rewrite_rules();
});

// -----------------------------------------------------------------------------
// Show success notice after plugin activation
// -----------------------------------------------------------------------------
function courtly_show_activation_notice() {
    if (get_transient('courtly_activation_notice')) {
        echo '<div class="notice notice-success is-dismissible">
                <p>Courtly is now active and ready to use.</p>
              </div>';
        delete_transient('courtly_activation_notice');
    }
}
add_action('admin_notices', 'courtly_show_activation_notice');

function courtly_activation_notice_flag() {
    set_transient('courtly_activation_notice', true, 5);
}
register_activation_hook(__FILE__, 'courtly_activation_notice_flag');

// -----------------------------------------------------------------------------
// Public shortcodes (e.g. booking calendar)
// -----------------------------------------------------------------------------
require_once plugin_dir_path(__FILE__) . 'presentation/public/shortcode.php';

// -----------------------------------------------------------------------------
// Hide reservation detail page from automatic menus
// -----------------------------------------------------------------------------
add_filter('wp_get_nav_menu_items', function ($items) {
    return array_filter($items, function ($item) {
        return $item->object !== 'page' || get_post_meta($item->object_id, '_courtly_hidden_page', true) !== '1';
    });
});

// -----------------------------------------------------------------------------
// Admin dashboard logic and AJAX controllers
// -----------------------------------------------------------------------------
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/admin.php';

    require_once plugin_dir_path(__FILE__) . 'presentation/admin/controllers/AvailabilityAjaxController.php';
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/controllers/DashboardAjaxController.php';
    require_once plugin_dir_path(__FILE__) . 'presentation/admin/controllers/ReservationAjaxController.php';

    AvailabilityAjaxController::registerHooks();
    DashboardAjaxController::registerHooks();
    ReservationAjaxController::registerHooks();
}
