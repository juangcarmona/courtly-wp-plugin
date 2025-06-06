<div class="wrap">
    <h1><?php esc_html_e('Users', 'courtly'); ?></h1>
    <form method="POST">
        <?php wp_nonce_field('courtly_update_user_types'); ?>
        <table class="widefat fixed">
            <thead>
                <tr>
                    <th><?php esc_html_e('User', 'courtly'); ?></th>
                    <th><?php esc_html_e('Email', 'courtly'); ?></th>
                    <th><?php esc_html_e('User Type', 'courtly'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $user): 
                    $current = get_user_meta($user->ID, 'courtly_user_type', true);
                ?>
                    <tr>
                        <td><?= esc_html($user->display_name); ?></td>
                        <td><?= esc_html($user->user_email); ?></td>
                        <td>
                            <select name="user_type[<?= esc_attr($user->ID); ?>]">
                                <option value=""><?php esc_html_e('-- Select --', 'courtly'); ?></option>
                                <?php foreach ($data['user_types'] as $type): ?>
                                    <option value="<?= esc_attr($type->getName()); ?>" <?php selected($current, $type->getName()); ?>>
                                        <?= esc_html($type->getDisplayName()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>
            <input type="submit" class="btn btn-sm btn-success" value="<?php esc_attr_e('Save Changes', 'courtly'); ?>">
        </p>
    </form>
</div>
