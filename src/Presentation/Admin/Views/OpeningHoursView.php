<?php
$options = $data['options'] ?? [];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>

<div class="wrap">
    <h1><?php echo esc_html(__('Opening Hours', 'courtly')); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('save_opening_hours', 'opening_hours_nonce'); ?>

        <table class="form-table">
            <?php foreach ($openingHours as $hour): ?>
                <tr>
                    <th scope="row">
                        <?php echo esc_html($hour['day_of_week']); ?>
                    </th>
                    <td>
                        <input type="text" name="opening_hours[<?php echo esc_attr($hour['day_of_week']); ?>][time_ranges]" value="<?php echo esc_attr(json_encode($hour['time_ranges'])); ?>">
                        <label>
                            <input type="checkbox" name="opening_hours[<?php echo esc_attr($hour['day_of_week']); ?>][closed]" value="1" <?php checked($hour['closed'], true); ?>>
                            <?php esc_html_e('Closed', 'courtly'); ?>
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p class="submit">
            <button type="submit" class="button button-primary">Save Changes</button>
        </p>
    </form>
</div>
