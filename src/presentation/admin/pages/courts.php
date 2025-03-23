<?php
global $wpdb;
$table = $wpdb->prefix . 'courtly_courts';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_add_court')) {
    $name = sanitize_text_field($_POST['name']);
    $number = intval($_POST['number']);
    $description = sanitize_textarea_field($_POST['description']);

    if ($name && $number) {
        $wpdb->insert($table, [
            'name' => $name,
            'number' => $number,
            'description' => $description,
        ]);
        echo '<div class="notice notice-success is-dismissible"><p>Court added successfully.</p></div>';
    }
}

$courts = $wpdb->get_results("SELECT * FROM $table ORDER BY number ASC");
?>

<div class="wrap">
    <h1>Courts</h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_add_court'); ?>
        <h2>Add New Court</h2>
        <table class="form-table">
            <tr><th><label for="number">Court Number</label></th><td><input type="number" name="number" required /></td></tr>
            <tr><th><label for="name">Name</label></th><td><input type="text" name="name" required /></td></tr>
            <tr><th><label for="description">Description</label></th><td><textarea name="description" rows="3"></textarea></td></tr>
        </table>
        <p><input type="submit" class="button button-primary" value="Add Court"></p>
    </form>

    <hr>

    <h2>Existing Courts</h2>
    <table class="widefat fixed">
        <thead><tr><th>ID</th><th>Number</th><th>Name</th><th>Description</th><th>Created</th></tr></thead>
        <tbody>
        <?php foreach ($courts as $court): ?>
            <tr>
                <td><?= esc_html($court->id) ?></td>
                <td><?= esc_html($court->number) ?></td>
                <td><?= esc_html($court->name) ?></td>
                <td><?= esc_html($court->description) ?></td>
                <td><?= esc_html($court->created_at) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
