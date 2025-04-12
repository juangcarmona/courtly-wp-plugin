<?php
/**
 * Courtly – Public Routing Setup
 * Handles pretty permalinks and detail page creation.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registers the custom rewrite rule:
 * e.g., /reservation/17 → index.php?courtly_reservation_id=17
 */
function courtly_register_public_routes() {
    $slug = get_option('courtly_reservation_detail_slug', 'reservation');

    add_rewrite_rule(
        "^$slug/([0-9]+)/?$",
        'index.php?courtly_reservation_id=$matches[1]',
        'top'
    );
}

/**
 * Registers courtly_reservation_id as a valid query var.
 */
function courtly_add_query_vars($vars) {
    $vars[] = 'courtly_reservation_id';
    return $vars;
}

/**
 * Creates a public page with the reservation detail shortcode.
 * Called on plugin activation. Stores slug and ID in options.
 */
function courtly_create_reservation_detail_page() {
    $slug = 'reservation';
    $title = 'Reservation';
    $shortcode = '[courtly_reservation_detail]';

    // Check if it already exists
    $existing = get_page_by_path($slug);

    if (!$existing) {
        // Insert new page
        $page_id = wp_insert_post([
            'post_title'     => $title,
            'post_name'      => $slug,
            'post_content'   => $shortcode,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'menu_order'     => -100, // Push to bottom of menu if automatic
        ]);

        if ($page_id && !is_wp_error($page_id)) {
            update_option('courtly_reservation_detail_slug', $slug);
            update_option('courtly_reservation_detail_page_id', $page_id);
            update_post_meta($page_id, '_courtly_hidden_page', '1');
        }
    } else {
        update_option('courtly_reservation_detail_slug', $existing->post_name);
        update_option('courtly_reservation_detail_page_id', $existing->ID);
        update_post_meta($existing->ID, '_courtly_hidden_page', '1');
    }
}