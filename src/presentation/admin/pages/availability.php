<?php
global $wpdb;
$prefix = $wpdb->prefix;
$courts = $wpdb->get_results("SELECT * FROM {$prefix}courtly_courts");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_update_availability')) {
    $court_id = intval($_POST['court_id']);
    $opening_time = sanitize_text_field($_POST['opening_time']);
    $closing_time = sanitize_text_field($_POST['closing_time']);

    // Update opening hours
    $wpdb->query($wpdb->prepare("
        INSERT INTO {$prefix}courtly_availability (court_id, day_of_week, start_time, end_time, is_blocked)
        VALUES (%d, -1, %s, %s, 0)
        ON DUPLICATE KEY UPDATE start_time = VALUES(start_time), end_time = VALUES(end_time)
    ", $court_id, $opening_time, $closing_time));

    echo '<div class="notice notice-success is-dismissible"><p>Availability updated successfully.</p></div>';
}

// Fetch opening hours
$selected_court_id = $_GET['court_id'] ?? ($courts[0]->id ?? null);
$opening_hours = $wpdb->get_row($wpdb->prepare("
    SELECT start_time, end_time FROM {$prefix}courtly_availability 
    WHERE court_id = %d AND day_of_week = -1
", $selected_court_id));
?>

<div class="wrap">
    <h1>Manage Court Availability</h1>

    <!-- Court Selection -->
    <form method="GET">
        <input type="hidden" name="page" value="courtly_availability">
        <label for="court_id">Select Court:</label>
        <select name="court_id" onchange="this.form.submit()">
            <?php foreach ($courts as $court): ?>
                <option value="<?= esc_attr($court->id) ?>" <?= selected($selected_court_id, $court->id) ?>>
                    <?= esc_html($court->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <hr>

    <!-- Opening Hours -->
    <h2>Opening Hours</h2>
    <form method="POST">
        <?php wp_nonce_field('courtly_update_availability'); ?>
        <input type="hidden" name="court_id" value="<?= esc_attr($selected_court_id) ?>">
        <label for="opening_time">From:</label>
        <input type="time" name="opening_time" value="<?= esc_attr($opening_hours->start_time ?? '09:00') ?>" required>
        <label for="closing_time">To:</label>
        <input type="time" name="closing_time" value="<?= esc_attr($opening_hours->end_time ?? '21:00') ?>" required>
        <input type="submit" class="button button-primary" value="Save">
    </form>

    <hr>

    <!-- Weekly Availability Calendar -->
    <h2>Weekly Availability</h2>
    <div id="courtly-calendar"></div>
</div>

<style>
    #courtly-calendar { margin-top: 20px; }
</style>
