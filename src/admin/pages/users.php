<?php
global $wpdb;
$user_types = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}courtly_user_types");
$users = get_users();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('courtly_update_user_types')) {
    foreach ($_POST['user_type'] as $user_id => $type) {
        update_user_meta($user_id, 'courtly_user_type', sanitize_text_field($type));
    }
    echo '<div class="notice notice-success is-dismissible"><p>User types updated.</p></div>';
}
?>

<div class="wrap">
    <h1>Users</h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_update_user_types'); ?>
        <table class="widefat fixed">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>User Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): 
                    $current = get_user_meta($user->ID, 'courtly_user_type', true);
                ?>
                    <tr>
                        <td><?= esc_html($user->display_name); ?></td>
                        <td><?= esc_html($user->user_email); ?></td>
                        <td>
                            <select name="user_type[<?= esc_attr($user->ID); ?>]">
                                <option value="">-- Select --</option>
                                <?php foreach ($user_types as $type): ?>
                                    <option value="<?= esc_attr($type->name); ?>" <?php selected($current, $type->name); ?>>
                                        <?= esc_html($type->display_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><input type="submit" class="button button-primary" value="Save Changes" /></p>
    </form>
</div>
