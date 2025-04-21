<div class="wrap">
    <h1><?php esc_html_e('User Types', 'courtly'); ?></h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_add_user_type'); ?>
        <h2><?php esc_html_e('Add New User Type', 'courtly'); ?></h2>
        <table class="form-table">
            <tr>
                <th><label for="name"><?php esc_html_e('Internal Name', 'courtly'); ?></label></th>
                <td><input type="text" name="name" required /></td>
            </tr>
            <tr>
                <th><label for="display_name"><?php esc_html_e('Display Name', 'courtly'); ?></label></th>
                <td><input type="text" name="display_name" required /></td>
            </tr>
            <tr>
                <th><label for="booking_days_in_advance"><?php esc_html_e('Booking Days in Advance', 'courtly'); ?></label></th>
                <td><input type="number" name="booking_days_in_advance" min="1" required /></td>
            </tr>
        </table>
        <p>
            <input type="submit" class="btn btn-sm btn-primary" value="<?php esc_attr_e('Add User Type', 'courtly'); ?>">
        </p>
    </form>

    <hr>

    <h2><?php esc_html_e('Existing User Types', 'courtly'); ?></h2>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th><?php esc_html_e('ID', 'courtly'); ?></th>
                <th><?php esc_html_e('Internal', 'courtly'); ?></th>
                <th><?php esc_html_e('Display', 'courtly'); ?></th>
                <th><?php esc_html_e('Days in Advance', 'courtly'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data['user_types'] as $type): ?>
            <tr>
                <td><?= esc_html($type->getId()) ?></td>
                <td><?= esc_html($type->getName()) ?></td>
                <td><?= esc_html($type->getDisplayName()) ?></td>
                <td><?= esc_html($type->booking_days_in_advance) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
