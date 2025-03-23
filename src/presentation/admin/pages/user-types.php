<?php
global $wpdb;
$table = $wpdb->prefix . 'courtly_user_types';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_add_user_type')) {
    $name = sanitize_title($_POST['name']);
    $display_name = sanitize_text_field($_POST['display_name']);
    $days = intval($_POST['booking_days_in_advance']);

    if ($name && $display_name && $days > 0) {
        $wpdb->insert($table, [
            'name' => $name,
            'display_name' => $display_name,
            'booking_days_in_advance' => $days,
        ]);
        echo '<div class="notice notice-success is-dismissible"><p>User type added successfully.</p></div>';
    }
}

$user_types = $wpdb->get_results("SELECT * FROM $table ORDER BY booking_days_in_advance DESC");
?>

<div class="wrap">
    <h1>User Types</h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_add_user_type'); ?>
        <h2>Add New User Type</h2>
        <table class="form-table">
            <tr>
                <th><label for="name">Internal Name</label></th>
                <td><input type="text" name="name" required /></td>
            </tr>
            <tr>
                <th><label for="display_name">Display Name</label></th>
                <td><input type="text" name="display_name" required /></td>
            </tr>
            <tr>
                <th><label for="booking_days_in_advance">Booking Days in Advance</label></th>
                <td><input type="number" name="booking_days_in_advance" required min="1" max="30" /></td>
            </tr>
        </table>
        <p><input type="submit" class="button button-primary" value="Add User Type" /></p>
    </form>

    <hr>

    <h2>Existing User Types</h2>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Internal Name</th>
                <th>Display Name</th>
                <th>Advance Days</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user_types as $type): ?>
                <tr>
                    <td><?= esc_html($type->id) ?></td>
                    <td><?= esc_html($type->name) ?></td>
                    <td><?= esc_html($type->display_name) ?></td>
                    <td><?= esc_html($type->booking_days_in_advance) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
